Documento de Estrutura do Projeto


1. Pasta public/

Pasta pública do projeto, serve como document root do servidor web. Contém arquivos acessíveis diretamente pelo navegador.

Arquivos principais:

index.php – Rota inicial do sistema. Redireciona o usuário para a página de login caso não esteja autenticado.

login.php – Página de login do sistema. Permite que usuários façam autenticação.

logout.php – Encerra a sessão do usuário, realizando logout.

dashboard.php – Página principal após login. Exibe o painel de controle do gestor, com resumo de informações (ex.: pedidos, vendas, promoções).

Subpastas e arquivos:
1.1 produtos/

Gerencia produtos cadastrados no sistema.

list.php – Lista todos os produtos cadastrados.

add.php – Formulário para adicionar novos produtos.

edit.php – Formulário para editar produtos existentes.

delete.php – Script para excluir produtos do banco de dados.

1.2 categorias/

Gerencia categorias de produtos.

list.php – Lista todas as categorias.

add.php – Formulário para adicionar novas categorias.

edit.php – Formulário para editar categorias existentes.

delete.php – Script para remover categorias do banco de dados.

1.3 pedidos/

Gerencia os pedidos realizados pelos clientes.

list.php – Lista todos os pedidos realizados.

view.php – Exibe detalhes de um pedido específico.

update_status.php – Permite atualizar o status de um pedido (ex.: pendente, enviado, entregue).

1.4 promocoes/

Gerencia promoções de produtos.

list.php – Lista promoções existentes.

add.php – Formulário para adicionar nova promoção.

edit.php – Formulário para editar promoções existentes.

1.5 relatorios/

vendas.php – Gera relatórios de vendas do sistema, podendo incluir gráficos e filtros por período.

1.6 assets/

Armazena recursos estáticos do site, como CSS, JavaScript e imagens.

css/styles.css – Contém todo o CSS do projeto, responsável por estilizar o site.

js/main.js – Contém scripts JavaScript do sistema, ex.: interações do dashboard, validações e requisições assíncronas (AJAX).

images/ – Pasta para imagens utilizadas no site (ícones, banners, etc.).

1.7 api/

Endpoints usados para comunicação assíncrona (AJAX) ou integração externa.

pedidos_count.php – Retorna a quantidade de pedidos pendentes ou em andamento, utilizado pelo painel em tempo real (polling).

... – Outros endpoints podem ser adicionados conforme necessidade.

2. Pasta src/

Contém arquivos de configuração e funções auxiliares do sistema.

config.php – Arquivo de configuração do sistema, incluindo dados de conexão com banco de dados, variáveis globais e parâmetros gerais.

db.php – Wrapper de conexão PDO com o banco de dados. Facilita consultas SQL seguras (prepared statements).

helpers.php – Funções utilitárias utilizadas em todo o sistema, ex.: validação de dados, manipulação de strings, redirecionamentos.

3. Pasta sql/

database.sql – Script completo do banco de dados. Contém criação de tabelas, relacionamentos, inserts iniciais e configurações necessárias para o sistema funcionar.

4. Pasta uploads/

Armazena imagens de produtos enviadas pelo administrador.

Permissões recomendadas: 755 para pastas e 644 para arquivos, garantindo que o servidor web possa gravar arquivos, mas mantendo segurança.

5. Arquivo README.md

Arquivo de documentação do projeto. Serve para explicar o propósito do projeto, instruções de instalação, dependências e qualquer observação importante para desenvolvedores ou usuários.