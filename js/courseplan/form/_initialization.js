var table;
var state = null;
var idle = 0;
var add = 1;
var remove = 2;


$('#month, #disciplines, #classroom').on('change', function () {
    $('#class-contents').hide();
});

$('#classroom').on('change', function () {
    $('#disciplines').val('').trigger('change');
});

$('#classesSearch').on('click', function () {
    jQuery.ajax({
        type: 'GET',
        url: getClassesURL,
        cache: false,
        data: jQuery('#classroom').parents("form").serialize(),
        success: function (data) {
            var data = jQuery.parseJSON(data);
            $.ajax({
                type: 'POST',
                url: getContentsURL,
                cache: false,
                success: function (contents) {
                    var obj = jQuery.parseJSON(contents);

                    if (data === null)
                        createNoDaysTable();
                    else
                        createTable(data, obj);
                }
            });
        }});
});


$(document).ready(function () {
    $('#class-contents').hide();

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

$('.heading-buttons').css('width', $('#content').width());

$(document).ready(function () {
    table = $('#course-classes').DataTable({
//        "ajax": "../ajax/data/objects.txt",
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
        if (stateIsRemove()) {
            table.column(1).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        }
    });

    // Add event listener for opening and closing details
    $('#course-classes tbody').on('click', 'td.details-control', function () {

        var tr = $(this).closest('tr');
        var i = $(this).children('i').first();
        var row = table.row(tr);

        if (!row.child.isShown()) {
            row.child(format(row.data())).show();
            $('.course-class:last select').select2();
            tr.next().show();
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
});

$(window).load(function () {
    courseClasses = JSON.parse(courseClasses);
    $.each(courseClasses, function (i, v) {
        table.row.add({
            "class": i,
            "objective": v.objective,
            "content": v.content,
            "resource": v.resource,
            "type": v.type
        }).draw();
    });
    $(".details-control").click().click();
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

$(document).on("click", ".add-resource", function (evt) {
    evt.preventDefault();
    addResourceLabel(this);
});

$(document).on("click", ".remove-resource", function () {
    removeResource(this);
});