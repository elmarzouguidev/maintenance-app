$(document).ready(function () {
    $('#datatable').DataTable();

    //Facturis Datatables Run Datatable with buttons
    var table = $('#datatable-buttons').DataTable({
        lengthChange: false,
        /*ordering: false,*/
        buttons: [
            { extend: 'excel', className: 'btn-primary' },
            { extend: 'pdf', className: 'btn-primary' },
            { extend: 'colvis', className: 'btn-primary' },
        ],
        order: [[0, "asc"]],
        autoWidth: true,
        pageLength: 30,
        language: {
            "lengthMenu": "Afficher _MENU_ éléments par page",
            "zeroRecords": "Aucune donnée disponible dans le tableau",
            "info": "Affichage de l'élément _PAGE_ sur _PAGES_",
            "infoEmpty": "Aucune donnée disponible",
            "infoFiltered": "(filtered from _MAX_ total records)",
            "paginate": {
                "first": "First",
                "last": "Last",
                "next": "Suivant",
                "previous": "Précédent"
            },
            "search": "Chercher :",
        },

    });

    table.buttons().container()
        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

    $(".dataTables_length select").addClass('form-select form-select-sm');
});