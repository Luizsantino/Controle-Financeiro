<?php
include 'includes/conectarBD.php'; // Inclui o arquivo de conexão com o banco de dados

// Consulta SQL para buscar todas as transações, ordenadas pela data
$sql = "SELECT t.id, t.descricao, t.valor, t.data, c.nome AS categoria 
        FROM transacoes t 
        LEFT JOIN categorias c ON t.categoria_id = c.id 
        ORDER BY t.data DESC";
$result = $conn->query($sql);

// Consulta para buscar o total de gastos por mês
$sqlTotalPorMes = "SELECT DATE_FORMAT(data, '%m/%Y') AS mes_ano, SUM(valor) AS total
    FROM transacoes
    GROUP BY mes_ano
    ORDER BY data DESC";
$resultTotalPorMes = $conn->query($sqlTotalPorMes);
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Controle Financeiro</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="nav-link active" href="/teste/index.php">Controle Financeiro</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/teste/index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="actions/listarTransaction.php">Editar transações</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-lg mt-4">

        <!-- Tabela para exibir as transações -->
        <div class="mb-4">
            <h1 class="text-center mb-4">Transações</h1>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Data</th>
                        <th>Categoria</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        // Exibe cada linha de resultado
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['descricao'], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td>R$ " . number_format($row['valor'], 2, ',', '.') . "</td>";
                            echo "<td>" . date('d/m/Y', strtotime($row['data'])) . "</td>";
                            echo "<td>" . htmlspecialchars($row['categoria'], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>Nenhuma transação encontrada.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Total de gastos por mês -->
        <div>
            <h4 class="mb-3">Apuramento por Mês</h4>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Mês/Ano</th>
                        <th>Saldo Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($resultTotalPorMes->num_rows > 0) {
                        // Exibe o total de gastos por mês
                        while ($rowTotal = $resultTotalPorMes->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($rowTotal['mes_ano'], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td>R$ " . number_format($rowTotal['total'], 2, ',', '.') . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>Nenhum gasto encontrado.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!-- Botão para redirecionar para a página de adição de transações -->
        <div class="d-flex justify-content-center mb-3">
            <button type="button" class="btn btn-primary" onclick="window.location.href='actions/add_transaction.php'">Adicionar Nova Transação</button>
        </div>
    </div>
    <script>
        function confirmDelete(id) {
            if (confirm("Deseja excluir a transação?")) {
                window.location.href = "delete_transaction.php?id=" + id;
            }
        }
    </script>
</body>

</html>