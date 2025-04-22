<?php

namespace App\Tests\Controller;

use App\DataFixtures\ZoneFixtures;
use App\DataFixtures\TableFixtures;
use App\Entity\Table;
use App\Entity\Zone;
use Symfony\Component\HttpFoundation\Response;

class TableControllerTest extends AbstractWebTestCase
{
    private int $tableId;

    private int $zoneId;

    private int $tableNumber = 4;

    protected function setUp(): void
    {
        $this->setClient();
        $this->authorize();

        $table = self::getContainer()->get('doctrine')->getRepository(Table::class)
            ->findOneBy(['number' => TableFixtures::TABLE_NUMBER]);

        $zone = self::getContainer()->get('doctrine')->getRepository(Zone::class)
            ->findOneBy(['alias' => ZoneFixtures::ZONE_ALIAS]);

        $this->tableId = $table->getId();
        $this->zoneId = $zone->getId();
    }

    /** @test */
    public function testCreateTable(): void
    {
        $this->client->request('POST', '/api/en/table',
            content: json_encode([
                "number"        => $this->tableNumber,
                "zone"   => $this->zoneId,
                "seatsCount"    => 4,
            ])
        );
        $response = $this->decodeResponse();
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertEquals($this->tableNumber, $response['data']['number']);
    }

    /** @test */
    public function testUpdateTable(): void
    {
        $newNumber = 3;
        
        $this->client->request('PATCH', '/api/en/table/' . $this->tableId,
            content: json_encode([
                "number" => $newNumber,
            ])
        );
        $response = $this->decodeResponse();

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals($newNumber, $response['data']['number']);
    }

    /** @test */
    public function testGetAllTables()
    {
        $this->client->request('GET', '/api/en/table');
        $response = $this->decodeResponse();
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertGreaterThan(0, count($response['data']));
    }

    /** @test */
    public function testGetOneTable()
    {
        $this->client->request('GET', '/api/en/table/' . $this->tableId);

        $response = $this->decodeResponse();
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals(TableFixtures::TABLE_NUMBER, $response['data']['number']);
    }

    /** @test */
    public function testDeleteTable()
    {
        $this->client->request('DELETE', '/api/en/table/' . $this->tableId);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
