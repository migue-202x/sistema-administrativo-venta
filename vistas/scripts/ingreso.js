
var tabla; //variable que almacena todos los datos que vamos a ir actualizando en el datatables

//function que se ejecuta al inicio
function init(){
    mostrarForm(false);
    listar();

    //si apreto el boton Guarda llamo a guardaryeditar()
    $("#formulario").on("submit", function (event){
        guardaryeditar(event);
    });

    // Cargamos los items al select proveedor
    $.post("../ajax/ingreso.php?op=selectProveedor", function(r){
        $("#idproveedor").html(r);
        $("#idproveedor").selectpicker('refresh');
    });
}


//Funcion limpiar
function limpiar(){
    $("#idproveedor").val("");
    $("#proveedor").val("");
    $("#serie_comprobante").val("");
    $("#num_comprobante").val("");

    $("#total_compra").val("");
    $(".filas").remove("");
    $("#total").html("0");
}

//valores por defecto que tendran los input
function porDefecto(){
      //Obtenemos la fecha actual
      var now = new Date();
      var day = ("0" + now.getDate()).slice(-2); 
      var month = ("0" + (now.getMonth() + 1)).slice(-2); 
      var today = now.getFullYear()+ "-" + (month) + "-" + (day);
      $("#fecha_hora").val(today);
  
      //Marcamos el primer tipo_documento
      $("#tipo_comprobante").val("Boleta");
      $("#tipo_comprobante").selectpicker('refresh');
  
      //Dejo Publico General por defecto
      $("#idproveedor").val("15"); 
      $('#idproveedor').selectpicker('refresh');

      $("#impuesto").val("0");
}

