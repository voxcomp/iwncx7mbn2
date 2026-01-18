<?php

namespace App\Console\Commands;

use App\Models\Registrant;
use App\Models\Team;
use Illuminate\Console\Command;

class PPReviewAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'personalpage:review';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifies an administrator via email there are personal pages waiting for review';

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
        $count = Registrant::where('moderated', 0)->where(function ($query) {
            $query->whereNotNull('pagetitle')->Where('pagetitle', '<>', '');
        })->count();

        $count += Team::where('moderated', 0)->where(function ($query) {
            $query->whereNotNull('pagetitle')->Where('pagetitle', '<>', '');
        })->count();

        if ($count > 0) {
            $emails = explode(',', env('ADMIN_EMAIL'));
            \Mail::to($emails)->send(new \App\Mail\ModeratePersonalPages($count));
        }
    }
}
