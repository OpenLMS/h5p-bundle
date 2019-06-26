# H5PBundle
Bundle to integrate H5P into Symfony. This bundle is for using with OpenLMS. For more info about H5P see [H5P.org](https://h5p.org)

This bundle was tested on Symfony 4

Installation
------------

Install with composer
``` bash
composer require openlms/h5p-bundle
```

Enable the bundle in `bundles.php`
``` php
    OpenLMS\H5PBundle\H5PBundle::class => ['all' => true],
```

Add the H5P assets to the bundle
``` bash
php app/console h5p-bundle:include-assets
php app/console assets:install --symlink
```

Add required tables and relations to the database
``` bash
php app/console doctrine:schema:update --force 
```

Enable the routing in `routing.yml`
``` yaml

openlms_h5p:
    resource: "@H5PBundle/Resources/config/routing.yml"
    prefix:   /
```

Configuration
-------------

Configure the bundle in `config.yml`. (Watch for the underscore between h5 and p)
``` yml
openlms_h5p:
    use_permission: true # This is false by default to let the demo work out of the box.
    storage_dir: h5p # Location to store all H5P libraries and files
    web_dir: web # Location of the public web directory
```
For all configurations see [Configuration.php](DependencyInjection/Configuration.php)
