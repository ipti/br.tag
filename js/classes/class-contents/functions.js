function createTable(data) {
    let urlIsValidate = window.location.href.includes("validateClassContents");
    $('#given-classes').html('<div class="t-badge-info t-margin-none--left"><b>Total de carga horária do mês: </b>' + data.totalClasses + '</div><div class="t-badge-info t-margin-none--left"><b>Total de aulas ministradas do mês: </b>' + data.totalClassContents + '</div>')
    var monthSplit = $("#month").val().split("-");
    $("#class-contents")
        .attr("classroom", $("#classroom").val())
        .attr("month", monthSplit[1])
        .attr("year", monthSplit[0])
        .attr("discipline", $("#disciplines").val())
        .attr("fundamentalmaior", $("#classroom option:selected")
        .attr("fundamentalmaior")
    );
    $('#class-contents > thead').html('<tr><th class="center">Dias</th><th style="text-align:left">Conteúdo ministrado em sala de aula</th></tr>');
    $('#class-contents > tbody').html('');

    let options = "";
    let i = 1;
    let pivotChanger = "";
    $.each(data.courseClasses, function () {
        i = pivotChanger !== this.cpid ? 1 : i;
        pivotChanger = this.cpid;
        options += '<option value="' + this.id + '" disciplineid="' + this.edid + '" disciplinename="' + this.edname + '">' + this.cpname + "|" + i++ + "|" + this.content + "|" + this.edname + '</option>';
    });
    let accordionBuilt = false;
    let accordionHtml = "";
    accordionHtml += `<div id='accordion' class='t-accordeon-primary' style='overflow: auto; height: 400px;'>`
    $.each(data.classContents, function (day, classContent) {
        let studentInputs = "";
        if (Object.keys(classContent.students).length) {
            $.each(classContent.students, function (index, studentArray) {
                $.each(studentArray, function (index, student) {
                    studentInputs += "<input type='hidden' class='student-diary-of-the-day' studentid='" + this.id + "' value='" + this.diary + "'>";
                    if (!accordionBuilt) {
                        accordionHtml +=
                            `<div class='align-items--center'>
                                <h4 class='t-title'>
                                    <span class='t-icon-person icon-color'></span>
                                    ${this.name}
                                </h4>
                            </div>
                            <div class='ui-accordion-content js-std-classroom-diaries'>
                                <textarea ${urlIsValidate ? 'readonly' : ''} class='t-field-tarea__input js-student-classroom-diary' studentid='${this.id}' style='resize: vertical;height: 37px;'></textarea>
                            </div>`
                    }
                });
            });

            if (!accordionBuilt) {
                $(".accordion-students").html(accordionHtml);
                accordionBuilt = true;
            }
            $(".classroom-diary-no-students").hide();
        } else {
            $(".classroom-diary-no-students").show();
        }

        let head = '<th class="center vmiddle contents-day ">' + ((day < 10) ? '0' : '') + day + '</th>';
        let body = '<td class="t-multiselect">'
            + '<input type="hidden" class="classroom-diary-of-the-day" value="' + classContent.diary + '">'
            + studentInputs
            + '<span class="t-icon-annotation t-icon classroom-diary-button ' + (!classContent.available ? "disabled" : "") + '" data-toggle="tooltip" title="Diário"></span>'
            + '<select ' + (urlIsValidate ? 'disabled' : '') +' id="day[' + day + ']" name="day[' + day + '][]" class=" course-classes-select vmiddle" ' + (!classContent.available ? "disabled" : "") + ' multiple="yes">'
            + options
            + '</select>'
            + '</td>';
        $('#class-contents > tbody').append('<tr class="center day-row" day="' + day + '">' + head + body + '</tr>');
        let select = $("select.course-classes-select").last();
        select.children("option").each(function () {
            let formatedDisciplineName = $(this).attr("disciplinename") == "undefined" ? "Multidisciplinar" : $(this).attr("disciplinename");

            if (!select.find("optgroup[value=" + $(this).attr("disciplineid") + "]").length) {
                select.append("<optgroup value='" + $(this).attr("disciplineid") + "' label='" + formatedDisciplineName + "'></optgroup>");
            }
            $(this).appendTo(select.find("optgroup[value=" + $(this).attr("disciplineid") + "]"));
        });
        if (classContent.contents !== undefined) {
            $.each(classContent.contents, function (i, v) {
                select.find('option[value=' + v + ']').attr('selected', 'selected');
            });
        }
    });
    accordionHtml += `</div>`
    $('select.course-classes-select').select2({
        width: "calc(100% - 40px)",
        formatSelection: function (state) {
            let textArray = state.text.split("|");
            return '<div class="text-align--left" style="margin-left: 0"><b>Aula:</b> "' + textArray[1] + '" <b>Plano de Aula:</b> ' + textArray[0] + ' <br><b>Conteúdo:</b> ' + textArray[2] + "</div>";
        },
        formatResult: function (data, container) {
            let textArray = data.text.split("|");
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

    $(function () {
        $( "#accordion" ).accordion({
            active: false,
            collapsible: true,
            icons: false,
            heightStyle: "content"
        });
    })

}
