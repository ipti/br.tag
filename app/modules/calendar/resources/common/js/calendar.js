$(document).on("click", ".change-event", function () {
    var eventId = $(this).data('id');
    var calendarFk = $(this).closest(".calendar").attr("data-id")
    var eventName = "";
    var m = $(this).data('month');
    var d = $(this).data('day');
    var eventStartDate = $(this).data('year') + "-" + (m > 9? m : "0"+m) + "-" + (d > 9? d : "0"+d);
    var eventEndDate =  $(this).data('year') + "-" + (m > 9? m : "0"+m) + "-" + (d > 9? d : "0"+d)
    var eventTypeFk = "";
    var eventCopyable = "0";

    var url = GET_EVENT_URL;

    var change = function(){
        $("#CalendarEvent_id").val(eventId);
        $("#CalendarEvent_calendar_fk").val(calendarFk);
        $("#CalendarEvent_name").val(eventName);
        $("#CalendarEvent_start_date").val(eventStartDate);
        $("#CalendarEvent_end_date").val(eventEndDate);
        $("#CalendarEvent_calendar_event_type_fk").val(eventTypeFk);
        if(eventCopyable == 1) {
            $("#CalendarEvent_copyable").attr("checked", "checked");
        } else{
            $("#CalendarEvent_copyable").removeAttr("checked");
        }
    }

    if(eventId != -1) {
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
    }else{
        $(".remove-event-button").hide();
        change();
    }

});