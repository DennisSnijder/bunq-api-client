<?php
require_once __DIR__ . "/../vendor/autoload.php";


$keyPair = createKeyPair();

var_dump(\Snijder\Bunq\Installation::install($keyPair->publicKey));

/**
 * Simple function to create a private and public key.
 *
 * @return stdClass
 */
function createKeyPair()
{
    $config = [
        'private_key_bits' => 2048,
        'private_key_type' => OPENSSL_KEYTYPE_RSA
    ];

    // Create the private and public key.
    $resourceIdentifier = openssl_pkey_new($config);

    openssl_pkey_export($resourceIdentifier, $privateKey);
    $publicKey = openssl_pkey_get_details($resourceIdentifier)['key'];

    $pair = new stdClass();
    $pair->privateKey = $privateKey;
    $pair->publicKey = $publicKey;

    // Clean up the key resource.
    openssl_pkey_free($resourceIdentifier);

    return $pair;
}