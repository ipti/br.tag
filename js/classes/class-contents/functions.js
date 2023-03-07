function createTable(data) {
    $("#class-contents").attr("classroom", $("#classroom").val()).attr("month", $("#month").val()).attr("discipline", $("#disciplines").val()).attr("fundamentalmaior", $("#classroom option:selected").attr("fundamentalmaior"));
    $('#class-contents > thead').html('<tr><th class="center">Dias</th><th style="text-align:center">Aulas Ministradas em Sala</th></tr>');
    $('#class-contents > tbody').html('');

    var options = "";
    $.each(data.courseClasses, function () {
        options += '<option value="' + this.id + '" disciplineid="' + this.edid + '" disciplinename="' + this.edname + '">' + this.cpname + "|" + this.order + "|" + this.objective + "|" + this.edname + '</option>';
    });

    var accordionBuilt = false;
    var accordionHtml = "";
    $.each(data.classContents, function (day, classContent) {
        var studentInputs = "";
        if (Object.keys(classContent.students).length) {
            $.each(classContent.students, function () {
                studentInputs += "<input type='hidden' class='student-diary-of-the-day' studentid='" + this.id + "' value='" + this.diary + "'>";
                if (!accordionBuilt) {
                    accordionHtml +=
                        '<div class="accordion-group" studentid="' + this.id + '">' +
                        '<div class="accordion-heading">' +
                        '<div class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-students" href="#collapse-' + this.id + '"' +
                        '<i class="fa fa-plus-square"></i><a class="accordion-title">' + this.name + '</a>' +
                        '</div></div>' +
                        '<div id="collapse-' + this.id + '" class="accordion-body collapse">' +
                        '<div class="accordion-inner">' +
                        '<textarea class="student-classroom-diary"></textarea>' +
                        '</div></div></div>';
                }
            });
            if (!accordionBuilt) {
                $(".accordion-students").html(accordionHtml);
                accordionBuilt = true;
            }
            $(".classroom-diary-no-students").hide();
        } else {
            $(".classroom-diary-no-students").show();
        }

        var head = '<th class="center vmiddle contents-day">' + ((day < 10) ? '0' : '') + day + '</th>';
        var body = '<td>'
            + '<input type="hidden" class="classroom-diary-of-the-day" value="' + classContent.diary + '">'
            + studentInputs
            + '<i class="fa fa-book classroom-diary-button ' + (!classContent.available ? "disabled" : "") + '" data-toggle="tooltip" title="Diário"></i>'
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
        width: "calc(100% - 40px)",
        formatSelection: function (state) {
            var textArray = state.text.split("|");
            return 'Plano de Aula "' + textArray[0] + '": Aula ' + textArray[1];
        },
        formatResult: function (data, container) {
            var textArray = data.text.split("|");
            if (textArray.length === 1) {
                return "<div class='course-classes-optgroup'><b>" + textArray[0] + "</b></div>";
            } else {
                return "<div class='course-classes-option'><div><b>Plano de Aula:</b> <span>" + textArray[0] + "</span></div><div><b>Aula " + textArray[1] + "</b> - " + textArray[2] + "</div></div>";
            }
        },
    });
    $('[data-toggle="tooltip"]').tooltip({container: "body"});
    $('#widget-class-contents').show();
    $('#class-contents').show();
}