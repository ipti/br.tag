/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$("#print").on('click', function() {
    window.print();
});

$(document).ready(function() {
    limpar();
});
function gerarRelatorio(data) {
    $("#report, #print").show();
    var enrollments = $.parseJSON(data);
    $.each(enrollments, function(i){
        $("table").append(
        '<tr>' +
            '<td style="text-align: center; font-weight: bold"><span class="student-number">' + (i+1) +'</span></td>' +
            '<td><span class="name">' + enrollments[i].name + '</span></td>' +
            '<td style="text-align: center; font-weight: bold"><span class="male">' + ((enrollments[i].sex == 'M') ? 'X' : '') + '</span></td>' +
            '<td style="text-align: center; font-weight: bold"><span class="female">' + ((enrollments[i].sex == 'F') ? 'X' : '') + '</span></td>' +
            '<td style="text-align: center"><span class="birthday">' + enrollments[i].birthday.split('<br>')[0] + '</span></td>' +
            '<td><span class="city">' + enrollments[i].city + '</span></td>' +
            '<td><span class="enrollment-p"></span></td>' +
            '<td><span class="enrollment-pc"></span></td>' +
            '<td><span class="enrollment-t"></span></td>' +
            '<td><span class="situation-n"></span></td>' +
            '<td><span class="situation-p">'
                + '</span></td>'
                    + '<td><span class="address">' + ((enrollments[i].address != '') ? enrollments[i].address : '')
                    + ((enrollments[i].number != '') ? (', ' + enrollments[i].number) : '')
                    + ((enrollments[i].complement != '') ? (', ' + enrollments[i].complement) : '')
                    + ((enrollments[i].neighborhood != '') ? (', ' + enrollments[i].neighborhood) : '')
                + '</span></td>' +
        '</tr>'
        );
    });
}

function limpar() {
    $("#report, #print").hide();
}
