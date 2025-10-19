<?php 
$title = 'Produtos';
$layout = 'layouts/app';
ob_start(); 
?>

<div class="page-header">
    <h1>Produtos</h1>
    <a href="/produtos/add" class="btn btn-primary">
        <i class="icon-plus"></i> Novo Produto
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
        <h3>Lista de Produtos</h3>
    </div>
    <div class="card-body">
        <?php if (empty($produtos)): ?>
            <div class="empty-state">
                <p>Nenhum produto cadastrado.</p>
                <a href="/produtos/add" class="btn btn-primary">Cadastrar primeiro produto</a>
            </div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Imagem</th>
                        <th>Nome</th>
                        <th>Categoria</th>
                        <th>Preço</th>
                        <th>Status</th>
                        <th class="actions">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td>
                                <?php if ($produto['prod_imagem']): ?>
                                    <img src="/uploads/<?= htmlspecialchars($produto['prod_imagem']) ?>" 
                                         alt="<?= htmlspecialchars($produto['prod_nome']) ?>"
                                         class="product-thumb"
                                         width="50" height="50">
                                <?php else: ?>
                                    <div class="no-image">Sem imagem</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($produto['prod_nome']) ?></strong>
                                <?php if ($produto['prod_descricao']): ?>
                                    <br><small class="text-muted"><?= htmlspecialchars(substr($produto['prod_descricao'], 0, 100)) ?>...</small>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($produto['cat_nome'] ?? 'Sem categoria') ?></td>
                            <td>R$ <?= number_format($produto['prod_preco'], 2, ',', '.') ?></td>
                            <td>
                                <?php if ($produto['prod_ativo']): ?>
                                    <span class="badge badge-success">Ativo</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Inativo</span>
                                <?php endif; ?>
                            </td>
                            <td class="actions">
                                <a href="/produtos/edit/<?= $produto['prod_codigo'] ?>" 
                                   class="btn btn-sm btn-outline-primary" 
                                   title="Editar">
                                    <i class="icon-edit"></i>
                                </a>
                                <a href="/produtos/delete/<?= $produto['prod_codigo'] ?>" 
                                   class="btn btn-sm btn-outline-danger" 
                                   title="Excluir"
                                   onclick="return confirm('Tem certeza que deseja excluir este produto?')">
                                    <i class="icon-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<style>
.product-thumb {
    border-radius: 4px;
    object-fit: cover;
}
.no-image {
    width: 50px;
    height: 50px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    color: #6c757d;
    border-radius: 4px;
}
.badge {
    padding: 0.25em 0.6em;
    font-size: 0.75em;
    border-radius: 10rem;
}
.badge-success { background-color: #28a745; color: white; }
.badge-danger { background-color: #dc3545; color: white; }
</style>

<?php 
$content = ob_get_clean();
include __DIR__ . "/../{$layout}.php";
?>