<?php 
$title = 'Pedido #' . $pedido['ped_numero'];
$layout = 'layouts/app';
ob_start(); 
?>

<div class="page-header">
    <h1>Pedido #<?= $pedido['ped_numero'] ?></h1>
    <a href="/pedidos" class="btn btn-secondary">
        <i class="icon-arrow-left"></i> Voltar
    </a>
</div>

<?php if (isset($_SESSION['flash'])): ?>
    <div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
        <?= htmlspecialchars($_SESSION['flash']['message']) ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<div class="pedido-details">
    <!-- Informações do Pedido -->
    <div class="card">
        <div class="card-header">
            <h3>Informações do Pedido</h3>
        </div>
        <div class="card-body">
            <div class="pedido-info">
                <div class="info-item">
                    <label>Status:</label>
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
                    <span class="badge <?= $statusClass ?> large">
                        <?= ucfirst(str_replace('_', ' ', $pedido['ped_status'])) ?>
                    </span>
                </div>
                <div class="info-item">
                    <label>Data do Pedido:</label>
                    <span><?= date('d/m/Y H:i', strtotime($pedido['ped_data_elaboracao'])) ?></span>
                </div>
                <div class="info-item">
                    <label>Valor Total:</label>
                    <span class="valor-total">R$ <?= number_format($pedido['ped_valor_total'], 2, ',', '.') ?></span>
                </div>
                <?php if ($pedido['ped_data_finalizacao']): ?>
                <div class="info-item">
                    <label>Data de Finalização:</label>
                    <span><?= date('d/m/Y H:i', strtotime($pedido['ped_data_finalizacao'])) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Informações do Cliente -->
    <div class="card">
        <div class="card-header">
            <h3>Dados do Cliente</h3>
        </div>
        <div class="card-body">
            <div class="cliente-info">
                <div class="info-item">
                    <label>Nome:</label>
                    <span><?= htmlspecialchars($pedido['cli_nome']) ?></span>
                </div>
                <?php if ($pedido['cli_email']): ?>
                <div class="info-item">
                    <label>E-mail:</label>
                    <span><a href="mailto:<?= htmlspecialchars($pedido['cli_email']) ?>"><?= htmlspecialchars($pedido['cli_email']) ?></a></span>
                </div>
                <?php endif; ?>
                <?php if ($pedido['cli_telefone']): ?>
                <div class="info-item">
                    <label>Telefone:</label>
                    <span><a href="tel:<?= htmlspecialchars($pedido['cli_telefone']) ?>"><?= htmlspecialchars($pedido['cli_telefone']) ?></a></span>
                </div>
                <?php endif; ?>
                <?php if ($pedido['cli_endereco']): ?>
                <div class="info-item">
                    <label>Endereço:</label>
                    <span><?= htmlspecialchars($pedido['cli_endereco']) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Itens do Pedido -->
    <div class="card">
        <div class="card-header">
            <h3>Itens do Pedido</h3>
        </div>
        <div class="card-body">
            <?php if (empty($itens)): ?>
                <p class="text-muted">Nenhum item encontrado para este pedido.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Preço Unitário</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $subtotal = 0; ?>
                        <?php foreach ($itens as $item): ?>
                            <?php 
                            $totalItem = $item['itp_quantidade_comprada'] * $item['itp_preco_unitario'];
                            $subtotal += $totalItem;
                            ?>
                            <tr>
                                <td>
                                    <div class="produto-item">
                                        <?php if ($item['prod_imagem']): ?>
                                            <img src="/uploads/<?= htmlspecialchars($item['prod_imagem']) ?>" 
                                                 alt="<?= htmlspecialchars($item['prod_nome']) ?>"
                                                 class="produto-thumb">
                                        <?php endif; ?>
                                        <span><?= htmlspecialchars($item['prod_nome']) ?></span>
                                    </div>
                                </td>
                                <td><?= $item['itp_quantidade_comprada'] ?></td>
                                <td>R$ <?= number_format($item['itp_preco_unitario'], 2, ',', '.') ?></td>
                                <td>R$ <?= number_format($totalItem, 2, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="total-row">
                            <td colspan="3"><strong>Total do Pedido:</strong></td>
                            <td><strong>R$ <?= number_format($pedido['ped_valor_total'], 2, ',', '.') ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Ações do Pedido -->
    <?php if (!in_array($pedido['ped_status'], ['entregue', 'cancelado'])): ?>
    <div class="card">
        <div class="card-header">
            <h3>Atualizar Status</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="/pedidos/update-status/<?= $pedido['ped_numero'] ?>" class="status-form">
                <div class="form-group">
                    <label for="ped_status">Novo Status:</label>
                    <select id="ped_status" name="ped_status" class="form-control" required>
                        <option value="">-- Selecione o novo status --</option>
                        <?php if ($pedido['ped_status'] == 'pendente'): ?>
                            <option value="em_preparacao">Em Preparação</option>
                            <option value="cancelado">Cancelar Pedido</option>
                        <?php elseif ($pedido['ped_status'] == 'em_preparacao'): ?>
                            <option value="pronto">Marcar como Pronto</option>
                            <option value="cancelado">Cancelar Pedido</option>
                        <?php elseif ($pedido['ped_status'] == 'pronto'): ?>
                            <option value="entregue">Marcar como Entregue</option>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="icon-save"></i> Atualizar Status
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
.pedido-details {
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.pedido-info, .cliente-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}
.info-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.info-item label {
    font-weight: 600;
    color: #495057;
    font-size: 14px;
}
.info-item span {
    color: #212529;
}
.valor-total {
    font-size: 18px;
    font-weight: 700;
    color: #28a745;
}
.badge.large {
    font-size: 16px;
    padding: 8px 12px;
}
.produto-item {
    display: flex;
    align-items: center;
    gap: 10px;
}
.produto-thumb {
    width: 40px;
    height: 40px;
    border-radius: 6px;
    object-fit: cover;
}
.total-row {
    background-color: #f8f9fa;
    font-weight: 600;
}
.status-form {
    max-width: 300px;
}
.text-muted {
    color: #6c757d;
}
</style>

<?php 
$content = ob_get_clean();
include __DIR__ . "/../{$layout}.php";
?>