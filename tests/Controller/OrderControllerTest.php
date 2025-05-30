<?php

namespace App\Tests\Controller;

use App\DataFixtures\MenuItemFixtures;
use App\DataFixtures\TableFixtures;
use App\Entity\Order;
use App\Entity\User;
use App\DataFixtures\UserFixtures;
use App\Entity\MenuItem;
use App\Entity\Table;
use Symfony\Component\HttpFoundation\Response;

class OrderControllerTest extends AbstractWebTestCase
{
    private int $orderId;

    private int $userId;

    private int $tableId;

    private int $menuItemId;

    protected function setUp(): void
    {
        $this->setClient();
        $this->authorize();

        $order = self::getContainer()->get('doctrine')->getRepository(Order::class)
            ->findOneBy([]);

        $user = self::getContainer()->get('doctrine')->getRepository(User::class)
            ->findOneBy(['email' => UserFixtures::USER_EMAIL]);

        $table = self::getContainer()->get('doctrine')->getRepository(Table::class)
            ->findOneBy(['number' => TableFixtures::TABLE_NUMBER]);
        
        $menuItem = self::getContainer()->get('doctrine')->getRepository(MenuItem::class)
            ->findOneBy(['alias' => MenuItemFixtures::MENU_ITEM_ALIAS]);

        $this->orderId = $order->getId();
        $this->userId = $user->getId();
        $this->tableId = $table->getId();
        $this->menuItemId = $menuItem->getId();
    }

    /** @test */
    public function testCreateOrder(): void
    {
        $comment = "new comment";
        $this->client->request('POST', '/api/en/order',
            content: json_encode([
                "comment"        => $comment,
                "orderedTable"   => $this->tableId,
                "user"          => $this->userId,
                "menuItem"      => [$this->menuItemId],
                "timeFrom"      => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ])
        );
        
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $order = $this->decodeResponse();
        $this->assertEquals($comment, $order['data']['comment']);
    }

    /** @test */
    public function testUpdateOrder(): void
    {
        $newText = "text_updated";
        
        $this->client->request(
            method: 'PATCH',
            uri: '/api/en/order/' . $this->orderId,
            content: json_encode([
                "comment" => $newText,
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $order = $this->decodeResponse();
        $this->assertEquals($newText, $order['data']['comment']);
    }

    /** @test */
    public function testGetAllOrders()
    {
        $this->client->request('GET', '/api/en/order');
        
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $orders = $this->decodeResponse();
        $this->assertGreaterThan(0, count($orders['data']));
    }

    /** @test */
    public function testGetOneOrder()
    {
        $this->client->request('GET', '/api/en/order/' . $this->orderId);
        
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $order = $this->decodeResponse();
        $this->assertArrayHasKey('id', $order['data']);
    }

    /** @test */
    public function testDeleteOrder()
    {
        $this->client->request('DELETE', '/api/en/order/' . $this->orderId);

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
