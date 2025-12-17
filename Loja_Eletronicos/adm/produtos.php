<?php require_once __DIR__ . '/../includes/protect_adm.php';?>
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

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0 fw-bold">Produtos disponíveis</h2>
    <a href="form_prod.php" class="btn btn-primary btn-sm rounded-pill">Adicionar Produto</a>
  </div>

  <div class="row g-4">
    <?php
    require_once __DIR__ . '/../includes/conexao.php';
    try {
      $stmt = $conn->query('SELECT * FROM products ORDER BY cod_product DESC');
      $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log('Erro ao buscar produtos: ' . $e->getMessage());
      echo '<div class="alert alert-danger">Erro ao buscar produtos. Tente novamente mais tarde.</div>';
      $products = [];
    }

    if (empty($products)) {
      echo '<div class="col-12"><p>Nenhum produto cadastrado.</p></div>';
    } else {
      foreach ($products as $p) {
        if (!empty($p['image']) && file_exists(__DIR__ . '/../public/assets/produtos/' . $p['image'])) {
          $imgPath = '../public/assets/produtos/' . htmlspecialchars($p['image']); //evita xss  
          $img = $imgPath . '?v=' . filemtime(__DIR__ . '/../public/assets/produtos/' . $p['image']);
        } else {
          $img = 'https://via.placeholder.com/300x200?text=Sem+imagem';
        }
        $price = number_format((float)$p['valor'], 2, ',', '.');
        ?>
        <div class="col-md-4">
          <div class="card h-100 shadow-sm">
            <?php if (!empty($p['image']) && file_exists(__DIR__ . '/../public/assets/produtos/' . $p['image'])): ?>
              <img src="<?= $img ?>" class="card-img-top product-image" alt="<?= htmlspecialchars($p['p_name']) ?>">
            <?php else: ?>
              <img src="https://via.placeholder.com/400x220?text=Sem+imagem" class="card-img-top product-image" alt="Sem imagem">
            <?php endif; ?>
            <div class="card-body d-flex flex-column">
              <h5 class="card-title fw-semibold"><?= htmlspecialchars($p['p_name']) ?></h5>
              <p class="card-text small"><?= htmlspecialchars($p['description']) ?></p>
              <p class="fw-bold mb-2">R$ <?= $price ?></p>
              <p class="small text-muted mb-3">Quantidade em estoque: <?= (int)$p['quant'] ?></p>

              <div class="mt-auto d-flex">
                <a href="editar_prod_form.php?edit=<?= (int)$p['cod_product'] ?>" class="btn btn-outline-secondary btn-sm me-2">Editar</a>

                <form action="../actions/deletar_prod.php" method="POST" onsubmit="return confirm('Confirma exclusão deste produto?');">
                  <input type="hidden" name="cod_product" value="<?= (int)$p['cod_product'] ?>">
                  <input type="hidden" name="image" value="<?= htmlspecialchars($p['image']) ?>">
                  <button class="btn btn-danger btn-sm">Excluir</button>
                </form>
              </div>
            </div>
          </div>
        </div>
        <?php
      }
    }
    ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="../script.js"></script>
</body>
</html>
