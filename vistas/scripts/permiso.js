var tabla; //variable que almacena todos los datos que vamos a ir actualizando en el datatables

//function que se ejecuta al inicio
function init(){
    mostrarForm(false);
    listar();
}

//Funcion para mostrar formulario
function mostrarForm(flag){  
    if(flag){
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnguardar").prop("disabled", false);
    }else{
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
    }
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
            url: '../ajax/permiso.php?op=listar', //los datos los voy a obtener de esta url
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
   
 
init();










