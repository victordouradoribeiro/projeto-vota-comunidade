<?php
session_start();
include 'auth.php'; // deve garantir session_start() e perfil == 3
include '../config/conexao.php';

$id_usuario = $_SESSION['id_usuario'];

$stmt = $conn->prepare("SELECT id_condominio FROM usuarios WHERE codigo = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

$id_condominio = $result['id_condominio'] ?? 0;

// Busca pautas ativas do condomínio
$sql = "
    SELECT p.id, p.titulo, p.descricao
    FROM pautas p
    JOIN usuarios s ON s.codigo = p.id_sindico
    WHERE p.status = 'ativa' AND s.id_condominio = ?
    ORDER BY p.data_inicio DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_condominio);
$stmt->execute();
$pautas = $stmt->get_result();
include '../includes/header.php';
include '../includes/navbar-morador.php';
?>
<div class="container py-4">

<h2 class="mb-4">Votações em Aberto</h2>

<?php while ($pauta = $pautas->fetch_assoc()): ?>
    <?php
    // Verifica se o usuário já votou nessa pauta
    $stmtVoto = $conn->prepare("SELECT 1 FROM votos WHERE id_usuario = ? AND id_pauta = ?");
    $stmtVoto->bind_param("ii", $id_usuario, $pauta['id']);
    $stmtVoto->execute();
    $jaVotou = $stmtVoto->get_result()->num_rows > 0;

    // Busca opções de voto
    $stmtOpcoes = $conn->prepare("SELECT id, descricao FROM opcoes_voto WHERE id_pauta = ?");
    $stmtOpcoes->bind_param("i", $pauta['id']);
    $stmtOpcoes->execute();
    $opcoes = $stmtOpcoes->get_result();
    ?>
    <div class="card shadow-sm mb-5">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($pauta['titulo']) ?></h5>
            <p class="card-text mb-3"><?= htmlspecialchars($pauta['descricao']) ?></p>

            <div class="list-group list-group-flush mb-4" data-votacao="<?= $pauta['id'] ?>">
                <?php while ($opcao = $opcoes->fetch_assoc()): ?>
                    <a href="#" class="list-group-item list-group-item-action list-option" data-opcao="<?= $opcao['id'] ?>">
                        <?= htmlspecialchars($opcao['descricao']) ?>
                    </a>
                <?php endwhile; ?>
            </div>

            <div class="d-grid gap-2">
                <button id="btn-votar-<?= $pauta['id'] ?>" 
                        class="btn btn-dark btn-votar" 
                        <?= $jaVotou ? 'disabled' : '' ?>>
                    <?= $jaVotou ? 'Voto já registrado' : 'Votar' ?>
                </button>
            </div>
        </div>
    </div>
<?php endwhile; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.list-group[data-votacao]').forEach(listGroup => {
        const votarButton = listGroup.closest('.card-body').querySelector('.btn-votar');
        let selectedOption = null;

        listGroup.addEventListener('click', function(e) {
            e.preventDefault();
            const option = e.target.closest('.list-group-item');
            if (!option || votarButton.disabled) return;

            listGroup.querySelectorAll('.list-group-item').forEach(item => item.classList.remove('active'));
            option.classList.add('active');
            selectedOption = option.dataset.opcao;

            votarButton.disabled = false;
            votarButton.classList.remove('btn-dark');
            votarButton.classList.add('btn-primary-custom');
        });

        votarButton.addEventListener('click', function() {
            if (!selectedOption) {
                alert('Selecione uma opção antes de votar.');
                return;
            }

            fetch('../php_action/registrar-voto.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `id_pauta=${listGroup.dataset.votacao}&id_opcao=${selectedOption}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    votarButton.textContent = 'Voto registrado com sucesso';
                    votarButton.classList.remove('btn-primary-custom');
                    votarButton.classList.add('btn-success');
                    votarButton.disabled = true;
                    listGroup.querySelectorAll('.list-group-item').forEach(item => item.style.pointerEvents = 'none');
                } else {
                    alert(data.message || 'Erro ao registrar voto.');
                }
            });
        });
    });
});
</script>
</div>

<style>
    .list-group-item {
        cursor: pointer;
        border-radius: 8px;
        margin-bottom: 8px;
        padding: 1rem 1.25rem;
        transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
        font-weight: 500;
        border: 1px solid #dee2e6; /* Adiciona uma borda sutil */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); /* Sombra mais sutil por padrão */
    }
    
    .list-group-item:hover {
        background-color: #f8f9fa;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra mais forte no hover */
        border-color: #007bff; /* Borda destacada no hover */
    }

    .list-group-item.active {
        background-color: #4338CA;
        color: #fff;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(67, 56, 202, 0.4); /* Sombra mais forte e colorida para a opção ativa */
        border-color: #4338CA;
        transform: translateY(-2px); /* Efeito de elevação sutil */
    }

    .btn-dark { background-color: #343a40; border-color: #343a40; }
    .btn-dark:disabled { opacity: 0.65; cursor: not-allowed; }
    .btn-primary-custom { background-color: #4338CA; border-color: #4338CA; }
    .btn-primary-custom:hover { background-color: #3f31b8; }
    .btn-success { background-color: #28a745 !important; border-color: #28a745 !important; }

    /* Remove a borda padrão do list-group-flush para evitar duplicação */
    .list-group-flush .list-group-item:first-child { border-top-left-radius: 8px; border-top-right-radius: 8px; }
    .list-group-flush .list-group-item:last-child { border-bottom-left-radius: 8px; border-bottom-right-radius: 8px; margin-bottom: 0; }
</style>

<?php include '../includes/footer.php'; ?>