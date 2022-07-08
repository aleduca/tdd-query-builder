<?php
namespace app\database;

class Select
{
    private array $query = [];

    // private ?string $sql;
    // private ?string $order;
    // private ?string $limit;
    // private array $where = [];
    // private array $join = [];
    // private array $binds = [];

    public function query(string $query)
    {
        $this->query = [];

        if (!isset($this->query['sql'])) {
            $this->query['sql'] = null;
        }

        $this->query['sql'] = $query;

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

    public function order(string $order)
    {
        if (!isset($this->query['order'])) {
            $this->query['order'] = null;
        }

        $this->query['order'] = " {$order}";
        
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


    public function dump()
    {
        $this->query['sql'].= (!empty($this->query['join'])) ? rtrim(implode('', $this->query['join'])) : '';
        $this->query['sql'].= (!empty($this->query['where'])) ? rtrim(' where '.implode('', $this->query['where'])) : '';
        $this->query['sql'].= $this->query['order'] ?? '';
        $this->query['sql'].= $this->query['limit'] ?? '';
    }

    public function get()
    {
        $this->dump();

        $connection = Connection::getConnection();

        $prepare = $connection->prepare($this->query['sql']);
        $prepare->execute($this->query['binds'] ?? []);
        return $prepare->fetchAll();
    }


    public function first()
    {
        $this->dump();

        $connection = Connection::getConnection();

        $prepare = $connection->prepare($this->query['sql']);
        $prepare->execute($this->query['binds'] ?? []);
        return $prepare->fetchObject();
    }

    public function test()
    {
        $this->dump();

        return (object)[
            'sql' => $this->query['sql'],
            'binds' => $this->query['binds'] ?? []
        ];
    }
}
