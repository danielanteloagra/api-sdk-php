[Smartling Translation API PHP SDK](http://docs.smartling.com)
==================================

This is an optimized and refactored version of https://github.com/Smartling/api-sdk-php/ with the following changes/additions:

- Composer based with PSR2 coding standards
- Improved curl connection system (rewritten into decoupled composer dependency)
- Fixed several bugs/issues
- Upload times improved greatly
- Added simple upload method that uploads arrays directly
- Added ContentBuilder which converts arrays into the expected json format


Description
-----------

This repository contains the PHP SDK for accessing the Smartling Translation API.

The Smartling Translation API lets developers internationalize their website or app by automating the translation and integration of their site content.
Developers can upload resource files/content and then download the translated files in a language of their choosing. There are options to allow for professional translation, community translation and machine translation.

For a full description of the Smartling Translation API, please read the docs at: http://docs.smartling.com


Usage
-----

Simply add the following to your composer file and do a composer update:

```
  "require": {
        ...
        "daa/smartling-api-sdk": "2.*"
  },
```


For example of usage have a look at examples folder.

For more information or to report an issue go to https://github.com/Smartling/api-sdk-php/
