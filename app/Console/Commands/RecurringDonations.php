<?php

namespace App\Console\Commands;

use App\Donation;
use Illuminate\Console\Command;

class RecurringDonations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'donations:recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update recurring donations with total-to-date and sends receipt email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $count = 0;
        $total = 0;

        $donations = Donation::where('recurring', 'YES')->whereNull('cancelled_on')->where('updated_at', '>=', date('Y-m-d 0:00', strtotime('-1 month')))->where('updated_at', '<=', date('Y-m-d 23:59', strtotime('-1 month')))->get();

        foreach ($donations as $donation) {
            $donation->amount += $donation->recurring_amount;
            $total += $donation->recurring_amount;
            $donation->save();
            \Mail::to($donation->email)->send(new \App\Mail\RecurringDonation($donation->recurring_amount, $donation->fname));
            $count++;
        }

        echo "$count donations made totaling $$total\n";
    }
}
