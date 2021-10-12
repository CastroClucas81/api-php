## BEM-VINDO
---
## Primeiramente, importe a base de dados ipdv.SQL
---
## A seguir, configure a base de dados a partir dos seguintes procedimentos:
- Renomeie o arquivo **env.example.php** para **env.php**;
- Configure as variáveis globais do **env.php** conforme os dados do seu banco.
```sh
define(HOST, '');
define(DATABASE, '');
define(USER, '');
define(PASSWORD, '');
```
---
## Por fim, utilize uma API Client (Ex.: Postman e Insomnia) para acessar as rotas da API REST.
---
## Rotas da API REST:
## Rota base:
```sh
localhost/api-php/
```
---
## Rota de autenticacao (login):

```sh
Method: POST
Header: Content-Type application/json
body: JSON

localhost/api-php/login/auth
```
```json
{ 
    "login": "admin",
	"password": "1234"
}
```
## Obs.: O resultado da consulta será um token de autenticação que durará 60 minutos. Insira ele no Bearer Token das demais rotas a seguir:
---
## Rotas referentes ao cargo:

```sh
Cadastrar cargo

Method: POST
Header: Content-Type application/json
body: JSON

localhost/api-php/cargos/register
```
```json
{ 
    "descricao": ""
}
```

```sh
Listar cargos

Method: GET
Header: Content-Type application/json

localhost/api-php/cargos/list
```
```sh
Atualizar cargo

Method: PUT
Header: Content-Type application/json
body: JSON

localhost/api-php/cargos/update/{id}
```
```json
{ 
    "descricao": ""
}
```

```sh
Deletar cargo

Method: DELETE
Header: Content-Type application/json

localhost/api-php/cargos/delete/{id}
```
---
## Rotas referentes ao centro de custo:
```sh
Cadastrar centro de custo

Method: POST
Header: Content-Type application/json

localhost/api-php/centros_de_custos/register
```
```json
{ 
    "descricao": ""
}
```
```sh
Listar centros de custos

Method: GET
Header: Content-Type application/json

localhost/api-php/centros_de_custos/list
```
```sh
Atualizar centro de custo

Method: PUT
Header: Content-Type application/json
body: JSON

localhost/api-php/centros_de_custos/update/{id}
```
```json
{ 
    "descricao": ""
}
```
```sh
Deletar centro de custo

Method: DELETE
Header: Content-Type application/json

localhost/api-php/centros_de_custos/delete/{id}
```
---
## Rotas referentes ao departamento:
```sh
Cadastrar departamento

Method: POST
Header: Content-Type application/json
body: JSON

localhost/api-php/departamentos/register
```
```json
{ 
	"centro_de_custo_idfk": id,
	"descricao": ""
}
```
```sh
Listar departamentos

Method: GET
Header: Content-Type application/json

localhost/api-php/departamentos/list
```
```sh
Listar departamentos por centro de custo

Method: GET
Header: Content-Type application/json

localhost/api-php/departamentos/listDepartamentsForCostCenter
```
```sh
Atualizar departamento

Method: PUT
Header: Content-Type application/json
body: JSON

localhost/api-php/departamentos/update/{id}
```
```json
{ 
	"centro_de_custo_idfk": id,
	"descricao": ""
}
```
```sh
Deletar departamento

Method: DELETE
Header: Content-Type application/json

localhost/api-php/departamentos/delete/{id}
```
---
## Rotas referentes ao usuário:
```sh
Cadastrar usuário

Method: POST
Header: Content-Type application/json
body: JSON

localhost/api-php/usuarios/register
```
```json
{
	"cargo_idfk": id,
	"departamento_idfk": id,
	"login": "",
	"password": ""
} 
```
```sh
Listar usuários

Method: GET
Header: Content-Type application/json

localhost/api-php/usuarios/list
```
```sh
Listar usuários por departamento

Method: GET
Header: Content-Type application/json

localhost/api-php/usuarios/listUserDepartaments
```
```sh
Atualizar usuário

Method: PUT
Header: Content-Type application/json
body: JSON

localhost/api-php/usuarios/update/{id}
```
```json
{
	"cargo_idfk": id,
	"departamento_idfk": id,
	"login": "",
	"password": ""
} 
```
```sh
Deletar usuário

Method: DELETE
Header: Content-Type application/json

localhost/api-php/usuarios/delete/{id}
```
---
## Importar usuário
```sh
Method: POST
Header: Content-Type multipart/form-data
body: Multipart Form

localhost/api-php/usuarios/listImportUsers
```
## Insira o arquivo "arquivoImportar.csv" e nomeie ele de "fileImport"



