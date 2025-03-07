$(document).on("click", ".js-remove-enrollment", function () {
    let $button = $(this); // Armazena o botão clicado
    let enrollmentId = $button.attr("enrollment-id");

    $.ajax({
        type: "POST",
        url: "?r=student/getGradesAndFrequency",
        cache: false,
        data: {
            enrollmentId: enrollmentId
        },
        success: function (data) {

            let response =  JSON.parse(DOMPurify.sanitize(data));

            let $alertBox = $(".js-remove-enrollment-alert");
            $alertBox.removeClass("alert-error alert-success").show().html(response.message);

            if (!response.success) {
                $alertBox.addClass("alert-error");
            } else {
                $alertBox.addClass("alert-success");

                let $header = $button.closest('.ui-accordion-header');
                let $content = $header.next('.ui-accordion-content');

                $header.addClass("hide");
                $content.addClass("hide");
            }
        },
        error: function (xhr, status, error) {
            console.error("Erro na requisição:", error);
        }
    });
});


