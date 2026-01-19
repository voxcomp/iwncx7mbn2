<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Donation;
use App\Models\Event;
use App\Models\Registrant;
use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.  Entire controller requires logged in user
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function personalpageReview(): View
    {
        $registrants = Registrant::where('moderated', 0)->where(function ($query) {
            $query->whereNotNull('pagetitle')->Where('pagetitle', '<>', '');
        })->get();
        $teams = Team::where('moderated', 0)->where(function ($query) {
            $query->whereNotNull('pagetitle')->Where('pagetitle', '<>', '');
        })->get();

        return view('admin.personalpage', compact('registrants', 'teams'));
    }

    public function registrantView(Registrant $registrant): View
    {
        $event = $registrant->event;
        $event->registrant = $registrant;
        $event->personal_goal = $registrant->goal;
        $event->personal_raised = $event->donations->where('registrant_id', $registrant->id)->sum('amount');
        if ($event->teams->count()) {
            $teammember = TeamMember::where('registrant_id', $registrant->id)->whereHas('team', function ($query) use ($event) {
                $teams = Team::where('event_id', $event->id)->get(['id'])->pluck('id')->toArray();
                $query->whereIn('team_id', $teams);
            })->first();
            if (! is_null($teammember) && ! empty($teammember)) {
                $event->team = $teammember->team;
            }
        }

        return view('admin.registrant', compact('event'));
    }

    public function registrantEdit(Registrant $registrant): View
    {
        $user = $registrant;

        return view('admin.registrant-edit', compact('registrant', 'user'));
    }

    public function registrantSave(Request $request, Registrant $registrant): View
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
            'shirt' => 'nullable|string|max:6',
            'pets' => 'nullable|integer',
            'shipaddress' => 'nullable|string|max:200',
            'shipcity' => 'nullable|string|max:75',
            'shipstate' => 'nullable|string|max:2',
            'shipzip' => 'nullable|string|max:10',
        ]);

        if ($registrant) {
            $registrant->fname = $request->fname;
            $registrant->lname = $request->lname;
            $registrant->email = $request->email;
            $registrant->phone = $request->phone;
            $registrant->address = $request->address;
            $registrant->city = $request->city;
            $registrant->state = $request->state;
            $registrant->zip = $request->zip;
            $registrant->shirt = $request->shirt;
            $registrant->pets = $request->pets;
            $registrant->shipshirt = (isset($request->shipshirt)) ? $request->shipshirt : 0;
            $registrant->shipaddress = $request->shipaddress;
            $registrant->shipcity = $request->shipcity;
            $registrant->shipstate = $request->shipstate;
            $registrant->shipzip = $request->shipzip;
            if (isset($request->pagetitle)) {
                $registrant->pagetitle = htmlspecialchars($request->pagetitle);
            }
            if (isset($request->pagecontent)) {
                $registrant->pagecontent = htmlspecialchars($request->pagecontent);
            }
            $registrant->save();

            if (! is_null($registrant->team)) {
                if (isset($request->teampagetitle)) {
                    $registrant->team->pagetitle = htmlspecialchars($request->teampagetitle);
                }
                if (isset($request->teampagecontent)) {
                    $registrant->team->pagecontent = htmlspecialchars($request->teampagecontent);
                }
                $registrant->team->save();
            }

            $event = $registrant->event;
            $event->registrant = $registrant;
            $event->personal_goal = $registrant->goal;
            $event->personal_raised = $event->donations->where('registrant_id', $registrant->id)->sum('amount');
            if ($event->teams->count()) {
                $teammember = TeamMember::where('registrant_id', $registrant->id)->whereHas('team', function ($query) use ($event) {
                    $teams = Team::where('event_id', $event->id)->get(['id'])->pluck('id')->toArray();
                    $query->whereIn('team_id', $teams);
                })->first();
                if (! is_null($teammember) && ! empty($teammember)) {
                    $event->team = $teammember->team;
                }
            }
        }

        return view('admin.registrant', compact('event'))->with('message', 'Registrant information saved.');
    }

    /**
     * Show coupon page.  If coupon code id sent, populate form fields
     */
    public function coupons(Coupon $coupon): View
    {
        $coupons = Coupon::all();
        if (! is_null($coupon->id)) {
            $existing = ['id' => $coupon->id, 'name' => $coupon->name, 'amount' => $coupon->amount, 'discount_type' => $coupon->discount_type, 'valid_from' => date('m/d/Y', $coupon->valid_from), 'valid_to' => date('m/d/Y', $coupon->valid_to), 'maxuse' => $coupon->maxuse, 'active' => $coupon->active];

            return view('admin.coupons', compact('existing', 'coupons'));
        } else {
            return view('admin.coupons', compact('coupons'));
        }
    }

    /**
     * Save new coupon from form.
     *
     * @return \Illuminate\Http\Response
     */
    public function couponCreate(Request $request)
    {
        // validate post vars from form
        $request->validate([
            'name' => 'required|string|max:25|unique:coupons',
            'amount' => 'required|numeric',
            'maxuse' => 'required|integer',
            'valid_from' => 'required|date_format:m/d/Y|max:10',
            'valid_to' => 'required|date_format:m/d/Y|max:10|after:'.$request->valid_from,
            'discount_type' => 'required|string|max:8|in:dollar,percent',
            'active' => 'required|integer|max:1',
        ]);

        // create new coupon
        $coupon = new Coupon;
        $coupon->name = $request->name;
        $coupon->amount = $request->amount;
        $coupon->maxuse = $request->maxuse;
        $coupon->valid_from = strtotime($request->valid_from);
        $coupon->valid_to = strtotime($request->valid_to);
        $coupon->active = $request->active;
        $coupon->discount_type = $request->discount_type;
        $coupon->save();

        return back()->with('message', 'Discount Code has been saved.');
    }

    /**
     * Save updated coupon from form.
     */
    public function couponSave(Request $request): RedirectResponse
    {
        $coupon = Coupon::where('id', '=', $request->id)->first();
        if (! empty($coupon)) {
            // validate post vars from form
            $request->validate([
                'name' => 'required|string|max:25',
                'amount' => 'required|numeric',
                'maxuse' => 'required|integer',
                'valid_from' => 'required|date_format:m/d/Y|max:10',
                'valid_to' => 'required|date_format:m/d/Y|max:10|after:'.$request->valid_from,
                'discount_type' => 'required|string|max:8|in:dollar,percent',
                'active' => 'required|integer|max:1',
            ]);

            // update coupon
            $updatable = [
                'name' => $request->name,
                'amount' => $request->amount,
                'maxuse' => $request->maxuse,
                'valid_from' => strtotime($request->valid_from),
                'valid_to' => strtotime($request->valid_to),
                'active' => $request->active,
                'discount_type' => $request->discount_type,
            ];
            $coupon->update($updatable);
        }

        return redirect()->route('coupons')->with('message', 'Discount Code has been saved.');
    }

    /**
     * Delete coupon.
     */
    public function couponDelete(Coupon $coupon)
    {
        $coupon->delete();
        echo 'confirm';
    }

    public function donations(): View
    {
        $donations = Donation::orderBy('created_at', 'desc')->get();

        return view('admin.donations', compact('donations'));
    }

    public function donationDelete(Donation $donation)
    {
        $donation->delete();
        echo 'confirm';
    }

    public function donationEdit(Donation $donation): View
    {
        $events = Event::orderBy('event_date', 'desc')->orderBy('title', 'asc')->get();
        $registrants = Registrant::orderBy('lname', 'asc')->orderBy('fname', 'asc')->get();
        $teams = Team::orderBy('name', 'asc')->get();

        return view('admin.donationEdit', compact('donation', 'events', 'registrants', 'teams'));
    }

    public function donationSave(Request $request, Donation $donation)
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
        ]);

        $donation->fname = $request->fname;
        $donation->lname = $request->lname;
        $donation->address = $request->address;
        $donation->city = $request->city;
        $donation->state = $request->state;
        $donation->zip = $request->zip;
        $donation->phone = $request->phone;
        $donation->email = $request->email;
        $donation->registrant_id = $request->registrant;
        $donation->event_id = $request->event;
        $donation->team_id = $request->team;
        $donation->save();

        return \Redirect::route('admin.donations')->with('message', 'Donation saved.');
    }
}
