<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use Dbp\Relay\BasePersonBundle\Entity\Person;
use Dbp\Relay\BasePersonBundle\TestUtils\DummyPersonProvider;
use Dbp\Relay\CoreBundle\TestUtils\UserAuthTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class ExtTest extends ApiTestCase
{
    use UserAuthTrait;

    private function withPerson(Client $client, UserInterface $user): Person
    {
        $person = new Person();
        $person->setIdentifier($user->getUserIdentifier());
        $personProvider = new DummyPersonProvider($person);
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

    public function testGetPersonWrongAuth()
    {
        $client = $this->withUser('foobar', [], '42');
        $response = $client->request('GET', '/base/people/foobar', ['headers' => [
            'Authorization' => 'Bearer NOT42',
        ]]);
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

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

    public function testResponseHeaders()
    {
        $client = $this->withUser('foobar', [], '42');
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
