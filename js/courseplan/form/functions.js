var addContent = function () {
    var name = $('#add-content-name').val().toUpperCase();
    var description = $('#add-content-description').val().toUpperCase();
    $.ajax({
        type: 'POST',
        url: saveContentURL,
        cache: false,
        data: {'name':name, 'description':description},
        success: function(data) {
            var data = $.parseJSON(data);
            var selects = $('select.contents-select');
            
            if(selects.length > 0){
                $.each(selects, function(){
                    var index = data['id'];
                    var value = data['name'];
                    $(this).append('<option value="' + index + '">' + value + '</option>');
                });
            }
        }
    });
};


function format ( d ) {
    var $div = $('<div id="course-class['+d.class+']" class="course-class"></div>');
    var $objective = $('<div class="control-group span12"></div>');
    var $objectiveLabel = $('<div class="span6"><label class="control-label" for="course-class['+d.class+'][objective]">'+labelObjective+'</label></span>');
    var $objectiveInput = $('<textarea class="course-class-objective span11" name="course-class['+d.class+'][objective]">'+d.objective+'</textarea>');
    
    var $content = $('<div class="control-group span4"></div>');
    var $contentLabel = $('<label class="control-label" for="course-class['+d.class+'][content]">'+labelContent+'</label>');
    var $contentInput = $('<select class="span3" name="course-class['+d.class+'][content]" multiple>'+d.content+'</select>');
    
    var $resource = $('<div class="control-group span4"></div>');
    var $resourceLabel = $('<label class="control-label" for="course-class['+d.class+'][resource]">'+labelResource+'</label>');
    var $resourceInput = $('<select class="span3" name="course-class['+d.class+'][resource]" multiple>'+d.resource+'</select>');
    
    var $type = $('<div class="control-group span4"></div>');
    var $typeLabel = $('<label class="control-label" for="course-class['+d.class+'][type]">'+labelType+'</label>');
    var $typeInput = $('<select class="span3" name="course-class['+d.class+'][type]" multiple>'+d.type+'</select>');
 
    $objective.append($objectiveLabel);
    $objective.append($objectiveInput);
    $content.append($contentLabel);
    $content.append($contentInput);
    $resource.append($resourceLabel);
    $resource.append($resourceInput);
    $type.append($typeLabel);
    $type.append($typeInput);
    $div.append($objective);
    $div.append($content);
    $div.append($resource);
    $div.append($type);
    
    return $div;
}