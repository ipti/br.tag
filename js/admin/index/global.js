//Baixar o Arquivo
$('#callSyncExport').on('click', function () {
    location.href = "/index.php?r=admin/synchronizationExport";
});


$(function ()
{
    
// Variable to store your file
    var files;
// Add events
    $('#syncFile').on('change', prepareUpload);
    $('#syncImport-file-form').on('submit', uploadFiles);
// Grab the files and set them to our variable
    function prepareUpload(event)
    {
        console.log(event.target.files);

        files = event.target.files;
    }
// Catch the form submit and upload the files
    function uploadFiles(event)
    {
        event.stopPropagation(); // Stop stuff happening
        event.preventDefault(); // Totally stop stuff happening
// START A LOADING SPINNER HERE
// Create a formdata object and add the files
    
    var url = '?r=admin/synchronizationImport';
    var photo = document.getElementById("syncFile");
    var file = photo.files[0];
    var formData = new FormData( );
    formData.append( "file", file );
    
    $.ajax( 
    {
      url : url,
      data : formData,
      cache : false,
      contentType : false,
      processData : false,
      type : "POST",
      success: function( result ) 
      {
        console.log(result);
      }
    } );

  
    }
//    function submitForm(event, data)
//    {
//// Create a jQuery object from the form
//        $form = $(event.target);
//// Serialize the form data
//        var formData = $form.serialize();
//// You should sterilise the file names
//        $.each(data.files, function (key, value)
//        {
//            formData = formData + '&filenames[]=' + value;
//        });
//        $.ajax({
//            url: 'submit.php',
//            type: 'POST',
//            data: formData,
//            cache: false,
//            dataType: 'json',
//            success: function (data, textStatus, jqXHR)
//            {
//                if (typeof data.error === 'undefined')
//                {
//// Success so call function to process the form
//                    console.log('SUCCESS: ' + data.success);
//                }
//                else
//                {
//// Handle errors here
//                    console.log('ERRORS: ' + data.error);
//                }
//            },
//            error: function (jqXHR, textStatus, errorThrown)
//            {
//// Handle errors here
//                console.log('ERRORS: ' + textStatus);
//            },
//            complete: function ()
//            {
//// STOP LOADING SPINNER
//            }
//        });
//    }
});



