# Tag functionality for Symfony2

Tag bundle provides tagging of different units inside a Symfony 2 project. Taging is done with the Selector bundle.


## Dependencies

xi-bundle-selector
* https://github.com/xi-project/xi-bundle-selector

## Installing

### deps -file
```
[XiSelectorBundle]
    git=http://github.com/xi-project/xi-bundle-selector.git
    target=/bundles/Xi/Bundle/SelectorBundle

[XiTagBundle]
    git=http://github.com/xi-project/xi-bundle-tag.git
    target=/bundles/Xi/Bundle/TagBundle
```

### autoload.php file
```php
<?php
'Xi\\Bundle'       => __DIR__.'/../vendor/bundles',
?>
```

### appKernel.php -file
```php
<?php
            new Xi\Bundle\SelectorBundle\XiSelectorBundle(),
            new Xi\Bundle\TagBundle\XiTagBundle(),
 ?>
```

### routing.yml -file
```yml
XiTagBundle:
    resource: "@XiTagBundle/Resources/config/routing.yml"
    prefix:   /
```

### config.yml -file

Your twig configuration should look something like this:

```
twig:
    debug:            %kernel.debug%
    strict_variables: %kernel.debug%
    form:
        resources:
            - 'XiTagBundle:Form:tag-fields.html.twig'
    globals:
        domain:   %domain%
```