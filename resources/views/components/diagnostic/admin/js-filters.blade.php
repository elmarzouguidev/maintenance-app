<script>
    $(function () {
        const $clientFilter = $('#diagnosticClientFilter');

        if ($clientFilter.length && $.fn.select2) {
            $clientFilter.select2({
                width: '100%',
                placeholder: 'Tous les clients',
            });
        }

        const filterForm = document.getElementById('diagnosticFilterForm');

        if (!filterForm) {
            return;
        }

        filterForm.addEventListener('submit', function () {
            const startDate = document.getElementById('diagnosticDateStart').value.trim();
            const endDate = document.getElementById('diagnosticDateEnd').value.trim();
            const dateBetween = document.getElementById('diagnosticDateBetween');

            if (startDate && endDate) {
                dateBetween.value = `${startDate},${endDate}`;
                dateBetween.disabled = false;
            } else {
                dateBetween.value = '';
                dateBetween.disabled = true;
            }

            filterForm.querySelectorAll('select[name^="appFilter["]').forEach(function (select) {
                select.disabled = !select.value;
            });
        });
    });
</script>
