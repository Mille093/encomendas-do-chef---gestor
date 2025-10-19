<?php 
$title = 'Pedidos';
$layout = 'layouts/app';
ob_start(); 
?>

<div class="page-header">
    <h1>Pedidos</h1>
</div>

<?php if (isset($_SESSION['flash'])): ?>
    <div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
        <?= htmlspecialchars($_SESSION['flash']['message']) ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<!-- Filtros por Status -->
<div class="filters-card">
    <div class="filters">
        <a href="/pedidos" class="filter-btn <?= empty($filtroAtual) ? 'active' : '' ?>">
            Todos
        </a>
        <a href="/pedidos?status=pendente" class="filter-btn <?= $filtroAtual == 'pendente' ? 'active' : '' ?>">
            Pendentes
        </a>
        <a href="/pedidos?status=em_preparacao" class="filter-btn <?= $filtroAtual == 'em_preparacao' ? 'active' : '' ?>">
            Em Preparação
        </a>
        <a href="/pedidos?status=pronto" class="filter-btn <?= $filtroAtual == 'pronto' ? 'active' : '' ?>">
            Prontos
        </a>
        <a href="/pedidos?status=entregue" class="filter-btn <?= $filtroAtual == 'entregue' ? 'active' : '' ?>">
            Entregues
        </a>
        <a href="/pedidos?status=cancelado" class="filter-btn <?= $filtroAtual == 'cancelado' ? 'active' : '' ?>">
            Cancelados
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>
            Lista de Pedidos 
            <?php if ($filtroAtual): ?>
                - <?= ucfirst(str_replace('_', ' ', $filtroAtual)) ?>
            <?php endif; ?>
        </h3>
    </div>
    <div class="card-body">
        <?php if (empty($pedidos)): ?>
            <div class="empty-state">
                <p>
                    <?php if ($filtroAtual): ?>
                        Nenhum pedido encontrado com status "<?= ucfirst(str_replace('_', ' ', $filtroAtual)) ?>".
                    <?php else: ?>
                        Nenhum pedido cadastrado ainda.
                    <?php endif; ?>
                </p>
            </div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Valor Total</th>
                        <th>Status</th>
                        <th>Data do Pedido</th>
                        <th class="actions">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                        <?php
                        $statusClass = match($pedido['ped_status']) {
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
                                <strong>#<?= $pedido['ped_numero'] ?></strong>
                            </td>
                            <td><?= htmlspecialchars($pedido['cli_nome']) ?></td>
                            <td>R$ <?= number_format($pedido['ped_valor_total'], 2, ',', '.') ?></td>
                            <td>
                                <span class="badge <?= $statusClass ?>">
                                    <?= ucfirst(str_replace('_', ' ', $pedido['ped_status'])) ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($pedido['ped_data_elaboracao'])) ?></td>
                            <td class="actions">
                                <a href="/pedidos/view/<?= $pedido['ped_numero'] ?>" 
                                   class="btn btn-sm btn-outline-primary" 
                                   title="Ver Detalhes">
                                    <i class="icon-eye"></i> Ver
                                </a>
                                <?php if ($pedido['ped_status'] == 'pendente'): ?>
                                    <form method="POST" action="/pedidos/update-status/<?= $pedido['ped_numero'] ?>" style="display: inline;">
                                        <input type="hidden" name="ped_status" value="em_preparacao">
                                        <button type="submit" class="btn btn-sm btn-success" title="Iniciar Preparo">
                                            <i class="icon-play"></i> Assumir
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<style>
.filters-card {
    background: white;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
}
.filters {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.filter-btn {
    padding: 8px 15px;
    background: #f8f9fa;
    color: #495057;
    text-decoration: none;
    border-radius: 6px;
    border: 1px solid #dee2e6;
    font-size: 14px;
    transition: all 0.3s;
}
.filter-btn:hover {
    background: #e9ecef;
}
.filter-btn.active {
    background: var(--amarelo);
    color: var(--preto);
    font-weight: 600;
}
.badge-info { background-color: #17a2b8; color: white; }
.badge-warning { background-color: #ffc107; color: #212529; }
.badge-secondary { background-color: #6c757d; color: white; }
</style>

<?php 
$content = ob_get_clean();
include __DIR__ . "/../{$layout}.php";
?>