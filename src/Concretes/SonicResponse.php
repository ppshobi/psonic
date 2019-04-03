<?php

namespace Psonic\Concretes;

use Psonic\Contracts\Response as ResponseInterface;

class SonicResponse implements ResponseInterface 
{
    private $message;
    private $pieces;
    private $results;
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

        $this->pieces['status'] = $this->pieces[0];
        unset($this->pieces[0]);

        if($this->pieces['status'] === 'RESULT') {
            $this->pieces['count'] = (int) $this->pieces[1];
            unset($this->pieces[1]);
        }

        if($this->pieces['status'] === 'EVENT') {
            $this->pieces['query_key'] = $this->pieces[2];
            $this->results = array_slice($this->pieces, 2, count($this->pieces)-4);
        }
    }

    public function getResults()
    {
        return $this->results;
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
        return $this->get('status');
    }

    public function getCount()
    {
        return $this->get('count') ?? 0;
    }
}
 