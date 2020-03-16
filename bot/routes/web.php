<?php


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Illuminate\Http\Request;

$router->get('/', function () use ($router) {
    return "Bot Root";
});

/**
 * 
 * Configuration Endpoint (POST)
 * 
 */

$router->post('/fb/config', [
    'as' => 'config', 'uses' => 'ConfigurationController@fbBotConfiguration'
]);

/**
 * 
 * Personas endpoints
 * 
 */

$router->post('/fb/persona/create', [
    'as' => 'persona', 'uses' => 'PersonaController@createPersona'
]);

$router->get('/fb/persona/retrieve', [
    'as' => 'persona', 'uses' => 'PersonaController@retrievePersonas'
]);

$router->delete('/fb/persona/{personaId}', [
    'as' => 'persona', 'uses' => 'PersonaController@deletePersona'
]);


/**
 * 
 * Facebook Webhook validation Endpoint (GET)
 * 
 */

$router->get('/messaging', [
    'as' => 'messaging', 'uses' => 'EventDispatcherController@fbChallengeEvent'
]);

/**
 * 
 * Messenging Endpoints (POST)
 * 
 */

$router->post('/messaging', [
    'as' => 'messaging', 'uses' => 'EventDispatcherController@eventDispatcher'
]);

/**
 * 
 * Project configuration
 * 
 */


$router->get('/key', function() {
    return str_random(32);
});
