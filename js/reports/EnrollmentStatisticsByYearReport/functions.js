$(document).ready(function() {

    $('#enrollment-table tbody tr').each(function() {

        var totalEnrollment = 0;
        $(".stage-enrollment").each(function() {
            console.log("Cell: " + $(this).html());
            totalEnrollment += Number($(this).html());
        });
        console.log("totalEnrollment: " + totalEnrollment);
        $(".school-total").html(totalEnrollment);
    });

});