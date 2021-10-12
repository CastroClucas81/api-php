<?php

namespace Service;

use InvalidArgumentException;
use Repository\UsersRepository;
use Util\ConstantsGenericsUtil;

class LoginService
{

    private array $data;
    private object $UsersRepository;
    public const GET_RESOURCES = ['auth'];
    public const TABLE = 'usuarios';
    private array $requestDataBody = [];

    public function __construct($data = [])
    {
        $this->data = $data;
        $this->UsersRepository = new UsersRepository();
    }

    public function validateLogin()
    {
        $return = null;
        $resource = $this->data['resource'];

        if (in_array($resource, self::GET_RESOURCES, strict)) {
            $return = $this->$resource();
        } else {
            throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        $this->validateReturnRequest($return);
        return $return;
    }

    private function auth()
    {
        return $this->UsersRepository->getLogin($this->requestDataBody);
    }

    private function validateReturnRequest($return)
    {
        if ($return == null) {
            throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_GENERICO);
        }
    }

    public function setDataBodyRequest($requestData)
    {
        $this->requestDataBody = $requestData;
    }
}
