function initTable() {
    table = $('#course-classes').DataTable({
        ajax: {
            type: "POST",
            url: "?r=courseplan/courseplan/getCourseClasses",
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
                "className": 'dt-justify content-title',
                "data": "content",
            },
            {
                "className": 'courseplan-methodology-container',
                "data": "methodology",
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
        "content": "",
        "methodology": "",
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

function format_validate(d){
    // Bloquando os campos de nome e data do plano de aula
    $('#CoursePlan_name').attr('readonly', 'readonly');
    $('#courseplan_start_date').attr('readonly', 'readonly');

    // Inserindo as informações do plano de aula no formulário
    let div = $('<div id="course-class[' + d.class + ']" class="course-class course-class-' + d.class + ' row"></div>');
    let column1 = $('<div   class="column no-grow"></div>');
    let id = $('<input type="hidden" name="course-class[' + d.class + '][id]" value="' + d.courseClassId + '">');
    let content = $('<div class="t-field-tarea content-input"></div>');
    let contentLabel = $('<label class="t-field-tarea__label" for="course-class[' + d.class + '][content]">Conteúdo *</label>');
    let contentInput = $('<textarea readonly="readonly" class="t-field-tarea__input course-class-content" placeholder="Digite o conteúdo do Plano" id="content-' + d.class + '" name="course-class[' + d.class + '][content]">' + d.content + '</textarea>');

    let ability = $('<div class="control-group courseplan-ability-container"></div>');
    let abilityLabel = $('<label class="" for="course-class[' + d.class + '][ability][]">Habilidade(s)</label>');
    let abilitiesContainer = $('<div class="courseplan-abilities-selected">');

    let methodology = $('<div class="t-field-text control-group courseplan-methodology-container"></div>');
    let methodologyLabel = $('<label class="t-field-text__label" for="course-class[' + d.class + '][methodology][]">Metodologia</label>');
    let methodologyInput = $('<textarea readonly="readonly" class="t-field-tarea__input course-class-methodology" name="course-class[' + d.class + '][methodology]" maxlength="1500"></textarea>');
    methodologyInput.val(d.methodology);

    let resourceButtonContainer = $('<div class="t-buttons-container control-group no-margin"></div>');
    let resource = $('<div class="t-field-select control-group"></div>');
    let resourceLabel = $('<label class="t-field-select__label" for="resource">Recurso(s)</label>');
    let resourceInput = $('<div class="t-field-select__input resource-input"></div>');
    let deleteButton = "";
    if(d.deleteButton === 'js-unavailable'){
        deleteButton = $('<div class="t-buttons-container"><a class="t-button-danger js-remove-course-class js-unavailable t-button-danger--disabled" data-toggle="tooltip" data-placement="left" title="Aula já ministrada em alguma turma. Não é possível removê-la do plano de aula.">Excluir Plano</a></div>')
    } else {
        deleteButton = $('<div class="t-buttons-container"><a class="t-button-danger js-remove-course-class">Excluir Plano</a></div>')
    }

    let resources = $('<div class="row wrap no-gap resources"></div>');
    if (d.abilities !== null) {
        let uniqueDisciplines = [...new Set(d.abilities.map(item => item.discipline))];
        $.each(uniqueDisciplines, function (i, discipline) {
            let div = '<label>' + discipline + '</label>';
            $.each(d.abilities, function (i, v) {
                if(v.discipline == discipline) {
                    div += '<div class="ability-panel-option"><input type="hidden" class="ability-panel-option-id" value="' + v.id + '" name="course-class[' + d.class + '][ability][' + i + ']"><i class="fa fa-check-square"></i><span>(<b>' + v.code + '</b>) ' + v.description + '</span></div>';
                }
            });
            abilitiesContainer.append(div);
        })
        // $.each(d.abilities, function (i, v) {
        //     console.log("Esse é o I:" + i);
        //     console.log("Esse é o V:" + v.discipline);
        //     let div = '<div class="ability-panel-option"><input type="hidden" class="ability-panel-option-id" value="' + v.id + '" name="course-class[' + d.class + '][ability][' + i + ']"><i class="fa fa-check-square"></i><span>(<b>' + v.code + '</b>) ' + v.description + '</span></div>';
        //     console.log(div);
        //     abilitiesContainer.append(div);
        // });
    }
    if (d.methodologies !== null) {
        methodologyInput.val(d.methodology);
    }
    if (d.resources !== null) {
        $.each(d.resources, function (i, v) {
            let resourceId = v.id;
            let resourceValue = v.value;
            let resourceName = v.description;
            let resourceAmount = v.amount;
            let div = $('<div class="row t-badge-content course-class-resource"></div>');
            let values = $('<input class="resource-id" type="hidden" name="course-class[' + d.class + '][resource][' + i + '][id]" value="' + resourceId + '"/>'
                + '<input class="resource-value" type="hidden" name="course-class[' + d.class + '][resource][' + i + '][value]" value="' + resourceValue + '"/>'
                + '<input class="resource-amount" type="hidden" name="course-class[' + d.class + '][resource][' + i + '][amount]" value="' + resourceAmount + '"/>');
            let label = $('<span class="row"><span class="resource-amount-text">' + resourceAmount + '</span>x - ' + resourceName + ' </span></span>');
            div.append(values);
            div.append(label);
            resources.append(div);
        });
    }
    content.append(contentLabel);
    content.append(contentInput);
    ability.append(abilityLabel);
    ability.append(abilitiesContainer);
    resource.append(resourceButtonContainer);
    resource.append(resourceLabel);
    resource.append(resourceInput);
    resource.append(resources);
    methodology.append(methodologyLabel);
    methodology.append(methodologyInput);
    column1.append(content);
    column1.append(ability);
    column1.append(methodology);
    column1.append(resource);
    div.append(id);
    div.append(column1);
    return div;
}

function format(d) {
    let div = $('<div id="course-class[' + d.class + ']" class="course-class course-class-' + d.class + ' row"></div>');
    let column1 = $('<div   class="column no-grow"></div>');
    let id = $('<input type="hidden" name="course-class[' + d.class + '][id]" value="' + d.courseClassId + '">');
    let content = $('<div class="t-field-tarea content-input"></div>');
    let contentLabel = $('<label class="t-field-tarea__label" for="course-class[' + d.class + '][content]">Conteúdo *</label>');
    let contentInput = $('<textarea class="t-field-tarea__input course-class-content" placeholder="Digite o conteúdo do Plano" id="-' + d.class + '" name="course-class[' + d.class + '][content]">' + d.content + '</textarea>');

    let ability = $('<div class="control-group courseplan-ability-container"></div>');
    let abilityLabel = $('<label class="" for="course-class[' + d.class + '][ability][]">Habilidade(s)</label>');
    let abilityButton = $('<button class="t-button-primary add-abilities" style="height: 28px;gap: 5px" ><icon class="t-icon-start"></icon>Adicionar habilidades</button>');
    let abilitySelect = $('<select id="ability-search-select" class="ability-search-select" name="resource"><option value="">Selecione habilidades...</option></select>');
    let abilitiesContainer = $('<div class="courseplan-abilities-selected">');
    let abilitySelectContainer = $('<div class="row wrap"></div>');

    let methodology = $('<div class="t-field-text control-group courseplan-methodology-container"></div>');
    let methodologyLabel = $('<label class="t-field-tarea__label" for="course-class[' + d.class + '][methodology][]">Metodologia</label>');
    let methodologyInput = $('<textarea class="t-field-tarea__input course-class-methodology" name="course-class[' + d.class + '][methodology]" maxlength="1500"></textarea>');
    methodologyInput.val(d.methodology);

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
        let uniqueDisciplines = [...new Set(d.abilities.map(item => item.discipline))];
        $.each(uniqueDisciplines, function (i, discipline) {
            let div = '<label>' + discipline + '</label>';
            $.each(d.abilities, function (i, v) {
                if(v.discipline == discipline) {
                    div += '<div class="ability-panel-option"><input type="hidden" class="ability-panel-option-id" value="' + v.id + '" name="course-class[' + d.class + '][ability][' + i + ']"><i class="fa fa-check-square"></i><span>(<b>' + v.code + '</b>) ' + v.description + '</span></div>';
                }
            });
            abilitiesContainer.append(div);
        })
        // $.each(d.abilities, function (i, v) {
        //     let div = '<div class="ability-panel-option"><input type="hidden" class="ability-panel-option-id" value="' + v.id + '" name="course-class[' + d.class + '][ability][' + i + ']"><i class="fa fa-check-square"></i><span>(<b>' + v.code + '</b>) ' + v.description + '</span></div>';
        //     abilitiesContainer.append(div);
        // });
    }
    if (d.types !== null) {
        methodologyInput.val(d.methodology);
    }
    if (d.resources !== null) {
        $.each(d.resources, function (i, v) {
            let resourceId = v.id;
            let resourceValue = v.value;
            let resourceName = v.description;
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
    content.append(contentLabel);
    content.append(contentInput);
    ability.append(abilityLabel);
    abilitySelectContainer.append(abilityButton);
    abilitySelectContainer.append(abilitySelect);
    ability.append(abilitySelectContainer);
    ability.append(abilitiesContainer);
    resourceButtonContainer.append(resourceButton);
    resourceInput.append(resourceValue);
    resourceInput.append(resourceAmount);
    resourceInput.append(resourceAdd);
    resource.append(resourceButtonContainer);
    resource.append(resourceLabel);
    resource.append(resourceInput);
    resource.append(resources);
    methodology.append(methodologyLabel);
    methodology.append(methodologyInput);
    column1.append(content);
    column1.append(ability);
    column1.append(methodology);
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
        alert.removeClass('hide');
        alert.addClass('show');
        if(!$('.alert-resource-exist').hasClass('hide'))
            $('.alert-resource-exist').addClass('hide');
        return;
    }
    alert.removeClass('show');
    alert.addClass('hide');
    // The following function will validate the resource input value and append a new resource if is valid
    // The first param is a callback function which will append the new resource to the list of resources
    // that'll be added to the list elem
    validateResource(appendNewResource, newResource, newResourceP);
}

function validateResource(callback, newRes, newResP){
    const resourceInput = $('.new-resource');
    if(resourceInput !== '')
        $.ajax({
            type: "POST",
            url: "?r=courseplan/courseplan/checkResourceExists",
            cache: false,
            data: {
                resource: resourceInput.val()
            },
            success: function (response) {
                const data = JSON.parse(response);
                const alertResourceExist = $('.alert-resource-exist');
                const validate = data['valid'];
                if(!validate){
                    alertResourceExist.removeClass('hide');
                    return;
                }
                alertResourceExist.addClass('hide');
                // callback(validate);
                if(validate)
                    callback(newRes, newResP);
            }
        })
}

function appendNewResource(newResource, newResourceP){
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
        url: "?r=courseplan/courseplan/addResources",
        cache: false,
        data: {
            resources: newResources,
        },
        success: function(data){
            const elements = document.getElementById('new-resources-table');
            // To clean the list of elements added to new resources form
            elements.innerHTML = "";
            newResources.length = 0;
            $('.new-resource').val('');
            updateSelectResources();
        }
    })
}

function updateSelectResources(){
    const resourceSelect = $('select#resource-select');
    resourceSelect.html("");
    $.ajax({
        type: "GET",
        url: "?r=courseplan/courseplan/getResources",
        cache: false
    }).success(function(response){
        let resources = JSON.parse(response);
        Object.entries(resources).forEach(function(option) {
            resourceSelect.append(DOMPurify.sanitize(option));
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
            let divRes = $('<div class="t-badge-content course-class-resource"></div>');
            let values = $('<input class="resource-id" type="hidden" name="course-class[' + d.class + '][resource][' + count + '][id]" value=""/>'
                + '<input class="resource-value" type="hidden" name="course-class[' + d.class + '][resource][' + count + '][value]" value="' + resourceValue + '"/>'
                + '<input class="resource-amount" type="hidden" name="course-class[' + d.class + '][resource][' + count + '][amount]" value="' + resourceAmount + '"/>');
            let label = $('<span class="row"><span class="fa fa-times remove-resource"><i></i></span><span><span class="resource-amount-text">' + resourceAmount + '</span>x - ' + resourceName + ' </span></span>');
            divRes.append(values);
            divRes.append(label);
            resources.append(divRes);
        };
    }
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
