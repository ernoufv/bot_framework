<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Bot\Helpers\PersonaHelper;
use Illuminate\Http\Request;

class PersonaController extends BaseController
{

    public function createPersona(Request $request)
    {
        $persona = new PersonaHelper($request);
        return $persona->createPersona();
    }

    public function retrievePersonas(Request $request)
    {
        $persona = new PersonaHelper($request);
        return $persona->retrievePersonas();
    }

    public function deletePersona(Request $request, $personaId)
    {
        $persona = new PersonaHelper($request);
        return $persona->deletePersona($personaId);
    }

}
