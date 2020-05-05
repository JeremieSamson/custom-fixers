# method_to_route_annotation

### Configuration examples

```php
$config = PhpCsFixer\Config::create()
    ->setRules([
        'Jsamson/method_to_route_annotation' => true,
       
    ])
    ->registerCustomFixers([
        new JSamson\CS\Fixer\Deprecation\ResponseCodeFixer,
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