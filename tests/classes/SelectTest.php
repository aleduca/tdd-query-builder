<?php

use app\database\Select;
use PHPUnit\Framework\TestCase;

class SelectTest extends TestCase
{
    private Select $select;

    protected function setUp(): void
    {
        parent::setUp();
    
        $this->select = new Select;
    }
    

    public function test_get_simple_select()
    {
        $query = $this->select->query("select * from users")->get();

        $this->assertEquals("select * from users", $query->sql);
    }

    public function test_get_select_with_conditional()
    {
        $query = $this->select->query("select * from users")->where("id", ">", 10)->get();

        $this->assertEquals("select * from users where id > :id", $query->sql);
    }

    public function test_get_select_with_more_than_one_conditional()
    {
        $query = $this->select->query("select * from users")
        ->where("id", ">", 10, "and")
        ->where('firstName', "=", "Alexandre")
        ->get();

        $this->assertEquals("select * from users where id > :id and firstName = :firstName", $query->sql);
    }


    public function test_get_select_with_more_than_one_conditional_and_use_type_conditional()
    {
        $query = $this->select->query("select * from users")
        ->where("id", ">", 10, 'or')
        ->where('firstName', "=", "Alexandre")
        ->get();

        $this->assertEquals("select * from users where id > :id or firstName = :firstName", $query->sql);
    }


    public function test_get_binds_from_conditional()
    {
        $query = $this->select->query("select * from users")
        ->where("id", ">", 10, 'or')
        ->where('firstName', "=", "Alexandre")
        ->get();

        $this->assertEquals(["id" => 10,'firstName' => 'Alexandre'], $query->binds);
    }

    public function test_get_select_with_order_by()
    {
        $query = $this->select->query("select * from users")
        ->order("order by id desc")
        ->get();

        $this->assertEquals("select * from users order by id desc", $query->sql);
    }

    public function test_get_select_with_conditional_and_orderby_with_differente_order()
    {
        $query = $this->select->query("select * from users")
        ->order("order by id desc")
        ->where('id', '>', 10)
        ->get();

        $this->assertEquals("select * from users where id > :id order by id desc", $query->sql);
    }
}
