<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Bot\Helpers\ConfigurationHelper;
use Illuminate\Http\Request;

class ConfigurationController extends BaseController
{

    public function fbBotConfiguration(Request $request)
    {
        $config = new ConfigurationHelper($request);
        return $config->fbBotConfiguration();
    }

}
