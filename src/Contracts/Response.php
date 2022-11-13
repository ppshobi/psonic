<?php
namespace Psonic\Contracts;

interface Response
{
    /**
     * fetches an item from the parsed buffer
     * @return mixed
     */
    public function get(string $key);

    /**
     * returns the status of the read buffer
     * @return string
     */
    public function getStatus(): string;
}
