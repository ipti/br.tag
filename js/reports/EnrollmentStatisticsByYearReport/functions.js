$(document).ready(function() {

    var totalEnrollmentPerSchool;
    var columnIndex;

    $('#enrollment-table tbody tr').each(function() {
        totalEnrollmentPerSchool = 0;
        columnIndex = 0;
        $(this).find(".stage-enrollment").each(function(i, value) {
            totalEnrollmentPerSchool += Number($(value).html());
            $(value).addClass('col-'+columnIndex);
            columnIndex++;
        });
        $(this).find(".school-total").html(totalEnrollmentPerSchool);
    });

    $('#enrollment-table tfoot .col-total .stage-total').each(function(i) {
        var colTotal = 0;
        $('.col-'+i).each(function() {
            colTotal += Number($(this).html());
        });
        $(this).html(colTotal);
    });

    $('#enrollment-table tfoot .group-stage-total .group-total').each(function(i) {
        var groupTotal = 0;
        $('.stage-'+i).each(function() {
            groupTotal += Number($(this).html());
        });
        $(this).html(groupTotal);
    });

    var redeTotal = 0;
    $('.school-total').each(function() {
        redeTotal += Number($(this).html());
    });
    $('.rede-total').html(redeTotal);

});