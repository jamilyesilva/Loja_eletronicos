<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$logado = isset($_SESSION['cod_user']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 fixed-top">
  <a class="navbar-brand fw-bold" >EletroSys</a>
  <div class="ms-auto dropdown">
    <button class="btn btn-link text-light p-0 m-0 dropdown-toggle admin-menu-btn" id="adminMenuBtn" 
            type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menu Administrativo">
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
      <li><a class="dropdown-item" href="produtos.php"><i class="bi bi-shop me-2"></i> Produtos</a></li>
      <li><a class="dropdown-item" href="cart.php"><i class="bi bi-cart me-2"></i>Carrinho</a></li>
      <li><a class="dropdown-item" href="reclame.php"><i class="bi bi-exclamation-circle me-2"></i>Reclame aqui</a></li>
      <li>
        <?php if ($logado): ?>
          <a class="dropdown-item text-danger" href="../actions/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
        <?php else: ?>
          <a class="dropdown-item text-primary" href="index.php"><i class="bi bi-box-arrow-in-right me-2"></i>Logar</a>
        <?php endif; ?>
      </li>
    </ul>
  </div>
</nav>
