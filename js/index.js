$(document).ready(function () {
    // Inicializar Select2 en los selects
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: $(this).data('placeholder'),
        allowClear: true
    });

    // Manejar visibilidad de fecha_vuelta según tipo de vuelo
    $('input[name="tipo_vuelo"]').change(function () {
        const fechaVueltaContainer = $('#fecha_vuelta_container');
        const fechaVueltaInput = $('input[name="fecha_vuelta"]');

        if ($(this).val() === 'ida') {
            fechaVueltaContainer.hide();
            fechaVueltaInput.prop('required', false);
        } else {
            fechaVueltaContainer.show();
            fechaVueltaInput.prop('required', true);
        }
    });

    // Trigger inicial para establecer estado correcto
    $('input[name="tipo_vuelo"]:checked').trigger('change');

    // Validar que destino != origen
    $('form').on('submit', function (e) {
        const origen = $('select[name="origen"]').val();
        const destino = $('select[name="destino"]').val();

        if (origen === destino) {
            alert('El origen y destino no pueden ser iguales');
            e.preventDefault();
            return false;
        }

        // Validar que fecha_vuelta > fecha_ida si es ida_vuelta
        if ($('input[name="tipo_vuelo"]:checked').val() === 'ida_vuelta') {
            const fechaIda = new Date($('input[name="fecha_ida"]').val());
            const fechaVuelta = new Date($('input[name="fecha_vuelta"]').val());

            if (fechaVuelta <= fechaIda) {
                alert('La fecha de vuelta debe ser posterior a la fecha de ida');
                e.preventDefault();
                return false;
            }
        }
    });
});