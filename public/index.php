<?php
require '../vendor/autoload.php';

use app\database\Select;

$select = new Select;
$users = $select->query('select * from users')
->where('users.id', '>', 200)
->join('inner join comments on comments.user_id = users.id')
->order('order by users.id desc')->get();

var_dump($users);
