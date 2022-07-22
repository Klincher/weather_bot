<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/bot/getupdates', function () {
    $updates = Telegram::getUpdates();

    if (!empty($updates)) {
        $city = ($updates[count($updates) - 1]['message']['text']);

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', "http://api.weatherapi.com/v1/current.json?key=3c42e21f7af74634940142342222107&q=$city&aqi=no");
        $data = json_decode($response->getBody(), true)['current'];
        // dd($data);
        $text = 'Время ' . $data['last_updated'] . ', температура ' . $data['temp_c'] . ', ветер ' . $data['wind_dir'] . ', видимость ' . $data['vis_km'];

        Telegram::sendMessage([
            'chat_id' => 642114867,
            'text' => $text
        ]);
    }
    return;
});

// Route::get('/bot/sendmessage', function () {
//     $client = new \GuzzleHttp\Client();
//     $response = $client->request('GET', 'http://api.weatherapi.com/v1/current.json?key=3c42e21f7af74634940142342222107&q={$city}&aqi=no');

//     // echo $response->getStatusCode(); // 200
//     // echo $response->getHeaderLine('content-type'); // 'application/json; charset=utf8'
//     echo $response->getBody(); // '{"id": 1420053, "name": "guzzle", ...}'

//     Telegram::sendMessage([
//         'chat_id' => 642114867,
//         'text' => 'Weather is neochen`!'
//     ]);
// });
