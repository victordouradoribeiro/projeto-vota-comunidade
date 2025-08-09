<nav class="navbar navbar-expand-lg" style="background-color: #4338CA;">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="/admin/dashboard.php">
            Vota Comunidade
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($currentPage === 'dashboard') ? 'active text-white' : 'text-white'; ?>" 
                       href="/admin/dashboard.php"
                       <?php if ($currentPage === 'dashboard') echo 'aria-current="page"'; ?>>
                        Início
                    </a>
                </li>
                <li class="nav-item ms-3">
                    <a class="nav-link <?php echo ($currentPage === 'sindicos') ? 'active text-white' : 'text-white'; ?>" 
                       href="/admin/gerenciar-sindicos.php"
                       <?php if ($currentPage === 'sindicos') echo 'aria-current="page"'; ?>>
                        Síndicos
                    </a>
                </li>
                <li class="nav-item ms-3">
                    <a class="nav-link <?php echo ($currentPage === 'moradores') ? 'active text-white' : 'text-white'; ?>" 
                       href="/admin/gerenciar-moradores.php"
                       <?php if ($currentPage === 'moradores') echo 'aria-current="page"'; ?>>
                        Moradores
                    </a>
                </li>
                <li class="nav-item ms-3">
                    <a class="nav-link <?php echo ($currentPage === 'condominios') ? 'active text-white' : 'text-white'; ?>" 
                       href="/admin/gerenciar-condominios.php"
                       <?php if ($currentPage === 'condominios') echo 'aria-current="page"'; ?>>
                        Condomínios
                    </a>
                </li>
                <li class="nav-item ms-3">
                    <a class="nav-link <?php echo ($currentPage === 'resultados') ? 'active text-white' : 'text-white'; ?>" 
                       href="/admin/resultados.php"
                       <?php if ($currentPage === 'resultados') echo 'aria-current="page"'; ?>>
                        Resultados
                    </a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="/public/logout.php" class="d-inline">
                        <button class="btn btn-sair btn-light ms-3" type="submit">Sair</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>