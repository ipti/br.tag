function createTable(data) {
    $("#class-contents").attr("classroom", $("#classroom").val()).attr("month", $("#month").val()).attr("discipline", $("#disciplines").val()).attr("fundamentalmaior", $("#classroom option:selected").attr("fundamentalmaior"));
    $('#class-contents > thead').html('<tr><th class="center">Dias</th><th style="text-align:center">Aulas Ministradas em Sala</th></tr>');
    $('#class-contents > tbody').html('');

    var options = "";
    $.each(data.courseClasses, function () {
        options += '<option value="' + this.id + '" discipline="' + this.edname + '">' + this.cpname + "|" + this.order + "|" + this.objective + '</option>';
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
        select.find("option").each(function () {
            if (!select.find("optgroup[label=" + $(this).attr("discipline") + "]").length) {
                select.append("<optgroup label='" + $(this).attr("discipline") + "'></optgroup>");
            }
            $(this).appendTo(select.find("optgroup[label=" + $(this).attr("discipline") + "]"));
        });
        if (classContent.contents !== undefined) {
            $.each(classContent.contents, function (i, v) {
                $("select.course-classes-select").last().children('[value=' + i + ']').attr('selected', 'selected');
            });
        }
    });

    $('.course-classes-select').select2({
        matcher: function modelMatcher(params, data) {
            data.parentText = data.parentText || "";
            if ($.trim(params.term) === '') {
                return data;
            }
            if (data.children && data.children.length > 0) {
                var match = $.extend(true, {}, data);
                for (var c = data.children.length - 1; c >= 0; c--) {
                    var child = data.children[c];
                    child.parentText += data.parentText + " " + data.text;
                    var matches = modelMatcher(params, child);
                    if (matches == null) {
                        match.children.splice(c, 1);
                    }
                }
                if (match.children.length > 0) {
                    return match;
                }
                return modelMatcher(params, match);
            }
            var original = (data.parentText + ' ' + data.text).toUpperCase();
            var term = params.term.toUpperCase().trim();
            original = original.normalize('NFD').replace(/[\u0300-\u036f]/g, "");
            term = term.normalize('NFD').replace(/[\u0300-\u036f]/g, "");
            if (original.indexOf(term) > -1) {
                return data;
            }
            return null;
        }
    });
    $('#widget-class-contents').show();
    $('#class-contents').show();
    $('#month_text').html($('#month').find('option:selected').text());
    $('#discipline_text').html($('#disciplines').is(":visible") ? $('#disciplines').find('option:selected').text() : "Todas as Disciplinas");
}