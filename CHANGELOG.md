# Changelog

## Unreleased

## v0.2.43

- Dependency cleanups

## v0.2.42

- Add ROLE_READER, which is required for read operations

## v0.2.41

- Add the new method PersonProviderInterface::getCurrentPersonIdentifier() that should return
the identifier of the person that represents the currently logged-in user, or null of there is
no such person (e.g., for service clients)

## v0.2.40

- Remove support for api-platform 3
- Add role `ROLE_READER` that is required for read operations

## v0.2.39

- Remove support for deprecate 'queryLocal' query parameter

## v0.2.38

- OpenAPI fixes for api-platform >=4.1

## v0.2.37

- Drop support for PHP 8.1
- Drop support for Psalm
- Drop support for Symfony 5
- Add support for api-platform >=4.1 and drop for <3.4

## v0.2.36

- Remove OpenApi example for the collection filter parameter, which makes the request fail if not removed before execution

## v0.2.35

- Add public test utilities TestPersonTrait::withCurrentPerson/withPerson

## v0.2.34

- Adapt to core bundle >= v0.1.190

## v0.2.33

- Improve DummyPersonProvider

## v0.2.31

- Adjust tests for minor core bundle changes

## v0.2.29

- Port to PHPUnit 10
- Port from doctrine annotations to PHP attributes

## v0.2.28

- Add support for api-platform 3.2

## v0.2.26

- Add support for Symfony 6

## v0.2.24

- Drop support for PHP 7.4/8.0

## v0.2.23

- Drop support for PHP 7.3

## v0.2.19

- Minor cleanups and deprecation fixes