# 🔐 Gerenciador de Senhas - Sistema Web

Este sistema foi desenvolvido para facilitar o **gerenciamento seguro de senhas corporativas**, associadas a diferentes empresas e usuários. Ideal para times técnicos ou empresas que precisam manter o controle centralizado de credenciais.

## ✅ Funcionalidades

- 🔐 Login com controle de acesso por usuário
- 🏢 Cadastro de empresas
- 👤 Cadastro de usuários vinculados às empresas
- 🔑 Armazenamento de senhas associadas a serviços/sistemas
- 📝 Edição e exclusão de registros
- 🔎 Tela protegida com listagem de empresas e usuários
- 🧩 Estrutura simples e segura em PHP

## 🛠 Tecnologias Utilizadas

- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: PHP (puro)
- **Armazenamento**: Arquivos JSON ou integração com banco de dados (ajustável)
- **Autenticação**: Sessões PHP

## 📦 Estrutura de Pastas

/gerenciador-senhas
├── index.php # Página de login
├── dashboard.php # Página protegida com as senhas
├── cadastrar_empresa.php
├── cadastrar_usuario.php
├── adicionar_senha.php
├── excluir_empresa.php
├── editar_senha.php
├── includes/
│ ├── funcoes.php # Funções utilitárias
│ └── proteger.php # Controle de sessão/login
├── dados/
│ ├── empresas.json
│ └── senhas.json
└── assets/
├── css/
├── js/
└── img/


## 🔒 Segurança

- Senhas armazenadas podem ser criptografadas (ex: com `password_hash()` ou criptografia simétrica).
- Acesso às páginas é protegido por login obrigatório.
- Ações críticas exigem confirmação (ex: exclusão de empresa).

## 📸 Captura de Tela

![image](https://github.com/user-attachments/assets/c1fbc033-95bf-4a13-955d-cabd1141fec0)


## 🚀 Como Rodar Localmente

1. Clone este repositório:
   ```bash
   git clone https://github.com/seu-usuario/gerenciador-senhas.git
   cd gerenciador-senhas

2. Coloque os arquivos em um servidor local com suporte a PHP (como XAMPP, WAMP ou Laragon):
   ```bash
   C:/xampp/htdocs/gerenciador-senhas

3. Inicie o Apache (ou Nginx) e acesse via navegador:
   ```bash
   http://localhost/gerenciador-senhas

4. Faça login com o usuário padrão (ajustável em funcoes.php).

✍️ Sugestões de Melhorias Futuras
Banco de dados (MySQL ou SQLite) para maior escalabilidade

Criptografia AES para armazenar senhas

Filtros e busca por empresa/usuário

Exportação das senhas (CSV ou PDF)

📄 Licença
Distribuído sob a licença MIT. Veja LICENSE para detalhes.

Desenvolvido com 💻 por André – @Andretorresb
