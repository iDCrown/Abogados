<?php include 'conexion.php'; ?>
<?php
    //Crear y seleccionar query de clientes
    $query = "SELECT * FROM clientes ORDER BY cedula DESC";
    $clientes = mysqli_query($con, $query);

    if(isset($_POST['borrar'])){        
      $idRegistro = $_POST['cedula'];
      //Validar si no están vacíos
      $query = "DELETE FROM clientes where cedula='$idRegistro'";

        if(!mysqli_query($con, $query)){
        
          die('Error: ' . mysqli_error($con));
          $error = "Error, no se pudo crear el registros";
        }else{
          $mensaje = "Registro borrado correctamente";
          header('Location: index.php?mensaje='.urlencode($mensaje));
          exit();
        }
    }


    if(isset($_POST['borrarCaso'])){        

      $idRCasos = $_POST['expediente'];
      //Validar si no están vacíos
      $query = "DELETE FROM casos where expediente='$idRCasos'";

        if(!mysqli_query($con, $query)){
        
          die('Error: ' . mysqli_error($con));
          $error = "Error, no se pudo crear el registros";
        }else{
          $mensaje = "Registro borrado correctamente";
          header('Location: index.php?mensaje='.urlencode($mensaje));
          exit();
        }
    }
    // ABOGADOS
    if(isset($_POST['enviarAbogado'])){
      $idAbogado = mysqli_real_escape_string($con, $_POST['idAbogado']);
      $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
      $email = mysqli_real_escape_string($con, $_POST['email']);
      $telefono = mysqli_real_escape_string($con, $_POST['telefono']);
      $direccion = mysqli_real_escape_string($con, $_POST['direccion']);
      $direccion = mysqli_real_escape_string($con, $_POST['direccion']);


      //Configurar tiempo zona horaria
      date_default_timezone_set('America/Bogota');
      $time = date('h:i:s a', time());

      //Validar si no están vacíos
      if(!isset($idAbogado) || $idAbogado == '' || !isset($nombre) || $nombre == '' || !isset($telefono) || $telefono == '' || !isset($email) || $email == '' || !isset($direccion) || $direccion == ''){
          $error = "Algunos campos están vacíos";
      }else{
          $query = "INSERT INTO abogados(idAbogado, nombre, email, telefono, direccion)VALUES('$idAbogado', '$nombre', '$email', '$telefono', '$direccion')";

          if(!mysqli_query($con, $query)){
              die('Error: ' . mysqli_error($con));
              $error = "Error, no se pudo crear el registro";
          }else{
              $mensaje = "Registro creado correctamente";
              header('Location: index.php?mensaje='.urlencode($mensaje));
              exit();
          }
      }

  }

  // HISTORIAL DE CASOS
 // Casos
$result = null;

