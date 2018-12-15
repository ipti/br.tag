/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



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