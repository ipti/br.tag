$('#classroom').change(function () {
    $.ajax({
        type: 'POST',
        url: getGradesUrl,
        cache: false,
        data: {classroom: $("#classroom").val()},
        success: function (data) {
            data = jQuery.parseJSON(data);
            if (data !== null && !$.isEmptyObject(data.students)) {
                $(".students ul li").remove();
                $(".grades .tab-content .tab-pane").remove();
                $(".classroom").show();
                $.each(data.students, function (i, v) {
                    $(".classroom .students ul").append('<li>'
                            + '<a href="#tab' + v + '" data-toggle="tab">'
                            + '<i></i><span class="strong">' + i + '</span>'
                            + '</a>'
                            + '</li>');
                    $(".grades .tab-content").append('<div class="tab-pane" id="tab' + v + '">'
                            + '<h5>Notas de '+ i +'</h5>'
                            + '<p></p>'
                            + '</div>');
                    
                });
                $(".classroom .students li").first().addClass('active');
                $(".grades .tab-content .tab-pane").first().addClass('active');
            } else {
                $(".classroom").hide();
            }
        }
    });
});