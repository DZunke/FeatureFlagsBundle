# Symfony FeatureFlagsBundle [![License](https://poser.pugx.org/dzunke/feature-flags-bundle/license.svg)](https://packagist.org/packages/dzunke/feature-flags-bundle)

![GitHub Workflow Status (branch)](https://img.shields.io/github/workflow/status/DZunke/FeatureFlagsBundle/CI/master?style=for-the-badge)

The Bundle will allow you to implement Feature Flags to your Application.
Please Note that there is no Interface available, so the Flags must be
configured directly in Symfony-Configs.

**Use Versions ^2.0 if Symfony 3.0 Support is wanted!**
**Use Versions ^4.0 if Symfony 4.2 Support is wanted!**
**Use Versions ^5.0 if Symfony 4.3 or 5.x Support is wanted!**
**Use Versions ^6.0 if Symfony 6.x Support is wanted!**

## Documentation

## Setup

You can add the Bundle by running [Composer](http://getcomposer.org) on your shell or adding it directly to your composer.json

``` bash
php composer.phar require dzunke/feature-flags-bundle:"^6.0"
```

``` json
"require" :  {
    "dzunke/feature-flags-bundle": "^6.0"
}
```
The Namespace will be registered by autoloading with Composer but to use the integrated features for symfony you have to register the Bundle.

``` php
# app/AppKernel.php
public function registerBundles()
{
    $bundles = [
        // [..]
        new DZunke\FeatureFlagsBundle\DZunkeFeatureFlagsBundle(),
    ];
}
```
Without any Configuration all Features will be enabled! But at this point you
can start developing.

## Usage

### Service-Container

The simplest way to use the Bundle is to get the Container and request the
state of a Feature. **Note**: Features that are not configured are enabled by
default.

``` php
# src/AcmeBundle/Controller/IndexController.php
<?php

namespace AcmeBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
class DefaultController extends Controller
{
    public function indexAction()
    {
        if ($this->get('dz.feature_flags.toggle')->isActive('FooFeature')) {
           // [...]
        }
        // [...]
    }
}

```

### Twig

``` php
# src/AcmeBundle/Resources/views/Index/index.html.twig
{% if has_feature('FooFeature') %}
    <p>Lorem Ipsum Dolor ...</p>
{% endif %}
```

### Argument-Usage

On every check you can give arguments to the check if you want to specify
the check. The Arguments for a Flag can be definied by an array on the validation
method. The Keys must be named like the condition itself. Please Note that if the
Condition does not support the Arguments they would be ignored.

``` php
# src/AcmeBundle/Resources/views/Index/index.html.twig
{% if has_feature('FooBarFeature', {'device': 'tablet'}) %}
    <p>Lorem Ipsum Dolor ...</p>
{% endif %}
```

### Creating a Condition

At first the Condition must be created. The Condition must implement the
ConditionInterface. There is a general context available.

``` php
<?php
# src/AcmeBundle/FeatureFlags/Condition/Foo.php
namespace AcmeBundle\FeatureFlags\Condition;

use DZunke\FeatureFlagsBundle\Toggle\Conditions\AbstractCondition;
use DZunke\FeatureFlagsBundle\Toggle\Conditions\ConditionInterface;

class Foo extends AbstractCondition implements ConditionInterface
{
    public function validate($config, $argument = null)
    {
        // [..] Implement your Methods to Validate the Feature

        return true;
    }

    public function __toString()
    {
        return 'Foo';
    }
}
```

After the Class was created it must be defined as a Tagged-Service. With this
Tag and the Alias the Condition would be loaded. At this point there is many
space to extend the Condition by adding calls or arguments.

``` yaml
# src/AcmeBundle/Resources/config/services.yml
services:
    acme.feature_flags.condition.fo:
        class: DZunke\FeatureFlagsBundle\Toggle\Conditions\Foo
        calls:
            - [setContext, [@dz.feature_flags.context]]
        tags:
            -  { name: dz.feature_flags.toggle.condition, alias: foo }
```

## Configuration

### Example

``` yaml
d_zunke_feature_flags:
    flags:
        FooFeature: # feature will always be disabled
            default: false
        BarFeature: # feature will only be enabled for a list of special ClientIps
            conditions_config:
                ip_address: [192.168.0.1]
        BazFeature: # the feature will be enabled for the half of the users
            conditions_config:
                percentage:
                    percentage: 50
                    cookie: ExampleCookieForFeature
                    lifetime: 3600
        FooBarFeature:
            conditions_config:
                device:
                    tablet: "/ipad|playbook|android|kindle|opera mobi|arm|(^.*android(?:(?!mobile).)*$)/i"
                    mobile: "/iphone|ipod|bb10|meego|blackberry|windows\\sce|palm|windows phone|((android.*mobile))|mobile/i"
```

## Available Conditions

``` yaml
hostname: [example.local, www.example.local]
```

``` yaml
ip_address: [192.168.0.1, 192.168.0.2]
```

``` yaml
percentage:
  cookie: NameThisCookieForTheUser # Default: 84a0b3f187a1d3bfefbb51d4b93074b1e5d9102a
  percentage: 29 # Default: 100
  lifetime: 3600 # Default: 86400 - 1 day
```

``` yaml
device:
  name: regex # give regex for each valid device
```

``` yaml
# See php.net/datetime
date:
  start_date: "2016-09-01" # Start date, accepts DateTime constructor values. Defaults to "now".
  end_date: "2016-09-03" # End date, accepts DateTime constructor values. Defaults to "now".
```

### Reference

``` yaml
d_zunke_feature_flags:
    # the default state to return for non-existent features
    default:              true
    # feature flags for the built system
    flags:
        # Prototype
        feature:
            # general active state for the flag - if conditions used it would be irrelevant
            default:              false
            # list of configured conditions which must be true to set this flag active
            conditions_config:    []
```

## License

FeatureFlagsBundle is licensed under the MIT license.