//Funcion para mostrar formulario
function mostrarForm(flag){  
    porDefecto();
    // limpiar();
    if(flag){
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnagregar").hide();
        listarArticulos();

        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        $("#btnAgregarArt").show();
        detalles = 0;
    }else{
        $("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
    }
}

//ocultar formulario de nuevo ingreso
function cancelarForm(){
    limpiar();
    mostrarForm(false);
}

//Funcion listar
function listar()
{
    tabla = $('#tbllistado').dataTable({ 
        "aProcessing": true, //activamos el procesamiento del datatables
        "aServerSide": true, //paginación y filtrado realizados por el servidor
        dom: 'Bfrtip', //definimos los elementos del control de tabla
        //botones que nos van a permitir exportar
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
        "ajax": {
            url: '../ajax/ingreso.php?op=listar', //los datos los voy a obtener de esta url
            type: "get",
            dataType: "json",
            error:function(e){
                console.log(e.responsiveText);            }
        },
        "bDestroy": true,
        "iDisplayLength": 7, //Paginación cada 5 registros
        "order": [[ 0, "desc" ]] //ordenar los datos por columna de forma descendente
    }).DataTable();
}

//Funcion listar articulos
function listarArticulos()
{
    tabla = $('#tbllistarticulos').dataTable({ 
        "aProcessing": true, //activamos el procesamiento del datatables
        "aServerSide": true, //paginación y filtrado realizados por el servidor
        dom: 'Bfrtip', //definimos los elementos del control de tabla
        //botones que nos van a permitir exportar
        buttons: [
           
        ],
        "ajax": {
            url: '../ajax/ingreso.php?op=listarArticulos', //los datos los voy a obtener de esta url
            type: "get",
            dataType: "json",
            error:function(e){
                console.log(e.responsiveText);            }
        },
        "bDestroy": true,
        "iDisplayLength": 5, //Paginación cada 5 registros
        "order": [[ 0, "desc" ]] //ordenar los datos por columna de forma descendente
    }).DataTable();
}
   
//Funcion para guardar o editar
function guardaryeditar(event)
{
    event.preventDefault(); //para que no recargue la página
    //obtengo todos los objetos del Form (values)
    var formData = new FormData(event.currentTarget);
    //debugger
    $.ajax({
        url: "../ajax/ingreso.php?op=guardaryeditar",
        type: "POST", //envio los values en forma de POST
        data: formData,
        contentType: false, 
        processData: false,

        //si la funcion se ejecuta de manera correcta...
        success: function (datos){ 
            bootbox.alert(datos); //muestro en el alert los datos recibidos vendran de "categoria.php ajax"  
            mostrarForm(false); //oculto el formulario
            listar();
        }
    });
    limpiar(); //limpio los objetos del formulario
}

function mostrar(idingreso){

    $.post("../ajax/ingreso.php?op=mostrar", {idingreso : idingreso}, function(data, status)
    { //data lo recibo de la url 
        data = JSON.parse(data);
        
        $("#idproveedor").val(data.idproveedor);
        $("#idproveedor").selectpicker('refresh');
        $("#tipo_comprobante").val(data.tipo_comprobante);
        $("#tipo_comprobante").selectpicker('refresh');
        
        $("#serie_comprobante").val(data.serie_comprobante);
        $("#num_comprobante").val(data.num_comprobante);
        $("#fecha_hora").val(data.fecha);
        $("#impuesto").val(data.impuesto);
        $("#idingreso").val(data.idingreso);
        
        //ocultar y mostrar los botones
        $("#btnGuardar").hide();  
        $("#btnCancelar").show(); 
        $("#btnAgregarArt").hide(); 

        mostrarForm(true);
    });

    $.post("../ajax/ingreso.php?op=listarDetalle&id=" + idingreso, function(r){
        $("#detalles").html(r);
    });
}

//Function para desactivar registros
function anular(idingreso)
{
    bootbox.confirm("¿Está Seguro de anular el ingreso?", function(result){
        if(result){ //si el usuario eligue la opcion SI
            $.post("../ajax/ingreso.php?op=anular", {idingreso : idingreso}, function(e){
                bootbox.alert(e);
                var tabla = $('#tbllistado').DataTable();
	            tabla.ajax.reload();
            });
        }
    })
}

//Declaración de variables necesarias para trabajar con las compras y sus detalles
var impuesto = 18;
var cont = 0;
var row = 0; 
var detalles = 0;
// $("#guardar").hide(); 
$("#btnGuardar").hide(); //cuando tenga mas de un detalle se va a visualizar el boton Guardar
$("#tipo_comprobante").change(marcarImpuesto)//cuando cambie de valor va a llamar a la funcion marcarImpuesto

function marcarImpuesto(){
    //lo que seleccione en el select se almacenara en tipo_comprobante
    var tipo_comprobante = $("#tipo_comprobante option:selected").text();
    
    //solamente voy a tener impuesto cuando el tipo de comprobante sea igual a Factura
    if(tipo_comprobante == 'Factura'){
        $("#impuesto").val(impuesto)
    }else{
        $("#impuesto").val("0")
    }
}

function agregarDetalle(idarticulo, articulo, precio_venta, fila){
    row = fila; 
    $("#agregarDetalle"+fila).hide();

    $.post("../ajax/ingreso.php?op=precioDeVenta&idarticulo=" + idarticulo, function(data){
        data = JSON.parse(data);
        var cantidad = 1;

        if (data != null){
            var precio_compra = data.precio_compra; 
        }else{
            var precio_compra = 1; 
        }
        
        // cont: contendra el id de la fila
        if (idarticulo != ""){
            var subtotal = cantidad * precio_compra;
            var fila = '<tr class="filas" id="fila'+cont+'">' + 
                    '<td> <button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+','+row+')">X</button> </td>' + 
                    '<td> <input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td>' + 
                    '<td> <input type="number" name="cantidad[]" id="cantidad[]" min="1" value="'+cantidad+'"</td>' +
                    '<td> <input type="number" step="0.01" name="precio_compra[]" id="precio_compra[]" min="1" value="'+precio_compra+'"></td>' + 
                    '<td> <input type="number" step="0.01" name="precio_venta[]" id="precio_venta[]" value="'+precio_venta+'"></td>' + 
                    '<td><span name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>' +  
                    '<td> <button type="button" onclick="modificarSubtotales()" class="btn btn-info"><i class="fa fa-refresh"></i></button> </td>' + 
                '</tr>';
                cont++;
                detalles++;
                $('#detalles').append(fila);
                modificarSubtotales();
        }else{
            alert("Error al ingresar el detalle, revisar los datos del articulo")
        }
    });
        
}

function modificarSubtotales(){
    var cantidad = document.getElementsByName("cantidad[]");
    var precio = document.getElementsByName("precio_compra[]");
    var subtotal = document.getElementsByName("subtotal");

    for (var i = 0; i < cantidad.length; i++){
        var inputCantidad = cantidad[i];
        var inputPrecio = precio[i];
        var inputSubtotal = subtotal[i];

        inputSubtotal.value = inputCantidad.value * inputPrecio.value;
        document.getElementsByName("subtotal")[i].innerHTML = inputSubtotal.value;
    }
    calcularTotales();
}

function calcularTotales(){
    //array subtotal
    var subtotal = document.getElementsByName("subtotal");
    var total = 0.0;

    for (var i = 0; i < subtotal.length; i++){
        total += document.getElementsByName("subtotal")[i].value;
    }
    $("#total").html("S/. " + total);
    $("#total_compra").val(total);
    evaluar();
}

//me muestra los botones de guardar si tengo al menos un detalle
function evaluar(){
    if (detalles > 0){
        $("#btnGuardar").show();
    }else{
        $("#btnGuardar").hide();
        cont = 0;
        row = -1; 
    }
}

function eliminarDetalle(indice, row){
    $("#agregarDetalle" + row).show();
    $("#fila" + indice).remove();
    calcularTotales();
    detalles = detalles - 1;
    evaluar();
}

init();


