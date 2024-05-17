<?php


namespace App\Http\Controllers\Application;


use App\TempApplication;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;

class TempApplicationController
{
    public function displayApplicationCreation()
    {
        $client = new Client();
        $headers = [
            'headers' => [
                'Authorization' => 'Bearer ' . Cookie::get('token')
            ]
        ];

        try
        {
            $response = $client->get('https://discordapp.com/api/v6/users/@me', $headers);
        }
        catch (ClientException $exception)
        {
            return abort(403);
        }

        $responseBody = json_decode($response->getBody()->getContents());
        $profilePicture = 'https://cdn.discordapp.com/avatars/' . $responseBody->id . '/' . $responseBody->avatar . '.png';

        return view('pages.user.composeTemp')->with('profilePicture', $profilePicture);
    }

    public function handleApplicationCreation()
    {
        $client = new Client();
        $headers = [
            'headers' => [
                'Authorization' => 'Bearer ' . Cookie::get('token')
            ]
        ];

        try
        {
            $response = $client->get('https://discordapp.com/api/v6/users/@me', $headers);
        }
        catch (ClientException $exception)
        {
            return abort(403);
        }

        $responseBody = json_decode($response->getBody()->getContents());
        $id = $responseBody->id;
        $profilePicture = 'https://cdn.discordapp.com/avatars/' . $responseBody->id . '/' . $responseBody->avatar . '.png';

        $data = Request::all();
        unset($data['_token']);

        $tempApp = new TempApplication($data);
        $tempApp->discord_id = $id;
        $tempApp->state = 0;
        $tempApp->save();

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

        $webhook = "https://discordapp.com/api/webhooks/567021813960278020/8GzCYR3eJXIM97GKVnl3Y30ZXbbw-4TWpJ4BbYvhlABhdRxttvo-W7meuxY1kMRWaLnj";
        $hookObject = json_encode([
            "username" => "StrefaRP.pl • System aplikacji",
            "avatar_url" => "https://cdn.discordapp.com/avatars/567021813960278020/9eaade8b14136a684e3199501e64cc8c.png?size=128",
            "tts" => false,
            "embeds" => [
                [
                    "type" => "rich",
                    "description" => "",
                    "timestamp" => date('Y-m-d').'T'.date("H:i:s").'.'.date("v").'Z',
                    "color" => hexdec( "546e7a" ),
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
                            "value" => "**W trakcie sprawdzania**",
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

        session()->flash('success', 'Podanie zostało wysłane!');

        return redirect('/');
    }
}
