"use strict";

/**
 * validar_registro.js
 * Validación centralizada del formulario de registro.
 * No utiliza handlers inline en HTML.
 */

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("registroForm");
    const formAlert = document.getElementById("formAlert");

    const nombre = document.getElementById("nombre_contacto");
    const negocio = document.getElementById("nombre_negocio");
    const telefono = document.getElementById("numero_contacto");
    const tipoUsuario = document.getElementById("tipo_usuario");
    const correo = document.getElementById("correo");
    const clave = document.getElementById("clave");
    const regimen = document.getElementById("regimen");

    if (!form) return;

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    const clearAlert = () => {
        if (!formAlert) return;
        formAlert.classList.add("hidden");
        formAlert.textContent = "";
    };

    const showAlert = (message) => {
        if (!formAlert) return;
        formAlert.textContent = message;
        formAlert.classList.remove("hidden");
    };

    const setInvalid = (element, message) => {
        if (!element) return;
        element.setAttribute("aria-invalid", "true");
        element.classList.add("border-red-500", "ring-4", "ring-red-100");
        if (message) {
            element.setCustomValidity(message);
        }
    };

    const setValid = (element) => {
        if (!element) return;
        element.removeAttribute("aria-invalid");
        element.classList.remove("border-red-500", "ring-4", "ring-red-100");
        element.setCustomValidity("");
    };

    const resetFieldState = () => {
        [
            nombre,
            negocio,
            telefono,
            tipoUsuario,
            correo,
            clave,
            regimen,
        ].forEach(setValid);
    };

    const getSelectedTipoCliente = () => {
        const checked = form.querySelector('input[name="tipo_cliente"]:checked');
        return checked ? checked.value : "";
    };

    const validate = () => {
        clearAlert();
        resetFieldState();

        const nombreValue = nombre.value.trim();
        const negocioValue = negocio.value.trim();
        const telefonoValue = telefono.value.trim();
        const telefonoDigits = telefonoValue.replace(/\D/g, "");
        const tipoUsuarioValue = tipoUsuario.value.trim();
        const correoValue = correo.value.trim();
        const claveValue = clave.value;
        const regimenValue = regimen.value.trim();
        const tipoClienteValue = getSelectedTipoCliente();

        let firstInvalidField = null;

        const fail = (element, message) => {
            if (!firstInvalidField) firstInvalidField = element;
            setInvalid(element, message);
            showAlert(message);
            return false;
        };

        if (!nombreValue) {
            return fail(nombre, "El nombre completo es obligatorio.");
        }
        if (nombreValue.length > 30) {
            return fail(nombre, "El nombre completo no debe superar 30 caracteres.");
        }

        if (!negocioValue) {
            return fail(negocio, "El nombre del negocio es obligatorio.");
        }
        if (negocioValue.length > 80) {
            return fail(negocio, "El nombre del negocio no debe superar 80 caracteres.");
        }

        if (!telefonoValue) {
            return fail(telefono, "El número de teléfono es obligatorio.");
        }
        if (telefonoDigits.length < 7 || telefonoDigits.length > 15) {
            return fail(telefono, "El teléfono debe tener entre 7 y 15 dígitos.");
        }

        if (!tipoUsuarioValue) {
            return fail(tipoUsuario, "Debe seleccionar un tipo de usuario.");
        }

        if (!correoValue) {
            return fail(correo, "El correo electrónico es obligatorio.");
        }
        if (correoValue.length > 100) {
            return fail(correo, "El correo electrónico no debe superar 100 caracteres.");
        }
        if (!emailRegex.test(correoValue)) {
            return fail(correo, "El correo electrónico no tiene un formato válido.");
        }

        if (!claveValue) {
            return fail(clave, "La contraseña es obligatoria.");
        }
        if (claveValue.length < 6 || claveValue.length > 20) {
            return fail(clave, "La contraseña debe tener entre 6 y 20 caracteres.");
        }

        if (!tipoClienteValue) {
            const radio = form.querySelector('input[name="tipo_cliente"]');
            return fail(radio, "Debe seleccionar un tipo de cliente.");
        }

        if (!regimenValue) {
            return fail(regimen, "Debe seleccionar un régimen fiscal.");
        }

        clearAlert();
        return true;
    };

    form.addEventListener("submit", (event) => {
        const isValid = validate();
        if (!isValid) {
            event.preventDefault();
            const invalidField = form.querySelector('[aria-invalid="true"]');
            if (invalidField && typeof invalidField.focus === "function") {
                invalidField.focus();
            }
        }
    });

    [
        nombre,
        negocio,
        telefono,
        tipoUsuario,
        correo,
        clave,
        regimen,
    ].forEach((field) => {
        field.addEventListener("input", () => {
            setValid(field);
            clearAlert();
        });

        field.addEventListener("change", () => {
            setValid(field);
            clearAlert();
        });
    });

    form.querySelectorAll('input[name="tipo_cliente"]').forEach((radio) => {
        radio.addEventListener("change", () => {
            clearAlert();
        });
    });
});