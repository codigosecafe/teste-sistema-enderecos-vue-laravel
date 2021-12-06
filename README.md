# Setup Sistema de Endereços
---
<img src="https://github.com/codigosecafe/teste-sistema-enderecos-vue-laravel/blob/main/docs/images/imagem-001.png?raw=true"  />

---
#### Lista de conteúdo:
* [Instalação](#installation)
* [Usando a API do sistema](#app_api)
    * [Postman](#app_api-postman)
    * [Buscar endereços com base no CEP](#app_api-busca-endereco)
    * [Listando endereços](#app_api-lista-endereco)
        * [Filtrando endereços](#app_api-lista-endereco-filtros) 
    * [Cadastrar endereços com base no CEP](#app_api-cadastrar-endereco)
    * [Atualizar endereços com base no CEP](#app_api-atualizar-endereco)
    * [Deletar endereços com base no CEP](#app_api-deletar-endereco)
* [Problemas?](#issue)
---
## Instalação (Passo a passo)<span id="installation"></span>
É nescessário tem o docker corretamente configurado em seu ambiente
caso não tenha existe varios videos no youtube ensinando a instalar e configurar o docker

### Passo a passo
Clone Repositório
```sh
git clone https://github.com/codigosecafe/teste-sistema-enderecos-vue-laravel.git
```

Crie o Arquivo .env
```sh
cd teste-sistema-enderecos-vue-laravel/
cp .env-docker .env
```

Suba os containers do projeto
```sh
docker-compose up -d
```

Acessar o container
```sh
docker-compose exec sistema_enderecos bash
```

Instalar as dependências do projeto
```sh
composer install
```

Gerar a key do projeto Laravel
```sh
php artisan key:generate
```

Gerar a tabela do banco de dados e alimentar com alguns dados iniciais
```sh
php artisan migrate --seed
```

Se tudo estiver certo basta acessar o projeto com o seguinte link
[http://localhost:8989](http://localhost:8989)

--
## Usando a aplicação no modo API <span id="app_api"></span>

- ### Postman <span id="app_api-postman"></span>
Adicionei uma pasta chamada POSTMAN, você pode importar os arquivos e testar todos os end-points disponível para API.

<img src="https://github.com/codigosecafe/teste-sistema-enderecos-vue-laravel/blob/main/docs/images/image-002.png?raw=true"  />

* [Listando endereços](#app_api-lista-endereco)
- ### Buscar endereços com base no CEP <span id="app_api-busca-endereco"></span>
Para buscar os detalhes de um endereço com base em seu CEP basta informá-lo na URL da requisição. Primeiramente o nosso sistema busca em nosso cache de consultas caso não encontre, realiza uma busca em nossa base não tendo sucesso ele buscará externamente no serviço do ViaCEP.

```shell
curl --location --request GET 'http://127.0.0.1:8989/api/busca-cep/83704325'
```
Resposta da requisição
```json
{
    "data": {
        "id": 2,
        "cep": "83.704-325",
        "logradouro": "RUA MARIA EDITH DE FRANÇA TRAUCZYNSKI",
        "complemento": "",
        "bairro": "BOQUEIRÃO",
        "cidade": "ARAUCÁRIA",
        "estado": "PR",
        "ibge": "4101804",
        "ddd": "41",
        "google_map": "https://www.google.com.br/maps/place/RUA+MARIA+EDITH+DE+FRANÇA+TRAUCZYNSKI+-+BOQUEIRÃO,+ARAUCÁRIA+-+PR",
        "cadastrado_em": {
            "timestamp": "2021-12-02 21:04:35",
            "dia_formatado": "02/12/2021",
            "hora_formatado": "21:04:35"
        },
        "atualizado_em": {
            "timestamp": "2021-12-06 12:44:48",
            "dia_formatado": "06/12/2021",
            "hora_formatado": "12:44:48"
        }
    },
    "success": true,
    "info_app": {
        "date_time": "2021-06-12 12:18:04",
        "app_name": "Teste Sistema de Endereços"
    }
}
```
- ### Listando endereços <span id="app_api-lista-endereco"></span>
Para listar os endereços cadastrados na base dados basta execultar a seguinte requisição
```shell
curl --location --request GET 'http://127.0.0.1:8989/api/busca-cep'
```
Resposta da requisição
```json
{
    "data": [
        {
            "id": 2,
            "cep": "83.704-325",
            "logradouro": "RUA MARIA EDITH DE FRANÇA TRAUCZYNSKI",
            "complemento": "",
            "bairro": "BOQUEIRÃO",
            "cidade": "ARAUCÁRIA",
            "estado": "PR",
            "ibge": "4101804",
            "ddd": "41",
            "google_map": "https://www.google.com.br/maps/place/RUA+MARIA+EDITH+DE+FRANÇA+TRAUCZYNSKI+-+BOQUEIRÃO,+ARAUCÁRIA+-+PR",
            "cadastrado_em": {
                "timestamp": "2021-12-02 21:04:35",
                "dia_formatado": "02/12/2021",
                "hora_formatado": "21:04:35"
            }
        },
        {
            "id": 1,
            "cep": "81.260-360",
            "logradouro": "RUA TELÊMACO DA SILVA QUADROS",
            "complemento": "",
            "bairro": "CIDADE INDUSTRIAL",
            "cidade": "CURITIBA",
            "estado": "PR",
            "ibge": "4106902",
            "ddd": "41",
            "google_map": "https://www.google.com.br/maps/place/RUA+TELÊMACO+DA+SILVA+QUADROS+-+CIDADE+INDUSTRIAL,+CURITIBA+-+PR",
            "cadastrado_em": {
                "timestamp": "2021-12-02 19:49:00",
                "dia_formatado": "02/12/2021",
                "hora_formatado": "19:49:00"
            }
        }
    ],
    "success": true,
    "info_app": {
        "date_time": "2021-06-12 11:00:52",
        "app_name": "Teste Sistema de Endereços"
    }
}
```
- ### Filtrando os endereços <span id="app_api-lista-endereco-filtros"></span>
É possível filtrar os endereços passando parâmetros na requisição da listagem, segue abaixo os parâmetros aceitos
  * searchTerm: Filtra os resultados por qualquer termo informado, ele realiza fuzzy search do logradouro em nossa base de dados
  * sort: Ordena os resultados, os valores aceitos são id,zip_code,street,complement,neighborhood,city,state,ibge,ddd
  * limit: Limita a quantidade de resultados a ser retornados, caso seja informado ele criara uma paginação dos mesmos
  * order: Define qual será a ordem que será apresentado os resultados podendo ser crescente e decrescente com base no valor informado no sort
    
#### Segue um exemplo de requisição

Como usamos a técnia de "fuzzy search" basta colocar no searchTerm o seguinte valor "me" que ele retornará o seguinte logradouro "RUA MARIA EDITH DE FRANÇA TRAUCZYNSKI"

Segue o script de requisição

```shell
curl --location --request GET 'http://127.0.0.1:8989/api/busca-cep?searchTerm=me&sort=neighborhood&limit=15&order=asc'
```
Resposta da requisição
```json
{
    "data": [
        {
            "id": 2,
            "cep": "83.704-325",
            "logradouro": "RUA MARIA EDITH DE FRANÇA TRAUCZYNSKI",
            "complemento": "",
            "bairro": "BOQUEIRÃO",
            "cidade": "ARAUCÁRIA",
            "estado": "PR",
            "ibge": "4101804",
            "ddd": "41",
            "google_map": "https://www.google.com.br/maps/place/RUA+MARIA+EDITH+DE+FRANÇA+TRAUCZYNSKI+-+BOQUEIRÃO,+ARAUCÁRIA+-+PR",
            "cadastrado_em": {
                "timestamp": "2021-12-02 21:04:35",
                "dia_formatado": "02/12/2021",
                "hora_formatado": "21:04:35"
            },
            "atualizado_em": {
                "timestamp": "2021-12-02 21:04:35",
                "dia_formatado": "02/12/2021",
                "hora_formatado": "21:04:35"
            }
        }
    ],
    "success": true,
    "info_app": {
        "date_time": "2021-06-12 12:36:44",
        "app_name": "Teste Sistema de Endereços"
    },
    "links": {
        "first_page_url": "http://127.0.0.1:8989/api/busca-cep?page=1",
        "last_page_url": "http://127.0.0.1:8989/api/busca-cep?page=1",
        "next_page_url": null,
        "prev_page_url": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "path": "http://127.0.0.1:8989/api/busca-cep",
        "per_page": "15",
        "to": 2,
        "total": 2
    }
}
```

- ### Cadastrar um novo endereço com base no CEP <span id="app_api-cadastrar-endereco"></span>
Para cadastrar um novo endereço basta informar o CEP, assim o nosso sistema busca externamente no ViaCEP o detalhe do endereço, assim evitamos o cadastro de informações falsas.

Payload para cadastro:
```json
{
    "zip_code": "81590050"
}
```
Requisição para cadastro
```shell
curl --location --request POST 'http://127.0.0.1:8989/api/busca-cep' \
--header 'Content-Type: application/json' \
--data-raw '{
    "zip_code": "81590050"
}'
```
Resposta da requisição
```json
{
    "data": {
         "id": 5,
        "cep": "81.590-050",
        "logradouro": "RUA BENJAMIN GELINSKI",
        "complemento": "",
        "bairro": "UBERABA",
        "cidade": "CURITIBA",
        "estado": "PR",
        "ibge": "4106902",
        "ddd": "41",
        "google_map": "https://www.google.com.br/maps/place/RUA+BENJAMIN+GELINSKI+-+UBERABA,+CURITIBA+-+PR",
        "cadastrado_em": {
            "timestamp": "2021-12-06 12:54:15",
            "dia_formatado": "06/12/2021",
            "hora_formatado": "12:54:15"
        },
        "atualizado_em": {
            "timestamp": "2021-12-06 12:54:15",
            "dia_formatado": "06/12/2021",
            "hora_formatado": "12:54:15"
        }
    },
    "success": true,
    "info_app": {
        "date_time": "2021-06-12 12:54:15",
        "app_name": "Teste Sistema de Endereços"
    }
}
```

- ### Atualizar um endereço com base no CEP <span id="app_api-atualizar-endereco"></span>
Para atualizar um endereço basta informar o CEP na URL e realizar uma requisição do tipo PUT, assim o nosso sistema busca externamente no ViaCEP o detalhe do endereço, assim evitamos o cadastro de informações falsas. A atualização só ocorre caso haja divirgencia entre os dados salvos em nossa base com os dados disponiveis no ViaCEP.

Requisição para cadastro
```shell
curl --location --request PUT 'http://127.0.0.1:8989/api/busca-cep/83704325'
```
Resposta da requisição
```json
{
    "data": {
        "id": 2,
        "cep": "83.704-325",
        "logradouro": "RUA MARIA EDITH DE FRANÇA TRAUCZYNSKI",
        "complemento": "",
        "bairro": "BOQUEIRÃO",
        "cidade": "ARAUCÁRIA",
        "estado": "PR",
        "ibge": "4101804",
        "ddd": "41",
        "google_map": "https://www.google.com.br/maps/place/RUA+MARIA+EDITH+DE+FRANÇA+TRAUCZYNSKI+-+BOQUEIRÃO,+ARAUCÁRIA+-+PR",
        "cadastrado_em": {
            "timestamp": "2021-12-02 21:04:35",
            "dia_formatado": "02/12/2021",
            "hora_formatado": "21:04:35"
        },
        "atualizado_em": {
            "timestamp": "2021-12-06 12:44:48",
            "dia_formatado": "06/12/2021",
            "hora_formatado": "12:44:48"
        }
    },
    "success": true,
    "info_app": {
        "date_time": "2021-06-12 12:44:48",
        "app_name": "Teste Sistema de Endereços"
    }
}
```


- ### Deletar um endereço com base no CEP <span id="app_api-deletar-endereco"></span>
Para deletar um endereço basta informar o CEP na URL e realizar uma requisição do tipo DELETE.

Requisição para cadastro
```shell
curl --location --request DELETE 'http://127.0.0.1:8989/api/busca-cep/83704325'
```
Resposta da requisição
```json
{
    "status": "success",
    "message": "Endereço deletado com sucesso!"
}
```

--
## Problemas? <span id="issue"></span>

Sinta-se à vontade para abrir uma `issue` no repositório para qualquer problema ou solicitação de recurso. Para obter detalhes de como enviar sua solicitação, verifique o arquivo [CONTRIBUTING][contributing].

Se entretanto for algo que requer nossa atenção iminente, sinta-se à vontade para nos contatar [codigosecafe+github@gmail.com](codigosecafe+github@gmail.com).

[contributing]:CONTRIBUTING.md
