<?php

namespace App\Classes;

class ResponseZy
{
    public bool $status = true;
    public string $message = '';
    public bool|array|string|object $results = false;

    public function send_json():string {
        return json_encode($this);
    }
}