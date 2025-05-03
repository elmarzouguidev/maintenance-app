$(document).ready(function () {
    // Initialize all datatables at once, but keep references to them
    var tables = {};
    
    // Initialize the visible table on page load
    initializeDataTableInActiveTab();
    
    // When a tab is clicked, reinitialize the DataTable in that tab
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        initializeDataTableInActiveTab();
    });
    
    function initializeDataTableInActiveTab() {
        // Find the active tab
        var activeTabId = $('.tab-pane.active').attr('id');
        var activeTabSelector = '#' + activeTabId + ' table';
        
        // If this table was already initialized, destroy it first and remove wrapper elements
        if ($.fn.DataTable.isDataTable(activeTabSelector)) {
            $(activeTabSelector).DataTable().destroy();
            // Remove the added DOM elements to prevent duplication
            $(activeTabSelector + '_wrapper').remove();
        }
        
        // Initialize the DataTable in the active tab
        tables[activeTabId] = $(activeTabSelector).DataTable({
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
        
        // Append buttons container for this table
        tables[activeTabId].buttons().container()
            .appendTo('#' + activeTabId + ' .dataTables_wrapper .col-md-6:eq(0)');
            
        $(".dataTables_length select").addClass('form-select form-select-sm');
    }
});