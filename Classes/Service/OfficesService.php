<?php

namespace Service;

use InvalidArgumentException;
use Repository\OfficesRepository;
use Util\ConstantsGenericsUtil;

class OfficesService
{
    public const TABLE = 'cargos';
    public const GET_RESOURCES = ['list'];
    public const DELETE_RESOURCES = ['delete'];
    public const POST_RESOURCES = ['register'];
    public const PUT_RESOURCES = ['update'];

    private array $data;
    private array $requestDataBody = [];
    private object $OfficesRepository;

    public function __construct($data = [])
    {
        $this->data = $data;
        $this->OfficesRepository = new OfficesRepository();
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

            if ($this->OfficesRepository->insertOffice($description) > 0) {
                $enteredId = $this->OfficesRepository->getMySQL()->getDb()->lastInsertId();
                $this->OfficesRepository->getMySQL()->getDb()->commit();
                return ['entered_id' => $enteredId];
            }
            $this->OfficesRepository->getMySQL()->getDb()->rollBack();

            throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_GENERICO);
        }

        throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_SEM_DESCRICAO);
    }

    private function list()
    {
        return $this->OfficesRepository->getMySQL()->getAll(self::TABLE);
    }

    private function delete()
    {
        return $this->OfficesRepository->getMySQL()->delete(self::TABLE, $this->data['id']);
    }

    private function update()
    {
        if ($this->OfficesRepository->updateOffice($this->data['id'], $this->requestDataBody) > 0) {
            $this->OfficesRepository->getMySQL()->getDb()->commit();
            return ConstantsGenericsUtil::MSG_ATUALIZADO_SUCESSO;
        }

        $this->OfficesRepository->getMySQL()->getDb()->rollBack();
        throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_NAO_AFETADO);
    }

    public function setDataBodyRequest($requestData)
    {
        $this->requestDataBody = $requestData;
    }

    private function getOneByKey()
    {
        return $this->OfficesRepository->getMySQL()->getOneByKey(self::TABLE, $this->data['id']);
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
