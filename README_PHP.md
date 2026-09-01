# VolX - Site de Serviços de Eletricidade

Projeto completo desenvolvido em **PHP puro** com **MySQL**, seguindo estrutura profissional e modularizada.

## 📁 Estrutura do Projeto

```
volx/
├── assets/
│   ├── css/
│   │   ├── style.css           # Estilos gerais
│   │   ├── admin.css           # Estilos do painel admin
│   │   └── dashboard.css       # Estilos do dashboard
│   ├── js/
│   │   ├── main.js             # JavaScript principal
│   │   └── calendario.js       # Calendário para agendamentos
│   └── img/
│       ├── logo.png
│       └── servicos/
├── config/
│   └── database.php            # Conexão com MySQL
├── includes/
│   ├── header.php              # Cabeçalho do site
│   ├── footer.php              # Rodapé do site
│   ├── auth.php                # Sistema de autenticação
│   └── functions.php           # Funções utilitárias
├── admin/                      # Área administrativa
│   ├── index.php               # Painel admin
│   ├── servicos.php            # Gerenciar serviços
│   ├── agendamentos.php        # Gerenciar agendamentos
│   ├── orcamentos.php          # Gerenciar orçamentos
│   ├── postagens.php           # Gerenciar posts
│   └── clientes.php            # Gerenciar clientes
├── dashboard/                  # Área do cliente
│   ├── index.php               # Dashboard principal
│   ├── agendamentos.php        # Meus agendamentos
│   ├── orcamentos.php          # Meus orçamentos
│   └── perfil.php              # Meu perfil
├── blog/                       # Blog
│   ├── index.php               # Lista de posts
│   └── post.php                # Artigo completo
├── db/
│   └── schema.sql              # Schema do banco de dados
├── index.php                   # Página inicial
├── login.php                   # Login
├── cadastro.php                # Cadastro
├── logout.php                  # Logout
├── sobre.php                   # Sobre
├── servicos.php                # Lista de serviços
├── servico-detalhes.php        # Detalhes do serviço
├── agendar.php                 # Agendar serviço
├── orcamento.php               # Solicitar orçamento
├── contato.php                 # Contato
└── README.md
```

## 🚀 Como Usar

### 1. Pré-requisitos
- PHP 7.4+
- MySQL 5.7+
- Apache (ou outro servidor web compatível)

### 2. Instalação

#### Banco de Dados
```bash
# 1. Criar banco de dados
mysql -u root -p

CREATE DATABASE volx_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# 2. Importar schema
mysql -u root -p volx_db < db/schema.sql
```

#### Configuração
1. Editar `config/database.php` com suas credenciais:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'seu_usuario');
define('DB_PASS', 'sua_senha');
define('DB_NAME', 'volx_db');
```

### 3. Execução
```bash
# Iniciar o servidor web (na raiz do projeto)
php -S localhost:8000

# Acessar no navegador
http://localhost:8000
```

## 📋 Funcionalidades

### Públicas
- ✅ Página inicial (Home)
- ✅ Lista de serviços
- ✅ Detalhes de serviço
- ✅ Blog
- ✅ Página sobre
- ✅ Contato
- ✅ Cadastro de usuário
- ✅ Login

### Cliente (Autenticado)
- ✅ Dashboard
- ✅ Agendar serviços
- ✅ Visualizar agendamentos
- ✅ Solicitar orçamentos
- ✅ Visualizar orçamentos
- ✅ Editar perfil

### Administrador
- ✅ Painel de controle
- ✅ Gerenciar serviços (CRUD)
- ✅ Gerenciar agendamentos
- ✅ Gerenciar orçamentos
- ✅ Gerenciar posts do blog
- ✅ Gerenciar clientes

## 🔐 Segurança

- Senhas com hash BCRYPT
- Proteção contra SQL Injection
- Sanitização de inputs
- Session-based authentication
- CSRF protection pronto para implementar

## 🎨 Frontend

- Design responsivo
- CSS modular
- JavaScript vanilla
- Compatível com navegadores modernos

## 🗄️ Banco de Dados

**Tabelas principais:**
- `usuarios` - Clientes e administradores
- `servicos` - Serviços disponíveis
- `agendamentos` - Agendamentos de serviços
- `orcamentos` - Orçamentos solicitados
- `posts_blog` - Posts do blog

## 📝 Padrões de Código

### Convenções
- Nomes de arquivos em minúsculas com hífen
- Funções em snake_case
- Variáveis em snake_case
- Indentação: 4 espaços

### Exemplo de Uso de Functions
```php
// Incluir as dependências
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

// Verificar autenticação
verificarAutenticacao();

// Sanitizar entrada
$nome = sanitizar($_POST['nome']);

// Validar email
if (!validar_email($email)) {
    mensagem_erro('Email inválido');
}

// Buscar dados
$servicos = pegar_servicos();
```

## 🔄 Fluxo de Autenticação

```
1. Usuário acessa /login.php ou /cadastro.php
2. Dados são validados e armazenados no MySQL
3. Sessão é criada com session_start()
4. Verificado se é admin ou cliente
5. Redireciona para /admin/ ou /dashboard/
6. Cada página verifica autenticação com verificarAutenticacao()
7. Logout limpa a sessão
```

## 🚀 Próximos Passos

- [ ] Implementar CSRF tokens
- [ ] Adicionar mais validações
- [ ] Implementar paginação
- [ ] Adicionar sistema de notificações
- [ ] Implementar integração com APIs (Java/Python)
- [ ] Adicionar testes

## 📞 Suporte

Para suporte, abra uma issue ou entre em contato através da página de contato.

## 📄 Licença

Este projeto está sob licença MIT.
