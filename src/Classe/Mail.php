<?php 

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

Class Mail
{
    private $api_key = "97b2652c7ba8bed40a423980d462f8c3";
    private $api_key_secret = "69449ae5f75ad0fb89d974aec28983c2";

    public function send($to_email, $to_name, $subject, $content){

        $mj = new Client($this->api_key, $this->api_key_secret,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "fabien.l02@outlook.fr",
                        'Name' => "La Boutique Française"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 2817815,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();

    }
}