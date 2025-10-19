<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/helpers.php';
require_login();

// exemplos de métricas
$stmt = $pdo->query("SELECT COUNT(*) as total_pedidos FROM pedidos");
$totalPedidos = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COALESCE(SUM(ped_valor_total),0) as total_vendas FROM pedidos WHERE ped_status != 'cancelado'");
$totalVendas = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) as pendentes FROM pedidos WHERE ped_status = 'pendente'");
$pendentes = $stmt->fetchColumn();
?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<title>Painel - Gestor</title>
<link rel="stylesheet" href="assets/css/styles.css">
<script>
  // Polling simples para pedidos novos (cada 10s)
  function fetchCounts(){
    fetch('api/pedidos_count.php').then(r=>r.json()).then(data=>{
      if (data.ok) {
        document.getElementById('count-pendentes').innerText = data.pendentes;
        document.getElementById('count-total').innerText = data.total_pedidos;
        document.getElementById('count-vendas').innerText = data.total_vendas;
      }
    });
  }
  setInterval(fetchCounts, 10000);
  window.onload = fetchCounts;
</script>
</head>
<body>
  <header class="topbar">
    <div class="brand">Encomendas do Chef - Gestor</div>
    <div class="user">Olá, <?=htmlspecialchars($_SESSION['gestor_nome'])?> | <a href="logout.php">Sair</a></div>
  </header>
  <aside class="sidebar">
    <nav>
      <a href="dashboard.php">Dashboard</a>
      <a href="produtos/list.php">Produtos</a>
      <a href="categorias/list.php">Categorias</a>
      <a href="pedidos/list.php">Pedidos</a>
      <a href="promocoes/list.php">Promoções</a>
      <a href="relatorios/vendas.php">Relatórios</a>
    </nav>
  </aside>

  <main class="main">
    <h2>Painel</h2>
    <div class="cards">
      <div class="card">
        <h3>Total de Pedidos</h3>
        <p id="count-total"><?= $totalPedidos ?></p>
      </div>
      <div class="card">
        <h3>Pedidos Pendentes</h3>
        <p id="count-pendentes"><?= $pendentes ?></p>
      </div>
      <div class="card">
        <h3>Total Vendas (R$)</h3>
        <p id="count-vendas"><?= number_format($totalVendas,2,',','.') ?></p>
      </div>
    </div>

    <section>
      <h3>Pedidos recentes</h3>
      <?php
      $stmt = $pdo->query("SELECT p.*, c.cli_nome FROM pedidos p JOIN clientes c ON p.cli_codigo = c.cli_codigo ORDER BY p.ped_data_elaboracao DESC LIMIT 6");
      $pedidos = $stmt->fetchAll();
      if ($pedidos):
      ?>
      <table class="table">
        <thead><tr><th>#</th><th>Cliente</th><th>Valor</th><th>Status</th><th>Data</th><th>Ações</th></tr></thead>
        <tbody>
          <?php foreach($pedidos as $pd): ?>
          <tr>
            <td><?=$pd['ped_numero']?></td>
            <td><?=htmlspecialchars($pd['cli_nome'])?></td>
            <td>R$ <?=number_format($pd['ped_valor_total'],2,',','.')?></td>
            <td><?=htmlspecialchars($pd['ped_status'])?></td>
            <td><?=$pd['ped_data_elaboracao']?></td>
            <td><a href="pedidos/view.php?id=<?=$pd['ped_numero']?>">Ver</a></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php else: ?>
        <p>Nenhum pedido recente.</p>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>
