$("#classesSearch").on("click", function () {
  if (
    $("#classroom").val() !== "" &&
    $("#month").val() !== "" &&
    (!$("#disciplines").is(":visible") || $("#disciplines").val() !== "")
  ) {
    $(".alert-required-fields, .alert-incomplete-data").hide();
    var fundamentalMaior = Number(
      $("#classroom option:selected").attr("fundamentalmaior")
    );
    jQuery.ajax({
      type: "POST",
      url: "?r=classes/getFrequency",
      cache: false,
      data: {
        classroom: $("#classroom").val(),
        fundamentalMaior: fundamentalMaior,
        discipline: $("#disciplines").val(),
        month: $("#month").val(),
      },
      beforeSend: function () {
        $(".loading-frequency").css("display", "inline-block");
        $(".table-frequency").css("opacity", 0.3).css("pointer-events", "none");
        $("#classroom, #month, #disciplines, #classesSearch").attr(
          "disabled",
          "disabled"
        );
      },

      success: function (response) {
        var data = JSON.parse(response);
        if (data.valid) {
          var accordion = "";
          accordion +=
            '<div id="accordion" class="t-accordeon-secondary">';
          var item = 0;
          $.each(data.students[0].schedules, function () {
            var dia = this.day;
            var mes = $("#month").val();
            fault = this.fault;
            item++;
            accordion +=
          `
            <div  class='t-accordeon-container ui-accordion-header'>
              <table class='table-frequency table'>
                <thead>
                  <tr>
                    <th>
                      <div class="column is-half">
                        Nome
                      </div>
                    </th>
                    <th>
                      <div class="column is-four-fifths" style="">
                        ${this.day}/${mes}
                      </div>
                    </th>
                  </tr>
                </thead>
              </table>
            </div>
            <div class='ui-accordion-content'>  
              <table class='table-frequency table'>
                <tbody>`;
                  $.each(data.students, function (indexStudent, student) {
                    var hasFaults = student.schedules.filter((schedule) => dia == schedule.day && mes == $("#month").val() && schedule.fault == true).length > 0;
                    accordion +=
                      `<tr>
                        <td class='student-name'>
                          <div class='t-accordeon-container-table'>
                            ${student.studentName}
                            <a href='javascript:;' studentId=${student.studentId} day=${dia} data-toggle='tooltip' class='frequency-justification-icon ${!hasFaults ? 'hide' : 'show'}' title=''>
                              <span class='t-icon-annotation icon-color'></span>
                            </a>
                          </div>
                            </td>
                      `;
                    $.each(student.schedules, function (indexSchedule, schedule) {
                      if (dia == schedule.day && mes == $("#month").val()) {
                        var justificationContainer = "";
                        if (schedule.fault) {
                          if (schedule.justification !== null) {
                            justificationContainer +=
                              "<a href='javascript:;' data-toggle='tooltip' class='frequency-justification-icon' title='" +
                              schedule.justification +
                              "'></a>";
                          } else {
                            justificationContainer +=
                              "<a href='javascript:;' data-toggle='tooltip' class='frequency-justification-icon'></a>";
                          }
                        }

                        accordion +=
                          `<td class='frequency-checkbox-student frequency-checkbox-container ${!this.available ? $("disabled") : $("")}'>
                            <input class='frequency-checkbox' type='checkbox' ${!schedule.available ? "disabled" : ""} ${schedule.fault ? "checked" : ""} 
                            classroomId = '${$("#classroom").val()}' 
                              studentId = ${student.studentId} 
                              day = ${schedule.day} 
                              month = ${$("#month").val()} 
                              schedule = ${schedule.schedule} 
                              fundamentalMaior = ${fundamentalMaior}>
                                ${justificationContainer}
                          </td>`;
                      }


                    });
                    accordion += `</tr>`;
                  });
                  accordion +=
                `</tbody>
              </table>
            </div>`;
          });
        `
        </div>`;
          $("#frequency-container").html(accordion).show();





          $(function () {
            $("#accordion").accordion({
              collapsible: true,
            });
          });
          $(".frequency-checkbox-general").each(function () {
            var day = $(this).find(".frequency-checkbox").attr("day");
            $(this)
              .find(".frequency-checkbox")
              .prop("checked", $(".frequency-checkbox-student .frequency-checkbox[day=" + day + "]:checked").length === $(".frequency-checkbox-student .frequency-checkbox[day=" + day + "]").length);
          });
          $('[data-toggle="tooltip"]').tooltip({ container: "body" });
        } else {
          $("#frequency-container").hide();
          $(".alert-incomplete-data").html(data.error).show();
        }
      },
      complete: function () {
        $(".loading-frequency").hide();
        $(".table-frequency").css("opacity", 1).css("pointer-events", "auto");
        $("#classroom, #month, #disciplines, #classesSearch").removeAttr(
          "disabled"
        );
      },
    });
  } else {
    $(".alert-required-fields").show();
    $("#frequency-container, .alert-incomplete-data").hide();
  }
});

$(document).on("click", ".frequency-checkbox-container", function (e) {
  if (e.target === this && !$(this).hasClass("disabled")) {
    $(this)
      .find(".frequency-checkbox")
      .prop("checked", !$(this).find(".frequency-checkbox").is(":checked"))
      .trigger("change");
  }
});

