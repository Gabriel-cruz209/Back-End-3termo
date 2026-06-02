# Briefing visual das telas - Confeccao TB2

Este arquivo reune as informacoes do sistema para orientar a criacao das imagens/mockups das telas por outra IA. A ideia e gerar imagens de interface administrativa realista, com aparencia de sistema pronto para uso.

## Resumo do sistema

- Nome sugerido para a interface: **Confeccao TB2**
- Nome tecnico atual no painel: **Meu Sistema**
- Tipo de sistema: painel administrativo web para gestao de uma confeccao.
- Tecnologia atual: Laravel com painel Filament.
- URL principal do painel: `/admin`
- Publico: dono(a) da confeccao, equipe de vendas, equipe de estoque e administradores.
- Objetivo principal: controlar clientes, fornecedores, produtos, insumos, estoque, pedidos, usuarios, cargos e permissoes.

## Tom visual desejado

Criar uma interface de painel administrativo moderna, limpa e profissional. O visual deve parecer um sistema de gestao real, com foco em leitura rapida, organizacao e produtividade.

Direcao visual:

- Estilo SaaS administrativo, nao landing page.
- Sidebar lateral fixa com grupos de navegacao.
- Topbar discreta com busca, notificacoes e perfil.
- Conteudo principal com tabelas, cards, formularios e graficos.
- Layout denso, organizado e facil de escanear.
- Bordas com raio pequeno, entre 6px e 8px.
- Fundo claro, com areas neutras e contraste bom.
- Usar icones simples e consistentes.
- Evitar visual muito colorido, infantil, publicitario ou decorativo demais.

## Paleta recomendada

- Fundo da pagina: `#f8fafc`
- Superficies/cards: `#ffffff`
- Texto principal: `#111827`
- Texto secundario: `#64748b`
- Borda: `#e5e7eb`
- Azul primario: `#2563eb`
- Verde sucesso: `#16a34a`
- Amarelo alerta: `#f59e0b`
- Vermelho perigo: `#dc2626`
- Ciano informativo: `#0891b2`

Usar cores com funcao clara:

- Azul: links, botoes principais, icones de navegacao.
- Verde: faturamento, valores positivos, estoque de entrada, pedido finalizado.
- Amarelo: pendencias e estoque baixo.
- Vermelho: estoque zerado, saida de estoque, erros ou alertas graves.
- Cinza: dados neutros, datas e informacoes secundarias.

## Navegacao do sistema

A interface deve ter menu lateral com estes itens:

- Dashboard
- Cadastros
  - Clientes
  - Fornecedores
- Estoque
  - Produtos
  - Estoque
  - Insumos
- Vendas
  - Pedidos
- Administracao
  - Usuarios
  - Cargos
  - Painel Permissoes

## Componentes padrao

Usar os seguintes componentes visuais nas telas:

- Sidebar com logo/nome do sistema e grupos de menu.
- Topbar com titulo da tela, campo de busca, sino de notificacao e usuario logado.
- Cards de estatisticas com icone, numero principal, descricao curta e mini grafico.
- Tabelas com linhas alternadas, busca, ordenacao, paginacao e acoes por linha.
- Botoes principais em azul.
- Botoes secundarios discretos.
- Acoes por linha com icones de olho para ver e lapis para editar.
- Badges coloridos para status e estoque.
- Formularios em duas colunas no desktop e uma coluna no mobile.
- Selects pesquisaveis para cliente, produto, cargo e permissoes.
- Repeater/lista de itens para produtos dentro de um pedido.
- Graficos em card: linha para vendas recentes e doughnut para pedidos por status.

## Dados ficticios para popular as imagens

Use dados realistas, em portugues:

Clientes:

- Ana Paula Santos
- Gabriel Cruz
- Mariana Oliveira
- Carlos Eduardo Lima

Fornecedores:

- Tecidos Bom Preco
- Aviamentos Central
- Malhas Santa Rita
- Linha Forte Distribuidora

Produtos:

- Camiseta Basica Branca
- Uniforme Escolar Azul
- Calca Moletom Cinza
- Blusa Personalizada TB2

Insumos:

- Tecido algodao
- Linha branca
- Elastico
- Etiqueta personalizada

Pedidos:

- #1024 - Ana Paula Santos - Pendente - R$ 320,00
- #1025 - Gabriel Cruz - Em Producao - R$ 780,00
- #1026 - Mariana Oliveira - Finalizado - R$ 145,90
- #1027 - Carlos Eduardo Lima - Pendente - R$ 1.240,00

Indicadores do dashboard:

- Faturamento: R$ 18.420,90
- Hoje: R$ 1.240,00
- Pedidos: 38
- Clientes: 124
- Produtos: 86
- Estoque baixo: 5
- Operacao: 24 insumos, 12 fornecedores cadastrados

## Telas a gerar

### 1. Login do painel

Objetivo: tela de entrada do sistema administrativo.

Elementos:

- Fundo claro e limpo.
- Card central pequeno com nome Confeccao TB2.
- Campos E-mail e Senha.
- Botao Entrar.
- Link discreto para recuperacao de senha, se desejar.

