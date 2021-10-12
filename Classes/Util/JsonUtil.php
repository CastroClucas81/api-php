<?php

namespace Util;

use InvalidArgumentException;
use JsonException;

class JsonUtil
{
    public static function handleJsonRequestBody()
    {
        try {
            if (empty($_FILES)) {
                $postJson = json_decode(file_get_contents('php://input'), true);
            } else {
                $postJson = $_FILES['fileImport'];
            }
        } catch (JsonException $e) {
            throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERR0_JSON_VAZIO);
        }

        if (is_array($postJson) && count($postJson) > 0) {
            return $postJson;
        }
    }

    public function handleArrayToReturn($array, $token)
    {
        $data = [];
        $data[ConstantsGenericsUtil::TIPO] = ConstantsGenericsUtil::TIPO_ERRO;

        if ((is_array($array) && count($array)) > 0 || strlen($array) > 10) {
            $data[ConstantsGenericsUtil::TOKEN] = $token;
            $data[ConstantsGenericsUtil::TIPO] = ConstantsGenericsUtil::TIPO_SUCESSO;
            $data[ConstantsGenericsUtil::RESPOSTA] = $array;
        }

        $this->returnJson($data);
    }

    private function returnJson($data)
    {
        header('Content-Type: application/json');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        echo json_encode($data);
        exit;
    }
}
