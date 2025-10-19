<?php 
$title = 'Categorias';
$layout = 'layouts/app';
ob_start(); 
?>

<div class="page-header">
    <h1>Categorias</h1>
    <a href="/categorias/add" class="btn btn-primary">
        <i class="icon-plus"></i> Nova Categoria
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
        <h3>Lista de Categorias</h3>
    </div>
    <div class="card-body">
        <?php if (empty($categorias)): ?>
            <div class="empty-state">
                <p>Nenhuma categoria cadastrada.</p>
                <a href="/categorias/add" class="btn btn-primary">Cadastrar primeira categoria</a>
            </div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th class="actions">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias as $categoria): ?>
                        <tr>
                            <td><?= htmlspecialchars($categoria['cat_codigo']) ?></td>
                            <td><?= htmlspecialchars($categoria['cat_nome']) ?></td>
                            <td class="actions">
                                <a href="/categorias/edit/<?= $categoria['cat_codigo'] ?>" 
                                   class="btn btn-sm btn-outline-primary" 
                                   title="Editar">
                                    <i class="icon-edit"></i>
                                </a>
                                <a href="/categorias/delete/<?= $categoria['cat_codigo'] ?>" 
                                   class="btn btn-sm btn-outline-danger" 
                                   title="Excluir"
                                   onclick="return confirm('Tem certeza que deseja excluir esta categoria?')">
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

<?php 
$content = ob_get_clean();
include __DIR__ . "/../{$layout}.php";
?>