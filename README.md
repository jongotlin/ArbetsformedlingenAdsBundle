# ArbetsformedlingenAdsBundle
Symfony bundle for [jongotlin/arbetsformedlingen-ads](https://github.com/jongotlin/ArbetsformedlingenAds)

## Installation
Install with composer and your favourite http adapter (in this case Guzzle 6)
```bash
$ composer require php-http/client-implementation:^1.0 php-http/discovery:^1.0 php-http/guzzle6-adapter:^1.0 php-http/httplug:^1.0 php-http/message:^1.0 jongotlin/arbetsformedlingen-ads-bundle:^1.0
```

In services.yml
```yaml
services:
    http.client:
        class: Http\Adapter\Guzzle6\Client
        arguments:
            - '@client'

    client:
        class: GuzzleHttp\Client
```

In config.yml
```yaml
arbetsformedlingen_ads:
    http_client: 'http.client'
    test_environment: true
    loggers: ['logger', 'your.custom.logger.service_name']
```

In AppKernel.php
```php
$bundles = array(
    // ...
    new JGI\ArbetsformedlingenAdsBundle\ArbetsformedlingenAdsBundle(),
);
```

## Usage

See [jongotlin/arbetsformedlingen-ads](https://github.com/jongotlin/ArbetsformedlingenAds)
