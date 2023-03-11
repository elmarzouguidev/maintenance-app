<?php

namespace App\Mail\Backup\Client;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientDataExportReady extends Mailable
{
    use Queueable, SerializesModels;

    private $path;

    private $client;

    /**
     * ClientDataExportReady constructor.
     */
    public function __construct($client, $path)
    {
        $this->client = $client;
        $this->path = $path;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('view.name');
    }
}
