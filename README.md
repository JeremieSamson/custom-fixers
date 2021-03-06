# Custom fixers for [PHP-CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer)

[![Actions Status](https://github.com/JeremieSamson/custom-fixers/workflows/CI/badge.svg)](https://github.com/JeremieSamson/custom-fixers/actions)

# Installation

```bash
composer require jsamson/php-cs-custom-fixer --dev
```

# Fixers

## [Jsamson/sensio_to_symfony_route](doc/sensio_to_symfony_route.md)

Replace deprecated `Sensio\Bundle\FrameworkExtraBundle\Configuration\Route` by `Symfony\Component\Routing\Annotation\Route`


## [Jsamson/method_to_route_annotation](doc/method_to_route_annotation.md)

Remove deprecated `@Method` annotation and move content to `@Route` args

## [Jsamson/doctrine_migration_clean](doc/doctrine_migration_clean.md)

Remove `declare(strict_types=1)`, auto-generated comments, and `abortIf` calls from doctrine migration generated files.
