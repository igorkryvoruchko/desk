<?php

namespace App\Tests\Controller;

use App\DataFixtures\CompanyFixtures;
use App\Entity\Company;
use Symfony\Component\HttpFoundation\Response;

class CompanyControllerTest extends AbstractWebTestCase
{
    private int $companyId;

    protected function setUp(): void
    {
        $this->setClient();
        $this->authorize();

        $company = self::getContainer()->get('doctrine')->getRepository(Company::class)
            ->findOneBy(['alias' => CompanyFixtures::COMPANY_ALIAS]);

        $this->companyId = $company->getId();
    }

    /** @test */
    public function testCreateCompany(): void
    {
        $companyAlias = "test_company";
        $this->client->request('POST', '/api/en/company',
            content: json_encode([
                "alias"        => $companyAlias,
                "logo"         => "foto.jpg",
                "translations" => [
                    ["name" => "TEST COMPANY",    "locale" => "en"],
                    ["name" => "TEST FIRMA.DE",   "locale" => "de"]
                ]
            ])
        );
        $responce = $this->decodeResponse();
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertEquals($companyAlias, $responce['data']['alias']);
    }

    /** @test */
    public function testUpdateCompany(): void
    {
        $newAlias = "test_updated";
        
        $this->client->request('PATCH', '/api/en/company/' . $this->companyId,
            content: json_encode([
                "alias" => $newAlias,
            ])
        );
        $responce = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals($newAlias, $responce['data']['alias']);
    }

    /** @test */
    public function testGetAllCompanies()
    {
        $this->client->request('GET', '/api/en/company');
        $responce = json_decode($this->client->getResponse()->getContent(), true);
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertGreaterThan(0, count($responce['data']));
    }

    /** @test */
    public function testGetOneCompany()
    {
        $this->client->request('GET', '/api/en/company/' . $this->companyId);
        $responce = json_decode($this->client->getResponse()->getContent(), true);
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals(CompanyFixtures::COMPANY_ALIAS, $responce['data']['alias']);
    }

    /** @test */
    public function testDeleteCompany()
    {
        $this->client->request('DELETE', '/api/en/company/' . $this->companyId);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
