
$(document).ready(function () {
    $('#datatable').DataTable();

    //Buttons examples
    var table = $('#datatable-buttons').DataTable({
        lengthChange: false,
        buttons: ['copy', 'excel', 'pdf'],
        order: [[0, "desc"]],
        autoWidth: true,
        pageLength: 60,
        columnDefs: [
            { "width": "10", },
            { "width": "10%", }
        ],
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