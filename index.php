<?php

use Util\ConstantsGenericsUtil;
use Util\JsonUtil;
use Util\RoutesUtil;
use Validator\RequestValidator;

include "env.php";

try {
    $requestValidator = new RequestValidator(RoutesUtil::getRoutes());
    $return = $requestValidator->processRequest();

    $JsonUtil = new JsonUtil();
    $JsonUtil->handleArrayToReturn($return, $requestValidator->getToken());
} catch (Exception $e) {
    echo json_encode([
        ConstantsGenericsUtil::TIPO => ConstantsGenericsUtil::TIPO_ERRO,
        ConstantsGenericsUtil::RESPOSTA => utf8_encode($e->getMessage())
    ], JSON_THROW_ON_ERROR);
    exit;
}
