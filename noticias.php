<?php
session_start();
$conn = new mysqli("localhost", "root", "", "NoticiasDB");

if (!isset($_SESSION['usuario_cookie'])) {
    $_SESSION['usuario_cookie'] = 'usuario_'.rand(1000, 9999);
}

$result = $conn->query("SELECT * FROM noticias");
$noticias = $result->fetch_all(MYSQLI_ASSOC);
$usuario = $_SESSION['usuario_cookie'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias personalizadas</title>
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .noticia-titulo{
            margin-bottom: 0;
            font-size: 19px;
        }
        .noticia-contenido{
            margin-bottom: 0;
            font-size: 15px;
        }
    </style>
    <script>
        function verNoticia(idNoticia, titulo, contenido) {
            let myRequest = new XMLHttpRequest(); //AJAX
            myRequest.open("POST", "guardar.php", true);
            myRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            myRequest.onreadystatechange = function() {
                if (myRequest.readyState == 4 && myRequest.status == 200) {
                    let respuesta = JSON.parse(myRequest.responseText);
                    document.getElementById("resultado").innerHTML = `
                        <div class="alert alert-info mt-3 p-1">${respuesta.mensaje}</div>`;
                }
            };
            myRequest.send("id_noticia="+idNoticia+"&titulo="+encodeURIComponent(titulo)+"&contenido="+encodeURIComponent(contenido));
        }
    </script>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center display-5">Bienvenido, <?= htmlspecialchars($usuario) ?></h1>
        <h2 class="mt-4 display-6">Noticias personalizadas</h2>
        <ul class="list-group mt-3">
            <?php foreach ($noticias as $noticia): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <p class="noticia-titulo"><?= htmlspecialchars($noticia['titulo']) ?></p>
                    <i class="noticia-contenido"><?= htmlspecialchars($noticia['contenido']) ?></i>
                    <button class="btn" onclick="verNoticia(<?= $noticia['id'] ?>, '<?= htmlspecialchars($noticia['titulo']) ?>', '<?= htmlspecialchars($noticia['contenido']) ?>')">
                    <span style='font-size:20px;'>&#128209;</span>
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>
        <div id="resultado"></div>
    </div>
</body>
</html>