# DbpRelayBaseBundle

[GitLab](https://gitlab.tugraz.at/dbp/relay/dbp-relay-base-bundle) | [Packagist](https://packagist.org/packages/dbp/relay-base-bundle)

This Symfony bundle contains entities required by many bundles for the DBP Relay project.

## Integration into the Relay API Server

* Add the bundle package as a dependency:

```
composer require dbp/relay-base-bundle
```

* Add the bundle to your `config/bundles.php` in front of `DbpRelayCoreBundle`:

```php
...
Dbp\Relay\BaseBundle\DbpRelayBaseBundle::class => ['all' => true],
Dbp\Relay\CoreBundle\DbpRelayCoreBundle => ['all' => true],
];
```

* Run `composer install` to clear caches

## PersonProvider service

For this bundle to work you need to create a service that implements
[PersonProviderInterface](https://gitlab.tugraz.at/dbp/relay/dbp-relay-base-bundle/-/blob/main/src/API/PersonProviderInterface.php)
in your application.

### Example

#### Service class

You can for example put below code into `src/Service/PersonProvider.php`:

```php
<?php

declare(strict_types=1);

namespace YourUniversity\Service;

use Dbp\Relay\BaseBundle\API\PersonProviderInterface;
use Dbp\Relay\BaseBundle\Entity\Person;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PersonProvider implements PersonProviderInterface
{
    /**
     * @param array $filters $filters['search'] can be a string to search for people (e.g. part of the name)
     * @return Person[]
     */
    public function getPersons(array $filters): array
    {
        $people = some_method_to_fetch_persons($filters);

        return $people;
    }

    public function getPersonsByNameAndBirthDate(string $givenName, string $familyName, string $birthDate): array
    {
        $people = some_method_to_fetch_persons_by_name_and_birthday($givenName, $familyName, $birthDate);

        return $people;
    }

    public function getPerson(string $id): Person
    {
        return some_method_to_fetch_person_by_id($id);
    }

    /**
     * This is only used by external services (e.g. the alma bundle) to translate external persons to internal persons
     *
     * @param string $service identifies the service that wants to fetch a person
     * @param string $serviceID identifies person by an external id
     * @return Person
     */
    public function getPersonForExternalService(string $service, string $serviceID): Person
    {
        switch($service) {
            case "some-service":
                return some_method_to_fetch_person_from_external_service($serviceID);
            break;
            default:
                throw new BadRequestHttpException("Unknown service: $service");
        }
    }

    /**
     * Returns the Person matching the current user. Or null if there is no associated person
     * like when the client is another server.
     */
    public function getCurrentPerson(): ?Person
    {
        return some_method_to_fetch_current_person();
    }
}
```

#### Services configuration

For above class you need to add this to your `src/Resources/config/services.yaml`:

```yaml
  Dbp\Relay\BaseBundle\API\PersonProviderInterface:
    '@YourUniversity\Service\PersonProvider'
```

## OrganizationProvider service

For services that need to fetch organizations you need to create a service that implements
[OrganizationProviderInterface](https://gitlab.tugraz.at/dbp/relay/dbp-relay-base-bundle/-/blob/main/src/API/OrganizationProviderInterface.php)
in your application.

### Example

#### Service class

You can for example put below code into `src/Service/OrganizationProvider.php`:

```php
<?php

declare(strict_types=1);

namespace YourUniversity\Service;

use Dbp\Relay\BaseBundle\API\OrganizationProviderInterface;
use Dbp\Relay\BaseBundle\Entity\Organization;

class OrganizationProvider implements OrganizationProviderInterface
{
    public function getOrganizationById(string $identifier, string $lang): Organization
    {
        return some_method_that_fetches_an_organization_by_id($identifier, $lang);
    }

    /**
     * @return Organization[]
     */
    public function getOrganizationsByPerson(Person $person, string $context, string $lang): array
    {
        return some_method_that_fetches_an_organization_by_person($person, $context, $lang);
    }

    /**
     * @return Organization[]
     */
    public function getOrganizations(string $lang): array
    {
        return some_method_that_fetches_all_organizations($lang);
    }
}
```

#### Services configuration

For above class you need to add this to your `src/Resources/config/services.yaml`:

```yaml
  Dbp\Relay\BaseBundle\API\OrganizationProviderInterface:
    '@YourUniversity\Service\OrganizationProvider'
```
