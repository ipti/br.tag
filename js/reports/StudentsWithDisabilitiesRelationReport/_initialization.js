
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$("#print").on('click', function() {
    window.print();
});

$(document).ready(function() {
    $("table").each(function() {
        if (!$(this).find("td").length) {
            $(this).closest(".classroom-container").remove();
        }
    });
});