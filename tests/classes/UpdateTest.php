<?php

use app\database\Update;
use PHPUnit\Framework\TestCase;

class UpdateTest extends TestCase
{
    private Update $update;

    protected function setUp(): void
    {
        parent::setUp();
    
        $this->update = new Update;
    }
    

    public function test_update()
    {
        $updated = $this->update->update('users', [
            'firstName' => 'Alexandre',
            'lastName' => 'Cardoso',
            'email' => 'email22@email.com.br',
            'password' => password_hash('123', PASSWORD_DEFAULT),
        ])->where('id', '=', 10)->getSql();

        $this->assertEquals("update users set firstName = :firstName, lastName = :lastName, email = :email, password = :password where id = :id", $updated);
    }

    public function test_if_where_not_exist()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('I need where do update');

        $this->update->update('users', [
            'firstName' => 'Alexandre',
            'lastName' => 'Cardoso',
            'email' => 'email22@email.com.br',
            'password' => password_hash('123', PASSWORD_DEFAULT),
        ])->getSql();
    }
}
