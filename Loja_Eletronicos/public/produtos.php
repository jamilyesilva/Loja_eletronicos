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
<?php include_once __DIR__ . '/../includes/navbar.php';?>

<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$userName = $_SESSION['name'] ?? null;
?>

<div class="container my-5">
  <?php if ($userName): ?>
    <h2 class="lead fw-bold">Olá, <?= htmlspecialchars($userName) ?>!</h2>
    <h3 class="mb-4 fw-bold">Produtos disponíveis</h3>
  <?php else: ?>
    <p class="lead">Olá!</p>
  <?php endif; ?>


  <?php if (isset($_GET['added'])): ?>
    <div class="alert alert-success">Produto adicionado ao carrinho.</div>
  <?php endif; ?>

  <?php if (isset($_GET['out_of_stock'])): ?>
    <div class="alert alert-danger">Produto sem estoque ou quantidade insuficiente no momento.</div>
  <?php endif; ?>

    <?php
    require_once __DIR__ . '/../includes/conexao.php';
    try {
      $stmt = $conn->query('SELECT * FROM products ORDER BY cod_product DESC');
      $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo '<div class="alert alert-danger">Erro ao buscar produtos: ' . htmlspecialchars($e->getMessage()) . '</div>';
      $products = [];
    }

    if (empty($products)) {
      echo '<div class="col-12"><p>Nenhum produto cadastrado.</p></div>';
    } else {
      echo '<div class="row g-4">';
      foreach ($products as $p) {
        if (!empty($p['image']) && file_exists(__DIR__ . '/../public/assets/produtos/' . $p['image'])) {
          $imgPath = '../public/assets/produtos/' . htmlspecialchars($p['image']);
          $img = $imgPath . '?v=' . filemtime(__DIR__ . '/../public/assets/produtos/' . $p['image']);
        } else {
          $img = 'https://via.placeholder.com/400x220?text=Sem+imagem';
        }
        $price = number_format((float)$p['valor'], 2, ',', '.');
        ?>
        <div class="col-md-4">
          <div class="card h-100 shadow-sm">
            <img src="<?= $img ?>" class="card-img-top product-image" alt="<?= htmlspecialchars($p['p_name']) ?>">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title fw-semibold"><?= htmlspecialchars($p['p_name']) ?></h5>
              <p class="card-text small"><?= htmlspecialchars($p['description']) ?></p>
              <p class="fw-bold mb-3">R$ <?= $price ?></p>

              <?php if ((int)$p['quant'] > 0): ?>
                <form action="../actions/add_to_cart.php" method="POST" class="mt-auto">
                  <input type="hidden" name="cod_product" value="<?= (int)$p['cod_product'] ?>">
                  <input type="hidden" name="valor" value="<?= htmlspecialchars($p['valor']) ?>">
                  <button class="btn btn-dark btn-sm rounded-pill w-100">Adicionar ao carrinho</button>
                </form>
              <?php else: ?>
                <button class="btn btn-secondary btn-sm rounded-pill w-100" disabled>Produto indisponível</button>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <?php
      }
      echo '</div>'; 
    }
    ?>

  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="../script.js"></script>
</body>
</html>