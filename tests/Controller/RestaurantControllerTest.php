<?php

namespace App\Tests\Controller;

use App\DataFixtures\CityFixtures;
use App\DataFixtures\CompanyFixtures;
use App\DataFixtures\RestaurantFixtures;
use App\Entity\City;
use App\Entity\Company;
use App\Entity\Restaurant;
use Symfony\Component\HttpFoundation\Response;

class RestaurantControllerTest extends AbstractWebTestCase
{
    private int $restaurantId;

    private int $companyId;

    private int $cityId;

    private string $restaurantAlias = 'test_restaurant';

    protected function setUp(): void
    {
        $this->setClient();
        $this->authorize();

        $restaurant = self::getContainer()->get('doctrine')->getRepository(Restaurant::class)
            ->findOneBy(['alias' => RestaurantFixtures::RESTAURANT_ALIAS]);

        $company = self::getContainer()->get('doctrine')->getRepository(Company::class)
            ->findOneBy(['alias' => CompanyFixtures::COMPANY_ALIAS]);

        $city = self::getContainer()->get('doctrine')->getRepository(City::class)
            ->findOneBy(['alias' => CityFixtures::CITY_ALIAS]);

        $this->restaurantId = $restaurant->getId();
        $this->companyId = $company->getId();
        $this->cityId = $city->getId();
    }

    /** @test */
    public function testCreateRestaurant(): void
    {
        $this->client->request('POST', '/api/en/restaurant',
            content: json_encode([
                "alias"        => $this->restaurantAlias,
                "company"      => $this->companyId,
                "address"      => "Test Address",
                "postalCode"   => "12345",
                "city"         => $this->cityId,
                "type"         => "BurgerRestaurant",
                "translations" => [
                    ["name" => "TestRestaurant",    "description" => "New Restaurant",   "locale" => "en"],
                    ["name" => "TestRestaurant.DE", "description" => "Neu Restaurant",   "locale" => "de"]
                ]
            ])
        );
        $response = $this->decodeResponse();
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertEquals($this->restaurantAlias, $response['data']['alias']);
    }

    /** @test */
    public function testUpdateRestaurant(): void
    {
        $newAlias = "test_restaurant_updated";
        
        $this->client->request('PATCH', '/api/en/restaurant/' . $this->restaurantId,
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
    public function testGetAllRestaurant()
    {
        $this->client->request('GET', '/api/en/restaurant');
        $response = $this->decodeResponse();
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertGreaterThan(0, count($response['data']));
    }

    /** @test */
    public function testGetOneRestaurant()
    {
        $this->client->request('GET', '/api/en/restaurant/' . $this->restaurantId);

        $response = $this->decodeResponse();
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals(RestaurantFixtures::RESTAURANT_ALIAS, $response['data']['alias']);
    }

    /** @test */
    public function testDeleteRestaurant()
    {
        $this->client->request('DELETE', '/api/en/restaurant/' . $this->restaurantId);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
