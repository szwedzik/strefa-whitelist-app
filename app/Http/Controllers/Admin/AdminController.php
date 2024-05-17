<?php

namespace App\Http\Controllers\Admin;

use App\Campaign;
use App\Http\Controllers\Controller;
use App\TempApplication;
use app\Http\Controllers\Billing\BillingController;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use App\Classes\SteamAPI;
use RestCord\DiscordClient;
use PDO;
use GuzzleHttp\Psr7\Response;
use Datetime;

class AdminController extends Controller
{

    public function displayCampaignAddition()
    {
        return view('pages.admin.createCampaign');
    }

    public function handleCampaignAddition()
    {
        $data = Request::all();
        unset($data['_token']);

        $campaign = new Campaign($data);
        $campaign->form = 's';
        $campaign->save();
    }


    public function displayTempApplications()
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

        $priorityApps = TempApplication::where('state', '666')->orderBy('created_at', 'asc')->get();
        $applications = TempApplication::where('state', '0')->orderBy('created_at', 'asc')->get();

        $toCheck = TempApplication::where('state', '0')->count();
        $accepted = TempApplication::where('state', '3')->count();
        $rejected = TempApplication::where('state', '1')->count();
        $awaiting = TempApplication::where('state', '2')->count();


        return view('pages.admin.browse')
            ->with('profilePicture', $profilePicture)
            ->with('priorityApps', $priorityApps)
            ->with('applications', $applications)
            ->with('toCheck', $toCheck)
            ->with('accepted', $accepted)
            ->with('rejected', $rejected)
            ->with('awaiting', $awaiting);
    }

    public function displayAcceptedApplications()
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

        $applications = TempApplication::where('state', '3')->orderBy('created_at', 'desc')->get();

        $toCheck = TempApplication::where('state', '0')->count();
        $accepted = TempApplication::where('state', '3')->count();
        $rejected = TempApplication::where('state', '1')->count();
        $awaiting = TempApplication::where('state', '2')->count();

        return view('pages.admin.accepted')
            ->with('profilePicture', $profilePicture)
            ->with('applications', $applications)
            ->with('toCheck', $toCheck)
            ->with('accepted', $accepted)
            ->with('rejected', $rejected)
            ->with('awaiting', $awaiting);
    }

    public function displayRejectedApplications()
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

        $applications = TempApplication::where('state', '1')->orderBy('created_at', 'desc')->get();

        $toCheck = TempApplication::where('state', '0')->count();
        $accepted = TempApplication::where('state', '3')->count();
        $rejected = TempApplication::where('state', '1')->count();
        $awaiting = TempApplication::where('state', '2')->count();

        return view('pages.admin.rejected')
            ->with('profilePicture', $profilePicture)
            ->with('applications', $applications)
            ->with('toCheck', $toCheck)
            ->with('accepted', $accepted)
            ->with('rejected', $rejected)
            ->with('awaiting', $awaiting);
    }

    function dec2hex($number)
    {
        $hex = dechex($number);

        return $hex;
    }

    public function displayAwaitingApplications()
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

        $applications = TempApplication::where('state', '2')->orderBy('created_at', 'desc')->paginate(10);

        foreach ($applications as $application)
        {
            
            /*if(!is_numeric($application->steam_url)) {
                if (filter_var($application->steam_url, FILTER_VALIDATE_URL) === FALSE) {
                    $application->hex = "Niepoprawny URL, sprawdź podanie.";
                }
                else {
                    $firstReplace = str_replace('/', '', $application->steam_url);
                    $secReplace = str_replace('https:steamcommunity.comid', '', $firstReplace);
                    $thirdReplace = str_replace('https:steamcommunity.comprofiles', '', $secReplace);

                    if(!is_numeric($thirdReplace)){
                        $steamAPI = new SteamAPI("ED0F693EFB57384F65E6CB989B9D3986");
                        $handler = $steamAPI->GetPlayerSteamID($thirdReplace);
                        if(!is_numeric($handler)){
                            $application->hex = "Błąd SteamAPI, sprawdź podanie.";
                        }
                        else {
                            $application->hex = $this->dec2hex($handler)."(".strlen($this->dec2hex($handler)).")";
                        }
                    }
                }
            }
            else {
                $application->hex = $this->dec2hex($application->steam_url)."(".$this->dec2hex($application->steam_url).")";
            }*/

            //$application->hex = $application->steam_url;

            $replace = str_replace('/', '', $application->steam_url);
            $replace = str_replace('https:steamcommunity.comid', '', $replace);
            $replace = str_replace('http:steamcommunity.comid', '', $replace);
            $replace = str_replace('https:steamcommunity.comprofiles', '', $replace);
            $replace = str_replace('http:steamcommunity.comprofiles', '', $replace);
            $replace = str_replace('www.steamcommunity.comid', '', $replace);

            if(!is_numeric($replace))
            {
                $application->hex = '(not_num) '.$replace;
                $steamAPI = new SteamAPI("ED0F693EFB57384F65E6CB989B9D3986");
                $handler = $steamAPI->GetPlayerSteamID($replace);
                if(!is_numeric($handler)){
                    $application->hex = "Błąd SteamAPI, sprawdź podanie.";
                }
                else {
                    $application->hex = $this->dec2hex($handler);
                }
            }
            else
            {
                $application->hex = $this->dec2hex($replace);
            }
        }

        $toCheck = TempApplication::where('state', '0')->count();
        $accepted = TempApplication::where('state', '3')->count();
        $rejected = TempApplication::where('state', '1')->count();
        $awaiting = TempApplication::where('state', '2')->count();

        return view('pages.admin.awaiting')
            ->with('profilePicture', $profilePicture)
            ->with('applications', $applications)
            ->with('toCheck', $toCheck)
            ->with('accepted', $accepted)
            ->with('rejected', $rejected)
            ->with('awaiting', $awaiting);
    }

    public function displayTempApplicationInfo($uuid)
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

        $application = TempApplication::where('uuid', $uuid)->get()->first();
        $appage = new DateTime($application->age);
        $currentDate = new DateTime(date('y.m.d'));
        $diff = $currentDate->diff($appage);
        $age = $diff->y;

        if ($application == null)
        {
            return abort(404);
        }

        return view('pages.admin.details')
            ->with('profilePicture', $profilePicture)
            ->with('application', $application)
            ->with('age', $age);
    }

    public function usercheck()
    {

        echo "Ktowa dotacji z aplikacji:" .$payment->amount. "<br>";
        /*$applications = TempApplication::where('state', '0')->orderBy('created_at', 'asc')->get();
        
        foreach($applications as $app) {
            $appage = new DateTime($app->age);
            $currentDate = new DateTime(date('y.m.d'));
            $diff = $currentDate->diff($appage);
            $age = $diff->y;
            if($age >= 16)
            {
                echo "<b>Discord ID</b>: ".$app->discord_id." <b>Age:</b>" .$age."<br>";
            }
            else
            {
                echo "<b>Discord ID</b>: ".$app->discord_id." <b>Age:</b> Użytkownik nie spełnia kryteriów, ma lat:" .$age. "<br>";
            }
        if(strlen($app->steam_url) != 15){
            continue;
        }
        else {
            echo "<b>".$app->steam_url."(".strlen($app->steam_url).")</b><br>";
        }
            $hex = dechex($app->steam_url);
            echo $hex."(".strlen($hex).")<br><br>";
        }*/

    }

    public function actOnTempApplication($uuid, $action)
    {
        $client = new Client();
        if ($action == 'accept')
        {
            TempApplication::where('uuid', $uuid)->update(['state' => 2]);

            $application = TempApplication::where('uuid', $uuid)->get()->first();
            $headers = [
                        'headers' => [
                            'Authorization' => 'Bot NTQ0MTc4NTI4NDQzMTcwODI2.D0Le7g.1y_9g0glJFXwF6qsfi6i_tojzw4'
                        ]
                    ];
                
                    try {
                        $client->get('https://discordapp.com/api/v6/guilds/426411830542467072/members/'.$application->discord_id, $headers, ['http_errors' => false]);    
                    }
                    catch (\GuzzleHttp\Exception\ClientException $e) {
                        $response = $e->getResponse();
                    }
                    if(!isset($response)) {
                        $resp = 0;
                    } else {
                        $resp = $response->getStatusCode();
                    } 
                    try {
                        $client->get('https://discordapp.com/api/v6/guilds/426411830542467072/members/'.$application->discord_id, $headers, ['http_errors' => false]);  
                    }
                    catch (\GuzzleHttp\Exception\ClientException $e) {
                        $response = $e->getResponse();
                    }
                    if(!isset($response)) {
                        $resp = 0;
                    } else {
                        $resp = $response->getStatusCode();
                    } 
                    if($resp != 404){
                        $bot = new DiscordClient(['token' => 'NTQ0MTc4NTI4NDQzMTcwODI2.D0Le7g.1y_9g0glJFXwF6qsfi6i_tojzw4']);
                        $dm = $bot->user->createDm(array("recipient_id" => intval($application->discord_id)));
                        $cid = $dm->id;
                        try {
                            $bot->channel->createMessage([
                                'channel.id' => $cid,
                                'embed'      => [
                                    "description" => "Witaj, <@".$application->discord_id.">! \n\n Z przyjemnością informujemy ze twoja aplikacja przeszła pierwszy etap rekrutacyjny!",
                                    "color" => 14776100,
                                    "timestamp" => date('Y-m-d').'T'.date("H:i:s").'.'.date("v").'Z',
                                    "footer" => [
                                        "icon_url" => "https://i.imgur.com/0N4Vdl2.png",
                                        "text" => "StrefaRP.pl • System aplikacji"
                                    ],
                                    "thumbnail" => [
                                        "url" => "https://i.imgur.com/0N4Vdl2.png"
                                    ],
                                    "author" => [
                                        "name" => "StrefaRP.pl • System aplikacji",
                                        "url" => "https://apilkacje.strefarp.pl",
                                        "icon_url" => "https://i.imgur.com/0N4Vdl2.png"
                                    ],
                                    "fields" => [
                                        [
                                            "name" => "Status:",
                                            "value" => "**Oczekuje na Whitelistę**"
                                        ],
                                        [
                                            "name" => "**Informacje**",
                                            "value" => "Przed tobą krótki okres oczekiwania, w najbliższym czasie zostaniesz dodany na whitelistę serwera oraz zostaną przyznane Ci rangi na forum oraz serwerze discord."
                                        ],
                                        [
                                            "name" => "O czym warto pamiętać?",
                                            "value" => "Po otrzymaniu tej wiadomości nie wysyłaj pytań do Administracji w stylu **'Dostałem się na Whitelistę a nie mogę wejsc!'** poczekaj cierpliwe, a przez ten czas zapoznaj się z poradnikami, klawioszologią oraz [regulaminem](https://docs.google.com/document/d/1g7HEQSiX6PoFlL8rAFsFbpciJMhV8vPilH_3K1CLbZE) serwera. \n\n Z poważaniem, \n Zespół **StrefaRP.pl**"
                                        ]
                                    ]
                                ]
                            ]);
                        sleep(1);  
                        }
                        catch(\Exception $e) {
                            $response = $e->getResponse();
                        }
                        
                        if(!isset($response)){
                            $resp = 0;
                        }
                        else {
                            $resp = $response->getStatusCode();
                        }
                        
                        if($resp != 403){
                        }
                    }
            return redirect('/admin');
        }
        else if ($action == 'deny')
        {
            TempApplication::where('uuid', $uuid)->update(['state' => 1]);
                $application = TempApplication::where('uuid', $uuid)->get()->first();
                $headers = [
                            'headers' => [
                                'Authorization' => 'Bot NTQ0MTc4NTI4NDQzMTcwODI2.D0Le7g.1y_9g0glJFXwF6qsfi6i_tojzw4'
                            ]
                        ];
                        try {
                            $client->get('https://discordapp.com/api/v6/guilds/426411830542467072/members/'.$application->discord_id, $headers, ['http_errors' => false]);    
                        }
                        catch (\GuzzleHttp\Exception\ClientException $e) {
                            $response = $e->getResponse();
                        }
                        if(!isset($response)) {
                            $resp = 0;
                        } else {
                            $resp = $response->getStatusCode();
                        } 
                        try {
                            $client->get('https://discordapp.com/api/v6/guilds/426411830542467072/members/'.$application->discord_id, $headers, ['http_errors' => false]);  
                        }
                        catch (\GuzzleHttp\Exception\ClientException $e) {
                            $response = $e->getResponse();
                        }
                        if(!isset($response)) {
                            $resp = 0;
                        } else {
                            $resp = $response->getStatusCode();
                        } 
                        if($resp != 404){
                            $bot = new DiscordClient(['token' => 'NTQ0MTc4NTI4NDQzMTcwODI2.D0Le7g.1y_9g0glJFXwF6qsfi6i_tojzw4']);
                            $dm = $bot->user->createDm(array("recipient_id" => intval($application->discord_id)));
                            $cid = $dm->id;
                            try {
                                $bot->channel->createMessage([
                                    'channel.id' => $cid,
                                    'embed'      => [
                                        "description" => "Witaj, <@".$application->discord_id.">! \n\n Z przykrością informujemy że twoje podanie zostąło rozpatrzone negatywnie.",
                                        "color" => 16711680,
                                        "timestamp" => date('Y-m-d').'T'.date("H:i:s").'.'.date("v").'Z',
                                        "footer" => [
                                            "icon_url" => "https://i.imgur.com/0N4Vdl2.png",
                                            "text" => "StrefaRP.pl • System aplikacji"
                                        ],
                                        "thumbnail" => [
                                            "url" => "https://i.imgur.com/0N4Vdl2.png"
                                        ],
                                        "author" => [
                                            "name" => "StrefaRP.pl • System aplikacji",
                                            "url" => "https://apilkacje.strefarp.pl",
                                            "icon_url" => "https://i.imgur.com/0N4Vdl2.png"
                                        ],
                                        "fields" => [
                                            [
                                                "name" => "Status:",
                                                "value" => "**Odrzucone**"
                                            ],
                                            [
                                                "name" => "**Informacje**",
                                                "value" => "Pewnie zadajesz sobię teraz pytanie dlaczego nie dostałem się na **Whitelistę**? Powodów może być wiele, możliwe że nie spełniłeś naszych oczekiwań, kryteriów bądź innych czynników które o tym zaważyły."
                                            ],
                                            [
                                                "name" => "O czym warto pamiętać?",
                                                "value" => "Po otrzymaniu tej wiadomości pamiętaj aby nie pisać do **Administracji** z pytaniami dlaczego się nie dostałem, tylko odczekaj 7 dni i złóż kolejne podanie a może wkrótce zobaczymy się na serwerze powodzenia! \n\n Z poważaniem, \n Zespół **StrefaRP.pl**"
                                            ]
                                        ]
                                    ]
                                ]);
                            sleep(1);  
                            }
                            catch(\Exception $e) {
                                $response = $e->getResponse();
                            }
                            
                            if(!isset($response)){
                                $resp = 0;
                            }
                            else {
                                $resp = $response->getStatusCode();
                            }
                            
                            if($resp != 403){
                            }
                        }
            return redirect('/admin');
        }
        else if ($action == 'mark')
        {
            TempApplication::where('uuid', $uuid)->update(['state' => 3]);

            $application = TempApplication::where('uuid', $uuid)->get()->first();

            $replace = str_replace('/', '', $application->steam_url);
            $replace = str_replace('https:steamcommunity.comid', '', $replace);
            $replace = str_replace('http:steamcommunity.comid', '', $replace);
            $replace = str_replace('https:steamcommunity.comprofiles', '', $replace);
            $replace = str_replace('http:steamcommunity.comprofiles', '', $replace);
            $replace = str_replace('www.steamcommunity.comid', '', $replace);

            if(!is_numeric($replace))
            {
                $application->hex = "(not_num) ".$replace;
                $steamAPI = new SteamAPI("ED0F693EFB57384F65E6CB989B9D3986");
                $handler = $steamAPI->GetPlayerSteamID($replace);
                if(!is_numeric($handler)){
                    $hex = "Błąd SteamAPI, sprawdź podanie.";
                }
                else {
                    $hex = $this->dec2hex($handler);
                }
            }
            else
            {
                $hex = $this->dec2hex($replace);
            }

            $link = $application->forum_name;
            $link1 = str_replace('/', '', $link);
            $link2 = str_replace('https:forum.strefarp.plprofile', '', $link1);
            $link3 = explode('-', $link2);

            include_once('/home/admin/web/forum.strefarp.pl/public_html/init.php');
            $ipsMember = \IPS\Member::load( $link3[0] );
            if($ipsMember->inGroup(3)){
                $ipsMember->member_group_id = 22;
                $ipsMember->save();
            }

            $headers = [
                        'headers' => [
                            'Authorization' => 'Bot NTQ0MTc4NTI4NDQzMTcwODI2.D0Le7g.1y_9g0glJFXwF6qsfi6i_tojzw4'
                        ]
                    ];
                    try {
                        $client->get('https://discordapp.com/api/v6/guilds/426411830542467072/members/'.$application->discord_id, $headers, ['http_errors' => false]);  
                    }
                    catch (\GuzzleHttp\Exception\ClientException $e) {
                        $response = $e->getResponse();
                    }
                    if(!isset($response)) {
                        $resp = 0;
                    } else {
                        $resp = $response->getStatusCode();
                    } 
                    if($resp != 404){

                        $replace = str_replace('/', '', $application->steam_url);
                        $replace = str_replace('https:steamcommunity.comid', '', $replace);
                        $replace = str_replace('http:steamcommunity.comid', '', $replace);
                        $replace = str_replace('https:steamcommunity.comprofiles', '', $replace);
                        $replace = str_replace('http:steamcommunity.comprofiles', '', $replace);
                        $replace = str_replace('www.steamcommunity.comid', '', $replace);
            
                        if(!is_numeric($replace))
                        {
                            $application->hex = "(not_num) ".$replace;
                            $steamAPI = new SteamAPI("ED0F693EFB57384F65E6CB989B9D3986");
                            $handler = $steamAPI->GetPlayerSteamID($replace);
                            if(!is_numeric($handler)){
                                $hex = "Błąd SteamAPI, sprawdź podanie.";
                            }
                            else {
                                $hex = $this->dec2hex($handler);
                            }
                        }
                        else
                        {
                            $hex = $this->dec2hex($replace);
                        }

                        $hexlength = strlen($hex);
                        if($hexlength > 14 and $hexlength < 16){
                            define('SLACK_WEBHOOK', 'https://discordapp.com/api/webhooks/544230686924603403/MTs02J709idepQHjwt2jH2ONoh1522O9jOVITlfb9QQR6fmCRm8r1-mHRqHOMA0MeNc-');
                            $message = array('payload_json' => json_encode(array('content' => '!d '.$hex, 'username' => 'StrefaRP.pl • System aplikacji',  'avatar_url' => 'https://i.imgur.com/0N4Vdl2.png')));
                            $c = curl_init(SLACK_WEBHOOK);
                            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($c, CURLOPT_POST, true);
                            curl_setopt($c, CURLOPT_POSTFIELDS, $message);
                            curl_exec($c);
                            curl_close($c);
                            $client->put('https://discordapp.com/api/v6/guilds/426411830542467072/members/'.$application->discord_id.'/roles/426888281590988810', $headers);
                        }

                        $bot = new DiscordClient(['token' => 'NTQ0MTc4NTI4NDQzMTcwODI2.D0Le7g.1y_9g0glJFXwF6qsfi6i_tojzw4']);
                        $dm = $bot->user->createDm(array("recipient_id" => intval($application->discord_id)));
                        $cid = $dm->id;
                        try {
                            $bot->channel->createMessage([
                            'channel.id' => $cid,
                            'embed'      => [
                            "description" => "Witaj, <@".$application->discord_id.">!\n\n Z przyjemnością informujemy ze twoja aplikacja została rozpatrzona pozytywnie!",
                            "color" => 2686720,
                            "timestamp" => date('Y-m-d').'T'.date("H:i:s").'.'.date("v").'Z',
                            "footer" => [
                                "icon_url" => "https://i.imgur.com/0N4Vdl2.png",
                                "text" => "StrefaRP.pl • System aplikacji"
                                ],
                            "thumbnail" => [
                                "url" => "https://i.imgur.com/0N4Vdl2.png"
                                ],
                            "author" => [
                            "name" => "StrefaRP.pl • System aplikacji",
                                "url" => "https://apilkacje.strefarp.pl",
                                "icon_url" => "https://i.imgur.com/0N4Vdl2.png"
                                ],
                            "fields" => [
                                [
                                    "name" => "Status:",
                                    "value" => "**Przyjęte**"
                                ],
                                [
                                    "name" => "**Informacje**",
                                    "value" => "Twoje podanie zostało rozpatrzone **pozytywnie**! Więc wkrótce zobaczymy się na serwerze. \nRangi oraz dostęp do serwera zostanie przyznany do 15 minut od otrzymania tej wiadomości."
                                ],
                                [
                                    "name" => "O czym warto pamiętać?",
                                    "value" => "Jeśli po upływie 15 minut od otrzymania tej wiadomości nie będziesz wstanie połączyć się z serwerem nie pisz do **Administracji** wiadomości w stylu **'Nie mogę połączyć się z serwerem'** tylko zgłoś to nam po przez [ticket](https://forum.strefarp.pl/support) do odpowiedniego działu na forum. Zalecamy zapoznać się z wszelkimi poradnikami oraz [regulaminem](https://docs.google.com/document/d/1g7HEQSiX6PoFlL8rAFsFbpciJMhV8vPilH_3K1CLbZE) serwera. Do zobaczenia na serwerze powodzenia! \n\nZ poważaniem, \nZespół **StrefaRP.pl**"
                                    ]
                                ]
                            ]
                        ]);
                        sleep(1);  
                        }
                        catch(\Exception $e) {
                            $response = $e->getResponse();
                        }
                        
                        if(!isset($response)){
                            $resp = 0;
                        }
                        else {
                            $resp = $response->getStatusCode();
                        }
                        
                        if($resp != 403){
                        }
                    }
            return redirect('/admin/awaiting');
        }
        else if ($action == 'markall')
        {
            $applications = TempApplication::where('state', '3')->get();
            $a=array();
            foreach ($applications as $application)
            {
if(strlen($application->steam_url) != 17){
 continue;
}
else {
$hex = dechex($application->steam_url);
} 
    
                $hexlength = strlen($hex);
                if($hexlength != 15){
continue;
} 
else {
                    $link = $application->forum_name;
                    $link1 = str_replace('/', '', $link);
                    $link2 = str_replace('https:forum.strefarp.plprofile', '', $link1);
                    $link3 = explode('-', $link2);

                    array_push($a, $hex);

                    include_once('/home/admin/web/forum.strefarp.pl/public_html/init.php');
                    $ipsMember = \IPS\Member::load( $link3[0] );
                    if($ipsMember->inGroup(3)){
                        $ipsMember->member_group_id = 22;
                        $ipsMember->save();
                        /*\IPS\Log::log( "Ranga", 'nadano range dla ID '.$link[3][0]);*/
                        TempApplication::where('uuid', $application->uuid)->update(['state' => 3]);

                    }
                }
            }
            $hexy = implode(' ', $a);
            define('SLACK_WEBHOOK', 'https://discordapp.com/api/webhooks/544230686924603403/MTs02J709idepQHjwt2jH2ONoh1522O9jOVITlfb9QQR6fmCRm8r1-mHRqHOMA0MeNc-');
            $message = array('payload_json' => json_encode(array('content' => '!dw '.$hexy, 'username' => 'StrefaRP.pl • System aplikacji',  'avatar_url' => 'https://i.imgur.com/0N4Vdl2.png')));
            $c = curl_init(SLACK_WEBHOOK);
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($c, CURLOPT_POST, true);
            curl_setopt($c, CURLOPT_POSTFIELDS, $message);
            curl_exec($c);
            curl_close($c);


            foreach($applications as $application) {
                
if(strlen($application->steam_url) != 17){
 continue;
}
else {
$hex = dechex($application->steam_url);
} 
    
                $hexlength = strlen($hex);
                if($hexlength != 15){
continue;
} 
else {
                    $headers = [
                        'headers' => [
                                'Authorization' => 'Bot NTQ0MTc4NTI4NDQzMTcwODI2.D0Le7g.1y_9g0glJFXwF6qsfi6i_tojzw4'
                                ]
                            ];
                            try {
                                $client->get('https://discordapp.com/api/v6/guilds/426411830542467072/members/'.$application->discord_id, $headers, ['http_errors' => false]);  
                            }
                            catch (\GuzzleHttp\Exception\ClientException $e) {
                                $response = $e->getResponse();
                            }
                            if(!isset($response)) {
                                $resp = 0;
                            } else {
                                $resp = $response->getStatusCode();
                            } 
                            if($resp != 404){
                                $client->put('https://discordapp.com/api/v6/guilds/426411830542467072/members/'.$application->discord_id.'/roles/426888281590988810', $headers);
                                sleep(1);
                                $bot = new DiscordClient(['token' => 'NTQ0MTc4NTI4NDQzMTcwODI2.D0Le7g.1y_9g0glJFXwF6qsfi6i_tojzw4']);
                                $dm = $bot->user->createDm(array("recipient_id" => intval($application->discord_id)));
                                $cid = $dm->id;
                                try {
                                    $bot->channel->createMessage([
                                    'channel.id' => $cid,
                                    'embed'      => [
                                    "description" => "Witaj, <@".$application->discord_id.">!\n\n Z przyjemnością informujemy ze twoja aplikacja została rozpatrzona pozytywnie!",
                                    "color" => 2686720,
                                    "timestamp" => date('Y-m-d').'T'.date("H:i:s").'.'.date("v").'Z',
                                    "footer" => [
                                        "icon_url" => "https://i.imgur.com/0N4Vdl2.png",
                                        "text" => "StrefaRP.pl • System aplikacji"
                                        ],
                                    "thumbnail" => [
                                        "url" => "https://i.imgur.com/0N4Vdl2.png"
                                        ],
                                    "author" => [
                                    "name" => "StrefaRP.pl • System aplikacji",
                                        "url" => "https://apilkacje.strefarp.pl",
                                        "icon_url" => "https://i.imgur.com/0N4Vdl2.png"
                                        ],
                                    "fields" => [
                                        [
                                            "name" => "Status:",
                                            "value" => "**Przyjęte**"
                                        ],
                                        [
                                            "name" => "**Informacje**",
                                            "value" => "Twoje podanie zostało rozpatrzone **pozytywnie**! Więc wkrótce zobaczymy się na serwerze. \nRangi oraz dostęp do serwera zostanie przyznany do 15 minut od otrzymania tej wiadomości."
                                        ],
                                        [
                                            "name" => "O czym warto pamiętać?",
                                            "value" => "Jeśli po upływie 15 minut od otrzymania tej wiadomości nie będziesz wstanie połączyć się z serwerem nie pisz do **Administracji** wiadomości w stylu **'Nie mogę połączyć się z serwerem'** tylko zgłoś to nam po przez [ticket](https://forum.strefarp.pl/support) do odpowiedniego działu na forum. Zalecamy zapoznać się z wszelkimi poradnikami oraz [regulaminem](https://docs.google.com/document/d/1g7HEQSiX6PoFlL8rAFsFbpciJMhV8vPilH_3K1CLbZE) serwera. Do zobaczenia na serwerze powodzenia! \n\nZ poważaniem, \nZespół **StrefaRP.pl**"
                                            ]
                                        ]
                                    ]
                                ]);
                            sleep(1);  
                                }
                                catch(\Exception $e) {
                                    $response = $e->getResponse();
                                }
                                
                                if(!isset($response)){
                                    $resp = 0;
                                }
                                else {
                                    $resp = $response->getStatusCode();
                                }
                                
                                if($resp != 403){
                            }
                        }
                    } 
                }
            return redirect('/admin/awaiting');
        }
    }
}