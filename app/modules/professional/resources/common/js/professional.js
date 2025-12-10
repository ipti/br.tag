$(".new-attendance-button").on("click", function () {
    $(".form-attendance").show();
    $(".new-attendance-button").hide();
});

function deleteAttendance(deleteBt){
    const idAttendance = deleteBt.value;
    const idProfessional = document.querySelector('#id_professional').value;
    $.ajax({
        type: 'POST',
        url: '?r=professional/default/deleteAttendance',
        data: {
            attendance: idAttendance,
            professional: idProfessional,
        },
    }).success(function(response) {
        location.reload();
    })
}


$('#new-enrollment-button').on('click', function (event) {
    $("#js-add-professional-enrollment").modal("show");
});

