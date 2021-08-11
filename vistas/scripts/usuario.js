
var tabla; //variable que almacena todos los datos que vamos a ir actualizando en el datatables


//function que se ejecuta al inicio
function init(){
    mostrarForm(false);
    listar();

    //si apreto el boton Guarda llamo a guardaryeditar()
    $("#formulario").on("submit", function (event){
        guardaryeditar(event);
    })

    $("#imagenmuestra").hide();

    //Mostramos los permisos
    $.post("../ajax/usuario.php?op=permisos&id=", function(r){
        $("#permisos").html(r);
    });
}


//Funcion limpiar
function limpiar(){
    $("#nombre").val("");
    $("#num_documento").val("");
    $("#direccion").val("");
    $("#telefono").val("");
    $("#email").val("");
    $("#cargo").val("");
    $("#login").val("");
    $("#clave").val("");
    $("#imagenmuestra").attr("src", "");
    $("#imagenactual").val("");
    $("#idusuario").val("");
}

//Funcion para mostrar formulario
function mostrarForm(flag){  
    limpiar();
    if(flag){
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnguardar").prop("disabled", false);
        $("#btnagregar").hide();
    }else{
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
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
            url: '../ajax/usuario.php?op=listar', //los datos los voy a obtener de esta url
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
    // var formData = FormData($("#formulario")[0]); //obtengo los datos de todo el formulario 
    var formData = new FormData(event.currentTarget);
    //debugger
    $.ajax({
        url: "../ajax/usuario.php?op=guardaryeditar",
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

function mostrar(idusuario)
{
    $.post("../ajax/usuario.php?op=mostrar", {idusuario : idusuario}, function(data, status)
    { //data lo recibo de la url 
        data = JSON.parse(data);

        // console.log(data);
        mostrarForm(true);

        $("#nombre").val(data.nombre);
        $("#tipo_documento").val(data.tipo_documento);
        $("#tipo_documento").selectpicker('refresh');
        $("#num_documento").val(data.num_documento);
        $("#direccion").val(data.direccion);
        $("#telefono").val(data.telefono);
        $("#email").val(data.email);
        $("#cargo").val(data.cargo);
        $("#login").val(data.login);
        $("#clave").val(data.clave);
        $("#imagenmuestra").show();
        $("#imagenmuestra").attr("src", "../files/usuarios/" + data.imagen);
        $("#imagenactual").val(data.imagen);
        $("#idusuario").val(data.idusuario);
    });
    $.post("../ajax/usuario.php?op=permisos&id="+idusuario, function(r){
        $("#permisos").html(r);
    });
    
}

//Function para desactivar registros
function desactivar(idusuario)
{
    bootbox.confirm("¿Está Seguro de desactivar el Usuario?", function(result){
        if(result){ //si el usuario eligue la opcion SI
            $.post("../ajax/usuario.php?op=desactivar", {idusuario : idusuario}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

//Function para activar registros
function activar(idusuario)
{
    bootbox.confirm("¿Está Seguro de activar el Usuario?", function(result){
        if(result){ //si el usuario eligue la opcion SI
            $.post("../ajax/usuario.php?op=activar", {idusuario : idusuario}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

init();










