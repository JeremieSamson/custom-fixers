# doctrine_migration_clean

### Configuration examples

```php
$config = PhpCsFixer\Config::create()
    ->setRules([
        'Jsamson/doctrine_migration_clean' => true,
       
    ])
    ->registerCustomFixers([
        new JSamson\CS\Fixer\Doctrine\DoctrineMigrationCleanFixer,
    ])
;
```
