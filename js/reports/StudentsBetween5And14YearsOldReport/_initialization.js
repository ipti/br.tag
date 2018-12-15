
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$("#print").on('click', function() {
    window.print();
});

$(document).ready(function() {
    $(".table-classroom").each(function() {
        if (!$(this).find(".student").length) {
            $("<tr class='student'><td>N&atilde;o h&aacute; alunos entre 5 e 14 anos</td></tr>").insertAfter($(this).find("th").parent());
        }
    })
});