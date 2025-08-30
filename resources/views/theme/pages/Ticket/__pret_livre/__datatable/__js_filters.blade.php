<script>
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
        
        document.location.href = href;
    }

    function setYearPeriod() {
        let href = '{{ collect(request()->segments())->last() }}?';
        href += 'appFilter[GetStartDate]={{ now()->startOfYear()->format("d-m-Y") }}&appFilter[GetEndDate]={{ now()->endOfYear()->format("d-m-Y") }}';
        // Preserve other filters
        let currentUrl = new URL(window.location);
        let params = currentUrl.searchParams;
        
        if (params.has('appFilter[GetClient]')) {
            href += '&appFilter[GetClient]=' + params.get('appFilter[GetClient]');
        }
        if (params.has('appFilter[GetStatus]')) {
            href += '&appFilter[GetStatus]=' + params.get('appFilter[GetStatus]');
        }
        
        document.location.href = href;
    }

    function setLastYearPeriod() {
        let href = '{{ collect(request()->segments())->last() }}?';
        href += 'appFilter[GetStartDate]={{ now()->subYear()->startOfYear()->format("d-m-Y") }}&appFilter[GetEndDate]={{ now()->subYear()->endOfYear()->format("d-m-Y") }}';
        // Preserve other filters
        let currentUrl = new URL(window.location);
        let params = currentUrl.searchParams;
        
        if (params.has('appFilter[GetClient]')) {
            href += '&appFilter[GetClient]=' + params.get('appFilter[GetClient]');
        }
        if (params.has('appFilter[GetStatus]')) {
            href += '&appFilter[GetStatus]=' + params.get('appFilter[GetStatus]');
        }
        
        document.location.href = href;
    }

    function setMonthPeriod() {
        let href = '{{ collect(request()->segments())->last() }}?';
        href += 'appFilter[GetStartDate]={{ now()->startOfMonth()->format("d-m-Y") }}&appFilter[GetEndDate]={{ now()->endOfMonth()->format("d-m-Y") }}';
        // Preserve other filters
        let currentUrl = new URL(window.location);
        let params = currentUrl.searchParams;
        
        if (params.has('appFilter[GetClient]')) {
            href += '&appFilter[GetClient]=' + params.get('appFilter[GetClient]');
        }
        if (params.has('appFilter[GetStatus]')) {
            href += '&appFilter[GetStatus]=' + params.get('appFilter[GetStatus]');
        }
        
        document.location.href = href;
    }

    function setLastMonthPeriod() {
        let href = '{{ collect(request()->segments())->last() }}?';
        href += 'appFilter[GetStartDate]={{ now()->subMonth()->startOfMonth()->format("d-m-Y") }}&appFilter[GetEndDate]={{ now()->subMonth()->endOfMonth()->format("d-m-Y") }}';
        // Preserve other filters
        let currentUrl = new URL(window.location);
        let params = currentUrl.searchParams;
        
        if (params.has('appFilter[GetClient]')) {
            href += '&appFilter[GetClient]=' + params.get('appFilter[GetClient]');
        }
        if (params.has('appFilter[GetStatus]')) {
            href += '&appFilter[GetStatus]=' + params.get('appFilter[GetStatus]');
        }
        
        document.location.href = href;
    }

    function filterResults() {
        // Get all filter values
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
</script>
