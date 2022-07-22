<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class GetCity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get city name';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
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
    }
}
