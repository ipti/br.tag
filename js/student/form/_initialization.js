window.location.search.includes("update")
    ? $(".last").css("display", "flex")
    : $(".last").css("display", "none");

$(document).ready(function () {
    if ($("#others-check").is(":checked")) {
        $(".others-text-box").show();
    } else {
        $(".others-text-box").hide();
    }

    if ($("#show-student-civil-name").is(":checked")) {
        $(".student-civil-name").show();
        $("#show-student-civil-name-box").hide();
    }

    $("#new-enrollment-button").click(function () {
        if ($(".new-enrollment-form").css("display") == "none") {
            $(".new-enrollment-form").show();
            $("#new-enrollment-button").text("Fechar formulário");
        } else {
            $(".new-enrollment-form").hide();
            $("#new-enrollment-button").text("Adicionar Matrícula");
        }
    });

    let simple = getUrlVars()["simple"];
    if (simple == "1") {
        $("#tab-student-documents").hide();
        $(".js-hide-not-required").hide();
        $("#StudentEnrollment_classroom_fk")
            .closest(".js-hide-not-required")
            .show();
    }
    $(".tab-student").show();
    $(".tab-content").show();

    if ($(formIdentification + "deficiency_type_blindness").is(":checked")) {
        $(formIdentification + "deficiency_type_low_vision").attr(
            "disabled",
            "disabled"
        );
        $(formIdentification + "deficiency_type_monocular_vision").attr(
            "disabled",
            "disabled"
        );
        $(formIdentification + "deficiency_type_deafness").attr(
            "disabled",
            "disabled"
        );
        $(formIdentification + "deficiency_type_deafblindness").attr(
            "disabled",
            "disabled"
        );
    }
    if ($(formIdentification + "deficiency_type_low_vision").is(":checked")) {
        $(formIdentification + "deficiency_type_blindness").attr(
            "disabled",
            "disabled"
        );
        $(formIdentification + "deficiency_type_deafblindness").attr(
            "disabled",
            "disabled"
        );
    }
    if (
        $(formIdentification + "deficiency_type_monocular_vision").is(
            ":checked"
        )
    ) {
        $(formIdentification + "deficiency_type_blindness").attr(
            "disabled",
            "disabled"
        );
        $(formIdentification + "deficiency_type_deafblindness").attr(
            "disabled",
            "disabled"
        );
    }
    if ($(formIdentification + "deficiency_type_deafness").is(":checked")) {
        $(formIdentification + "deficiency_type_blindness").attr(
            "disabled",
            "disabled"
        );
        $(formIdentification + "deficiency_type_disability_hearing").attr(
            "disabled",
            "disabled"
        );
        $(formIdentification + "deficiency_type_deafblindness").attr(
            "disabled",
            "disabled"
        );
    }
    if (
        $(formIdentification + "deficiency_type_disability_hearing").is(
            ":checked"
        )
    ) {
        $(formIdentification + "deficiency_type_deafness").attr(
            "disabled",
            "disabled"
        );
        $(formIdentification + "deficiency_type_deafblindness").attr(
            "disabled",
            "disabled"
        );
    }
    if (
        $(formIdentification + "deficiency_type_deafblindness").is(":checked")
    ) {
        $(formIdentification + "deficiency_type_blindness").attr(
            "disabled",
            "disabled"
        );
        $(formIdentification + "deficiency_type_low_vision").attr(
            "disabled",
            "disabled"
        );
        $(formIdentification + "deficiency_type_monocular_vision").attr(
            "disabled",
            "disabled"
        );
        $(formIdentification + "deficiency_type_deafness").attr(
            "disabled",
            "disabled"
        );
        $(formIdentification + "deficiency_type_disability_hearing").attr(
            "disabled",
            "disabled"
        );
    }
    if (
        $(formIdentification + "deficiency_type_intelectual_disability").is(
            ":checked"
        )
    ) {
        $(formIdentification + "deficiency_type_gifted").attr(
            "disabled",
            "disabled"
        );
    }
    if ($(formIdentification + "deficiency_type_gifted").is(":checked")) {
        $(formIdentification + "deficiency_type_intelectual_disability").attr(
            "disabled",
            "disabled"
        );
    }
    $(".ui-accordion-header a").click(function (event) {
        event.preventDefault();
        let url = $(this).attr("href");
        window.location.href = url;
    });
    $(".ui-accordion-header").click(function (event) {
        if (!$(this).hasClass("ui-accordion-header-active")) {
            $(this).find($(".accordion-arrow-icon")).addClass("rotate");
        } else {
            $(this).find($(".accordion-arrow-icon")).removeClass("rotate");
        }
    });
    $(function () {
        $("#accordion").accordion({
            active: false,
            collapsible: true,
            icons: false,
            heightStyle: "content",
            animate: 600,
        });
    });

    if ($("#show-cpf-reason").is(":checked")) {
        $("#cpfReasonStudents").hide();
        $("#cpfStudents").show();
    } else {
        $("#cpfReasonStudents").show();
        $("#cpfStudents").hide();
    }

    if ($("#StudentDocumentsAndAddress_cpf").val()) {
        $("#show-student-cpf-box").hide();
    }
});

