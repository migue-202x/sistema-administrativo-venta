 <?php
//Activamos el almacenamiento en el buffer 
ob_start();
session_start();

if (!isset($_SESSION['nombre'])){
  header("Location: login.html");
}else{
  require 'header.php';

  if ($_SESSION['almacen'] == 1)
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
                        <h1 class="box-title">Categoria 
                          <button class="btn btn-success" onclick="mostrarForm(true)"><i class="fa fa-plus-circle"></i>Agregar</button>
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
                          <th>Opciones</th>
                          <th>Nombre</th>
                          <th>Descripcion</th>
                          <th>Estado</th>
                        </thead>
                          <!-- el tbody lo muestro con el datatable -->
                        <tbody> 
                        </tbody>
                        <tfoot>
                        <th>Opciones</th>
                          <th>Nombre</th>
                          <th>Descripcion</th>
                          <th>Estado</th>
                        </tfoot>
                      </table>
                    </div>
                    <!-- FORMULARIO DE REGISTROS -->
                    <div class="panel-body" style="height: 400px;" id="formularioregistros">
                      <form name="formulario" id="formulario" method="POST">
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Nombre:</label>
                          <input type="hidden" name="idcategoria" id="idcategoria">
                          <input class="form-control" type="text" name="nombre" id="nombre" maxLength="50" placeholder="nombre" required>   
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Descripcion:</label>
                          <input class="form-control" type="text" name="descripcion" id="descripcion" maxLength="256" placeholder="descripcion">  
                        </div>
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>Guardar</button>
                          <button class="btn btn-danger" onclick="cancelarForm()" type="button"><i class="fa fa-arrow-circle-left"></i>Cancelar</button>
                        </div>
                      </form>    
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

<script type="text/javascript" src="scripts/categoria.js"></script>
<?php
}
//Liberamos el espacio en el buffer
ob_end_flush();
?>