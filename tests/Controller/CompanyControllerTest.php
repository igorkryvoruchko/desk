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
        $testedName = "TEST COMPANY";
        $this->client->request('POST', '/api/en/company',
            content: json_encode([
                "alias"        => $companyAlias,
                "logo"         => "foto.jpg",
                "translations" => [
                    ["name" => $testedName,    "locale" => "en"],
                    ["name" => "TEST FIRMA.DE",   "locale" => "de"]
                ]
            ])
        );

        $company = $this->decodeResponse();
        
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertEquals($companyAlias, $company['data']['alias']);
        $this->assertEquals($testedName, $company['data']['translations']['en']['name']);
    }

    /** @test */
    public function testUpdateCompany(): void
    {
        $newAlias = "test_updated";
        
        $this->client->request(
            method: 'PATCH', 
            uri: '/api/en/company/' . $this->companyId,
            content: json_encode([
                "alias" => $newAlias,
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $company = $this->decodeResponse();
        $this->assertEquals($newAlias, $company['data']['alias']);
    }

    /** @test */
    public function testGetAllCompanies()
    {
        $this->client->request('GET', '/api/en/company');
        
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $companies = $this->decodeResponse();
        $this->assertGreaterThan(0, count($companies['data']));
    }

    /** @test */
    public function testGetOneCompany()
    {
        $this->client->request('GET', '/api/en/company/' . $this->companyId);
        
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $company = $this->decodeResponse();
        $this->assertEquals(CompanyFixtures::COMPANY_ALIAS, $company['data']['alias']);
        $this->assertArrayHasKey('name', $company['data']['translations']['en']);
    }

    /** @test */
    public function testDeleteCompany()
    {
        $this->client->request('DELETE', '/api/en/company/' . $this->companyId);

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
