<?php 
$title = 'Promoções';
$layout = 'layouts/app';
ob_start(); 
?>

<div class="page-header">
    <h1>Promoções</h1>
    <a href="/promocoes/add" class="btn btn-primary">
        <i class="icon-plus"></i> Nova Promoção
    </a>
</div>

<?php if (isset($_SESSION['flash'])): ?>
    <div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
        <?= htmlspecialchars($_SESSION['flash']['message']) ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3>Lista de Promoções</h3>
    </div>
    <div class="card-body">
        <?php if (empty($promocoes)): ?>
            <div class="empty-state">
                <p>Nenhuma promoção cadastrada.</p>
                <a href="/promocoes/add" class="btn btn-primary">Cadastrar primeira promoção</a>
            </div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Tipo</th>
                        <th>Valor</th>
                        <th>Período</th>
                        <th>Status</th>
                        <th class="actions">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($promocoes as $promocao): ?>
                        <?php
                        $hoje = date('Y-m-d');
                        $ativa = $promocao['ativo'] == 1 && 
                                $promocao['prm_data_inicio'] <= $hoje && 
                                $promocao['prm_data_fim'] >= $hoje;
                        $expirada = $promocao['prm_data_fim'] < $hoje;
                        $futura = $promocao['prm_data_inicio'] > $hoje;
                        ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($promocao['prm_nome']) ?></strong>
                                <?php if ($promocao['prm_descricao']): ?>
                                    <br><small class="text-muted"><?= htmlspecialchars(substr($promocao['prm_descricao'], 0, 100)) ?>...</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($promocao['prm_tipo'] === 'percentual'): ?>
                                    <span class="badge badge-info">Percentual</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Valor Fixo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($promocao['prm_tipo'] === 'percentual'): ?>
                                    <?= number_format($promocao['prm_valor'], 2, ',', '.') ?>%
                                <?php else: ?>
                                    R$ <?= number_format($promocao['prm_valor'], 2, ',', '.') ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= date('d/m/Y', strtotime($promocao['prm_data_inicio'])) ?><br>
                                <small>até <?= date('d/m/Y', strtotime($promocao['prm_data_fim'])) ?></small>
                            </td>
                            <td>
                                <?php if ($ativa): ?>
                                    <span class="badge badge-success">Ativa</span>
                                <?php elseif ($expirada): ?>
                                    <span class="badge badge-danger">Expirada</span>
                                <?php elseif ($futura): ?>
                                    <span class="badge badge-secondary">Futura</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Inativa</span>
                                <?php endif; ?>
                            </td>
                            <td class="actions">
                                <a href="/promocoes/edit/<?= $promocao['prm_id'] ?>" 
                                   class="btn btn-sm btn-outline-primary" 
                                   title="Editar">
                                    <i class="icon-edit"></i>
                                </a>
                                <?php if (!$ativa): ?>
                                    <a href="/promocoes/delete/<?= $promocao['prm_id'] ?>" 
                                       class="btn btn-sm btn-outline-danger" 
                                       title="Excluir"
                                       onclick="return confirm('Tem certeza que deseja excluir esta promoção?')">
                                        <i class="icon-trash"></i>
                                    </a>
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
.badge-info { background-color: #17a2b8; color: white; }
.badge-warning { background-color: #ffc107; color: #212529; }
.badge-secondary { background-color: #6c757d; color: white; }
.text-muted { color: #6c757d; }
</style>

<?php 
$content = ob_get_clean();
include __DIR__ . "/../{$layout}.php";
?>