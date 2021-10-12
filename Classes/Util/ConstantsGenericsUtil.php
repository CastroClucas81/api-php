<?php

namespace Util;

abstract class ConstantsGenericsUtil
{
    /* requests */
    public const TIPO_REQUEST = ['GET', 'POST', 'DELETE', 'PUT'];
    public const TIPO_GET = ['USUARIOS', 'CARGOS', "CENTROS_DE_CUSTOS", "DEPARTAMENTOS"];
    public const TIPO_POST = ['USUARIOS', 'CARGOS', "CENTROS_DE_CUSTOS", "DEPARTAMENTOS", "LOGIN"];
    public const TIPO_DELETE = ['USUARIOS', 'CARGOS', "CENTROS_DE_CUSTOS", "DEPARTAMENTOS"];
    public const TIPO_PUT = ['USUARIOS', 'CARGOS', "CENTROS_DE_CUSTOS", "DEPARTAMENTOS"];

    /* errors */
    public const MSG_ERRO_TIPO_ROTA = 'Rota não permitida!';
    public const MSG_ERRO_RECURSO_INEXISTENTE = 'Recurso inexistente!';
    public const MSG_ERRO_GENERICO = 'Algum erro ocorreu na requisição!';
    public const MSG_ERRO_SEM_RETORNO = 'Nenhum registro encontrado!';
    public const MSG_ERRO_NAO_AFETADO = 'Nenhum registro afetado!';
    public const MSG_ERRO_TOKEN_VAZIO = 'É necessário informar um Token!';
    public const MSG_ERRO_TOKEN_NAO_AUTORIZADO = 'Token não autorizado!';
    public const MSG_ERR0_JSON_VAZIO = 'O Corpo da requisição não pode ser vazio!';
    public const MSG_ERRO_SEM_DESCRICAO = "O campo Descrição é obrigatório!";
    public const MSG_ERRO_SEM_ARQUIVO = "O arquivo é necessário!";

    /* sucess */
    public const MSG_DELETADO_SUCESSO = 'Registro deletado com Sucesso!';
    public const MSG_IMPORTADO_SUCESSO = 'Usuários importados com Sucesso!';
    public const MSG_ATUALIZADO_SUCESSO = 'Registro atualizado com Sucesso!';

    /* resource user */
    public const MSG_ERRO_ID_OBRIGATORIO = 'ID é obrigatório!';
    public const MSG_ERRO_LOGIN_SENHA_OBRIGATORIO = 'Login e Senha são obrigatórios!';

    /* resource departaments */
    public const MSG_ERRO_DEPARTAMENTO_CADASTRO = "O Centro de Custo e Descrição são obrigatórios!";

    /* return json */
    const TIPO_SUCESSO = 'sucesso';
    const TIPO_ERRO = 'erro';

    /* others */
    public const SIM = 'S';
    public const TIPO = 'tipo';
    public const TOKEN = 'token';
    public const RESPOSTA = 'resposta';
}
