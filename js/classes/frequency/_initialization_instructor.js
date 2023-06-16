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
          accordion += '<div id="accordion" class"aaaaa">';
          var item = 0;
          $.each(data.students[0].schedules, function () {
            var dia = this.day;
            var mes = $("#month").val();
            item++;
            accordion += `<div class="t-accordeon" style='display: flex; flex-direction: row; justify-content:space-around;'>
                                        <div>
                                            <h3>Nome</h3>
                                        </div>  
                                        <div>
                                            <h3>Data</h3>
                                        </div>
                                    </div>
                                    <div>
                                    <table class='table-frequency table table-bordered table-striped table-hover'>
                                        <thead></thead>
                                        <tbody>`;
            $.each(data.students, function (indexStudent, student) {
              accordion += `<tr>
                                            <td class='student-name'>
                                            ${student.studentName}
                                            </td>`;
              $.each(student.schedules, function (indexSchedule, schedule) {
                if (dia == schedule.day && mes == $("#month").val()) {
                  var justificationContainer = "";

                  if (schedule.fault) {
                    if (schedule.justification !== null) {
                      justificationContainer +=
                        "<a href='javascript:;' data-toggle='tooltip' class='frequency-justification-icon' title='" +
                        schedule.justification +
                        "'><i class='fa fa-file-text-o'></i><i class='fa fa-file-text'></i></a>";
                    } else {
                      justificationContainer +=
                        "<a href='javascript:;' data-toggle='tooltip' class='frequency-justification-icon'><i class='fa fa-file-o'></i><i class='fa fa-file'></i></a>";
                    }
                  }

                  accordion += `<td class='frequency-checkbox-student frequency-checkbox-container ${
                    !this.available ? $("disabled") : $("")
                  }'>
                                                            <input class='frequency-checkbox' type='checkbox'
                                                                ${
                                                                  !schedule.available
                                                                    ? $(
                                                                        "disabled"
                                                                      )
                                                                    : $("")
                                                                } ${
                    schedule.fault ? $("checked") : $("")
                  } 
                                                                classroomId = '${$(
                                                                  "#classroom"
                                                                ).val()}' 
                                                                studentId = ${
                                                                  student.studentId
                                                                } 
                                                                day = ${
                                                                  schedule.day
                                                                } 
                                                                month = ${$(
                                                                  "#month"
                                                                ).val()} 
                                                                schedule = ${
                                                                  schedule.schedule
                                                                } 
                                                                fundamentalMaior = ${fundamentalMaior}
                                                            >
                                                        ${justificationContainer}
                                        </td>`;
                }
              });
              accordion += `<tr>`;
            });
            accordion += `</tbody>
                                    </table>
                                </div>`;
          });
          `</div>
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
              .prop(
                "checked",
                $(
                  ".frequency-checkbox-student .frequency-checkbox[day=" +
                    day +
                    "]:checked"
                ).length ===
                  $(
                    ".frequency-checkbox-student .frequency-checkbox[day=" +
                      day +
                      "]"
                  ).length
              );
          });
          $('[data-toggle="tooltip"]').tooltip({ container: "body" });
        } else {
          $("#frequency-container").hide();
          $(".alert-incomplete-data").html(data.error).show();
        }
      },
      complete: function (response) {
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
    complete: function (response) {
      if ($(checkbox).attr("studentId") === undefined) {
        $(
          ".table-frequency tbody .frequency-checkbox[day=" +
            $(checkbox).attr("day") +
            "][schedule=" +
            $(checkbox).attr("schedule") +
            "]"
        ).prop("checked", $(checkbox).is(":checked"));
        if ($(checkbox).is(":checked")) {
          $(
            ".table-frequency tbody .frequency-checkbox[day=" +
              $(checkbox).attr("day") +
              "][schedule=" +
              $(checkbox).attr("schedule") +
              "]"
          ).each(function () {
            if (
              !$(this).parent().find(".frequency-justification-icon").length
            ) {
              $(this)
                .parent()
                .append(
                  "<a href='javascript:;' data-toggle='tooltip' class='frequency-justification-icon'><i class='fa fa-file-o'></i><i class='fa fa-file'></i></a>"
                );
            }
          });
        } else {
          $(
            ".table-frequency tbody .frequency-checkbox[day=" +
              $(checkbox).attr("day") +
              "][schedule=" +
              $(checkbox).attr("schedule") +
              "]"
          )
            .parent()
            .find(".frequency-justification-icon")
            .remove();
        }
      } else {
        if ($(checkbox).is(":checked")) {
          $(checkbox)
            .parent()
            .append(
              "<a href='javascript:;' data-toggle='tooltip' class='frequency-justification-icon'><i class='fa fa-file-o'></i><i class='fa fa-file'></i></a>"
            );
        } else {
          $(checkbox).parent().find(".frequency-justification-icon").remove();
        }
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
  var checkbox = $(this).parent().find(".frequency-checkbox");
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
      $("#save-justification-modal")
        .find(".modal-body")
        .css("opacity", 0.3)
        .css("pointer-events", "none");
      $("#save-justification-modal")
        .find("button")
        .attr("disabled", "disabled");
      $("#save-justification-modal").find(".centered-loading-gif").show();
    },
    success: function (data) {
      var justification = $(
        ".table-frequency tbody .frequency-checkbox[studentid=" +
          $("#justification-studentid").val() +
          "][day=" +
          $("#justification-day").val() +
          "][month=" +
          $("#justification-month").val() +
          "]"
      )
        .parent()
        .find(".frequency-justification-icon");
      if ($(".justification-text").val() == "") {
        justification.html(
          "<i class='fa fa-file-o'></i><i class='fa fa-file'></i>"
        );
        justification.attr("data-original-title", "").tooltip("hide");
      } else {
        justification.html(
          "<i class='fa fa-file-text-o'></i><i class='fa fa-file-text'></i>"
        );
        justification
          .attr("data-original-title", $(".justification-text").val())
          .tooltip({ container: "body" });
      }
      $("#save-justification-modal").modal("hide");
    },
    complete: function (response) {
      $("#save-justification-modal")
        .find(".modal-body")
        .css("opacity", 1)
        .css("pointer-events", "auto");
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
