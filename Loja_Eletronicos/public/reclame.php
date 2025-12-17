<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reclame Aqui | EletroSys</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body class="pt-5 d-flex align-items-center justify-content-center min-vh-100 bg-light "d-flex align-items-center justify-content-center min-vh-100 bg-light">

<?php include '../includes/navbar.php';?>

<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$userId = $_SESSION['cod_user'] ?? null;

require_once __DIR__ . '/../includes/conexao.php';
$pedidos = [];
$need_login = false;
try {
    if (!$userId) {
        $need_login = true;
    } else {
        $stmt = $conn->prepare('SELECT cod_pedido, data_pedido, num_pedido FROM pedido WHERE cod_user = :u ORDER BY data_pedido DESC LIMIT 50');
        $stmt->bindValue(':u', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    $pedidos = [];
}
?>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-sm p-4">
        <h2 class="fw-bold mb-4 text-center">Reclame Aqui</h2>

        <!-- Baseado na tabela chamado -->
        <?php if (isset($_GET['opened']) && $_GET['opened'] === '1'): ?>
          <div class="alert alert-success">Chamado aberto com sucesso.</div>
        <?php endif; ?>

        <?php if ($need_login): ?>
          <div class="alert alert-warning">Você precisa <a href="form_user.php">entrar</a> para registrar um chamado.</div>
        <?php else: ?>
          <?php if (empty($pedidos)): ?>
            <div class="alert alert-info">Nenhum pedido encontrado para abrir chamado.</div>
          <?php else: ?>
            <form action="../actions/abrir_chamado.php" method="POST">
              <div class="mb-3">
                <label class="form-label fw-semibold">Pedido relacionado</label>
                <select name="cod_pedido" class="form-select form-select-sm rounded-pill" required>
                  <option value="">Selecione um pedido</option>
                  <?php foreach ($pedidos as $p): ?>
                    <option value="<?= (int)$p['cod_pedido'] ?>">Pedido #<?= htmlspecialchars($p['num_pedido'])?> - <?= date('d/m/Y', strtotime($p['data_pedido'])) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">Descreva o problema</label>
                <textarea name="problem" class="form-control rounded-4" rows="4" placeholder="Explique o ocorrido" required></textarea>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-dark btn-sm rounded-pill fw-bold">Abrir Chamado</button>
              </div>
            </form>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="../script.js"></script>
</body>
</html>
