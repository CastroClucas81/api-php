<?php

namespace Database;

use InvalidArgumentException;
use Util\ConstantsGenericsUtil;

class MySQL
{
    private object $db;

    /**
     * MySQL constructor.
     */
    public function __construct()
    {
        $this->db = ConnectionDB::getInstance();
    }

    public function delete($table, $id)
    {
        $queryDelete = 'DELETE FROM ' . $table . ' WHERE id = :id';
        if ($table && $id) {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($queryDelete);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $this->db->commit();
                return ConstantsGenericsUtil::MSG_DELETADO_SUCESSO;
            }
            $this->db->rollBack();
            throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_SEM_RETORNO);
        }
        throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_GENERICO);
    }

    public function getAll($table)
    {
        if ($table) {
            $query = 'SELECT * FROM ' . $table;
            $stmt = $this->db->query($query);
            $registros = $stmt->fetchAll($this->db::FETCH_ASSOC);
            if (is_array($registros) && count($registros) > 0) {
                return $registros;
            }
        }
        throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_SEM_RETORNO);
    }

    public function getOneByKey($table, $id)
    {
        if ($table && $id) {
            $query = 'SELECT * FROM ' . $table . ' WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $totalRecords = $stmt->rowCount();
            if ($totalRecords === 1) {
                return $stmt->fetch($this->db::FETCH_ASSOC);
            }
            throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_SEM_RETORNO);
        }

        throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_ID_OBRIGATORIO);
    }

    public function getDb()
    {
        return $this->db;
    }
}
