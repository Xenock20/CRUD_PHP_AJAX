<?php
// Conexión a la base de datos
$servername = "mysql";
$username = "root";
$password = "rootadmin";
$dbname = "IAC";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Manejo de solicitudes DELETE
if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    handleDeleteRequest($conn);
}

// Manejo de solicitudes POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    handlePostRequest($conn);
}

$conn->close();

// Función para manejar solicitudes DELETE
function handleDeleteRequest($conn) {
    parse_str(file_get_contents("php://input"), $_DELETE);
    $id = $_DELETE['id'];

    $id = intval($id);

    $sql = "DELETE FROM t_alumnos_del_curso WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo getAlumnosAsJSON($conn);
    } else {
        echo "Error al eliminar los datos en la base de datos: " . $conn->error;
    }
}

// Función para manejar solicitudes POST
function handlePostRequest($conn) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $codigoCurso = $_POST['codigo_curso'];

    $sql = "";

    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "UPDATE t_alumnos_del_curso SET nombre_de_usuario = '$nombre', mail = '$email', codigo_curso = '$codigoCurso' WHERE id = '$id'";
    } else {
        $sql = "INSERT INTO t_alumnos_del_curso (nombre_de_usuario, mail, codigo_curso) VALUES ('$nombre', '$email', '$codigoCurso')";
    }

    if ($conn->query($sql) === TRUE) {
        echo getAlumnosAsJSON($conn);
    } else {
        echo "Error al insertar los datos en la base de datos: " . $conn->error;
    }
}

// Función para obtener datos de alumnos como JSON
function getAlumnosAsJSON($conn) {
    $sql = "SELECT * FROM t_alumnos_del_curso";
    $result = $conn->query($sql);

    $datos = array();
    while ($row = $result->fetch_assoc()) {
        $datos[] = $row;
    }

    return json_encode($datos);
}
?>
