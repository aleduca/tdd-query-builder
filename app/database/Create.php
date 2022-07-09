<?php
namespace app\database;

class Create {

    private string $sql;
    private array $data;

    public function create(string $table, array $data)
    {
        $this->data = $data;
        // insert into users(firstName, lastName, email, password) values(:firstName, :lastName, :email, :password)
        $this->sql = "insert into {$table}(";
        $this->sql .= implode(', ',array_keys($this->data)).') values(';
        $this->sql .= ':'.implode(', :',array_keys($this->data)).')';
    }

    public function execute()
    {
        $connection = Connection::getConnection();
        $prepare = $connection->prepare($this->sql);
        return $prepare->execute($this->data);
    }

    public function getSql()
    {
       return $this->sql; 
    }

}