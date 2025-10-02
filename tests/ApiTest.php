<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Tests;

use Dbp\Relay\BasePersonBundle\TestUtils\TestPersonTrait;
use Dbp\Relay\CoreBundle\TestUtils\AbstractApiTest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ApiTest extends AbstractApiTest
{
    use TestPersonTrait;

    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws \JsonException
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testGetPerson()
    {
        $personId = 'janed';
        $givenName = 'Jane';
        $familyName = 'Doe';
        $this->withCurrentPerson($this->testClient->getContainer(), $personId, $givenName, $familyName);
        $this->testClient->setUpUser(userAttributes: ['MAY_READ' => true]);
        $response = $this->testClient->get('/base/people/'.$personId);
        $content = $response->getContent(false);
        $this->assertJson($content);
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        $this->assertEquals('/base/people/'.$personId, $data['@id']);
        $this->assertEquals($personId, $data['identifier']);
        $this->assertEquals($givenName, $data['givenName']);
        $this->assertEquals($familyName, $data['familyName']);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws \JsonException
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testGetPersons()
    {
        $personId = 'janed';
        $givenName = 'Jane';
        $familyName = 'Doe';
        $this->withCurrentPerson($this->testClient->getContainer(), $personId, $givenName, $familyName);
        $this->testClient->setUpUser(userAttributes: ['MAY_READ' => true]);
        $response = $this->testClient->get('/base/people');
        $content = $response->getContent(false);
        $this->assertJson($content);
        $personData = json_decode($content, true, 512, JSON_THROW_ON_ERROR)['hydra:member'][0];
        $this->assertEquals('/base/people/'.$personId, $personData['@id']);
        $this->assertEquals($personId, $personData['identifier']);
        $this->assertEquals($givenName, $personData['givenName']);
        $this->assertEquals($familyName, $personData['familyName']);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testResponseHeaders()
    {
        $this->withCurrentPerson($this->testClient->getContainer(), 'foobar');
        $this->testClient->setUpUser(userAttributes: ['MAY_READ' => true]);
        $response = $this->testClient->get('/base/people/foobar');
        $header = $response->getHeaders();

        // We extend the defaults with CORS related headers
        $this->assertArrayHasKey('vary', $header);
        $this->assertContains('Accept', $header['vary']);
        $this->assertContains('Origin', $header['vary']);
        $this->assertContains('Access-Control-Request-Headers', $header['vary']);
        $this->assertContains('Access-Control-Request-Method', $header['vary']);

        // Make sure we have etag caching enabled
        $this->assertArrayHasKey('etag', $header);
    }

    public function testUnauthorizedRequests()
    {
        foreach (['/base/people', '/base/people/foo'] as $path) {
            $response = $this->testClient->get($path, token: null);
            $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        }
    }

    public function testForbiddenRequests()
    {
        foreach (['/base/people', '/base/people/foo'] as $path) {
            $this->testClient->setUpUser(userAttributes: ['MAY_READ' => false]);
            $response = $this->testClient->get($path);
            $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
        }
    }
}
