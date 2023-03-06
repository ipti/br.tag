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
            {"data": "abilities", "visible": false},
            {"data": "resources", "visible": false},
            {"data": "types", "visible": false},
            {
                "className": 'dt-center',
                "orderable": false,
                "data": "deleteButton",
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
        $(".remove-course-class").tooltip();
    });
}

function addCoursePlanRow() {
    var lastTr = $('#course-classes tbody tr.dt-hasChild').last();
    var index = 0;
    if (lastTr.length > 0) {
        var row = table.row(lastTr);
        index = row.data().class;
    }

    $(".details-control .fa-minus-circle").click();

    table.row.add({
        "class": index + 1,
        "courseClassId": "",
        "objective": "",
        "abilities": null,
        "resources": null,
        "types": null,
        "deleteButton": '<a href="#" class="btn btn-danger btn-small remove-course-class"><i class="fa fa-times"></i></a>'
    }).draw();
    $("#course-classes tbody .details-control").last().click();
}

function removeCoursePlanRow(element) {
    var tr = $(element).parent().parent();
    table.row(tr).remove().draw();
}

function format(d) {
    var $div = $('<div id="course-class[' + d.class + ']" class="course-class course-class-' + d.class + ' row"></div>');
    var $column1 = $('<div   class="course-class-column1 column"></div>');
    var $id = $('<input type="hidden" name="course-class[' + d.class + '][id]" value="' + d.courseClassId + '">');
    var $objective = $('<div class="control-group objective-input"></div>');
    var $objectiveLabel = $('<div><label class="" for="course-class[' + d.class + '][objective]">Objetivo *</label></span>');
    var $objectiveInput = $('<textarea class="course-class-objective" id="objective-' + d.class + '" name="course-class[' + d.class + '][objective]">' + d.objective + '</textarea>');

    var $ability = $('<div class="control-group courseplan-ability-container"></div>');
    var $abilityLabel = $('<label class="" for="course-class[' + d.class + '][ability][]">Habilidade(s)</label>');
    var $abilityButton = $('<button class="btn btn-success add-abilities" style="height: 28px;" ><i class="fa fa-plus-square"></i> Adicionar</button>');
    var $abilitiesContainer = $('<div class="courseplan-abilities-selected">');

    var $type = $('<div class="control-group courseplan-type-container"></div>');
    var $typeLabel = $('<label class="" for="course-class[' + d.class + '][type][]">Tipo(s)</label>');
    var $typeInput = $('<select class="type-select" name="course-class[' + d.class + '][type][]" multiple>' + $(".js-all-types")[0].innerHTML + '</select>');

    var $column2 = $('<div class="course-class-column2 column"></div>');
    var $resource = $('<div class="control-group"></div>');
    var $resourceLabel = $('<label class="" for="resource">Recurso(s)</label>');
    var $resourceInput = $('<div class="resource-input"></div>');
    var $resourceValue = $('<select class="resource-select" name="resource"><option value=""></option>' + $(".js-all-resources")[0].innerHTML + '</select>');
    var $resourceAmount = $('<input class="resource-amount" style="width:35px; height: 22px;margin-left: 5px;" type="number" name="amount" step="1" min="1" value="1" max="999">');
    var $resourceAdd = $('<button class="btn btn-success btn-small fa fa-plus-square add-resource" style="height: 28px;margin-top:10px;" ><i></i></button>');

    var $resources = $('<div class="resources"></div>');
    if (d.abilities !== null) {
        $.each(d.abilities, function (i, v) {
            var div = '<div class="ability-panel-option"><input type="hidden" class="ability-panel-option-id" value="' + v.id + '"><i class="fa fa-check-square"></i><span>(<b>' + v.code + '</b>) ' + v.description + '</span></div>';
            $abilitiesContainer.append(div);
        });
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
    $ability.append($abilityLabel);
    $ability.append($abilityButton);
    $ability.append($abilitiesContainer);
    $resourceInput.append($resourceValue);
    $resourceInput.append($resourceAmount);
    $resourceInput.append($resourceAdd);
    $resource.append($resourceLabel);
    $resource.append($resourceInput);
    $resource.append($resources);
    $type.append($typeLabel);
    $type.append($typeInput);
    $column1.append($objective);
    $column1.append($ability);
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

function buildAbilityStructureSelect(data) {
    var div = '<div class="control-group ability-structure-container"><label>' + data.selectTitle + '</label><select class="ability-structure-select"><option value="">Selecione...</option>';
    $.each(data.options, function () {
        div += '<option value="' + this.id + '">' + this.description + '</option>';
    });
    div += "</select><i class='loading-next-structure fa fa-spin fa-spinner'></i></div>";
    return div;
}

function buildAbilityStructurePanel(data) {
    var panel = '<div><label>' + data.selectTitle + '</label>';
    $.each(data.options, function () {
        var selected = $(".abilities-selected").find(".ability-panel-option-id[value=" + this.id + "]").length ? "selected" : "";
        panel += '<div class="ability-panel-option ' + selected + '"><input type="hidden" class="ability-panel-option-id" value="' + this.id + '"><i class="fa fa-plus-square"></i><span>(<b>' + this.code + '</b>) ' + this.description + '</span></div>';
    });
    panel += "</div>";
    return panel;
}