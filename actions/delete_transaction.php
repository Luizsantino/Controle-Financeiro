<?php
include '../includes/conectarBD.php'; // Inclui o arquivo de conexão com o banco de dados

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepara a instrução SQL para excluir a transação
    $stmt = $conn->prepare("DELETE FROM transacoes WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: listarTransaction.php?mensagem=Transação excluída com sucesso");
    } else {
        echo "Erro: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close(); // Fecha a conexão com o banco de dados
?>
