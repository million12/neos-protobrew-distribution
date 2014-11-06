# M12.Foundation - Foundation components inside TYPO3 Neos

M12.Foundation aims to implement all [Zurb Foundation](http://foundation.zurb.com/) components, in the best possible way, inside TYPO3 Neos CMS.

The best way is to install it together with [M12.FoundationSite](https://github.com/million12/M12.FoundationSite) Neos site package. It **conflicts with NeosDemoTypo3Org**, so remember to remove `typo3/neosdemotypo3org` dependency from your `composer.json`.

## Usage

You can try ready-to-use [neos-typostrap-distribution](https://github.com/million12/neos-typostrap-distribution), TYPO3 Neos distribution which has this installed and configured.

Alternatively, include in your main `composer.json` file:  
``` json
    "repositories": [
        { "type": "git", "url": "https://github.com/million12/M12.FoundationSite" }
    ],
    "require": {
        # standard dependencies here...
        # "typo3/neosdemotypo3org": "x.y.z", # Remember to remove this one 
        "m12/neos-foundation": "dev-master",
        "m12/neos-foundation-site": "dev-master"
    },
```  
and run `composer install`


## Author(s)

* Marcin Ryzycki marcin@m12.io  
* Samuel Ryzycki samuel@m12.io
