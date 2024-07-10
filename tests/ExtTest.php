<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use Dbp\Relay\BasePersonBundle\Entity\Person;
use Dbp\Relay\BasePersonBundle\Service\DummyPersonProvider;
use Dbp\Relay\CoreBundle\TestUtils\UserAuthTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ExtTest extends ApiTestCase
{
    use UserAuthTrait;

    private function withPerson(Client $client, UserInterface $user): Person
    {
        $person = new Person();
        $person->setIdentifier($user->getUserIdentifier());
        $person->setGivenName('John');
        $person->setFamilyName('Doe');
        $personProvider = new DummyPersonProvider();
        $personProvider->setCurrentPerson($person);
        $container = $client->getContainer();
        $container->set('test.PersonProviderInterface', $personProvider);

        return $person;
    }

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
        $person = $this->withPerson($client, $user);
        $person->setGivenName('Foo');
        $person->setFamilyName('Bar');
        $response = $client->request('GET', '/base/people/foobar', ['headers' => [
            'Authorization' => 'Bearer 42',
        ]]);
        $this->assertJson($response->getContent(false));
        $data = json_decode($response->getContent(false), true, 512, JSON_THROW_ON_ERROR);
        $this->assertEquals('/base/people/foobar', $data['@id']);
        $this->assertEquals('foobar', $data['identifier']);
        $this->assertEquals('Foo', $data['givenName']);
        $this->assertEquals('Bar', $data['familyName']);
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
        $person = $this->withPerson($client, $user);
        $person->setGivenName('Foo');
        $person->setFamilyName('Bar');
        $response = $client->request('GET', '/base/people', ['headers' => [
            'Authorization' => 'Bearer 42',
        ]]);
        $this->assertJson($response->getContent(false));
        $personData = json_decode($response->getContent(false), true, 512, JSON_THROW_ON_ERROR)['hydra:member'][0];
        $this->assertEquals('/base/people/foobar', $personData['@id']);
        $this->assertEquals('foobar', $personData['identifier']);
        $this->assertEquals('Foo', $personData['givenName']);
        $this->assertEquals('Bar', $personData['familyName']);
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
        $this->withPerson($client, $user);
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
