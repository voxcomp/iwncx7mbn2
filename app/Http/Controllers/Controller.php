<?php

namespace App\Http\Controllers;


class Controller
{

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
