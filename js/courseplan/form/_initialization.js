var table;
$(document).ready(function () {
    table = $('#course-classes').DataTable({
        paginate: false,
        ordering: false,
        lengthMenu: false,
        filter: false,
        info: false,
        "columns": [
            {
                "className": 'details-control dt-center',
                "orderable": false,
                "data": null,
                "defaultContent": '<i class="fa fa-plus-circle"></i>',
                "width": "1px"
            },
            {
                "className": 'dt-center',
                "data": "class",
                "width": "1px"
            },
            {
                "className": 'dt-justify',
                "data": "objective"
            },
            {"data": "content", "visible": false},
            {"data": "resource", "visible": false},
            {"data": "type", "visible": false},
            {
                "className": 'dt-center',
                "orderable": false,
                "data": null,
                "defaultContent": '<a href="#" class="btn btn-danger btn-small remove-course-class"><i class="fa fa-times"></i></a>',
                "width": "1px"
            }
        ],
        language: {
            emptyTable: "Nenhuma aula cadastrada.",
        }
    });
    table.on('draw.dt', function () {
        table.column(1).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    });
});

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

$(document).on("change", "#CoursePlan_modality_fk", function () {
    $("#CoursePlan_discipline_fk").val("").trigger("change.select2");
    if ($(this).val() !== "") {
        $.ajax({
            type: "POST",
            url: "?r=courseplan/getDisciplines",
            cache: false,
            data: {
                stage: $("#CoursePlan_modality_fk").val(),
            },
            success: function (response) {
                if (response === "") {
                    $("#CoursePlan_discipline_fk").html("<option value='-1'></option>").trigger("change.select2").show();
                } else {
                    $("#CoursePlan_discipline_fk").html(decodeHtml(response)).trigger("change.select2").show();
                }
                $(".disciplines-container").show();
            },
        });
    } else {
        $("#CoursePlan_discipline_fk").html("<option value=''>Selecione a disciplina...</option>").trigger("change.select2").show();
    }
});

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