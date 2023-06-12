/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

window.location.search.includes("update") ? $('.last').css('display', 'block') : $('.last').css('display', 'none');

$('.heading-buttons').css('width', $('#content').width());
$(document).ready(function() {
    $(window).load(function() {
        
        initial_date = stringToDate($(formIdentification+'initial_date').val());    
        final_date = stringToDate($(formIdentification+'final_date').val());
    });
});

$(document).on("click", ".upload-logo-button", function() {
    $("#SchoolIdentification_logo_file_content").click();
});

$(document).on("change", "#SchoolIdentification_logo_file_content", function(e) {
    $(".uploaded-logo-name").text(e.target.files[0].name);
});

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
        vars[key] = value;
    });
    return vars;
}