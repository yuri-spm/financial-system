<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Account;
use Filament\Forms\Get;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transactions;
use App\TransactionsTypeEnum;
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Resources\Components\Tab;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TransactionsResource\Pages;


class TransactionsResource extends Resource
{
    protected static ?string $model = Transactions::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Despesas';

    protected static ?string $navigationLabel = 'Transações';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Nome da Despesa')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('amount')
                    ->label('Valor')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('type')
                     ->label('Tipo de conta')
                     ->options(
                     TransactionsTypeEnum::class
                   )
                    ->searchable()
                    ->preload()
                     ->required(),
                Forms\Components\DatePicker::make('transaction_date')
                    ->label('Data da Transação')
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->required(),
                Forms\Components\Select::make('category_id')
                     ->label('Categoria')
                     ->options(Category::all()->pluck('name', 'id'))
                     ->searchable(),
                Forms\Components\Select::make('user_id')
                     ->relationship(name: 'user', titleAttribute: 'name')
                     ->native(false)
                     ->searchable()
                     ->preload()
                     ->required()
                     ->live(onBlur: true)
                     ->afterStateUpdated(function (callable $set) {
                         $set('account_id', null);
                     }),
                 Forms\Components\Select::make('account_id')
                     ->label('Conta')
                     ->options(function (callable $get) {
                         $userId = $get('user_id'); 
                 
                         return Account::query()
                             ->where('user_id', $userId) 
                             ->pluck('name', 'id');
                     })
                     ->native(false)
                     ->searchable()
                     ->required(),
                Forms\Components\Toggle::make('is_recurring')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Descrição')
                    ->columnSpanFull(),
                FileUpload::make('attachment')
                    ->label("Comprovante")
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Valor')
                    ->numeric(locale: 'pt_br')
                    ->money('BRL')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->formatStateUsing(fn ($state) 
                        => $state ? TransactionsTypeEnum::from($state)->getLabel() : '-'),
                Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Data da Transação')
                    ->date('d/m/Y')
                    ->sortable()

                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Categoria')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('account.name')
                    ->label('Conta')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuário')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_recurring')
                    ->label('Recorrente')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('attachment')
                    ->label("Comprovante")
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('Tipo de conta')
                ->relationship('account', 'name')
                ->searchable()
                ->preload()
                ->label('Conta')
                ->indicator('Conta'),

              SelectFilter::make('Tipo de categoria')
                ->relationship('category', 'name')
                ->searchable()
                ->preload()     
                ->label('Categoria')
                ->indicator('Tipo de categoria'),

            Filter::make('transaction_date')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Data inicial')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        DatePicker::make('created_until')
                            ->label('Data inicial')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('transaction_date', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('transaction_date', '<=', $date),
                            );
    })

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('clone')
                    ->label('Clonar')
                    ->action(function(Transactions $record){
                        $newTransaction = $record->replicate();
                        $newTransaction->transaction_date = now();
                        $newTransaction->save();
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }  

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTransactions::route('/'),
        ];
    }
}
