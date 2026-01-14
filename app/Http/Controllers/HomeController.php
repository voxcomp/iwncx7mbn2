<?php

namespace App\Http\Controllers;

use App\Donation;
use App\Event;

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
            $events = \App\Event::orderBy('event_date', 'DESC')->limit(1)->get();
        } else {
            $events = \App\Event::where('event_date', '>', time())->get();
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

    public function unauthorized()
    {
        return view('pages.unauthorized');
    }

    public function pagePromise()
    {
        $donations = Donation::where('promise', 'yes')->where('photo', '<>', '')->orderBy('id', 'DESC')->get();

        return view('pages.promisewall', compact('donations'));
    }

    public function pagePromiseConfirm()
    {
        return view('pages.donatepromiseconfirm');
    }
}
