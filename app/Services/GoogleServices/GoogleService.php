<?php

namespace App\Services\GoogleServices;

use Google\Client;

abstract class GoogleService{

    public string $credentialsPath;
    public Client $client;

    function __construct($credentialsPath){
        $this->credentialsPath = $credentialsPath;
    }

    public function createClient() {
        $client = new Client();
        $client->setAuthConfig($this->credentialsPath);
        $client->setAccessType('offline');

        $this->client = $client;
    }
}