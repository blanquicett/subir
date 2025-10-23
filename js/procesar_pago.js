function selectPayment(method) {
    // Deseleccionar todos
    document.querySelectorAll('.payment-method').forEach(el => {
        el.classList.remove('selected');
    });

    // Ocultar todos los formularios
    document.querySelectorAll('.card-form').forEach(form => {
        form.classList.remove('show');
    });

    // Seleccionar el método actual
    const radio = document.getElementById('metodo_' + method);
    radio.checked = true;
    radio.closest('.payment-method').classList.add('selected');

    // Mostrar el formulario correspondiente
    const form = document.getElementById(method + 'Form');
    if (form) {
        form.classList.add('show');
    }
}

// Formatear número de tarjeta
document.getElementById('cardNumber')?.addEventListener('input', function (e) {
    let value = e.target.value.replace(/\s/g, '');
    let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
    e.target.value = formattedValue;

    document.getElementById('displayCardNumber').textContent =
        formattedValue || '•••• •••• •••• ••••';
});

// Actualizar nombre en tarjeta visual
document.getElementById('cardName')?.addEventListener('input', function (e) {
    document.getElementById('displayCardName').textContent =
        e.target.value.toUpperCase() || 'NOMBRE APELLIDO';
});

// Formatear fecha de expiración
document.getElementById('cardExpiry')?.addEventListener('input', function (e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.slice(0, 2) + '/' + value.slice(2, 4);
    }
    e.target.value = value;

    document.getElementById('displayExpiry').textContent = value || 'MM/YY';
});

// Validar solo números en CVV
document.getElementById('cardCVV')?.addEventListener('input', function (e) {
    e.target.value = e.target.value.replace(/\D/g, '');
});

// Validación del formulario
document.getElementById('paymentForm').addEventListener('submit', function (e) {
    const selectedMethod = document.querySelector('input[name="metodoPago"]:checked');

    if (!selectedMethod) {
        e.preventDefault();
        alert('Por favor seleccione un método de pago');
        return false;
    }

    // Mostrar indicador de carga
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
});