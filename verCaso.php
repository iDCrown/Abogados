<?php
include 'conexion.php';

// Verifica si se recibió el expediente_ex correctamente
if (isset($_POST['expediente_ex'])) {
    $idExpediente = $_POST['expediente_ex'];

    // Prepara la consulta SQL
    $query_abogado = "SELECT 
        a.nombre AS nombreAbogado, 
        cs.expediente, 
        cs.fechaini, 
        cs.fechafz,
        cs.tipoCaso, 
        cs.estado,
        cl.cedula,
        cl.nombre,
        cl.email,
        cl.telefono
    FROM casos cs 
    JOIN caso_abogado ca ON ca.expediente = cs.expediente 
    JOIN abogados a ON ca.idAbogado = a.idAbogado 
    JOIN clientes cl ON cs.cedula = cl.cedula 
    WHERE cs.expediente = ?";

    // Ejecuta la consulta preparada
    $stmt = $con->prepare($query_abogado);
    $stmt->bind_param('s', $idExpediente);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica si se obtuvieron resultados
    if ($result && $result->num_rows > 0) {
        // Obtiene la fila de resultados
        $row = $result->fetch_assoc();

        // Crea un array asociativo con los datos obtenidos
        $Vabogado = [
            'nombreAbogado' => $row['nombreAbogado'],
            'tipoCaso' => $row['tipoCaso'],
            'fechaini' => $row['fechaini'],
            'fechafz' => $row['fechafz'],
            'nombre' => $row['nombre'],
            'email' => $row['email'],
            'telefono' => $row['telefono'],
            'estado' => $row['estado'],
            'cedula' => $row['cedula'],
        ];

        // Convierte el array a formato JSON y lo imprime
        echo json_encode($Vabogado);
    } else {
        // No se encontraron resultados
        echo json_encode(['error' => 'No se encontraron resultados para el expediente proporcionado']);
    }
} else {
    // No se recibió el expediente_ex
    echo json_encode(['error' => 'No se recibió el expediente_ex']);
}
?>
