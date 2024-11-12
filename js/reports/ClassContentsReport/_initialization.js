$(document).ready(function() {
    let params = new URLSearchParams(window.location.search);
    let classroomId = params.get('classroomId');
    let month = params.get('month');
    let year = params.get('year');
    let discipline = params.get('discipline');

    $.ajax({
        type: 'POST',
        url: "?r=classes/getClassContents",
        cache: false,
        data: {
            classroom: classroomId,
            month: month,
            year: year,
            discipline: discipline
        },
        success: function (data) {
            data = jQuery.parseJSON(data);
            if (data.valid) {
                createTable(data);

            } else {
                $('#class-contents > thead').html('');
                $('#class-contents > tbody').html('');
                $('#class-contents').show();
            }
        }
    });
});
