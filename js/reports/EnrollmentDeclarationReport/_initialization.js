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
    var infos = $.parseJSON(data);
    for (var i in infos) {
    	if(i == "cc"){
	    	if(infos[i] == 1){
	    		$("#old_cc").show();
	    		$("#new_cc").hide();
	    	}else{
	    		$("#old_cc").hide();
	    		$("#new_cc").show();
	    	}
    	}
        if (i != 'id'){
            if (i == 'inep_id') {
                for (var j = 0; j < infos["inep_id"].length; j++){
                    $(".table_inep_id td").eq(j).text(infos["inep_id"][j]);
                }
            }
            if (i == 'nis') {
                for (var j = 0; j < infos["nis"].length; j++){
                    $(".table_nis td").eq(j).text(infos["nis"][j]);
                }
            }            
            $("." + i).html(infos[i]);
        }
    }

}

function limpar() {
    $("#report, #print").hide();
}
