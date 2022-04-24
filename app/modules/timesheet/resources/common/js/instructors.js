$(document).on("click", ".menu div", function() {
  $(".menu div").removeClass("selected");
  $("table").hide();
  $("table." + $(this).attr("class")).show();
  $(this).addClass("selected");
});

$("#instructor_fk").on("change", function() {
  $.ajax({
    url: getInstructorsDisciplinesURL + "/" + $(this).val(),
    type: "get"
  }).success(function(data) {
    data = $.parseJSON(data);
    //console.log(data);
  });
});

$(document).on("click", "#add-instructors-button", function() {
  $("#add-instructors-form").submit();
});
$(document).on("click", "#add-instructors-unavailability-button", function() {
  $("#add-instructors-unavailability-form").submit();
});

$(document).on("click", "#add-unavailability", function() {
  var html = $("#add-instructors-unavailability-times_0")[0]
    .outerHTML.replace(
      /_0/g,
      "_" + $(".add-instructors-unavailability-times").length
    )
    .replace(
      /\[0]/g,
      "[" + $(".add-instructors-unavailability-times").length + "]"
    );

  var last = $("#add-instructors-unavailability-times")
    .children()
    .last();
  $("#add-instructors-unavailability-times")
    .children()
    .last()
    .remove();
  $("#add-instructors-unavailability-times").append(html);
  $("#add-instructors-unavailability-times").append(last);
  $("#add-instructors-unavailability-modal .modal-body").scrollTop(
    $("#add-instructors-unavailability-modal .modal-body").prop("scrollHeight")
  );
});

$(document).on("click", "#add-discipline", function() {
  var qtde = $(".add-instructors-disciplines").length;
  /*
    <div class="row-fluid add-instructors-disciplines" id="add-instructors-disciplines_0">
        <div class=" span6">
    <?= CHtml::dropDownList("add-instructors-disciplines-stage[0]", "", CHtml::listData(EdcensoStageVsModality::getAll(), 'id', 'name'), [
        "class" => "select-search-on span12", "multiple" => "multiple"
    ]) ?>
    </div>
    <div class=" span5">
    <?= CHtml::dropDownList("add-instructors-disciplines-discipline[0]", "", CHtml::listData(EdcensoDiscipline::model()->findAll(), 'id', 'name'), [
        "class" => "select-search-on span12", "multiple" => "multiple"
    ]) ?>
    </div>
    </div>
*/
  var stageOptions = $("#add-instructors-disciplines-stage_0")[0].innerHTML;
  var disciplineOptions = $("#add-instructors-disciplines-discipline_0")[0]
    .innerHTML;
  var select;
  var html =
    '<br><div class="row-fluid add-instructors-disciplines" id="add-instructors-disciplines_' +
    qtde +
    '">' +
    '<div class=" span6">' +
    '<select class="select-search-on span12 select2-offscreen" multiple="multiple" name="add-instructors-disciplines-stage[' +
    qtde +
    '][]" id="add-instructors-disciplines-stage_' +
    qtde +
    '" tabindex="-1"> ' +
    stageOptions +
    "</select>" +
    "</div>" +
    '<div class=" span5">' +
    '<select class="select-search-on span12 select2-offscreen" multiple="multiple" name="add-instructors-disciplines-discipline[' +
    qtde +
    '][]" id="add-instructors-disciplines-discipline_' +
    qtde +
    '" tabindex="-1"> ' +
    disciplineOptions +
    "</select>" +
    "</div>" +
    "</div>";

  var last = $("#add-instructors-disciplines")
    .children()
    .last();
  $("#add-instructors-disciplines")
    .children()
    .last()
    .remove();
  $("#add-instructors-disciplines").append(html);
  $("#add-instructors-disciplines").append(last);
  $("#add-instructors-disciplines-modal .modal-body").scrollTop(
    $("#add-instructors-disciplines-modal .modal-body").prop("scrollHeight")
  );
  $("#add-instructors-disciplines_" + qtde)
    .find("select")
    .select2();
});

$("#instructor_school_fk").on("change", function() {
  $.ajax({
    url: loadUnavailability,
    type: "POST",
    data: {
      id: $(this).val()
    }
  }).success(function(result) {
    result = $.parseJSON(result);
    $(".table-unavailability  td")
      .removeClass("available")
      .removeClass("unavailable");
    if (Object.keys(result).length > 0)
      $(".table-unavailability  td").addClass("available");
    $.each(result, function(week_day, turns) {
      $.each(turns, function(turn, schedules) {
        $.each(schedules, function(k, schedule) {
          $(
            ".table" +
              turn +
              " #h" +
              schedule +
              " td[week_day=" +
              week_day +
              "]"
          )
            .removeClass("available")
            .addClass("unavailable");
        });
      });
    });
  });
});
