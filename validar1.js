function validar(){
    var correo, clave;
    correo= document.getElementById("correo").value;
    clave= document.getElementById("clave").value;
    
    expresion = /\w+@\w\.+[a-z]/;
    
    if(correo ==="" || clave ===""){
        alert("Todos los Campos son Obligatorios");
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
    else if(clave.length>20){
        alert("La clave debe de tener como maximo 20 caracteres");
        return false;
    }

    if (correo !== "" || clave !== "") {
        intentos++;
        if (intentos >= 3) {
            alert("❌ Excediste los 3 intentos. Vuelvelo a intentar en 3 minutos.");
            bloqueadoHasta = new Date().getTime() + (3 * 60 * 1000); // 3 minutos
            intentos = 0; // Reinicia el contador
        } else {
            const restantes = 3 - intentos;
            alert("Correo o contraseña incorrectos. Intentos restantes: " + restantes);
        }
        return false;
    } else {
        alert("✅ Bienvenido, acceso concedido.");
        intentos = 0; // Reinicia si es correcto
        return true;
    }
}

