/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$('.heading-buttons').css('width', $('#content').width());
$(document).ready(function() {
    $(window).load(function() {
        $('#SchoolIdentification_edcenso_uf_fk').val($('#SchoolIdentification_edcenso_uf_fk').val()).trigger('change');
    });
});