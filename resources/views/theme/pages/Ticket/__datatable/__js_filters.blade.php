<script>
    function getChecked(checkboxName) {
        let checkBoxes = document.getElementsByName(checkboxName);
        let ids = Array.prototype.slice.call(checkBoxes)
            .filter(ch => ch.checked == true)
            .map(ch => ch.value);
        return ids;
    }

    function getSelected() {
        let client = $('#clientsList').select2('data');
        console.log('Selected client:', client[0] ? client[0].id : 'none');
        return client[0] ? client[0].id : '';
    }
    
    function getStatus() {
        let status = document.getElementById("statusList");
        console.log('Selected status:', status.value);
        return status.value;
    }

    function getStartDateFilter() {
        let startDate = document.getElementById("startDate");
        console.log('Start date:', startDate.value);
        return startDate.value.trim();
    }

    function getEndDateFilter() {
        let endDate = document.getElementById("endDate");
        console.log('End date:', endDate.value);
        return endDate.value.trim();
    }

    function filterResults() {
        // Get all filter values
        let etatId = getChecked("etat");
        let hasRouter = getChecked("has_router");
        let clientId = getSelected();
        let statusId = getStatus();
        let startDate = getStartDateFilter();
        let endDate = getEndDateFilter();

        // Build the filter URL
        let href = '{{ collect(request()->segments())->last() }}?';

        // Add filters to URL if they have values
        if (statusId && statusId.length) {
            href += '&appFilter[GetStatus]=' + statusId;
        }
        if (clientId && clientId.length) {
            href += '&appFilter[GetClient]=' + clientId;
        }
        if (etatId && etatId.length) {
            href += '&appFilter[GetEtat]=' + etatId;
        }
        if (hasRouter && hasRouter.length && hasRouter == 'on') {
            href += '&appFilter[GetRetour]=' + hasRouter;
        }
        if (startDate && startDate.length > 0) {
            href += '&appFilter[GetStartDate]=' + startDate;
        }
        if (endDate && endDate.length > 0) {
            href += '&appFilter[GetEndDate]=' + endDate;
        }

        console.log('Filter URL:', href);
        document.location.href = href;
    }

    // Add event listener to filter button
    document.getElementById("filterData").addEventListener("click", function(event) {
        event.preventDefault();
        filterResults();
    });

    // Optional: Add event listeners for real-time filtering
    // Uncomment if you want filters to apply automatically when changed
    /*
    $('#startDate, #endDate').on('change', function() {
        filterResults();
    });
    */
</script>


