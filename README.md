# fila-digital-v2

# Sistema de Fila - Clínica Vida+

Projeto simples de gerenciamento de fila para clínica, feito com PHP, MySQL, HTML, CSS e JavaScript.

## Funcionalidades

- Gerar senha para pacientes
- Painel de atendimento para administrador
- Chamar próximo paciente
- Resetar fila
- Histórico de atendimentos
- Página de acompanhamento para pacientes
- Modo TV para exibir a senha atual
- Login admin com banco de dados

## Estrutura do projeto

- `index.php` → página inicial do paciente
- `acompanhar.php` → acompanhamento da fila
- `tv.php` → modo TV
- `pegar_senha.php` → gera senha
- `dados.php` → retorna dados da fila
- `db.php` → conexão com banco
- `style.css` → estilos do sistema

### Pasta `admin/`
- `login.php`
- `painel.php`
- `chamar.php`
- `resetar.php`
- `logout.php`

## Banco de dados

Crie a tabela principal:

```sql
CREATE TABLE fila (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    senha VARCHAR(10) NOT NULL,
    status VARCHAR(20) NOT NULL
);

Crie a tabela de administradores:

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);
Tecnologias usadas
PHP
MySQL
HTML
CSS
JavaScript
Objetivo

Esse projeto foi feito para praticar desenvolvimento web com PHP e banco de dados, simulando um sistema de fila de atendimento para clínica.

Autor

Hugo de Lima Santos
