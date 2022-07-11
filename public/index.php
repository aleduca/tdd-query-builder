<?php
require '../vendor/autoload.php';

use app\database\Delete;
use app\database\Select;
use app\database\Update;

$select = new Select;
$users = $select->fields('users.id,firstName,lastName')
->table('users')
->where('users.id', '>', 120)
->order('users.id','desc')->get();


var_dump($users);

// $update = new Update;
// $updated = $update->update('users',[
//     'firstName' => 'Alexandre',
//     'lastName' => 'Cardoso',
//     'email' => 'email22@email.com.br',
//     'password' => password_hash('123', PASSWORD_DEFAULT),
// ],['id', 10]);

// var_dump($updated);


// $delete = new Delete;
// $deleted = $delete->delete('users',['id', 201])->execute();
// var_dump($deleted);