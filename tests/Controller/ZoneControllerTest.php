<?php

namespace App\Tests\Controller;

use App\DataFixtures\ZoneFixtures;
use App\DataFixtures\RestaurantFixtures;
use App\Entity\Restaurant;
use App\Entity\Zone;
use Symfony\Component\HttpFoundation\Response;

class ZoneControllerTest extends AbstractWebTestCase
{
    private int $restaurantId;

    private int $zoneId;

    private string $zoneAlias = 'test_zone';

    protected function setUp(): void
    {
        $this->setClient();
        $this->authorize();

        $restaurant = self::getContainer()->get('doctrine')->getRepository(Restaurant::class)
            ->findOneBy(['alias' => RestaurantFixtures::RESTAURANT_ALIAS]);

        $zone = self::getContainer()->get('doctrine')->getRepository(Zone::class)
            ->findOneBy(['alias' => ZoneFixtures::ZONE_ALIAS]);

        $this->restaurantId = $restaurant->getId();
        $this->zoneId = $zone->getId();
    }

    /** @test */
    public function testCreateZone(): void
    {
        $this->client->request('POST', '/api/en/zone',
            content: json_encode([
                "alias"        => $this->zoneAlias,
                "restaurant"   => $this->restaurantId,
                "translations" => [
                    ["name" => "TestZone",    "locale" => "en"],
                    ["name" => "TestZone.DE", "locale" => "de"]
                ]
            ])
        );
        $response = $this->decodeResponse();
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertEquals($this->zoneAlias, $response['data']['alias']);
    }

    /** @test */
    public function testUpdateZone(): void
    {
        $newAlias = "test_zone_updated";
        
        $this->client->request('PATCH', '/api/en/zone/' . $this->zoneId,
            content: json_encode([
                "alias" => $newAlias,
            ])
        );
        $response = $this->decodeResponse();

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals($newAlias, $response['data']['alias']);
    }

    /** @test */
    public function testGetAllZone()
    {
        $this->client->request('GET', '/api/en/zone');
        $response = $this->decodeResponse();
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertGreaterThan(0, count($response['data']));
    }

    /** @test */
    public function testGetOneZone()
    {
        $this->client->request('GET', '/api/en/zone/' . $this->zoneId);

        $response = $this->decodeResponse();
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals(ZoneFixtures::ZONE_ALIAS, $response['data']['alias']);
    }

    /** @test */
    public function testDeleteZone()
    {
        $this->client->request('DELETE', '/api/en/zone/' . $this->zoneId);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
