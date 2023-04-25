<?php 
namespace App\Service;

use Facebook\Facebook;

class FacebookService
{
    private $fb;

    public function __construct()
    {
        $appId = '106824712245974_184074527851418';
        $appSecret = '5f53c85869443487fd9414db1a4412b0';
        $accessToken = 'EAAO7HzlB0fsBAJkyQ3swB6G6YrajswxfmMau3PnjmRX0t9V9Ay9VfJwW4UXD2JX3oVBMbFMBMLUaP6goIZCbT21uDQejmYrRYGjhofngTJGdzDYp4wLE18fyZApW0f0ZCSgpH9v3ItnCmkejfUE5lY3Nu1Wb8NfQJlgzCjBo5whWHcwQyrk';

        $this->fb = new Facebook([
            'app_id' => $appId,
            'app_secret' => $appSecret,
            'default_graph_version' => 'v12.0',
            'default_access_token' => $accessToken,
        ]);
    }

    public function postArticle(string $articleUrl, string $message)
    {
        $response = $this->fb->post('/me/feed', [
            'link' => $articleUrl,
            'message' => $message,
        ]);
        $graphNode = $response->getGraphNode();
        return $graphNode->getField('id');
    }
}
