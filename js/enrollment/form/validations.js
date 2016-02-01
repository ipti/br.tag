/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function(){
    checkMulticlass();
});

var cls = formEnrollment+'classroom_fk'
var pt = formEnrollment + 'public_transport';
var trg = formEnrollment + 'transport_responsable_government';
var uni = formEnrollment + 'unified_class';
var stg = '#Stage';
var mod = formEnrollment + 'edcenso_stage_vs_modality_fk';


var tr = '#transport_responsable';
var tt = '#transport_type';
var tn = '#transport_null';

var traReadOnly;

$(tt + ' input').click(function() {
    traReadOnly = $(this).attr('readonly') == 'readonly';
});

$(cls).change(function(){
    checkMulticlass();
})
$(trg).change(function() {
    $(tt).attr('disabled', 'disabled').hide();
    $(tn).removeAttr('disabled');

    if ($(trg).val() != '') {
        $(tt).removeAttr('disabled').show();
        $(tn).attr('disabled', 'disabled');
    } else {
        $(tt + ' input').attr('checked', false);
    }
});

$(pt).change(function() {
    $(tr).attr('disabled', 'disabled').hide();
    $(tt).attr('disabled', 'disabled').hide();
    $(tn).removeAttr('disabled');
	
    if ($(pt).is(':checked')) {
	$(pt).val('1');
        $(tr).removeAttr('disabled').show();
        $(trg).trigger('change');
    } else {
	$(pt).val('0');
        $(trg).val('').trigger('change');
    }
});

    
$(tt + ' input').change(function() {
    if (traReadOnly) {
        $(this).attr('checked', false);
    }

    var checked = $(tt + ' input:checked');
    if (checked.length >= 3) {
        $(tt + ' input').attr('readonly', true);
        $(tt + ' input:checked').attr('readonly', false);
    } else {
        $(tt + ' input').attr('readonly', false);
        $(tt + ' input:checked').attr('readonly', false);
   }
});

$(pt).trigger('change');

function checkMulticlass(){

    var multi = $(cls + " option:selected" ).attr('id');
    if(multi == 1){
        $('#multiclass').show();
    }else{
        $('#multiclass').hide();
    }

    //console.log(multi);
    //console.log($(cls + " option:selected").attr('value'));
}