$("#classroom").on("change", function () {
  $("#disciplines").val("").trigger("change.select2");
  if ($(this).val() !== "") {
    if ($("#classroom > option:selected").attr("fundamentalMaior") === "1") {
      $.ajax({
        type: "POST",
        url: "?r=classes/getDisciplines",
        cache: false,
        data: {
          classroom: $("#classroom").val(),
        },
        success: function (response) {
          if (response === "") {
            $("#disciplines")
              .html("<option value='-1'></option>")
              .trigger("change.select2")
              .show();
          } else {
            $("#disciplines")
              .html(decodeHtml(response))
              .trigger("change.select2")
              .show();
          }
          $(".disciplines-container").show();
        },
      });
    } else {
      $(".disciplines-container").hide();
    }
  } else {
    $(".disciplines-container").hide();
  }
});

$(document).on("change", ".frequency-checkbox", function () {
  var checkbox = this;
  // console.log($(checkbox).attr('day') == "14");
  // console.log($(checkbox));
  $.ajax({
    type: "POST",
    url: "?r=classes/saveFrequency",
    cache: false,
    data: {
      classroomId: $(this).attr("classroomId"),
      day: $(this).attr("day"),
      month: $(this).attr("month"),
      schedule: $(this).attr("schedule"),
      studentId: $(this).attr("studentId"),
      fault: $(this).is(":checked") ? 1 : 0,
      fundamentalMaior: $(this).attr("fundamentalMaior"),
    },

    beforeSend: function () {
      $(".loading-frequency").css("display", "inline-block");
      $(".table-frequency").css("opacity", 0.3).css("pointer-events", "none");
      $("#classroom, #month, #disciplines, #classesSearch").attr(
        "disabled",
        "disabled"
      );
    },
    complete: function () {
      if ($(checkbox).is(":checked")) {
        $('[studentid=' + $(checkbox).attr('studentid') + '][day=' + $(checkbox).attr('day') + '].frequency-justification-icon').removeClass("hide").addClass("show");
      } else {
        $('[studentid=' + $(checkbox).attr('studentid') + '][day=' + $(checkbox).attr('day') + '].frequency-justification-icon').removeClass("show").addClass("hide");
      }

      $(".loading-frequency").hide();
      $(".table-frequency").css("opacity", 1).css("pointer-events", "auto");
      $("#classroom, #month, #disciplines, #classesSearch").removeAttr(
        "disabled"
      );
    },
  });
});

$(document).on("click", ".frequency-justification-icon", function () {
  var checkbox = $('[studentid=' + $(this).attr('studentid') + '].frequency-checkbox');

  $("#justification-classroomid").val(checkbox.attr("classroomid"));
  $("#justification-studentid").val(checkbox.attr("studentid"));

  $("#justification-day").val(checkbox.attr("day"));
  $("#justification-month").val(checkbox.attr("month"));
  $("#justification-schedule").val(checkbox.attr("schedule"));
  $("#justification-fundamentalmaior").val(checkbox.attr("fundamentalmaior"));
  $(".justification-text").val($(this).attr("data-original-title"));
  $("#save-justification-modal").modal("show");
});

$("#save-justification-modal").on("shown", function () {
  $(".justification-text").focus();
});

$(document).on("click", ".btn-save-justification", function () {
  $.ajax({
    type: "POST",
    url: "?r=classes/saveJustification",
    cache: false,
    data: {
      classroomId: $("#justification-classroomid").val(),
      studentId: $("#justification-studentid").val(),
      day: $("#justification-day").val(),
      month: $("#justification-month").val(),
      schedule: $("#justification-schedule").val(),
      fundamentalMaior: $("#justification-fundamentalmaior").val(),
      justification: $(".justification-text").val(),
    },
    beforeSend: function () {
      $("#save-justification-modal").find(".modal-body").css("opacity", 0.3).css("pointer-events", "none");
      $("#save-justification-modal").find("button").attr("disabled", "disabled");
      $("#save-justification-modal").find(".centered-loading-gif").show();
    },
    success: function (data) {
      var justification = $(".table-frequency tbody .frequency-checkbox[studentid=" + $("#justification-studentid").val() + "][day=" + $("#justification-day").val() + "][month=" + $("#justification-month").val() + "]").parent().parent().find(".frequency-justification-icon");

      if ($(".justification-text").val() == "") {
        justification.attr("data-original-title", "").tooltip("hide");
      } else {
        justification.attr("data-original-title", $(".justification-text").val()).tooltip({ container: "body" });
      }
      $("#save-justification-modal").modal("hide");
    },
    complete: function () {
      $("#save-justification-modal").find(".modal-body").css("opacity", 1).css("pointer-events", "auto");
      $("#save-justification-modal").find("button").removeAttr("disabled");
      $("#save-justification-modal").find(".centered-loading-gif").hide();
    },
  });
});

$(document).on("keyup", ".justification-text", function (e) {
  if (e.keyCode == 13) {
    $(".btn-save-justification").trigger("click");
  }
});