Prompt sugerido:

> Criar imagem de uma tela de login web administrativa para "Confeccao TB2", estilo SaaS moderno e limpo, fundo claro, card central com borda suave, campos de e-mail e senha, botao azul "Entrar", tipografia profissional, interface realista em portugues, proporcao desktop 1440x1024.

### 2. Dashboard

Objetivo: mostrar a visao geral do sistema.

Elementos:

- Sidebar com todos os grupos de menu.
- Titulo "Dashboard".
- Cards: Faturamento, Pedidos, Clientes, Produtos, Estoque baixo, Operacao.
- Grafico de linha "Vendas recentes".
- Grafico doughnut "Pedidos por status".
- Tabela "Pedidos recentes".
- Tabela "Produtos com estoque baixo".

Prompt sugerido:

> Criar screenshot realista de dashboard administrativo para sistema de confeccao chamado "Confeccao TB2", sidebar lateral com grupos Dashboard, Cadastros, Estoque, Vendas e Administracao, cards de estatisticas com icones, grafico de linha de vendas recentes, grafico doughnut de pedidos por status, tabela de pedidos recentes e tabela de produtos com estoque baixo, cores azul, verde, amarelo e vermelho usadas como estados, layout limpo e profissional, interface em portugues, desktop 1440x1024.

### 3. Lista de clientes

Objetivo: gerenciar cadastro de clientes.

Colunas da tabela:

- Nome
- E-mail
- Telefone
- CPF / CNPJ
- Cadastrado em
- Atualizado em, opcional ou oculto

Acoes:

- Botao "Novo cliente".
- Busca.
- Acoes por linha: Ver e Editar.

Prompt sugerido:

> Criar tela administrativa de lista de clientes para Confeccao TB2, com sidebar lateral, titulo "Clientes", botao azul "Novo cliente", tabela listrada com colunas Nome, E-mail, Telefone, CPF/CNPJ e Cadastrado em, acoes de ver e editar por linha com icones, busca no topo da tabela, design limpo e denso, interface realista em portugues.

### 4. Formulario de cliente

Objetivo: criar ou editar cliente.

Campos:

- Nome Completo
- E-mail
- Telefone/Zap
- CPF ou CNPJ

Prompt sugerido:

> Criar tela de formulario administrativo "Criar Cliente" para Confeccao TB2, layout em duas colunas no desktop, campos Nome Completo, E-mail, Telefone/Zap e CPF ou CNPJ, botoes Salvar e Cancelar, sidebar lateral visivel, visual moderno e profissional, campos bem alinhados e espacamento limpo.

### 5. Lista e formulario de fornecedores

Objetivo: gerenciar fornecedores da confeccao.

Colunas da tabela:

- Fornecedor
- CNPJ / CPF
- E-mail
- Telefone
- CEP
- Cadastrado em

Campos do formulario:

- Nome Fornecedor
- E-mail
- Telefone/Zap
- Endereco/CEP
- CNPJ

Prompt sugerido:

> Criar tela administrativa de fornecedores para sistema de confeccao, sidebar lateral, titulo "Fornecedores", botao "Novo fornecedor", tabela com fornecedor, CNPJ/CPF, e-mail, telefone, CEP e data de cadastro, icones discretos de empresa, envelope e telefone, design de painel profissional em portugues.

### 6. Lista de produtos

Objetivo: controlar produtos vendidos pela confeccao.

Colunas da tabela:

- Produto
- Referencia
- Preco de Venda
- Estoque
- Cadastrado em

Campos do formulario:

- Nome Produto
- Referencia
- Preco Venda
- Estoque

Estados visuais:

- Estoque normal: badge verde.
- Estoque baixo: badge amarelo.
- Estoque zerado: badge vermelho.

Prompt sugerido:

> Criar tela de lista de produtos para Confeccao TB2, tabela administrativa com produto, referencia, preco de venda em reais, estoque em unidades e data de cadastro, badges coloridos para estoque normal, baixo e zerado, botao "Novo produto", sidebar de navegacao no grupo Estoque, design limpo e funcional.

### 7. Movimentacao de estoque

Objetivo: registrar entradas e saidas de estoque.

Colunas da tabela:

- Data
- Produto
- Tipo
- Quantidade
- Observacao

Filtros:

- Tipo: Entrada ou Saida.

Campos do formulario:

- Produto
- Tipo de Movimentacao
- Quantidade
- Observacao / Motivo

Estados visuais:

- Entrada: badge verde com seta para cima.
- Saida: badge vermelho com seta para baixo.
- Quantidade com sinal + ou -.

Prompt sugerido:

> Criar tela administrativa de movimentacao de estoque para confeccao, sidebar lateral, titulo "Estoque", tabela com Data, Produto, Tipo, Quantidade e Observacao, badges verdes para Entrada e vermelhos para Saida, filtro por tipo, botao "Nova movimentacao", layout profissional em portugues.

### 8. Lista e formulario de insumos

Objetivo: controlar materia-prima e materiais usados na confeccao.

Colunas da tabela:

