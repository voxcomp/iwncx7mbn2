<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public static function levelCost($level)
    {
        switch ($level) {
            case 'Guardian': return 4500;
            case 'Stage': return 2500;
            case 'Golden Retriever': return 2300;
            case 'Heroes': return 1500;
            case 'Inspiring Hope': return 1500;
            case 'Hound': return 1200;
            case 'Veterinary Partner': return 600;
            case 'Bulldog': return 200;
            case 'Non-Profit Partner': return 100;
            case 'Shepherd': return 0;
        }
    }
}
