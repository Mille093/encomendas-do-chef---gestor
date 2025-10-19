<?php 
$title = 'Editar Categoria';
$layout = 'layouts/app';
ob_start(); 
?>

<div class="page-header">
    <h1>Editar Categoria</h1>
    <a href="/categorias" class="btn btn-secondary">
        <i class="icon-arrow-left"></i> Voltar
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
        <h3>Dados da Categoria</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="/categorias/edit/<?= $categoria['cat_codigo'] ?>">
            <div class="form-group">
                <label for="cat_nome">Nome da Categoria:</label>
                <input type="text" 
                       id="cat_nome" 
                       name="cat_nome" 
                       class="form-control" 
                       required 
                       maxlength="100"
                       placeholder="Digite o nome da categoria"
                       value="<?= htmlspecialchars($categoria['cat_nome']) ?>">
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="icon-save"></i> Salvar Alterações
                </button>
                <a href="/categorias" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php 
$content = ob_get_clean();
include __DIR__ . "/../{$layout}.php";
?>