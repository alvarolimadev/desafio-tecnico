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

2. Inicie os contêineres Docker usando o Docker Compose:
    ```bash
    docker compose build --no-cache
    docker-compose up -d
    ```
3. Execute as migrações do banco de dados com seed:
    ```bash
    docker exec -it backend php artisan migrate --seed
    ```
4. Fazer build do frontend no Nginx:
    ```bash
    cd frontend
    npm run build
    ```

## Acessando a Aplicação

Após iniciar os contêineres, você pode acessar a aplicação no seu navegador através do seguinte endereço:

```
http://localhost:8090
```
