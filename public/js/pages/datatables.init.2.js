$(document).ready(function () {
    // Store table references
    var dataTables = {};
    
    // Initialize only the active tab's DataTable on page load
    var activeTabId = $('.tab-pane.active').attr('id');
    initializeDataTable(activeTabId);
    
    // When a tab is clicked, make sure its DataTable is initialized
    $('a[data-bs-toggle="tab"], a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var targetId = $(e.target).attr('href').substring(1); // Remove the # character
        initializeDataTable(targetId);
    });
    
    function initializeDataTable(tabId) {
        var tableSelector = '#' + tabId + ' table';
        
        // Check if DataTable is already initialized for this tab
        if (!dataTables[tabId]) {
            // Initialize DataTable
            dataTables[tabId] = $(tableSelector).DataTable({
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
            dataTables[tabId].buttons().container()
                .appendTo('#' + tabId + ' .dataTables_wrapper .col-md-6:eq(0)');
                
            // Style selects
            $('#' + tabId + ' .dataTables_length select').addClass('form-select form-select-sm');
        } else {
            // If already initialized, just redraw and adjust columns
            dataTables[tabId].columns.adjust().draw();
        }
    }
});