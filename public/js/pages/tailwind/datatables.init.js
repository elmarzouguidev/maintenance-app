/*
 * DataTables for pages on the Tailwind layout: Tailwind twin of js/pages/datatables.init.js.
 * Load DataTables core, Buttons and Responsive WITHOUT the *-bs4 integration files;
 * the look comes from resources/css/app.css (.dataTables_* / .dt-* rules).
 * Same tables and options as the legacy script; only the layout (dom) and button classes differ.
 */
$(function () {
    $('#datatable').DataTable();

    $('#datatable-buttons').DataTable({
        lengthChange: false,
        dom: '<"dt-toolbar"Bf>rt<"dt-footer"ip>',
        buttons: ['excel', 'pdf', 'colvis'],
        order: [[0, 'desc']],
        autoWidth: true,
        pageLength: 30,
        language: {
            lengthMenu: 'Afficher _MENU_ éléments par page',
            zeroRecords: 'Aucune donnée disponible dans le tableau',
            info: "Affichage de l'élément _PAGE_ sur _PAGES_",
            infoEmpty: 'Aucune donnée disponible',
            infoFiltered: '(filtered from _MAX_ total records)',
            paginate: {
                first: 'First',
                last: 'Last',
                next: 'Suivant',
                previous: 'Précédent',
            },
            search: 'Chercher :',
        },
    });
});
