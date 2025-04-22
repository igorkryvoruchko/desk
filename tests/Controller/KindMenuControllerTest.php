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
        $this->client->request('POST', '/api/en/kind-menu',
            content: json_encode([
                "alias"        => $this->kindMenuAlias,
                "restaurant"   => $this->restaurantId,
                "isActive"   => true,
                "translations" => [
                    ["name" => "TestRestaurant",    "description" => "New Restaurant",   "locale" => "en"],
                    ["name" => "TestRestaurant.DE", "description" => "Neu Restaurant",   "locale" => "de"]
                ]
            ])
        );
        $response = $this->decodeResponse();
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertEquals($this->kindMenuAlias, $response['data']['alias']);
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
        $response = $this->decodeResponse();

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals($newAlias, $response['data']['alias']);
    }

    /** @test */
    public function testGetAllKindMenu()
    {
        $this->client->request('GET', '/api/en/kind-menu');
        $response = $this->decodeResponse();
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertGreaterThan(0, count($response['data']));
    }

    /** @test */
    public function testGetOneKindMenu()
    {
        $this->client->request('GET', '/api/en/kind-menu/' . $this->kindMenuId);

        $response = $this->decodeResponse();
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals(KindMenuFixtures::KIND_MENU_ALIAS, $response['data']['alias']);
    }

    /** @test */
    public function testDeleteKindMenu()
    {
        $this->client->request('DELETE', '/api/en/kind-menu/' . $this->kindMenuId);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
