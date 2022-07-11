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
        ->order("id", "desc")
        ->test();

        $this->assertEquals("select * from users order by id desc", $query->sql);
    }

    public function test_get_select_with_conditional_and_orderby_with_differente_order()
    {
        $query = $this->select->query("select * from users")
        ->order("id", "desc")
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
        ->order('id', 'desc')
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
        ->join('inner join posts on posts.user_id = users.id')
        ->test();

        $this->assertEquals("select * from users inner join comments on comments.user_id = users.id inner join posts on posts.user_id = users.id", $query->sql);
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

    public function test_select_with_group()
    {
        $query = $this->select->query("select * from users")
        ->group('id')
        ->test();

        $this->assertEquals("select * from users group by id", $query->sql);       
    }

    public function test_select_with_group_order_and_limit()
    {
        $query = $this->select->query("select * from users")
        ->limit(10)
        ->order('id','desc')
        ->group('id')
        ->test();

        $this->assertEquals("select * from users group by id order by id desc limit 10", $query->sql);       
    }

    public function test_select_with_conditional_and_group_order_and_limit()
    {
        $query = $this->select->query("select * from users")
        ->limit(10)
        ->where('id','>', 10)
        ->order('id','desc')
        ->group('id')
        ->test();

        $this->assertEquals("select * from users where id > :id group by id order by id desc limit 10", $query->sql);       
    }

    public function test_select_with_fields_and_table_with_separated_methods()
    {
        $query = $this->select->fields("id,firstName,lastName")
        ->table('users')
        ->test();

        $this->assertEquals("select id,firstName,lastName from users", $query->sql);        
    }

    public function test_if_table_and_fields_methods_called_not_accept_call_query_method()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("if you call fields and table methods you cannot call query method");

        $this->select->fields("id,firstName,lastName")
        ->query("select * from users")
        ->table('users')
        ->test();     

    }

    public function test_if_table_called_table_but_not_fields()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("You need both, fields and table methods");

        $this->select->table('users')
        ->test();     

    }

    public function test_if_table_called_fields_but_not_table()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("You need both, fields and table methods");

        $this->select->fields('users')
        ->test();     

    }
}
