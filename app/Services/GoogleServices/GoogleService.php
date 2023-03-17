<?php

namespace App\Services\GoogleServices;

use Google\Client;

abstract class GoogleService{

    public Client $client;

    /**
     * Create client authenticated of service account via json file, and set to $this->client
     * @return void
     */
    public function createClient() {
        $client = new Client();
        $client->useApplicationDefaultCredentials();

        $this->client = $client;
    }
}