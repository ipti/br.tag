function createValidateTable(data) {
    console.log(data);
    let urlIsValidate = window.location.href.includes("validateClassContents");
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
        console.log(this);
        i = pivotChanger !== this.cpid ? 1 : i;
        pivotChanger = this.cpid;
        options += '<div class="t-badge-info" value="' + this.id + '" disciplineid="' + this.edid + '" disciplinename="' + this.edname + '">' + this.cpname + "|" + i++ + "|" + this.content + "|" + this.edname + '</div>';
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
        let courseClasses = "";
        $.each(data.courseClasses, function (index, courseClass) {
            $.each(classContent.contents, function (index, content) {
                if(courseClass.id == content) {
                    courseClasses += '<div class="t-badge-info column is-four-fifths">'
                    + "Plano de aula: " + courseClass.cpname + " Aula " + courseClass.order + "<br>"
                    + "Conteúdo: " + courseClass.content
                    + '</div>';
                }
            });

        });


        let head = '<th class="center vmiddle contents-day ">' + ((day < 10) ? '0' : '') + day + '</th>';
        let body = '<td class="row">'
            + '<input type="hidden" class="classroom-diary-of-the-day" value="' + classContent.diary + '">'
            + studentInputs
            + '<span class="t-icon-annotation t-icon classroom-diary-button ' + (!classContent.available ? "disabled" : "") + '" data-toggle="tooltip" title="Diário"></span>'
            + '<div class="mobile-row wrap" id="day[' + day + ']" name="day[' + day + '][]" class="vmiddle">'
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
