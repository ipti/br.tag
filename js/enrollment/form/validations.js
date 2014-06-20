/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var pt = form + 'public_transport';
var trg = form + 'transport_responsable_government';

var tr = '#transport_responsable';
var tt = '#transport_type';
var tn = '#transport_null';

$(pt).change(function() {
    $(tr).attr('disabled','disabled').hide();
    $(tt).attr('disabled','disabled').hide();
    $(tn).removeAttr('disabled');
    
    if ($(pt).is(':checked')) {
        $(tr).removeAttr('disabled').show();
    }
});

$(trg).change(function() {
    $(tt).attr('disabled','disabled').hide();
    $(tn).removeAttr('disabled');
    
    if ($(trg).val() != '') {
        $(tt).removeAttr('disabled').show();
        $(tn).attr('disabled','disabled');
    }
});

$(tt+' input').change(function(){
   var checked = $(tt+' input:checked');
   if (checked.length >= 3){
       $(tt+' input').attr('readonly', true);
       $(tt+' input:checked').attr('readonly', false);
   }else{
       $(tt+' input').attr('readonly', false);
       $(tt+' input:checked').attr('readonly', false);
   }
});

$(pt).trigger('change');