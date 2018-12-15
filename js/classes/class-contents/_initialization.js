$('#month, #disciplines, #classroom').on('change', function () {
    $('#class-contents').hide();
});

$('#classroom').on('change', function () {
    $('#disciplines').val('').trigger('change');
});

$('#classesSearch').on('click', function () {
    jQuery.ajax({
        type: 'GET',
        url: getClassesURL,
        cache: false,
        data: jQuery('#classroom').parents("form").serialize(),
        success: function (data) {
            var data = jQuery.parseJSON(data);
            $.ajax({
                type: 'POST',
                url: getContentsURL,
                cache: false,
                success: function (contents) {
                    var obj = jQuery.parseJSON(contents);
                    
                    if (data === null) createNoDaysTable();
                    else createTable(data,obj);
                }
            });
        }});
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

