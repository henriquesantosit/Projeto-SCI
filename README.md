
Projeto-SCI
Este projeto configura um ambiente de desenvolvimento utilizando Docker para rodar um servidor web Nginx com suporte a PHP-FPM.

Estrutura do Projeto
A estrutura de pastas do projeto é a seguinte:

projeto-sci/
├── docker/
|   
│   ├── docker-compose.yml
│   ├── DockerFile
│   └── nginx/
│       └── nginx.conf
        └── db.init.sql
└── src/
    └── public/
        └── index.php
        └── index2.php
        └── db_init.sql



###Descrição das Pastas e Arquivos
docker/: Contém todos os arquivos de configuração relacionados ao Docker.

docker-compose.yml: Arquivo de configuração do Docker Compose foi usado para definir e executar multi-containers.desenvolver e executar uma aplicação PHP com servidor web Nginx e banco de dados MySQL. Ele utiliza volumes para persistência de dados e define portas para acessar os serviços através do host. 
DockerFile: Arquivo de configuração para construir a imagem Docker do PHP-FPM.
nginx/: Pasta contendo o arquivo de configuração do Nginx.
nginx.conf: Arquivo de configuração do Nginx para servir a aplicação PHP.
src/: Contém o código-fonte da aplicação.

public/: Pasta pública acessível pelo servidor web.
index.php: Arquivo PHP simples que retorna "Olá, mundo!".

####Objetivos do Projeto
Instalar o Docker em seu ambiente de desenvolvimento local.
Criar um container Docker para executar um servidor web Nginx.
Configurar o container para expor a porta 80.
Criar um container para o PHP-FPM receber as requisições do Nginx na porta 9000 para os arquivos .php do projeto.
Criar um arquivo index.php simples que retorne o texto "Olá, mundo!".
Crie um container para o MySql que recebe conexões na porta 3306 e faça o arquivo index.php
buscar algo do banco de dados, quando acessado.
Crie deploy automático para a aplicação PHP usando GitHub Actions.

######Configuração e Execução
Pré-requisitos
Docker: Certifique-se de que o Docker está instalado em sua máquina. Você pode baixá-lo e instalá-lo a partir do site oficial do Docker.
Passos para Configurar e Executar
Clone este repositório para o seu ambiente local:

git clone https://github.com/seu-usuario/projeto-sci.git
cd projeto-sci/docker

#######Construa e inicie os containers Docker utilizando o Docker Compose:

docker-compose up --build -d

########Acesse a aplicação no navegador utilizando o endereço:

http://localhost:8080

########Detalhes dos Arquivos de Configuração
docker-compose.yml
version: "3.8"

services:
  app:
    build:
      context: ./
      dockerfile: DockerFile
    container_name: servidorphp
    restart: always
    working_dir: /var/www/
    volumes:
      - ../src:/var/www/

  nginx:
    image: nginx:latest
    container_name: servidornginx
    restart: always
    ports:
      - "8080:80"
    volumes:
      - ../src:/var/www
      - ./nginx:/etc/nginx/conf.d


  mysql:
    image: mysql:latest
    container_name: servidor_mysql
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: senha
      MYSQL_DATABASE: scidb
      MYSQL_USER: usuario
      MYSQL_PASSWORD: senha
    ports:
      - "3306:3306"
    volumes:
      - db_data:/var/lib/mysql
      - ./src/db_init.sql:/docker-entrypoint-initdb.d/db_init.sql

volumes:
  db_data:

######## DockerFile

FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git   \
    curl  \
    zip \
    unzip

WORKDIR /var/www

FROM php:7.4-fpm


RUN apt-get update \
    && apt-get install -y \
       libzip-dev \
       zip \
       unzip \
       default-mysql-client \
    && docker-php-ext-install mysqli pdo_mysql zip

COPY ./ /var/www/


WORKDIR /var/www/

CMD ["php-fpm"]

########## nginx.conf
server {
    listen 80;
    index index.php;
    error_log  /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;
    error_page 404 /index.php;
    root /var/www/public;
    
    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
        gzip_static on;
    }
}

########### index.php
<?php

echo "Olá, mundo!";?>

<?php
$servername = "mysql"; 
$username = "root";
$password = "senha";2
$dbname = "scidb";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

echo "Conexão ao MySQL realizada com sucesso!";


$sql = "SELECT * FROM sua_tabela"; 
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Exibe os dados de cada linha
    echo "<br><br>Dados da tabela:<br>";
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"]. " - Nome: " . $row["nome"]. "<br>";
    }
} else {
    echo "<br><br>Nenhum resultado encontrado na tabela.";
}

$conn->close();
?>


Contribuição
Sinta-se à vontade para abrir issues e pull requests para melhorias.

Licença
Este projeto está licenciado sob a licença MIT. Veja o arquivo LICENSE para mais detalhes.

