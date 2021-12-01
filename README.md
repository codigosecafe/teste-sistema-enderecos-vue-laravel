
# Setup Sistema de Endereços
### Passo a passo
Clone Repositório
```sh
git clone https://github.com/codigosecafe/teste-sistema-enderecos-vue-laravel.git
```


Crie o Arquivo .env
```sh
cd teste-sistema-enderecos-vue-laravel/
cp .env.example .env
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

Acessar o projeto
[http://localhost:8989](http://localhost:8989)


---
Eu gosto depois de rodar o commando anteriores rodar o comando
```sh
docker-compose down
```

E iniciar tudo novamente com o comando abaixo
```sh
docker-compose up --build
```
