<?php 
$title = 'Novo Produto';
$layout = 'layouts/app';
ob_start(); 
?>

<div class="page-header">
    <h1>Novo Produto</h1>
    <a href="/produtos" class="btn btn-secondary">
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
        <h3>Dados do Produto</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="/produtos/add" enctype="multipart/form-data">
            <div class="form-group">
                <label for="prod_nome">Nome do Produto:</label>
                <input type="text" 
                       id="prod_nome" 
                       name="prod_nome" 
                       class="form-control" 
                       required 
                       maxlength="200"
                       placeholder="Digite o nome do produto"
                       value="<?= htmlspecialchars($_POST['prod_nome'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="prod_descricao">Descrição:</label>
                <textarea id="prod_descricao" 
                          name="prod_descricao" 
                          class="form-control" 
                          rows="4"
                          placeholder="Descrição detalhada do produto (opcional)"><?= htmlspecialchars($_POST['prod_descricao'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="prod_preco">Preço (R$):</label>
                <input type="text" 
                       id="prod_preco" 
                       name="prod_preco" 
                       class="form-control" 
                       required 
                       placeholder="0,00"
                       value="<?= htmlspecialchars($_POST['prod_preco'] ?? '') ?>">
                <small class="text-muted">Use vírgula para decimais (ex: 15,50)</small>
            </div>

            <div class="form-group">
                <label for="cat_codigo">Categoria:</label>
                <select id="cat_codigo" name="cat_codigo" class="form-control" required>
                    <option value="">-- Selecione uma categoria --</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= $categoria['cat_codigo'] ?>" 
                                <?= (($_POST['cat_codigo'] ?? '') == $categoria['cat_codigo']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($categoria['cat_nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="prod_imagem">Imagem do Produto:</label>
                <input type="file" 
                       id="prod_imagem" 
                       name="prod_imagem" 
                       class="form-control"
                       accept="image/*">
                <small class="text-muted">Formatos aceitos: JPG, PNG, GIF (máximo 2MB)</small>
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" 
                           name="prod_ativo" 
                           value="1" 
                           <?= (($_POST['prod_ativo'] ?? '1') == '1') ? 'checked' : '' ?>>
                    Produto ativo (disponível para venda)
                </label>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="icon-save"></i> Salvar Produto
                </button>
                <a href="/produtos" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<style>
.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: normal;
    cursor: pointer;
}
.checkbox-label input[type="checkbox"] {
    width: auto;
    margin: 0;
}
</style>

<?php 
$content = ob_get_clean();
include __DIR__ . "/../{$layout}.php";
?>