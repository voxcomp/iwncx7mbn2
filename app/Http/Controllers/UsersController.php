<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Event;
use App\Team;
use App\TeamMember;
use App\Registrant;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.  Entire controller requires logged in user
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Show home page of logged in user
     *
     * @return \Illuminate\Http\Response
     */
    public function home() {
	    if(\Auth::user()->isAdmin()) {
		    return \Redirect::route('event.list');
		} else {
			$user = \Auth::user();
			$events = Event::where('event_date','>',time())->whereDoesntHave('participants',function($query) use ($user) {
				$query->where('user_id',$user->id);
			})->get();
			$myevents = Event::whereHas('participants',function($query) use ($user) {
				$query->where('user_id',$user->id);
			})->orderBy('event_date','DESC')->get();
			foreach($myevents as &$event) {
				$registrant = $event->participants->where('user_id',$user->id)->first();
				$event->registrant = $registrant;
				if(!empty($registrant->pagetitle)) {
					$event->personal_page = $registrant->pageurl;
					$event->personal_page_short = $registrant->pageshorturl;
				}
				$event->personal_goal = $registrant->goal;
				$event->personal_raised = $event->donations->where('registrant_id',$registrant->id)->sum('amount');
				if($event->teams->count()) {
					$team = $event->teams->where('registrant_id',$registrant->id)->first();
					if(is_null($team) || empty($team)) {
						$teammember = TeamMember::where('registrant_id',$registrant->id)->whereHas('team',function($query) use ($event) {
							$teams = Team::where('event_id',$event->id)->get(['id'])->pluck('id')->toArray();
							$query->whereIn('team_id',$teams);
						})->first();
						if(!is_null($teammember) && !empty($teammember)) {
							$team = $teammember->team;
							$event->team = $team;
						}
					} else {
						$event->team = $team;
					}
					if(isset($event->team)) {
						$event->team->page = $team->pageurl;
						$event->team->page_short = $team->pageshorturl;
					}
				}
			}
			return view('user.home',compact('events','myevents'));
		}
    }

    /**
     * Show/edit profile of logged in user
     * @param App\User
     *
     * @return \Illuminate\Http\Response
     */
    public function profile(User $user=null) {
	    if(!is_null($user->id)) {
		    if(\Auth::user()->isAdmin()) {
			    return view('user.profile',compact('user'));
			} else {
				return view('pages.unauthorized');
			}
		} else {
			return view('user.profile',['user'=>\Auth::user()]);
		}
    }

    /**
     * Update member profile from form submission.
     * @param App\Http\Requests $request (form submission data)
     *
     * @return \Illuminate\Http\Response
     */
	public function update(Request $request) {
		$isadmin = false;
		if(\Auth::user()->isAdmin() && !(!isset($request->cred) || empty($request->cred))) {
			$isadmin = true;
		} else {
			$viewredirect = '/home';
		}
		$errorredirect = '/account';
		// test if request has UID (cred) field, if so the member is being edited by an admin and does not need current password
		if(!$isadmin) {
			$user = User::where('id',\Auth::user()->id)->first();
			
			// require current password if new password sent
			if(empty($request->current_password) && !empty($request->password)) {
				return \Redirect::to($errorredirect)->with('message','There was an error, please see the form below for specifics.')->withErrors(['current_password'=>'Your current password is required.']);
			} elseif(!empty($request->current_password) && !empty($request->password)) {
				if(!\Auth::validate(['username'=>$user->username,'password'=>$request->current_password])) {
					return \Redirect::to($errorredirect)->with('message','There was an error, please see the form below for specifics.')->withErrors(['current_password'=>'The password entered is incorrect.']);
				}
			}
		} else {
			$request->cred = \Crypt::decrypt($request->cred);
			$user = User::where('username',$request->cred)->first();
		}

		// validate request data
		$tovalidate = [
            'fname' => 'required|string|max:50',
            'lname' => 'required|string|max:75',
            'email' => 'required|email|max:150',
            'phone' => 'max:12|nullable|regex:/\d{3}[-]\d{3}[-]\d{4}/',
            'zip' => 'max:5|nullable'
		];
		// admin is editing user, include additional profile fields
		if($isadmin) {
			$tovalidate=array_merge($tovalidate,[
	            'user_type' => 'required|string|max:6|in:super,admin,auth',
			]);
			
			if($user->username!=$request->username) {
				$tovalidate=array_merge($tovalidate,[
		            'username' => 'required|unique:users|string|min:5|max:20',
				]);
			}
		}
		if(!empty($request->password)) {
            $tovalidate['password'] = 'required|min:6|max:25|confirmed';
		}
		$this->validate($request,$tovalidate);
	    
		$updatable=[
			'fname'=>$request->fname,
			'lname'=>$request->lname,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
		];
		$updatable['join'] = (isset($request->join))?1:0;

		// check if new profile photo uploaded and save it
	    if(strpos($request->photo,"temp/")!==false) {
		    try {
			    \Storage::disk('local')->delete($user->photo);
			} catch(\Exception $e) { }
		    $filename=\Crypt::encrypt($request->email);
		    $filename='profile/'.substr($filename,(round(strlen($filename)/2))).substr($request->photo,-4);
		    \Storage::disk('local')->move($request->photo, $filename);
		    $updatable['photo']=$filename;
	    }
		// if profile is edited by admin, save additional profile fields
		if($isadmin) {
			$updatable=array_merge($updatable,[
				'user_type' => $request->user_type,
				'validated' => 1,
				'username' => $request->username,
				'slug' => str_slug($request->username,'-')
			]);
			$viewredirect = '/user/profile/'.str_slug($request->username,'-');
		}
		// if new password entered
		if(!empty($request->password)) {
			$updatable['password']=bcrypt($request->password);
		}
		
		if($user->validated==0 && $updatable['validated']==1) {
			$newuser = true;
		} else { $newuser = false; }
		$user->update($updatable);

		return \Redirect::to($viewredirect)->with('message',($isadmin)?'User profile updated.':'Your profile has been updated.');
	}

    /**
     * Show/edit profile of logged in user
     * @param App\User
     *
     */
    public function delete(User $user) {
	    if($user->id==\Auth::user()->id) {
		    \Auth::logout();
			Registrant::where('user_id',$user->id)->update(['user_id'=>0]);
		    $user->delete();
		} else {
			if(\Auth::user()->isAdmin()) {
				// get current user
//				$loggedInUser = \Auth::user();
				
				// logout user
				//\Auth::setUser($user);
				//\Auth::logout();
				
				// set again current user
				//\Auth::setUser($loggedInUser);
				Registrant::where('user_id',$user->id)->update(['user_id'=>0]);
				$user->delete();
			}
		}
    }
    
    public function search() {
	    return view("user.search");
    }

	public function searchResult(Request $request) {
		$where=[];
		if(!empty($request->user_type)) {
			$where[]=['user_type',$request->user_type];
		}
		if(!empty($request->username)) {
			$where[]=['username','like','%'.$request->username.'%'];
		}
		if(!empty($request->fname)) {
			$where[]=['fname','like','%'.$request->fname.'%'];
		}
		if(!empty($request->lname)) {
			$where[]=['lname','like','%'.$request->lname.'%'];
		}
		if(!empty($request->email)) {
			$where[]=['email','like','%'.$request->email.'%'];
		}
		$users = User::where($where)->orderBy("lname")->orderBy('fname')->get();
		
		print view('parts.userSearchResult',compact('users'));
		die();
	}
	
	public function create() {
		return view('user.create');
	}

	public function save(Request $request) {
		$this->validate($request,[
            'fname' => 'required|string|max:50',
            'lname' => 'required|string|max:75',
            'email' => 'required|email|max:150',
            'phone' => 'max:12|nullable|regex:/\d{3}[-]\d{3}[-]\d{4}/',
            'zip' => 'max:5|nullable',
			'username' => 'required|string|max:30|unique:users',
			'email' => 'required|email|max:255',
            'user_type' => 'required|string|max:6|in:super,admin,auth',
            'password' => 'required|min:8|confirmed'
		]);
	    
		$updatable=[
			'user_type'=>$request->user_type,
			'username'=>$request->username,
			'password'=>bcrypt($request->password),
			'fname'=>$request->fname,
			'lname'=>$request->lname,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'validated' => 1
		];
		// check if new profile photo uploaded and save it
	    if(strpos($request->photo,"temp/")!==false) {
		    try {
			    \Storage::disk('local')->delete($user->photo);
			} catch(\Exception $e) { }
		    $filename=\Crypt::encrypt($request->email);
		    $filename='profile/'.substr($filename,(round(strlen($filename)/2))).substr($request->photo,-4);
		    \Storage::disk('local')->move($request->photo, $filename);
		    $updatable['photo']=$filename;
	    }
	    $user = User::create($updatable);

        Registrant::where('email',$user->email)->update(['user_id'=>$user->id]);
        if(empty($user->address)) {
	        $registrant = Registrant::orderBy('created_at','DESC')->where('user_id',$user->id)->first();
	        if(!is_null($registrant)) {
		        $user->update([
			        'phone'=>$registrant->phone,
			        'address'=>$registrant->address,
			        'city'=>$registrant->city,
			        'state'=>$registrant->state,
			        'zip'=>$registrant->zip,
		        ]);
	        }
	    }
	    
	    return \Redirect::route('user.create')->with('message','A user account has been created for '.$request->fname.' '.$request->lname.'.');
	}
}
