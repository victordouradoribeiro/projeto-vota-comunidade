<?php
include 'auth.php';
include '../config/conexao.php';

include '../includes/header.php'; // seu header
include '../includes/navbar-admin.php'; // sua navbar
?>

<style>
    .form-select {
        border-radius: 8px;
        font-weight: 500;
        min-width: 200px;
    }
    .card-header {
        background-color: #4338CA !important;
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        font-weight: 600;
        padding: 1rem 1.25rem;
        border-top-left-radius: 0.25rem;
        border-top-right-radius: 0.25rem;
        color: #fff;
    }
    .progress-bar {
        background-color: #4338CA !important;
    }
    .card-body .d-flex.align-items-center > span {
        white-space: nowrap;
    }
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
        margin-bottom: 1.5rem;
        border-radius: 0.25rem;
        border: 1px solid #dee2e6;
    }
</style>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Resultados das Votações</h2>
        <div class="d-flex align-items-center">
            <p class="mb-0 me-2">Condomínio:</p>
            <select id="condominioSelect" class="form-select"></select>
        </div>
    </div>

    <div id="pautasContainer"></div>
</div>

<script>
async function carregarResultados(condominio_id = 0) {
    const url = '../php_action/read-resultados.php?condominio_id=' + condominio_id;
    const res = await fetch(url);
    const data = await res.json();

    const select = document.getElementById('condominioSelect');
    select.innerHTML = '';

    data.condominios.forEach(c => {
        const option = document.createElement('option');
        option.value = c.id;
        option.textContent = c.nome;
        select.appendChild(option);
    });

    // Se não passar ID, seleciona o primeiro (se houver)
    if (condominio_id === 0 && data.condominios.length > 0) {
        condominio_id = data.condominios[0].id;
        select.value = condominio_id;
    } else {
        select.value = condominio_id;
    }

    mostrarPautas(data.pautas);
}

function mostrarPautas(pautas) {
    const container = document.getElementById('pautasContainer');
    container.innerHTML = '';

    if (pautas.length === 0) {
        container.innerHTML = '<p class="text-muted">Nenhuma votação encontrada para este condomínio.</p>';
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

document.getElementById('condominioSelect').addEventListener('change', function() {
    carregarResultados(this.value);
});

// Carrega inicialmente com o primeiro condomínio
carregarResultados();

</script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<?php include '../includes/footer.php'; ?>