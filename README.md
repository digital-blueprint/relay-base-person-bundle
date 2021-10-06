# API-Base-Bundle

[GitLab](https://gitlab.tugraz.at/dbp/relay/api-base-bundle) | [Packagist](https://packagist.org/packages/dbp/api-base-bundle)

This Symfony bundle contains entities required by many bundles for the DBP Relay project.

## Integration into the API Server

* Add the bundle package as a dependency:

```
composer require dbp/api-base-bundle
```

* Add the bundle to your `config/bundles.php`:

```php
...
DBP\API\BaseBundle\DbpBaseBundle::class => ['all' => true],
DBP\API\CoreBundle\DbpCoreBundle::class => ['all' => true],
];
```

* Run `composer install` to clear caches

