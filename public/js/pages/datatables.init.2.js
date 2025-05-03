$(document).ready(function () {
    // Initialize tables on each tab with unique IDs
    var tabPanes = $('.tab-pane');
    
    tabPanes.each(function() {
        var tabId = $(this).attr('id');
        var tableSelector = '#' + tabId + ' table';
        
        // Add ID to table if it doesn't have one
        if (!$(tableSelector).attr('id')) {
            $(tableSelector).attr('id', 'datatable-' + tabId);
        }
        
        // Initialize DataTable
        var table = $(tableSelector).DataTable({
            lengthChange: false,
            buttons: [
                { extend: 'excel', className: 'btn-primary' },
                { extend: 'pdf', className: 'btn-primary' },
                { extend: 'colvis', className: 'btn-primary' },
            ],
            order: [[0, "desc"]],
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
        
        // Append buttons container
        table.buttons().container()
            .appendTo('#' + tabId + ' .dataTables_wrapper .col-md-6:eq(0)');
    });
    
    // When tab is shown, adjust columns and redraw table
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        var targetTab = $(e.target).attr('href');
        $(targetTab + ' table').DataTable().columns.adjust().draw();
    });
    
    $(".dataTables_length select").addClass('form-select form-select-sm');
});