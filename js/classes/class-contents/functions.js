function createNoDaysTable() {
    $('#class-contents > thead').html('<tr><th class="center">Preencha a frequência primeiro.</th></tr>');
    $('#class-contents > tbody').html('');
    $('#widget-class-contents').show();
    $('#class-contents').show();
    return true;
}

function createTable(data, contents) {
    $('#class-contents > thead').html('<tr><th class="center" style="text-align:center">Dias</th><th>Conteúdo ministrado em sala de aula</th></tr>');
    $('#class-contents > tbody').html('');

    var month = $('#month').val();
    var year = new Date().getFullYear();
    var maxDays = new Date(year, month, 0).getDate();

    var options = "";
    $.each(contents, function (index, value) {
        options += '<option value="' + index + '">' + value + '</option>';
    });

    $.each(data, function (day, content) {
        var head = '<th class="center vmiddle contents-day">'+((day < 10)? '0' : '') + day + '</th>';
        var body = '<td>'
                + '<select id="day[' + day + ']" name="day[' + day + '][]" class="contents-select vmiddle" multiple="yes">'
                + options
                + '</select>'
                + '</td>';
        $('#class-contents > tbody').append('<tr class="center">' + head + body + '</tr>');
        $.each(content, function (i, v) {
            $('.contents-select').last().children('[value=' + i + ']').attr('selected', 'selected');
        });
    });

    $('.contents-select').select2();
    $('#widget-class-contents').show();
    $('#class-contents').show();
    $('#month_text').html($('#month').find('option:selected').text());
    $('#discipline_text').html($('#disciplines').is(":visible") ? $('#disciplines').find('option:selected').text() : "Todas as Disciplinas");
}

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