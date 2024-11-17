<?php
session_start();
$conn = new mysqli("localhost", "root", "", "NoticiasDB");

// Verificar si la cookie de sesión existe
if (!isset($_SESSION['usuario_cookie'])) {
    echo json_encode(["error" => "Usuario no identificado"]);
    exit;
}

// Recuperar datos enviados mediante POST
$idNoticia = isset($_POST['id_noticia']) ? intval($_POST['id_noticia']) : null;
$titulo = isset($_POST['titulo']) ? htmlspecialchars($_POST['titulo']) : null;
$usuario = $_SESSION['usuario_cookie'];

// Verificar datos
if ($idNoticia && $titulo) {
    // Insertar datos en la base de datos
    $stmt = $conn->prepare("INSERT INTO noticias_leidas (usuario_cookie, id_noticia, titulo) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $usuario, $idNoticia, $titulo);
    $stmt->execute();
    $stmt->close();

    // Respuesta JSON al cliente
    echo json_encode([
        "mensaje" => "Has leído la noticia: '$titulo'. Datos guardados correctamente.",
        "id_noticia" => $idNoticia,
        "titulo" => $titulo
    ]);
} else {
    echo json_encode(["error" => "Datos incompletos"]);
}
?>