
# Setup Sistema de Endereços
### Passo a passo
Clone Repositório
```sh
git clone https://github.com/codigosecafe/teste-sistema-enderecos-vue-laravel.git
```


Crie o Arquivo .env
```sh
cd teste-sistema-enderecos-vue-laravel/
cp .env-doccker .env
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

Acessar o projeto
[http://localhost:8989](http://localhost:8989)