- Insumo
- Unidade
- Preco de Custo
- Estoque
- Cadastrado em

Campos do formulario:

- Nome Insumo
- Unidade de medida: Kg, L, Mg
- Preco Custo
- Estoque

Prompt sugerido:

> Criar tela administrativa de insumos para uma confeccao, tabela com insumo, unidade, preco de custo, estoque e data de cadastro, badges para unidade e estoque, botao "Novo insumo", visual de sistema operacional moderno, limpo e realista.

### 9. Lista de pedidos

Objetivo: acompanhar vendas e producao.

Colunas da tabela:

- Numero do pedido
- Cliente
- Status
- Itens
- Valor Total
- Criado em

Filtros:

- Status: Pendente, Em Producao, Finalizado.

Estados visuais:

- Pendente: amarelo, icone de relogio.
- Em Producao: azul/ciano, icone de ferramenta.
- Finalizado: verde, icone de check.

Prompt sugerido:

> Criar tela administrativa de pedidos para Confeccao TB2, tabela com numero do pedido, cliente, status, quantidade de itens, valor total e data de criacao, badges coloridos para Pendente, Em Producao e Finalizado, filtro de status, botao "Novo pedido", design limpo e profissional em portugues.

### 10. Formulario de pedido

Objetivo: criar venda com varios produtos e calcular total.

Campos:

- Selecione o Cliente
- Status
- Valor Total, somente leitura
- Produtos do Pedido
- Produto
- Quantidade
- Preco Unitario

Comportamento visual esperado:

- Secao "Produtos do Pedido" em formato de lista/repeater.
- Cada item do pedido com produto, quantidade e preco unitario.
- Valor total destacado no topo ou no fim do formulario.

Prompt sugerido:

> Criar tela de formulario "Criar Pedido" para sistema de confeccao, com select de cliente, select de status, campo Valor Total em destaque e somente leitura, secao "Produtos do Pedido" com linhas repetiveis contendo produto, quantidade e preco unitario, botoes Salvar e Cancelar, layout administrativo moderno em portugues.

### 11. Administracao: usuarios, cargos e permissoes

Objetivo: controlar acesso ao sistema.

Usuarios:

- Nome
- E-mail
- Cargo
- Criada em

Cargos:

- Nome do Cargo
- Permissoes
- Criada em

Permissoes:

- Nome de Permissao
- Nivel de Permissao
- Criada em

Prompt sugerido:

> Criar tela administrativa de usuarios e permissoes para Confeccao TB2, sidebar com grupo Administracao, tabela de usuarios com nome, e-mail, cargo e data, botao "Novo usuario", visual mais restrito e corporativo, campos de selecao multipla para cargos e permissoes.

### 12. Versao mobile

Objetivo: mostrar que o sistema funciona em tela pequena.

Elementos:

- Sidebar recolhida em menu.
- Cards empilhados.
- Tabelas virando listas compactas.
- Botoes com icones.

Prompt sugerido:

> Criar versao mobile de painel administrativo Confeccao TB2 em smartphone 390x844, menu lateral recolhido, dashboard com cards empilhados, lista compacta de pedidos recentes, badges de status, visual limpo, moderno e legivel em portugues.

## Prompt base reutilizavel

Use este prompt como base e troque apenas a tela:

> Gerar screenshot de interface web administrativa realista para o sistema "Confeccao TB2", um painel de gestao para confeccao. Estilo SaaS moderno, limpo, profissional e funcional. Layout com sidebar lateral, topbar discreta, tipografia sem serifa, cards e tabelas bem alinhados, cores de estado azul, verde, amarelo e vermelho, fundo claro, bordas suaves, interface em portugues, alta fidelidade, sem aparencia de landing page, sem ilustracoes decorativas, proporcao desktop 1440x1024.

## Prompt negativo

Evitar:

- Landing page ou pagina de marketing.
- Hero grande com texto promocional.
- Ilustracoes decorativas sem relacao com o sistema.
- Fundo escuro dominante.
- Gradientes muito fortes.
- Layout muito vazio.
- Texto em ingles.
- Botoes gigantes.
- Cartoes exageradamente arredondados.
- Imagem de fabrica/foto como tela principal.
- Interface estilo aplicativo infantil.

## Padrao de arquivos das imagens

Sugestao de nomes:

- `01-login.png`
- `02-dashboard.png`
- `03-clientes-lista.png`
- `04-cliente-formulario.png`
- `05-fornecedores.png`
- `06-produtos.png`
- `07-estoque.png`
- `08-insumos.png`
- `09-pedidos-lista.png`
- `10-pedido-formulario.png`
- `11-administracao.png`
- `12-mobile-dashboard.png`

## Observacoes finais

- O sistema deve parecer pronto para uso por uma equipe pequena ou media de confeccao.
- Priorizar legibilidade e organizacao sobre impacto visual.
- Textos devem estar em portugues.
- Se a IA tiver dificuldade com texto perfeito, gerar a composicao visual e substituir os textos manualmente depois.
- Manter a identidade simples: Confeccao TB2 como nome principal e azul como cor de destaque.
