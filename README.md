# 🛒 Sistema de Gestão de Produtos, Cupons e Pedidos

Projeto desenvolvido como parte de um **teste técnico** para demonstrar habilidades com **Laravel, PHP, Bootstrap e testes automatizados**.

O sistema simula um painel administrativo onde é possível **gerenciar produtos, cupons de desconto e pedidos**, com atualização de estoque e aplicação de regras de negócios.

---

## ✅ Funcionalidades

### **1. Produtos**
- Cadastro, listagem, edição e exclusão de produtos.  
- Suporte a **variações (tamanho, cor, modelo)** com controle de estoque automático.  
- Atualização de estoque baseada na soma das variações.

### **2. Cupons**
- Criação e listagem de cupons com:
  - **Validade configurável**;
  - **Valor mínimo de compra** para uso do cupom;
  - Validação automática antes de aplicar no carrinho.

### **3. Pedidos**
- Cadastro de pedidos com:
  - **Resumo de produtos e quantidades**;
  - **Aplicação de cupom de desconto (quando válido)**;
  - **Cálculo de frete baseado no subtotal**;
  - **Status do pedido** (Pendente, Pago, Cancelado ou Cupom Vencido).

### **4. Carrinho de Compras**
- Adição e remoção de produtos do carrinho;
- Aplicação de cupons em tempo real (AJAX);
- Cálculo automático do total + desconto + frete.

### **5. Painel Administrativo**
- **Menu lateral fixo** para navegar entre Cupons, Produtos e Pedidos;
- Totalmente responsivo, utilizando **Bootstrap 5**.

### **6. Testes Automatizados**
- Cobertura de testes com **PHPUnit** para:
  - CRUD de Produtos;
  - CRUD de Cupons;
  - Fluxo completo de Pedidos (inclusive atualização de status);
  - Aplicação de regras de negócio (estoque, cupons e frete).

---

## 🛠 Tecnologias Utilizadas
- **Backend:** Laravel 10 (PHP 8+)
- **Frontend:** Blade + Bootstrap 5 + jQuery
- **Banco de Dados:** MySQL
- **Testes:** PHPUnit
- **API Externa:** ViaCEP (consulta automática de endereço pelo CEP)

---

## 🚀 Como Rodar o Projeto

### **1. Clonar o Repositório**
```bash
git clone https://github.com/JuanMiguelElric/testemontink.git
cd testemontink
```

### **2. Instalar Dependências**
```bash
composer install
cp .env.example .env
php artisan key:generate
```

### **3. Configurar Banco de Dados**
Edite o arquivo `.env`:
```
DB_DATABASE=testemontink
DB_USERNAME=root
DB_PASSWORD=
```

### **4. Rodar Migrações e Seeders**
```bash
php artisan migrate --seed
```

### **5. Subir o Servidor**
```bash
php artisan serve
```
Acesse em: [http://localhost:8000](http://localhost:8000)

---

## ✅ Como Rodar os Testes
Execute todos os testes com:
```bash
php artisan test
```

Resultados esperados: ✅ **100% de testes passando**.

---

## ✨ Diferenciais Implementados
- Estrutura de código limpa seguindo boas práticas do Laravel;
- Testes de integração com cenários reais (CRUD + regras de negócio);
- Painel administrativo moderno e intuitivo;
- Uso de API externa (ViaCEP) para preenchimento automático de endereço.

---

## 👨‍💻 Autor
Desenvolvido por **Juan Miguel de Oliveira** como parte do **Teste Técnico Montink**.
