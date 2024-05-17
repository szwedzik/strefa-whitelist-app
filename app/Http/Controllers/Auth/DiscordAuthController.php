<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;

class DiscordAuthController extends Controller
{

    public function __invoke()
    {
        $code = request()->query('code');
        if ($code == null)
        {
            return redirect('https://discordapp.com/api/oauth2/authorize?client_id=663739630813839370&redirect_uri=https%3A%2F%2Faplikacje.strefarp.pl%2Fauth&response_type=code&scope=identify%20guilds');
        }

        $client = new Client();
        $headers = [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ]
        ];
        $formParams = [
            'form_params' => [
                'client_id'     => env('DISCORD_CLIENT_ID'),
                'client_secret' => env('DISCORD_CLIENT_SECRET'),
                'grant_type'    => 'authorization_code',
                'code'          => $code,
                'redirect_uri'  => 'https://aplikacje.strefarp.pl/auth',
                'scope'         => 'identify guilds'
            ]
        ];

        try
        {
            $response = $client->post('https://discordapp.com/api/v6/oauth2/token', $formParams, $headers);
        }
        catch (ClientException $exception)
        {
            return abort(403);
        }

        $responseBody = json_decode($response->getBody()->getContents());

        $token = $responseBody->access_token;
        $tokenExpiration = floor($responseBody->expires_in / 60);

        return redirect('/')->withCookie('token', $token, $tokenExpiration);
    }
}
