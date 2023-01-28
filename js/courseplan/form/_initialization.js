var table;

// Add event listener for opening and closing details
$('#course-classes tbody').on('click', 'td.details-control', function () {

    var tr = $(this).closest('tr');
    var i = $(this).children('i').first();
    var row = table.row(tr);

    if (!row.child.isShown()) {
        row.child(format(row.data())).show();
        $('.course-class:last select').select2();
        tr.next().addClass("detailed-row").show();
    } else {
        tr.next().toggle();
    }


    if (!tr.next().is(":visible")) {
        i.removeClass('fa-minus-circle');
        i.addClass('fa-plus-circle');
    } else {
        i.removeClass('fa-plus-circle');
        i.addClass('fa-minus-circle');
    }
});

$(document).on("click", "#new-course-class", function () {
    addCoursePlanRow();
});

$(document).on("click", ".remove-course-class", function () {
    removeCoursePlanRow(this);
});

$(document).on("keyup", ".course-class-objective", function () {
    var objective = $(this).val();
    if (objective.length > 110) {
        objective = objective.substring(0, 107) + "..."
    }
    $(this).parents("tr").prev().children(".dt-justify").html(objective);
});

$(document).on("change", "#CoursePlan_modality_fk", function (evt, loadingData) {
    $("#CoursePlan_discipline_fk").val("").trigger("change.select2");
    if ($(this).val() !== "") {
        $.ajax({
            type: "POST",
            url: "?r=courseplan/getDisciplines",
            cache: false,
            data: {
                stage: $("#CoursePlan_modality_fk").val(),
            },
            beforeSend: function () {
                $(".js-course-plan-loading-disciplines").css("display", "inline-block");
                $("#CoursePlan_discipline_fk").attr("disabled", "disabled");
            },
            success: function (data) {
                data = JSON.parse(data);
                var option = "<option value=''>Selecione a disciplina...</option>";
                $.each(data, function () {
                    var selectedValue = loadingData !== undefined && $("#CoursePlan_discipline_fk").attr("initval") !== "" && $("#CoursePlan_discipline_fk").attr("initval") === this.id ? "selected" : "";
                    option += "<option value='" + this.id + "' " + selectedValue + ">" + this.name + "</option>";
                });
                $("#CoursePlan_discipline_fk").html(option).trigger("change").show();
                $(".js-course-plan-loading-disciplines").hide();
                $("#CoursePlan_discipline_fk").removeAttr("disabled");
            },
        });
    } else {
        $("#CoursePlan_discipline_fk").html("<option value=''>Selecione a disciplina...</option>").trigger("change.select2").show();
    }
});
$("#CoursePlan_modality_fk").trigger("change", [true]);

$(document).on("change", "#CoursePlan_discipline_fk", function () {
    if ($(this).val() !== "") {
        $("#course-classes").show();
        if (!$.fn.DataTable.isDataTable('#course-classes')) {
            initDatatable();
        }
    } else {
        $("#course-classes").hide();
    }
})

$(document).on("click", ".add-resource", function (evt) {
    evt.preventDefault();
    if ($(this).parent().find("select.resource-select").val() !== "" && $(this).parent().find(".resource-amount").val() !== "" && $(this).parent().find(".resource-amount").val() > 0) {
        addResource(this);
    }
});

$(document).on("click", ".remove-resource", function () {
    removeResource(this);
});

$("#print").on('click', function () {
    window.print();
});

$("#save").on('click', function () {
    var submit = validateSave();
    if (submit) {
        $("#course-plan-form").submit();
    }
});