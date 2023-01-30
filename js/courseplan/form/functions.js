function initDatatable() {
    table = $('#course-classes').DataTable({
        ajax: {
            type: "POST",
            url: "?r=courseplan/getCourseClasses",
            data: function (data) {
                data.coursePlanId = $(".js-course-plan-id").val();
            },
            complete: function () {
                $(".details-control").click().click();
            }
        },
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
            {"data": "courseClassId", "visible": false},
            {
                "className": 'dt-justify objective-title',
                "data": "objective"
            },
            {"data": "competences", "visible": false},
            {"data": "resources", "visible": false},
            {"data": "types", "visible": false},
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
            "sLoadingRecords": "Carregando...",
        }
    });
    table.on('draw.dt', function () {
        table.column(1).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    });
}

function addCoursePlanRow() {
    var lastTr = $('#course-classes tbody tr[role=row]').last();
    var index = 0;
    if (lastTr.length > 0) {
        index = table.row($('#course-classes tbody tr[role=row]').last()).index() + 1;
    }

    $(".details-control .fa-minus-circle").click();

    table.row.add({
        "class": index + 1,
        "courseClassId": "",
        "objective": "",
        "competences": null,
        "resources": null,
        "types": null
    }).draw();
    $("#course-classes tbody .details-control").last().click();
}

function removeCoursePlanRow(element) {
    var tr = $(element).parent().parent();
    table.row(tr).remove().draw();
}

function format(d) {
    var $div = $('<div id="course-class[' + d.class + ']" class="course-class"></div>');
    var $column1 = $('<div   class="course-class-column1"></div>');
    var $id = $('<input type="hidden" name="course-class[' + d.class + '][id]" value="' + d.courseClassId + '">');
    var $objective = $('<div class="control-group"></div>');
    var $objectiveLabel = $('<div><label class="" for="course-class[' + d.class + '][objective]">Objetivo *</label></span>');
    var $objectiveInput = $('<textarea class="course-class-objective" id="objective-' + d.class + '" name="course-class[' + d.class + '][objective]">' + d.objective + '</textarea>');

    var $competence = $('<div class="control-group courseplan-competence-container"></div>');
    var $competenceLabel = $('<label class="" for="course-class[' + d.class + '][competence][]">Habilidade(s)</label>');
    var $competenceInput = $('<select class="competence-select" name="course-class[' + d.class + '][competence][]" multiple>' + $(".js-all-competences")[0].innerHTML + '</select>');

    var $type = $('<div class="control-group courseplan-type-container"></div>');
    var $typeLabel = $('<label class="" for="course-class[' + d.class + '][type][]">Tipo(s)</label>');
    var $typeInput = $('<select class="type-select" name="course-class[' + d.class + '][type][]" multiple>' + $(".js-all-types")[0].innerHTML + '</select>');

    var $column2 = $('<div class="course-class-column2"></div>');
    var $resource = $('<div class="control-group"></div>');
    var $resourceLabel = $('<label class="" for="resource">Recurso(s)</label>');
    var $resourceInput = $('<div class="resource-input"></div>');
    var $resourceValue = $('<select class="resource-select" name="resource"><option value=""></option>' + $(".js-all-resources")[0].innerHTML + '</select>');
    var $resourceAmount = $('<input class="resource-amount" style="width:35px; height: 22px;margin-left: 5px;" type="number" name="amount" step="1" min="1" value="1" max="999">');
    var $resourceAdd = $('<button class="btn btn-success btn-small fa fa-plus-square add-resource" style="height: 28px;margin-left: 10px;" ><i></i></button>');

    var $resources = $('<div class="resources"></div>');
    if (d.competences !== null) {
        $competenceInput.val(d.competences);
    }
    if (d.types !== null) {
        $typeInput.val(d.types);
    }
    if (d.resources !== null) {
        $.each(d.resources, function (i, v) {
            var resourceId = v.id;
            var resourceValue = v.value;
            var resourceName = $resourceValue.find("option[value=" + v.value + "]").text();
            var resourceAmount = v.amount;
            var div = $('<div class="course-class-resource"></div>');
            var values = $('<input class="resource-id" type="hidden" name="course-class[' + d.class + '][resource][' + i + '][id]" value="' + resourceId + '"/>'
                + '<input class="resource-value" type="hidden" name="course-class[' + d.class + '][resource][' + i + '][value]" value="' + resourceValue + '"/>'
                + '<input class="resource-amount" type="hidden" name="course-class[' + d.class + '][resource][' + i + '][amount]" value="' + resourceAmount + '"/>');
            var label = $('<span><span class="resource-amount-text">' + resourceAmount + '</span>x - ' + resourceName + ' <span class="fa fa-times remove-resource"><i></i></span></span>');
            div.append(values);
            div.append(label);
            $resources.append(div);
        });
    }
    $objective.append($objectiveLabel);
    $objective.append($objectiveInput);
    $competence.append($competenceLabel);
    $competence.append($competenceInput);
    $resourceInput.append($resourceValue);
    $resourceInput.append($resourceAmount);
    $resourceInput.append($resourceAdd);
    $resource.append($resourceLabel);
    $resource.append($resourceInput);
    $resource.append($resources);
    $type.append($typeLabel);
    $type.append($typeInput);
    $column1.append($objective);
    $column1.append($competence);
    $column2.append($type);
    $column2.append($resource);
    $div.append($id);
    $div.append($column1);
    $div.append($column2);

    return $div;
}

function addResource(button) {
    var div = $(button).parent();
    var resources = div.parent().children(".resources");
    var resourceAmount = div.children("input[name=amount]").val();
    if (resources.find(".resource-value[value=" + div.children("select").val() + "]").length) {
        var resource = resources.find(".resource-value[value=" + div.children("select").val() + "]").parent();
        resource.find(".resource-amount-text").text(resourceAmount);
        resource.find(".resource-amount").val(resourceAmount);
    } else {
        var resourceValue = div.children("select").val();
        var resourceName = div.children("select").select2('data').text;
        if (resourceAmount > 0 && resourceAmount < 1000 && resourceValue !== "") {
            var tr = div.closest('tr').prev();
            var row = table.row(tr);
            var d = row.data();
            var count = $(resources).children('.course-class-resource').length;
            var div = $('<div class="course-class-resource"></div>');
            var values = $('<input class="resource-id" type="hidden" name="course-class[' + d.class + '][resource][' + count + '][id]" value=""/>'
                + '<input class="resource-value" type="hidden" name="course-class[' + d.class + '][resource][' + count + '][value]" value="' + resourceValue + '"/>'
                + '<input class="resource-amount" type="hidden" name="course-class[' + d.class + '][resource][' + count + '][amount]" value="' + resourceAmount + '"/>');
            var label = $('<span><span class="resource-amount-text">' + resourceAmount + '</span>x - ' + resourceName + ' <span class="fa fa-times remove-resource"><i></i></span></span>');
            div.append(values);
            div.append(label);
            resources.append(div);
        }
    }
}

function removeResource(button) {
    var resource = $(button).closest(".course-class-resource");
    var resources = resource.parent();
    var classe = resources.closest("tr").prev().children(".details-control").next().text();
    resource.remove();
    $.each(resources.children(".course-class-resource"), function () {
        var index = resources.children(".course-class-resource").index(this);
        $(this).attr("name", "course-class[" + classe + "][resource][" + index + "]");
        $(this).children(".resource-value").attr("name", "course-class[" + classe + "][resource][" + index + "][value]");
        $(this).children(".resource-amount").attr("name", "course-class[" + classe + "][resource][" + index + "][amount]");
    });
}