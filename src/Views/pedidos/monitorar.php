<?php
// src/Views/pedidos/monitorar.php

// A variável $pedidos (com os dados agrupados) é acessível aqui.
?>
<style>
/* Variáveis de Cores */
:root {
    --fundo-escuro: #444444; 
    --amarelo-principal: #fcd900; 
    --vermelho-destaque: #b53835; 
    --amarelo-card: #f8e596; 
}

/* IMPORTANTE: Este CSS é para o conteúdo da tela, não para o layout principal da aplicação (sidebar, header superior). */

body {
    background-color: var(--fundo-escuro); 
    font-family: Arial, sans-serif;
    color: #333; 
    margin: 0;
    padding: 0;
}

/* CONTAINER PRINCIPAL */
.monitoramento-container {
    width: 100%;
    max-width: 800px; 
    margin: 20px auto;
    padding: 20px;
}

/* CABEÇALHO E TÍTULO */
.header-monitoramento {
    text-align: center;
    margin-bottom: 25px;
}

.titulo-pedidos-andamento {
    display: inline-block;
    background-color: var(--vermelho-destaque);
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 1.5em;
    font-weight: bold;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    text-transform: uppercase;
}

/* CARTÕES DE PEDIDO */
.pedido-card {
    background-color: var(--amarelo-card);
    border: 1px solid #d4c06b;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid #e7d174;
    padding-bottom: 10px;
    margin-bottom: 10px;
}

.status-titulo {
    font-weight: bold;
    color: var(--vermelho-destaque);
    font-size: 1.1em;
}

.cliente-info {
    margin-bottom: 10px;
    font-weight: bold;
}

/* ITENS DO PEDIDO */
.itens-lista {
    margin: 10px 0;
    padding-left: 5px;
}

.item-pedido {
    margin-bottom: 5px;
    display: flex;
    align-items: center;
}

.quantidade {
    display: inline-block;
    width: 30px;
    text-align: center;
    margin-right: 10px;
    font-weight: bold;
    border: 1px solid #ccc;
    padding: 2px 5px;
    border-radius: 3px;
    background-color: #fff;
    font-size: 0.9em;
}

.descricao {
    flex-grow: 1;
}

/* OBSERVAÇÃO */
.observacao {
    background-color: #f7bb63; 
    border-left: 5px solid #d88b0b;
    padding: 8px 10px;
    margin: 15px 0;
    border-radius: 3px;
    font-size: 0.9em;
    font-style: italic;
}

.obs-tag {
    font-weight: bold;
    color: var(--vermelho-destaque);
    margin-right: 5px;
}

/* BOTÃO DE AÇÃO */
.btn-acao {
    background-color: #c99c33; 
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s;
    text-transform: uppercase;
}

.btn-acao:hover {
    background-color: #a8832a;
}

.prazo {
    text-align: right;
    font-size: 0.9em;
    font-style: italic;
    margin-top: 10px;
    color: #555;
}
</style>


<div class="monitoramento-container">

    <div class="header-monitoramento">
        <h2 class="titulo-pedidos-andamento">Pedidos em Andamento</h2>
    </div>
    
    <?php if (empty($pedidos)): ?>
        <div style="text-align: center; color: white; padding: 50px; background-color: #555; border-radius: 8px;">
            <p>🎉 Nenhum pedido em preparo ou pendente no momento!</p>
        </div>
    <?php else: ?>
        
        <?php foreach ($pedidos as $pedido): 
            // Formata o status para exibição
            $status_display = ucfirst(str_replace('_', ' ', $pedido['status']));
            $data_formatada = date('d/m/y H:i', strtotime($pedido['data']));
            
            // Define a URL para edição ou finalização (usando a rota view)
            $url_acao = "/pedidos/view/{$pedido['numero']}";
        ?>
        <div class="pedido-card">
            <div class="card-header">
                <span class="status-titulo">Pedido em Andamento - N° <?= $pedido['numero'] ?></span>
                
                <a href="<?= $url_acao ?>" class="btn-acao">
                    Ver/Avançar Status
                </a>
            </div>
            
            <p class="cliente-info">Cliente: <?= htmlspecialchars($pedido['cliente']) ?></p>
            
            <div class="itens-lista">
                <?php foreach ($pedido['itens'] as $item): ?>
                    <div class="item-pedido">
                        <span class="quantidade"><?= $item['quantidade'] ?>x</span>
                        <span class="descricao"><?= htmlspecialchars($item['nome']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (!empty($pedido['observacao'])): ?>
                <div class="observacao">
                    <span class="obs-tag">OBS:</span> <span class="obs-texto"><?= htmlspecialchars($pedido['observacao']) ?></span>
                </div>
            <?php endif; ?>

            <p class="prazo">Pedido feito em: **<?= $data_formatada ?>**</p>
        </div>
        <?php endforeach; ?>

    <?php endif; ?>

</div>