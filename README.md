# Projeto Laravel + VueJS + Vite com Docker

Este é um projeto Laravel + VueJS + Vite configurado para rodar em um ambiente Docker. Este guia irá ajudá-lo a configurar e iniciar o projeto rapidamente.

## Requisitos

-   Docker
-   Docker Compose

## Passos para Instalação

1. Clone este repositório para o seu ambiente local:
    ```bash
    git clone https://github.com/alvarolimadev/desafio-tecnico
    cd desafio-tecnico
    ```

2. Copie o arquivo de ambiente de exemplo para criar o seu próprio arquivo .env do backend:
    ```bash
    cd backend
    cp .env.example .env
    ```

3. Inicie os contêineres Docker usando o Docker Compose:
    ```bash
    docker compose up -d --build
    ```

4. Instale as dependências do Composer dentro do contêiner do backend:
    ```bash
    docker exec -it backend composer install
    ```

5. Execute as migrações do banco de dados com seed:
    ```bash
    docker exec -it backend php artisan migrate --seed
    ```
6. Fazer build do frontend no Nginx:
    ```bash
    cd frontend
    npm install
    sudo rm -rf dist/
    npm run build
    ```

## Acessando a Aplicação

Após iniciar os contêineres, você pode acessar a aplicação no seu navegador através do seguinte endereço:

```
http://localhost:8090
```
