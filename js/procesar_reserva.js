<?php// Configuración del mapa de asientos
const capacidad = <?= $vuelo['capacidad'] ?>;
const asientosOcupados = <?= json_encode($asientosOcupados) ?>;
const cantidadPasajeros = <?= $cantidadPasajeros ?>;
let asientosSeleccionados = [];

console.log('Capacidad del avión:', capacidad);
console.log('Asientos ocupados:', asientosOcupados);
console.log('Cantidad de pasajeros:', cantidadPasajeros);

// Configuración del layout del avión
const filas = Math.ceil(capacidad / 6); // 6 asientos por fila (3-3)
const container = document.getElementById('asientosContainer');

// Establecer el grid basado en el número de columnas (3 + pasillo + 3)
container.style.gridTemplateColumns = 'repeat(7, 1fr)';

// Generar asientos
let asientoActual = 1;
for (let fila = 0; fila < filas && asientoActual <= capacidad; fila++) {
    // Letras para las filas
    const letraFila = String.fromCharCode(65 + fila);

    for (let col = 1; col <= 6 && asientoActual <= capacidad; col++) {
        if (col === 4) {
            // Crear pasillo después de la columna 3
            const pasillo = document.createElement('div');
            pasillo.className = 'pasillo';
            container.appendChild(pasillo);
            continue;
        }

        const asiento = document.createElement('div');
        const numeroAsiento = letraFila + col;
        asiento.className = 'asiento';
        asiento.textContent = numeroAsiento;
        asiento.setAttribute('data-seat', numeroAsiento);

        // Verificar si el asiento está ocupado
        if (asientosOcupados.includes(numeroAsiento)) {
            asiento.classList.add('ocupado');
        } else {
            asiento.addEventListener('click', () => seleccionarAsiento(asiento, numeroAsiento));
        }

        container.appendChild(asiento);
        asientoActual++;
    }
}

function seleccionarAsiento(elemento, numeroAsiento) {
    if (elemento.classList.contains('ocupado')) return;

    // Toggle selección
    if (elemento.classList.contains('seleccionado')) {
        elemento.classList.remove('seleccionado');
        asientosSeleccionados = asientosSeleccionados.filter(a => a !== numeroAsiento);
    } else {
        // Verificar si ya se alcanzó el máximo de asientos
        if (asientosSeleccionados.length >= cantidadPasajeros) {
            alert('Ya ha seleccionado el número máximo de asientos permitido: ' + cantidadPasajeros);
            return;
        }
        elemento.classList.add('seleccionado');
        asientosSeleccionados.push(numeroAsiento);
    }

    console.log('Asientos seleccionados:', asientosSeleccionados);

    // Actualizar asignación de asientos
    actualizarAsignacionAsientos();

    // Actualizar estado del botón continuar
    document.getElementById('btnContinuar').disabled = asientosSeleccionados.length !== cantidadPasajeros;

    // Actualizar campo oculto con asientos seleccionados
    document.getElementById('asientosSeleccionados').value = JSON.stringify(asientosSeleccionados);
}

function actualizarAsignacionAsientos() {
    const asignaciones = document.querySelectorAll('#asignacionAsientos .mb-2');
    asignaciones.forEach((elem, index) => {
        const asiento = asientosSeleccionados[index];
        const label = elem.querySelector('.asiento-asignado');
        if (asiento) {
            label.textContent = ` - Asiento ${asiento}`;
            label.classList.remove('text-primary');
            label.classList.add('text-success');
        } else {
            label.textContent = ' - Pendiente de asignar';
            label.classList.remove('text-success');
            label.classList.add('text-primary');
        }
    });
}

// Validar antes de enviar
document.getElementById('asientosForm').addEventListener('submit', function (e) {
    if (asientosSeleccionados.length !== cantidadPasajeros) {
        e.preventDefault();
        alert('Por favor, seleccione exactamente ' + cantidadPasajeros + ' asiento(s) antes de continuar');
        return false;
    }

    console.log('Enviando formulario con asientos:', asientosSeleccionados);
    document.getElementById('asientosSeleccionados').value = JSON.stringify(asientosSeleccionados);
    return true;
});