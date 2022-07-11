<?php
namespace app\database;

class Select
{
    private array $query = [];


    public function query(string $query)
    {
        // $this->query = [];

        if (!isset($this->query['sql'])) {
            $this->query['sql'] = null;
        }

        $this->query['sql'] = $query;

        return $this;
    }

    public function fields(string $fields = '*')
    {
        if (!isset($this->query['fields'])) {
            $this->query['fields'] = null;
        }

        $this->query['fields'] = "{$fields}";

        return $this; 
    }

    public function table(string $table)
    {
        if (!isset($this->query['table'])) {
            $this->query['table'] = null;
        }

        $this->query['table'] = "{$table}";

        return $this;  
    }

    public function where(string $field, string $operator, mixed $value, ?string $type = null)
    {
        if (!isset($this->query['where'])) {
            $this->query['where'] = [];
        }

        if (!isset($this->query['binds'])) {
            $this->query['binds'] = [];
        }

        $fieldPlaceholder = $field;

        if (str_contains($fieldPlaceholder, '.')) {
            $fieldPlaceholder = str_replace('.', '', $fieldPlaceholder);
        }

        $this->query['where'][] = "{$field} {$operator} :{$fieldPlaceholder} {$type} ";

        $this->query['binds'][$fieldPlaceholder] = $value;

        return $this;
    }

    public function join(string $join)
    {
        if (!isset($this->query['join'])) {
            $this->query['join'] = [];
        }

        $this->query['join'][] = " {$join}";

        return $this;
    }

    public function order(string $field, string|int $value )
    {
        if (!isset($this->query['order'])) {
            $this->query['order'] = null;
        }

        $this->query['order'] = " order by {$field} {$value}";
        
        return $this;
    }

    public function group(string $fields)
    {
        if (!isset($this->query['group'])) {
            $this->query['group'] = null;
        }

        $this->query['group'] = " group by {$fields}";
        
        return $this; 
    }
    
    public function limit(int $limit)
    {
        if (!isset($this->query['limit'])) {
            $this->query['limit'] = null;
        }

        $this->query['limit'] = " limit {$limit}";
    
        return $this;
    }

    private function getQueryExceptions()
    {
        if(isset($this->query['fields']) || isset($this->query['table'])){
            if(isset($this->query['sql'])) throw new \Exception("if you call fields and table methods you cannot call query method");
        }

        if(isset($this->query['fields']) and !isset($this->query['table'])){
            throw new \Exception("You need both, fields and table methods");
        }

        if(isset($this->query['table']) and !isset($this->query['fields'])){
            throw new \Exception("You need both, fields and table methods");
        }   
    }

    private function getCreatedQueries()
    {
        if(isset($this->query['fields'], $this->query['table'])){
            $this->query['sql'] = "select {$this->query['fields']} from {$this->query['table']}";
        }

        $this->query['sql'].= (!empty($this->query['join'])) ? rtrim(implode('', $this->query['join'])) : '';
        $this->query['sql'].= (!empty($this->query['where'])) ? rtrim(' where '.implode('', $this->query['where'])) : '';
        $this->query['sql'].= $this->query['group'] ?? '';
        $this->query['sql'].= $this->query['order'] ?? '';
        $this->query['sql'].= $this->query['limit'] ?? '';   
    }

    public function dump()
    {   
        $this->getQueryExceptions();
        $this->getCreatedQueries(); 
    }

    public function get()
    {
        $this->dump();

        $connection = Connection::getConnection();

        $prepare = $connection->prepare($this->query['sql']);
        $prepare->execute($this->query['binds'] ?? []);
        $fetchAll = $prepare->fetchAll();

        $this->query = [];

        return $fetchAll;
    }


    public function first()
    {
        $this->dump();

        $connection = Connection::getConnection();

        $prepare = $connection->prepare($this->query['sql']);
        $prepare->execute($this->query['binds'] ?? []);
        $fetchOject = $prepare->fetchObject();

        $this->query = [];

        return $fetchOject;
    }

    public function test()
    {
        $this->dump();

        $returnTest = (object)[
            'sql' => $this->query['sql'],
            'binds' => $this->query['binds'] ?? []
        ];

        $this->query = [];

        return $returnTest;
    }
}
