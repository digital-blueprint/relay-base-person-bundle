# DbpRelayBasePersonBundle

[GitHub](https://github.com/digital-blueprint/relay-base-person-bundle) | [Packagist](https://packagist.org/packages/dbp/relay-base-person-bundle)


## Integration into the Relay API Server

* Add the bundle package as a dependency:

```
composer require dbp/relay-base-person-bundle
```

* Add the bundle to your `config/bundles.php` in front of `DbpRelayCoreBundle`:

```php
...
Dbp\Relay\BasePersonBundle\DbpRelayBasePersonBundle::class => ['all' => true],
Dbp\Relay\CoreBundle\DbpRelayCoreBundle => ['all' => true],
];
```

* Run `composer install` to clear caches

## PersonProvider service

For this bundle to work you need to create a service that implements
[PersonProviderInterface](https://github.com/digital-blueprint/relay-base-person-bundle/blob/main/src/API/PersonProviderInterface.php)
in your application.

### Example

#### Service class

You can for example put below code into `src/Service/PersonProvider.php`:

```php
<?php

declare(strict_types=1);

namespace YourUniversity\Service;

use Dbp\Relay\BasePersonBundle\API\PersonProviderInterface;
use Dbp\Relay\BasePersonBundle\Entity\Person;
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

    public function getPerson(string $id): Person
    {
        return some_method_to_fetch_person_by_id($id);
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
  Dbp\Relay\BasePersonBundle\API\PersonProviderInterface:
    '@YourUniversity\Service\PersonProvider'
```
