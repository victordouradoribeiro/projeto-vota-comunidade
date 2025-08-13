<?php
session_start(); // garante que a sessão está ativa
include '../auth.php';
include '../config/conexao.php';

// Pega o ID do condomínio do síndico logado
$idSindico = $_SESSION['id_usuario'];

// var_dump($_SESSION);
// var_dump($_SESSION['usuario']);
// print("\n\n\n\ID do usuário: " . $_SESSION['id_usuario'] . "\n"); // Debugging
// print("\n\n\n\ID do usuário: " . $_SESSION['usuario'] . "\n"); // Debugging
// print("\n\n\n\ID do Síndico: $idSindico"); // Debugging

if ($idSindico > 0) {
    $sqlCond = "SELECT id_condominio FROM usuarios WHERE codigo = $idSindico LIMIT 1";
    $resCond = mysqli_query($conn, $sqlCond);
    $condominio = mysqli_fetch_assoc($resCond);
    $idCondominio = $condominio['id_condominio'] ?? 0;
} else {
    $idCondominio = -1;
    print("Fallback");
}

include '../includes/header.php';
include '../includes/navbar-sindico.php'; // navbar do síndico

?>

<style>
    .card-header {
        background-color: #4338CA !important;
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        font-weight: 600;
        color: #fff;
    }
    .progress-bar { background-color: #4338CA !important; }
    .card-body .d-flex.align-items-center > span { white-space: nowrap; }
    .card { box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075); margin-bottom: 1.5rem; border-radius: 0.25rem; border: 1px solid #dee2e6; }
</style>

<div class="container mt-4">
    <h2 class="mb-4">Resultados das Votações</h2>
    <div id="pautasContainer"></div>
</div>

<script>
async function carregarResultados() {
    const url = '../php_action/read-resultados.php?condominio_id=<?= $idCondominio ?>';
    const res = await fetch(url);
    const data = await res.json();
    mostrarPautas(data.pautas);
}

function mostrarPautas(pautas) {
    const container = document.getElementById('pautasContainer');
    container.innerHTML = '';

    if (pautas.length === 0) {
        container.innerHTML = '<p class="text-muted">Nenhuma votação encontrada para o seu condomínio.</p>';
        return;
    }

    pautas.forEach(pauta => {
        const totalVotos = pauta.total_votos;
        const descricao = pauta.descricao ? pauta.descricao : '';
        let optionsHTML = '';

        pauta.opcoes.forEach(opcao => {
            const votos = parseInt(opcao.votos);
            const porcentagem = totalVotos > 0 ? ((votos / totalVotos) * 100).toFixed(1) : 0;
            optionsHTML += `
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <p class="mb-0 me-2">${opcao.descricao}</p>
                        <div class="d-flex align-items-center">
                            <span class="me-2 text-end" style="min-width: 45px;"><strong>${porcentagem}%</strong></span>
                            <span class="text-muted text-end" style="min-width: 50px;">${votos} votos</span>
                        </div>
                    </div>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar" role="progressbar" style="width: ${porcentagem}%;" aria-valuenow="${porcentagem}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            `;
        });

        const cardHTML = `
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">${pauta.titulo}</h5>
                    <span>Total de votos: <strong>${totalVotos}</strong></span>
                </div>
                <div class="card-body">
                    <p class="card-text mb-3">${descricao}</p>
                    ${optionsHTML}
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', cardHTML);
    });
}

// Carrega resultados automaticamente do condomínio do síndico
carregarResultados();
</script>

<?php include '../includes/footer.php'; ?>