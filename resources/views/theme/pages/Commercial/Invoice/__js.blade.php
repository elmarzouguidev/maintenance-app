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
        console.log(client[0] ? client[0].id : 'none');
        return client[0] ? client[0].id : '';
    }

    function getStatus() {
        let status = document.getElementById("statusList");
        console.log(status.value);
        return status.value;
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
        let comanyIds = getChecked("company");
        let statusIds = getStatus();
        let clientId = getSelected();
        let getDate = getDateFilter();

        let href = '{{ collect(request()->segments())->last() }}?';

        if (comanyIds.length) {
            href += 'appFilter[GetCompany]=' + comanyIds;
        }

        if (statusIds && statusIds.length) {
            href += '&appFilter[GetStatus]=' + statusIds;
        }
        if (clientId && clientId.length) {
            href += '&appFilter[GetClient]=' + clientId;
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
