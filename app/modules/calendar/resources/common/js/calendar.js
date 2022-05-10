$(document).on("click", ".change-event", function () {
    $("#myChangeEvent").find(".selected-calendar-current-year").val($(this).closest(".calendar").data("year"));
    $(".error-calendar-event").hide();
    var eventId = $(this).data('id');
    var calendarFk = $(this).closest(".calendar").attr("data-id");
    var eventName = "";
    var m = $(this).data('month');
    var d = $(this).data('day');
    var eventStartDate = $(this).data('year') + "-" + (m > 9 ? m : "0" + m) + "-" + (d > 9 ? d : "0" + d);
    var eventEndDate = $(this).data('year') + "-" + (m > 9 ? m : "0" + m) + "-" + (d > 9 ? d : "0" + d)
    var eventTypeFk = "";
    var eventCopyable = "0";

    var url = GET_EVENT_URL;

    var change = function () {
        $("#CalendarEvent_id").val(eventId);
        $("#CalendarEvent_calendar_fk").val(calendarFk);
        $("#CalendarEvent_name").val(eventName);
        $("#CalendarEvent_start_date").val(eventStartDate);
        $("#CalendarEvent_end_date").val(eventEndDate);
        $("#CalendarEvent_calendar_event_type_fk").val(eventTypeFk);
        if (eventCopyable == 1) {
            $("#CalendarEvent_copyable").attr("checked", "checked");
        } else {
            $("#CalendarEvent_copyable").removeAttr("checked");
        }
    }

    if (eventId != -1) {
        $(".remove-event-button").show();
        $.ajax({
            url: url,
            type: "POST",
            dataType: 'text',
            data: {
                id: eventId
            },
            success: function (data) {
                data = $.parseJSON(data);
                eventId = data.id;
                calendarFk = data.calendar_fk;
                eventName = data.name;
                eventStartDate = data.start_date.split(" ")[0];
                eventEndDate = data.end_date.split(" ")[0];
                eventTypeFk = data.calendar_event_type_fk;
                eventCopyable = data.copyable;
                change();
            },
        });
    } else {
        $(".remove-event-button").hide();
        change();
    }
    $("#myChangeEvent").modal("show");
});

$(document).on("click", ".new-event", function () {
    $("#myNewEvent").find(".selected-calendar-current-year").val($("div.calendar").data("year"));
    $(".error-calendar-event").hide();
});

$(document).on("click", ".new-calendar-button", function () {
    $(".error-calendar-event").hide();
    $(".create-calendar-title, #copy").val("");
    $("#stages").val(null).trigger("change.select2");
    $("#myNewCalendar").modal("show");
});

$(document).on("click", ".create-calendar", function () {
    var form = $(this).closest("form");
    if (form.find(".create-calendar-title").val() === "" || form.find("#stages").val() === null) {
        form.find(".alert").html("Campos com * são obrigatórios.").show();
    } else {
        form.find(".alert").hide();
        $.ajax({
            url: "?r=calendar/default/create",
            type: "POST",
            data: {
                title: $(".create-calendar-title").val(),
                stages: $("#stages").val(),
                copyFrom: $("#copy").val()
            },
            beforeSend: function () {
                $("#myNewCalendar .modal-body").css("opacity", 0.3);
                $("*").css("cursor", "wait");
                $(".create-calendar").attr("disabled", "disabled");
            },
        }).success(function (data) {
            data = JSON.parse(data);
            if (!data.valid) {
                form.find(".alert").html(data.error).show();
                $("#myNewCalendar .modal-body").css("opacity", 1);
                $("*").css("cursor", "auto");
                $(".create-calendar").removeAttr("disabled");
            } else {
                window.location.reload();
            }
        });
    }
});

$(document).on("click", ".save-event", function (e) {
    var form = $(this).closest("form");
    if (form.find("#CalendarEvent_name").val() === "" || form.find("#CalendarEvent_start_date").val() === "" || form.find("#CalendarEvent_end_date").val() === "" || form.find("#CalendarEvent_calendar_event_type_fk").val() === "") {
        form.find(".alert").html("Campos com * são obrigatórios.").show();
    } else if (form.find("#CalendarEvent_end_date").val() < form.find("#CalendarEvent_start_date").val()) {
        form.find(".alert").html("A Data de Encerramento não deve ser anterior à Data de Início.").show();
    } else if (form.find("#CalendarEvent_start_date").val().split("-")[0] !== form.find(".selected-calendar-current-year").val() || form.find("#CalendarEvent_end_date").val().split("-")[0] !== form.find(".selected-calendar-current-year").val()) {
        form.find(".alert").html("O intervalo de datas deve atender o ano do calendário.").show();
    } else {
        form.find(".alert").hide();
        form.submit();
    }
});

$(document).on("click", ".edit-calendar-title", function () {
    $("#edit-calendar-title-modal").find("#Calendar_id").val($(this).closest(".calendar-container").find("div.calendar").attr("data-id"));
    $("#edit-calendar-title-modal").find("#Calendar_title").val($(this).parent().children(".calendar-title").text());
    $("#edit-calendar-title-modal").modal("show");
});

$(document).on("click", ".edit-calendar-title-button", function () {
    var form = $(this).closest("form");
    if (form.find("#Calendar_title").val() === "") {
        form.find(".alert").html("Preencha o campo abaixo.").show();
    } else {
        form.find(".alert").hide();
        form.submit();
    }
});

$(document).on("click", ".remove-calendar", function () {
    $("#calendar_removal_id").val($(this).data('id'));
});

$(document).on("click", ".add-fundamental-menor", function () {
    $("#stages option[value=14]").prop("selected", true);
    $("#stages option[value=15]").prop("selected", true);
    $("#stages option[value=16]").prop("selected", true);
    $("#stages").trigger("change.select2");
});

$(document).on("click", ".add-fundamental-maior", function () {
    $("#stages option[value=17]").prop("selected", true);
    $("#stages option[value=18]").prop("selected", true);
    $("#stages option[value=19]").prop("selected", true);
    $("#stages option[value=20]").prop("selected", true);
    $("#stages option[value=21]").prop("selected", true);
    $("#stages option[value=41]").prop("selected", true);
    $("#stages").trigger("change.select2");
});

$(document).on("click", ".remove-stages", function () {
    $("#stages option").prop("selected", false);
    $("#stages").trigger("change.select2");
});