if (isset($_GET['cedula'])) {
    $idCedula = $_GET['cedula'];

    $query_historialCaso = "SELECT 
    a.nombre AS nombreAbogado, 
    cs.expediente, 
    cs.fechaini, 
    cs.tipoCaso, 
    cs.estado 
    FROM casos cs 
    JOIN caso_abogado ca 
    ON ca.expediente = cs.expediente 
    JOIN abogados a 
    ON ca.idAbogado = a.idAbogado 
    JOIN clientes cl 
    ON cs.cedula = cl.cedula 
    WHERE cs.cedula = ?
    GROUP BY cs.expediente, a.nombre, cs.tipoCaso, cs.estado ";
    // Preparar la declaración
    $stmt = $con->prepare($query_historialCaso);
    // Vincular los parámetros
    $stmt->bind_param('i', $idCedula); // 's' indica que el parámetro es de tipo string
    // Ejecutar la declaración
    $stmt->execute();
    // Obtener los resultados
    $result = $stmt->get_result();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link rel="stylesheet" href="./index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
  <ul class="nav nav-tabs" style=" padding: 1em; background: #fff4c1c2;">
    <li class="nav-item">
      <a class="nav-link" style="border: none; color: #ffc108; font-variant-caps: all-petite-caps; font-weight: 900; letter-spacing: 1px;" aria-current="page"  href="#" onclick="mostrarTabla('clientes')">Clientes</a>
    </li>
    <li class="nav-item">
      <a class="nav-link"style="border: none; color: #ffc108; font-variant-caps: all-petite-caps; font-weight: 900; letter-spacing: 1px;" href="#" onclick="mostrarTabla('casos')">Historial de casos</a>
    </li>
    <li class="nav-item">
      <a class="nav-link"style="border: none; color: #ffc108; font-variant-caps: all-petite-caps; font-weight: 900; letter-spacing: 1px;" href="#" onclick="mostrarTabla('abogado')">Crear Abogado</a>
    </li>
  </ul>
  <div class="conteiner">
    <div class="content">
      <!-- Tabla de clientes -->
      <div id="clientes" style="display: block;">
        <!-- Boton Crear Cliente -->
        <div class="boton">
          <a href="crearCliente.php" class=""> 
            <button type="button" class=" btn btn-outline-warning">Crear Cliente</button>
          </a>
        </div>
        <table class="table table-hover ">
          <thead class="table-warning table-bordered border-warning">
            <tr>
              <th scope="col">Cedula</th>
              <th scope="col">Nombre</th>
              <th scope="col">Correo</th>
              <th scope="col">Telefono</th>
              <th scope="col">Direccion</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
          <?php while ( $fila = mysqli_fetch_assoc($clientes)) : ?>
            <tr class="tr-row" style="font-size: smaller">
              <td scope="row">
                <a href="casos.php?cedula=<?php echo $fila['cedula']; ?>">
                  <?php echo $fila['cedula']; ?>
                </a>
              </td>
              <td scope="row"><?php echo $fila['nombre']; ?></td>
              <td scope="row"><?php echo $fila['email']; ?></td>
              <td scope="row"><?php echo $fila['telefono']; ?></td>
              <td scope="row"><?php echo $fila['direccion']; ?></td>
              <td scope="row">
              <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="cedula" value="<?php echo $fila['cedula']; ?>">
                <button type="submit" class="btn btn-warning w-100" name="borrar">Borrar</button>
              </form>
            </tr> 
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
      <!-- tabla casos -->
      <div id="casos" style="display:none;">
      <div class="boton">
          <a href="crearCliente copy.php" class=""> 
            <button type="button" class=" btn btn-outline-warning">Crear Cliente</button>
          </a>
        </div>
        <form class="consultar" action="" method="GET">
        <p  style="color:black" class="p_crear">Ingrese el ID del cliente</p>
          <input class="input" type="text" name="cedula">
          <button type="submit" class="button" name="consultar">buscar</button>
        </form>
        <table class="table table-hover ">
          <thead class="table-warning table-bordered border-warning">
            <tr>
              <th scope="col">Expediente</th>
              <th scope="col">Fecha de inicio</th>
              <th scope="col">TipoCaso</th>
              <th scope="col">Estado</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
          <?php if ($result): ?>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
              <tr class="tr-row" style="font-size: smaller">
                <td scope="row"><?php echo $row['expediente']; ?></td>
                  <td scope="row"><?php echo $row['fechaini']; ?></td>
                  <td scope="row"><?php echo $row['tipoCaso']; ?></td>
                  <td scope="row"><?php echo $row['estado']; ?></td>
                  <td scope="row">
                  <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="hidden" name="expediente" value="<?php echo $fila['expediente']; ?>">
                    <button type="submit" class="btn btn-warning w-100" name="borrarCaso">Borrar</button>

                    <input type="hidden" name="expediente" value="<?php echo $fila['expediente']; ?>">
                    <button type="submit" class="btn btn-warning w-100" name="borrarCaso">er</button>
                  </form>
              </tr> 
            <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="5">No se encontraron registros.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Crear Abogado -->
      <div style="display: flex; justify-content: center;">
        <div class="abogado" id="abogado" style="display:none;">
          <h2 class="h2_crear">Abogado</h2>
          <p class="p_crear" >Ingrese la información del Abogado</p>
          <div style="margin-top: 22px">  
            <form class="conteiner-form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
              <div class="forml1">
                <div class="first mb-3">
                  <label for="cedula" class="form-label">Cedula</label>
                  <input type="number" class="for" name="idAbogado" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>
                <div class="first mb-3">
                  <label for="nombre" class="form-label">Nombre Completo</label>
                  <input type="text" class=" for" name="nombre" id="exampleInputPassword1">
                </div>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Correo Electronico</label>
                <input type="email" class="for b1" name="email" id="exampleInputPassword1">
              </div>
              <div class="mb-3">
                <label for="telefono" class="form-label">Numero Telefonico</label>
                <input type="number" class="for b2" name="telefono" id="exampleInputPassword1">
              </div>
                <div class="mb-3">
                  <label for="direccion" class="form-label">Dirección</label>
                  <input type="text" class="for b3" name="direccion" id="exampleInputPassword1">
                </div>
                
              <button type="submit" class="btn-brown" name="enviarAbogado">Enviar</button>
            </form>
          </div>
        </div>
      </div>
    </div>  
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
  function mostrarTabla(tabla) {
    if (tabla === 'clientes') {
      document.getElementById('clientes').style.display = 'block';
      document.getElementById('casos').style.display = 'none';
      document.getElementById('abogado').style.display = 'none';
    } else if (tabla === 'casos') {
      document.getElementById('clientes').style.display = 'none';
      document.getElementById('casos').style.display = 'block';
      document.getElementById('abogado').style.display = 'none';
    } else if (tabla === 'abogado') {
      document.getElementById('clientes').style.display = 'none';
      document.getElementById('casos').style.display = 'none';
      document.getElementById('abogado').style.display = 'block';
    }
  }
</script>

</body>
</html>