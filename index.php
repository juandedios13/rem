<?php

  include 'DB.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Document</title>
</head>
<body class="bg-info">
    <div class="m-5 border border-secondary rounded p-3 bg-white">
        <h1 class="display-4"> Alquiler de vehículos</h1>
        <form class=" m-2" method="POST" action="index.php">
            <div class="form-group">
              <div class="input-group mb-3 ">
                <div class="input-group-prepend">
                  <label class="input-group-text" for="inputGroupSelect01">Carros</label>
                </div>
                <select name="TipoCarro" class="custom-select" id="inputGroupSelect01">
                  <?php

                      $sql = "SELECT NplacaCarro, GamaCarro FROM $TablaCarro ";
                      $resultado = mysqli_query($conexion,$sql);
                      while($consulta = mysqli_fetch_array($resultado)){
                        echo "<option value='{$consulta["NplacaCarro"]}'> {$consulta['NplacaCarro']} - Gama {$consulta['GamaCarro']} </option>";
                      }
                  ?>
                  
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="input-group-prepend">
                <label class="input-group-text" >Fecha salida    :</label>
                <input  type="date" id="start" name="FechaSalida" value="">
              </div>
            </div>
            <div class="form-group">
                <div class="input-group-prepend">
                    <label class="input-group-text" >Fecha entrega :</label>
                    <input  type="date" id="start" name="FechaEntrega" value="">
                </div>
            </div>
            <div class="form-group text-center">
                <button type="submit" name='Enviar' class="btn btn-primary ">Enviar</button>
            </div>
          </form>

          <div>Resultado: 
              <?php 
              
                if(isset($_POST['Enviar'])){
                  extract($_POST);
                  
                  include 'Validar.php';
                  if($FechaSalida != null && $FechaEntrega != null &&  ValidarFechas($FechaSalida,$FechaEntrega) ){
                  
                    $sql = "SELECT * FROM pedidos WHERE Carro_NplacaCarro = '$TipoCarro' AND (`FechaSalidaPedidos` BETWEEN '$FechaSalida'AND '$FechaEntrega') OR (`FechaEntregaPedidos` BETWEEN '$FechaSalida'AND '$FechaEntrega' );";
                    $resultado = mysqli_query($conexion,$sql);
                    if(mysqli_num_rows($resultado)>0){
                        echo (' 
                        <div class="input-group-prepend">
                        <label class="input-group-text" >Disponible:</label>
                        <input  type="text"  value=" No" disabled>
                        </div>
                        ');
                    }else{
                      $sql = "SELECT GamaCarro FROM carro WHERE NplacaCarro = '$TipoCarro' ";
                      $resultado = mysqli_query($conexion,$sql);
                      $resultado = mysqli_fetch_array($resultado);
                      $dias = CalcularDias($FechaSalida,$FechaEntrega);
                      $diasSabadoDomingo = CalcularSabadoDomingo($FechaSalida,$dias);
                      $valor = CalcularValor($dias,$diasSabadoDomingo,$resultado['GamaCarro']);
                      


                      $sql = "INSERT INTO `pedidos`( `FechaSalidaPedidos`, `FechaEntregaPedidos`, `ValorPedido`, `Carro_NplacaCarro`) VALUES ('$FechaSalida','$FechaEntrega','$valor','$TipoCarro')";
                      $resultado = mysqli_query($conexion,$sql);
                      if(!$resultado){
                        echo('
                        <div class="input-group-prepend mb-2">
                            <label class="input-group-text" >Registro:</label>
                            <input  type="text"  value="No" disabled>
                        </div>
                        ');
                      }else{
                        echo (' 
                        <div class="input-group-prepend mb-2">
                        <label class="input-group-text" >Disponible:</label>
                        <input  type="text"  value=" Si" disabled>
                        </div>
                        <div class="input-group-prepend mb-2">
                        <label class="input-group-text" >Numero de placa:</label>
                        <input  type="text"  value='.$TipoCarro.' disabled>
                        </div>
                        <div class="input-group-prepend mb-2">
                        <label class="input-group-text" >Fecha salida:</label>
                        <input  type="text"  value='.$FechaSalida.' disabled>
                        </div>
                        <div class="input-group-prepend mb-2">
                        <label class="input-group-text" >Fecha Entrega:</label>
                        <input  type="text"  value='.$FechaEntrega.' disabled>
                        </div>
                        <div class="input-group-prepend mb-2">
                        <label class="input-group-text" >Cantidad de dias:</label>
                        <input  type="text"  value='.$dias.' disabled>
                        </div>
                        <div class="input-group-prepend mb-2">
                        <label class="input-group-text" >Total:</label>
                        <input  type="text"  value='.$valor.' dólares'.' disabled>
                        </div>
                        ');
                      }

                    }
                  }else{
                    echo('
                    <div class="input-group-prepend mb-2">
                        <label class="input-group-text" >Datos:</label>
                        <input  type="text"  value="Datos Vacios" disabled>
                    </div>
                    ');
                  }
                }
              
              ?>
          </div>



    </div>
    
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>