<?php

namespace Psonic\Concretes;

use Psonic\Contracts\Response as ResponseInterface;

class SonicResponse implements ResponseInterface 
{
    private $message;
    private $pieces;
    public function __construct($message)
    {
        $this->message = (string) $message;
        $this->parse();
    }

    private function parse()
    {
        $this->pieces = explode(" ", $this->message);

        if(preg_match_all("/buffer\((\d+)\)/", $this->message, $matches)) {
            $this->pieces['bufferSize'] = $matches[1][0];
        }

        $this->pieces[0] = ['status' => $this->pieces[0]];
    }

    public function __toString()
    {
        return implode(" ", $this->pieces);
    }

    public function get($key)
    {
        if(isset($this->pieces[$key])){
            return $this->pieces[$key];
        }
    }

    public function getStatus()
    {
        $this->get('status');
    }
}
 