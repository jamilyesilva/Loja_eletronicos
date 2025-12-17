<?php
require_once __DIR__ . '/../includes/protect_adm.php';
require_once __DIR__ . '/../includes/conexao.php';

if (!isset($_GET['edit'])) {
    header('Location: produtos.php');
    exit;
}

$id = (int) $_GET['edit'];
$stmt = $conn->prepare('SELECT * FROM products WHERE cod_product = :id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) {
    header('Location: produtos.php');
    exit;
}

$error_msg = '';
if (isset($_GET['error']) && $_GET['error'] === 'invalid_image') {
    $error_msg = 'Tipo de imagem inválido.';
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Editar Produto | EletroSys</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../style.css">
</head>

<body class="page-auth pt-5">
<?php include '../includes/navbar_adm.php';?>
  <fieldset class="shadow">

    <h3 class="text-center mb-3">Editar Produto</h3>

    <?php if ($error_msg): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error_msg) ?></div>
    <?php endif; ?>

    <form action="../actions/editar_prod.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="cod_product" value="<?= (int)$product['cod_product'] ?>">
        <input type="hidden" name="old_image" value="<?= htmlspecialchars($product['image'] ?? '') ?>">

        <div class="mb-3 text-center">
            <label class="form-label fw-semibold">Imagem do produto</label>

            <div class="image-upload-box  shadow mx-auto">
                <input type="file" id="image-input" name="image" accept="image/*">
                <img id="img-preview" class="img-preview" alt="Preview">
                <span class="form-label fw-semibold" id="image-label">Selecionar imagem</span>
            </div>

            <div class="mb-2 text-muted small mt-2">Deixe em branco para manter a imagem atual.</div>
        </div>

        <div class="mb-2">
            <label class="form-label-sm">Nome do produto</label>
            <input type="text" name="p_name" class="form-control form-control-sm" autocomplete="off" value="<?= htmlspecialchars($product['p_name']) ?>" required>
        </div>

        <div class="mb-2">
            <label class="form-label-sm">Descrição</label>
            <input type="text" name="description" class="form-control form-control-sm" autocomplete="off" value="<?= htmlspecialchars($product['description']) ?>" required>
        </div>

        <div class="mb-2">
            <label class="form-label-sm">Preço (R$)</label>
            <input type="number" step="0.01" name="valor" class="form-control form-control-sm" autocomplete="off" value="<?= htmlspecialchars($product['valor']) ?>" required>
        </div>

        <div class="mb-2">
            <label class="form-label-sm">Quantidade em estoque</label>
            <input type="number" name="quant" class="form-control form-control-sm" value="<?= htmlspecialchars($product['quant']) ?>" required>
        </div>

        <div class="d-grid gap-2 mb-3 justify-content-center">
            <button type="submit" class="btn btn-dark rounded-pill w-100">Atualizar Produto</button>
        </div>
    </form>
  </fieldset>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="../script.js"></script>
</body>
</html>