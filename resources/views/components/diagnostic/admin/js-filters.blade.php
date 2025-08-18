<script>
    function getChecked(checkboxName) {
        let checkBoxes = document.getElementsByName(checkboxName);
        let ids = Array.prototype.slice.call(checkBoxes)
            .filter(ch => ch.checked == true)
            .map(ch => ch.value);
        return ids;
    }

    function getSelected() {
        let client = $('#clienterd').select2('data');
        console.log(client[0] ? client[0].id : 'none');
        return client[0] ? client[0].id : '';
    }

    function getTechnicienSelected() {
        let technicien = document.getElementById("technicienList");
        console.log(technicien.value);
        return technicien.value;
    }



    function getEtat() {
        let etat = document.getElementById("etatList");
        console.log(etat.value);
        return etat.value;
    }

    function getDateFilter() {
        let startDate = document.getElementById("filterDateStart");
        let endDate = document.getElementById("filterDateEnd");
        let startValue = startDate.value.trim();
        let endValue = endDate.value.trim();
        console.log(startValue, "##", endValue);
        
        // Only return dates if both are filled
        if (startValue && endValue) {
            return [startValue, endValue];
        }
        return [];
    }
    
    function filterResults() {
        let etatIds = getEtat();
        let clientId = getSelected();
        let technicienId = getTechnicienSelected();
        let getDate = getDateFilter();

        let href = '{{ collect(request()->segments())->last() }}?';

        if (etatIds && etatIds.length) {
            href += 'appFilter[GetEtat]=' + etatIds;
        }
        if (clientId && clientId.length) {
            href += '&appFilter[GetClient]=' + clientId;
        }
        if (technicienId && technicienId.length) {
            href += '&appFilter[GetTechnicien]=' + technicienId;
        }
        if (getDate.length > 0) {
            href += '&appFilter[DateBetween]=' + getDate;
        }
        document.location.href = href;
    }

    document.getElementById("filterData").addEventListener("click", function(event) {
        event.preventDefault();
        filterResults();
    });
</script>
