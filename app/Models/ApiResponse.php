<?php

namespace App\Models;

/**
 * @property string $message
 * @property int $code
 * @property mixed $data
 * @property bool $ok
 */
class ApiResponse
{
    public function __construct(string $message = 'Success', int $code = 200, mixed $data = null)
    {
        $this->message = $message;
        $this->code = $code;
        $this->data = $data;
        $this->ok = $code >= 200 && $code < 300;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
}
