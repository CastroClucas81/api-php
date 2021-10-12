<?php

namespace Service;

use InvalidArgumentException;
use Repository\CostsCentersRepository;
use Util\ConstantsGenericsUtil;

class CostsCentersService
{
    public const TABLE = 'centros_de_custos';
    public const GET_RESOURCES = ['list'];
    public const DELETE_RESOURCES = ['delete'];
    public const POST_RESOURCES = ['register'];
    public const PUT_RESOURCES = ['update'];

    private array $data;
    private array $requestDataBody = [];
    private object $CostsCentersRepository;

    public function __construct($data = [])
    {
        $this->data = $data;
        $this->CostsCentersRepository = new CostsCentersRepository();
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
        $description = $this->requestDataBody['descricao'];

        if ($description) {

            if ($this->CostsCentersRepository->insertCostCenter($description) > 0) {
                $enteredId = $this->CostsCentersRepository->getMySQL()->getDb()->lastInsertId();
                $this->CostsCentersRepository->getMySQL()->getDb()->commit();
                return ['entered_id' => $enteredId];
            }
            $this->CostsCentersRepository->getMySQL()->getDb()->rollBack();

            throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_GENERICO);
        }

        throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_SEM_DESCRICAO);
    }

    private function list()
    {
        return $this->CostsCentersRepository->getMySQL()->getAll(self::TABLE);
    }

    private function delete()
    {
        return $this->CostsCentersRepository->getMySQL()->delete(self::TABLE, $this->data['id']);
    }

    private function update()
    {
        if ($this->CostsCentersRepository->updateCostCenter($this->data['id'], $this->requestDataBody) > 0) {
            $this->CostsCentersRepository->getMySQL()->getDb()->commit();
            return ConstantsGenericsUtil::MSG_ATUALIZADO_SUCESSO;
        }

        $this->CostsCentersRepository->getMySQL()->getDb()->rollBack();
        throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_NAO_AFETADO);
    }

    public function setDataBodyRequest($requestData)
    {
        $this->requestDataBody = $requestData;
    }

    private function getOneByKey()
    {
        return $this->CostsCentersRepository->getMySQL()->getOneByKey(self::TABLE, $this->data['id']);
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
