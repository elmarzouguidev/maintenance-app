<script>
    function getIds(checkboxName) {
        let checkBoxes = document.getElementsByName(checkboxName);
        let ids = Array.prototype.slice.call(checkBoxes)
            .filter(ch => ch.checked == true)
            .map(ch => ch.value);
        return ids;
    }

    function filterResults() {
        
        let comanyIds = getIds("company");

        let statusIds = getIds("status");

        let href = 'invoices?';

        if (comanyIds.length) {
            href += 'appFilter[GetCompany]=' + comanyIds;
        }

        if (statusIds.length) {
            href += '&appFilter[GetStatus]=' + statusIds;
        }

        document.location.href = href;
    }

    document.getElementById("filter").addEventListener("click", filterResults);
</script>
