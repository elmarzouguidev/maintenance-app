<?php


namespace App\Repositories\Client;

use App\Models\Client;
use App\Repositories\AppRepository;
use Illuminate\Database\Eloquent\Collection;

class ClientRepository extends AppRepository implements ClientInterface
{


    private $client;

    private $instance;

    private $options;

    public function __construct(Client $client)
    {
        $this->client = $client;

        $this->options = config('app-config');
    }

    public function __instance(): Client
    {
        if (!$this->instance) {
            $this->instance = $this->ticket;
        }

        return $this->instance;
    }


    /**
     * @return Client[]|Collection|string[]
     */
    public function getClients()
    {
        if ($this->useCache()) {
            //dd('oui');
            return $this->setCache()->remember('all_clients_cache', $this->timeToLive(), function () {

                return $this->client->all();
            });
        }
        //dd('nooo');
        return $this->client->all();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getClient(int $id)
    {
        return $this->client->find($id);
    }


    public function getClientByExternalId(string $id)
    {
        return $this->client->whereExternalId($id);
    }

    public function getClientById(int $id)
    {
        return $this->client->whereId($id);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function addClient(array $data)
    {
        return $this->client->create($data);
    }

    public function select(array $fields)
    {
        return $this->client->select($fields);
    }

    /**
     * @return mixed
     */
    public function getFirst()
    {
        return $this->client->first();
    }
}
