<?php require_once __DIR__ . '/../includes/protect_adm.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> EletroSys</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body class="pt-5">
<?php include '../includes/navbar_adm.php';?>

<?php
require_once __DIR__ . '/../includes/conexao.php';
try {
    // contar somente usuários do tipo 'user' (excluir admins)
    $totalUsers = (int) $conn->query("SELECT COUNT(*) FROM usuarios WHERE type_user = 'user'")->fetchColumn();
    $totalProducts = (int) $conn->query('SELECT COUNT(*) FROM products')->fetchColumn();
    $totalPedidos = (int) $conn->query('SELECT COUNT(*) FROM pedido')->fetchColumn();
    $totalChamados = (int) $conn->query('SELECT COUNT(*) FROM chamado')->fetchColumn();

    $stmt = $conn->query("SELECT p.cod_pedido, p.num_pedido, p.data_pedido, u.name AS cliente FROM pedido p LEFT JOIN usuarios u ON p.cod_user = u.cod_user ORDER BY p.data_pedido DESC LIMIT 8");
    $ultimos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt2 = $conn->query("SELECT c.cod_chamado, c.data_abertura, c.problem, p.num_pedido,  u.name AS cliente FROM chamado c JOIN pedido p ON c.cod_pedido = p.cod_pedido JOIN usuarios u ON p.cod_user = u.cod_user ORDER BY c.data_abertura DESC LIMIT 5");
    $chamados = $stmt2->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = $e->getMessage();
}
?>



<h2 class="fw-bold mb-4">Dashboard</h2>

<div class="row g-4">
  <div class="col-md-3">
    <div class="card shadow-sm text-center">
      <div class="card-body">
        <i class="bi bi-people fs-1"></i>
        <h6 class="mt-2">Usuários</h6>
        <h3 class="fw-bold"><?= $totalUsers ?></h3>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card shadow-sm text-center">
      <div class="card-body">
        <i class="bi bi-box-seam fs-1"></i>
        <h6 class="mt-2">Produtos</h6>
        <h3 class="fw-bold"><?= $totalProducts ?></h3>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card shadow-sm text-center">
      <div class="card-body">
        <i class="bi bi-receipt fs-1"></i>
        <h6 class="mt-2">Pedidos</h6>
        <h3 class="fw-bold"><?= $totalPedidos ?></h3>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card shadow-sm text-center">
      <div class="card-body">
        <i class="bi bi-exclamation-circle fs-1"></i>
        <h6 class="mt-2">Chamados</h6>
        <h3 class="fw-bold"><?= $totalChamados ?></h3>
      </div>
    </div>
  </div>
</div>

<div class="row g-4 mt-4">
  <!-- Últimos pedidos -->
  <div class="col-lg-7">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="fw-bold mb-3">Últimos Pedidos</h5>
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead>
              <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Data</th>
              </tr>
            </thead>
            <tbody>
                <?php if (!empty($ultimos)): ?>
                  <?php foreach ($ultimos as $u): ?>
                    <tr>
                      <td><?= htmlspecialchars($u['num_pedido']) ?></td>
                      <td><?= htmlspecialchars($u['cliente'] ?? '—') ?></td>
                      <td><?= date('d/m/Y H:i', strtotime($u['data_pedido'])) ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="3">Nenhum pedido encontrado.</td></tr>
                <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Chamados recentes -->
  <div class="col-lg-5">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="fw-bold mb-3">Chamados Recentes</h5>
        <ul class="list-group list-group-flush">
            <?php if (!empty($chamados)): ?>
              <?php foreach ($chamados as $c): ?>
                <li class="list-group-item">
                  Pedido #<?= htmlspecialchars(mb_strimwidth($c['problem'], 0, 60, '...')) ?>
                  <small class="text-muted float-end"><?= htmlspecialchars($c['cliente']) ?> • <?= date('d/m/Y H:i', strtotime($c['data_abertura'])) ?></small>
                </li>
              <?php endforeach; ?>
            <?php else: ?>
              <li class="list-group-item">Nenhum chamado encontrado.</li>
            <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="../script.js"></script>

</body>
</html>