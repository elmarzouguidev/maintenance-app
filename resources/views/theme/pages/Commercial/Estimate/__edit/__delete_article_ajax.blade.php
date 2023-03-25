<script>
    $(".deleteArticle").click(function(event) {
        event.preventDefault();

        var result = confirm('Are you sure you want to delete this record?');

        var article = $(this).data("article");
        var estimate = $(this).data("estimate");
        var token = $("meta[name='csrf-token']").attr("content");

        if (result) {

            $.ajax({
                url: "{{ route('commercial:estimates.delete.article') }}",
                type: 'DELETE',
                data: {
                    "article": article,
                    "estimate": estimate,
                    "_token": token,
                },
                success: function() {
                    window.location.reload();
                }
            });
        }

    });
</script>
