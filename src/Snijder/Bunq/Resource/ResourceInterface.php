<?php
namespace Snijder\Bunq\Resource;

/**
 * Interface ResourceInterface
 * @package Snijder\Bunq\Resource
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
interface ResourceInterface
{
    /**
     * Returns the resource endpoint.
     *
     * @return string
     */
    public function getResourceEndpoint(): string;
}