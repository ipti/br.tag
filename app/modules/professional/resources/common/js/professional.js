$(".new-attendance-button").on("click", function () {
    $(".form-attendance").show();
    $(".new-attendance-button").hide();
});

$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    if (typeof $.fn.dataTable === 'function') {
        // Remove inline width styles that DataTables may have set
        $('.js-tag-table').css('width', '100%');

        // Force column recalculation
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust()
            .responsive.recalc()
            .draw();
    }
});

// Handle location type selection
$(document).ready(function () {
    function toggleLocationFields() {
        const locationType = $('#location-type-select').val();

        if (locationType === 'school') {
            $('#school-selection-row').show();
            $('#location-name-row').hide();
            $('[name="ProfessionalAllocation[location_name]"]').val('');
        } else {
            $('#school-selection-row').hide();
            $('#location-name-row').show();
            $('[name="ProfessionalAllocation[school_inep_fk]"]').val('');
        }
    }

    $('#location-type-select').on('change', toggleLocationFields);

    // Trigger on page load
    toggleLocationFields();
});

function deleteAttendance(deleteBt) {
    const idAttendance = deleteBt.value;
    const idProfessional = document.querySelector('#id_professional').innerHTML;
    $.ajax({
        type: 'POST',
        url: '?r=professional/default/deleteAttendance',
        data: {
            attendance: idAttendance,
            professional: idProfessional,
        },
    }).success(function (response) {
        location.reload();
    })
}

// Funções de lotação movidas para allocation.js
