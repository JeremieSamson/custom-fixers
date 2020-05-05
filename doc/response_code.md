# response_code

### Configuration examples

```php
$config = PhpCsFixer\Config::create()
    ->setRules([
        'Jsamson/response_code' => true,
       
    ])
    ->registerCustomFixers([
        new JSamson\CS\Fixer\CodingStyle\ResponseCodeFixer,
    ])
;
```

### Diffs

```diff
-return new JsonResponse([], 200);
+return new JsonResponse([], Response::HTTP_OK);
```