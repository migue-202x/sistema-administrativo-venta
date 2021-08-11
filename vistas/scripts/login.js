
$("#frmAcceso").on('submit', function(e){
    e.preventDefault();

    login_acceso = $("#login_acceso").val();
    clave_acceso = $("#clave_acceso").val();

    $.post("../ajax/usuario.php?op=verificar", {"login_acceso":login_acceso, "clave_acceso":clave_acceso}, function(data){
        if (data != "null") {
            $(location).attr("href", "escritorio.php")
        }else{
            bootbox.alert("Usuario y/o Password incorrectos!");
        }
    });
})