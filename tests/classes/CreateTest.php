<?php

use app\database\Create;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    public function test_create()
    {
        $create = new Create;
        $create->create('users', [
            'firstName' => 'Alexandre',
            'lastName' => 'Cardoso',
            'email' => 'email20@email.com',
            'password' => password_hash('123', PASSWORD_DEFAULT),
        ]);

        $created = $create->getSql();

        $this->assertEquals("insert into users(firstName, lastName, email, password) values(:firstName, :lastName, :email, :password)", $created);
    }

}
