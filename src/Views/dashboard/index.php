<?php
$config = require __DIR__ . '/../../../config/app.php';
$title = 'Dashboard';
$pageTitle = 'Encomendas do Chef - Gestor';

ob_start();
?>

<style>
    .dashboard-container {
        margin-left: 400px; 
        margin-top: 60px; 
        padding: 20px;
        background-color: #F4C430; 
        min-height: calc(100vh - 60px); 
        box-sizing: border-box;
        overflow-y: auto; 
        position: relative;
        z-index: 800; 
        box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1); 
    }

    .cards {
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 30px;
        background: linear-gradient(135deg, #fff, #f9f9f9);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05); 
    }

    .card {
        background-color: #f7d571ff;
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 20px;
        width: 1000px;
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px); 
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }

    .card h3 {
        color: #C0392B; 
        font-size: 1.1em;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .card p {
        color: #333;
        font-size: 1.4em;
        font-weight: bold;
        margin: 0;
    }

    .dashboard-section {
        background: linear-gradient(135deg, #fff, #fafafa); 
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        margin-top: 20px;
    }

    .dashboard-section h2 {
        color: #C0392B; 
        font-size: 1.6em;
        font-weight: 700;
        margin-bottom: 15px;
        text-transform: uppercase;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .table th,
    .table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #eee;
        transition: background-color 0.3s ease;
    }

    .table th {
        background-color: #f4c430; 
        color: #fff;
        font-weight: 600;
        text-transform: uppercase;
    }

    .table tr:hover td {
        background-color: #f9f9f9; 
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 12px;
        color: #fff;
        font-weight: 500;
        font-size: 0.9em;
    }

    .status-badge.status-pendente {
        background-color: #E67E22; 
    }

    .status-badge.status-concluido {
        background-color: #27AE60; 
    }

    .status-badge.status-cancelado {
        background-color: #E74C3C; 
    }

    .table a {
        color: #f4c430; 
        text-decoration: none;
        font-weight: 500;
    }

    .table a:hover {
        text-decoration: underline;
        color: #e0b12a; 
    }
</style>

<div class="dashboard-container">
 
    <div class="cards">
        <div class="card">
            <h3>📦 Pedidos para Entregar Hoje</h3>
            <p id="count-total"><?= $stats['total_pedidos'] ?></p>
        </div>
        <div class="card">
            <h3>⏳ Quantidade de pedidos na ultima hora</h3>
            <p id="count-pendentes"><?= $stats['pedidos_pendentes'] ?></p>
        </div>
        <div class="card">
            <h3>💰 Total Vendas (R$)</h3>
            <p id="count-vendas"><?= number_format($stats['total_vendas'], 2, ',', '.') ?></p>
        </div>
        <div class="card">
            <h3>🍽️ Pedidos Concluídos</h3>
            <p id="count-concluidos">0</p> 
        </div>
        <div class="card">
            <h3>🔄 Pedidos Cancelados</h3>
            <p id="count-cancelados">0</p> 
        </div>
        <div class="card">
            <h3>🎂 Pedidos em andamento</h3>
            <p id="count-tempo-medio">0</p> 
        </div>
    </div>

    <!-- Pedidos Recentes -->
    <div class="dashboard-section">
        <h2>Pedidos Recentes</h2>
        <?php if ($pedidos): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Valor</th>
                    <th>Status</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pd): ?>
                <tr>
                    <td><?= $pd['ped_numero'] ?></td>
                    <td><?= htmlspecialchars($pd['cli_nome']) ?></td>
                    <td>R$ <?= number_format($pd['ped_valor_total'], 2, ',', '.') ?></td>
                    <td><span class="status-badge status-<?= strtolower($pd['ped_status']) ?>"><?= htmlspecialchars($pd['ped_status']) ?></span></td>
                    <td><?= $pd['ped_data_elaboracao'] ?></td>
                    <td>
                        <a href="<?= $config['base_url'] ?>/pedidos/view/<?= $pd['ped_numero'] ?>">Ver</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p>Nenhum pedido recente.</p>
        <?php endif; ?>
    </div>
</div>

<script>

function fetchCounts() {
    fetch('<?= $config['base_url'] ?>/api/pedidos_count.php')
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                document.getElementById('count-pendentes').innerText = data.pendentes;
                document.getElementById('count-total').innerText = data.total_pedidos;
                document.getElementById('count-vendas').innerText = data.total_vendas;
                // Atualiza os novos cards (placeholders)
                document.getElementById('count-concluidos').innerText = data.concluidos || 0;
                document.getElementById('count-cancelados').innerText = data.cancelados || 0;
                document.getElementById('count-tempo-medio').innerText = data.tempo_medio || 0;
            }
        });
}
setInterval(fetchCounts, 10000);
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';