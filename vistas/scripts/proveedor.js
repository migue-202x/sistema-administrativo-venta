var tabla; //variable que almacena todos los datos que vamos a ir actualizando en el datatables

//function que se ejecuta al inicio
function init(){
    mostrarForm(false);
    listar();

    $("#formulario").on("submit", function (event){
        guardaryeditar(event);
    })
}
//Funcion limpiar
function limpiar(){
    $("#nombre").val("");
    $("#num_documento").val("");
    $("#direccion").val("");
    $("#telefono").val("");
    $("#email").val("");
    $("#idpersona").val("");
}

//Funcion para mostrar formulario
function mostrarForm(flag){  
    limpiar();
    if(flag){
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnguardar").prop("disabled", false);
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
            url: '../ajax/persona.php?op=listarproveedores', //los datos los voy a obtener de esta url
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
    // event.preventDefault(); //para que no recargue la página 
    // $("#btnGuardar").prop("disabled", true);
    // const formData = FormData($("#formulario")[0]); //obtengo los datos de todo el formulario 
    
    event.preventDefault(); //para que no recargue la página 
    const formDatos = new FormData(event.currentTarget); 
    console.log(formDatos)

    $.ajax({
        url: "../ajax/persona.php?op=guardaryeditar",
        type: "POST",
        data: formDatos,
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

function mostrar(idpersona)
{
    $.post("../ajax/persona.php?op=mostrar", {idpersona : idpersona}, function(data, status)
    { //data lo recibo de la url  
        data = JSON.parse(data);
        mostrarForm(true);

        $("#nombre").val(data.nombre);
        $("#tipo_documento").val(data.tipo_documento);
        $("#tipo_documento").selectpicker('refresh');
        $("#num_documento").val(data.num_documento);
        $("#direccion").val(data.direccion);
        $("#telefono").val(data.telefono);
        $("#email").val(data.email);
        $("#idpersona").val(data.idpersona);
    })
}

//Function para eliminar registros
function eliminar(idpersona)
{
    bootbox.confirm("¿Esta Seguro de eliminar el proveedor?", function(result){
        if(result){ //si el usuario eligue la opcion SI
            $.post("../ajax/persona.php?op=eliminar", {idpersona : idpersona}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}
 
init();










