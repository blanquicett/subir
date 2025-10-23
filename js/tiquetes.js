document.getElementById("idAvion").addEventListener("change", function () {
    const idAvion = this.value;
    const asientoSelect = document.getElementById("idAsiento");

    asientoSelect.innerHTML = "<option value=''>Cargando asientos...</option>";

    fetch(`asientos_disponibles.php?idAvion=${idAvion}`)
        .then(response => response.json())
        .then(data => {
            asientoSelect.innerHTML = "";
            if (data.length > 0) {
                data.forEach(a => {
                    const opt = document.createElement("option");
                    opt.value = a.idAsiento;
                    opt.textContent = a.codigoAsiento;
                    asientoSelect.appendChild(opt);
                });
            } else {
                asientoSelect.innerHTML = "<option value=''>No hay asientos disponibles</option>";
            }
        });
});