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
                "className": ' dt-center',
                "orderable": false,
                "data": "deleteButton",
                "visible": false,
            },
            {
                "className": 'dt-center',
                "data": "class",
            },
            {"data": "courseClassId", "visible": false},
            {
                "className": 'dt-justify objective-title',
                "data": "objective",
            },
            {
                "className": 'courseplan-type-container',
                "data": "type",
                "visible": false},
            {"data": "abilities", "visible": false},
            {"data": "resources", "visible": false},
            {
                "className": 'dt-center details-control t-accordion__container-icon',
                "orderable": false,
                "defaultContent": '<img class="t-accordion__icon" src="themes/default/img/Glyph.svg" />',
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
    let lastTr = $('#course-classes tbody tr.dt-hasChild').last();
    let index = 0;
    if (lastTr.length > 0) {
        let row = table.row(lastTr);
        index = row.data().class;
    }

    $(".details-control .fa-minus-circle").click();

    index = $('#course-classes .dt-hasChild').length;
    table.row.add({
        "class": index + 1,
        "courseClassId": "",
        "objective": "",
        "type": "",
        "abilities": null,
        "resources": null,
        "deleteButton": null
    }).draw();
    $("#course-classes tbody .details-control").last().click();
}

function removeCoursePlanRow(element) {
    let tr = $(element).closest( "tr" ).prev()
    table.row(tr).remove().draw();
}

