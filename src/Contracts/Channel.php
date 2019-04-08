<?php

namespace Psonic\Contracts;


use Psonic\Exceptions\ConnectionException;

interface Channel
{
    /**
     * @return mixed
     * @throws ConnectionException
     */
    public function connect();

    /**
     * @return Response
     */
    public function disconnect(): Response;
}