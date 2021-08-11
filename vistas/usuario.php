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
                    <h1 class="display-1 text-primary">
                          <u>Usuario</u>  
                          <button class="btn btn-primary btn-lg" id="btnagregar" onclick="mostrarForm(true)"><i class="fa fa-plus-circle"></i>Agregar</button>
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
                            <th>Tipo Doc</th>
                            <th>Num Doc</th>
                            <!-- <th>Dirección</th> -->
                            <th>Tel</th>
                            <th>Email</th>
                            <th>Login</th>
                            <th>Foto</th>
                            <th>Estado</th>
                        </thead>
                          <!-- el tbody lo muestro con el datatable -->
                        <tbody> 
                        </tbody>
                        <tfoot>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>Tipo Doc</th>
                            <th>Num Doc</th>
                            <!-- <th>Dirección</th> -->
                            <th>Tel</th>
                            <th>Email</th>
                            <th>Login</th>
                            <th>Foto</th>
                            <th>Estado</th>
                        </tfoot>
                      </table>
                    </div>
                    <!-- FORMULARIO DE REGISTROS -->
                    <div class="panel-body" id="formularioregistros">
                      <form name="formulario" id="formulario" method="POST">
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          <label>Nombre(*):</label>
                          <input type="hidden" name="idusuario" id="idusuario">
                          <input class="form-control" type="text" name="nombre" id="nombre" maxLength="100" placeholder="nombre" required>   
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Tipo Documento(*):</label>
                          <select class="form-control select-picker" name="tipo_documento" id="tipo_documento" required>
                              <option value="DNI">DNI</option>
                              <option value="RUC">RUC</option>
                              <option value="CEDULA">CEDULA</option>
                          </select>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Número Documento(*):</label>
                          <input class="form-control" type="text" name="num_documento" id="num_documento" maxLength="20" placeholder="Documento" required>     
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Dirección:</label>
                          <input class="form-control" type="text" name="direccion" id="direccion" maxLength="70" placeholder="Dirección">  
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Teléfono:</label>
                          <input type="text" class="form-control" name="telefono" id="telefono" maxLength="20" placeholder="Teléfono">   
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Email:</label>
                          <input class="form-control" type="email" name="email" id="email" maxLength="256" placeholder="Email">  
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Cargo:</label>
                          <input type="text" class="form-control" name="cargo" id="cargo" maxLength="20" placeholder="Cargo">   
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Login(*):</label>
                          <input class="form-control" type="text" name="login" id="login" maxLength="20" placeholder="descripcion" required>  
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Clave(*):</label>
                          <input class="form-control" type="password" name="clave" id="clave" maxLength="64" placeholder="Clave" required>  
                        </div>
                        
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="">Permisos</label>
                            <ul id="permisos" style="list-style: none;">

                            </ul>
                        </div>

                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Imagen:</label>
                          <input class="form-control" type="file" name="imagen" id="imagen">  
                          <input type="hidden" name="imagenactual" id="imagenactual">  
                          <img src="" width="150px" height="120px" id="imagenmuestra">
                        </div>
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          <div class="btn-toolbar pull-right">
                            <button class="btn btn-primary btn-lg" type="submit" id="btnGuardar"><i class="fa fa-save"></i>Guardar</button>
                            <button class="btn btn-danger btn-lg" onclick="cancelarForm()" type="button"><i class="fa fa-arrow-circle-left"></i>Cancelar</button>
                          </div>
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
<script type="text/javascript" src="scripts/usuario.js"></script>
<?php
}
//Liberamos el espacio en el buffer
ob_end_flush();
?>