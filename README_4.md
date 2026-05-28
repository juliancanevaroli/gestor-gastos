# 💰 Gestor de Gastos

Aplicação web para controle financeiro pessoal, desenvolvida em PHP com MySQL. Permite registrar entradas e saídas, categorizar transações e visualizar o saldo mensal de forma simples e rápida.

---

## 🚀 Funcionalidades

- ✅ Adicionar transações de **entrada** e **saída**
- ✅ Categorizar transações por tipo
- ✅ Filtrar por **mês**, **tipo** e **categoria**
- ✅ Visualizar **resumo financeiro** com total de entradas, saídas e saldo
- ✅ Excluir transações
- ✅ Saldo com indicação visual de positivo/negativo
- ✅ Gráfico de **gastos por categoria** (rosca)
- ✅ Gráfico de **entradas x saídas** dos últimos 6 meses

---

## 🛠️ Tecnologias

| Camada     | Tecnologia        |
|------------|-------------------|
| Back-end   | PHP 8+            |
| Banco      | MySQL             |
| Front-end  | HTML, CSS, JS     |
| Ambiente   | XAMPP / WAMP      |
| Gráficos   | Chart.js 4        |

---

## 📁 Estrutura de Pastas

```
gestor-gastos/
├── api/
│   └── graficos.php
├── css/
│   └── style.css
├── db/
│   └── conexao.php
├── includes/
│   ├── transacoes.php
│   └── graficos.php
├── js/
│   └── app.js
└── index.php
```

---

## ⚙️ Instalação e Configuração

### Pré-requisitos

- [XAMPP](https://www.apachefriends.org/) ou [WAMP](https://www.wampserver.com/) instalado
- PHP 8.0 ou superior
- MySQL 5.7 ou superior

### Passo a passo

**1. Clone ou copie o projeto para a pasta do servidor:**

```bash
# XAMPP
C:/xampp/htdocs/gestor-gastos/

# WAMP
C:/wamp64/www/gestor-gastos/
```

**2. Crie o banco de dados:**

Acesse o phpMyAdmin (`http://localhost/phpmyadmin`) e execute o SQL abaixo:

```sql
CREATE DATABASE gestor_gastos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE gestor_gastos;

CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    tipo ENUM('entrada', 'saida') NOT NULL
);

CREATE TABLE transacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(255) NOT NULL,
    valor DECIMAL(10, 2) NOT NULL,
    tipo ENUM('entrada', 'saida') NOT NULL,
    categoria_id INT DEFAULT NULL,
    data DATE NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
);

-- Categorias de exemplo
INSERT INTO categorias (nome, tipo) VALUES
('Salário', 'entrada'),
('Freelance', 'entrada'),
('Alimentação', 'saida'),
('Transporte', 'saida'),
('Moradia', 'saida'),
('Saúde', 'saida'),
('Lazer', 'saida');
```

**3. Configure a conexão com o banco:**

Edite o arquivo `db/conexao.php` com suas credenciais:

```php
<?php
function conectar() {
    $conn = new mysqli('localhost', 'root', '', 'gestor_gastos');

    if ($conn->connect_error) {
        die('Erro na conexão: ' . $conn->connect_error);
    }

    $conn->set_charset('utf8mb4');
    return $conn;
}
```

**4. Acesse no navegador:**

```
http://localhost/gestor-gastos/
```

---

## 📌 Observações

- O filtro de mês usa o formato `YYYY-MM` (ex: `2025-04`)
- Transações sem categoria são exibidas como `—` na tabela
- O saldo fica **verde** quando positivo e **vermelho** quando negativo

---

## 📄 Licença

Este projeto é de uso livre para fins pessoais e educacionais.
