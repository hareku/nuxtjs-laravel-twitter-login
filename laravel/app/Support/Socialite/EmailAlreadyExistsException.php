<?php

namespace App\Support\Socialite;

use Exception;

class EmailAlreadyExistsException extends Exception
{
    /**
     * Email address
     *
     * @var string
     */
    protected $email;

    /**
     * Create a new exception instance.
     *
     * @param  string  $email
     * @return void
     */
    public function __construct(string $email)
    {
        parent::__construct('This Email already exists.');

        $this->email = $email;
    }

    /**
     * Get a Email address.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
