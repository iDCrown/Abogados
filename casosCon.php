<?php
  // Incluimos conexión
  include 'conexion.php';

  // Obtener el ID del cliente de la URL
  if (isset($_GET['cedula']) && !empty($_GET['cedula'])) {
    $idRegistro = $_GET['cedula'];
  } else {
    die("Error: Cedula parameter is missing in the URL.");
  }

  // Generar un número de expediente aleatorio
  $expediente = rand(1000, 9999);

  // Prepare and execute the query to fetch client data
  $query = "SELECT * FROM clientes WHERE cedula = ?";
  $stmt = $con->prepare($query);
  $stmt->bind_param("i", $idRegistro);
  $stmt->execute();
  $result = $stmt->get_result();

  // Verificar si se encontró el cliente
  if ($result->num_rows > 0) {
    $fila = $result->fetch_assoc();
  } else {
    die("Error: Cliente no encontrado.");
  }

  // Verificar si se ha enviado el formulario de edición
  if (isset($_POST['editarRegistro'])) {
    $idRegistro = $_POST['cedula'];
    // Obtener los datos del formulario
    $cedula = mysqli_real_escape_string($con, $_POST['cedula']);
    $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $telefono = mysqli_real_escape_string($con, $_POST['telefono']);
    $direccion = mysqli_real_escape_string($con, $_POST['direccion']);

    // Validar si no están vacíos
    if (!empty($cedula) && !empty($nombre) && !empty($email) && !empty($telefono) && !empty($direccion)) {
      // Actualizar los datos del cliente en la base de datos
      $query = "UPDATE clientes SET cedula=?, nombre=?, email=?, telefono=?, direccion=? WHERE cedula=?";
      $stmt = $con->prepare($query);
      $stmt->bind_param("sssssi", $cedula, $nombre, $email, $telefono, $direccion, $idRegistro);
      if ($stmt->execute()) {
        $mensaje = "Registro editado correctamente";
        // Recargar los datos del cliente después de la edición
        $result = $con->query("SELECT * FROM clientes WHERE cedula='$cedula'");
        if ($result->num_rows > 0) {
            $fila = $result->fetch_assoc();
        } else {
            die("Error: Cliente no encontrado.");
        }
        } else {
            die("Error: No se pudo editar el registro.");
        }
    } else {
        die("Error: Algunos campos están vacíos.");
    }
  }

  // Verificar si se ha enviado el formulario de creación de caso
  if (isset($_POST['enviarCaso'])) {
    echo'cedula'. $idRegistro;
    $expediente = mysqli_real_escape_string($con, $_POST['expediente']);
    $fechaini = mysqli_real_escape_string($con, $_POST['fechaini']);
    $fechafz = mysqli_real_escape_string($con, $_POST['fechafz']);
    $tipoCaso = mysqli_real_escape_string($con, $_POST['tipoCaso']);
    $estado = mysqli_real_escape_string($con, $_POST['estado']);
    $descripcion = mysqli_real_escape_string($con, $_POST['descripcion']);
    $idAbogado = mysqli_real_escape_string($con, $_POST['idAbogado']);

    // Validar si no están vacíos
    if (!empty($expediente) && !empty($fechaini) && !empty($fechafz) && !empty($tipoCaso) && !empty($estado) && !empty($descripcion) ){
      $query = "INSERT INTO casos(expediente, fechaini, fechafz, tipoCaso, estado, descripcion, cedula) VALUES (?, ?, ?, ?, ?, ?,?)";
      $stmt = $con->prepare($query);
      $stmt->bind_param("isssssi", $expediente, $fechaini, $fechafz, $tipoCaso, $estado, $descripcion, $idRegistro);
      $stmt->execute();


      $query_casos = "INSERT INTO caso_abogado(expediente, idAbogado) VALUES (?, ?)";
      $stmt_casos = $con->prepare($query_casos);
      $stmt_casos->bind_param("ii", $expediente, $idAbogado);
      $stmt_casos->execute();

      if($stmt_casos->error){
        die('Error: ' . $stmt_casos->error);
      }
    } 
    header('Location: index.php?mensaje='.urlencode("Cita programada correctamente y factura generada"));
    exit();
    }

?>