function format(d) {
    let div = $('<div id="course-class[' + d.class + ']" class="course-class course-class-' + d.class + ' row"></div>');
    let column1 = $('<div   class="column no-grow"></div>');
    let id = $('<input type="hidden" name="course-class[' + d.class + '][id]" value="' + d.courseClassId + '">');
    let objective = $('<div class="t-field-tarea objective-input"></div>');
    let objectiveLabel = $('<div><label class="t-field-tarea__label" for="course-class[' + d.class + '][objective]">Objetivo *</label></span>');
    let objectiveInput = $('<textarea class="t-field-tarea__input course-class-objective" placeholder="Digite o Objetivo do Plano" id="objective-' + d.class + '" name="course-class[' + d.class + '][objective]">' + d.objective + '</textarea>');

    let ability = $('<div class="control-group courseplan-ability-container"></div>');
    let abilityLabel = $('<label class="" for="course-class[' + d.class + '][ability][]">Habilidade(s)</label>');
    let abilityButton = $('<button class="t-button-primary add-abilities" style="height: 28px;gap: 5px" ><icon class="t-icon-start"></icon>Adicionar habilidades</button>');
    let abilitiesContainer = $('<div class="courseplan-abilities-selected">');

    let type = $('<div class="t-field-text control-group courseplan-type-container"></div>');
    let typeLabel = $('<label class="t-field-text__label" for="course-class[' + d.class + '][type][]">Tipo</label>');
    let typeInput = $('<input type="text" class="t-field-text__input" name="course-class[' + d.class + '][type]"></input>');
    typeInput.val(d.type);

    let resourceButtonContainer = $('<div class="t-buttons-container control-group no-margin"></div>');
    let resourceButton = $('<button class="t-button-primary add-new-resource" style="height: 28px;gap: 5px" ><icon class="t-icon-start"></icon>Adicionar recursos</button>');
    let resource = $('<div class="t-field-select control-group"></div>');
    let resourceLabel = $('<label class="t-field-select__label" for="resource">Recurso(s)</label>');
    let resourceInput = $('<div class="t-field-select__input resource-input"></div>');
    let resourceValue = $('<select id="resource-select" class="resource-select" name="resource"><option value=""></option>' + $(".js-all-resources")[0].innerHTML + '</select>');
    let resourceAmount = $('<input class="resource-amount" style="width:35px; padding: 0px 5px;;margin-left: 5px;" type="number" name="amount" step="1" min="1" value="1" max="999">');
    let resourceAdd = $('<button class="add-resource" style="height: 30px;width: 30px;margin-left:10px;" ><icon class="t-icon-plus"></icon></button>');
    let deleteButton = "";
    if(d.deleteButton === 'js-unavailable'){
        deleteButton = $('<div class="t-buttons-container"><a class="t-button-danger js-remove-course-class js-unavailable t-button-danger--disabled" data-toggle="tooltip" data-placement="left" title="Aula já ministrada em alguma turma. Não é possível removê-la do plano de aula.">Excluir Plano</a></div>')
    } else {
        deleteButton = $('<div class="t-buttons-container"><a class="t-button-danger js-remove-course-class">Excluir Plano</a></div>')
    }

    let resources = $('<div class="row wrap no-gap resources"></div>');
    if (d.abilities !== null) {
        $.each(d.abilities, function (i, v) {
            let div = '<div class="ability-panel-option"><input type="hidden" class="ability-panel-option-id" value="' + v.id + '" name="course-class[' + d.class + '][ability][' + i + ']"><i class="fa fa-check-square"></i><span>(<b>' + v.code + '</b>) ' + v.description + '</span></div>';
            abilitiesContainer.append(div);
        });
    }
    if (d.types !== null) {
        typeInput.val(d.type);
    }
    if (d.resources !== null) {
        $.each(d.resources, function (i, v) {
            let resourceId = v.id;
            let resourceValue = v.value;
            let resourceName = resourceValue.find("option[value=" + v.value + "]").text();
            let resourceAmount = v.amount;
            let div = $('<div class="row t-badge-content course-class-resource"></div>');
            let values = $('<input class="resource-id" type="hidden" name="course-class[' + d.class + '][resource][' + i + '][id]" value="' + resourceId + '"/>'
                + '<input class="resource-value" type="hidden" name="course-class[' + d.class + '][resource][' + i + '][value]" value="' + resourceValue + '"/>'
                + '<input class="resource-amount" type="hidden" name="course-class[' + d.class + '][resource][' + i + '][amount]" value="' + resourceAmount + '"/>');
            let label = $('<span class="row"><span class="fa fa-times remove-resource"><i></i></span><span><span class="resource-amount-text">' + resourceAmount + '</span>x - ' + resourceName + ' </span></span>');
            div.append(values);
            div.append(label);
            resources.append(div);
        });
    }
    objective.append(objectiveLabel);
    objective.append(objectiveInput);
    ability.append(abilityLabel);
    ability.append(abilityButton);
    ability.append(abilitiesContainer);
    resourceButtonContainer.append(resourceButton);
    resourceInput.append(resourceValue);
    resourceInput.append(resourceAmount);
    resourceInput.append(resourceAdd);
    resource.append(resourceButtonContainer);
    resource.append(resourceLabel);
    resource.append(resourceInput);
    resource.append(resources);
    type.append(typeLabel);
    type.append(typeInput);
    column1.append(objective);
    column1.append(ability);
    column1.append(type);
    column1.append(resource);
    column1.append(deleteButton);
    div.append(id);
    div.append(column1);
    return div;
}

let newResources = Array();

function addNewResources(){
    const newResource = $('.new-resource');
    const newResourceP = `<p>${newResource.val()}</p>`
    const alert = $('.alert-resource');
    if(newResource.val() == ""){
        console.log(alert);
        alert.removeClass('hide');
        alert.addClass('show');
        return;
    }
    alert.removeClass('show');
    alert.addClass('hide');
    const divResources = $('#new-resources-table');
    const closeBt = '<span class="remove-new-resource"><i class="t-icon-close"></i></span></div>';
    const newDivResource = `<div class='row ui-accordion-content mobile-row justify-content--space-between t-margin-small--top'>${newResourceP} ${closeBt}`;
    divResources.append(newDivResource);
    newResources.push(newResource.val());
}

function removeNewResource(button){
    const parentNode = button.parentNode;
    const resourceTag = button.previousElementSibling;
    newResources = newResources.filter(e => e !== resourceTag.innerHTML);
    parentNode.remove();
}

