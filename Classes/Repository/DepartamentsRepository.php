<?php

namespace Repository;

use Database\MySQL;
use InvalidArgumentException;
use Util\ConstantsGenericsUtil;

class DepartamentsRepository
{
    private object $MySQL;

    public const TABLE = 'departamentos';

    public function __construct()
    {
        $this->MySQL = new MySQL();
    }

    public function getMySQL()
    {
        return $this->MySQL;
    }

    public function insertDepartaments($cost_center_idfk, $description)
    {
        $query = 'INSERT INTO ' . self::TABLE . ' (centro_de_custo_idfk, descricao) VALUES (:cost_center_idfk, :description)';
        $this->MySQL->getDb()->beginTransaction();
        $stmt = $this->MySQL->getDb()->prepare($query);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':cost_center_idfk', $cost_center_idfk);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function updateDepartaments($id, $data)
    {
        $query = 'UPDATE ' . self::TABLE . ' SET centro_de_custo_idfk = :centro_de_custo_idfk, descricao = :description WHERE id = :id';
        $this->MySQL->getDb()->beginTransaction();
        $stmt = $this->MySQL->getDb()->prepare($query);
        $stmt->bindParam(':description', $data['descricao']);
        $stmt->bindParam(':centro_de_custo_idfk', $data['centro_de_custo_idfk']);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function departamentForCostCenter()
    {
        $query = 'SELECT d.*, cc.descricao AS descricao_centro_custo FROM ' . self::TABLE . ' d INNER JOIN centros_de_custos cc ON cc.id = d.centro_de_custo_idfk';
        $stmt = $this->MySQL->getDb()->prepare($query);
        $stmt->execute();
        $totalRecords = $stmt->rowCount();

        if ($totalRecords > 0) {
            return $stmt->fetchAll($this->MySQL->getDb()::FETCH_ASSOC);
        }

        throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_SEM_RETORNO);
    }
}
