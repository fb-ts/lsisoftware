$(document).ready(function () {
    let startDateInput = $('#export_history_filter_form_startDate');
    let endDateInput = $('#export_history_filter_form_endDate');

    startDateInput.datepicker({
        format: 'yyyy-mm-dd',
        endDate: '+0d',
        autoclose: true,
        language: 'pl'
    }).on('changeDate', function (selected) {
        let minDate = new Date(selected.date.valueOf());
        endDateInput.datepicker('setStartDate', minDate);
    });

    endDateInput.datepicker({
        format: 'yyyy-mm-dd',
        endDate: '+0d',
        autoclose: true,
        language: 'pl'
    }).on('changeDate', function (selected) {
        let maxDate = new Date(selected.date.valueOf());
        startDateInput.datepicker('setEndDate', maxDate);
    });
});
