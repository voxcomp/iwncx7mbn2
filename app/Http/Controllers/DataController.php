<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\User;
use App\Team;
use App\Registrant;
use App\Event;
use App\Coupon;

class DataController extends Controller
{
    /**
     * Check if email address unique.  Prints true if email address is not found.
     * @param string
     *
     * @return bool
     */
	public function isUniqueUsername($username = NULL) {
		try {
			$user=User::where("username","=",$username)->first();
			if(empty($user)) {
				print "true";
				die();
			}
		} catch(\Exception $e) {
		}
		print "false";
		die();
	}

    /**
     * Check if team name is unique.  Prints true if name is not found.
     * @param string
     *
     * @return bool
     */
	public function isUniqueTeam(Event $event, $name = NULL) {
		try {
			$team=Team::where("name","=",str_replace("~"," ",$name))->where('event_id',$event->id)->first();
			if(empty($team)) {
				print "true";
				die();
			}
		} catch(\Exception $e) {
		}
		print "false";
		die();
	}

    /**
     * Check coupon code.  Prints true if usable.
     * @param string $coupon
     *
     */
	public function coupon($coupon = NULL, $amount) {
		if(!is_null($coupon)) {
			$coupon = Coupon::where("name","=",$coupon)->first();
			if(!empty($coupon)) {
				if($coupon->isUsable()) {
					print json_encode(['status'=>true,'value'=>$coupon->value($amount)]);
					die();
				}
			}
		}
		print json_encode(['status'=>false]);
		die();
	}

    /**
     * Save the specified profile photo. Print/return new image name and path, both public and private urls
     * @param App\Http\Requests $request
     *
     */
    public function profilePhoto(Request $request) {
	    $valid = $this->validate($request,[
		    'photo' => 'mimes:png,jpg,jpeg|dimensions:min_width=200,min_height=200'
	    ]);

	    $image = $request->file('photo');

	    $input['imagename']=time().'-'.$image->getClientOriginalName();
	    
	    $destinationPath = \Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().'temp';
	    
		$img = \Image::make($image->getRealPath())->resize(800, 800, function ($constraint) {
		    $constraint->aspectRatio();
			$constraint->upsize();
		})->save($destinationPath.'/'.$input['imagename']);
		
		print json_encode(["image"=>\Storage::disk('local')->url('temp/'.$input['imagename']),"file"=>"temp/".$input['imagename']]);
    }
    
    /**
     * Save the cropped profile photo. Print/return new image name and path
     * @param App\Http\Requests $request
     *
     */
    public function profileCroppedPhotodead() {
	    print "dead";
	}
    public function profileCroppedPhoto(Request $request) {
	    //$original = $request->original;
	    $data = $request->photoData;
	    $path = $request->path;
	    if(empty($data)) { // || empty($original)) {
			abort(422,'Inadequate Input');
	    }
		
		//\Storage::disk('local')->delete($original);
		$destinationPath = \Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().'temp';
		$profilePath = \Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().$path;
		$file = base64_decode(str_replace(['data:image/png;base64,',' '], ['','+'], $data));
		$filename = microtime(true) . ".jpg";
		$success = file_put_contents($destinationPath.'/'.$filename,$file);

		if($path=='profile') {
			$img = \Image::make($destinationPath.'/'.$filename)->resize(300, 300, function ($constraint) {
			    $constraint->aspectRatio();
				$constraint->upsize();
			})->mask($profilePath.'/mask.png')->save($destinationPath.'/'.$filename);
		} else {
			$img = \Image::make($destinationPath.'/'.$filename)->resize(800, 800, function ($constraint) {
			    $constraint->aspectRatio();
				$constraint->upsize();
			})->save($destinationPath.'/'.$filename);
		}
		
		print json_encode(["image"=>\Storage::disk('local')->url('temp/'.$filename),"file"=>"temp/".$filename]);
    }
    
    public function personalpageFail(Request $request,Registrant $registrant) {
	    if(!is_null($registrant->id)) {
		    if(!is_null($request->data) && !empty($request->data)) {
				$registrant->adminnotes=$request->data;
			}
		    $registrant->moderated=1;
		    $registrant->reviewed=0; 
		    $registrant->save();

			\Mail::to($registrant->email)->send(new \App\Mail\PersonalPageRejected($registrant));
		}
    }
    
    public function personalpagePass(Request $request,Registrant $registrant) {
	    if(!is_null($registrant->id)) {
		    $registrant->moderated=1;
		    $registrant->reviewed=1;
		    $registrant->save();

			\Mail::to($registrant->email)->send(new \App\Mail\PersonalPageApproved($registrant));
		}
    }

    public function teampageFail(Request $request,Team $team) {
	    if(!is_null($team->id)) {
		    if(!is_null($request->data) && !empty($request->data)) {
				$team->adminnotes=$request->data;
			}
		    $team->moderated=1;
		    $team->reviewed=0; 
		    $team->save();

			\Mail::to($team->captain->email)->send(new \App\Mail\TeamPageRejected($team));
		}
    }
    
    public function teampagePass(Request $request,Team $team) {
	    if(!is_null($team->id)) {
		    $team->moderated=1;
		    $team->reviewed=1;
		    $team->save();

			\Mail::to($team->captain->email)->send(new \App\Mail\TeamPageApproved($team));
		}
    }
}
