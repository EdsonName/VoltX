# 🐳 Guia Docker - VoltX

## Como usar com Docker

### 1. Iniciar os containers (PHP + MySQL)
```bash
docker-compose up -d
```

Isso vai:
- ✅ Baixar imagens PHP 8.2 Apache e MySQL 8.0
- ✅ Criar os containers `volx-php` e `volx-mysql`
- ✅ Importar schema do banco automaticamente
- ✅ Rodar em background (-d)

### 2. Acessar a aplicação
```
http://localhost:8000
```

### 3. Credenciais do Banco
```
Host: volx-mysql (ou localhost:3306 do seu PC)
User: volx_user
Password: volx_password
Database: volx_db
```

### 4. Parar os containers
```bash
docker-compose down
```

### 5. Ver logs
```bash
docker-compose logs -f php
docker-compose logs -f mysql
```

### 6. Acessar container interativamente
```bash
# Shell do container PHP
docker exec -it volx-php bash

# MySQL CLI
docker exec -it volx-mysql mysql -u root -p volx_db
```

## Estrutura do docker-compose.yml

- **php:8.2-apache** - PHP + Apache2
- **mysql:8.0** - Banco de dados
- **Volumes**: 
  - `.:/var/www/html` - Todo projeto mapeado
  - `mysql_data` - Persistência de dados MySQL
  - `schema.sql` - Importado automaticamente
- **Network**: `volx-network` para comunicação entre services

## Reiniciar banco de dados

Se quiser resetar o banco:
```bash
docker-compose down -v
docker-compose up -d
```

## Troubleshooting

### Porta já em uso
Se a porta 8000 estiver em uso:
```bash
# Editar docker-compose.yml
# Mudar "8000:80" para outra porta, ex: "8001:80"
```

### MySQL não conecta
Aguarde alguns segundos enquanto o MySQL inicia:
```bash
docker-compose logs mysql
```

### Arquivo schema.sql não foi importado
Execute manualmente:
```bash
docker exec volx-mysql mysql -u root -proot volx_db < db/schema.sql
```
