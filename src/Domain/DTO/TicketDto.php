<?php
namespace Domain\DTO;

use http\Exception\InvalidArgumentException;

class TicketDto
{

    private $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public static function fromArray(array $data): self
    {
        if (!array_key_exists("messaggio", $data)){
            throw new \InvalidArgumentException('Messaggio inesistente');
        }

        return new self($data["messaggio"]);
    }

    public function getMessage()
    {
        return $this->message;
    }
}