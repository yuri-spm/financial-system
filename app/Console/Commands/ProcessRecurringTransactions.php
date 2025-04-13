<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Transaction;

class ProcessRecurringTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:process-recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process recurring transactions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        $recurringTransactions = Transaction::where('is_recurring', true)
            ->where('next_occurrence', '<=', $today)
            ->get();

        foreach ($recurringTransactions as $transaction) {
            $newTransaction = $transaction->replicate();
            $newTransaction->transaction_date = $today;
            $newTransaction->next_occurrence = null;
            $newTransaction->save();

            $transaction->next_occurrence = Carbon::parse($transaction->next_occurrence)->addMonth();
            $transaction->save();

            $this->info("Processed transaction ID: {$transaction->id}");
        }

        $this->info("Processed concluded transactions.");
    }
}
