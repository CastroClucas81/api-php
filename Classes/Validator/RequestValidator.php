<?php

namespace Validator;

use InvalidArgumentException;
use Repository\AuthorizedTokensRepository;
use Service\CostsCentersService;
use Service\DepartamentsService;
use Service\LoginService;
use Service\OfficesService;
use Service\UsersService;
use Util\ConstantsGenericsUtil;
use Util\JsonUtil;

class RequestValidator
{
    const GET = 'GET';
    const DELETE = 'DELETE';
    const USERS = 'USUARIOS';
    const OFFICES = 'CARGOS';
    const COSTS_CENTERS = 'CENTROS_DE_CUSTOS';
    const DEPARTAMENTS = 'DEPARTAMENTOS';
    const LOGIN = 'LOGIN';

    private $request;
    private array $requestData = [];
    private object $AuthorizedTokensRepository;
    private $token;

    public function __construct($request)
    {
        $this->request = $request;
        $this->AuthorizedTokensRepository = new AuthorizedTokensRepository();
    }

    public function processRequest()
    {
        $return = utf8_encode(ConstantsGenericsUtil::MSG_ERRO_TIPO_ROTA);

        if (in_array($this->request['method'], ConstantsGenericsUtil::TIPO_REQUEST, true)) {
            $return = $this->directRequest();
        }

        return $return;
    }

    private function directRequest()
    {
        if ($this->request['method'] !== self::GET && $this->request['method'] !== self::DELETE) {
            $this->requestData = JsonUtil::handleJsonRequestBody();
        }

        if ($this->request['route'] !== self::LOGIN) {
            $this->token = $this->AuthorizedTokensRepository->validateToken(getallheaders()['Authorization']);
        }

        $method = $this->request['method'];
        return $this->$method();
    }

    private function get()
    {
        $return = utf8_encode(ConstantsGenericsUtil::MSG_ERRO_TIPO_ROTA);

        if (in_array($this->request['route'], ConstantsGenericsUtil::TIPO_GET, strict)) {
            switch ($this->request['route']) {
                case self::USERS:
                    $UsersService = new UsersService($this->request);
                    $return = $UsersService->validateGet();
                    break;
                case self::OFFICES:
                    $OfficesService = new OfficesService($this->request);
                    $return = $OfficesService->validateGet();
                    break;
                case self::COSTS_CENTERS:
                    $CostsCentersService = new CostsCentersService($this->request);
                    $return = $CostsCentersService->validateGet();
                    break;
                case self::DEPARTAMENTS:
                    $DepartamentsService = new DepartamentsService($this->request);
                    $return = $DepartamentsService->validateGet();
                    break;
                default:
                    throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }

        return $return;
    }

    private function delete()
    {
        $return = utf8_encode(ConstantsGenericsUtil::MSG_ERRO_TIPO_ROTA);

        if (in_array($this->request['route'], ConstantsGenericsUtil::TIPO_DELETE, strict)) {
            switch ($this->request['route']) {
                    //usuarios/"listar"
                case self::USERS:
                    $UsersService = new UsersService($this->request);
                    $return = $UsersService->validateDelete();
                    break;
                case self::OFFICES:
                    $OfficesService = new OfficesService($this->request);
                    $return = $OfficesService->validateDelete();
                    break;
                case self::COSTS_CENTERS:
                    $CostsCentersService = new CostsCentersService($this->request);
                    $return = $CostsCentersService->validateDelete();
                    break;
                case self::DEPARTAMENTS:
                    $DepartamentsService = new DepartamentsService($this->request);
                    $return = $DepartamentsService->validateDelete();
                    break;
                default:
                    throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }

        return $return;
    }

    private function post()
    {
        $return = utf8_encode(ConstantsGenericsUtil::MSG_ERRO_TIPO_ROTA);

        if (in_array($this->request['route'], ConstantsGenericsUtil::TIPO_POST, strict)) {
            switch ($this->request['route']) {
                case self::USERS:
                    $UsersService = new UsersService($this->request);
                    $UsersService->setDataBodyRequest($this->requestData);
                    $return = $UsersService->validatePost();
                    break;
                case self::OFFICES:
                    $OfficesService = new OfficesService($this->request);
                    $OfficesService->setDataBodyRequest($this->requestData);
                    $return = $OfficesService->validatePost();
                    break;
                case self::COSTS_CENTERS:
                    $CostsCentersService = new CostsCentersService($this->request);
                    $CostsCentersService->setDataBodyRequest($this->requestData);
                    $return = $CostsCentersService->validatePost();
                    break;
                case self::DEPARTAMENTS:
                    $DepartamentsService = new DepartamentsService($this->request);
                    $DepartamentsService->setDataBodyRequest($this->requestData);
                    $return = $DepartamentsService->validatePost();
                    break;
                case self::LOGIN:
                    $LoginService = new LoginService($this->request);
                    $LoginService->setDataBodyRequest($this->requestData);
                    $user = $LoginService->validateLogin();
                    $return = $this->AuthorizedTokensRepository->generateToken($user['id']);
                    break;
                default:
                    throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }

        return $return;
    }

    private function put()
    {
        $return = utf8_encode(ConstantsGenericsUtil::MSG_ERRO_TIPO_ROTA);

        if (in_array($this->request['route'], ConstantsGenericsUtil::TIPO_PUT, strict)) {
            switch ($this->request['route']) {
                    //usuarios/"listar"
                case self::USERS:
                    $UsersService = new UsersService($this->request);
                    $UsersService->setDataBodyRequest($this->requestData);
                    $return = $UsersService->validatePut();
                    break;
                case self::OFFICES:
                    $OfficesService = new OfficesService($this->request);
                    $OfficesService->setDataBodyRequest($this->requestData);
                    $return = $OfficesService->validatePut();
                    break;
                case self::COSTS_CENTERS:
                    $CostsCentersService = new CostsCentersService($this->request);
                    $CostsCentersService->setDataBodyRequest($this->requestData);
                    $return = $CostsCentersService->validatePut();
                    break;
                case self::DEPARTAMENTS:
                    $DepartamentsService = new DepartamentsService($this->request);
                    $DepartamentsService->setDataBodyRequest($this->requestData);
                    $return = $DepartamentsService->validatePut();
                    break;
                default:
                    throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }

        return $return;
    }

    public function getToken()
    {
        return $this->token;
    }
}
