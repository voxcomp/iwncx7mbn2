<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public static function levelCost($level) {
	    switch($level) {
		    case "Guardian": return 4500;
			case "Stage": return 2500;
		    case "Golden Retriever": return 2300;
			case "Heroes": return 1500;
			case "Inspiring Hope": return 1500;
		    case "Hound": return 1200;
			case "Veterinary Partner": return 600;
		    case "Bulldog": return 200;
			case "Non-Profit Partner": return 100;
		    case "Shepherd": return 0;
	    }
    }
}
