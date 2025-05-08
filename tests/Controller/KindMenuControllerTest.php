<?php

namespace App\Tests\Controller;

use App\DataFixtures\KindMenuFixtures;
use App\DataFixtures\RestaurantFixtures;
use App\Entity\KindMenu;
use App\Entity\Restaurant;
use Symfony\Component\HttpFoundation\Response;

class KindMenuControllerTest extends AbstractWebTestCase
{
    private int $kindMenuId;

    private int $restaurantId;

    private string $kindMenuAlias = 'test_kind_menu';

    protected function setUp(): void
    {
        $this->setClient();
        $this->authorize();

        $kindMenu = self::getContainer()->get('doctrine')->getRepository(KindMenu::class)
            ->findOneBy(['alias' => KindMenuFixtures::KIND_MENU_ALIAS]);

        $restaurant = self::getContainer()->get('doctrine')->getRepository(Restaurant::class)
            ->findOneBy(['alias' => RestaurantFixtures::RESTAURANT_ALIAS]);

        $this->kindMenuId = $kindMenu->getId();
        $this->restaurantId = $restaurant->getId();
    }

    /** @test */
    public function testCreateKindMenu(): void
    {
        $testedName = 'TestRestaurant';
        $this->client->request('POST', '/api/en/kind-menu',
            content: json_encode([
                "alias"        => $this->kindMenuAlias,
                "restaurant"   => $this->restaurantId,
                "isActive"   => true,
                "translations" => [
                    ["name" => $testedName,         "description" => "New Restaurant",   "locale" => "en"],
                    ["name" => "TestRestaurant.DE", "description" => "Neu Restaurant",   "locale" => "de"]
                ]
            ])
        );
        
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $kindMenu = $this->decodeResponse();
        $this->assertEquals($this->kindMenuAlias, $kindMenu['data']['alias']);
        $this->assertEquals($testedName, $kindMenu['data']['translations']['en']['name']);
    }

    /** @test */
    public function testUpdateKindMenu(): void
    {
        $newAlias = 'test_kind_menu_updated';
        
        $this->client->request('PATCH', '/api/en/kind-menu/' . $this->kindMenuId,
            content: json_encode([
                "alias" => $newAlias,
            ])
        );
        
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $kindMenu = $this->decodeResponse();
        $this->assertEquals($newAlias, $kindMenu['data']['alias']);
    }

    /** @test */
    public function testGetAllKindMenu()
    {
        $this->client->request('GET', '/api/en/kind-menu');
        
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $kindMenus = $this->decodeResponse();
        $this->assertGreaterThan(0, count($kindMenus['data']));
    }

    /** @test */
    public function testGetOneKindMenu()
    {
        $this->client->request('GET', '/api/en/kind-menu/' . $this->kindMenuId);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $kindMenu = $this->decodeResponse();
        $this->assertEquals(KindMenuFixtures::KIND_MENU_ALIAS, $kindMenu['data']['alias']);
        $this->assertArrayHasKey('name', $kindMenu['data']['translations']['en']);
    }

    /** @test */
    public function testDeleteKindMenu()
    {
        $this->client->request('DELETE', '/api/en/kind-menu/' . $this->kindMenuId);

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
