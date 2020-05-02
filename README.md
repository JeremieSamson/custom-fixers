# Custom fixers for [PHP-CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer)

# Installation

```bash
composer require jsamson/php-cs-custom-fixer --dev
```

# Fixers

## Jsamson/sensio_to_symfony_route

Replace deprecated Sensio\Bundle\FrameworkExtraBundle\Configuration\Route by Symfony\Component\Routing\Annotation\Route

### Configuration examples

```php
$config = PhpCsFixer\Config::create()
    ->setRules([
        'Jsamson/sensio_to_symfony_route' => true,
       
    ])
    ->registerCustomFixers([
        new JSamson\CS\Fixer\Deprecation\SensioToSymfonyRouteFixer,
    ])
;
```

### Fixes

```diff
+use Symfony\Component\Routing\Annotation\Route;
-use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
```

## Jsamson/method_to_route_annotation

Remove deprecated `@Method` annotation and move content to `@Route` args

### Configuration examples

```php
$config = PhpCsFixer\Config::create()
    ->setRules([
        'Jsamson/method_to_route_annotation' => true,
       
    ])
    ->registerCustomFixers([
        new JSamson\CS\Fixer\Deprecation\MethodToRouteAnnotationFixer,
    ])
;
```

### Fixes

```diff
     /**
-     * @Route(name="foo", path="foo")
+     * @Route(name="foo", path="foo", methods={"GET"})
      */
```