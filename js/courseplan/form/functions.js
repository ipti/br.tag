function setStateAdd() {
    state = add;
}
function setStateRemove() {
    state = remove;
}
function setStateIdle() {
    state = idle;
}
function getState() {
    return state;
}
function stateIsAdd() {
    return state = add;
}
function stateIsRemove() {
    return state = remove;
}
function stateIsIdle() {
    return state = idle;
}

var addContent = function () {
    var name = $('#add-content-name').val().toUpperCase();
    var description = $('#add-content-description').val().toUpperCase();
    $.ajax({
        type: 'POST',
        url: saveContentURL,
        cache: false,
        data: {'name': name, 'description': description},
        success: function (data) {
            var data = $.parseJSON(data);
            var selects = $('select.contents-select');

            if (selects.length > 0) {
                $.each(selects, function () {
                    var index = data['id'];
                    var value = data['name'];
                    $(this).append('<option value="' + index + '">' + value + '</option>');
                });
            }
        }
    });
};

function addCoursePlanRow() {
    setStateAdd();

    var lastTr = $('#course-classes tbody tr[role=row]').last();
    var index = 0;
    if (lastTr.length > 0) {
        index = table.row($('#course-classes tbody tr[role=row]').last()).index() + 1;
    }

    $(".details-control .fa-minus-circle").click();

    table.row.add({
        "class": index + 1,
        "objective": "",
        "content": null,
        "resource": null,
        "type": null
    }).draw();
    $("#course-classes tbody .details-control").last().click();
    setStateIdle();
}

function removeCoursePlanRow(element) {
    setStateRemove();
    var tr = $(element).parent().parent();
    table.row(tr).remove().draw();
    setStateIdle();
}

function addResource(button) {
    var div = $(button).parent();
    var resourceValue = div.children("select").val();
    var resourceName = div.children("select").select2('data').text;
    var resourceAmount = div.children("input[name=amount]").val();
    var resources = div.parent().children(".resources");

    if (resourceAmount > 0 && resourceAmount < 1000 && resourceValue !== "") {
        var tr = div.closest('tr').prev();
        var row = table.row(tr);
        var d = row.data();
        var count = $(resources).children('.course-class-resource').length;
        var div = $('<div class="course-class-resource" name="course-class[' + d.class + '][resource][' + count + ']"></div>')
        var values = $('<input class="resource-value" type="hidden" name="course-class[' + d.class + '][resource][' + count + '][value]" value="' + resourceValue + '"/>'
                + '<input class="resource-amount" type="hidden" name="course-class[' + d.class + '][resource][' + count + '][amount]" value="' + resourceAmount + '"/>');
        var label = $('<p>' + resourceAmount + 'x - ' + resourceName + ' <span class="pull-right fa fa-times remove-resource"><i></i></span></p>');
        div.append(values);
        div.append(label);
        resources.append(div);
    }
}

function removeResource(button) {
    var resource = $(button).closest(".course-class-resource");
    var resources = resource.parent();
    var classe = resources.closest("tr").prev().children(".details-control").next().text();
    resource.remove();
    $.each(resources.children(".course-class-resource"), function(){
        var index = resources.children(".course-class-resource").index(this);
        $(this).attr("name", "course-class[" + classe + "][resource][" + index + "]");
        $(this).children(".resource-value").attr("name", "course-class[" + classe + "][resource][" + index + "][value]");
        $(this).children(".resource-amount").attr("name", "course-class[" + classe + "][resource][" + index + "][amount]");
    });
}

function format(d) {
    var $div = $('<div id="course-class[' + d.class + ']" class="course-class"></div>');
    var $column1 = $('<div id="course-class-column1" class="span8"></div>');
    var $objective = $('<div class="control-group span12"></div>');
    var $objectiveLabel = $('<div class="span6"><label class="" for="course-class[' + d.class + '][objective]">' + labelObjective + '</label></span>');
    var $objectiveInput = $('<textarea class="course-class-objective span7" name="course-class[' + d.class + '][objective]">' + d.objective + '</textarea>');

    var $content = $('<div class="control-group span4"></div>');
    var $contentLabel = $('<label class="" for="course-class[' + d.class + '][content][]">' + labelContent + '</label>');
    var $contentInput = $('<select class="span3" name="course-class[' + d.class + '][content][]" multiple>' + contents + '</select>');

    var $type = $('<div class="control-group span4"></div>');
    var $typeLabel = $('<label class="" for="course-class[' + d.class + '][type][]">' + labelType + '</label>');
    var $typeInput = $('<select class="span3" name="course-class[' + d.class + '][type][]" multiple>' + types + '</select>');

    var $column2 = $('<div id="course-class-column2" class="span4"></div>');
    var $resource = $('<div class="control-group span4"></div>');
    var $resourceLabel = $('<label class="span4" for="resource">' + labelResource + '</label>');
    var $resourceInput = $('<div class="span4 resource-input"></div>');
    var $resourceValue = $('<select class="span3" name="resource" >' + resources + '</select>');
    var $resourceAmount = $('<input class="pull-right" style="width:25px; height: 18px" type="number" name="amount" step="1" min="1" value="1" max="999"></input>');
    var $resourceAdd = $('<button class="btn btn-success btn-small pull-right fa fa-plus-square add-resource" style="height: 28px" ><i></i></button>');

    var $resources = $('<div class="span4 resources"></div>');
    if (d.content !== null) {
        $contentInput.val(d.content);
    }
    if (d.type !== null) {
        $typeInput.val(d.type);
    }
    if (d.resource !== null) {
        $.each(d.resource, function (i, v) {
            var resourceValue = v.value;
            var resourceName = $resourceValue.find("option[value=" + v.value + "]").text();
            var resourceAmount = v.amount;
            var div = $('<div class="course-class-resource" name="course-class[' + d.class + '][resource][' + i + ']"></div>')
            var values = $('<input class="resource-value" type="hidden" name="course-class[' + d.class + '][resource][' + i + '][value]" value="' + resourceValue + '"/>'
                    + '<input class="resource-amount" type="hidden" name="course-class[' + d.class + '][resource][' + i + '][amount]" value="' + resourceAmount + '"/>');
            var label = $('<p>' + resourceAmount + 'x - ' + resourceName + ' <span class="pull-right fa fa-times remove-resource"><i></i></span></p>');
            div.append(values);
            div.append(label);
            $resources.append(div);
        });
    }
    $objective.append($objectiveLabel);
    $objective.append($objectiveInput);
    $content.append($contentLabel);
    $content.append($contentInput);
    $resourceInput.append($resourceValue);
    $resourceInput.append($resourceAdd);
    $resourceInput.append($resourceAmount);
    $resource.append($resourceLabel);
    $resource.append($resourceInput);
    $resource.append($resources);
    $type.append($typeLabel);
    $type.append($typeInput);
    $column1.append($objective);
    $column1.append($content);
    $column1.append($type);
    $column2.append($resource);
    $div.append($column1);
    $div.append($column2);

    return $div;
}