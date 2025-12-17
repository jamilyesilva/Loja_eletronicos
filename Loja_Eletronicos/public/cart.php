<?php
session_start();
require_once __DIR__ . '/../includes/conexao.php';
$cart = $_SESSION['cart'] ?? [];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carrinho | EletroSys</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body class="page-produtos pt-5">
<?php include '../includes/navbar.php';?>

<div class="container my-5">
  <h2 class="fw-bold mb-4">Meu Carrinho</h2>

  <?php if (isset($_GET['added'])): ?>
    <div class="alert alert-success">Produto adicionado ao carrinho.</div>
  <?php endif; ?>
  <?php if (isset($_GET['removed'])): ?>
    <div class="alert alert-success">Produto removido do carrinho.</div>
  <?php endif; ?>
  <?php if (isset($_GET['decreased'])): ?>
    <div class="alert alert-success">Quantidade reduzida em 1.</div>
  <?php endif; ?>
  <?php if (isset($_GET['out_of_stock'])): ?>
    <div class="alert alert-danger">Produto indisponível ou estoque insuficiente.</div>
  <?php endif; ?>
  <?php if (isset($_GET['sucess'])): ?>
    <div class="alert alert-success">Pedido realizado com sucesso. Obrigado!</div>
  <?php elseif (isset($_GET['ordered']) && $_GET['ordered'] == '0'): ?>
  <?php endif; ?>
  <?php if (isset($_GET['need_login'])): ?>
    <div class="alert alert-warning">Você precisa entrar para finalizar o pedido.</div>
  <?php endif; ?>

  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>Produto</th>
          <th>Quantidade</th>
          <th>Preço</th>
          <th>Total</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
        $grandTotal = 0.0;
        if (empty($cart)) {
          echo '<tr><td colspan="5">Seu carrinho está vazio.</td></tr>';
        } else {
          foreach ($cart as $item) {
            $lineTotal = $item['quant'] * (float)$item['valor'];
            $grandTotal += $lineTotal;
            ?>
            <tr>
              <td><?= htmlspecialchars($item['p_name']) ?></td>
              <td>
                <?php
                  $stock = 0;
                  try {
                    $stmtStock = $conn->prepare('SELECT quant FROM products WHERE cod_product = :id');
                    $stmtStock->bindValue(':id', (int)$item['cod_product'], PDO::PARAM_INT);
                    $stmtStock->execute();
                    $sr = $stmtStock->fetch(PDO::FETCH_ASSOC);
                    $stock = $sr ? (int)$sr['quant'] : 0;
                  } catch (PDOException $e) {
                    $stock = 0;
                  }
                ?>
                <div class="d-flex align-items-center gap-2">
                  <form action="../actions/remove_from_cart.php" method="POST" class="m-0 p-0">
                    <input type="hidden" name="cod_product" value="<?= (int)$item['cod_product'] ?>">
                    <input type="hidden" name="decrease" value="1">
                    <button type="submit" class="btn btn-sm btn-outline-secondary">-</button>
                  </form>
                  <span><?= (int)$item['quant'] ?></span>
                  <?php if ($stock > 0 && (int)$item['quant'] < $stock): ?>
                    <form action="../actions/add_to_cart.php" method="POST" class="m-0 p-0">
                      <input type="hidden" name="cod_product" value="<?= (int)$item['cod_product'] ?>">
                      <button type="submit" class="btn btn-sm btn-outline-secondary">+</button>
                    </form>
                  <?php else: ?>
                    <button class="btn btn-sm btn-outline-secondary" disabled title="Sem mais estoque">+</button>
                  <?php endif; ?>
                </div>
              </td>
              <td>R$ <?= number_format((float)$item['valor'], 2, ',', '.') ?></td>
              <td>R$ <?= number_format($lineTotal, 2, ',', '.') ?></td>
              <td>
                <form action="../actions/remove_from_cart.php" method="POST" onsubmit="return confirm('Remover este item do carrinho?');">
                  <input type="hidden" name="cod_product" value="<?= (int)$item['cod_product'] ?>">
                  <button class="btn btn-sm btn-outline-danger">Excluir</button>
                </form>
              </td>
            </tr>
            <?php
          }
        }
        ?>
      </tbody>
    </table>
  </div>

  <div class="text-end mt-3">
    <h5 class="fw-bold">Total: R$ <?= number_format($grandTotal, 2, ',', '.') ?></h5>
    <form action="../actions/finalizar_pedido.php" method="POST" class="d-inline">
      <button type="submit" class="btn btn-dark btn-sm rounded-pill mt-2" <?= empty($cart) ? 'disabled' : '' ?>>
        Finalizar Pedido
      </button>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="../script.js"></script>
</body>
</html>
