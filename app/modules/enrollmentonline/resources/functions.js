$(".js-cpf-mask").mask("000.000.000-00", {
    placeholder: "___.___.___-__",
});
$(".js-cep-mask").mask("00000-000", {
    placeholder: "_____-___",
});
$(".js-tel-mask").mask("(00) 00000-0000", {
    placeholder: "(__) _____-____"
});

$(document).ready(function () {

    $("select.js-filiation-select").select2();

    getCitiesByState();
    hideFiliationFields();

    $("select.js-filiation-select").on("change", hideFiliationFields);
});



function hideFiliationFields() {
    if ($(".js-filiation-select").select2("val") === "1") {
        $(".js-hide-filiation").css("display", "flex");
        $('.js-father-name').addClass('js-field-required');
        $('.js-mother-name').addClass('js-field-required');
    } else {
        $(".js-hide-filiation").hide()
        $('.js-father-name').removeClass('js-field-required');
        $('.js-mother-name').removeClass('js-field-required');
    }
}

$(".js-uf").on("change", () => {

    getCitiesByState();
})

function getCitiesByState() {
    $.ajax({
        type: "POST",
        url: "?r=enrollmentonline/Enrollmentonlinestudentidentification/getCities",
        cache: false,
        data: {
            state: $('select.js-uf').val(),
        },
        success: function (response) {
            const data = DOMPurify.sanitize(response);

            $("select.js-cities").html(data)

            if (window.location.search.includes("update")) {
                $('select.js-cities').select2('val', $('#edcenso-city-fk-hidden').val());
            }
            $('select.js-cities').select2();
            $("select.js-cities").removeAttr("disabled");
        }
    })
}

$(".js-stage").on("change", () => {

    $.ajax({
        type: "POST",
        url: "?r=enrollmentonline/Enrollmentonlinestudentidentification/getSchools",
        cache: false,
        data: {
            stage: $('select.js-stage').val(),
        },
        success: function (response) {
            const data = DOMPurify.sanitize(response);
            let schools = $("select.js-school-1, select.js-school-2, select.js-school-3");

            schools.html(data)
            schools.select2()
            schools.removeAttr("disabled");
        }
    })
})


$("select.js-school-1, select.js-school-2, select.js-school-3").on("change", function () {
    const currentVal = $(this).val();
    const currentSelect = this;

    let duplicateFound = false;

    $("select.js-school-1, select.js-school-2, select.js-school-3").each(function () {
        if (this !== currentSelect && $(this).val() === currentVal && currentVal !== "") {
            duplicateFound = true;
        }
    });

    if (duplicateFound) {
        $(".js-alert").text("Você não pode selecionar a mesma opção de matíricula mais de uma vez");
        $(".js-alert").show();
        $(this).select2("val", "");
        $(this).select2();

    }
});


$(".js-confirm-enrollment").on("click", function () {
    if (confirm("Tem certeza que deseja confirmar a matrícula?")) {
        $.ajax({
            type: "POST",
            url: "?r=enrollmentonline/Enrollmentonlinestudentidentification/confirmEnrollment",
            dataType: "json",
            cache: false,
            data: {
                enrollmentId: $('input.js-online-enrollment-id').val(),
            },
            success: function (result) {
                result = DOMPurify.sanitize(result);

                if (result.status === "error") {

                    mensagem = result.message;
                    $(".js-alert-enrollment-online").removeClass("alert-success");
                    $(".js-alert-enrollment-online").addClass("alert-error");

                } else if (result.status === "success") {

                    link = "?r=classroom/update&id=" + result.data.classroomId;
                    mensagem = result.message +
                        " Acesse a turma: " +
                        "<a href='" + link + "' target='_blank' style='text-decoration: underline;'>" +
                        result.data.classroomName +
                        "</a>";
                    $(".js-alert-enrollment-online").removeClass("alert-err");
                    $(".js-alert-enrollment-online").addClass("alert-success");

                }
                $(".js-alert-enrollment-online").html(mensagem);


                $(".js-hide-buttons-enrollment").hide();

                $(".js-alert-enrollment-online").show();
            }
        });

    }
});

$(".js-rejected-enrollment").on("click", function () {
    if (confirm("Tem certeza que deseja rejeitar a matrícula?")) {
        $.ajax({
            type: "POST",
            url: "?r=enrollmentonline/Enrollmentonlinestudentidentification/rejectEnrollment",
            dataType: "json",
            cache: false,
            data: {
                enrollmentId: $('input.js-online-enrollment-id').val(),
            },
            beforeSend: function () {
                $("#loading-popup").removeClass("hide").addClass("loading-center");
            },
            success: function (result) {
                result = DOMPurify.sanitize(result);
                $(".js-alert-enrollment-online").text(result.message);

                if (result.status === "success") {
                    $(".js-alert-enrollment-online").removeClass("alert-error");
                    $(".js-alert-enrollment-online").addClass("alert-success");
                } else {
                    $(".js-alert-enrollment-online").removeClass("alert-success");
                    $(".js-alert-enrollment-online").addClass("alert-error");
                }
                $(".js-hide-buttons-enrollment").hide();
                $(".js-alert-enrollment-online").show();
                $("#loading-popup").removeClass("loading-center").addClass("hide");

            }
        });

    }
});


$(".js-nationality-select").on("change", () => {
    const val = $(".js-nationality-select").select2("val");

    if (val === "1" || val === "2") {
        $('.js-edcenso_nation_fk').attr('disabled', 'disabled');
        $('.js-edcenso_nation_fk').select2('val', 76);
        $('.js-edcenso_nation_fk_hidden').val(76);
    } else if (val === "3") {
        $('.js-edcenso_nation_fk').removeAttr('disabled');
        $('.js-edcenso_nation_fk').select2('val', '');
        $('.js-edcenso_nation_fk_hidden').val('');
    } else {
        $('.js-edcenso_nation_fk').attr('disabled', 'disabled');
        $('.js-edcenso_nation_fk').select2('val', '');
        $('.js-edcenso_nation_fk_hidden').val('');
    }
});

$("select.js-filter-enrollment-status").on("change", () => {
    const selectedStatus = $(".js-filter-enrollment-status").select2("val");

    $.ajax({
        type: "POST",
        url: "?r=enrollmentonline/Enrollmentonlinestudentidentification/renderStudentTable",
        cache: false,
        data: {
            status: selectedStatus,
        },
        success: function (response) {
            const data = DOMPurify.sanitize(response);

            $(".js-student-table-container").html(data);
            initDatatable();
        }
    });
});


$(".js-pre-enrollment-event").on("change", () => {

    const selectedEvent = $(".js-pre-enrollment-event").select2("val");

    $.ajax({
        type: "POST",
        url: "?r=enrollmentonline/EnrollmentOnlinePreEnrollmentEvent/getPreEnrollmentStages",
        cache: false,
        data: {
            id: selectedEvent,
        },
        success: function (response) {
            const data = DOMPurify.sanitize(response);
            $(".js-stage").html(data);
            $("select.js-stage").select2();
            $(".js-stage").removeAttr("disabled");
        }
    });


});
