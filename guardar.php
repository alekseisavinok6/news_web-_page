<?php
session_start();
$conn = new mysqli("localhost", "root", "", "NoticiasDB");

if (!isset($_SESSION['usuario_cookie'])) {
    echo json_encode(["error" => "Usuario no identificado"]);
    exit;
}

$idNoticia = isset($_POST['id_noticia']) ? intval($_POST['id_noticia']) : null;
$titulo = isset($_POST['titulo']) ? trim($_POST['titulo']) : null;
$contenido = isset($_POST['contenido']) ? trim($_POST['contenido']) : null;
$usuario = $_SESSION['usuario_cookie'];

if ($idNoticia && $titulo && $contenido) {
    $stmt = $conn->prepare("INSERT INTO noticias_leidas (usuario_cookie, id_noticia, titulo, contenido) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $usuario, $idNoticia, $titulo, $contenido);
    $stmt->execute();
    $stmt->close();


    //La función json_encode()
    //Sintaxis básica: json_encode($data);

    echo json_encode([
        "mensaje" => "Has leído la noticia: '$titulo'. Datos guardados correctamente.",
        "id_noticia" => $idNoticia,
        "titulo" => $titulo
        //"contenido" => $contenido
    ]);
} else {
    echo json_encode(["error" => "Datos incompletos"]);
}
?>