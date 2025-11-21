<?php
// guardado de datos
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "usuarios"; 
$tabla = "testimonios"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Obtiene los datos
    $nombre = htmlspecialchars($_POST['nombre']);
    $comentario = htmlspecialchars($_POST['comentario']);
    $fecha = date("2025-11-20");

    // Conexión
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) { die("La conexión falló: " . $conn->connect_error); }
    $conn->set_charset("utf8");

    // Insertando los datos
    $sql = "INSERT INTO $tabla (nombre, comentario, fecha) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) { die("Error al preparar consulta: " . $conn->error); }

    $stmt->bind_param("sss", $nombre, $comentario, $fecha);
    
    // mostrando los resultados
    if ($stmt->execute()) {
        echo '<html><head><title>Testimonio Recibido</title><link rel="stylesheet" href="style.css"></head><body>';
        echo '<main>'; 
        echo '<section style="text-align:center;">';
        echo '<h1>Gracias por tu comentar</h1>';
        echo '<div class="testimonio-card" style="margin: 0 auto; max-width:500px;">';
        echo '<h3>Datos Publicados:</h3>';
        echo '<p><strong>Nombre</strong> ' . $nombre . '</p>';
        echo '<p><strong>Comentario</strong> ' . $comentario . '</p>';
        echo '<p><strong>Fecha</strong> ' . $fecha . '</p>';
        echo '</div>';
        echo '<br><a href="testimonios.php" style="color: #444; font-weight:bold;">← Volver a ver los testimonios</a>';
        echo '</section>';
        echo '</main></body></html>';
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();

} else {
    header("Location: testimonios.php");
    exit;
}
?>