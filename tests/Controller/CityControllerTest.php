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
        
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $cities = $this->decodeResponse();
        $this->assertGreaterThan(0, count($cities['data']));
        $this->assertArrayHasKey('name', $cities['data'][0]['translations']['en']);
    }
}
