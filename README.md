# TaskManager - Sistema de Gerenciamento de Tarefas

Sistema de gerenciamento de tarefas com multitenancy, autenticação JWT e envio de emails.

## 🛠 Tecnologias Utilizadas

### Backend

- **Laravel 8**
- **JWT Auth**
- **PostgreSQL**
- **Sistema de envio de emails**

### Frontend

- **Vue.js 2**
- **Vuex**
- **Vue Router**
- **Axios**

## 📋 Funcionalidades

### ✅ Autenticação

- Registro e login com JWT
- Middleware de autenticação
- Isolamento por empresa

### ✅ Multitenancy

- Cada usuário pertence a uma empresa
- Dados isolados por empresa
- Não há interferência entre empresas

### ✅ Gerenciamento de Tarefas

- CRUD completo de tarefas
- Filtros por status e prioridade
- Campos: título, descrição, status, prioridade, data limite

### ✅ Notificações por Email

- Email ao criar tarefa
- Email ao concluir tarefa
- Templates HTML personalizados

## 🚀 Como executar o projeto

### Backend (Laravel)

```bash
# Clonar o repositório
git clone [url-do-repositorio]

## 🔧 Configuração

#criar arquivo .env
cp .env.example .env

#Executar os comandos
composer install
php artisan key:generate
php artisan jwt:secret

### Editar variáveis de Ambiente (.env)

# Configurações do banco
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=taskmanager
DB_USERNAME=admin
DB_PASSWORD=123456

# Configurações JWT
JWT_SECRET=sua_chave_jwt #Gerada com php artisan jwt:secret

# Configurações de email
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=noreply@taskmanager.com

```

## 🐳 Docker

```bash
#Para executar com Docker:

#Construir e iniciar containers

docker compose up -d

# Executar as migrations

docker compose exec backend php artisan migrate
docker compose exec backend php artisan db:seed

```

## 📧 Emails

O sistema utiliza o **[MailHog](https://github.com/mailhog/MailHog)** para capturar os emails enviados em ambiente de desenvolvimento.
Isso permite testar notificações sem enviar emails reais.

- Acesse a interface do MailHog em: [http://localhost:8025](http://localhost:8025)
- Todos os emails enviados pela aplicação aparecerão lá automaticamente.

## ⏳ Filas

O envio de emails é assíncrono e utiliza o **driver Database** do Laravel.
Os jobs ficam armazenados na tabela `jobs` até serem processados pelo worker.

### 🔹 Executar o worker manualmente (Opcional)

```bash
docker compose exec backend php artisan queue:work

```

## 🖥️ Acesso a aplicação

A aplicação estará disponível em:

http://localhost:8080

Usuários de teste

Ao subir os containers, três usuários de teste são automaticamente criados no sistema:

| Usuário                                             | Senha  |
| --------------------------------------------------- | ------ |
| [jonh@empresa1.com](mailto:jonh@empresa1.com)       | 123456 |
| [jane@empresa2.com](mailto:jane@empresa2.com)       | 123456 |
| [richard@empresa3.com](mailto:richard@empresa3.com) | 123456 |

---

## 📚 API Endpoints

### Autenticação

- `POST /api/login` - Fazer login

### Tarefas

- `GET /api/tasks` - Listar tarefas
- `POST /api/tasks` - Criar tarefa
- `GET /api/tasks/{id}` - Visualizar tarefa
- `PUT /api/tasks/{id}` - Atualizar tarefa
- `DELETE /api/tasks/{id}` - Excluir tarefa

## 👨‍💻 Desenvolvedor

Desenvolvido por Alexandre Valim
