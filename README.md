# 🌐 GestIC – Sistema de Gestão Integrada de Projetos

O **GestIC** é um sistema web desenvolvido para auxiliar no gerenciamento de projetos de extensão do IC.
Com um painel centralizado, permissões por nível de acesso e módulos organizados, o sistema oferece visão completa sobre a dinâmica dos projetos, seus responsáveis, recursos utilizados e impactos.

---

## 📌 Sumário

- [🎯 Sobre o Sistema](#sobre-o-sistema)
- [👥 Níveis de Usuário](#níveis-de-usuário)
- [🚀 Funcionalidades](#funcionalidades)
  - [Dashboard](#dashboard)
  - [Projetos](#projetos)
  - [Colaboradores](#colaboradores)
  - [Parceiros](#parceiros)
  - [Usuários](#usuários)
- [⚙️ Como Instalar](#como-instalar)
- [🛠️ Tecnologias Utilizadas](#tecnologias-utilizadas)

---

# 🎯 Sobre o Sistema

O **GestIC** foi desenvolvido para automatizar e centralizar a gestão de:

- Projetos de extensão  
- Colaboradores e suas atribuições  
- Parceiros organizacionais  
- Usuários internos (Admin, Coordenadores e Estudantes)

O sistema organiza e exibe informações essenciais, incluindo relatórios, métricas, filtros avançados, mapas de calor e visualizações gráficas para facilitar tomada de decisões.

---

# 👥 Níveis de Usuário

O GestIC possui **três papéis** com permissões distintas:

| Papel         | Acesso |
|---------------|--------|
| **Admin**     | Acesso total ao sistema (incluindo CRUD de usuários e visualização completa) |
| **Coordinator** | Pode acessar e gerenciar projetos, colaboradores e parceiros |
| **Student**   | Pode visualizar projetos e acessar o painel geral |

---

# 🚀 Funcionalidades

## 🧭 Dashboard

📌 **Acessível por todos os usuários**

O dashboard inicial apresenta um resumo geral do sistema:

- Total de projetos cadastrados  
- Projetos por categoria  
- Orçamento total por categoria  
- Colaboradores ativos  
- Coordenadores cadastrados  
- Distribuição de recursos  
- Gráficos interativos (categorias, orçamento, etc.)

---

## 📁 Projetos

📌 **Acessível por Admin, Coordenadores e Estudantes**

A página de Projetos lista:

- Nome do projeto  
- Descrição  
- Coordenador  
- Categoria  
- Orçamento  
- Colaboradores vinculados (nome, e-mail e função)

### 🔎 Filtros e Busca

- Busca por nome  
- Busca por descrição  
- Filtro por **coordenador**  
- Filtro por **categoria**

A listagem possui paginação, ordenação e visualização detalhada.

---

## 👤 Colaboradores

📌 **Acessível por Admin e Coordenadores**

Exibe todos os colaboradores cadastrados, incluindo:

- Informações pessoais  
- Projetos em que participa  
- Bolsa total recebida  
- Função no projeto  

### 🔎 Filtros

- Busca por nome  
- Busca por e-mail  
- Ordenação por bolsa, nome, função, etc.  
- Filtro por projeto ou categoria

---

## 🌍 Parceiros

📌 **Acessível por Admin e Coordenadores**

Página com **mapa de calor (heatmap)** exibindo:

- Quantidade de colaboradores por região (país)  
- Distribuição geográfica dos parceiros  

Permite análise rápida da atuação geográfica.

---

## 🧑‍💻 Usuários

📌 **Acessível apenas pelo Admin**

Listagem completa contendo:

- Nome  
- E-mail  
- CPF  
- Papel (Admin, Coordinator, Student)  
- Status (ativo/inativo)

### ✏️ Ações

- Ver detalhes  
- Editar  
- Excluir  
- Ativar/Inativar usuário

---

# ⚙️ Como Instalar

> ⚠️ Antes de começar, instale:  
> - **PHP 8.1+**  
> - **Composer**  
> - **Node.js + NPM**  
> - **MySQL**  
> - **Git**

---

## 1. Clonar o Repositório

```
git clone https://github.com/SEDUC-TI/perfil-seduc.git
```

## 2. **Configure o arquivo `.env`**
Vá para a pasta do projeto, faça uma cópia do arquivo `.env.example`, renomeie o arquivo para `.env` e faça as devidas modificações para configurar o servidor.
Você pode usar o seguinte comando para fazer uma cópia do arquivo `.env.example`:

```
cd perfil-seduc
cp .env.example .env]
**Observação:** Caso não tenha o **[Git](https://git-scm.com/)**, instale-o.
```
Em seguida, abra o arquivo `.env` com um editor de texto e faça as configurações necessárias, como definir variáveis de ambiente, configurar credenciais de banco de dados, etc.

## 3. Instale as Dependências
Instale as dependências do projeto com o composer e node
```
composer install
npm install
```
> **Observação:** Caso não tenha o **[Composer](https://getcomposer.org/)** e/ou [Node](https://nodejs.org/pt), instale-os.

## 4. Rode as Migrations
Rode as migrations do projeto com as seeders
```
php artisan migrate --seed
```
## 5. Inicie o Servidor
Inicio o servidor laravel e node

#### Desenvolvimento

```
php artisan serve
npm run dev
```
> **Observação:** Por padrão, o servidor é executado na URL **[localhost](http://127.0.0.1:8000)**.

#### Produção
Seguir as instruções do servidor usado para o sistema.
> **Observação:** É recomendado o uso do **[apache](https://httpd.apache.org/)** ou **[nginx](https://nginx.org/en/)**.

# :computer: Tecnologias Utilizadas

### Back-end
- **[Laravel](https://laravel.com/)**
- **[PHP](https://www.php.net/)**
- **[MySQL](https://www.mysql.com/)**

### Front-end
- **[Vue](https://vuejs.org/)**
- **[Inertia](https://inertiajs.com/)**
- **[MDBootstrap](https://mdbootstrap.com/)**
