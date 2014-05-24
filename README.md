M12.Foundation
=============

TYPO3 Neos package implementing Foundation 5 elements.

## Instalation

From command line (from Neos root directory) type:

```composer require m12/neos-foundation:dev-master```

**Alternatively**, you might add it manually to composer.json:
```
    "require": {
        ...
        "m12/neos-foundation": "dev-master"
    },
```
and then execute `composer install`.

After all call:

```./flow flow:cache:flush --force && ./flow cache:warmup```
