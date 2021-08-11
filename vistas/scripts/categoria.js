var tabla; //variable que almacena todos los datos que vamos a ir actualizando en el datatables

//function que se ejecuta al inicio
function init(){
    mostrarForm(false);
    listar();

    //si apreto el boton Guarda llamo a guardaryeditar()
    $("#formulario").on("submit", function (event){
        guardaryeditar(event);
    })
}
//Funcion limpiar
function limpiar(){
    $("#idcategoria").val("");
    $("#nombre").val("");
    $("#descripcion").val("");
}

//Funcion para mostrar formulario
function mostrarForm(flag){  
    limpiar();
    if(flag){
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        // $("#btnGuardar").prop("disabled", false);
    }else{
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
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
            url: '../ajax/categoria.php?op=listar', //los datos los voy a obtener de esta url
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
    // $("#btnGuardar").prop("disabled", true);
    //var formData = FormData($("#formulario")[0]); //obtengo los datos de todo el formulario 
    var formData = new FormData(event.currentTarget);
    //debugger
    $.ajax({
        url: "../ajax/categoria.php?op=guardaryeditar",
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

function mostrar(idcategoria)
{
    $.post("../ajax/categoria.php?op=mostrar", {idcategoria : idcategoria}, function(data, status)
    { //data lo recibo de la url 
        data = JSON.parse(data);
        mostrarForm(true);

        $("#nombre").val(data.nombre);
        $("#descripcion").val(data.descripcion);
        $("#idcategoria").val(data.idcategoria);
    })
}

//Function para desactivar registros
function desactivar(idcategoria)
{
    bootbox.confirm("¿Esta Seguro de desactivar la Categoria?", function(result){
        if(result){ //si el usuario eligue la opcion SI
            $.post("../ajax/categoria.php?op=desactivar", {idcategoria : idcategoria}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

//Function para activar registros
function activar(idcategoria)
{
    bootbox.confirm("¿Esta Seguro de activar la Categoria?", function(result){
        if(result){ //si el usuario eligue la opcion SI
            $.post("../ajax/categoria.php?op=activar", {idcategoria : idcategoria}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}


 
init();










