<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/graficos.php';

$mes = $_GET['mes'] ?? date('Y-m');
$tipo = $_GET['tipo'] ?? 'pizza';

if ($tipo === 'pizza') {
    $dados = gastosPorCategoria($mes);
    echo json_encode([
        'labels' => array_column($dados, 'categoria'),
        'valores' => array_map(fn($d) => floatval($d['total']), $dados),
    ]);
    exit;
}

if ($tipo === 'barras') {
    $dados = fluxoUltimosMeses();
    $meses = [];
    foreach ($dados as $linha) {
        $meses[$linha['mes']][$linha['tipo']] = floatval($linha['total']);
    }

    $labels    = array_keys($meses);
    $entradas  = array_map(fn($m) => $meses[$m]['entrada'] ?? 0, $labels);
    $saidas    = array_map(fn($m) => $meses[$m]['saida']   ?? 0, $labels);

    echo json_encode(compact('labels', 'entradas', 'saidas'));
    exit;
}

echo json_encode(['erro' => 'Tipo inválido']);