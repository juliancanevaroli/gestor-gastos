<?php
require_once __DIR__ . '/../db/conexao.php';

function gastosPorCategoria($mes) {
    $conn = conectar();

    $sql = "SELECT 
                COALESCE(c.nome, 'Sem categoria') AS categoria,
                SUM(t.valor) AS total
            FROM transacoes t
            LEFT JOIN categorias c ON t.categoria_id = c.id
            WHERE t.tipo = 'saida'
              AND DATE_FORMAT(t.data, '%Y-%m') = ?
            GROUP BY c.id, c.nome
            ORDER BY total DESC";

 $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $mes);
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conn->close();
    return $resultado;
}

function fluxoUltimosMeses() {
    $conn = conectar();

    $sql = "SELECT 
                DATE_FORMAT(data, '%Y-%m') AS mes,
                tipo,
                SUM(valor) AS total
            FROM transacoes
            WHERE data >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
            GROUP BY mes, tipo
            ORDER BY mes ASC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conn->close();
    return $resultado;
}