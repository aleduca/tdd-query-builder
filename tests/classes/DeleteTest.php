<?php

use app\database\Delete;
use PHPUnit\Framework\TestCase;


class DeleteTest extends TestCase
{
    public function test_delete()
    {
        $delete = new Delete;
        $delete->delete('users',['id', 10]);
        $deleted = $delete->getSql();

        $this->assertEquals("delete from users where id = :id", $deleted);
    }

}
