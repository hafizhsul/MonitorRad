<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class NotificationController extends Controller
{
    public function sendNotification()
    {
        $message = 'Peringatan, level radiasi tinggi!!';
        $chatId = '1152076122';
        $botToken = '7281018577:AAEjj1uh5jsuqKpRDvlc-EgqzxJPFKxrMhw';

        $client = new Client();
        $response = $client->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
            'form_params' => [
                'chat_id' => $chatId,
                'text' => $message,
            ]
        ]);

        return $response->getBody();
    }
}
