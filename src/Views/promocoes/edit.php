<?php 
$title = 'Editar Promoção';
$layout = 'layouts/app';
ob_start(); 
?>

<div class="page-header">
    <h1>Editar Promoção</h1>
    <a href="/promocoes" class="btn btn-secondary">
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
        <h3>Editar Dados da Promoção</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="/promocoes/edit/<?= $promocao['prm_id'] ?>">
            <div class="form-group">
                <label for="prm_nome">Nome da Promoção:</label>
                <input type="text" 
                       id="prm_nome" 
                       name="prm_nome" 
                       class="form-control" 
                       required 
                       maxlength="100"
                       value="<?= htmlspecialchars($promocao['prm_nome']) ?>">
            </div>

            <div class="form-group">
                <label for="prm_descricao">Descrição:</label>
                <textarea id="prm_descricao" 
                          name="prm_descricao" 
                          class="form-control" 
                          rows="3"><?= htmlspecialchars($promocao['prm_descricao']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="prm_tipo">Tipo de Desconto:</label>
                <select id="prm_tipo" name="prm_tipo" class="form-control" required>
                    <option value="percentual" <?= ($promocao['prm_tipo'] == 'percentual') ? 'selected' : '' ?>>
                        Percentual (%)
                    </option>
                    <option value="valor" <?= ($promocao['prm_tipo'] == 'valor') ? 'selected' : '' ?>>
                        Valor Fixo (R$)
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="prm_valor">Valor do Desconto:</label>
                <input type="text" 
                       id="prm_valor" 
                       name="prm_valor" 
                       class="form-control" 
                       required 
                       value="<?= number_format($promocao['prm_valor'], 2, ',', '.') ?>">
                <small class="text-muted">
                    Para percentual: digite apenas o número (ex: 15 para 15%)<br>
                    Para valor: use vírgula para decimais (ex: 10,50)
                </small>
            </div>

            <div class="form-group">
                <label for="prm_data_inicio">Data de Início:</label>
                <input type="date" 
                       id="prm_data_inicio" 
                       name="prm_data_inicio" 
                       class="form-control" 
                       required
                       value="<?= $promocao['prm_data_inicio'] ?>">
            </div>

            <div class="form-group">
                <label for="prm_data_fim">Data de Fim:</label>
                <input type="date" 
                       id="prm_data_fim" 
                       name="prm_data_fim" 
                       class="form-control" 
                       required
                       value="<?= $promocao['prm_data_fim'] ?>">
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" 
                           name="ativo" 
                           value="1" 
                           <?= $promocao['ativo'] ? 'checked' : '' ?>>
                    Promoção ativa (habilitada)
                </label>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="icon-save"></i> Salvar Alterações
                </button>
                <a href="/promocoes" class="btn btn-secondary">Cancelar</a>
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