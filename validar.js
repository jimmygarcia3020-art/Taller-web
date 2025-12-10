function validar(){
    var nombre, negocio, numero, correo, clave;
    nombre= document.getElementById("nombre_contacto").value;
    negocio= document.getElementById("nombre_negocio").value;
    numero= document.getElementById("numero_contacto").value;
    correo= document.getElementById("correo").value;
    clave= document.getElementById("clave").value;
    
    expresion = /\w+@\w\.+[a-z]/;
    
    if(nombre ==="" || negocio ===""|| numero==="" || correo ==="" || clave ===""){
        alert("Todos los Campos son Obligatorios")
        return false;
    }
    else if(nombre.length>30){
        alert("El Nombre es muy Largo");
        return false;
    }
    else if(negocio.length>80){
        alert("Los Negocios son muy Largos");
        return false;
    }
    else if(numero.length===8){
        alert("El numero de Telefono es muy corto");
        return false;
    }
    else if(isNaN(numero)){
        alert("El valor no es numerico");
        return false;
    }
    else if(correo.length>100){
        alert("El correo es muy Largo");
        return false;
    }
    else if(expresion.test(correo)){
        alert("El correo no es valido");
        return false;        
    }
    else if(clave.length>8 || clave.length>20){
        alert("La clave debe de tener como maximo 20 caracteres");
        return false;
    }
}