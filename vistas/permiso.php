<?php
//Activamos el almacenamiento en el buffer 
ob_start();
session_start();

if (!isset($_SESSION['nombre'])){
  header("Location: login.html");
}else{
  require 'header.php';

  if ($_SESSION['acceso'] == 1)
  {
?>

<!-- Contenido -->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        
        <!-- Main content -->
        <section class="content">
            <div class="row">
              <div class="col-md-12">
                  <div class="box">
                    <div class="box-header with-border">
                        <h1 class="box-title">Permiso 
                          <!-- <button id="btnagregar" class="btn btn-success" onclick="mostrarForm(true)"><i class="fa fa-plus-circle"></i>Agregar</button> -->
                        </h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <!-- LISTADO DE REGISTROS -->
                    <div class="panel-body table-responsive" id="listadoregistros">  
                      <table id="tbllistado" class="table table-striped table-bordered table-condesed table-hover">
                        <thead>
                          <th>Nombre</th>
                        </thead>
                          <!-- el tbody lo muestro con el datatable -->
                        <tbody> 
                        </tbody>
                        <tfoot>
                          <th>Nombre</th>
                        </tfoot>
                      </table>
                    </div>
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->
<?php
}else{ //si $_SESSION['almacen'] no es 1
  require 'noacceso.php';
}

require 'footer.php';

?>

<script type="text/javascript" src="scripts/permiso.js"></script>
<?php
}
//Liberamos el espacio en el buffer
ob_end_flush();
?>