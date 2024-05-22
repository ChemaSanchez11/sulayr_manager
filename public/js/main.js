$(document).ready(function () {

    $('#certificate').on('click', async function (event) {
        console.log(await renewCertificate());
    });

    $('.action-collapse').on('click', function (event) {
        event.preventDefault();

        let element = $(this);
        let id = element.data('id');

        element.toggleClass('fa-angle-down fa-angle-up');

        let element_collapse = $('#collapse-' + id);
        element_collapse.collapse('toggle');

        // Obtén el contexto del lienzo
        let ctx = document.getElementById('chart-' + id).getContext('2d');

        // Datos para el gráfico
        let data = {
            labels: ["Usuarios Totales", "Volvieron A Conectarse", "Sin Reconexion"],
            datasets: [{
                label: 'Valores',
                data: [element_collapse.data('visit'), element_collapse.data('repeats'), element_collapse.data('no-repeats')],
                backgroundColor: [
                    'rgba(20,159,246, 0.5)',
                    'rgba(78,225,106,0.5)',
                    'rgba(236,111,64,0.5)',
                ],
                borderColor: [
                    'rgb(20,159,246)',
                    'rgb(78,225,106)',
                    'rgba(236,111,64, 1)',
                ],
                borderWidth: 1
            }]
        };

        // Opciones del gráfico
        let options = {
            responsive: true,
            maintainAspectRatio: false,
            scale: {
                ticks: {
                    beginAtZero: true
                }
            }
        };

        new Chart(ctx, {
            type: 'polarArea',
            data: data,
            options: options
        });
    });

    async function renewCertificate() {
        const url = '/sulayr_manager/api/renew';
        const options = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
        };

        try {
            const response = await fetch(url, options);
            return await response.json();
        } catch (e) {
            console.error(e);
        }
    }
});