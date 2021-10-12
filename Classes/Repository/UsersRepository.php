<?php

namespace Repository;

use Database\MySQL;
use InvalidArgumentException;
use Util\ConstantsGenericsUtil;

class UsersRepository
{
    private object $MySQL;

    public const TABLE = 'usuarios';

    public function __construct()
    {
        $this->MySQL = new MySQL();
    }

    public function getMySQL()
    {
        return $this->MySQL;
    }

    public function insertUser($office_idfk, $departament_idfk, $login, $password)
    {
        $query = 'INSERT INTO ' . self::TABLE . ' (cargo_idfk, departamento_idfk, login, password) VALUES (:office_idfk, :departament_idfk, :login, :password)';
        $this->MySQL->getDb()->beginTransaction();
        $stmt = $this->MySQL->getDb()->prepare($query);
        $stmt->bindParam(':office_idfk', $office_idfk);
        $stmt->bindParam(':departament_idfk', $departament_idfk);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function updateUser($id, $data)
    {
        $query = 'UPDATE ' . self::TABLE . ' SET cargo_idfk = :office_idfk, departamento_idfk = :departament_idfk, login = :login, password = :password WHERE id = :id';
        $this->MySQL->getDb()->beginTransaction();
        $stmt = $this->MySQL->getDb()->prepare($query);
        $stmt->bindParam(':office_idfk', $data['cargo_idfk']);
        $stmt->bindParam(':departament_idfk', $data['departamento_idfk']);
        $stmt->bindParam(':login', $data['login']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function getLogin($data)
    {
        if ($data) {
            $query = 'SELECT id FROM ' . self::TABLE . ' WHERE login = :login AND password = :password';
            $stmt = $this->MySQL->getDb()->prepare($query);
            $stmt->bindParam(':login', $data['login']);
            $stmt->bindParam(':password', $data['password']);
            $stmt->execute();
            $totalRecords = $stmt->rowCount();

            if ($totalRecords === 1) {
                return $stmt->fetch($this->MySQL->getDb()::FETCH_ASSOC);
            }

            throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_SEM_RETORNO);
        }

        throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_LOGIN_SENHA_OBRIGATORIO);
    }

    public function userForDepartament()
    {
        $query = 'SELECT u.*, d.descricao AS descricao_departamento FROM ' . self::TABLE . ' u INNER JOIN departamentos d ON d.id = u.departamento_idfk';
        $stmt = $this->MySQL->getDb()->prepare($query);
        $stmt->execute();
        $totalRecords = $stmt->rowCount();

        if ($totalRecords > 0) {
            return $stmt->fetchAll($this->MySQL->getDb()::FETCH_ASSOC);
        }

        throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_SEM_RETORNO);
    }

    public function importUsers($fileImport)
    {
        $delimiter = ',';
        $fence = '"';

        //$file = fopen("D:\base1.csv", "r");
        $file = fopen($fileImport['tmp_name'], "r");

        if ($file) {
            $this->MySQL->getDb()->beginTransaction();

            while (!feof($file)) {
                $line = fgetcsv($file, 0, $delimiter, $fence);

                $query = 'INSERT INTO ' . self::TABLE . ' (cargo_idfk, departamento_idfk, login, password) VALUES (?, ?, ?, ?);';

                $stmt = $this->MySQL->getDb()->prepare($query);

                $stmt->bindParam(1, $line[0], \PDO::PARAM_INT);
                $stmt->bindParam(2, $line[1], \PDO::PARAM_INT);
                $stmt->bindParam(3, $line[2], \PDO::PARAM_STR);
                $stmt->bindParam(4, $line[3], \PDO::PARAM_STR);
                $stmt->execute();
            }

            fclose($file);

            if ($stmt->rowCount() > 0) {
                $this->MySQL->getDb()->commit();
                return ConstantsGenericsUtil::MSG_IMPORTADO_SUCESSO;
            }
        } else {
            throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_SEM_ARQUIVO);
        }

        throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_GENERICO);
    }
}
