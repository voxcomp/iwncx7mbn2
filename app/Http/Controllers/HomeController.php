<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Event;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::check() && \Auth::user()->isAdmin()) {
            $events = \App\Models\Event::orderBy('event_date', 'DESC')->limit(1)->get();
        } else {
            $events = \App\Models\Event::where('event_date', '>', time())->get();
        }

        if ($events->count() == 1) {
            $event = $events->first();

            return \Redirect::route('event.view', [$event->slug]);
        } else {
            return view('welcome', compact('events'));
        }
    }

    public function test()
    {
        $event = Event::where('id', 7)->first();
        dd($event->raised());
    }

    public function unauthorized(): View
    {
        return view('pages.unauthorized');
    }

    public function pagePromise(): View
    {
        $donations = Donation::where('promise', 'yes')->where('photo', '<>', '')->orderBy('id', 'DESC')->get();

        return view('pages.promisewall', compact('donations'));
    }

    public function pagePromiseConfirm(): View
    {
        return view('pages.donatepromiseconfirm');
    }
}
