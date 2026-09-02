# Projeto VoltX - Estrutura Monorepo

Projeto completo com Frontend React, Backend Java (Spring Boot) e Backend Python (FastAPI).

## 📁 Estrutura do Projeto

```
volx/
├── frontend/                    # Frontend React/Next.js
│   └── pages/                   # Páginas da aplicação
├── backend-java/                # API REST - Spring Boot
│   ├── src/
│   │   ├── main/
│   │   │   ├── java/
│   │   │   │   └── com/volx/    # Código-fonte
│   │   │   └── resources/       # application.yml, etc
│   │   └── test/
│   └── pom.xml
├── backend-python/              # Workers e serviços - FastAPI
│   ├── app/                     # Código da aplicação
│   ├── tests/                   # Testes
│   ├── main.py                  # Entrada principal
│   ├── config.py                # Configurações
│   └── requirements.txt
├── db/                          # Banco de dados
│   ├── migrations/              # Migrations SQL
│   └── schema.sql               # Schema principal
└── docs/                        # Documentação
```

## 🚀 Como Executar

### Frontend (React/Next.js)
```bash
cd frontend
npm install
npm run dev
```

### Backend Java (Spring Boot)
```bash
cd backend-java
mvn clean install
mvn spring-boot:run
```
Acessar: http://localhost:8080/api

### Backend Python (FastAPI)
```bash
cd backend-python
python -m venv venv
source venv/bin/activate  # Windows: venv\Scripts\activate
pip install -r requirements.txt
python main.py
```
Acessar: http://localhost:8001
Docs: http://localhost:8001/docs

## 🗄️ Banco de Dados

1. Criar banco de dados:
```sql
CREATE DATABASE volx_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Executar schema:
```bash
mysql -u root -p volx_db < db/schema.sql
```

## 🔗 APIs

- **Java API** (Serviços, Clientes): `http://localhost:8080/api`
- **Python API** (Orçamentos, Agendamentos): `http://localhost:8001`
- **Frontend**: `http://localhost:3000`

## 📝 Tecnologias

- **Frontend**: React, Next.js, JavaScript/JSX
- **Backend Java**: Spring Boot 3.1, Spring Data JPA, MySQL
- **Backend Python**: FastAPI, SQLAlchemy, MySQL
- **Database**: MySQL 8.0+
