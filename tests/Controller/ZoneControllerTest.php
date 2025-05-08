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
        $zoneName = "TestZone";
        $this->client->request(
            method: 'POST', 
            uri: '/api/en/zone',
            content: json_encode([
                "alias"        => $this->zoneAlias,
                "restaurant"   => $this->restaurantId,
                "translations" => [
                    ["name" => $zoneName,    "locale" => "en"],
                    ["name" => "TestZone.DE", "locale" => "de"]
                ]
            ])
        );
        
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $zone = $this->decodeResponse();
        $this->assertEquals($this->zoneAlias, $zone['data']['alias']);
        $this->assertEquals($zoneName, $zone['data']['translations']['en']['name']);
    }

    /** @test */
    public function testUpdateZone(): void
    {
        $newAlias = "test_zone_updated";
        
        $this->client->request(
            method: 'PATCH', 
            uri: '/api/en/zone/' . $this->zoneId,
            content: json_encode([
                "alias" => $newAlias,
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $zone = $this->decodeResponse();
        $this->assertEquals($newAlias, $zone['data']['alias']);
    }

    /** @test */
    public function testGetAllZone()
    {
        $this->client->request('GET', '/api/en/zone');
        
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $zones = $this->decodeResponse();
        $this->assertGreaterThan(0, count($zones['data']));
    }

    /** @test */
    public function testGetOneZone()
    {
        $this->client->request('GET', '/api/en/zone/' . $this->zoneId);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $zone = $this->decodeResponse();
        $this->assertEquals(ZoneFixtures::ZONE_ALIAS, $zone['data']['alias']);
    }

    /** @test */
    public function testDeleteZone()
    {
        $this->client->request('DELETE', '/api/en/zone/' . $this->zoneId);

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