$("#show-cpf-reason").on("change", (event) => {
    if ($("#show-cpf-reason").is(":checked")) {
        $("#cpfReasonStudents").hide();
        $("#cpfStudents").show();
    } else {
        $("#cpfReasonStudents").show();
        $("#cpfStudents").hide();
    }
});

$(".heading-buttons").css("width", $("#content").width());

function getUrlVars() {
    let vars = {};
    return vars;
}

function displayRecords() {
    if ($("#registrosSimilares").css("display") == "none") {
        $("#registrosSimilares").css("display", "block");
        $("#similarMessage").attr(
            "data-original-title",
            "Cadastro(s) similar(es) encontrado(s), verifique com atenção os dados. Clique para ocultar registros"
        );
    } else {
        $("#registrosSimilares").css("display", "none");
        $("#similarMessage").attr(
            "data-original-title",
            "Cadastro(s) similar(es) encontrado(s), verifique com atenção os dados. Clique para exibir registros"
        );
    }
}

$(document).on(
    "change",
    ".resources-container input[type=checkbox]",
    function () {
        if ($(this).attr("id") !== "StudentIdentification_resource_none") {
            $("#StudentIdentification_resource_none").prop("checked", false);
        } else {
            $(".resources-container input[type=checkbox]")
                .not("#StudentIdentification_resource_none")
                .prop("checked", false);
        }
    }
);

$(document).on("change", "#StudentEnrollment_public_transport", function () {
    if ($(this).is(":checked")) {
        $("#transport_responsable, #transport_type")
            .show()
            .find(".control-label")
            .addClass("required");
    } else {
        $("#StudentEnrollment_transport_responsable_government")
            .val("")
            .trigger("change.select2");
        $("#transport_type input[type=checkbox]:checked").prop(
            "checked",
            false
        );
        $("#transport_responsable, #transport_type").hide();
    }
});

$(document).on("change", "#others-check", function () {
    if ($(this).is(":checked")) {
        $(".others-text-box").show();
    } else {
        $(".others-text-box").hide();
    }
});

$(document).on("change", "#show-student-civil-name", function () {
    if ($(this).is(":checked")) {
        $(".student-civil-name").show();
        $("#show-student-civil-name-box").hide();
    }
});
$("#copy-gov-id").click(function () {
    let govId = $("#StudentIdentification_gov_id").val();
    navigator.clipboard.writeText(govId);
    $("#copy-message").text("Copiado!").fadeIn().delay(1000).fadeOut();
});

$("#StudentEnrollment_public_transport").trigger("change");

$(".update-student-from-sedsp").click(function () {
    $("#importStudentFromSEDSP").modal("show");
});

$(".import-student-button").click(function () {
    $("#importStudentFromSEDSP").find("form").submit();
});
