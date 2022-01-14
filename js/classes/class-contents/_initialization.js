$('#classesSearch').on('click', function () {
    if ($("#classroom").val() !== "" && $("#month").val() !== "") {
        $(".alert-no-classroom-and-month").hide();
        jQuery.ajax({
            type: 'GET',
            url: getClassesURL,
            cache: false,
            data: {
                classroom: $("#classroom").val(),
                month: $("#month").val(),
                disciplines: $("#disciplines").val()
            },
            success: function (data) {
                var data = jQuery.parseJSON(data);
                $.ajax({
                    type: 'POST',
                    url: getContentsURL,
                    cache: false,
                    success: function (contents) {
                        var obj = jQuery.parseJSON(contents);

                        if (data === null) {
                            createNoDaysTable();
                            $("#print, #save").hide();
                        } else {
                            createTable(data, obj);
                            $("#print, #save").show();
                        }
                    }
                });
            }
        });
    } else {
        $(".alert-no-classroom-and-month").show();
        $("#widget-class-contents").hide();
        $("#print, #save").hide();
    }
});

$("#classroom").on("change", function () {
    $("#disciplines").val("").trigger("change.select2");
    if ($(this).val() !== "") {
        if ($("#classroom > option:selected").attr("showdisciplines") === "1") {
            $.ajax({
                type: "POST",
                url: "?r=classes/getDisciplines",
                cache: false,
                data: {
                    classroom: $("#classroom").val(),
                },
                success: function (response) {
                    if (response == "") {
                        $("#disciplines").html("<option value='-1'></option>").trigger("change.select2").show();
                    } else {
                        $("#disciplines").html(response).trigger("change.select2").show();
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

$(document).ready(function () {
    $('#class-contents').hide();
});

$("#print").on('click', function () {
    window.print();
});

$("#save").on('click', function () {
    $("#classes-form").submit();
});

$('.heading-buttons').css('width', $('#content').width());

