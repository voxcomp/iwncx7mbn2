<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Event;
use App\Models\Registrant;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonateController extends Controller
{
    /**
     * Show donate (payment) page for particular cause.
     */
    public function pageCause($cause): View
    {
        return view('pages.donate', ['hideHeader' => true, 'stripe_pk' => ((env('STRIPE_MODE', 'test') == 'live') ? env('STRIPE_PK') : env('STRIPE_TEST_PK')), 'cause' => $cause]);
    }

    /**
     * Show donate (payment) page.
     */
    public function page(?Event $event = null, ?Registrant $registrant = null): View
    {

        if (is_null($event->id)) {
            return view('pages.donate', ['hideHeader' => true, 'stripe_pk' => ((env('STRIPE_MODE', 'test') == 'live') ? env('STRIPE_PK') : env('STRIPE_TEST_PK'))]);
        } else {
            if (is_null($registrant)) {
                return view('pages.donate', ['event' => $event, 'stripe_pk' => ((env('STRIPE_MODE', 'test') == 'live') ? env('STRIPE_PK') : env('STRIPE_TEST_PK'))]);
            } else {
                return view('pages.donate', ['registrant' => $registrant, 'event' => $event, 'stripe_pk' => ((env('STRIPE_MODE', 'test') == 'live') ? env('STRIPE_PK') : env('STRIPE_TEST_PK'))]);
            }
        }
    }

    /**
     * Show donate (payment) page for a team.
     */
    public function teamPage(Event $event, Team $team): View
    {
        return view('pages.donate', ['team' => $team, 'event' => $event, 'hideHeader' => true, 'stripe_pk' => ((env('STRIPE_MODE', 'test') == 'live') ? env('STRIPE_PK') : env('STRIPE_TEST_PK'))]);
    }

    /**
     * Retrieve donation payment information via Request and send to Stripe API for processing
     *
     * @return \Illuminate\Http\Response
     */
    public function donate(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:50',
            'lname' => 'required|string|max:75',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:200',
            'city' => 'required|string|max:75',
            'state' => 'required|string|max:2',
            'zip' => 'required|string|max:10',
            'comment' => 'nullable|string|max:1000',
        ]);

        $check = Donation::where('fname', $request->fname)->where('lname', $request->lname)->where('email', $request->email)->where('amount', str_replace(['$', ','], ['', ''], $request->amount))->orderBy('created_at', 'DESC')->first();
        if (empty($check) || strtotime($check->created_at) < strtotime('2 minutes ago')) {
            if (isset($request->registrant)) {
                $registrant = Registrant::where('slug', $request->registrant)->first();
            }
            if (isset($request->team)) {
                $team = Team::where('slug', $request->team)->first();
            }
            $cost = str_replace(['$', ','], ['', ''], $request->amount);
            $plan = '';
            $customer = '';
            $subscription = '';
            if ($cost == 0) {
                $cost = str_replace(['$', ','], ['', ''], $request->otheramount);
            }
            if ($cost == 0 || empty($cost)) {
                if (isset($request->registrant)) {
                    return \Redirect::route('donate', [$registrant->event->slug, $registrant->slug])->with('message', 'Please choose a donation amount before submitting your form')->withErrors(['amount' => 'Please choose a donation amount.'])->withInput();
                }
                if (isset($request->team)) {
                    return \Redirect::route('donate', [$registrant->event->slug, $team->slug])->with('message', 'Please choose a donation amount before submitting your form')->withErrors(['amount' => 'Please choose a donation amount.'])->withInput();
                }
                if (isset($request->event)) {
                    return \Redirect::route('donate', [$request->event])->with('message', 'Please choose a donation amount before submitting your form')->withErrors(['amount' => 'Please choose a donation amount.'])->withInput();
                }
                if (isset($request->cause)) {
                    return \Redirect::route('donate.cause', [$request->cause])->with('message', 'Please choose a donation amount before submitting your form')->withErrors(['amount' => 'Please choose a donation amount.'])->withInput();
                } else {
                    return \Redirect::route('donate')->with('message', 'Please choose a donation amount before submitting your form')->withErrors(['amount' => 'Please choose a donation amount.'])->withInput();
                }
            }

            $stripe_token = $request->stripeToken;
            $donation = Donation::where('payid', $stripe_token)->first();

            if ((empty($donation) && ! empty($stripe_token)) || isset($request->nopayment)) {

                if (! isset($request->nopayment)) { // && env("STRIPE_MODE",'live')=="live") {
                    \Stripe\Stripe::setApiKey(((env('STRIPE_MODE', 'live') == 'live') ? env('STRIPE_SK') : env('STRIPE_TEST_SK')));

                    try {
                        if ($cost > 0) {
                            if ($request->recurring == 'NO') {
                                \Stripe\Charge::create([
                                    'amount' => $cost * 100,
                                    'currency' => 'usd',
                                    'source' => $request->stripeToken,
                                    'description' => 'Czars Promise Donation',
                                    'statement_descriptor' => 'Czars Promise',
                                    'metadata' => ['name' => $request->fname.' '.$request->lname, 'e-mail' => $request->email],
                                ]);
                            } else {
                                $plans = \Stripe\Plan::all();
                                $plans = collect($plans->data);
                                $plan = $plans->search(function ($item, $key) use ($cost) {
                                    return $item->nickname == 'Donation' && $item->amount == ($cost * 100);
                                });
                                if ($plan !== false) {
                                    $plan = $plans[$plan];
                                } else {
                                    $plan = \Stripe\Plan::create([
                                        'product' => [
                                            'name' => 'Czars Promise Donation',
                                            'type' => 'service',
                                        ],
                                        'nickname' => 'Donation',
                                        'interval' => 'month',
                                        'interval_count' => '1',
                                        'currency' => 'usd',
                                        'amount' => $cost * 100,
                                    ]);
                                }
                                $customers = \Stripe\Customer::all();
                                $customers = collect($customers->data);
                                $customer = $customers->search(function ($item, $key) use ($request) {
                                    return $item->email == $request->email;
                                });
                                if ($customer !== false) {
                                    $customer = $customers[$customer];
                                } else {
                                    $customer = \Stripe\Customer::create([
                                        'email' => $request->email,
                                        'source' => $stripe_token,
                                    ]);
                                }

                                $subscription = \Stripe\Subscription::create([
                                    'customer' => $customer->id,
                                    'items' => [
                                        [
                                            'plan' => $plan->id,
                                        ],
                                    ],
                                ]);
                            }
                            $request->stripeToken = '';
                        }
                    } catch (\Exception $e) {
                        return redirect()->back()->with('message', 'There was an error processing the payment.');
                    }
                }

                $create = [
                    'fname' => $request->fname,
                    'lname' => $request->lname,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip,
                    'amount' => $cost,
                    'message' => (is_null($request->comment)) ? '' : $request->comment,
                    'join' => (isset($request->mailinglist)) ? 1 : 0,
                    'paytype' => 'credit',
                    'payid' => $stripe_token,
                    'recurring' => $request->recurring,
                    'cause' => (isset($request->cause)) ? $request->cause : '',
                ];

                if (isset($request->anonymous)) {
                    $create['anonymous'] = 1;
                }

                if (\Auth::check() && \Auth::user()->isAdmin()) {
                    $create['paytype'] = (isset($request->nopayment)) ? 'cash/check' : 'credit';
                }

                if ($request->recurring == 'YES') {
                    $create['recurring_amount'] = $cost;
                    $create['customerid'] = $customer->id;
                    $create['planid'] = $plan->id;
                    $create['subscriptionid'] = $subscription->id;
                }

                if (isset($request->registrant)) {
                    if (! empty($registrant)) {
                        $create['registrant_id'] = $registrant->id;
                        $create['event_id'] = $registrant->event_id;
                    }
                    if (! isset($request->nopayment)) { // && env("STRIPE_MODE",'live')=="live") {
                        \Mail::to($registrant->email)->send(new \App\Mail\DonationRegistrant($cost, $request->fname, $request->lname, $request->email, ((isset($request->anonymous)) ? true : false), $registrant->event->title, ((is_null($request->comment)) ? '' : $request->comment)));
                    }
                }
                if (isset($request->team)) {
                    if (! empty($team)) {
                        $create['team_id'] = $team->id;
                        $create['event_id'] = $team->event_id;
                    }
                    if (! isset($request->nopayment)) { // && env("STRIPE_MODE",'live')=="live") {
                        \Mail::to($team->captain->email)->send(new \App\Mail\DonationTeam($cost, $request->fname, $request->lname, $request->email, ((is_null($request->comment)) ? '' : $request->comment), ((isset($request->anonymous)) ? true : false)));
                    }
                }
                if (isset($request->event)) {
                    $event = Event::where('slug', $request->event)->first();
                    $create['event_id'] = $event->id;
                }

                $donation = Donation::create($create);

                if (isset($request->cause)) {
                    $emails = explode(',', env('DONATE_EMAIL'));
                    $recurring = false;
                    \Mail::to($request->email)->send(new \App\Mail\DonationCause($cost, $request->fname, ''));
                    \Mail::to($emails)->send(new \App\Mail\DonationAdmin($request->fname, $request->lname, $request->email, $request->phone, $request->address, $request->city, $request->state, $request->zip, ((is_null($request->comment)) ? '' : $request->comment), $cost, $recurring));

                    return redirect('http://www.czarspromise.com/thank-you/cause');
                } else {
                    if (! isset($request->nopayment)) { // && env("STRIPE_MODE",'live')=="live") {
                        $link = '';
                        if ($request->recurring == 'YES') {
                            $link = url('donate/subscription/cancel', [$subscription->id]);
                        }
                        \Mail::to($request->email)->send(new \App\Mail\Donation($cost, $request->fname, $link));
                        $emails = explode(',', env('DONATE_EMAIL'));
                        $recurring = false;
                        if ($request->recurring == 'YES') {
                            $recurring = true;
                        }
                        \Mail::to($emails)->send(new \App\Mail\DonationAdmin($request->fname, $request->lname, $request->email, $request->phone, $request->address, $request->city, $request->state, $request->zip, ((is_null($request->comment)) ? '' : $request->comment), $cost, $recurring));

                        // add person to mailchimp mailing list
                        if (isset($request->mailinglist)) {
                            if (\Mailchimp::checkStatus('8d8c06dfb7', $request->email) != 'subscribed') {
                                try {
                                    \Mailchimp::subscribe('8d8c06dfb7', $request->email, ['FIRSTNAME' => $request->fname, 'LASTNAME' => $request->lname], false);
                                } catch (\Exception $e) {
                                }
                            }
                        }
                    }

                    return redirect('http://www.czarspromise.com/thank-you');
                }
            }

            return \Redirect::back();
        } else {
            return redirect('http://www.czarspromise.com/thank-you');
        }
    }

    public function cancelSubscription($subscription_id): View
    {
        try {
            \Stripe\Stripe::setApiKey(((env('STRIPE_MODE', 'live') == 'live') ? env('STRIPE_SK') : env('STRIPE_TEST_SK')));
            $sub = \Stripe\Subscription::retrieve($subscription_id);
            $sub->cancel();

            \DB::table('donations')->where('subscriptionid', $subscription_id)->update(['cancelled_on' => time()]);

            return view('pages.donateCancel');
        } catch (\Exception $e) {
            return view('pages.donateCancel');
        }
    }

    /**
     * Show donate (payment) page for Promise Wall.
     */
    public function pagePromise(): View
    {
        return view('pages.donatepromise', ['hideHeader' => true, 'stripe_pk' => ((env('STRIPE_MODE', 'test') == 'live') ? env('STRIPE_PK') : env('STRIPE_TEST_PK'))]);
    }

    public function donatePromise(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:50',
            'lname' => 'required|string|max:75',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:200',
            'city' => 'required|string|max:75',
            'state' => 'required|string|max:2',
            'zip' => 'required|string|max:10',
            'comment' => 'nullable|string|max:1000',
            'photo' => 'nullable|string|max:250',
            'memoryof' => 'required|string|max:100',
        ]);

        $check = Donation::where('fname', $request->fname)->where('lname', $request->lname)->where('email', $request->email)->where('amount', str_replace(['$', ','], ['', ''], $request->amount))->orderBy('created_at', 'DESC')->first();
        if (empty($check) || strtotime($check->created_at) < strtotime('5 minutes ago')) {

            $cost = $request->amount;
            $plan = '';
            $customer = '';
            $subscription = '';
            if ($cost == 0) {
                $cost = $request->otheramount;
            }
            if ($cost == 0 || empty($cost)) {
                return \Redirect::route('donate')->with('message', 'Please choose a donation amount before submitting your form')->withErrors(['amount' => 'Please choose a donation amount.'])->withInput();
            }

            $stripe_token = $request->stripeToken;
            $donation = Donation::where('payid', $stripe_token)->first();

            if ((empty($donation) && ! empty($stripe_token)) || isset($request->nopayment)) {

                if (! isset($request->nopayment)) { // && env("STRIPE_MODE",'live')=="live") {
                    \Stripe\Stripe::setApiKey(((env('STRIPE_MODE', 'live') == 'live') ? env('STRIPE_SK') : env('STRIPE_TEST_SK')));

                    try {
                        if ($cost > 0) {
                            if ($request->recurring == 'NO') {
                                \Stripe\Charge::create([
                                    'amount' => $cost * 100,
                                    'currency' => 'usd',
                                    'source' => $request->stripeToken,
                                    'description' => 'Czars Promise Wall Donation',
                                    'statement_descriptor' => 'Czars Promise Wall',
                                    'metadata' => ['name' => $request->fname.' '.$request->lname, 'e-mail' => $request->email],
                                ]);
                            } else {
                                $plans = \Stripe\Plan::all();
                                $plans = collect($plans->data);
                                $plan = $plans->search(function ($item, $key) use ($cost) {
                                    return $item->nickname == 'Donation' && $item->amount == ($cost * 100);
                                });
                                if ($plan !== false) {
                                    $plan = $plans[$plan];
                                } else {
                                    $plan = \Stripe\Plan::create([
                                        'product' => [
                                            'name' => 'Czars Promise Wall Donation',
                                            'type' => 'service',
                                        ],
                                        'nickname' => 'Donation',
                                        'interval' => 'month',
                                        'interval_count' => '1',
                                        'currency' => 'usd',
                                        'amount' => $cost * 100,
                                    ]);
                                }
                                $customers = \Stripe\Customer::all();
                                $customers = collect($customers->data);
                                $customer = $customers->search(function ($item, $key) use ($request) {
                                    return $item->email == $request->email;
                                });
                                if ($customer !== false) {
                                    $customer = $customers[$customer];
                                } else {
                                    $customer = \Stripe\Customer::create([
                                        'email' => $request->email,
                                        'source' => $stripe_token,
                                    ]);
                                }

                                $subscription = \Stripe\Subscription::create([
                                    'customer' => $customer->id,
                                    'items' => [
                                        [
                                            'plan' => $plan->id,
                                        ],
                                    ],
                                ]);
                            }
                            $request->stripeToken = '';
                        }
                    } catch (\Exception $e) {
                        return redirect()->back()->with('message', 'There was an error processing the payment.');
                    }
                }

                $create = [
                    'fname' => $request->fname,
                    'lname' => $request->lname,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip,
                    'amount' => $cost,
                    'message' => (is_null($request->comment)) ? '' : $request->comment,
                    'join' => (isset($request->mailinglist)) ? 1 : 0,
                    'paytype' => 'credit',
                    'payid' => $stripe_token,
                    'recurring' => $request->recurring,
                    'promise' => (is_null($request->photo)) ? 'no' : 'yes',
                    'memoryof' => $request->memoryof,
                    'photo' => (is_null($request->photo)) ? '' : $request->photo,
                ];

                if (isset($request->anonymous)) {
                    $create['anonymous'] = 1;
                }

                if (\Auth::check() && \Auth::user()->isAdmin()) {
                    $create['paytype'] = (isset($request->nopayment)) ? 'cash/check' : 'credit';
                }

                if ($request->recurring == 'YES') {
                    $create['recurring_amount'] = $cost;
                    $create['customerid'] = $customer->id;
                    $create['planid'] = $plan->id;
                    $create['subscriptionid'] = $subscription->id;
                }

                // check if photo uploaded and save it
                if (strpos($request->photo, 'temp/') !== false) {
                    $filename = microtime(true);
                    $filename = 'promise/'.$filename.substr($request->photo, -4);
                    \Storage::disk('local')->move($request->photo, $filename);
                    $create['photo'] = $filename;
                }

                $donation = Donation::create($create);

                if (! isset($request->nopayment)) { // && env("STRIPE_MODE",'live')=="live") {
                    $link = '';
                    if ($request->recurring == 'YES') {
                        $link = url('donate/subscription/cancel', [$subscription->id]);
                    }
                    \Mail::to($request->email)->send(new \App\Mail\Donation($cost, $request->fname, $link));
                    $emails = explode(',', env('DONATE_EMAIL'));
                    $recurring = false;
                    if ($request->recurring == 'YES') {
                        $recurring = true;
                    }
                    \Mail::to($emails)->send(new \App\Mail\DonationAdmin($request->fname, $request->lname, $request->email, $request->phone, $request->address, $request->city, $request->state, $request->zip, ((is_null($request->comment)) ? '' : $request->comment), $cost, $recurring));

                    // add person to mailchimp mailing list
                    /*
                                    if(isset($request->mailinglist)) {
                                        if(\Mailchimp::checkStatus('8d8c06dfb7',$request->email)!='subscribed') {
                                            try {
                                                \Mailchimp::subscribe('8d8c06dfb7', $request->email, ["FIRSTNAME"=>$request->fname, "LASTNAME"=>$request->lname], false);
                                            } catch(\Exception $e) {}
                                        }
                                    }
                    */
                }
                if (strpos($request->photo, 'temp/') !== false && ! isset($request->anonymous)) {
                    return \Redirect::route('donate.promise')->with('confirm', 'Thank you for your donation.');
                } else {
                    return \Redirect::route('donate.promise.confirm');
                }
                // return redirect('http://www.czarspromise.com/thank-you');
            }

            return \Redirect::back();
        } else {
            return \Redirect::route('donate.promise')->with('confirm', 'Thank you for your donation.');
        }
    }
}
