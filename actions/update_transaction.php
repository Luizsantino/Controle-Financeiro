<?php
include '../includes/conectarBD.php'; // Inclui o arquivo de conexão com o banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $valor = $_POST['valor'];
    $data = $_POST['data'];
    $categoria_id = $_POST['categoria_id'];

    // Atualiza a transação no banco de dados
    $sql = "UPDATE transacoes SET valor = ?, data = ?, categoria_id = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $valor, $data, $categoria_id, $id);

    if ($stmt->execute()) {
        header("Location: listarTransaction.php"); // Redireciona de volta para a lista de transações
    } else {
        echo "Erro ao atualizar a transação: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
