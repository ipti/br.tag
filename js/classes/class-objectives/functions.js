function createNoDaysTable() {
    $('#class-objectives > thead').html('<tr><th class="center">Preencha a frequência primeiro.</th></tr>');
    $('#class-objectives > tbody').html('');
    $('#widget-class-objectives').show();
    $('#class-objectives').show();
    return true;
}

function createTable(data, objectives) {
    $('#class-objectives > thead').html('<tr><th class="center" style="text-align:center">Dia</th><th class="center">Conteúdo ministrado em sala de aula</th></tr>');
    $('#class-objectives > tbody').html('');

    var month = $('#month').val();
    var year = new Date().getFullYear();
    var maxDays = new Date(year, month, 0).getDate();

    var options = "";
    $.each(objectives, function (index, value) {
        options += '<option value="' + index + '">' + value + '</option>';
    });

    $.each(data, function (day, objective) {
        var head = '<th class="center vmiddle objectives-day">' + day + '</th>';
        var body = '<td>'
                + '<select id="day[' + day + ']" name="day[' + day + '][]" class="objectives-select vmiddle" multiple="yes">'
                + options
                + '</select>'
                + '</td>';
        $('#class-objectives > tbody').append('<tr class="center">' + head + body + '</tr>');
        $.each(objective, function (i, v) {
            $('.objectives-select').last().children('[value=' + i + ']').attr('selected', 'selected');
        });
    });

    $('.objectives-select').select2();
    $('#widget-class-objectives').show();
    $('#class-objectives').show();
    $('#month_text').html($('#month').find('option:selected').text());
    $('#discipline_text').html($('#disciplines').find('option:selected').text());
}

var addObjective = function () {
    var description = $('#add-objective-description').val().toUpperCase();
    $.ajax({
        type: 'POST',
        url: saveObjectiveURL,
        cache: false,
        data: {'description':description},
        success: function(data) {
            var data = $.parseJSON(data);
            var selects = $('select.objectives-select');
            
            if(selects.length > 0){
                $.each(selects, function(){
                    var index = data['id'];
                    var value = data['description'];
                    $(this).append('<option value="' + index + '">' + value + '</option>');
                });
            }
        }
    });
};