# Symfony FeatureFlagsBundle

The Bundle will allow you to implement Feature Flags to your Application.
Please Note that there is no Interface available, so the Flags must be
configured directly in Symfony-Configs.

## Documentation

## Setup

You can add the Bundle by running [Composer](http://getcomposer.org) on your shell or adding it directly to your composer.json

``` bash
php composer.phar require dzunke/feature-flags-bundle:dev-master
```

``` json
"require" :  {
    "dzunke/feature-flags-bundle": "dev-master"
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
use [...]
class DefaultController extends Controller
{
    public function indexAction()
    {
        if ($this->get('dz.feature_flags.toggle')->isActive('FooFeature')) {
           [...]
        }
        [...]
    }
}

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
