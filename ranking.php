<?php
$servername = "localhost";
$username   = "root";     
$password   = "";        
$dbname     = "feiratecnica";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome   = isset($_POST['nome']) ? $_POST['nome'] : "SemNome";
    $pontos = isset($_POST['pontos']) ? intval($_POST['pontos']) : 0;
    $tempo  = isset($_POST['tempoJogada']) ? $_POST['tempoJogada'] : "00:00:00"; 

    $stmt = $conn->prepare("INSERT INTO placar (nomeJogador, pontos, tempoJogada) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $nome, $pontos, $tempo); 
    $stmt->execute();
    $stmt->close();

    echo "✅ Resultado salvo com sucesso!";
    exit;
}

$pagina    = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$porPagina = 10; 
$offset    = ($pagina - 1) * $porPagina;

$sql = "SELECT * FROM placar ORDER BY pontos DESC, tempoJogada ASC LIMIT $porPagina OFFSET $offset";
$result = $conn->query($sql);

$totalSql = "SELECT COUNT(*) as total FROM placar";
$totalRes = $conn->query($totalSql);
$total = $totalRes->fetch_assoc()['total'];
$totalPaginas = ceil($total / $porPagina);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Ranking do Jogo</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap');

        body { 
            font-family: 'Orbitron', sans-serif;
            background: #0f002b; 
            color: #fff;
            text-align: center; 
        }
        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 80%;
            max-width: 700px;
            font-size: 1.3rem;
        }
        th, td {
            border: 2px solid #00ffff;
            padding: 15px 20px;
        }
        th {
            background: rgba(0,0,0,0.6);
            color: #00ffff;
            text-shadow: 0 0 10px #00ffff;
            font-size: 1.5rem;
        }
        td {
            color: #fff;
            background: rgba(255,255,255,0.1);
        }

                /* Brilho confinado dentro da célula */
        .gold {
            border: 2px solid #ffd700;
            box-shadow: inset 15px 0 25px rgba(255, 215, 0, 0.8),
                        inset -15px 0 25px rgba(255, 215, 0, 0.8);
            background: rgba(255, 215, 0, 0.1);
        }
        .silver {
            border: 2px solid #c0c0c0;
            box-shadow: inset 15px 0 25px rgba(192, 192, 192, 0.8),
                        inset -15px 0 25px rgba(192, 192, 192, 0.8);
            background: rgba(192, 192, 192, 0.1);
        }
        .bronze {
            border: 2px solid #cd7f32;
            box-shadow: inset 15px 0 25px rgba(205, 127, 50, 0.8),
                        inset -15px 0 25px rgba(205, 127, 50, 0.8);
            background: rgba(205, 127, 50, 0.1);
        }


        .paginacao a {
            margin: 0 5px;
            padding: 8px 15px;
            background: rgba(0,0,0,0.6);
            color: #ff00cc;
            text-decoration: none;
            border-radius: 8px;
            border: 2px solid #ff00cc;
            text-shadow: 0 0 10px #ff00cc;
            transition: 0.3s;
        }
        .paginacao a:hover {
            background: #ff00cc;
            color: #0f002b;
            box-shadow: 0 0 20px #ff00cc;
        }
    </style>
</head>
<body>
    <h1>🏆 Ranking dos Jogadores</h1>
    <table>
        <tr>
            <th>Posição</th>
            <th>Nome</th>
            <th>Pontos</th>
            <th>Tempo da Jogada</th>
        </tr>
        <?php
        $posicao = $offset + 1;
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $classe = "";
                if ($posicao == 1) $classe = "gold";
                elseif ($posicao == 2) $classe = "silver";
                elseif ($posicao == 3) $classe = "bronze";

                echo "<tr class='$classe'>
                        <td>".$posicao."</td>
                        <td>".$row['nomeJogador']."</td>
                        <td>".$row['pontos']."</td>
                        <td>".$row['tempoJogada']."</td>
                    </tr>";
                $posicao++;
            }
        } else {
            echo "<tr><td colspan='4'>Nenhum registro encontrado</td></tr>";
        }
        ?>
    </table>

    <div class="paginacao">
        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <a href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
</body>
</html>
<?php $conn->close(); ?>
