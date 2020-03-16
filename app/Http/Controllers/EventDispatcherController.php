<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Bot\Helpers\EventHelper;
use Illuminate\Http\Request;

class EventDispatcherController extends BaseController
{

    public function eventDispatcher(Request $request)
    {
        $eventHelper = new EventHelper($request);
        return $eventHelper->eventHelper();
    }

    public function fbChallengeEvent(Request $request)
    {
        $eventHelper = new EventHelper($request);
        return $eventHelper->fbChallengeEvent();
    }


}
