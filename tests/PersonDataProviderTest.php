<?php

declare(strict_types=1);

namespace Dbp\Relay\BasePersonBundle\Tests;

use Dbp\Relay\BasePersonBundle\DataProvider\PersonDataProvider;
use Dbp\Relay\BasePersonBundle\Entity\Person;
use Dbp\Relay\BasePersonBundle\Service\DummyPersonProvider;
use Dbp\Relay\CoreBundle\Exception\ApiError;
use Dbp\Relay\CoreBundle\TestUtils\DataProviderTester;
use Dbp\Relay\CoreBundle\TestUtils\TestAuthorizationService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class PersonDataProviderTest extends TestCase
{
    private const TEST_USER_IDENTIFIER = 'test_user';

    private ?DataProviderTester $personDataProviderTester = null;
    private ?DummyPersonProvider $personProvider = null;
    private ?PersonDataProvider $personDataProvider = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->personProvider = new DummyPersonProvider();
        $this->personDataProvider = new PersonDataProvider($this->personProvider);
        $this->personDataProvider->setConfig($this->getPersonDataProviderConfig());
        $this->personDataProviderTester = DataProviderTester::create($this->personDataProvider, Person::class, ['BasePerson:output']);
        $this->loginUserWithAttributes();
    }

    public function testGetPersonForbidden(): void
    {
        $this->personProvider->addPerson('foo', 'Foo', 'Bar');
        try {
            $this->personDataProviderTester->getItem('foo');
            $this->fail('Expected an exception');
        } catch (ApiError $apiError) {
            $this->assertSame(Response::HTTP_FORBIDDEN, $apiError->getStatusCode());
        }
    }

    public function testGetPerson(): void
    {
        $userAttributes = $this->getDefaultUserAttributes();
        $userAttributes['MAY_READ'] = true;
        $this->loginUserWithAttributes($userAttributes);
        $this->personProvider->addPerson('foo', 'Foo', 'Bar');
        $person = $this->personDataProviderTester->getItem('foo');
        $this->assertSame('foo', $person->getIdentifier());
        $this->assertSame('Foo', $person->getGivenName());
        $this->assertSame('Bar', $person->getFamilyName());
    }

    public function testGetPersonLocalDataAttribute(): void
    {
        $this->loginUserWithAttributes([
            'MAY_READ' => true,
            'MAY_READ_TITLE' => true,
        ]);
        $this->personProvider->addPerson('foo', 'Foo', 'Bar', ['title' => 'Queen']);
        $person = $this->personDataProviderTester->getItem('foo', ['includeLocal' => 'title']);
        $this->assertSame('foo', $person->getIdentifier());
        $this->assertSame('Foo', $person->getGivenName());
        $this->assertSame('Bar', $person->getFamilyName());
        $this->assertSame('Queen', $person->getLocalDataValue('title'));
    }

    public function testGetPersonLocalDataAttributeForbidden(): void
    {
        $this->loginUserWithAttributes([
            'MAY_READ' => true,
            'MAY_READ_TITLE' => false,
        ]);
        $this->personProvider->addPerson('foo', 'Foo', 'Bar', ['title' => 'Queen']);
        $person = $this->personDataProviderTester->getItem('foo', ['includeLocal' => 'title']);
        $this->assertSame('foo', $person->getIdentifier());
        $this->assertSame('Foo', $person->getGivenName());
        $this->assertSame('Bar', $person->getFamilyName());
        $this->assertSame(null, $person->getLocalDataValue('title'));
    }

    public function testGetSelfLocalDataAttributeForbidden(): void
    {
        // users may always get their own local data attributes
        $this->loginUserWithAttributes([
            'MAY_READ' => true,
            'MAY_READ_TITLE' => false,
        ]);
        $this->personProvider->addPerson(self::TEST_USER_IDENTIFIER, 'Test', 'User', ['title' => 'PHD']);
        $person = $this->personDataProviderTester->getItem(self::TEST_USER_IDENTIFIER, ['includeLocal' => 'title']);
        $this->assertSame(self::TEST_USER_IDENTIFIER, $person->getIdentifier());
        $this->assertSame('Test', $person->getGivenName());
        $this->assertSame('User', $person->getFamilyName());
        $this->assertSame('PHD', $person->getLocalDataValue('title'));
    }

    public function testGetPersonsForbidden(): void
    {
        $this->personProvider->addPerson('foo', 'Foo', 'Bar');
        try {
            $this->personDataProviderTester->getCollection();
            $this->fail('Expected an exception');
        } catch (ApiError $apiError) {
            $this->assertSame(Response::HTTP_FORBIDDEN, $apiError->getStatusCode());
        }
    }

    public function testGetPersons(): void
    {
        $userAttributes = $this->getDefaultUserAttributes();
        $userAttributes['MAY_READ'] = true;
        $this->loginUserWithAttributes($userAttributes);
        $this->personProvider->addPerson('foo', 'Bar', 'Baz');
        $this->personProvider->addPerson('bar', 'Baz', 'Foo');
        $this->personProvider->addPerson('baz', 'Foo', 'Bar');
        $persons = $this->personDataProviderTester->getPage(1, 2);
        $this->assertCount(2, $persons);
        $person = $persons[0];
        $this->assertSame('foo', $person->getIdentifier());
        $this->assertSame('Bar', $person->getGivenName());
        $this->assertSame('Baz', $person->getFamilyName());
        $person = $persons[1];
        $this->assertSame('bar', $person->getIdentifier());
        $this->assertSame('Baz', $person->getGivenName());
        $this->assertSame('Foo', $person->getFamilyName());
        $persons = $this->personDataProviderTester->getPage(2, 2);
        $this->assertCount(1, $persons);
        $person = $persons[0];
        $this->assertSame('baz', $person->getIdentifier());
        $this->assertSame('Foo', $person->getGivenName());
        $this->assertSame('Bar', $person->getFamilyName());
    }

    public function testGetPersonsWithLocalDataAttribute(): void
    {
        $this->loginUserWithAttributes([
            'MAY_READ' => true,
            'MAY_READ_TITLE' => true,
        ]);
        $this->personProvider->addPerson('foo', 'Bar', 'Baz', ['title' => 'Queen']);
        $persons = $this->personDataProviderTester->getCollection(['includeLocal' => 'title']);
        $this->assertCount(1, $persons);
        $person = $persons[0];
        $this->assertSame('foo', $person->getIdentifier());
        $this->assertSame('Bar', $person->getGivenName());
        $this->assertSame('Baz', $person->getFamilyName());
        $this->assertSame('Queen', $person->getLocalDataValue('title'));
    }

    public function testGetPersonsWithLocalDataAttributeForbidden(): void
    {
        $this->loginUserWithAttributes([
            'MAY_READ' => true,
            'MAY_READ_TITLE' => false,
        ]);
        $this->personProvider->addPerson('foo', 'Bar', 'Baz', ['title' => 'Queen']);
        $persons = $this->personDataProviderTester->getCollection(['includeLocal' => 'title']);
        $this->assertCount(1, $persons);
        $person = $persons[0];
        $this->assertSame('foo', $person->getIdentifier());
        $this->assertSame('Bar', $person->getGivenName());
        $this->assertSame('Baz', $person->getFamilyName());
        $this->assertSame(null, $person->getLocalDataValue('title'));
    }

    protected function getDefaultUserAttributes(): array
    {
        return [
            'MAY_READ' => false,
            'MAY_READ_TITLE' => false,
        ];
    }

    protected function loginUserWithAttributes(?array $userAttributes = null): void
    {
        $userAttributes ??= $this->getDefaultUserAttributes();
        TestAuthorizationService::setUp($this->personDataProvider, self::TEST_USER_IDENTIFIER, currentUserAttributes: $userAttributes);
    }

    protected function getPersonDataProviderConfig(): array
    {
        return [
            'authorization' => [
                'roles' => [
                    'ROLE_READER' => 'user.get("MAY_READ")',
                ],
            ],
            'local_data' => [
                [
                    'local_data_attribute' => 'title',
                    'read_policy' => 'user.get("MAY_READ_TITLE")',
                ],
            ],
        ];
    }
}
