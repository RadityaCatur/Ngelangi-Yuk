<?php

namespace App\Services;

use App\Client;

class QuotaService
{
    public function increase($clientId)
    {
        if ($clientId) {
            Client::where('id', $clientId)->increment('kuota');
        }
    }

    public function decrease($clientId)
    {
        if ($clientId) {
            Client::where('id', $clientId)->decrement('kuota');
        }
    }

    public function syncClient($old, $new)
    {
        if ($old != $new) {
            if ($old) $this->increase($old);
            if ($new) $this->decrease($new);
        }
    }
}
