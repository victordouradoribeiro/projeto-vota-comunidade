<?php
// Recebe $currentPage (string) para marcar item ativo
// Exemplo: $currentPage = 'dashboard', 'votacoes', 'moradores', 'resultados'
?>

<nav class="navbar navbar-expand-lg" style="background-color: #4338CA;">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="/sindico/dashboard.php">
            Vota Comunidade
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavSindico" aria-controls="navbarNavSindico" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavSindico">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($currentPage === 'dashboard') ? 'active text-white' : 'text-white'; ?>" 
                       href="/sindico/dashboard.php"
                       <?php if ($currentPage === 'dashboard') echo 'aria-current="page"'; ?>>
                        Início
                    </a>
                </li>
                <li class="nav-item ms-3">
                    <a class="nav-link <?php echo ($currentPage === 'votacoes') ? 'active text-white' : 'text-white'; ?>" 
                       href="/sindico/gerenciar-votacoes.php"
                       <?php if ($currentPage === 'votacoes') echo 'aria-current="page"'; ?>>
                        Votações
                    </a>
                </li>
                <li class="nav-item ms-3">
                    <a class="nav-link <?php echo ($currentPage === 'resultados') ? 'active text-white' : 'text-white'; ?>" 
                       href="/sindico/resultados.php"
                       <?php if ($currentPage === 'resultados') echo 'aria-current="page"'; ?>>
                        Resultados
                    </a>
                </li>
                <li class="nav-item ms-3">
                    <a class="nav-link <?php echo ($currentPage === 'moradores') ? 'active text-white' : 'text-white'; ?>" 
                       href="/sindico/minha-conta.php"
                       <?php if ($currentPage === 'minha-conta') echo 'aria-current="page"'; ?>>
                        Minha Conta
                    </a>
                </li>
                <li class="nav-item ms-3">
                    <form method="POST" action="/public/logout.php" class="d-inline m-0 p-0">
                        <button class="btn btn-sair btn-light" type="submit">Sair</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>