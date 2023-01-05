<script>

    function getDateFilter() {
        let status = document.getElementById("filterDate");
        console.log(status.value);
        return status.value;
    }
    
    function filterResults() {


        let getDate = getDateFilter();
        // console.log(clientId);

        let href = '{{ collect(request()->segments())->last() }}?';

        if (getDate.length) {
            href += '&appFilter[GetInvoiceDate]=' + getDate;
        }
        document.location.href = href;
       // return href;
    }

    document.getElementById("filterData").addEventListener("click", function(event) {

        event.preventDefault();
        filterResults();

        /*$.ajax({
            url: filterResults(),
            type: 'GET',
            success: function() {
                console.log("it Works");
                $("#invoices_lister").load(window.location.href + " #invoices_lister");
            }
        });*/
    });

    /*$(".chk-filter").on("click", function() {
        if (this.checked) {
           // $('#filter').click();
            filterResults()
        }
    });*/
</script>
