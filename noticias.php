<?php
session_start();
$conn = new mysqli("localhost", "root", "", "NoticiasDB");

// Configurar la cookie de usuario (si no existe)
if (!isset($_SESSION['usuario_cookie'])) {
    $_SESSION['usuario_cookie'] = 'usuario_' . rand(1000, 9999); // Identificador único
}

// Consultar noticias de la base de datos
$result = $conn->query("SELECT * FROM noticias");
$noticias = $result->fetch_all(MYSQLI_ASSOC);

// Usuario actual
$usuario = $_SESSION['usuario_cookie'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias personalizadas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function verNoticia(idNoticia, titulo) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "guardar.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Actualizar la página con la respuesta del servidor
                    var respuesta = JSON.parse(xhr.responseText);
                    document.getElementById("resultado").innerHTML = `
                        <div class="alert alert-success mt-3">
                            ${respuesta.mensaje}
                        </div>`;
                }
            };
            // Enviar datos de la noticia y el usuario
            xhr.send("id_noticia=" + idNoticia + "&titulo=" + encodeURIComponent(titulo));
        }
    </script>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center">Bienvenido, <?= htmlspecialchars($usuario) ?></h1>
        <h2 class="mt-4">Noticias personalizadas</h2>
        <ul class="list-group mt-3">
            <?php foreach ($noticias as $noticia): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= htmlspecialchars($noticia['titulo']) ?>
                    <button class="btn btn-primary btn-sm" onclick="verNoticia(<?= $noticia['id'] ?>, '<?= htmlspecialchars($noticia['titulo']) ?>')">
                        Leer más
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>
        <div id="resultado"></div>
    </div>
</body>
</html>