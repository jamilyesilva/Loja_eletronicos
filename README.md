🛒 EletroSys
Sistema de Loja Virtual de Eletrônicos

O EletroSys é um sistema web de loja virtual desenvolvido com PHP e MySQL, que simula o funcionamento completo de um e-commerce.
O projeto foi criado com foco em aprendizado prático, organização de código, segurança e lógica de negócio, contemplando diferentes tipos de usuários e fluxos reais de uma aplicação web.

🎯 Objetivo do Projeto
Desenvolver uma aplicação funcional que permita:
Venda de produtos eletrônicos
Controle de usuários
Gerenciamento de pedidos
Controle de estoque
Abertura de chamados (Reclame Aqui)
Área administrativa protegida
O projeto busca demonstrar conhecimentos em back-end com PHP, integração com banco de dados e boas práticas de desenvolvimento.

👥 Tipos de Usuários
👤 Usuário (Cliente)
Usuários comuns têm acesso às funcionalidades de compra e suporte.
Funcionalidades:
Login e logout
Visualização de produtos
Adição de produtos ao carrinho
Controle de quantidade e validação de estoque
Finalização de pedidos
Geração de número único do pedido
Abertura de chamados vinculados a pedidos realizados
Visualização de mensagens de sucesso e erro

🛠️ Administrador
Usuários administradores têm acesso exclusivo ao painel administrativo.
Funcionalidades:
Dashboard com informações gerais do sistema
Visualização da quantidade total de:
Usuários
Produtos
Pedidos
Chamados
Visualização dos últimos pedidos realizados
Visualização dos chamados abertos pelos usuários
Acesso restrito protegido por sessão e validação de tipo de usuário

🔐 Segurança e Controle de Acesso
Autenticação baseada em sessões ($_SESSION)
Validação do tipo de usuário (type_user)
Arquivo de proteção (protect_adm.php) que:
Impede acesso direto à área administrativa
Redireciona usuários não autorizados
Uso de PDO com prepared statements
Prevenção contra SQL Injection
Validação de requisições HTTP
Uso de transações no banco de dados para garantir integridade dos pedidos

🛒 Carrinho de Compras
Carrinho armazenado em sessão
Cada produto possui controle de quantidade
Validação automática de estoque antes de adicionar ou finalizar o pedido
Atualização do estoque após a compra
Sistema de alertas para informar ações ao usuário
Redirecionamentos limpos, sem acúmulo de parâmetros na URL

📦 Pedidos
Criação de pedidos vinculados ao usuário
Registro de data e número único do pedido (num_pedido)
Associação dos itens do pedido
Finalização do pedido apenas se houver estoque disponível
Cancelamento automático do processo em caso de erro

📣 Chamados (Reclame Aqui)
Usuário pode abrir chamado apenas para pedidos próprios
Chamados são vinculados ao número do pedido
Registro da descrição do problema e data de abertura
Visualização dos chamados recentes na área administrativa

🗂️ Organização do Projeto
O sistema está dividido de forma organizada em:
Public: páginas acessíveis ao usuário
Actions: scripts responsáveis por ações (carrinho, pedido, chamado)
Includes: conexão, navbar e arquivos de proteção
Assets: imagens e arquivos estáticos
Essa separação facilita a manutenção e leitura do código.

🧰 Tecnologias Utilizadas
PHP
MySQL
PDO
HTML5
CSS3
Bootstrap 5
JavaScript (básico)
Sessões PHP
