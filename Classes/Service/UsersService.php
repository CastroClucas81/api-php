<?php

namespace Service;

use InvalidArgumentException;
use Repository\UsersRepository;
use Util\ConstantsGenericsUtil;

//regras de negócio da empresa
class UsersService
{

    public const TABLE = 'usuarios';
    public const GET_RESOURCES = ['list', 'listUserDepartaments'];
    public const DELETE_RESOURCES = ['delete'];
    public const POST_RESOURCES = ['register', 'listImportUsers'];
    public const PUT_RESOURCES = ['update'];

    private array $data;
    private array $requestDataBody = [];
    private object $UsersRepository;

    public function __construct($data = [])
    {
        $this->data = $data;
        $this->UsersRepository = new UsersRepository();
    }

    public function validateGet()
    {
        $return = null;
        $resource = $this->data['resource'];
        if (in_array($resource, self::GET_RESOURCES, strict)) {
            $return = $this->data['id'] > 0 ? $this->getOneByKey() : $this->$resource();
        } else {
            throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        $this->validateReturnRequest($return);

        return $return;
    }

    public function validateDelete()
    {
        $return = null;
        $resource = $this->data['resource'];

        if (in_array($resource, self::DELETE_RESOURCES, strict)) {
            $return = $this->validateIdRequired($resource);
        } else {
            throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        $this->validateReturnRequest($return);

        return $return;
    }

    public function validatePost()
    {
        $return = null;
        $resource = $this->data['resource'];

        if (in_array($resource, self::POST_RESOURCES, strict)) {
            $return = $this->$resource();
        } else {
            throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        $this->validateReturnRequest($return);

        return $return;
    }

    public function validatePut()
    {
        $return = null;
        $resource = $this->data['resource'];

        if (in_array($resource, self::PUT_RESOURCES, strict)) {
            $return = $this->validateIdRequired($resource);
        } else {
            throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        $this->validateReturnRequest($return);

        return $return;
    }

    private function getOneByKey()
    {
        return $this->UsersRepository->getMySQL()->getOneByKey(self::TABLE, $this->data['id']);
    }

    private function list()
    {
        return $this->UsersRepository->getMySQL()->getAll(self::TABLE);
    }

    public function listUserDepartaments()
    {
        return $this->UsersRepository->userForDepartament();
    }

    public function listImportUsers()
    {
        return $this->UsersRepository->importUsers($this->requestDataBody);
    }

    private function delete()
    {
        return $this->UsersRepository->getMySQL()->delete(self::TABLE, $this->data['id']);
    }

    private function register()
    {
        [$office_idfk, $departament_idfk, $login, $password] = [$this->requestDataBody['cargo_idfk'], $this->requestDataBody['departamento_idfk'], $this->requestDataBody['login'], $this->requestDataBody['password']];

        if ($office_idfk && $departament_idfk && $login && $password) {
            if ($this->UsersRepository->insertUser($office_idfk, $departament_idfk, $login, $password) > 0) {
                $enteredId = $this->UsersRepository->getMySQL()->getDb()->lastInsertId();
                $this->UsersRepository->getMySQL()->getDb()->commit();
                return ['entered_id' => $enteredId];
            }

            $this->UsersRepository->getMySQL()->getDb()->rollBack();

            throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_GENERICO);
        }

        throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_LOGIN_SENHA_OBRIGATORIO);
    }

    private function update()
    {
        if ($this->UsersRepository->updateUser($this->data['id'], $this->requestDataBody) > 0) {
            $this->UsersRepository->getMySQL()->getDb()->commit();
            return ConstantsGenericsUtil::MSG_ATUALIZADO_SUCESSO;
        }

        $this->UsersRepository->getMySQL()->getDb()->rollBack();
        throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_NAO_AFETADO);
    }

    public function setDataBodyRequest($requestData)
    {
        $this->requestDataBody = $requestData;
    }

    private function validateReturnRequest($return)
    {
        if ($return == null) {
            throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_GENERICO);
        }
    }

    private function validateIdRequired($resource)
    {
        if ($this->data['id'] > 0) {
            $return = $this->$resource();
        } else {
            throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_ID_OBRIGATORIO);
        }

        return $return;
    }
}