function saveNewResources(){
    $.ajax({
        type: "POST",
        url: "?r=courseplan/addResources",
        cache: false,
        data: {
            resources: newResources,
        },
        success: function(data){
            const elements = document.getElementById('new-resources-table');
            // To clean the list of elements added to new resources form
            elements.innerHTML = "";
            newResources.length = 0;
           updateSelectResources();
        }
    })
}

function updateSelectResources(){
    const resourceSelect = $('select#resource-select');
    resourceSelect.html("");
    $.ajax({
        type: "GET",
        url: "?r=courseplan/getResources",
        cache: false
    }).success(function(response){
        let resources = JSON.parse(response);
        Object.entries(resources).forEach(function(option) {
            resourceSelect.append(option);
        });
        resourceSelect.select2("destroy");
        resourceSelect.select2();
    })
}

function addResource(button) {
    let div = $(button).parent();
    let resources = div.parent().children(".resources");
    let resourceAmount = div.children("input[name=amount]").val();
    if (resources.find(".resource-value[value=" + div.children("select").val() + "]").length) {
        let resource = resources.find(".resource-value[value=" + div.children("select").val() + "]").parent();
        resource.find(".resource-amount-text").text(resourceAmount);
        resource.find(".resource-amount").val(resourceAmount);
    } else {
        let resourceValue = div.children("select").val();
        let resourceName = div.children("select").select2('data').text;
        if (resourceAmount > 0 && resourceAmount < 1000 && resourceValue !== "") {
            let tr = div.closest('tr').prev();
            let row = table.row(tr);
            let d = row.data();
            let count = $(resources).children('.course-class-resource').length;
            let div = $('<div class="t-badge-content course-class-resource"></div>');
            let values = $('<input class="resource-id" type="hidden" name="course-class[' + d.class + '][resource][' + count + '][id]" value=""/>'
                + '<input class="resource-value" type="hidden" name="course-class[' + d.class + '][resource][' + count + '][value]" value="' + resourceValue + '"/>'
                + '<input class="resource-amount" type="hidden" name="course-class[' + d.class + '][resource][' + count + '][amount]" value="' + resourceAmount + '"/>');
            let label = $('<span class="row"><span class="fa fa-times remove-resource"><i></i></span><span><span class="resource-amount-text">' + resourceAmount + '</span>x - ' + resourceName + ' </span></span>');
            div.append(values);
            div.append(label);
            resources.append(div);
        };
    }
    objective.append(objectiveLabel);
    objective.append(objectiveInput);
    ability.append(abilityLabel);
    ability.append(abilityButton);
    ability.append(abilitiesContainer);
    resourceButtonContainer.append(resourceButton);
    resourceInput.append(resourceValue);
    resourceInput.append(resourceAmount);
    resourceInput.append(resourceAdd);
    resource.append(resourceButtonContainer);
    resource.append(resourceLabel);
    resource.append(resourceInput);
    resource.append(resources);
    type.append(typeLabel);
    type.append(typeInput);
    column1.append(objective);
    column1.append(ability);
    column1.append(type);
    column1.append(resource);
    column1.append(deleteButton);
    div.append(id);
    div.append(column1);
    return div;
}

function removeResource(button) {
    let resource = $(button).closest(".course-class-resource");
    let resources = resource.parent();
    resource.remove();
}

function buildAbilityStructureSelect(data) {
    let div = '<div class="control-group ability-structure-container"><label>' + data.selectTitle + '</label><select class="ability-structure-select"><option value="">Selecione...</option>';
    $.each(data.options, function () {
        div += '<option value="' + this.id + '">' + this.description + '</option>';
    });
    div += "</select></div>";
    return div;
}

function buildAbilityStructurePanel(data) {
    let panel = '<div><label>' + data.selectTitle + '</label>';
    $.each(data.options, function () {
        let selected = $(".js-abilities-selected").find(".ability-panel-option-id[value=" + this.id + "]").length ? "selected" : "";
        panel += '<div class="ability-panel-option ' + selected + '"><input type="hidden" class="ability-panel-option-id" value="' + this.id + '"><i class="fa fa-plus-square"></i><span>(<b>' + this.code + '</b>) ' + this.description + '</span></div>';
    });
    panel += "</div>";
    return panel;
}
