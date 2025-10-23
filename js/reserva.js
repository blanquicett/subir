// Función para validar todo el formulario antes de enviar
// Función para validar todo el formulario antes de enviar
function validarFormulario() {
    const forms = document.querySelectorAll('.passenger-form');

    for (let index = 0; index < forms.length; index++) {
        const form = forms[index];

        // Validar campos requeridos del pasajero principal
        const requiredInputs = form.querySelectorAll('input[required]:not(.infante-input), select[required]');
        for (let input of requiredInputs) {
            if (!input.value.trim()) {
                alert('Por favor complete todos los campos requeridos para el Pasajero ' + (index + 1));
                input.focus();
                return false;
            }
        }

        // Validar correo electrónico
        const emailInput = form.querySelector('input[type="email"]');
        if (emailInput && emailInput.value.trim() && !validarEmail(emailInput.value.trim())) {
            alert('Por favor ingrese un correo electrónico válido para el Pasajero ' + (index + 1));
            emailInput.focus();
            return false;
        }

        // Validar que el documento solo contenga números (solo para el pasajero principal)
        const documentoInput = form.querySelector('input[name*="pasajeros[' + index + '][documento]"]:not([name*="infante"])');
        if (documentoInput && documentoInput.value.trim() && !/^\d+$/.test(documentoInput.value.trim())) {
            alert('El número de documento solo debe contener números para el Pasajero ' + (index + 1));
            documentoInput.focus();
            return false;
        }

        // Validar infante SOLO si está marcado el checkbox
        const tieneInfante = form.querySelector('input[id="tieneInfante' + index + '"]');
        if (tieneInfante && tieneInfante.checked) {
            const infanteForm = document.getElementById('infanteForm' + index);
            if (infanteForm && infanteForm.style.display !== 'none') {
                const infanteInputs = infanteForm.querySelectorAll('input.infante-input');
                for (let input of infanteInputs) {
                    if (!input.value.trim()) {
                        alert('Por favor complete todos los campos del infante para el Pasajero ' + (index + 1));
                        input.focus();
                        return false;
                    }
                }

                // Validar que el infante sea menor de 2 años
                const fechaNacimientoInfante = infanteForm.querySelector('input[name*="[infante][fechaNacimiento]"]');
                if (fechaNacimientoInfante && fechaNacimientoInfante.value) {
                    const fechaNac = new Date(fechaNacimientoInfante.value);
                    const hoy = new Date();
                    let edad = hoy.getFullYear() - fechaNac.getFullYear();
                    const mes = hoy.getMonth() - fechaNac.getMonth();

                    if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNac.getDate())) {
                        edad--;
                    }

                    if (edad >= 2) {
                        alert('El infante debe ser menor de 2 años para el Pasajero ' + (index + 1));
                        fechaNacimientoInfante.focus();
                        return false;
                    }
                }
            }
        }
    }

    return true; // Cambiado de isValid a true
}

function validarEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}
let contadorPasajeros = 1;
const maxPasajeros = 5;

function agregarPasajero() {
    if (contadorPasajeros >= maxPasajeros) {
        alert('Solo se permiten hasta 5 pasajeros por reserva');
        return;
    }

    const template = document.getElementById('pasajero1').cloneNode(true);
    template.id = 'pasajero' + contadorPasajeros;

    // Actualizar título
    template.querySelector('h4').textContent = 'Pasajero ' + (contadorPasajeros + 1);

    // Actualizar nombres de campos y requerimientos
    const inputs = template.querySelectorAll('input, select');
    inputs.forEach(input => {
        if (input.name) {
            // Mantener el atributo required si existía en el original
            const wasRequired = input.hasAttribute('required');
            input.name = input.name.replace('[0]', '[' + contadorPasajeros + ']');
            if (wasRequired) {
                input.required = true;
            }
        }
        if (input.id && input.id.includes('tieneInfante')) {
            input.id = 'tieneInfante' + contadorPasajeros;
            input.checked = false;
        }
        // Limpiar el valor
        if (input.type !== 'checkbox' && input.type !== 'radio') {
            input.value = '';
        }
    });

    // Actualizar formulario de infante
    const infanteForm = template.querySelector('[id^="infanteForm"]');
    infanteForm.id = 'infanteForm' + contadorPasajeros;
    infanteForm.style.display = 'none';

    // Actualizar el evento onChange del checkbox
    const checkbox = template.querySelector('[id^="tieneInfante"]');
    checkbox.setAttribute('onchange', `toggleInfante(${contadorPasajeros})`);

    // Limpiar valores
    template.querySelectorAll('input:not([type="checkbox"])').forEach(input => {
        input.value = '';
    });
    template.querySelectorAll('select').forEach(select => {
        select.selectedIndex = 0;
    });

    document.getElementById('pasajerosContainer').appendChild(template);
    contadorPasajeros++;

    // Actualizar visibilidad del botón
    if (contadorPasajeros >= maxPasajeros) {
        document.getElementById('btnAgregarPasajero').style.display = 'none';
    }
}

function toggleInfante(index) {
    const checkbox = document.getElementById('tieneInfante' + index);
    const infanteForm = document.getElementById('infanteForm' + index);

    if (checkbox.checked) {
        infanteForm.style.display = 'block';
        infanteForm.querySelectorAll('input').forEach(input => {
            input.required = true;
        });
    } else {
        infanteForm.style.display = 'none';
        infanteForm.querySelectorAll('input').forEach(input => {
            input.required = false;
            input.removeAttribute('required'); // Asegurarse de que se elimine el atributo
            input.value = '';
        });
    }
}

// Validar fecha de nacimiento para infantes
document.querySelectorAll('[name$="[infante][fechaNacimiento]"]').forEach(input => {
    input.addEventListener('change', function () {
        const fechaNacimiento = new Date(this.value);
        const hoy = new Date();
        const edad = hoy.getFullYear() - fechaNacimiento.getFullYear();

        if (edad >= 2) {
            alert('El infante debe ser menor de 2 años');
            this.value = '';
        }
    });
});