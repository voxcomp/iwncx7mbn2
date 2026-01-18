<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportsController extends Controller
{
    /**
     * Create a new controller instance.  Entire controller requires logged in admin
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function reports(): View
    {
        return view('reports.reports', ['events' => Event::all()]);
    }

    public function getGeneralReports(Request $request)
    {
        $this->validate($request, [
            'report' => 'required',
            'start_date' => 'nullable|date|date_format:m/d/Y',
            'end_date' => 'nullable|date|after:start_date|date_format:m/d/Y',
        ]);
        $crit = $request->all();
        $start = '1900-01-01 00:01';
        $end = date('Y-m-d 23:59', strtotime('+1 year'));
        if (! $crit['all']) {
            $start = date('Y-m-d 23:59', strtotime($crit['start_date']));
            $end = date('Y-m-d 23:59', strtotime($crit['end_date']));
        }
        switch ($request->report) {
            case 'donations': return $this->generalDonations($start, $end);
        }
    }

    public function getReports(Request $request)
    {
        $this->validate($request, [
            'event' => 'required|integer',
            'report' => 'required',
        ]);

        $event = Event::where('id', $request->event)->first();
        switch ($request->report) {
            case 'sponsors': return $this->sponsors($event);
            case 'registrants': return $this->registrants($event);
            case 'teams': return $this->teams($event);
            case 'donations': return $this->donations($event);
            case 'progress': return $this->progress($event);
        }
    }

    private function sponsors(Event $event)
    {
        if (is_null($event->sponsors)) {
            return \Redirect::route('reports')->with('message', 'There are no sponsors for the '.$event->title.' event.');
        }

        return \Excel::create('sponsors-'.$event->slug.'-'.uniqid(), function ($excel) use ($event) {
            $excel->sheet('Sponsor-Vendor', function ($sheet) use ($event) {
                $sponsors = $event->sponsorSubmissions;
                $sheet->loadView('reports.sponsors', compact('sponsors'));
            });
        })->download('xlsx');
    }

    private function registrants(Event $event)
    {
        if (is_null($event->participants)) {
            return \Redirect::route('reports')->with('message', 'There are no participants signed up for the '.$event->title.' event.');
        }

        return \Excel::create('participants-'.$event->slug.'-'.uniqid(), function ($excel) use ($event) {
            $excel->sheet('Participants', function ($sheet) use ($event) {
                $registrants = $event->participants->sortBy('id');
                foreach ($registrants as &$registrant) {
                    try {
                        $teamname = ($registrant->teams->count()) ? $registrant->teams->filter(function ($value, $key) use ($event) {
                            return $value->team->event_id == $event->id;
                        })->first()->team->name : '';
                    } catch (\Exception $e) {
                        $teamname = '';
                    }
                    $registrant->teamname = $teamname;
                }
                $sheet->loadView('reports.registrants', compact('registrants', 'event'));
            });
        })->download('xlsx');
    }

    private function teams(Event $event)
    {
        if (is_null($event->teams)) {
            return \Redirect::route('reports')->with('message', 'There are no teams signed up for the '.$event->title.' event.');
        }

        return \Excel::create('teams-'.$event->slug.'-'.uniqid(), function ($excel) use ($event) {
            $excel->sheet('Teams', function ($sheet) use ($event) {
                $teams = $event->teams;
                $sheet->loadView('reports.teams', compact('teams'));
            });
        })->download('xlsx');
    }

    private function donations(Event $event)
    {
        if (is_null($event->donations)) {
            return \Redirect::route('reports')->with('message', 'There are no donations for the '.$event->title.' event.');
        }

        return \Excel::create('donations-'.$event->slug.'-'.uniqid(), function ($excel) use ($event) {
            $excel->sheet('Donors', function ($sheet) use ($event) {
                $donors = $event->donations;
                $sheet->loadView('reports.donations', compact('donors'));
            });
        })->download('xlsx');
    }

    private function generalDonations($start, $end)
    {
        if (Donation::where('created_at', '>=', $start)->where('created_at', '<=', $end)->where('event_id', 0)->count() <= 0) {
            return \Redirect::route('reports')->with('message', 'There are no general donations.');
        }

        return \Excel::create('donations-general-'.uniqid(), function ($excel) use ($start, $end) {
            $excel->sheet('Donors', function ($sheet) use ($start, $end) {
                $donors = Donation::where('created_at', '>=', $start)->where('created_at', '<=', $end)->where('event_id', 0)->get();
                $sheet->loadView('reports.donations', compact('donors'));
            });
        })->download('xlsx');
    }

    private function progress(Event $event)
    {
        return \Excel::create('progress-'.$event->slug.'-'.uniqid(), function ($excel) use ($event) {
            $excel->sheet('Progress Report', function ($sheet) use ($event) {
                $registrants = \DB::table('registrants')->select(\DB::raw('count(*) as registrant_count, date(created_at) as created'))->where('event_id', $event->id)->groupBy('created')->get()->pluck('registrant_count', 'created')->toArray();
                $teams = \DB::table('teams')->select(\DB::raw('count(*) as team_count, date(created_at) as created'))->where('event_id', $event->id)->groupBy('created')->get()->pluck('team_count', 'created')->toArray();
                $donations = \DB::table('donations')->select(\DB::raw('sum(amount) as total, date(created_at) as created'))->where('event_id', $event->id)->groupBy('created')->get()->pluck('total', 'created')->toArray();
                $registrations = \DB::table('registrants')->select(\DB::raw('sum(paid) as total, date(created_at) as created'))->where('event_id', $event->id)->groupBy('created')->get()->pluck('total', 'created')->toArray();

                $dates = array_merge(array_keys($registrants), array_keys($teams), array_keys($donations), array_keys($registrations));
                $dates = array_unique($dates);
                sort($dates);

                $sheet->loadView('reports.progress', compact('dates', 'registrants', 'teams', 'donations', 'registrations'));
            });
        })->download('xlsx');
    }
}
