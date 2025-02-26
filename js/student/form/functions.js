$(document).on("click", ".js-remove-enrollment", function () {
    $GradesAndFrequency = null;
    let enrollmentId = $(this).data("erollment-id");
    $.ajax({
        type: "POST",
        url: "?r=student/getGradesAndFrequency",
        cache: false,
        data: {
            errolmentId: enrollmentId,
        },
        success: function (response) {
            console.log("Sucesso:", response);
        },
        error: function (xhr, status, error) {
            console.error("Erro:", error);
        }
    });
});

