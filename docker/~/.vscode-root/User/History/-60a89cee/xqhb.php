<?php
$servername = "mysql"; // Nome do serviço do contêiner MySQL no Docker Compose
$username = "root";
$password = "senha";
$dbname = "scidb";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

echo "Conexão ao MySQL realizada com sucesso!";

// Query para buscar algo no banco de dados
$sql = "SELECT * FROM sua_tabela"; // Substitua pela sua tabela
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Exibe os dados de cada linha
    echo "<br><br>Dados da tabela:<br>";
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"]. " - Nome: " . $row["nome"]. "<br>";
    }
} else {
    echo "<br><br>Nenhum resultado encontrado na tabela.";
}

$conn->close();
?>
