<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Tests;

use Dbp\Relay\BasePersonBundle\TestUtils\TestPersonTrait;
use Dbp\Relay\CoreBundle\TestUtils\AbstractApiTest;
use Dbp\Relay\CoreBundle\TestUtils\UserAuthTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ExtTest extends AbstractApiTest
{
    use UserAuthTrait;
    use TestPersonTrait;

    public function testGetPersonNoAuth()
    {
        $client = $this->withUser('foobar', ['foo']);
        $response = $client->request('GET', '/base/people/foobar');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testGetPersonWrongAuth()
    {
        $client = $this->withUser('foobar', [], '42');
        $response = $client->request('GET', '/base/people/foobar', ['headers' => [
            'Authorization' => 'Bearer NOT42',
        ]]);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws \JsonException
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testGetPerson()
    {
        $client = $this->withUser('foobar', [], '42');
        $user = $this->getUser($client);
        $this->withCurrentPerson($client->getContainer(), $user->getUserIdentifier());
        $response = $client->request('GET', '/base/people/foobar', ['headers' => [
            'Authorization' => 'Bearer 42',
        ]]);
        $this->assertJson($response->getContent(false));
        $data = json_decode($response->getContent(false), true, 512, JSON_THROW_ON_ERROR);
        $this->assertEquals('/base/people/foobar', $data['@id']);
        $this->assertEquals('foobar', $data['identifier']);
        $this->assertEquals('Jane', $data['givenName']);
        $this->assertEquals('Doe', $data['familyName']);
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
        $client = $this->withUser('foobar', [], '42');
        $user = $this->getUser($client);
        $this->withCurrentPerson($client->getContainer(), $user->getUserIdentifier());
        $response = $client->request('GET', '/base/people', ['headers' => [
            'Authorization' => 'Bearer 42',
        ]]);
        $this->assertJson($response->getContent(false));
        $personData = json_decode($response->getContent(false), true, 512, JSON_THROW_ON_ERROR)['hydra:member'][0];
        $this->assertEquals('/base/people/foobar', $personData['@id']);
        $this->assertEquals('foobar', $personData['identifier']);
        $this->assertEquals('Jane', $personData['givenName']);
        $this->assertEquals('Doe', $personData['familyName']);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testResponseHeaders()
    {
        $client = $this->withUser('foobar', [], '42');
        $user = $this->getUser($client);
        $this->withCurrentPerson($client->getContainer(), $user->getUserIdentifier());
        $response = $client->request('GET', '/base/people/foobar', ['headers' => [
            'Authorization' => 'Bearer 42',
        ]]);
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

    public function testAuthChecks()
    {
        $client = self::createClient();

        $endpoints = [
            '/base/people',
            '/base/people/foo',
        ];
        foreach ($endpoints as $path) {
            $response = $client->request('GET', $path);
            $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        }
    }
}
