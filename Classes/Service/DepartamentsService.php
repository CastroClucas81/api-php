<?php

namespace Service;

use InvalidArgumentException;
use Repository\DepartamentsRepository;
use Util\ConstantsGenericsUtil;

class DepartamentsService
{
    public const TABLE = 'departamentos';
    public const GET_RESOURCES = ['list', 'listDepartamentsForCostCenter'];
    public const DELETE_RESOURCES = ['delete'];
    public const POST_RESOURCES = ['register'];
    public const PUT_RESOURCES = ['update'];

    private array $data;
    private array $requestDataBody = [];
    private object $DepartamentsRepository;

    public function __construct($data = [])
    {
        $this->data = $data;
        $this->DepartamentsRepository = new DepartamentsRepository();
    }

    public function validateGet()
    {
        $return = null;
        $resource = $this->data['resource'];

        if (in_array($resource, self::GET_RESOURCES, strict)) {
            $return = $this->data['id'] > 0 ? $this->getOneByKey() : $this->$resource(); //list
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

    private function register()
    {
        [$cost_center_idfk, $description] = [$this->requestDataBody['centro_de_custo_idfk'], $this->requestDataBody['descricao']];

        if ($cost_center_idfk && $description) {

            if ($this->DepartamentsRepository->insertDepartaments($cost_center_idfk, $description) > 0) {
                $enteredId = $this->DepartamentsRepository->getMySQL()->getDb()->lastInsertId();
                $this->DepartamentsRepository->getMySQL()->getDb()->commit();
                return ['entered_id' => $enteredId];
            }
            $this->DepartamentsRepository->getMySQL()->getDb()->rollBack();

            throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_GENERICO);
        }

        throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_DEPARTAMENTO_CADASTRO);
    }

    private function list()
    {
        return $this->DepartamentsRepository->getMySQL()->getAll(self::TABLE);
    }

    private function delete()
    {
        return $this->DepartamentsRepository->getMySQL()->delete(self::TABLE, $this->data['id']);
    }

    private function update()
    {
        if ($this->DepartamentsRepository->updateDepartaments($this->data['id'], $this->requestDataBody) > 0) {
            $this->DepartamentsRepository->getMySQL()->getDb()->commit();
            return ConstantsGenericsUtil::MSG_ATUALIZADO_SUCESSO;
        }

        $this->DepartamentsRepository->getMySQL()->getDb()->rollBack();
        throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_NAO_AFETADO);
    }

    public function setDataBodyRequest($requestData)
    {
        $this->requestDataBody = $requestData;
    }

    private function getOneByKey()
    {
        return $this->DepartamentsRepository->getMySQL()->getOneByKey(self::TABLE, $this->data['id']);
    }

    private function validateReturnRequest($return)
    {
        if ($return == null) {
            throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_GENERICO);
        }
    }

    public function listDepartamentsForCostCenter()
    {
        return $this->DepartamentsRepository->departamentForCostCenter();
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
