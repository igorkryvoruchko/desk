<?php

namespace App\Tests\Controller;

use App\DataFixtures\KindMenuFixtures;
use App\DataFixtures\MenuItemFixtures;
use App\Entity\KindMenu;
use App\Entity\MenuItem;
use Symfony\Component\HttpFoundation\Response;

class MenuItemControllerTest extends AbstractWebTestCase
{
    private int $kindMenuId;

    private int $menuItemId;

    private string $menuItemAlias = 'test_menu_item_new';

    protected function setUp(): void
    {
        $this->setClient();
        $this->authorize();

        $kindMenu = self::getContainer()->get('doctrine')->getRepository(KindMenu::class)
            ->findOneBy(['alias' => KindMenuFixtures::KIND_MENU_ALIAS]);

        $menuItem = self::getContainer()->get('doctrine')->getRepository(MenuItem::class)
            ->findOneBy(['alias' => MenuItemFixtures::MENU_ITEM_ALIAS]);

        $this->kindMenuId = $kindMenu->getId();
        $this->menuItemId = $menuItem->getId();
    }

    /** @test */
    public function testCreateMenuItem(): void
    {
        $this->client->request('POST', '/api/en/menu-item',
            content: json_encode([
                "alias"        => $this->menuItemAlias,
                "kindMenu"   => $this->kindMenuId,
                "quantity"    => 1,
                "photo"        => "test.jpg",
                "price"        => 5.99,
                "specialPrice" => 4.99,
                "translations" => [
                    ["name" => "TestMenuItem",    "description" => "New MenuItem",   "locale" => "en"],
                    ["name" => "TestMenuItem.DE", "description" => "Neu MenuItem",   "locale" => "de"]
                ]
            ])
        );
        $response = $this->decodeResponse();
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertEquals($this->menuItemAlias, $response['data']['alias']);
    }

    /** @test */
    public function testUpdateMenuItem(): void
    {
        $newAlias = 'test_menu_item_updated';
        
        $this->client->request('PATCH', '/api/en/menu-item/' . $this->menuItemId,
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
    public function testGetAllMenuItem()
    {
        $this->client->request('GET', '/api/en/menu-item');
        $response = $this->decodeResponse();
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertGreaterThan(0, count($response['data']));
    }

    /** @test */
    public function testGetOneMenuItem()
    {
        $this->client->request('GET', '/api/en/menu-item/' . $this->menuItemId);

        $response = $this->decodeResponse();
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals(MenuItemFixtures::MENU_ITEM_ALIAS, $response['data']['alias']);
    }

    /** @test */
    public function testDeleteMenuItem()
    {
        $this->client->request('DELETE', '/api/en/menu-item/' . $this->menuItemId);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
