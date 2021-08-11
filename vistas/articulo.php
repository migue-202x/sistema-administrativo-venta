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
                    <h1 class="display-1 text-success">
                          <u>Artículo</u>  
                          <button class="btn btn-success btn-lg" id="btnagregar" onclick="mostrarForm(true)"><i class="fa fa-plus-circle"></i>Agregar</button>
                          <a target="_blank" href="../reportes/rptarticulos.php"><button class="btn btn-info btn-lg" id="btnreporte">Reporte</button></a>
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
                          <th>Categoria</th>
                          <th>Descripcion</th>
                          <th>Precio compra</th>
                          <th>Precio venta</th>
                          <th>Talle</th>
                          <th>Stock</th>
                          <th>Imagen</th>
                          <th>Estado</th>
                        </thead>
                          <!-- el tbody lo muestro con el datatable -->
                        <tbody> 
                        </tbody>
                        <tfoot>
                          <th>Opciones</th>
                          <th>Nombre</th>
                          <th>Categoria</th>
                          <th>Descripcion</th>
                          <th>Precio compra</th>
                          <th>Precio venta</th>
                          <th>Talle</th>
                          <th>Stock</th>
                          <th>Imagen</th>
                          <th>Estado</th>
                        </tfoot>
                      </table>
                    </div>
                    <!-- FORMULARIO DE REGISTROS -->
                    <div class="panel-body" id="formularioregistros">
                      <form name="formulario" id="formulario" method="POST">
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Nombre(*):</label>
                          <input type="hidden" name="idarticulo" id="idarticulo">
                          <input class="form-control" type="text" name="nombre" id="nombre" maxLength="100" placeholder="Ingrese el nombre" required>   
                        </div>
                        
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Categoria(*):</label>
                          <select name="idcategoria" id="idcategoria" class="form-control selectpicker" data-live-search="true" required></select>  
                        </div>

                        <div id="Preciocompra" class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Precio compra:</label>
                          <input class="form-control" type="number" name="precio_compra" id="precio_compra" step="0.01" min="0" placeholder="Ingrese el precio de compra" required>  
                        </div>

                        <div id="PrecioVenta" class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Precio venta:</label>
                          <input class="form-control" type="number" name="precio_venta" id="precio_venta" step="0.01" min="0" placeholder="Ingrese el precio de venta" required>  
                        </div>
            
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Descripcion:</label>
                          <input class="form-control" type="text" name="descripcion" id="descripcion" maxLength="256" placeholder="Ingrese una descripción">  
                        </div>
              
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Talle:</label>
                          <input class="form-control" type="number" name="talle" id="talle" min="0" maxlength="2" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ingrese el talle" required>  
                        </div>

                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Stock(*):</label>
                          <input class="form-control" type="number" name="stock" id="stock" min="0" required>   
                        </div>

                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Imagen:</label>
                          <input class="form-control" type="file" name="imagen" id="imagen">  
                          <input type="hidden" name="imagenactual" id="imagenactual">  
                          <img src="" width="150px" height="120px" id="imagenmuestra">
                        </div>
                        <!-- <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Código:</label>
                          <input class="form-control" type="text" name="codigo" id="codigo" placeholder="código de barras">
                          <button type="button" class="btn btn-success" onclick="generarbarcode()">Generar</button>  
                          <button type="button" class="btn btn-info" onclick="imprimircodigobarra()">Imprimir</button>  
                          <div id="print">
                            <svg id="barcode"></svg> 
                          </div> -->
                        <!-- </div> -->
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
<script type="text/javascript" src="../public/js/JsBarcode.all.min.js"></script>
<script type="text/javascript" src="../public/js/jquery.PrintArea.js"></script>
<script type="text/javascript" src="scripts/articulo.js"></script>
<?php
}
//Liberamos el espacio en el buffer
ob_end_flush();
?>