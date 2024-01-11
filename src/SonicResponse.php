<?php

namespace Psonic;

use Psonic\Contracts\Response as ResponseInterface;
use Psonic\Exceptions\CommandFailedException;

class SonicResponse implements ResponseInterface
{
    private string $message;
    /** @var array<mixed> */
    private array $pieces;
    /** @var array<mixed> */
    private array $results;

    public function __construct(string $message)
    {
        $this->message = $message;
        $this->parse();
    }

    /**
     * parses the read buffer into a readable object
     * @throws CommandFailedException
     */
    private function parse(): void
    {
        $this->pieces = explode(" ", $this->message);

        if(preg_match_all("/buffer\((\d+)\)/", $this->message, $matches)) {
            $this->pieces['bufferSize'] = $matches[1][0];
        }

        $this->pieces['status'] = $this->pieces[0];
        unset($this->pieces[0]);

        if($this->pieces['status'] === 'ERR') {
            throw new CommandFailedException($this->message);
        }

        if($this->pieces['status'] === 'RESULT') {
            $this->pieces['count'] = (int) $this->pieces[1];
            unset($this->pieces[1]);
        }

        if($this->pieces['status'] === 'EVENT') {
            $this->pieces['query_key'] = $this->pieces[2];
            $this->results = array_slice($this->pieces, 2, count($this->pieces)-4);
        }
    }

    /**
     * @return array<mixed>
     */
    public function getResults(): array
    {
        return $this->results;
    }

    public function __toString():string
    {
        return implode(" ", $this->pieces);
    }

    /**
     * @return mixed
     */
    public function get(string $key)
    {
        if(isset($this->pieces[$key])){
            return $this->pieces[$key];
        }
    }

    public function getStatus(): string
    {
        /** @var string $status */
        $status = $this->get('status');

        return $status;
    }

    public function getCount():int
    {
        /** @var string $count */
        $count = $this->get('count');
        return $count ? (int)$count : 0;
    }
}
