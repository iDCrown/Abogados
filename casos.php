<?php
    // Incluimos conexión
    include 'conexion.php';

    // Obtener el ID del cliente de la URL
    $idRegistro = $_GET['cedula'];
// Generar un número de expediente aleatorio
    $expediente = rand(1000, 9999);

    // Verificar si se ha enviado el formulario de edición
    if(isset($_POST['editarRegistro'])){
      //$idRegistro = $_POST['cedula'];
        // Obtener los datos del formulario
        $cedula = mysqli_real_escape_string($con, $_POST['cedula']);
        $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $telefono = mysqli_real_escape_string($con, $_POST['telefono']);
        $direccion = mysqli_real_escape_string($con, $_POST['direccion']);

        // Validar si no están vacíos
        if(!empty($cedula) && !empty($nombre) && !empty($email) && !empty($telefono) && !empty($direccion)){
            // Actualizar los datos del cliente en la base de datos
            $query = "UPDATE clientes SET cedula='$cedula', nombre='$nombre', email='$email', telefono='$telefono', direccion='$direccion' WHERE cedula='$cedula'";
            if(mysqli_query($con, $query)){
                $mensaje = "Registro editado correctamente";
                // Recargar los datos del cliente después de la edición
                $result = mysqli_query($con, "SELECT * FROM clientes WHERE cedula='$cedula'");
                if(mysqli_num_rows($result) > 0){
                    $fila = mysqli_fetch_assoc($result);
                } else {
                    $error = "Cliente no encontrado";
                }
            } else {
                $error = "Error, no se pudo editar el registro";
            }
        } else {
            $error = "Algunos campos están vacíos";
        }
    }

    // Seleccionar datos del cliente
    $query = "SELECT * FROM clientes WHERE cedula='$idRegistro'";
    $result = mysqli_query($con, $query);
    
    // Verificar si se encontró el cliente
    if(mysqli_num_rows($result) > 0){
      
        $fila = mysqli_fetch_assoc($result);
    } else {
        $error = "Cliente no encontrado";
    }



    if(isset($_POST['enviarCaso'])){
      $expediente = mysqli_real_escape_string($con, $_POST['expediente']);
      $fechaini = mysqli_real_escape_string($con, $_POST['fechaini']);
      $fechafz = mysqli_real_escape_string($con, $_POST['fechafz']);
      $tipoCaso = mysqli_real_escape_string($con, $_POST['tipoCaso']);
      $estado = mysqli_real_escape_string($con, $_POST['estado']);
      $descripcion = mysqli_real_escape_string($con, $_POST['descripcion']);

      //Configurar tiempo zona horaria
      date_default_timezone_set('America/Bogota');
      $time = date('h:i:s a', time());

      //Validar si no están vacíos
      if(!isset($expediente) || $expediente == '' || !isset($fechaini) || $fechaini == '' || !isset($fechafz) || $fechafz == '' || !isset($tipoCaso) || $tipoCaso == '' || !isset($estado) || $estado == '' || !isset($descripcion) || $descripcion == ''){
          $error = "Algunos campos están vacíos";
      }else{
          $query = "INSERT INTO casos(expediente, fechafz, fechaini, tipoCaso, estado, descripcion)VALUES('$expediente', '$fechafz', '$fechaini', '$tipoCaso', '$estado', '$descripcion')";

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



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CREAR</title>
    <link rel="stylesheet" href="./casos.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
      integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
      crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  </head>
<body>
  <div class="containerr text-center">
    <div class="row content align-items-center">
      <div class="col col-5 clientes">
        <a href="index.php">
          <img style="position: absolute; top: 24px; left: 31px; width: 44px;" src="./icon/flecha.png" alt="">
        </a>
        <h2 class="fs-2"><?php echo $fila['nombre']; ?></h2>
        <p class="parraf">Datos del cliente</p>
        <div style="margin-bottom: 8em">
          <div class="hstack gap-3">
            <div class="p-2 title">cedula</div>
            <div class="vrr"></div>
            <div class="p-2 subt ms-auto"><?php echo $fila['cedula']; ?></div>
          </div>
          <div class="hstack gap-3">
            <div class="p-2 title">telefono</div>
            <div class="vrr"></div>
            <div class="p-2 subt ms-auto"><?php echo $fila['telefono']; ?></div>
          </div>
          <div class="hstack gap-3">
            <div class="p-2 title">email</div>
            <div class="vrr"></div>
            <div class="p-2 subt ms-auto"><?php echo $fila['email']; ?></div>
          </div>
          <div class="hstack gap-3">
            <div class="p-2 title">direccion</div>
            <div class="vrr"></div>
            <div class="p-2 subt ms-auto"><?php echo $fila['direccion']; ?></div>
          </div>
        </div>

        <div class="buton">
          <button type="button" class="btn-brown" data-bs-toggle="modal" data-bs-target="#exampleModal" >Editar</button>
        </div>
      <!-- MODAL -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Cliente</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                
                <form class="conteiner-modal" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                  <div class="forml1">
                    <div class="first mb-3">
                      <label for="cedula" class="form-labe">Cedula</label>
                      <input type="number" class="for" name="cedula" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php echo $fila['cedula']; ?>">
                    </div>
                    <div class="first mb-3">
                      <label for="nombre" class="form-labe">Nombre Completo</label>
                      <input type="text" class=" for" name="nombre" id="exampleInputPassword1"  value="<?php echo $fila['nombre']; ?>">
                    </div>
                  </div>
                  <div class="mb-3">
                    <label for="email" class="form-labe">Correo Electronico</label>
                    <input type="email" class="for b1" name="email" id="exampleInputPassword1"  value="<?php echo $fila['email']; ?>">
                  </div>
                  <div class="mb-3">
                    <label for="telefono" class="form-labe">Numero Telefonico</label>
                    <input type="number" class="for b2" name="telefono" id="exampleInputPassword1"  value="<?php echo $fila['telefono']; ?>">
                  </div>
                  <div class="mb-3">
                    <label for="direccion" class="form-labe">Dirección</label>
                    <input type="text" class="for b3" name="direccion" id="exampleInputPassword1"  value="<?php echo $fila['direccion']; ?>">
                  </div>

                  <div class="modal-footer">
                    <button type="submit" class="btn-brown" name="editarRegistro">Editar Registro</button>                  
                    <button type="button" class="btn-brown" data-bs-dismiss="modal">Cerrar</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col background">
        <h2 class="h2_crear">Caso</h2>
        <p class="p_crear" >Ingrese la información del Caso</p>
        <div style="margin-top: 22px">  
          <form class="conteiner-form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

            <div class="forml1">
              <div class="first mb-3">
                <label for="expediente" class="form-label">Expediente</label>
                <input type="number" class="for" name="expediente" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php echo $expediente; ?>">
              </div>
            </div>
            <div class="forml1">
              <div class="first mb-3">
                <label for="fechaini" class="form-label">Fecha de Inicio</label>
                <input type="date" class=" for"  name="fechaini">
              </div>
              <div class="first mb-3">
                
                <label for="fechafz" class="form-label">Fecha de Finalización</label>
                <input type="date" class=" for"  name="fechafz">
              </div>
            </div>

            <div class="forml1">
              <div class="first mb-3">
              <label for="tipoCaso" class="form-label">Tipo del Caso</label>
                <select class="form-select  for" name="tipoCaso" aria-label="Default select example">
                  <option value="Legislativo">Legislativo</option>
                  <option value="criminología">criminología</option>
                  <option value="fiscal">fiscal</option>
              </select>
              </div>

              <div class="first mb-3">
              <label for="estado" class="form-label">Estado del caso</label>
                <select class="form-select  for" name="estado" aria-label="Default select example">
                  <option value="Abierto">Abierto</option>
                  <option value="Cerrado">Cerrado</option>
                  <option value="Escalado">Escalado</option>
                  <option value="Cancelado">Cancelado</option>
              </select>
              </div>
            </div>
              <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Descripcion del caso</label>
                <textarea class="form-control " name="descripcion" style="background: #fef0dc; margin-bottom: 2em;" id="exampleFormControlTextarea1" rows="3"></textarea>
              </div>
            <button type="submit" class="btn-brown warning" name="enviarCaso">Enviar</button>
          </form>
        </div>
      </div>
    </div>
  </div>

</body>
</html>

