<?php include 'conexion.php'; ?>
<?php include 'verCaso.php';?>
<?php
    //Crear y seleccionar query de clientes
    $query = "SELECT * FROM clientes ORDER BY cedula DESC";
    $clientes = mysqli_query($con, $query);

    if(isset($_POST['borrar'])){        
      $idRegistro = $_POST['cedula'];
      //Validar si no están vacíos
      $query = "DELETE FROM clientes WHERE cedula='$idRegistro'";

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
      $idRegistro = $_POST['expediente'];
      //Validar si no están vacíos
      $query = "DELETE FROM casos WHERE expediente= ?";

      $stmt = $con->prepare($query);
      $stmt->bind_param("i", $idRegistro);
      $stmt->execute();
      $result = $stmt->get_result();
        if($result->num_rows>0){
        
          die('Error: ' . mysqli_error($con));
          $error = "Error, no se pudo crear el registros";
        }else{
          $mensaje = "Registro borrado correctamente";
          header('Location: index.php?mensaje='.urlencode($mensaje));
          exit();
        }
    }
    // Abogados
    $query = "SELECT * FROM abogado ORDER BY idAbogado DESC";
    $abogados = mysqli_query($con, $query);

    if(isset($_POST['borrarAbogado'])){        
    $idRegistro = $_POST['idAbogado'];
    //Validar si no están vacíos
    $query = "DELETE FROM abogado WHERE idAbogado='$idRegistro'";

    if(!mysqli_query($con, $query)){
    
      die('Error: ' . mysqli_error($con));
      $error = "Error, no se pudo crear el registros";
      }else{
      $mensaje = "Registro borrado correctamente";
      header('Location: index.php?mensaje='.urlencode($mensaje));
      exit();
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
    JOIN abogado a 
    ON ca.idAbogado = a.idAbogado 
    JOIN clientes cl 
    ON cs.cedula = cl.cedula 
    WHERE cs.cedula = ?
    GROUP BY cs.expediente, a.nombre, cs.tipoCaso, cs.estado ";
    // Preparar la declaración
    $stmt = $con->prepare($query_historialCaso);
    // Vincular los parámetros
    $stmt->bind_param('s', $idCedula); // 's' indica que el parámetro es de tipo string
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
      <a class="nav-link"style="border: none; color: #ffc108; font-variant-caps: all-petite-caps; font-weight: 900; letter-spacing: 1px;" href="#" onclick="mostrarTabla('abogado')">Abogados</a>
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
       <!-- Abogado -->
       <div id="abogado"  style="display: none;">
        <!-- Boton Crear  -->
        <div class="boton">
          <a href="abogado.php" class=""> 
            <button type="button" class=" btn btn-outline-warning">Crear Abogado</button>
          </a>
        </div>
          <!-- tabla Abogados -->
        <table class="table table-hover ">
          <thead class="table-warning table-bordered border-warning">
            <tr>
              <th scope="col">Cedula</th>
              <th scope="col">Nombre</th>
              <th scope="col">Correo</th>
              <th scope="col">Telefono</th>
              <th scope="col">Direccion</th>
              <th scope="col">Salario</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
          <?php while ( $fila = mysqli_fetch_assoc($abogados)) : ?>
            <tr class="tr-row" style="font-size: smaller">
              <td scope="row">
                <?php echo $fila['idAbogado']; ?>
              </td>
              <td scope="row"><?php echo $fila['nombre']; ?></td>
              <td scope="row"><?php echo $fila['email']; ?></td>
              <td scope="row"><?php echo $fila['telefono']; ?></td>
              <td scope="row"><?php echo $fila['direccion']; ?></td>
              <td scope="row"><?php echo $fila['salario']; ?></td>
              <td scope="row">
              <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="idAbogado" value="<?php echo $fila['idAbogado']; ?>">
                <button type="submit" class="btn btn-warning w-100" name="borrarAbogado">Borrar</button>
              </form>
            </tr> 
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
      <!-- tabla casos -->
      <div id="casos" style="display:none;">
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
                      <div class="forml1">
                        <button type="button" style="margin-right: 9px;" class="btnVer btn btn-warning w-100" data-expediente="<?php echo $row['expediente']; ?>" onclick="verCaso(this)" data-bs-toggle="modal" data-bs-target="#myModal">Ver</button>

                        <input type="hidden" name="expediente" value="<?php echo $row['expediente']; ?>">
                        <button type="submit" class="btn btn-warning w-100" name="borrarCaso">Borrar</button>
                      </div>
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
     
      <!-- Modal Abogado -->
      <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modalFactura-header">
              <div>
                <p class="modalFactura-title" id="exampleModalLabel">Caso # <span id="expediente"></span></p>
              </div>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>  
            <div class="tableFactura-header">
              <div class="infoMascota">
                <h2 class="title_mascota" id="nombre"></h2>
                <table class="data-table">
                  <tr>
                    <th>cedula:</th><td id="cedula"></td>
                  </tr>
                  <tr>
                    <th>telefono:</th><td id="telefono"></td>
                  </tr>
                  <tr>
                    <th>email:</th><td id="email"></td>
                  </tr>
                </table>
              </div>
              <div class="column_Nfactura">
                <table class="data-table">
                  <tr>
                    <th class="facturaN">Fecha inicio:</th><td id="fechaini"></td>
                  </tr>
                  <tr>
                    <th class="facturaN">Fecha final:</th><td id="fechafz"></td>
                  </tr>
                </table>
              </div>
            </div>
            <div class="modal-body">
            <table class="table table-sm caption-top ">
              <h2 class="casoFactura-title">Informacion del caso</h2>
              <thead>
                <tr>
                  <th scope="col">Expediente</th>
                  <th scope="col">Tipo de Caso</th>
                  <th scope="col">Nombre del Abogado</th>
                  <th scope="col">Estado</th>
                </tr>
              </thead>
              <tbody class="table-group-divider">
                <td id="expediente_ex"></td>
                <td id="tipoCaso"></td>
                <td id="nombreAbogado"></td>
                <td id="estado"></td>
              </tbody>
            </table>
              <p  style="margin-top: 32px;"><strong>Descripcion del caso:</strong></p>
              <p class="text_descripcion" id="descripcion">Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo, amet.</p>
            </div>
          </div>
        </div>
      </div>
    </div>  
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <script>
    function verCaso(button) {
      const expediente = button.getAttribute('data-expediente');

      // Realizar una solicitud AJAX a verCasos.php
      fetch('verCaso.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'expediente_ex=' + expediente
      })
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          alert(data.error);
        } else {
          // Poblar el modal con los datos recibidos
          document.getElementById('expediente').innerText = data.expediente;
          document.getElementById('expediente_ex').innerText = data.expediente;
          document.getElementById('tipoCaso').innerText = data.tipoCaso;
          document.getElementById('fechaini').innerText = data.fechaini;
          document.getElementById('fechafz').innerText = data.fechafz;
          document.getElementById('nombre').innerText = data.nombre;
          document.getElementById('nombreAbogado').innerText = data.nombreAbogado;
          document.getElementById('email').innerText = data.email;
          document.getElementById('telefono').innerText = data.telefono;
          document.getElementById('estado').innerText = data.estado;
          document.getElementById('descripcion').innerText = data.descripcion;
          document.getElementById('cedula').innerText = data.cedula;
        }
      })
      .catch(error => {
        console.error('Error:', error);
      });
    }
  </script>

  <script>
    function mostrarTabla(tabla) {
      const tablas = ['clientes', 'casos', 'abogado'];
      tablas.forEach(t => {
        document.getElementById(t).style.display = (t === tabla) ? 'block' : 'none';
      });
    }
  </script>

</body>
</html>