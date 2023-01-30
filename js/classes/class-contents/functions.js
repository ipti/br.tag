function createTable(data) {
    $("#class-contents").attr("classroom", $("#classroom").val()).attr("month", $("#month").val()).attr("discipline", $("#disciplines").val()).attr("fundamentalmaior", $("#classroom option:selected").attr("fundamentalmaior"));
    $('#class-contents > thead').html('<tr><th class="center">Dias</th><th style="text-align:center">Aulas Ministradas em Sala</th></tr>');
    $('#class-contents > tbody').html('');

    var options = "";
    $.each(data.courseClasses, function () {
        options += '<option value="' + this.id + '" disciplineid="' + this.edid + '" disciplinename="' + this.edname + '">' + this.cpname + "|" + this.order + "|" + this.objective + "|" + this.edname + '</option>';
    });

    $.each(data.classContents, function (day, classContent) {
        var head = '<th class="center vmiddle contents-day">' + ((day < 10) ? '0' : '') + day + '</th>';
        var body = '<td>'
            + '<select id="day[' + day + ']" name="day[' + day + '][]" class="course-classes-select vmiddle" ' + (!classContent.available ? "disabled" : "") + ' multiple="yes">'
            + options
            + '</select>'
            + '</td>';
        $('#class-contents > tbody').append('<tr class="center day-row" day="' + day + '">' + head + body + '</tr>');
        var select = $("select.course-classes-select").last();
        select.children("option").each(function () {
            if (!select.find("optgroup[value=" + $(this).attr("disciplineid") + "]").length) {
                select.append("<optgroup value='" + $(this).attr("disciplineid") + "' label='" + $(this).attr("disciplinename") + "'></optgroup>");
            }
            $(this).appendTo(select.find("optgroup[value=" + $(this).attr("disciplineid") + "]"));
        });
        if (classContent.contents !== undefined) {
            $.each(classContent.contents, function (i, v) {
                select.find('option[value=' + v + ']').attr('selected', 'selected');
            });
        }
    });
    $('select.course-classes-select').select2({
        width: "100%",
        formatSelection: function (state) {
            var textArray = state.text.split("|");
            return 'Plano de Aula "' + textArray[0] + '": Aula ' + textArray[1];
        },
        formatResult: function (data, container){
            var textArray = data.text.split("|");
            if (textArray.length === 1){
                return "<div class='course-classes-optgroup'><b>" + textArray[0] + "</b></div>";
            } else {
                return "<div class='course-classes-option'><div><b>Plano de Aula:</b> <span>" + textArray[0] + "</span></div><div><b>Aula " + textArray[1] + "</b> - " + textArray[2] + "</div></div>";
            }
        },
    });
    $('#widget-class-contents').show();
    $('#class-contents').show();
}