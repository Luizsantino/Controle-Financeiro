<?php
include '../includes/conectarBD.php'; // Inclui o arquivo de conexão com o banco de dados

// Inicializa a variável de mensagem
$mensagem = "";

// Processa o formulário quando ele é enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];
    $categoria_id = $_POST['categoria'];
    $data = $_POST['data'];

    // Prepara a instrução SQL
    $stmt = $conn->prepare("INSERT INTO transacoes (descricao, valor, data, categoria_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdsi", $descricao, $valor, $data, $categoria_id);

    if ($stmt->execute()) {
        $mensagem = "Nova transação adicionada com sucesso!";
    } else {
        $mensagem = "Erro: " . $stmt->error;
    }

    $stmt->close();
}
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
                        <a class="nav-link" href="listarTransaction.php">Editar transacões</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Adicionar Transação</h1>

        <!-- Formulário para adicionar transação -->
        <form id="transactionForm" method="post">
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <input class="form-control" type="text" name="descricao" placeholder="Descrição" required>
            </div>

            <div class="mb-3">
                <label for="valor" class="form-label">Valor</label>
                <input class="form-control" type="number" step="0.01" name="valor" placeholder="Valor" required>
            </div>

            <div class="mb-3">
                <label for="categoria" class="form-label">Categoria</label>
                <select class="form-select" name="categoria" required>
                    <option value="" disabled selected>Selecione uma categoria</option>
                    <?php
                    // Busca as categorias
                    $result = $conn->query("SELECT id, nome FROM categorias");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($row['nome'], ENT_QUOTES, 'UTF-8') . '</option>';
                        }
                    } else {
                        echo '<option value="">Nenhuma categoria encontrada</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="data" class="form-label">Data</label>
                <input class="form-control" type="date" name="data" required>
            </div>
                    <div class="d-flex justify-content-center mb-0" >
                        <button class="btn btn-primary" type="submit">Adicionar</button>
                    </div>
        </form>

        <!-- Exibe a mensagem de sucesso ou erro -->
        <?php if ($mensagem): ?>
            <div class="alert alert-info mt-3">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-cVDYUXq0Z6Pz4ODN8ozYXn6PEBlr9BJOKGksZpqJLzLSj9KEq+fpc8tzT4zFqy7T" crossorigin="anonymous"></script>
</body>

</html>

<?php
$conn->close(); // Fecha a conexão no final do arquivo, depois de todas as operações
?>
