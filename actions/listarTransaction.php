<?php
include '../includes/conectarBD.php'; // Inclui o arquivo de conexão com o banco de dados

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
 <title>Listar Transações</title>
 <link rel="stylesheet" href="../css/style.css">
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
      <a class="nav-link" href="add_transaction.php">Adicionar transação</a>
     </li>
    </ul>
   </div>
  </div>
 </nav>
 <h1 class="my-4 text-center">Lista de Transações</h1>
 <!-- Tabela para exibir as transações -->
 <div class="container">
  <table class="table table-bordered table-striped table-hover">
   <thead>
    <tr>
     <th>Descrição</th>
     <th>Valor</th>
     <th>Data</th>
     <th>Categoria</th>
     <th>Ação</th>
    </tr>
   </thead>
   <tbody>
    <?php
    if ($result->num_rows > 0) {
     // Exibe cada linha de resultado
     while ($row = $result->fetch_assoc()) {
      echo "<tr>";
      echo "<td>" . htmlspecialchars($row['descricao'], ENT_QUOTES, 'UTF-8') . "</td>";
      echo "<td><a href='#' class='edit-value' data-id='" . $row['id'] . "'>" . "R$ " . number_format($row['valor'], 2, ',', '.') . "</a></td>";
      echo "<td>" . date('d/m/Y', strtotime($row['data'])) . "</td>";
      echo "<td>" . htmlspecialchars($row['categoria'], ENT_QUOTES, 'UTF-8') . "</td>";
      echo "<td><i class='fas fa-trash' style='color: red; cursor: pointer; font-size: 1.2rem;' onclick='confirmDelete(" . $row['id'] . ")'></i></td>";
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
 <div class="container">
  <h4 class="my-1">Total de Gastos por Mês</h4>
  <table class="table table-bordered table-striped table-hover">
   <thead>
    <tr>
     <th>Mês/Ano</th>
     <th>Total de Gastos</th>
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
 <!-- Modal de Edição -->
 <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
   <div class="modal-content">
    <div class="modal-header">
     <h5 class="modal-title" id="editModalLabel">Editar Transação</h5>
     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form id="editForm" method="POST" action="update_transaction.php">
     <div class="modal-body">
      <input type="hidden" id="edit-id" name="id">
      <div class="mb-3">
       <label for="edit-valor" class="form-label">Valor</label>
       <input type="text" class="form-control" id="edit-valor" name="valor" required>
      </div>
      <div class="mb-3">
       <label for="edit-data" class="form-label">Data</label>
       <input type="date" class="form-control" id="edit-data" name="data" required>
      </div>
      <div class="mb-3">
       <label for="edit-categoria" class="form-label">Categoria</label>
       <select class="form-select" id="edit-categoria" name="categoria_id" required>
        <?php
        // Adicionando opções de categorias ao select
        $sqlCategorias = "SELECT id, nome FROM categorias";
        $resultCategorias = $conn->query($sqlCategorias);
        if ($resultCategorias->num_rows > 0) {
         while ($categoria = $resultCategorias->fetch_assoc()) {
          echo "<option value='" . $categoria['id'] . "'>" . htmlspecialchars($categoria['nome'], ENT_QUOTES, 'UTF-8') . "</option>";
         }
        }
        ?>
       </select>
      </div>
     </div>
     <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      <button type="submit" class="btn btn-primary">Salvar Alterações</button>
     </div>
    </form>
   </div>
  </div>
 </div>
 <script>
  $(document).ready(function() {
   // Evento para abrir o modal de edição
   $('.edit-value').on('click', function(e) {
    e.preventDefault();

    const id = $(this).data('id');
    const valor = $(this).text().replace('R$ ', '').replace('.', '').replace(',', '.');
    const linha = $(this).closest('tr');
    const data = linha.find('td:nth-child(3)').text();
    const categoria = linha.find('td:nth-child(4)').text();

    $('#edit-id').val(id);
    $('#edit-valor').val(valor);
    $('#edit-data').val(data);
    $('#edit-categoria').val(categoria);

    $('#editModal').modal('show');
   });

   // Evento de confirmação para exclusão
   window.confirmDelete = function(id) {
    if (confirm("Deseja excluir a transação?")) {
     window.location.href = "delete_transaction.php?id=" + id;
    }
   };
  });
 </script>

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-cVDYUXq0Z6Pz4ODN8ozYXn6PEBlr9BJOKGksZpqJLzLSj9KEq+fpc8tzT4zFqy7T" crossorigin="anonymous"></script>
</body>

</html>

<?php
$conn->close(); // Fecha a conexão com o banco de dados
?>