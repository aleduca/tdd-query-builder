<?php

use app\database\Delete;
use PHPUnit\Framework\TestCase;

class DeleteTest extends TestCase
{
    private Delete $delete;

    protected function setUp(): void
    {
        parent::setUp();
    
        $this->delete = new Delete;
    }
    

    public function test_delete()
    {
        $deleted = $this->delete->delete('users')->where('id', '=', 10)->getSql();

        $this->assertEquals("delete from users where id = :id", $deleted);
    }

    public function test_if_where_not_exist()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('I need where do delete');

        $this->delete->delete('users')->getSql();
    }
}
