function createValidateTable(data) {
    let urlIsValidate = window.location.href.includes("validateClassContents");
    $('#given-classes').html('<div class="t-badge-info t-margin-none--left"><b>Total de carga horária do mês: </b>' + data.totalClasses + '</div><div class="t-badge-info t-margin-none--left"><b>Total de aulas ministradas do mês: </b>' + data.totalClassContents + '</div>')
    let monthSplit = $("#month").val().split("-");
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

    let accordionBuilt = false;
    let accordionHtml = "";
    accordionHtml += `<div id='accordion' class='t-accordeon-primary' style='overflow: auto; height: 400px;'>`
    $.each(data.classContents, function (day, classContent) {
        let studentInputs = "";
        if (Object.keys(classContent.students).length) {
            $.each(classContent.students, function (index, studentArray) {
                $.each(studentArray, function (index, student) {
                    studentInputs += "<input type='hidden' class='student-diary-of-the-day' studentid='" + student.id + "' value='" + student.diary + "'>";
                    if (!accordionBuilt) {
                        accordionHtml +=
                            `<div class='align-items--center'>
                                <h4 class='t-title'>
                                    <span class='t-icon-person icon-color'></span>
                                    ${student.name}
                                </h4>
                            </div>
                            <div class='ui-accordion-content js-std-classroom-diaries'>
                                <textarea ${urlIsValidate ? 'readonly' : ''} class='t-field-tarea__input js-student-classroom-diary' studentid='${student.id}' style='resize: vertical;height: 37px;'></textarea>
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
        let courseClasses = "";
        let isContentsEmpty = false;

        $.each(data.courseClasses, function (index, courseClass) {
            if (classContent.contents && Object.keys(classContent.contents).length) {
                $.each(classContent.contents, function (index, content) {
                    if(courseClass.id == content) {
                        courseClasses += '<div class="t-badge-info column text-align--left t-padding-medium--x" style="width:80%">'
                        + "<div>"
                        + "<b>Aula:</b> " + courseClass.order + " <b>Plano de aula:</b> " + courseClass.cpname + "<br>"
                        + "<b>Conteúdo:</b> " + courseClass.content
                        + "</div>"
                        + '</div>';
                    }
                });
            } else {
                isContentsEmpty =  true;
            }
        });

        if(isContentsEmpty) {
            courseClasses += '<div class="t-badge-info column text-align--left t-padding-medium--x">'
            + 'Nenhuma aula ministrada neste dia'
            + '</div>';
        }


        let head = '<th class="center vmiddle contents-day ">' + ((day < 10) ? '0' : '') + day + '</th>';
        let body = '<td class="row align-items--center">'
            + studentInputs
            + '<span style="font-size: 24px" class="t-icon t-icon-annotation t-margin-large--left classroom-diary-button' + (!classContent.available ? "disabled" : "") + '" data-toggle="tooltip" title="Diário"></span>'
            + '<div class="column clearfix" id="day[' + day + ']" name="day[' + day + '][]">'
            + courseClasses
            + '</div>'
            + '</td>';
        $('#class-contents > tbody').append('<tr class="center day-row" day="' + day + '">' + head + body + '</tr>');
    });
    accordionHtml += `</div>`
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
