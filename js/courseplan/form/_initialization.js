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

$(document).ready(function() {
    var table = $('#course-classes').DataTable( {
//        "ajax": "../ajax/data/objects.txt",
        data: [],
        paginate: false,
        ordering: false,
        lengthMenu: false,
        filter: false,
        info:false,
        "columns": [
            {
                "className":      'details-control dt-center',
                "orderable":      false,
                "data":           null,
                "defaultContent": '<i class="fa fa-plus-circle"></i>',
                "width":          "1px"
            },
            { 
                "className":      'dt-center',
                "data":           "class" ,
                "width":          "1px"
            },
            { 
                "className":      'dt-justify',
                "data":           "objective" 
            },
            {   "data": "content",  "visible": false},
            {   "data": "resource", "visible": false },
            {   "data": "type",     "visible": false },
            {
                "className":      'dt-center',
                "orderable":      false,
                "data":           null,
                "defaultContent": '<a href="#" id="remove-course-class" class="btn btn-danger btn-small"><i class="fa fa-times"></i></a>',
                "width":          "1px"
            }
        ],
        language:{
            emptyTable: "Nenhuma aula cadastrada.",
        }
    } );
     
    // Add event listener for opening and closing details
    $('#course-classes tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var i =  $(this).children('i').first();
        var row = table.row( tr );
 
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
            i.removeClass('fa-minus-circle');
            i.addClass('fa-plus-circle');
        }
        else {
            // Open this row
            row.child( format(row.data()) ).show();
            $('.course-class select').select2({width: 'resolve'});
            tr.addClass('shown');
            i.removeClass('fa-plus-circle');
            i.addClass('fa-minus-circle');
        }
    } );
} );