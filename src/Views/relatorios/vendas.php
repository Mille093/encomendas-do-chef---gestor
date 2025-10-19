<?php 
$title = 'Relatório de Vendas';
$layout = 'layouts/app';
ob_start(); 
?>

<div class="page-header">
    <h1>Relatório de Vendas</h1>
    <div class="header-actions">
        <a href="/relatorios/exportar-csv/vendas?de=<?= $de ?>&ate=<?= $ate ?>" 
           class="btn btn-secondary">
            <i class="icon-download"></i> Exportar CSV
        </a>
    </div>
</div>

<!-- Filtro de Período -->
<div class="card filter-card">
    <div class="card-body">
        <form method="GET" action="/relatorios/vendas" class="filter-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="de">Data Inicial:</label>
                    <input type="date" 
                           id="de" 
                           name="de" 
                           class="form-control" 
                           value="<?= $de ?>" 
                           required>
                </div>
                <div class="form-group">
                    <label for="ate">Data Final:</label>
                    <input type="date" 
                           id="ate" 
                           name="ate" 
                           class="form-control" 
                           value="<?= $ate ?>" 
                           required>
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary">
                        <i class="icon-search"></i> Gerar Relatório
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Estatísticas Gerais -->
<div class="stats-cards">
    <div class="stat-card">
        <div class="stat-icon pedidos"></div>
        <div class="stat-content">
            <h3><?= number_format($estatisticas['total_pedidos']) ?></h3>
            <p>Pedidos Realizados</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon faturamento"></div>
        <div class="stat-content">
            <h3>R$ <?= number_format($estatisticas['faturamento_total'], 2, ',', '.') ?></h3>
            <p>Faturamento Total</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon ticket"></div>
        <div class="stat-content">
            <h3>R$ <?= number_format($estatisticas['ticket_medio'], 2, ',', '.') ?></h3>
            <p>Ticket Médio</p>
        </div>
    </div>
</div>

<!-- Produtos Mais Vendidos -->
<div class="card">
    <div class="card-header">
        <h3>Top 10 - Produtos Mais Vendidos</h3>
    </div>
    <div class="card-body">
        <?php if (empty($topProdutos)): ?>
            <div class="empty-state">
                <p>Nenhuma venda registrada no período selecionado.</p>
            </div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Produto</th>
                        <th>Quantidade Vendida</th>
                        <th>Valor Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topProdutos as $index => $produto): ?>
                        <tr>
                            <td>
                                <span class="ranking-badge"><?= $index + 1 ?>º</span>
                            </td>
                            <td><?= htmlspecialchars($produto['prod_nome']) ?></td>
                            <td><?= number_format($produto['quantidade_vendida']) ?> unidades</td>
                            <td class="text-success">
                                <strong>R$ <?= number_format($produto['valor_total'], 2, ',', '.') ?></strong>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- Vendas por Status -->
<div class="card">
    <div class="card-header">
        <h3>Pedidos por Status</h3>
    </div>
    <div class="card-body">
        <?php if (empty($pedidosPorStatus)): ?>
            <div class="empty-state">
                <p>Nenhum pedido encontrado no período.</p>
            </div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Quantidade</th>
                        <th>Valor Total</th>
                        <th>Porcentagem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totalPedidos = array_sum(array_column($pedidosPorStatus, 'quantidade'));
                    foreach ($pedidosPorStatus as $status): 
                        $porcentagem = $totalPedidos > 0 ? ($status['quantidade'] / $totalPedidos) * 100 : 0;
                        $statusClass = match($status['ped_status']) {
                            'pendente' => 'badge-warning',
                            'em_preparacao' => 'badge-info',
                            'pronto' => 'badge-success', 
                            'entregue' => 'badge-secondary',
                            'cancelado' => 'badge-danger',
                            default => 'badge-secondary'
                        };
                    ?>
                        <tr>
                            <td>
                                <span class="badge <?= $statusClass ?>">
                                    <?= ucfirst(str_replace('_', ' ', $status['ped_status'])) ?>
                                </span>
                            </td>
                            <td><?= number_format($status['quantidade']) ?></td>
                            <td>R$ <?= number_format($status['valor_total'], 2, ',', '.') ?></td>
                            <td><?= number_format($porcentagem, 1) ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- Categorias Mais Vendidas -->
<?php if (!empty($categorias)): ?>
<div class="card">
    <div class="card-header">
        <h3>Vendas por Categoria</h3>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Categoria</th>
                    <th>Pedidos</th>
                    <th>Quantidade</th>
                    <th>Valor Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td><?= htmlspecialchars($categoria['cat_nome']) ?></td>
                        <td><?= number_format($categoria['pedidos_com_categoria']) ?></td>
                        <td><?= number_format($categoria['quantidade_vendida']) ?> unidades</td>
                        <td>R$ <?= number_format($categoria['valor_total'], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<style>
.filter-card {
    margin-bottom: 20px;
}
.filter-form .form-row {
    display: flex;
    gap: 15px;
    align-items: end;
    flex-wrap: wrap;
}
.filter-form .form-group {
    flex: 1;
    min-width: 150px;
}
.stats-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}
.stat-card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #ddd;
    display: flex;
    align-items: center;
    gap: 15px;
}
.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}
.stat-icon.pedidos { background: #17a2b8; }
.stat-icon.faturamento { background: #28a745; }
.stat-icon.ticket { background: #ffc107; color: #212529; }
.stat-content h3 {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
    color: #212529;
}
.stat-content p {
    margin: 5px 0 0;
    color: #6c757d;
    font-size: 14px;
}
.ranking-badge {
    background: var(--amarelo);
    color: var(--preto);
    padding: 4px 8px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 12px;
}
.text-success {
    color: #28a745;
}
.header-actions {
    display: flex;
    gap: 10px;
}
</style>

<?php 
$content = ob_get_clean();
include __DIR__ . "/../{$layout}.php";
?>