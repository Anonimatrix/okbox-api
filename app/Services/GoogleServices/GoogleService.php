<?php

namespace App\Services\GoogleServices;

use Google\Client;

abstract class GoogleService{

    public Client $client;

    /**
     * Create client and authenticate
     */
    public function createClient() {
        $client = new Client();
        $client->useApplicationDefaultCredentials();

        $this->client = $client;
    }
}