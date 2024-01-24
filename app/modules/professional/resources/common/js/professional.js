$(".new-attendance-button").on("click", function () {
    $(".form-attendance").show();
    $(".new-attendance-button").hide();
});

$(document).ready(function() {
    $('.delete-attendance-bt').on('click', function(e){

        e.preventDefault();

        const idAttendance = $(this).val();
        const idProfessional = document.querySelector('#id_professional').innerHTML;

        $.ajax({
            type: 'POST',
            url: '?r=professional/default/deleteAttendance',
            data: {
                attendance: idAttendance,
                professional: idProfessional,
            },
        }).success(function(response) {
            console.log('Response: ', response);
        })
    })
})
