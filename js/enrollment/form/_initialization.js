jQuery(function($) {
    jQuery.ajax({
        'type': 'POST',
        'url': baseUrl + 'index.php?r=enrollment/updatedependencies',
        'cache': false,
        'data': jQuery("#StudentEnrollment_school_inep_id_fk").parents("form").serialize(),
        'success': function(data) {
            data = jQuery.parseJSON(data);
            $('#StudentEnrollment_student_fk').html(data.Students);
            $('#StudentEnrollment_classroom_fk').html(data.Classrooms);
        }
    });

});

$('.heading-buttons').css('width', $('#content').width());

$(".enrollmentButton").on("click", function(){
	filled = $(this).attr("cod");
	fill(filled);
})

$("#tab-student-enrollment").on("click",function(){
	$('[cod = -1]').trigger('click');
	//fill(-1);
});
