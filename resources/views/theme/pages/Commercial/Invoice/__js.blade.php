<script>
    function getChecked(checkboxName) {
        let checkBoxes = document.getElementsByName(checkboxName);
        let ids = Array.prototype.slice.call(checkBoxes)
            .filter(ch => ch.checked == true)
            .map(ch => ch.value);
        return ids;
    }

    function getSelected() {
       let client = $('#clienter').select2('data');
       // console.log(client[0].id);
        return client[0].id;
    }

    function filterResults() {

        let comanyIds = getChecked("company");

        let statusIds = getChecked("status");

        let clientId = getSelected();

        console.log(clientId);

        let href = '{{ collect(request()->segments())->last() }}?';

        if (comanyIds.length) {
            href += 'appFilter[GetCompany]=' + comanyIds;
        }

        if (statusIds.length) {
            href += '&appFilter[GetStatus]=' + statusIds;
        }
        if (clientId.length) {
            href += '&appFilter[GetClient]=' + clientId;
        }

        document.location.href = href;
    }

   // document.getElementById("filter").addEventListener("click", filterResults);

    $(".chk-filter").on("click", function() {
        if (this.checked) {
           // $('#filter').click();
            filterResults()
        }
    });
</script>
