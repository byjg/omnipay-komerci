# Omnipay: Komerci WebServer ([Rede](https://www.userede.com.br))

[![Build Status](https://travis-ci.org/byjg/omnipay-komerci.svg?branch=master)](https://travis-ci.org/byjg/omnipay-komerci)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/byjg/omnipay-komerci/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/byjg/omnipay-komerci/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5814bf37-e3ac-4232-9c5d-fe304a340e83/mini.png)](https://insight.sensiolabs.com/projects/5814bf37-e3ac-4232-9c5d-fe304a340e83)

**Komerci WebService ([Rede](https://www.userede.com.br)) driver for the Omnipay PHP payment processing library**

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements Dummy support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "byjg/omnipay-komerci": "~1.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

The following gateways are provided by this package:

* Komerci (Rede)

Komerci is the solution for e-commerce from Rede (former Redecard). Rede is a Brazilian acquirer. 
This is responsible for the authentication, authorization and capture the card data in the Rede environment. 

**NOTE**: Before using this driver is necessary to register the IP in the Komerci website. 
If your IP is not registered you'll get an error 500. 

```php
// Setup payment gateway
$gateway = Omnipay::create('Komerci');
$gateway->setApiKey('00000000');
$gateway->setUsername('user');
$gateway->setPassword('pass');
$gateway->setTestMode(true);

// Example form data
$formData = [
    'name' => 'Joao Magalhaes',
    'number' => '4242424242424242',
    'expiryMonth' => '6',
    'expiryYear' => '2016',
    'cvv' => '123'
];

// Send purchase request
$response = $gateway->purchase(
    [
        'amount' => '10.00',
        'transactionId' => '1234',
        'card' => $formData,
    ]
)->send();
```

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

Implemented methods
* authorize
* capture
* purchse
* void

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/thephpleague/omnipay-dummy/issues),
or better yet, fork the library and submit a pull request.
