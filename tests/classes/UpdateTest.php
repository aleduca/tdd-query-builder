<?php

use app\database\Update;
use PHPUnit\Framework\TestCase;

class UpdateTest extends TestCase
{
    public function test_update()
    {
        $update = new Update;
        $update->update('users',[
            'firstName' => 'Alexandre',
            'lastName' => 'Cardoso',
            'email' => 'email22@email.com.br',
            'password' => password_hash('123', PASSWORD_DEFAULT),
        ],['id', 10]);

        $updated = $update->getSql();

        $this->assertEquals("update users set firstName = :firstName, lastName = :lastName, email = :email, password = :password where id = :id", $updated);
    }

}
