<script>
    //  line chart facturis.app
    var datachiffre = @json($datachiffre);
    var databills = @json($databills);
    console.log(datachiffre);
    var options = {
        chart: {
            height: 380,
            type: 'line',
            zoom: {
                enabled: false
            },
            toolbar: {
                show: false
            }
        },
        colors: ['#556ee6', '#34c38f'],
        dataLabels: {
            enabled: false,
        },
        stroke: {
            width: [3, 3],
            curve: 'smooth'
        },
        series: [{
                name: "Chiffre d'affaires",
                data: datachiffre.map(item => item.price),
            },
            {
                name: "Encaissements",
                data: databills.map(item => item.price),
            }
        ],
        title: {
            text: 'Montants par mois',
            align: 'left',
            style: {
                fontWeight: '500',
            },
        },
        grid: {
            row: {
                colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.2
            },
            borderColor: '#f1f1f1'
        },
        markers: {
            style: 'inverted',
            size: 6
        },
        xaxis: {
            categories: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre',
                'Octobre', 'Novembre', 'Décembre'
            ],
            /*title: {
                text: 'Month'
            }*/
        },
        /*yaxis: {
            title: {
                text: 'Temperature'
            },
             min: 5,
             max: 400
        },*/
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            floating: true,
            offsetY: -25,
            offsetX: -5
        },
        responsive: [{
            breakpoint: 600,
            options: {
                chart: {
                    toolbar: {
                        show: false
                    }
                },
                legend: {
                    show: false
                },
            }
        }]
    }

    var chart = new ApexCharts(
        document.querySelector("#line_chart_datalabel"),
        options
    );

    chart.render();
</script>
