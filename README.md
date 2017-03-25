# Bunq API Client #

![Build status](https://travis-ci.org/DennisSnijder/bunq-api-client.svg)

## Description ##

A PHP Client Library for accessing Bunq's API.

## Installation ##

```
$ composer require snijder/bunq-api-client
```

## Usage ##

```php
$keyPair = new \Snijder\Bunq\Model\KeyPair($apiKey, $publicKey, $privateKey);
$bunqClient = new \Snijder\Bunq\BunqClient($keyPair);

$userResource = new \Snijder\Bunq\Resource\UserResource($BunqClient);
$userResource->listUsers(); //list all available users.
```


## Token Storage ##

This Bunq API client automatically handles the installation by itself.
By default the tokens are being store in the "PHP temporary folder".

You can use the ``TokenStorageInterface`` to overwrite the default file system storage.

```php
$bunqClient->setInstallationTokenStorage($myInstallationTokenStorage);
$bunqClient->setSessionTokenStorage($mySessionTokenStorage);
```

or use the default token file storage.

```php
$bunqClient->setSessionTokenStorage(
    new \Snijder\Bunq\Storage\SessionTokenFileStorage($path)
);

$bunqClient->setInstallationTokenStorage(
    new \Snijder\Bunq\Storage\InstallationTokenFileStorage($path)
);
```