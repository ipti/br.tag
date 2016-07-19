/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


 $("#save").on('click' , function() {
 	var result = new Array(); 
 	var idx = 0;
 	$(".stage-value").each(function() {
 		if($(this).val() != "null"){
 			result[idx] = {
			 				idx: $(this).data("student-id"),
			 				val: $(this).val()
 						};
	 		idx++;
 		}		
 	});

 	var st = JSON.stringify(result);
 	

	$.ajax({
         data: {data: st},
         dataType: "json",
         type: "POST",
         url: saveMultiStage,
         success: function (msg) {
             
         },
   });


  location.reload();

});