
var tabla; //variable que almacena todos los datos que vamos a ir actualizando en el datatables


//function que se ejecuta al inicio
function init(){
    mostrarForm(false);
    listar();

    //si apreto el boton Guarda llamo a guardaryeditar()
    $("#formulario").on("submit", function (event){
        guardaryeditar(event);
    })

    // Cargamos los items al select categoria
    $.post("../ajax/articulo.php?op=selectCategoria", function(r){
        // alert(r);
        $("#idcategoria").html(r);
        $("#idcategoria").selectpicker('refresh');    
    })

    $("#imagenmuestra").hide();

}


//Funcion limpiar
function limpiar(){
    $("#codigo").val("");
    $("#nombre").val("");
    $("#descripcion").val("");
    $("#imagenactual").val("");
    $("#stock").val("0");
    $("#imagenmuestra").attr("src", "");
    $("#print").hide();
    $("#idarticulo").val("");

    $("#talle").val("");
    $("#precio_venta").val("0.00");
    $("#precio_compra").val("0.00");
}

//Funcion para mostrar formulario
function mostrarForm(flag){  
    limpiar();
    if(flag){
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnguardar").prop("disabled", false);
        $("#btnagregar").hide();
        $("#btnreporte").hide();

        // $("#PrecioVenta").hide();   
    }else{
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
        $("#btnreporte").show();
    }
}

//ocultar formulario
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
            url: '../ajax/articulo.php?op=listar', //los datos los voy a obtener de esta url
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
   
//Funcion para guardar o editar
function guardaryeditar(event)
{
    event.preventDefault(); //para que no recargue la página
    // $("#btnGuardar").prop("disabled", true);
    // var formData = FormData($("#formulario")[0]); //obtengo los datos de todo el formulario 
    var formData = new FormData(event.currentTarget);
    //debugger
    $.ajax({
        url: "../ajax/articulo.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false, 
        processData: false,

        //si la funcion se ejecuta de manera correcta...
        success: function (datos){ 
            bootbox.alert(datos); //muestro en el alert los datos recibidos vendran de "categoria.php ajax"  
            mostrarForm(false); //oculto el formulario
            tabla.ajax.reload(); //recargo la tabla
        }
    });
    limpiar(); //limpio los objetos del formulario
}

function mostrar(idarticulo)
{
    $.post("../ajax/articulo.php?op=mostrar", {idarticulo : idarticulo}, function(data, status)
    { //data lo recibo de la url 
        data = JSON.parse(data);

        // console.log(data);
        mostrarForm(true);

        $("#idcategoria").val(data.idcategoria);
        $("#idcategoria").selectpicker('refresh');
        $("#codigo").val(data.codigo);
        $("#nombre").val(data.nombre);
        $("#stock").val(data.stock);
        $("#descripcion").val(data.descripcion);
        $("#imagenmuestra").show();
        $("#imagenmuestra").attr("src", "../files/articulos/" + data.imagen);
        $("#imagenactual").val(data.imagen);
        $("#talle").val(data.talle);
        $("#idarticulo").val(data.idarticulo);
        
        //--------------------------------------------------------------------------
        // $("#PrecioVenta").show();   
        $("#precio_venta").val(data.precio_venta);
        $("#precio_compra").val(data.precio_compra);
        
        //--------------------------------------------------------------------------
        generarbarcode(); 
    })
}

//Function para desactivar registros
function desactivar(idarticulo)
{
    bootbox.confirm("¿Está Seguro de desactivar el Articulo?", function(result){
        if(result){ //si el usuario eligue la opcion SI
            $.post("../ajax/articulo.php?op=desactivar", {idarticulo : idarticulo}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

//Function para activar registros
function activar(idarticulo)
{
    bootbox.confirm("¿Está Seguro de activar el Articulo?", function(result){
        if(result){ //si el usuario eligue la opcion SI
            $.post("../ajax/articulo.php?op=activar", {idarticulo : idarticulo}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

//funcion para generar el codigo de barras
function generarbarcode(){
    codigo = $("#codigo").val();
    $('#barcode').JsBarcode(codigo);
    $("#print").show();
}

 function imprimircodigobarra(){
     $("#print").printArea();
 }
init();










