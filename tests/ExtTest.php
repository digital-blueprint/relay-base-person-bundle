<?php

declare(strict_types=1);

namespace DBP\API\BaseBundle\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use DBP\API\BaseBundle\Entity\Person;
use DBP\API\BaseBundle\TestUtils\DummyPersonProvider;
use DBP\API\CoreBundle\TestUtils\UserAuthTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class ExtTest extends ApiTestCase
{
    use UserAuthTrait;

    private function withPerson(Client $client, UserInterface $user): Person
    {
        $person = new Person();
        $person->setIdentifier($user->getUserIdentifier());
        $person->setRoles($user->getRoles());
        $personProvider = new DummyPersonProvider($person);
        $container = $client->getContainer();
        $container->set('test.PersonProviderInterface', $personProvider);

        return $person;
    }

    public function testGetPersonNoAuth()
    {
        [$client, $user] = $this->withUser('foobar', '42');
        $response = $client->request('GET', '/people/foobar');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testGetPersonWrongAuth()
    {
        [$client, $user] = $this->withUser('foobar', '42');
        $response = $client->request('GET', '/people/foobar', ['headers' => [
            'Authorization' => 'Bearer NOT42',
        ]]);
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testGetPerson()
    {
        [$client, $user] = $this->withUser('foobar', '42');
        $person = $this->withPerson($client, $user);
        $person->setEmail('foo@bar.com');
        $response = $client->request('GET', '/people/foobar', ['headers' => [
            'Authorization' => 'Bearer 42',
        ]]);
        $this->assertJson($response->getContent(false));
        $data = json_decode($response->getContent(false), true);
        $this->assertEquals('/people/foobar', $data['@id']);
        $this->assertEquals('foobar', $data['identifier']);
        $this->assertEquals('foo@bar.com', $data['email']);
    }

    public function testResponseHeaders()
    {
        [$client, $user] = $this->withUser('foobar', '42');
        $response = $client->request('GET', '/people/foobar', ['headers' => [
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

    public function testGetPersonRoles()
    {
        [$client, $user] = $this->withUser('foobar', '42', ['roles' => ['ROLE']]);
        $this->withPerson($client, $user);
        $response = $client->request('GET', '/people/foobar', ['headers' => [
            'Authorization' => 'Bearer 42',
        ]]);
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(['ROLE'], $data['roles']);
    }

    public function testAuthChecks()
    {
        $client = self::createClient();

        $endpoints = [
            '/people',
            '/people/foo',
            // FIXME: '/people/foo/organizations',
            '/organizations',
            '/organizations/foo',
        ];
        foreach ($endpoints as $path) {
            $response = $client->request('GET', $path);
            $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        }
    }
}
