

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$("#print").on('click', function() {
    window.print();
});

$(document).ready(function() {
    if (!$(".table-no-instructors").find("td").length) {
        $(".table-no-instructors").remove();
        $(".innerLR").append("<br><span class='alert alert-primary'>N&atilde;o h&aacute; turmas para esta escola.</span>");
    }
});