<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;

class AuthControllerTest extends AbstractWebTestCase
{
    protected function setUp(): void
    {
        $this->client = static::createClient(server: ['CONTENT_TYPE' => 'application/json']);
    }

    /** @test */
    public function testRegisterLoginAndAccessProtectedRoute(): void
    { 
        $this->client->request('GET', '/api/en/city');
        $this->assertResponseIsSuccessful();
        $cities = $this->decodeResponse();
        
        $userData = [
            'name'     => 'testuser',
            'email'    => 'test@example.com',
            'password' => 'password123',
            'city'     => $cities['data'][0]['id'],
        ];
        // 1. Registration
        $this->client->request(
            method: 'POST', 
            uri: '/api/en/signup', 
            content: json_encode($userData)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $registeredUser = $this->decodeResponse();
        $this->assertEquals($userData['email'], $registeredUser['data']['user']['email']);

        // 2. Login
        $this->client->request(
            method: 'POST', 
            uri: '/api/en/login_check', 
            content: json_encode([
                'email'    => $userData['email'],
                'password' => $userData['password'],
            ])
        );

        $this->assertResponseIsSuccessful();
        $authenticatedUser = $this->decodeResponse();
        $this->assertArrayHasKey('token', $authenticatedUser['data']);

        $jwt = $authenticatedUser['data']['token'];

        // 3. Access protected route
        $this->client->request(
            method: 'GET', 
            uri: '/api/en/company', 
            server: [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $jwt,
            ]
        );

        $this->assertResponseIsSuccessful();
    }
}
