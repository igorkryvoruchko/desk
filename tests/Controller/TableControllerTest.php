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
        $this->client->request(
            method: 'POST', 
            uri: '/api/en/table',
            content: json_encode([
                "number"        => $this->tableNumber,
                "zone"   => $this->zoneId,
                "seatsCount"    => 4,
            ])
        );
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $table = $this->decodeResponse();
        $this->assertEquals($this->tableNumber, $table['data']['number']);
    }

    /** @test */
    public function testUpdateTable(): void
    {
        $newNumber = 3;
        
        $this->client->request(
            method: 'PATCH', 
            uri: '/api/en/table/' . $this->tableId,
            content: json_encode([
                "number" => $newNumber,
            ])
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $table = $this->decodeResponse();
        $this->assertEquals($newNumber, $table['data']['number']);
    }

    /** @test */
    public function testGetAllTables()
    {
        $this->client->request('GET', '/api/en/table');
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $tables = $this->decodeResponse();
        $this->assertGreaterThan(0, count($tables['data']));
    }

    /** @test */
    public function testGetOneTable()
    {
        $this->client->request('GET', '/api/en/table/' . $this->tableId);
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $table = $this->decodeResponse();
        $this->assertEquals(TableFixtures::TABLE_NUMBER, $table['data']['number']);
    }

    /** @test */
    public function testDeleteTable()
    {
        $this->client->request('DELETE', '/api/en/table/' . $this->tableId);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
