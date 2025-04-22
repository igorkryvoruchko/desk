<?php

namespace App\Tests\Controller;

use App\DataFixtures\UserFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class AbstractWebTestCase extends WebTestCase
{
    protected KernelBrowser $client;

    protected function setClient()
    {
        $this->client = static::createClient(server: ['CONTENT_TYPE' => 'application/json']);
    }

    protected function decodeResponse(): mixed
    {
        return json_decode($this->client->getResponse()->getContent(), true);
    }

    protected function authorize()
    {
        $this->client->request('POST', '/api/en/login_check', 
            content: json_encode([
                'email'    => UserFixtures::USER_EMAIL,
                'password' => UserFixtures::USER_PASSWORD,
            ])
        );
        $data = $this->decodeResponse();
        $this->client->setServerParameter('HTTP_AUTHORIZATION', 'Bearer ' . $data['data']['token']);
    }
}
