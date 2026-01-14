<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Event;
use App\Sponsor;
use App\SponsorSubmission;
use Illuminate\Http\Request;

class SponsorsController extends Controller
{
    public function sponsor(Event $event)
    {
        // return view('sponsors.closed',compact('event'));
        return view('sponsors.form', compact('event'));
    }

    public function saveSubmission(Request $request, Event $event)
    {
        $this->validate($request, [
            'company' => 'nullable|string|max:150',
            'fname' => 'required|string|max:50',
            'lname' => 'required|string|max:75',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:200',
            'city' => 'required|string|max:75',
            'state' => 'required|string|max:2',
            'zip' => 'required|string|max:10',
            'image' => 'mimes:png,jpg,jpeg|dimensions:min_width=150,min_height=75|max:1024',
            'inkind_value' => 'integer',
        ]);

        $create = [
            'event_id' => $event->id,
            'company' => $request->company,
            'fname' => $request->fname,
            'lname' => $request->lname,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'level' => $request->level,
            'paid' => $this->levelCost($request->level),
            'paytype' => 'credit',
        ];

        if (\Auth::check() && \Auth::user()->isAdmin()) {
            $create['paytype'] = (isset($request->nopayment)) ? 'cash/check' : 'credit';
            if ($request->level == 'In-Kind') {
                $create['inkind_value'] = $request->inkindvalue;
            }
        }

        // check if new image uploaded and save it

        if (! is_null($request->image)) {
            $path = $request->file('image')->store('temp');
            $create['image'] = $path;
        } else {
            $create['image'] = '';
        }

        if ($create['paid'] == 0 || isset($request->nopayment)) {
            $sponsor = SponsorSubmission::create($create);
            $emails = explode(',', env('SPONSOR_EMAIL'));
            \Mail::to($emails)->send(new \App\Mail\SponsorAdmin($sponsor, $event));

            return \Redirect::route('event.view', $event->slug)->with('message', 'The sponsor has been saved.');
        }

        session()->put('sponsor', $create);

        return view('sponsors.pay', ['event' => $event, 'cost' => $this->levelCost($request->level), 'stripe_pk' => ((env('STRIPE_MODE', 'test') == 'live') ? env('STRIPE_PK') : env('STRIPE_TEST_PK'))]);
    }

    public function payment(Request $request, Event $event)
    {
        if ($request->session()->has('sponsor')) {
            $create = session()->get('sponsor');

            if (env('STRIPE_MODE', 'live') == 'live') {
                \Stripe\Stripe::setApiKey(((env('STRIPE_MODE', 'live') == 'live') ? env('STRIPE_SK') : env('STRIPE_TEST_SK')));

                \Stripe\Charge::create([
                    'amount' => $create['paid'] * 100,
                    'currency' => 'usd',
                    'source' => $request->stripeToken,
                    'description' => 'Czars Promise Event Sponsor',
                    'statement_descriptor' => 'Czars Promise',
                    'metadata' => ['name' => $create['fname'].' '.$create['lname'], 'e-mail' => $create['email']],
                ]);
            }

            $sponsor = SponsorSubmission::create($create);

            $emails = explode(',', env('SPONSOR_EMAIL'));
            \Mail::to($emails)->send(new \App\Mail\SponsorAdmin($sponsor, $event));
            \Mail::to($create['email'])->send(new \App\Mail\Sponsor($sponsor, $event));

            if (isset($request->mailinglist)) {
                if (\Mailchimp::checkStatus('8d8c06dfb7', $create['email']) != 'subscribed') {
                    try {
                        \Mailchimp::subscribe('8d8c06dfb7', $create['email'], ['FIRSTNAME' => $create['fname'], 'LASTNAME' => $create['lname'], 'COMPANYNA' => $create['company']], false);
                    } catch (\Exception $e) {
                    }
                }
            }
            session()->forget('sponsor');

            return \Redirect::route('event.view', $event->slug)->with('message', 'Thank You!  A confirmation e-mail regarding your sponsorship is on its way.');
        } else {
            return \Redirect::route('sponsor', $event->slug)->with('message', 'Your session appears to have timed out, please submit the form again.');
        }
    }

    public function listing(Event $event)
    {
        return view('sponsors.list', ['event' => $event, 'sponsors' => $event->sponsorSubmissions]);
    }

    public function delete(SponsorSubmission $sponsor)
    {
        $event = Event::where('id', $sponsor->event_id);
        $sponsor->delete();

        return view('sponsors.list', ['event' => $event, 'sponsors' => $event->sponsorSubmissions]);
    }

    public function eventSponsors(Event $event)
    {
        return view('event.sponsors', compact('event'));
    }

    public function eventSponsorAdd(Request $request, Event $event)
    {
        $this->validate($request, [
            'name' => 'required|string|max:150',
            'image' => 'required|mimes:png,jpg,jpeg|dimensions:max_width=600,max_height=600|max:512',
        ]);

        $image = $request->file('image');
        if (! is_null($image)) {
            $input['imagename'] = Str::slug($request->name, '-').'-'.time().'.'.$image->getClientOriginalExtension();

            $destinationPath = \Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().'public';

            $img = \Image::make($image->getRealPath())->resize(1400, 600, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($destinationPath.'/'.$input['imagename']);

            $create = [
                'event_id' => $event->id,
                'name' => $request->name,
                'url' => $request->url,
                'filename' => $input['imagename'],
            ];
            if (isset($request->vendor)) {
                $create['vendor'] = 1;
            }
            if (isset($request->presenting)) {
                $create['presenting'] = 1;
            }

            Sponsor::create($create);
        }

        return $this->eventSponsors($event);
    }

    public function eventSponsorDelete(Sponsor $sponsor)
    {
        \Storage::delete('public/'.$sponsor->filename);
        $sponsor->delete();
    }
}
