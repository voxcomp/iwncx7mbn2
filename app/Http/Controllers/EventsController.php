<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Event;
use App\Models\Registrant;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EventsController extends Controller
{
    public function create(): View
    {
        return view('event.form');
    }

    public function edit(Event $event): View
    {
        return view('event.form', ['event' => $event]);
    }

    public function all(): View
    {
        $events = Event::get(); // Event::where("event_date",">",time())->get();

        return view('event.list', ['events' => $events]);
    }

    public function save(Request $request)
    {
        if (is_null($request->costs)) {
            return \Redirect::back()->withInput()->withErrors(['cost' => ['At least one registration cost is required.']]);
        }
        $this->validate($request, [
            'title' => 'required|string|max:250',
            'short' => 'required|string|max:50',
            'description' => 'required',
            'image' => 'mimes:png,jpg,jpeg|dimensions:min_width=800,min_height=250|max:1024',
        ]);

        $image = $request->file('image');
        if (! is_null($image)) {
            $input['imagename'] = Str::slug($request->short, '-').'-'.time().'.'.$image->getClientOriginalExtension();

            $destinationPath = \Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().'public';

            $img = \Image::make($image->getRealPath())->resize(1400, 600, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($destinationPath.'/'.$input['imagename']);

            $event = Event::create([
                'title' => $request->title,
                'short' => $request->short,
                'description' => htmlspecialchars($request->description),
                'event_date' => strtotime($request->event_date),
                'goal' => str_replace('$', '', $request->goal),
                'image' => $input['imagename'],
            ]);
        } else {
            $event = Event::create([
                'title' => $request->title,
                'short' => $request->short,
                'description' => htmlspecialchars($request->description),
                'event_date' => strtotime($request->event_date),
                'goal' => str_replace('$', '', $request->goal),
                'earlybird' => str_replace('$', '', $request->earlybird),
                'earlybirddate' => strtotime($request->earlybirddate),
                'cost' => str_replace('$', '', $request->cost),
            ]);
        }
        foreach ($request->costs as $cost) {
            $cost = explode(':', $cost);
            $event->costs()->create([
                'cost' => $cost[0],
                'ends' => strtotime($cost[1].' 23:59'),
            ]);
        }

        return \Redirect::route('event.list')->with('message', $request->title.' has been saved.');
    }

    public function update(Request $request, Event $event)
    {
        if (is_null($request->costs)) {
            return \Redirect::back()->withInput()->withErrors(['cost' => ['At least one registration cost is required.']]);
        }
        $this->validate($request, [
            'title' => 'required|string|max:250',
            'short' => 'required|string|max:50',
            'description' => 'required',
            'image' => 'mimes:png,jpg,jpeg|dimensions:min_width=800,min_height=250|max:1024',
        ]);

        $update = [
            'title' => $request->title,
            'short' => $request->short,
            'description' => htmlspecialchars($request->description),
            'event_date' => strtotime($request->event_date),
            'goal' => str_replace('$', '', $request->goal),
        ];

        $image = $request->file('image');

        if (! is_null($image)) {
            $input['imagename'] = Str::slug($request->short, '-').'-'.time().'.'.$image->getClientOriginalExtension();

            $destinationPath = \Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().'public';

            $img = \Image::make($image->getRealPath())->resize(1400, 600, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($destinationPath.'/'.$input['imagename']);
            $update['image'] = $input['imagename'];
        }

        $event->update($update);

        $event->costs()->delete();
        foreach ($request->costs as $cost) {
            $cost = explode(':', $cost);
            $event->costs()->create([
                'cost' => $cost[0],
                'ends' => strtotime($cost[1].' 23:59'),
            ]);
        }

        return \Redirect::route('event.list')->with('message', $request->title.' has been saved.');
    }

    public function delete(Event $event)
    {
        $event->costs()->delete();
        $event->participants()->delete();
        foreach ($event->teams as $team) {
            $team->members()->delete();
        }
        $event->donations()->delete();
        \Storage::delete('public/'.$event->image);
        $event->delete();
    }

    public function view(Event $event): View
    {
        // if (Cache::has($event->id.'topparticipants')) {
        $participants = $event->participants;

        $participants->each(function ($item, $key) use ($event) {
            $item->eventdonations = $item->eventDonations($event);
        });

        $participants = $participants->where('eventdonations', '>', 0)->sortByDesc('eventdonations')->take(10);
        /*
                    Cache::put($event->id.'topparticipants',$participants,60);
                } else {
                    $participants = Cache::get($event->id.'topparticipants');
                }

                if (Cache::has($event->id.'topteams')) {
        */
        $teams = $event->teams;

        $teams->each(function ($item, $key) use ($event) {
            $donations = $item->eventDonations($event);
            /*
                            $item->members->each(function($item,$key) use (&$donations,$event) {
                                $registrant = Registrant::where('id',$item->registrant_id)->first();
                                $donations = $registrant->eventDonations($event);
                            });
            */

            $item->eventdonations = $donations;
        });

        $teams = $teams->where('eventdonations', '>', 0)->sortByDesc('eventdonations')->take(10);
        /*
                    Cache::put($event->id.'topteams',$teams,60);
                } else {
                    $teams = Cache::get($event->id.'topteams');
                }
        */

        return view('event.view', compact('event', 'participants', 'teams'));
    }

    public function personalView(Event $event, Registrant $registrant): View
    {
        if (! empty($registrant->pagetitle) && ! is_null($registrant->pagetitle) && ($registrant->moderated && $registrant->reviewed)) {
            $donors = Donation::where('event_id', $event->id)->where('registrant_id', $registrant->id)->orderBy('amount', 'DESC')->get();

            return view('event.personal', compact('event', 'registrant', 'donors'));
        } else {
            return view('event.personal404', compact('event', 'registrant'));
        }
    }

    public function teampageView(Event $event, Team $team): View
    {
        if (! empty($team->pagetitle) && ! is_null($team->pagetitle) && ($team->moderated && $team->reviewed)) {
            $donors = Donation::where('event_id', $event->id)->where('team_id', $team->id)->orderBy('amount', 'DESC')->get();

            return view('event.team', compact('event', 'team', 'donors'));
        } else {
            return view('event.team404', compact('event', 'team'));
        }
    }

    public function register(Event $event)
    {
        if (\Auth::check() && \Auth::user()->user_type != 'admin') {
            if ($event->participants->where('email', \Auth::user()->email)->count() == 0) {
                return view('event.register', ['event' => $event, 'user' => \Auth::user()]);
            } else {
                return \Redirect::route('event.view', [$event->slug])->with('message', 'You have already registered for this event');
            }
        } else {
            if (session()->has('registration')) {
                $registration = session()->get('registration');
                $donation = $registration['amount'];
                if ($donation == 0) {
                    $donation = $registration['otheramount'];
                }

                return view('event.register2', ['event' => $event, 'donation' => $donation, 'registranttype' => $registration['registrant']]);
            } elseif (session()->has('registrationpersonal')) {
                $registration = session()->get('registration');
                if ($donation == 0 && $registration['registrant'] != 'adult') {
                    return $this->registerNoPay($event);
                } else {
                    return view('event.registerpay', ['event' => $event, 'donation' => $donation, 'registranttype' => $registration['registrant'], 'stripe_pk' => ((env('STRIPE_MODE', 'test') == 'live') ? env('STRIPE_PK') : env('STRIPE_TEST_PK'))]);
                }
            } else {
                return view('event.register', ['event' => $event]);
            }
        }
    }

    public function registerStep1(Request $request, Event $event, ?User $user = null)
    {
        /*
                if(!\Auth::check()) {
                    if($event->participants->where('email',$request->email)->count()!=0) {
                        return \Redirect::route('event.view',[$event->slug])->with('message','You have already registered for this event');
                    }
                }
        */
        $this->validate($request, [
            'fname' => 'required|string|max:50',
            'lname' => 'required|string|max:75',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:200',
            'city' => 'required|string|max:75',
            'state' => 'required|string|max:2',
            'zip' => 'required|string|max:10',
            'registrant' => 'required|string|max:50',
            'tshirt' => 'nullable|string|max:10',
            'pets' => 'nullable|integer',
            'shipaddress' => 'nullable|string|max:200',
            'shipcity' => 'nullable|string|max:75',
            'shipstate' => 'nullable|string|max:2',
            'shipzip' => 'nullable|string|max:10',
        ]);
        $registration = $request->all();
        $registration['event_id'] = $event->id;
        session()->put('registration', $registration);
        $donation = $registration['amount'];
        if ($donation == 0) {
            $donation = $registration['otheramount'];
        }

        return view('event.register2', ['event' => $event, 'donation' => $donation, 'registranttype' => $registration['registrant']]);
    }

    public function registerStep2(Request $request, Event $event, ?User $user = null)
    {
        $this->validate($request, [
            'username' => 'nullable|unique:users|string|min:5|max:20',
            'password' => 'nullable|string|min:6|max:25|confirmed',
            'newteam' => 'nullable|string|max:100',
            'newteam' => Rule::unique('teams', 'name')->where(function ($query) use ($event) {
                $query->where('event_id', $event->id);
            }),
            'pagetitle' => 'nullable|string|max:100',
        ]);
        if (session()->has('registration')) {
            $registration = session()->get('registration');

            $registrationpersonal = $request->all();
            session()->put('registrationpersonal', $registrationpersonal);

            $donation = $registration['amount'];
            if ($donation == 0) {
                $donation = (! empty($registration['otheramount'])) ? $registration['otheramount'] : 0;
            }
            if ($donation == 0 && $registration['registrant'] != 'adult') {
                return $this->registerNoPay($event);
            } else {
                $cost = $event->costs->where('ends', '>', time())->sortBy('ends')->first()->cost;
                if (isset($registration['shipshirt']) && $registration['shipshirt'] == 1) {
                    $cost += 8;
                }

                return view('event.registerpay', ['cost' => $cost, 'event' => $event, 'donation' => $donation, 'registranttype' => $registration['registrant'], 'stripe_pk' => ((env('STRIPE_MODE', 'test') == 'live') ? env('STRIPE_PK') : env('STRIPE_TEST_PK'))]);
            }
        } else {
            return \Redirect::route('event.register', [$event->slug])->with('message', 'It appears your session expired.  Please register for this event again.');
        }
    }

    private function registerNoPay(Event $event)
    {
        if (! session()->has('registration') || ! session()->has('registrationpersonal')) {
            return \Redirect::route('event.register', [$event->slug])->with('message', 'It seems your session has expired, please register for this event again.');
        }
        $registration = session()->get('registration');
        $registrationpersonal = session()->get('registrationpersonal');

        // create user account if entered and user is not logged in
        if (! \Auth::check() && (! empty($registrationpersonal['username']) && ! empty($registrationpersonal['password']))) {
            $user = User::create([
                'username' => $registrationpersonal['username'],
                'user_type' => 'auth',
                'fname' => $registration['fname'],
                'lname' => $registration['lname'],
                'email' => $registration['email'],
                'password' => Hash::make($registrationpersonal['password']),
                'validated' => 0,
                'phone' => $registration['phone'],
                'address' => $registration['address'],
                'city' => $registration['city'],
                'state' => $registration['state'],
                'zip' => $registration['zip'],
            ]);
            $validationFactory = new \App\Http\Factories\ValidationFactory(new \App\Http\Repositories\ValidationRepo);
            $validationFactory->sendValidationMail($user);
        } elseif (\Auth::check() && empty(Auth::user()->address)) {
            $user = \Auth::user();
            $user->update([
                'phone' => $registration['phone'],
                'address' => $registration['address'],
                'city' => $registration['city'],
                'state' => $registration['state'],
                'zip' => $registration['zip'],
            ]);
        } elseif (\Auth::check()) {
            $user = \Auth::user();
        }

        // create registrant entry
        $create = [
            'event_id' => $event->id,
            'user_id' => (isset($user)) ? $user->id : 0,
            'fname' => $registration['fname'],
            'lname' => $registration['lname'],
            'email' => $registration['email'],
            'phone' => $registration['phone'],
            'address' => $registration['address'],
            'city' => $registration['city'],
            'state' => $registration['state'],
            'zip' => $registration['zip'],
            'shipshirt' => (isset($registration['shipshirt'])) ? $registration['shipshirt'] : 0,
            'shipaddress' => $registration['shipaddress'],
            'shipcity' => $registration['shipcity'],
            'shipstate' => $registration['shipstate'],
            'shipzip' => $registration['shipzip'],
            'registrant' => $registration['registrant'],
            'shirt' => $registration['tshirt'],
            'pets' => $registration['pets'],
            'paid' => ($registration['registrant'] == 'adult') ? $event->costs->where('ends', '>', time())->sortBy('ends')->first()->cost : 0,
            'goal' => str_replace('$', '', $registrationpersonal['pagegoal']),
            'pagetitle' => $registrationpersonal['pagetitle'],
            'pagecontent' => htmlspecialchars($registrationpersonal['pagecontent']),
        ];

        $registrant = Registrant::create($create);
        if (! empty($registrant->pagetitle)) {
            $registrant->pageurl = config('app.url').'/event/personal/'.$event->slug.'/'.$registrant->slug;
            $registrant->pageshorturl = $registrant->pageurl;
            $registrant->save();
        }

        if ($this->previouslyModerated($registrant)) {
            $registrant->moderated = 1;
            $registrant->reviewed = 1;
            $registrant->save();
        }

        // create or join team
        if (! empty($registrationpersonal['newteam']) && ! is_null($registrationpersonal['newteam'])) {
            $create = [
                'event_id' => $event->id,
                'registrant_id' => $registrant->id,
                'name' => $registrationpersonal['newteam'],
            ];
            if (! empty($registrationpersonal['teampagetitle'])) {
                $create['goal'] = str_replace('$', '', $registrationpersonal['teampagegoal']);
                $create['pagetitle'] = $registrationpersonal['teampagetitle'];
                $create['pagecontent'] = htmlspecialchars($registrationpersonal['teampagecontent']);
            }
            $team = Team::create($create);
            if (! empty($team->pagetitle)) {
                $team->pageurl = config('app.url').'/event/team/'.$event->slug.'/'.$team->slug;
                $team->pageshorturl = $team->pageurl;
                $team->save();
            }
            $is_member = \App\Models\TeamMember::where('registrant_id', $registrant->id)->where('team_id', $team->id)->count();
            if (! $is_member) {
                TeamMember::create([
                    'team_id' => $team->id,
                    'registrant_id' => $registrant->id,
                ]);
                \Mail::to($registrant->email)->send(new \App\Mail\CreateTeam($team));
            }
        } elseif ($registrationpersonal['team'] != 0) {
            $team = Team::where('id', $registrationpersonal['team'])->first();
            $is_member = \App\Models\TeamMember::where('registrant_id', $registrant->id)->where('team_id', $registrationpersonal['team'])->count();
            if (! $is_member) {
                TeamMember::create([
                    'team_id' => $registrationpersonal['team'],
                    'registrant_id' => $registrant->id,
                ]);
                \Mail::to($registrant->email)->send(new \App\Mail\JoinTeam($team));
            }
        }

        $emails = explode(',', env('ADMIN_EMAIL'));
        \Mail::to($emails)->send(new \App\Mail\RegistrationAdmin($event->slug, $registrant->slug, ((isset($team)) ? $team->slug : 0), 0, ((! \Auth::check() && (! empty($registrationpersonal['username']) && ! empty($registrationpersonal['password']))) ? $user->slug : 0)));

        \Session::forget('registration');
        \Session::forget('registrationpersonal');
        // $request->session()->forget('registration');
        // $request->session()->forget('registrationpersonal');

        return \Redirect::route('event.register.confirm', [$event->slug, $registrant->slug, ((isset($team)) ? $team->slug : 0), 0, ((! \Auth::check() && (! empty($registrationpersonal['username']) && ! empty($registrationpersonal['password']))) ? $user->slug : 0)]);
    }

    public function registerPay(Request $request, Event $event)
    {
        $registration = session()->get('registration');

        $registrationpersonal = session()->get('registrationpersonal');
        if (is_null($registration) || is_null($registrationpersonal)) {
            return \Redirect::route('event.register', [$event->slug])->with('message', 'It seems your session has expired, please register for this event again.');
        }

        $is_donation = false;
        $cost = $registration['amount'];
        if ($cost == 0) {
            $cost = $registration['otheramount'];
        }
        if ($cost > 0) {
            $is_donation = $cost;
        }
        if ($registration['registrant'] == 'adult') {
            $regcost = $event->costs->where('ends', '>', time())->sortBy('ends')->first()->cost;
            if (isset($registration['shipshirt']) && $registration['shipshirt'] == 1) {
                $regcost += 8;
            }
            if (isset($request->coupon) && ! empty($request->coupon)) {
                $coupon = \App\Models\Coupon::where('name', '=', $request->coupon)->first();
                if (! empty($coupon) && ! is_null($coupon)) {
                    if ($coupon->isUsable()) {
                        $regcost = $coupon->value($regcost);
                    }
                }
            }
            $cost += $regcost;
        } else {
            $regcost = 0;
        }
        $stripe_token = (isset($request->stripeToken)) ? $request->stripeToken : '';
        $registrant = ($cost > 0) ? Registrant::where('payid', $stripe_token)->first() : '';
        if ((empty($registrant) && ! empty($stripe_token)) || $cost == 0 || isset($request->nopayment)) {
            if (! isset($request->nopayment)) {
                try {
                    // dd($request->stripeToken);
                    if ($cost > 0 && (isset($registration['fname']) && ! empty($registration['fname'])) && (isset($registration['lname']) && ! empty($registration['lname'])) && (isset($registration['email']) && ! empty($registration['email']))) {
                        if (isset($request->stripeToken) && ! empty($request->stripeToken)) {
                            \Stripe\Stripe::setApiKey(((env('STRIPE_MODE', 'test') == 'live') ? env('STRIPE_SK') : env('STRIPE_TEST_SK')));
                            \Stripe\Charge::create([
                                'amount' => $cost * 100,
                                'currency' => 'usd',
                                'source' => $request->stripeToken,
                                'description' => 'Czars Promise Event Sign Up',
                                'statement_descriptor' => 'Czars Promise',
                                'metadata' => ['name' => $registration['fname'].' '.$registration['lname'], 'e-mail' => $registration['email']],
                            ]);
                        }
                        $request->stripeToken = '';
                    }
                } catch (\Exception $e) {
                    // \Log::debug(var_dump($e));
                    $donation = $registration['amount'];
                    if ($donation == 0) {
                        $donation = (! empty($registration['otheramount'])) ? $registration['otheramount'] : 0;
                    }

                    return view('event.registerpay', ['event' => $event, 'cost' => $cost, 'donation' => $donation, 'registranttype' => $registration['registrant'], 'message' => 'There was an error processing the payment.', 'stripe_pk' => ((env('STRIPE_MODE', 'test') == 'live') ? env('STRIPE_PK') : env('STRIPE_TEST_PK'))]);
                    exit();
                }
            }
            if ($registration['registrant'] == 'adult') {
                if (isset($request->coupon) && ! empty($request->coupon)) {
                    $coupon = \App\Models\Coupon::where('name', '=', $request->coupon)->first();
                    if (! empty($coupon) && ! is_null($coupon)) {
                        $coupon->useCoupon();
                    }
                }
            }

            // create user account if entered and user is not logged in
            if ((! \Auth::check() || \Auth::user()->user_type == 'admin') && (! empty($registrationpersonal['username']) && ! empty($registrationpersonal['password']))) {
                $user = User::where('username', $registrationpersonal['username'])->first();
                if (empty($user)) {
                    $user = User::create([
                        'username' => $registrationpersonal['username'],
                        'user_type' => 'auth',
                        'fname' => $registration['fname'],
                        'lname' => $registration['lname'],
                        'email' => $registration['email'],
                        'password' => Hash::make($registrationpersonal['password']),
                        'validated' => 0,
                        'phone' => $registration['phone'],
                        'address' => $registration['address'],
                        'city' => $registration['city'],
                        'state' => $registration['state'],
                        'zip' => $registration['zip'],
                    ]);
                    $validationFactory = new \App\Http\Factories\ValidationFactory(new \App\Http\Repositories\ValidationRepo);
                    $validationFactory->sendValidationMail($user);
                }
            } elseif (\Auth::check() && empty(\Auth::user()->address) && \Auth::user()->user_type != 'admin') {
                $user = \Auth::user();
                $user->update([
                    'phone' => $registration['phone'],
                    'address' => $registration['address'],
                    'city' => $registration['city'],
                    'state' => $registration['state'],
                    'zip' => $registration['zip'],
                ]);
            } elseif (\Auth::check() && \Auth::user()->user_type != 'admin') {
                $user = \Auth::user();
            }

            // create registrant entry
            $create = [
                'event_id' => $event->id,
                'user_id' => (isset($user)) ? $user->id : 0,
                'fname' => $registration['fname'],
                'lname' => $registration['lname'],
                'email' => $registration['email'],
                'phone' => $registration['phone'],
                'address' => $registration['address'],
                'city' => $registration['city'],
                'state' => $registration['state'],
                'zip' => $registration['zip'],
                'shipshirt' => (isset($registration['shipshirt'])) ? $registration['shipshirt'] : 0,
                'shipaddress' => $registration['shipaddress'],
                'shipcity' => $registration['shipcity'],
                'shipstate' => $registration['shipstate'],
                'shipzip' => $registration['shipzip'],
                'registrant' => $registration['registrant'],
                'shirt' => $registration['tshirt'],
                'pets' => $registration['pets'],
                'paid' => $regcost,
                'goal' => str_replace('$', '', $registrationpersonal['pagegoal']),
                'pagetitle' => $registrationpersonal['pagetitle'],
                'pagecontent' => htmlspecialchars($registrationpersonal['pagecontent']),
                'payid' => $stripe_token,
            ];
            //         ($registration['registrant']=='adult')?$event->costs->where('ends','>',time())->sortBy('ends')->first()->cost:0,

            if (isset($request->coupon) && ! empty($request->coupon)) {
                $create['discountcode'] = $request->coupon;
            }
            $registrant = Registrant::create($create);

            if (! empty($registrant->pagetitle)) {
                $registrant->pageurl = config('app.url').'/event/personal/'.$event->slug.'/'.$registrant->slug;
                $registrant->pageshorturl = $registrant->pageurl;
                $registrant->save();
            }

            if ($this->previouslyModerated($registrant)) {
                $registrant->moderated = 1;
                $registrant->reviewed = 1;
                $registrant->save();
            }

            // create or join team
            if (! empty($registrationpersonal['newteam']) && ! is_null($registrationpersonal['newteam'])) {
                $create = [
                    'event_id' => $event->id,
                    'registrant_id' => $registrant->id,
                    'name' => $registrationpersonal['newteam'],
                ];
                if (! empty($registrationpersonal['teampagetitle'])) {
                    $create['goal'] = str_replace('$', '', $registrationpersonal['teampagegoal']);
                    $create['pagetitle'] = $registrationpersonal['teampagetitle'];
                    $create['pagecontent'] = htmlspecialchars($registrationpersonal['teampagecontent']);
                }
                $team = Team::create($create);
                if (! empty($team->pagetitle)) {
                    $team->pageurl = config('app.url').'/event/team/'.$event->slug.'/'.$team->slug;
                    $team->pageshorturl = $team->pageurl;
                    $team->save();
                }
                $is_member = \App\Models\TeamMember::where('registrant_id', $registrant->id)->where('team_id', $team->id)->count();
                if (! $is_member) {
                    TeamMember::create([
                        'team_id' => $team->id,
                        'registrant_id' => $registrant->id,
                    ]);
                    \Mail::to($registrant->email)->send(new \App\Mail\CreateTeam($team));
                }
            } elseif ($registrationpersonal['team'] != 0) {
                $team = Team::where('id', $registrationpersonal['team'])->first();
                $is_member = \App\Models\TeamMember::where('registrant_id', $registrant->id)->where('team_id', $registrationpersonal['team'])->count();
                if (! $is_member) {
                    TeamMember::create([
                        'team_id' => $registrationpersonal['team'],
                        'registrant_id' => $registrant->id,
                    ]);
                    \Mail::to($registrant->email)->send(new \App\Mail\JoinTeam($team));
                }
            }

            // create donation if there was one
            if ($is_donation !== false) {
                $donate = [
                    'event_id' => $event->id,
                    'registrant_id' => $registrant->id,
                    'fname' => $registration['fname'],
                    'lname' => $registration['lname'],
                    'email' => $registration['email'],
                    'phone' => $registration['phone'],
                    'address' => $registration['address'],
                    'city' => $registration['city'],
                    'state' => $registration['state'],
                    'zip' => $registration['zip'],
                    'amount' => $is_donation,
                    'message' => '',
                    'join' => 0,
                    'paytype' => 'credit',
                ];
                if (isset($team)) {
                    $donate['team_id'] = $team->id;
                }
                $donation = Donation::create($donate);
                $emails = explode(',', env('DONATE_EMAIL'));
                \Mail::to($emails)->send(new \App\Mail\DonationAdmin($donation->fname, $donation->lname, $donation->email, $donation->phone, $donation->address, $donation->city, $donation->state, $donation->zip, '', $is_donation, false));
            }

            // add person to mailchimp mailing list
            /*
                        if(isset($request->mailinglist)) {
                            if(\Mailchimp::checkStatus('8d8c06dfb7',$registration['email'])!='subscribed') {
                                try {
                                    \Mailchimp::subscribe('8d8c06dfb7', $registration['email'], ["FIRSTNAME"=>$registration['fname'], "LASTNAME"=>$registration['lname']], false);
                                } catch(\Exception $e) {}
                            }
                        }
            */

            $request->session()->forget('registration');
            $request->session()->forget('registrationpersonal');

            $routeparam = [$event->slug, $registrant->slug, ((isset($team)) ? $team->slug : 0), ((isset($donation)) ? $donation->id : 0), ((! \Auth::check() && (! empty($registrationpersonal['username']) && ! empty($registrationpersonal['password']))) ? $user->slug : 0)];

            \Mail::to($registrant->email)->send(new \App\Mail\Registration($event->slug, $registrant->slug, ((isset($team)) ? $team->slug : 0), ((isset($donation)) ? $donation->id : 0), ((! \Auth::check() && (! empty($registrationpersonal['username']) && ! empty($registrationpersonal['password']))) ? $user->slug : 0)));
            $emails = explode(',', env('ADMIN_EMAIL'));
            \Mail::to($emails)->send(new \App\Mail\RegistrationAdmin($event->slug, $registrant->slug, ((isset($team)) ? $team->slug : 0), ((isset($donation)) ? $donation->id : 0), ((! \Auth::check() && (! empty($registrationpersonal['username']) && ! empty($registrationpersonal['password']))) ? $user->slug : 0)));

            unset($registration);
            unset($registrationpersonal);
            if (isset($registrant)) {
                unset($registrant);
            }

            return \Redirect::route('event.register.confirm', $routeparam);
        }

        return \Redirect::route('event.register', [$event->slug])->with('message', 'It seems your session has expired, please register for this event again.');
    }

    public function registerConfirm(Event $event, Registrant $registrant, $team = null, $donation = null, $user = null): View
    {
        if (! is_null($team) && $team != 0) {
            $team = Team::where('id', $team)->first();
        } else {
            $team = null;
        }
        if (! is_null($donation) && $donation != 0) {
            $donation = Donation::where('id', $donation)->first();
        } else {
            $donation = null;
        }
        if (! is_null($user) && $user) {
            $user = User::where('slug', $user)->first();
        } else {
            $user = null;
        }

        return view('event.registerconfirm', compact('event', 'registrant', 'team', 'donation', 'user'));
    }

    public function teamJoin(Request $request, Event $event, Registrant $registrant, $route = '')
    {
        $this->validate($request, [
            'newteam'.$event->id => 'nullable|string|max:100',
            'newteam'.$event->id => Rule::unique('teams', 'name')->where(function ($query) use ($event) {
                $query->where('event_id', $event->id);
            }),
        ]);
        if (! empty($request->{'newteam'.$event->id}) && ! is_null($request->{'newteam'.$event->id})) {
            $team = Team::create([
                'event_id' => $event->id,
                'registrant_id' => $registrant->id,
                'name' => $request->{'newteam'.$event->id},
            ]);
            TeamMember::create([
                'team_id' => $team->id,
                'registrant_id' => $registrant->id,
            ]);
            \Mail::to($registrant->email)->send(new \App\Mail\CreateTeam($team));
        } elseif ($request->{'team'.$event->id} != 0) {
            $team = Team::where('id', $request->{'team'.$event->id})->first();
            $is_member = \App\Models\TeamMember::where('registrant_id', $registrant->id)->where('team_id', $request->{'team'.$event->id})->count();
            if (! $is_member) {
                TeamMember::create([
                    'team_id' => $request->{'team'.$event->id},
                    'registrant_id' => $registrant->id,
                ]);
                \Mail::to($registrant->email)->send(new \App\Mail\JoinTeam($team));
                \Mail::to($team->captain->email)->send(new \App\Mail\JoinTeamCaptain($team, $registrant));
            }
        }

        if (empty($route)) {
            return \Redirect::route('home');
        } else {
            return \Redirect::route($route, [$registrant->slug])->with('message', $registrant->fname.' '.$registrant->lname.' added to team '.$team->name);
        }
    }

    public function teamLeave(Event $event, Registrant $registrant)
    {
        $teams = $event->teams->pluck('id')->toArray();
        $teammember = $registrant->teams->whereIn('team_id', $teams)->first();
        $team = Team::where('id', $teammember->team_id)->first();
        if (! is_null($team) && ! empty($team)) {
            TeamMember::where('team_id', $team->id)->where('registrant_id', $registrant->id)->delete();
            // $team = Team::where("id",$team->id)->first();
            if ($team->captain->id == $registrant->id) {
                TeamMember::where('team_id', $team->id)->delete();
                $team->delete();
            } elseif ($team->members->count() == 0) {
                $team->delete();
            }
        }
        echo 'true';
    }

    private function previouslyModerated($registrant)
    {
        if ($registrant->user_id > 0) {
            $previous = Registrant::where('user_id', $registrant->user_id)->where('id', '<>', $registrant->id)->where('moderated', 1)->where('reviewed', 1)->get();
            if ($previous->count() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function personalPage(Request $request, Registrant $registrant)
    {
        $this->validate($request, [
            'pagetitle'.$registrant->event_id => 'nullable|string|max:100',
        ]);

        if ($registrant->reviewed == 0 && $registrant->moderated == 1) {
            $registrant->moderated = 0;
            $registrant->reviewed = 0;
            $registrant->adminnotes = '';
        } elseif ($registrant->moderated == 0) {
            if ($this->previouslyModerated($registrant)) {
                $registrant->moderated = 1;
                $registrant->reviewed = 1;
            }
        }
        $registrant->pagetitle = $request->{'pagetitle'.$registrant->event_id};
        $registrant->pagecontent = htmlspecialchars($request->{'pagecontent'.$registrant->event_id});
        $registrant->goal = str_replace('$', '', $request->{'pagegoal'.$registrant->event_id});
        if (! empty($registrant->pagetitle)) {
            $registrant->pageurl = config('app.url').'/event/personal/'.$registrant->event->slug.'/'.$registrant->slug;
            $registrant->pageshorturl = $registrant->pageurl;
        }
        $registrant->save();

        return \Redirect::route('home')->with('message', 'Your personal page has been saved'.((! $registrant->moderated) ? ' and sent to an administrator for approval.' : '.'));
    }

    public function teamPage(Request $request, Team $team)
    {
        $this->validate($request, [
            'teampagetitle'.$team->event_id => 'nullable|string|max:100',
        ]);

        if ($team->reviewed == 0 && $team->moderated == 1) {
            $team->moderated = 0;
            $team->reviewed = 0;
            $team->adminnotes = '';
        } elseif ($team->moderated == 0) {
            if ($this->previouslyModerated($team)) {
                $team->moderated = 1;
                $team->reviewed = 1;
            }
        }
        $team->pagetitle = $request->{'teampagetitle'.$team->event_id};
        $team->pagecontent = htmlspecialchars($request->{'teampagecontent'.$team->event_id});
        $team->goal = str_replace('$', '', $request->{'teampagegoal'.$team->event_id});
        if (! empty($team->pagetitle)) {
            $team->pageurl = config('app.url').'/event/team/'.$team->event->slug.'/'.$team->slug;
            $team->pageshorturl = $team->pageurl;
        }
        $team->save();

        return \Redirect::route('home')->with('message', 'Your team page has been saved'.((! $team->moderated) ? ' and sent to an administrator for approval.' : '.'));
    }

    public function volunteer(Event $event): View
    {
        return view('volunteers.form', compact('event'));
    }

    public function volunteerSubmission(Request $request, Event $event): View
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
        ];

        $volunteer = \App\Models\VolunteerSubmission::create($create);

        $emails = explode(',', env('VOLUNTEER_EMAIL'));
        \Mail::to($emails)->send(new \App\Mail\VolunteerAdmin($volunteer, $event));

        return view('volunteers.thanks', compact('volunteer', 'event'));
    }

    public function pageList(Event $event): View
    {
        foreach ($event->participants as &$registrant) {
            try {
                if (empty($registrant->pageurl) && ! is_null($registrant->pageurl)) {
                    $registrant->pageurl = config('app.url').'/event/personal/'.$event->slug.'/'.$registrant->slug;
                    $registrant->pageshorturl = $registrant->pageurl;
                    $registrant->save();
                }
                $registrant->page = $registrant->pageurl;
                $registrant->page_short = $registrant->pageshorturl;
            } catch (\Exception $e) {
            }
        }
        foreach ($event->teams as &$team) {
            try {
                if (empty($team->pageurl) && ! is_null($team->pageurl)) {
                    $team->pageurl = config('app.url').'/event/team/'.$event->slug.'/'.$team->slug;
                    $team->pageshorturl = $team->pageurl;
                    $team->save();
                }
                $team->page = $team->pageurl;
                $team->page_short = $team->pageshorturl;
            } catch (\Exception $e) {
            }
        }

        return view('event.pagelist', compact('event'));
    }

    public function registrantSearch(): View
    {
        $events = Event::get()->pluck('title', 'id')->toArray();

        return view('event.registrantsearch', compact('events'));
    }

    public function registrantSearchResult(Request $request)
    {
        $where[] = ['event_id', $request->event];
        if (! empty($request->fname)) {
            $where[] = ['fname', 'like', '%'.$request->fname.'%'];
        }
        if (! empty($request->lname)) {
            $where[] = ['lname', 'like', '%'.$request->lname.'%'];
        }
        if (! empty($request->email)) {
            $where[] = ['email', 'like', '%'.$request->email.'%'];
        }
        $registrants = Registrant::where($where)->orderBy('lname')->orderBy('fname')->get();

        echo view('parts.registrantSearchResult', compact('registrants'));
        exit();
    }
}
