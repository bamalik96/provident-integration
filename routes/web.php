<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('api/v1/properties', [
    'as' => 'properties', 'uses' => 'WebServicePropertyController@index'
]);

$router->get('api/v1/google-reviews', [
    'as' => 'google-reviews', 'uses' => 'GoogleReviewsController@index'
]);

$router->get('api/v1/google-reviews/callback', [
    'as' => 'google-reviews-callback', 'uses' => 'GoogleReviewsController@callBackGoogle'
]);



$router->get('instagram/auth/callback', '\Dymantic\InstagramFeed\AccessTokenController@handleRedirect');
