<?php

use Domain\DTO\TicketDto;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TicketDTOTest extends WebTestCase
{
    /** @expectedException InvalidArgumentException
     * @expectedExceptionMessage Messaggio inesistente
     */
    public function testCantCreateDtoTicket()
    {
        $data = [
        ];

        $dto = TicketDto::fromArray($data);
    }

    public function testCanCreateDtoTicket()
    {
        $message = "ciao io sono un messaggio";

        $data = [
            "messaggio" => $message
        ];

        $dto = TicketDto::fromArray($data);

        $this->assertInstanceOf(TicketDto::class, $dto);
        $this->assertEquals($message, $dto->getMessage());
    }
}