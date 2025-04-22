<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;

class CityControllerTest extends AbstractWebTestCase
{
    protected function setUp(): void
    {
        $this->setClient();
        $this->authorize();
    }

    /** @test */
    public function testGetAllCities()
    {
        $this->client->request('GET', '/api/en/city');
        $responce = json_decode($this->client->getResponse()->getContent(), true);
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertGreaterThan(0, count($responce['data']));
    }
}
