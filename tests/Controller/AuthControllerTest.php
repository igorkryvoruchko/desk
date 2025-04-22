<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

class AuthControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient(server: ['CONTENT_TYPE' => 'application/json']);
    }

    /** @test */
    public function testRegisterLoginAndAccessProtectedRoute(): void
    {
        $this->client->request('GET', '/api/en/city');
        $cities = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        
        // 1. Registration
        $this->client->request('POST', '/api/en/signup', 
        [],
        [],
        [],
        json_encode([
            'name'     => 'testuser',
            'email'    => 'test@example.com',
            'password' => 'password123',
            'city'     => $cities['data'][0]['id'] ?? null,
        ])
    );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $user = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('test@example.com', $user['data']['user']['email']);

        // 2. Login
        $this->client->request('POST', '/api/en/login_check', 
        [],
        [],
        [],
        json_encode([
            'email'    => 'test@example.com',
            'password' => 'password123',
        ])
        );

        $this->assertResponseIsSuccessful();
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $data['data']);

        $jwt = $data['data']['token'];

        // 3. Access protected route
        $this->client->request('GET', '/api/en/company', server: [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $jwt,
        ]);

        $this->assertResponseIsSuccessful();
    }
}
