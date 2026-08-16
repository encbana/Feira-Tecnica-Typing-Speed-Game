<?php
// feedback.php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "feiratecnica";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$mensagem = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome       = isset($_POST['nome']) ? trim($_POST['nome']) : "";
    $comentario = isset($_POST['comentario']) ? trim($_POST['comentario']) : "";
    $estrelas   = isset($_POST['estrelas']) ? floatval($_POST['estrelas']) : 0.0;

    if ($comentario === '') {
        $mensagem = '<p style="color:#ff6b6b;text-align:center;">⚠️ O comentário é obrigatório.</p>';
    } else {
        $stmt = $conn->prepare("INSERT INTO feedback (nomeJogador, comentario, estrelas) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $nome, $comentario, $estrelas);
        if ($stmt->execute()) {
            $mensagem = '<p style="color:#39ff14;text-align:center;">✅ Feedback enviado com sucesso!</p>';
        } else {
            $mensagem = '<p style="color:#ff6b6b;text-align:center;">❌ Erro ao enviar feedback.</p>';
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Feedback Retrowave</title>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&display=swap" rel="stylesheet">
<style>
    :root{
        --bg:#0b0b0e;
        --card:#121216;
        --accent1:#ff00cc;
        --accent2:#00ffff;
        --gold:#f9bf3b;
        --muted:#333;
    }
    body{
        margin:0;
        background: linear-gradient(160deg, #0a0022 0%, #000000 100%);
        color:#fff;
        font-family: "Orbitron", sans-serif;
        padding:30px;
        display:flex;
        justify-content:center;
    }
    .container{
        width:100%;
        max-width:720px;
        background: rgba(0,0,0,0.6);
        border: 1px solid rgba(255,255,255,0.08);
        padding:28px;
        border-radius:14px;
        box-shadow: 0 0 25px rgba(255,0,204,0.25), 0 0 25px rgba(0,255,255,0.25) inset;
    }
    h1{
        margin:0 0 12px 0;
        font-weight:700;
        font-size:26px;
        background: linear-gradient(90deg,var(--accent1),var(--accent2));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-align:center;
    }
    input[type="text"], textarea {
        width:100%;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.08);
        padding:12px 14px;
        border-radius:10px;
        color: #fff;
        font-size:16px;
        outline:none;
        box-sizing:border-box;
        font-family: "Orbitron", sans-serif;
    }
    textarea { min-height:120px; resize:vertical; }
    .actions {
        display:flex;
        justify-content:flex-end;
        margin-top:16px;
    }
    button[type="submit"]{
        background: linear-gradient(90deg, var(--accent1), var(--accent2));
        border:none;
        color:#fff;
        font-weight:bold;
        padding:12px 20px;
        border-radius:12px;
        cursor:pointer;
        font-family: "Orbitron", sans-serif;
        box-shadow: 0 0 15px var(--accent1), 0 0 15px var(--accent2);
        transition: transform 150ms ease;
    }
    button[type="submit"]:hover{
        transform: scale(1.05);
    }
    /* sistema de estrelas */
    .rating-wrap{
        text-align:center;
        margin:16px 0;
    }
    .stars {
        display:inline-flex;
        gap:6px;
        justify-content:center;
        user-select:none;
    }
    .star {
        font-size:54px;
        color: var(--muted);
        background:none;
        border:none;
        cursor:pointer;
        padding:0;
        transition: transform 160ms ease;
    }
    .star.full { color: var(--gold); }
    .star.half {
        background: linear-gradient(90deg, var(--gold) 50%, var(--muted) 50%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .star.pulse {
        animation: pulse 350ms ease;
    }
    @keyframes pulse {
        0% { transform: scale(1); text-shadow:0 0 0 var(--gold); }
        50% { transform: scale(1.2); text-shadow:0 0 20px var(--gold); }
        100% { transform: scale(1); text-shadow:0 0 0 var(--gold); }
    }
    .rating-val {
        font-size:16px;
        margin-top:6px;
        color: #00ffff;
    }
    .msg { margin:10px 0; text-align:center; font-size:15px; }
</style>
</head>
<body>
    <div class="container">
        <h1>💬 Feedback</h1>

        <?php if (!empty($mensagem)) echo '<div class="msg">'.$mensagem.'</div>'; ?>

        <form method="post" action="">
            <input type="text" name="nome" placeholder="Seu nome (opcional)">
            <div class="rating-wrap">
                <div style="font-size:14px;color:#ccc;margin-bottom:4px;">
                    Clique na estrela para inteiro, clique de novo para reduzir 0.5 ⭐
                </div>
                <div class="stars" id="stars">
                    <button type="button" class="star" data-value="1">★</button>
                    <button type="button" class="star" data-value="2">★</button>
                    <button type="button" class="star" data-value="3">★</button>
                    <button type="button" class="star" data-value="4">★</button>
                    <button type="button" class="star" data-value="5">★</button>
                </div>
                <div class="rating-val" id="ratingVal">0.0</div>
            </div>
            <textarea name="comentario" placeholder="Digite seu comentário..." required></textarea>
            <input type="hidden" name="estrelas" id="estrelasInput" value="0.0">
            <div class="actions">
                <button type="submit">Enviar</button>
            </div>
        </form>
    </div>

<script>
(function(){
    const stars = Array.from(document.querySelectorAll('.star'));
    const input = document.getElementById('estrelasInput');
    const ratingVal = document.getElementById('ratingVal');
    let current = 0.0;

    function setRatingDisplay(value){
        stars.forEach((el, idx) => {
            el.classList.remove('full','half');
            const i = idx+1;
            if(i <= Math.floor(value)) el.classList.add('full');
            else if(i === Math.ceil(value) && value % 1 !== 0) el.classList.add('half');
        });
        input.value = value.toFixed(1);
        ratingVal.textContent = value.toFixed(1);
    }
    setRatingDisplay(current);

    stars.forEach((el, idx) => {
        const val = idx+1;
        el.addEventListener('click', () => {
            let newRating;
            if(current === val) newRating = val - 0.5;
            else if(current === val - 0.5) newRating = val;
            else newRating = val;
            current = newRating;
            setRatingDisplay(current);
            el.classList.add('pulse');
            setTimeout(()=> el.classList.remove('pulse'), 350);
        });
    });
})();
</script>
</body>
</html>
