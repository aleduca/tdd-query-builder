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
        $query = $this->select->query("select * from users")->test();

        $this->assertEquals("select * from users", $query->sql);
    }

    public function test_get_select_with_conditional()
    {
        $query = $this->select->query("select * from users")->where("id", ">", 10)->test();

        $this->assertEquals("select * from users where id > :id", $query->sql);
    }

    public function test_get_select_with_more_than_one_conditional()
    {
        $query = $this->select->query("select * from users")
        ->where("id", ">", 10, "and")
        ->where('firstName', "=", "Alexandre")
        ->test();

        $this->assertEquals("select * from users where id > :id and firstName = :firstName", $query->sql);
    }


    public function test_get_select_with_more_than_one_conditional_and_use_type_conditional()
    {
        $query = $this->select->query("select * from users")
        ->where("id", ">", 10, 'or')
        ->where('firstName', "=", "Alexandre")
        ->test();

        $this->assertEquals("select * from users where id > :id or firstName = :firstName", $query->sql);
    }


    public function test_get_binds_from_conditional()
    {
        $query = $this->select->query("select * from users")
        ->where("id", ">", 10, 'or')
        ->where('firstName', "=", "Alexandre")
        ->test();

        $this->assertEquals(["id" => 10,'firstName' => 'Alexandre'], $query->binds);
    }

    public function test_get_select_with_order_by()
    {
        $query = $this->select->query("select * from users")
        ->order("order by id desc")
        ->test();

        $this->assertEquals("select * from users order by id desc", $query->sql);
    }

    public function test_get_select_with_conditional_and_orderby_with_differente_order()
    {
        $query = $this->select->query("select * from users")
        ->order("order by id desc")
        ->where('id', '>', 10)
        ->test();

        $this->assertEquals("select * from users where id > :id order by id desc", $query->sql);
    }


    public function test_select_with_limit()
    {
        $query = $this->select->query("select * from users")
        ->limit(10)
        ->test();

        $this->assertEquals("select * from users limit 10", $query->sql);
    }

    public function test_select_with_limit_and_conitional()
    {
        $query = $this->select->query("select * from users")
        ->where('id', '>', 10)
        ->limit(10)
        ->test();

        $this->assertEquals("select * from users where id > :id limit 10", $query->sql);
    }


    public function test_select_with_limit_and_multiple_conitionals()
    {
        $query = $this->select->query("select * from users")
        ->where('id', '>', 10, 'and')
        ->where('firstName', '=', 'Alexandre')
        ->limit(10)
        ->test();

        $this->assertEquals("select * from users where id > :id and firstName = :firstName limit 10", $query->sql);
    }

    public function test_select_with_limit_and_order_and_multiple_conitionals()
    {
        $query = $this->select->query("select * from users")
        ->where('id', '>', 10, 'and')
        ->where('firstName', '=', 'Alexandre')
        ->order('order by id desc')
        ->limit(10)
        ->test();

        $this->assertEquals("select * from users where id > :id and firstName = :firstName order by id desc limit 10", $query->sql);
    }

    public function test_select_with_joins()
    {
        $query = $this->select->query("select * from users")
        ->join('inner join comments on comments.user_id = users.id')
        ->test();

        $this->assertEquals("select * from users inner join comments on comments.user_id = users.id", $query->sql);
    }

    public function test_select_with_multiple_joins()
    {
        $query = $this->select->query("select * from users")
        ->join('inner join comments on comments.user_id = users.id')
        ->join('on posts.user_id = users.id')
        ->test();

        $this->assertEquals("select * from users inner join comments on comments.user_id = users.id on posts.user_id = users.id", $query->sql);
    }


    public function test_select_with_multiple_joins_and_where()
    {
        $query = $this->select->query("select * from users")
        ->join('inner join comments on comments.user_id = users.id')
        ->join('on posts.user_id = users.id')
        ->where('id', '>', 10)
        ->test();

        $this->assertEquals("select * from users inner join comments on comments.user_id = users.id on posts.user_id = users.id where id > :id", $query->sql);
    }


    public function test_select_with_multiple_joins_and_multiple_wheres()
    {
        $query = $this->select->query("select * from users")
        ->join('inner join comments on comments.user_id = users.id')
        ->join('on posts.user_id = users.id')
        ->where('id', '>', 10, 'and')
        ->where('firstName', '=', 'Alexandre')
        ->test();

        $this->assertEquals("select * from users inner join comments on comments.user_id = users.id on posts.user_id = users.id where id > :id and firstName = :firstName", $query->sql);
    }

    public function test_multiple_queries()
    {
        $query1 = $this->select->query("select * from users")
        ->where('id', '>', 10)
        ->test();

        $query2 = $this->select->query("select * from users")
        ->test();

        $this->assertEquals("select * from users where id > :id", $query1->sql);
        $this->assertEquals("select * from users", $query2->sql);
    }

    public function test_join_with_foreign_key_with_dot()
    {
        $query = $this->select->query("select * from users")
        ->join('inner join comments on comments.user_id = users.id')
        ->where('users.id', '>', 10)
        ->test();

        $this->assertEquals("select * from users inner join comments on comments.user_id = users.id where users.id > :usersid", $query->sql);
    }
}
