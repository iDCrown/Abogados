<?php include "casosCon.php";?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CREAR</title>
    <link rel="stylesheet" href="./css/casos.css">

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
                
                <form class="conteiner-modal" method="POST" action="<?php echo $_SERVER['PHP_SELF'] . '?cedula=' . $fila['cedula']; ?>">
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
      <!-- caso -->
      <div class="col background">
        <h2 class="h2_crear">Caso</h2>
        <p class="p_crear" >Ingrese la información del Caso</p>
        <div style="margin-top: 22px">  
          <form class="conteiner-form" method="POST" action="<?php echo $_SERVER['PHP_SELF'] . '?cedula=' . $fila['cedula']; ?>">

            <div class="forml1">
              <div class="first mb-3">
                <label for="expediente" class="form-label">Expediente</label>
                <input type="number" class="for" name="expediente" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php echo $expediente; ?>">
              </div>
              <div class="first mb-3">
                <label for="idAbogado" class="form-label">Abogado</label>
                <input type="number" class="for" name="idAbogado" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php echo $idAbogado; ?>">
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

