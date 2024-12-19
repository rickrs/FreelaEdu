<?php
// Verifica o tipo de usuário (admin ou usuario)
$tipo_usuario = $_SESSION['tipo_usuario'];
?>

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="home.php">
        <div class="sidebar-brand-icon">
            <!-- Exibe o logo -->
            <img src="https://hisense.com.br/wp-content/uploads/2024/03/Vector2.svg" alt="Logo" style="width: 150px; height: 150px;">
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="home.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Menu para Administradores -->
    <?php if ($tipo_usuario == 'admin') : ?>
        <!-- Heading -->
        <div class="sidebar-heading">
            Gerenciamento
        </div>

        <!-- Nav Item - Gerenciar Usuários -->
        <li class="nav-item">
            <a class="nav-link" href="usuarios.php">
                <i class="fas fa-fw fa-user"></i>
                <span>Usuários</span>
            </a>
        </li>
        
        <!-- Nav Item - Gerenciar Lojas -->
        <li class="nav-item">
            <a class="nav-link" href="lojas.php">
                <i class="fas fa-fw fa-store"></i>
                <span>Lojas</span>
            </a>
        </li>

        <!-- Nav Item - Gerenciar Categorias -->
        <li class="nav-item">
            <a class="nav-link" href="categorias.php">
                <i class="fas fa-fw fa-tags"></i>
                <span>Categorias</span>
            </a>
        </li>

        <!-- Nav Item - Gerenciar Detalhes de Categorias -->
        <li class="nav-item">
            <a class="nav-link" href="categorias_detalhe.php">
                <i class="fas fa-fw fa-th-list"></i>
                <span>Detalhes de Categorias</span>
            </a>
        </li>

        <!-- Nav Item - Gerenciar Produtos -->
        <li class="nav-item">
            <a class="nav-link" href="produtos.php">
                <i class="fas fa-fw fa-box"></i>
                <span>Produtos</span>
            </a>
        </li>

        <!-- Nav Item - Checklists Enviados -->
        <li class="nav-item">
            <a class="nav-link" href="visualizar_checklist.php">
                <i class="fas fa-fw fa-list"></i>
                <span>Checklists Enviados</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

    <?php else : ?>
        <!-- Menu para usuários comuns -->
        <div class="sidebar-heading">
            Minhas Ações
        </div>

        <!-- Nav Item - Visualizar Checklists -->
        <li class="nav-item">
            <a class="nav-link" href="visualizar_checklist.php">
                <i class="fas fa-fw fa-list"></i>
                <span>Meus Checklists</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">
    <?php endif; ?>

    <!-- Nav Item - Logout -->
    <li class="nav-item">
        <a class="nav-link" href="logout.php">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </li>

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
