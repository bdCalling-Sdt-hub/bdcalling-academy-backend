<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\Rest\Client;

class SendSMSController extends Controller
{


    public function send_sms()
    {
        $sid = getenv("TWILIO_SID");
        $token = getenv("TWILIO_TOKEN");
        $sender_number = getenv("TWILIO_PHONE");
        $twilio = new Client($sid, $token);

        $message = $twilio->messages
                        ->create("+880 1317659523", // to
                                [
                                    "body" => "This is the ship that made the Kessel Run in fourteen parsecs?",
                                    "from" => $sender_number
                                ]
                        );
                        return response()->json(['status'=>'success','message'=>'success']);
    }
}
