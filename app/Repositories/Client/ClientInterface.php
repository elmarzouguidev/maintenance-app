<?php


namespace App\Repositories\Client;

interface ClientInterface
{


    public function getClients();

    public function getClient(int $id);

    public function getClientByExternalId(string $id);

    public function getClientById(int $id);

    public function addClient(array $data);

    public function select(array $fields);

    public function getFirst();
}
