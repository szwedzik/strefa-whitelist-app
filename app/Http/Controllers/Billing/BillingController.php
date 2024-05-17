<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Payment;
use App\TempApplication;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Cookie;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use RestCord\DiscordClient;
use Illuminate\Support\Facades\Log;

class BillingController extends Controller
{
    public function handlePayment()
    {
        $data = request()->json()->all();
        $orderId = $data['orderId'];
        $appId = $data['appId'];

        if ($orderId === null || $appId === null) {
            return abort(500, "No order ID and/or application ID received.");
        }

        $ppClient = PayPalClient::client();
        $ppResponse = $ppClient->execute(new OrdersGetRequest($orderId));

        $dClient = new Client();
        $headers = [
            'headers' => [
                'Authorization' => 'Bearer ' . Cookie::get('token')
            ]
        ];

        try
        {
            $dResponse = $dClient->get('https://discordapp.com/api/v6/users/@me', $headers);
        }
        catch (ClientException $exception)
        {
            return abort(403);
        }

        $responseBody = json_decode($dResponse->getBody()->getContents());
        $id = $responseBody->id;

        $payment = new Payment();
        $payment->paypal_id = $ppResponse->result->id;
        $payment->discord_id = $id;
        $payment->full_name = $ppResponse->result->payer->name->given_name . ' ' . $ppResponse->result->payer->name->surname;
        $payment->email = $ppResponse->result->payer->email_address;
        $payment->ip = request()->ip();
        $payment->amount = $ppResponse->result->purchase_units[0]->amount->value;
        $payment->currency = $ppResponse->result->purchase_units[0]->amount->currency_code;

        $payment->save();

        $application = TempApplication::where('uuid', $appId)->get()->first();

        if ($ppResponse->result->purchase_units[0]->amount->value < 20.0) {
            $discord = new DiscordClient(['token' => 'NTQ0MTc4NTI4NDQzMTcwODI2.D0Le7g.1y_9g0glJFXwF6qsfi6i_tojzw4']);
            $userDmChannel = $discord->user->createDm(array("recipient_id" => (int) $application->discord_id));
            $dmChannelId = $userDmChannel->id;

            $discord->channel->createMessage([
                'channel.id' => $dmChannelId,
                'file' => file_get_contents('x.png')
            ]);

            return redirect('/');
        }

        TempApplication::where('uuid', $appId)->update(['state' => 666]);
        $client = new Client();
        $headers = [
            'headers' => [
                'Authorization' => 'Bot NTQ0MTc4NTI4NDQzMTcwODI2.D0Le7g.1y_9g0glJFXwF6qsfi6i_tojzw4'
            ]
        ];
        try
        {
            $response = $client->get('https://discordapp.com/api/v6/users/'.$tempApp->discord_id, $headers);
        }
        catch (ClientException $exception)
        {
            return abort(403);
        }
        $responseBody = json_decode($response->getBody()->getContents());
        $nick = $responseBody->username.'#'.$responseBody->discriminator;

        $webhook = "https://discordapp.com/api/webhooks/577183243955208202/cVUtQukSGFGsk1RWE4UObym5Z5HYlnbYwHPNM-z9c3xNNcEIJ4AtCcQQaUEeDh9P5W__";
        $hookObject = json_encode([
            "username" => "StrefaRP.pl • System aplikacji",
            "avatar_url" => "https://cdn.discordapp.com/avatars/567021813960278020/9eaade8b14136a684e3199501e64cc8c.png?size=128",
            "tts" => false,
            'content' => '@everyone',
            "embeds" => [
                [
                    "type" => "rich",
                    "description" => "",
                    "timestamp" => date('Y-m-d').'T'.date("H:i:s").'.'.date("v").'Z',
                    "color" => hexdec( "FFFF00" ),
                    "footer" => [
                        "text" => "StrefaRP.pl • System aplikacji",
                    ],
                    "image" => [
                        "url" => ""
                    ],
                    "thumbnail" => [
                        "url" => ""
                    ],
                    "author" => [
                        "name" => $nick,
                        "icon_url" =>  $profilePicture
                    ],
                    "fields" => [
                        [
                            "name" => "Nowa aplikacja",
                            "value" => '[Kliknij, aby otworzyć podgląd...](https://aplikacje.strefarp.pl/admin/'.$tempApp->uuid.')',
                        ],
                        [
                            "name" => "Status:",
                            "value" => "**Priorytet**",
                        ],
                        [
                            "name" => "Kampania",
                            "value" => "StrefaRP.pl - Whitelist",
                        ]
                    ]
                ]
            ]

        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

        $ch = curl_init();

        curl_setopt_array( $ch, [
            CURLOPT_URL => $webhook,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $hookObject,
            CURLOPT_HTTPHEADER => [
                "Length" => strlen( $hookObject ),
                "Content-Type" => "application/json"
            ]
        ]);
        $response = curl_exec( $ch );
        curl_close( $ch );

        return redirect('/');
    }
}
