<?php

namespace Psonic\Concretes;

use Psonic\Contracts\Response as ResponseInterface;

class SonicResponse implements ResponseInterface 
{
    private $message;

    public function __construct($message)
    {
        $this->message = (string) $message;
    }

    public function __toString()
    {
        return $this->message;
    }

    public static function fromResource()
    {
        
    }
}
 