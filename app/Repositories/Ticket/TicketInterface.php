<?php


namespace App\Repositories\Ticket;

interface TicketInterface
{


    public function getTickets();

    public function getTicket(int $id);

    public function getTicketByExternalId(string $id);

    public function addTicket(array $data);

    public function getFirst();
}
