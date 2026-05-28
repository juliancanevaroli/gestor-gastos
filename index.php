<?php
require_once 'includes/transacoes.php';

$erro = '';
$sucesso = isset($_GET['sucesso']) ? 'Transação adicionada!' : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao    = trim($_POST['descricao'] ?? '');
    $valor        = floatval($_POST['valor'] ?? 0);
    $tipo         = $_POST['tipo'] ?? '';
    $categoria_id = intval($_POST['categoria_id'] ?? 0);
    $data         = $_POST['data'] ?? '';

    if (!$descricao || !$valor || !$tipo || !$data) {
        $erro = 'Preencha todos os campos obrigatórios.';
    } else {
        $resultado = adicionarTransacao([
            'descricao'    => $descricao,
            'valor'        => $valor,
            'tipo'         => $tipo,
            'categoria_id' => $categoria_id ?: null,
            'data'         => $data,
        ]);

        if ($resultado) {
            header('Location: index.php?mes=' . date('Y-m') . '&sucesso=1');
            exit;
        } else {
            $erro = 'Erro ao salvar. Tente novamente.';
        }
    }
}

if (isset($_GET['excluir'])) {
    excluirTransacao(intval($_GET['excluir']));
    header('Location: index.php');
    exit;
}

$filtros = [
    'mes'          => $_GET['mes'] ?? date('Y-m'),
    'tipo'         => $_GET['tipo'] ?? '',
    'categoria_id' => $_GET['categoria_id'] ?? '',
];

$transacoes = listarTransacoes($filtros);
$categorias = listarCategorias();
$todas      = listarTransacoes(['mes' => $filtros['mes']]);

$total_entradas = array_sum(array_map(
    fn($t) => $t['tipo'] === 'entrada' ? floatval($t['valor']) : 0,
    $todas
));
$total_saidas = array_sum(array_map(
    fn($t) => $t['tipo'] === 'saida' ? floatval($t['valor']) : 0,
    $todas
));
$saldo = $total_entradas - $total_saidas;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Gastos</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <header>
        <h1>Gestor de Gastos</h1>
    </header>

    <div class="resumo">
        <div class="card card-entrada">
            <span class="label">Entradas</span>
            <span class="valor">R$ <?= number_format($total_entradas, 2, ',', '.') ?></span>
        </div>
        <div class="card card-saida">
            <span class="label">Saídas</span>
            <span class="valor">R$ <?= number_format($total_saidas, 2, ',', '.') ?></span>
        </div>
        <div class="card card-saldo <?= $saldo >= 0 ? 'positivo' : 'negativo' ?>">
            <span class="label">Saldo</span>
            <span class="valor">R$ <?= number_format($saldo, 2, ',', '.') ?></span>
        </div>
    </div>

    <section class="graficos-section">
    <h2>Visão geral</h2>
    <div class="graficos-grid">
        <div class="grafico-card">
            <h3>Gastos por categoria</h3>
            <canvas id="graficoPizza"></canvas>
            <p class="grafico-vazio" id="vaziopizza" style="display:none">
                Nenhuma saída neste mês.
            </p>
        </div>
        <div class="grafico-card">
            <h3>Entradas x Saídas (6 meses)</h3>
            <canvas id="graficoBarras"></canvas>
        </div>
    </div>
</section>

    <section class="form-section">
        <h2>Nova transação</h2>

        <?php if ($erro): ?>
            <p class="msg erro"><?= htmlspecialchars($erro) ?></p>
        <?php endif; ?>
        <?php if ($sucesso): ?>
            <p class="msg sucesso"><?= htmlspecialchars($sucesso) ?></p>
        <?php endif; ?>

        <form method="POST" id="form-transacao">
            <div class="form-row">
                <div class="form-group">
                    <label for="descricao">Descrição *</label>
                    <input type="text" id="descricao" name="descricao" placeholder="Ex: Almoço, Salário..." required>
                </div>
                <div class="form-group">
                    <label for="valor">Valor (R$) *</label>
                    <input type="number" id="valor" name="valor" step="0.01" min="0.01" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="tipo">Tipo *</label>
                    <select id="tipo" name="tipo" required>
                        <option value="">Selecione...</option>
                        <option value="entrada">Entrada</option>
                        <option value="saida">Saída</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="categoria_id">Categoria</label>
                    <select id="categoria_id" name="categoria_id">
                        <option value="">Sem categoria</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat['id'] ?>" data-tipo="<?= $cat['tipo'] ?>">
                                <?= htmlspecialchars($cat['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="data">Data *</label>
                    <input type="date" id="data" name="data" value="<?= date('Y-m-d') ?>" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Adicionar</button>
        </form>
    </section>

    <section class="filtros-section">
        <h2>Transações</h2>
        <form method="GET" class="filtros">
            <input type="month" name="mes" value="<?= htmlspecialchars($filtros['mes']) ?>">
            <select name="tipo">
                <option value="">Todos os tipos</option>
                <option value="entrada" <?= $filtros['tipo'] === 'entrada' ? 'selected' : '' ?>>Entradas</option>
                <option value="saida"   <?= $filtros['tipo'] === 'saida'   ? 'selected' : '' ?>>Saídas</option>
            </select>
            <select name="categoria_id">
                <option value="">Todas as categorias</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $filtros['categoria_id'] == $cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn">Filtrar</button>
            <a href="index.php" class="btn btn-link">Limpar</a>
        </form>
    </section>

    <table class="tabela">
        <thead>
            <tr>
                <th>Data</th>
                <th>Descrição</th>
                <th>Categoria</th>
                <th>Tipo</th>
                <th>Valor</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($transacoes)): ?>
                <tr>
                    <td colspan="6" class="vazio">Nenhuma transação encontrada.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($transacoes as $t): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($t['data'])) ?></td>
                        <td><?= htmlspecialchars($t['descricao']) ?></td>
                        <td><?= htmlspecialchars($t['categoria_nome'] ?? '—') ?></td>
                        <td>
                            <span class="badge <?= $t['tipo'] ?>">
                                <?= $t['tipo'] === 'entrada' ? 'Entrada' : 'Saída' ?>
                            </span>
                        </td>
                        <td class="valor-<?= $t['tipo'] ?>">
                            R$ <?= number_format($t['valor'], 2, ',', '.') ?>
                        </td>
                        <td>
                            <a href="?excluir=<?= $t['id'] ?>"
                               class="btn btn-danger"
                               onclick="return confirm('Excluir essa transação?')">
                                Excluir
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="js/app.js"></script>
</body>
</html>