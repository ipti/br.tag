
$('#callSyncExport').on('click', function(){
    $.ajax({
  type: "POST",
  url: "admin/synchronizationExport",
 // data: { name: "John", location: "Boston" }
}).done(function( msg ) {
   // console.log( msg );
  });
});
