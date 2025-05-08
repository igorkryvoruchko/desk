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
        $testedName = 'TestMenuItem';
        $this->client->request('POST', '/api/en/menu-item',
            content: json_encode([
                "alias"        => $this->menuItemAlias,
                "kindMenu"   => $this->kindMenuId,
                "quantity"    => 1,
                "photo"        => "test.jpg",
                "price"        => 5.99,
                "specialPrice" => 4.99,
                "translations" => [
                    ["name" => $testedName,       "description" => "New MenuItem",   "locale" => "en"],
                    ["name" => "TestMenuItem.DE", "description" => "Neu MenuItem",   "locale" => "de"]
                ]
            ])
        );
        
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $menuItem = $this->decodeResponse();
        $this->assertEquals($this->menuItemAlias, $menuItem['data']['alias']);
        $this->assertEquals($testedName, $menuItem['data']['translations']['en']['name']);
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
        
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $menuItem = $this->decodeResponse();
        $this->assertEquals($newAlias, $menuItem['data']['alias']);
    }

    /** @test */
    public function testGetAllMenuItem()
    {
        $this->client->request('GET', '/api/en/menu-item');
        
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $menuItems = $this->decodeResponse();
        $this->assertGreaterThan(0, count($menuItems['data']));
    }

    /** @test */
    public function testGetOneMenuItem()
    {
        $this->client->request('GET', '/api/en/menu-item/' . $this->menuItemId);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $menuItem = $this->decodeResponse();
        $this->assertEquals(MenuItemFixtures::MENU_ITEM_ALIAS, $menuItem['data']['alias']);
        $this->assertArrayHasKey('name', $menuItem['data']['translations']['en']);
    }

    /** @test */
    public function testDeleteMenuItem()
    {
        $this->client->request('DELETE', '/api/en/menu-item/' . $this->menuItemId);

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
