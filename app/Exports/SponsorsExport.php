<?php

namespace App\Exports;

use App\Event;
use App\SponsorSubmission;
use Maatwebsite\Excel\Concerns\FromView;

class SponsorsExport implements FromView
{
    public function view(?Event $event = null)
    {
        return view('exports.sponsors', [
            'sponsors' => SponsorSubmission::all(),
        ]);
    }
}
