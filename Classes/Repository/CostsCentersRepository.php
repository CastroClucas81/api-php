<?php

namespace Repository;

use Database\MySQL;

class CostsCentersRepository
{
    private object $MySQL;

    public const TABLE = 'centros_de_custos';

    public function __construct()
    {
        $this->MySQL = new MySQL();
    }

    public function getMySQL()
    {
        return $this->MySQL;
    }

    public function insertCostCenter($description)
    {
        $query = 'INSERT INTO ' . self::TABLE . ' (descricao) VALUES (:description)';
        $this->MySQL->getDb()->beginTransaction();
        $stmt = $this->MySQL->getDb()->prepare($query);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function updateCostCenter($id, $data)
    {
        $query = 'UPDATE ' . self::TABLE . ' SET descricao = :description WHERE id = :id';
        $this->MySQL->getDb()->beginTransaction();
        $stmt = $this->MySQL->getDb()->prepare($query);
        $stmt->bindParam(':description', $data['descricao']);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
