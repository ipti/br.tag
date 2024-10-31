$("#substituteInstructor").on("change", function() {
    if (
        $('#classrooms').val() !== "" &&
        $("#instructor").val() !== "" &&
        $("#month").val() !== ""
        ){
            $.ajax({
                type: "POST",
                url: "?r=timesheet/timesheet/getFrequency",
                cache: false,
                data: {
                    instructor: $("#instructor").val(),
                    classroom: $("#classrooms").val(),
                    month: $("month").val()
                },
                beforeSend: function () {
                    $(".loading-frequency").css("display", "inline-block");
                    $(".table-frequency").css("opacity", 0.3).css("pointer-events", "none");
                    $("#classrooms, #month, #instructor").attr("disabled", "disabled");
                },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.valid) {
                        var html = "";
                        html += "" +
                        "<table class='t-accordion table-frequency table table-bordered table-striped table-hover'>" +
                        "<thead class='t-accordion__head'>" +
                        "<tr><th class='table-title' colspan='" + (Object.keys(data.instructors[0].schedules).length + 1) + "'>" + ($('#disciplines').select2('data').text) + "aaaaaaa</th></tr>";
                        var daynameRow = "";
                        var dayRow = "";
                        var scheduleRow = "";
                        var checkboxRow = "";
                        $.each(data.schedules, function() {
                            dayRow += "<th>" + (pad(this.day, 2) + "/" + pad($("#month").val(), 2)) + "</th>";
                            daynameRow += "<th>" + this.week_day + "</th>";
                            scheduleRow += "<th>" + this.schedule + "º Horário</th>";
                            checkboxRow += "<th class='frequency-checkbox-general frequency-checkbox-container " + "'><input class='frequency-checkbox' type='checkbox' " + "classroomId='" + $("$classrooms").val() + "' day='" + this.day + "' month='" + $("#month").val() + "' schedule='" + this.schedule + "></th>";
                        });
                        html += "<tr class='day-row'><th></th>" + dayRow + "</tr><tr class='dayname-row'><th></th>" + daynameRow + "</tr>" + ("<tr class='schedule-row'><th></th>" + scheduleRow + "</tr>");
                        html += "</thead><tbody class='t-accordion__body'>";
                        html += "</tbody></table>";
                        $("#frequency-container").html(html).show();
                        $(".frequency-checkbox-general").each(function () {
                            var day = $(this).find(".frequency-checkbox").attr("day");
                            $(this).find(".frequency-checkbox").prop("checked", $(".frequency-checkbox-instructor .frequency-checkbox[day=" + day + "]:checked").length === $(".frequency-checkbox-instructor .frequency-checkbox[day=" + day + "]").length);

                        });
                        $('[data-toggle="tooltip"]').tooltip({ container: "body" });
                    } else {
                        $("#frequency-container").hide();
                        $(".alert-incomplete-data").html(data.error).show();
                    }
                }, complete: function (response) {
                    $(".loading-frequency").hide();
                    $(".table-frequency").css("opacity", 1).css("pointer-events", "auto");
                    $("#classroom, #month, #disciplines, #classesSearch").removeAttr("disabled");
                },
            })
        } else {
            $(".alert-required-fields").show();
            $("#frequency-container, .alert-incomplete-data").hide();
        }
})
