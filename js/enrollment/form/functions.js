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
function fill(index){
	if (index >= 0 && index < enrollments.length){
		for(i in enrollments[index]){
			val = enrollments[index][i];
			$(formEnrollment+i).val(val).trigger("change");
			$("[id^=StudentEnrollment]").attr("disabled", true);
			$("#Stage").attr("disabled", true);
		}
	}else{
		$("[id^=StudentEnrollment]").val("").trigger("change");
		$("#Stage").val("").trigger("change");
		$("[id^=StudentEnrollment]").attr("disabled", false);
		$("#Stage").attr("disabled", false);
	}
}
