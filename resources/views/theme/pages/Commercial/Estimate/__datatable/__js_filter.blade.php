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

    // Period filter functions
    function setPeriod(period) {
        let href = '{{ collect(request()->segments())->last() }}?';
        href += 'appFilter[GetPeriod]=' + period;
        // Preserve other filters
        let currentUrl = new URL(window.location);
        let params = currentUrl.searchParams;
        
        if (params.has('appFilter[GetClient]')) {
            href += '&appFilter[GetClient]=' + params.get('appFilter[GetClient]');
        }
        if (params.has('appFilter[GetStatus]')) {
            href += '&appFilter[GetStatus]=' + params.get('appFilter[GetStatus]');
        }
        if (params.has('appFilter[GetCompany]')) {
            href += '&appFilter[GetCompany]=' + params.get('appFilter[GetCompany]');
        }
        if (params.has('appFilter[GetSend]')) {
            href += '&appFilter[GetSend]=' + params.get('appFilter[GetSend]');
        }
        
        document.location.href = href;
    }

    function setYearPeriod() {
        let href = '{{ collect(request()->segments())->last() }}?';
        href += 'appFilter[DateBetween]={{ now()->startOfYear()->format("Y-m-d") }},{{ now()->endOfYear()->format("Y-m-d") }}';
        // Preserve other filters
        let currentUrl = new URL(window.location);
        let params = currentUrl.searchParams;
        
        if (params.has('appFilter[GetClient]')) {
            href += '&appFilter[GetClient]=' + params.get('appFilter[GetClient]');
        }
        if (params.has('appFilter[GetStatus]')) {
            href += '&appFilter[GetStatus]=' + params.get('appFilter[GetStatus]');
        }
        if (params.has('appFilter[GetCompany]')) {
            href += '&appFilter[GetCompany]=' + params.get('appFilter[GetCompany]');
        }
        if (params.has('appFilter[GetSend]')) {
            href += '&appFilter[GetSend]=' + params.get('appFilter[GetSend]');
        }
        
        document.location.href = href;
    }

    function setLastYearPeriod() {
        let href = '{{ collect(request()->segments())->last() }}?';
        href += 'appFilter[DateBetween]={{ now()->subYear()->startOfYear()->format("Y-m-d") }},{{ now()->subYear()->endOfYear()->format("Y-m-d") }}';
        // Preserve other filters
        let currentUrl = new URL(window.location);
        let params = currentUrl.searchParams;
        
        if (params.has('appFilter[GetClient]')) {
            href += '&appFilter[GetClient]=' + params.get('appFilter[GetClient]');
        }
        if (params.has('appFilter[GetStatus]')) {
            href += '&appFilter[GetStatus]=' + params.get('appFilter[GetStatus]');
        }
        if (params.has('appFilter[GetCompany]')) {
            href += '&appFilter[GetCompany]=' + params.get('appFilter[GetCompany]');
        }
        if (params.has('appFilter[GetSend]')) {
            href += '&appFilter[GetSend]=' + params.get('appFilter[GetSend]');
        }
        
        document.location.href = href;
    }

    function setMonthPeriod() {
        let href = '{{ collect(request()->segments())->last() }}?';
        href += 'appFilter[DateBetween]={{ now()->startOfMonth()->format("Y-m-d") }},{{ now()->endOfMonth()->format("Y-m-d") }}';
        // Preserve other filters
        let currentUrl = new URL(window.location);
        let params = currentUrl.searchParams;
        
        if (params.has('appFilter[GetClient]')) {
            href += '&appFilter[GetClient]=' + params.get('appFilter[GetClient]');
        }
        if (params.has('appFilter[GetStatus]')) {
            href += '&appFilter[GetStatus]=' + params.get('appFilter[GetStatus]');
        }
        if (params.has('appFilter[GetCompany]')) {
            href += '&appFilter[GetCompany]=' + params.get('appFilter[GetCompany]');
        }
        if (params.has('appFilter[GetSend]')) {
            href += '&appFilter[GetSend]=' + params.get('appFilter[GetSend]');
        }
        
        document.location.href = href;
    }

    function setLastMonthPeriod() {
        let href = '{{ collect(request()->segments())->last() }}?';
        href += 'appFilter[DateBetween]={{ now()->subMonth()->startOfMonth()->format("Y-m-d") }},{{ now()->subMonth()->endOfMonth()->format("Y-m-d") }}';
        // Preserve other filters
        let currentUrl = new URL(window.location);
        let params = currentUrl.searchParams;
        
        if (params.has('appFilter[GetClient]')) {
            href += '&appFilter[GetClient]=' + params.get('appFilter[GetClient]');
        }
        if (params.has('appFilter[GetStatus]')) {
            href += '&appFilter[GetStatus]=' + params.get('appFilter[GetStatus]');
        }
        if (params.has('appFilter[GetCompany]')) {
            href += '&appFilter[GetCompany]=' + params.get('appFilter[GetCompany]');
        }
        if (params.has('appFilter[GetSend]')) {
            href += '&appFilter[GetSend]=' + params.get('appFilter[GetSend]');
        }
        
        document.location.href = href;
    }

    function filterResults() {
        let clientId = getSelected();
        let statusId = getStatus();
        let comanyIds = getChecked("company");
        let sendId = getChecked("send");
        let getDate = getDateFilter();

        let href = '{{ collect(request()->segments())->last() }}?';

        if (comanyIds.length) {
            href += 'appFilter[GetCompany]=' + comanyIds;
        }

        if (statusId && statusId.length) {
            href += '&appFilter[GetStatus]=' + statusId;
        }
        if (clientId && clientId.length) {
            href += '&appFilter[GetClient]=' + clientId;
        }
        if (sendId.length) {
            href += '&appFilter[GetSend]=' + sendId;
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
