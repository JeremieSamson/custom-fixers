# sensio_to_symfony_route

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
