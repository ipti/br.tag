/**
 * 	@var formEnrollment {String}	Prefix element id.
 *  @var enr {String}			String in JSON's format.
 *  @var enrollments {Array}	Array of Objects. Result of JSON's Parser.
 */

/**
 * Fill the fields with the data from {enrollments} in the {index}.
 * 
 * @param index {Integer}	Valid position from {enrollments}.
 */


 $(window).on('load', function() {
    toggleDisabledInputs('#student-enrollment', true)
    $('#StudentEnrollment_classroom_fk').prop( "disabled", false )
    $('#StudentEnrollment_classroom_fk').on('change', function (e) {
        var data = e.val;
        if(data == "")
        {
            toggleDisabledInputs('#student-enrollment', true)
            $('#StudentEnrollment_classroom_fk').prop( "disabled", false )
        } else 
        {
            toggleDisabledInputs('#student-enrollment', false)
        }
    });
});
 
function toggleDisabledInputs(idForm, value) {
    $(idForm+" :input").each(function(){
        var input = $(this);
        input.prop("disabled", value )
    });
}