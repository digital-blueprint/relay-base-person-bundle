# DbpRelayBaseBundle

[GitLab](https://gitlab.tugraz.at/dbp/relay/dbp-relay-base-bundle) | [Packagist](https://packagist.org/packages/dbp/relay-base-bundle)

This Symfony bundle contains entities required by many bundles for the DBP Relay project.

## Integration into the Relay API Server

* Add the bundle package as a dependency:

```
composer require dbp/relay-base-bundle
```

* Add the bundle to your `config/bundles.php` in front on `DbpRelayCoreBundle`:

```php
...
Dbp\Relay\BaseBundle\DbpRelayBaseBundle::class => ['all' => true],
Dbp\Relay\CoreBundle\DbpRelayCoreBundle => ['all' => true],
];
```

* Run `composer install` to clear caches

