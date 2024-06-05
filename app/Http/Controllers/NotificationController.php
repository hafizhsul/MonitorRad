<?php

namespace App\Http\Controllers;

use Twilio\Rest\Client;

class NotificationController extends Controller
{
    public function sendNotification()
    {
        $twilioSid = 'AC8e7cb2bf130940dc159ade4bbf03aafc';
        $twilioToken = '487919387ac381a9e4329c395105aa5f';
        $twilioWhatsAppNumber = 'whatsapp:+14155238886';
        $recipientNumber = 'whatsapp:+6282235769474';
        $message = "*Peringatan*. Tingkat radiasi di sekitar tinggi !!";

        $twilio = new Client($twilioSid, $twilioToken);

        try {
            $twilio->messages->create(
                $recipientNumber,
                [
                    "from" => $twilioWhatsAppNumber,
                    "body" => $message,
                ]
            );

            return response()->json(['message' => 'WhatsApp message sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
