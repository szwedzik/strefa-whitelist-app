<?php

namespace App\Classes;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;
	
	class Jakso {

public function nickname($userid){
$client = new Client();
        $headers = [
            'headers' => [
                'Authorization' => 'Bot NTQ0MTc4NTI4NDQzMTcwODI2.D0Le7g.1y_9g0glJFXwF6qsfi6i_tojzw4'
            ]
        ];
$nick = $client->get('https://discordapp.com/api/v6/users/'. $userid, $headers);
$nickBody = json_decode($nick->getBody()->getContents());
$nickname = $nickBody->id . '#' . $nickBody->discriminator;

return $nickname;
        }

}

?>