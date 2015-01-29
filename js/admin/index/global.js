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
        files = event.target.files;
    }
// Catch the form submit and upload the files
    function uploadFiles(event)
    {
        event.stopPropagation(); // Stop stuff happening
        event.preventDefault(); // Totally stop stuff happening

        var url = '?r=admin/synchronizationImport';
        var photo = document.getElementById("syncFile");
        var file = photo.files[0];
        var formData = new FormData();
        formData.append("file", file);

        $.ajax(
                {
                    url: url,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: "POST",
                    success: function (result)
                    {
                        console.log(result);
                    }
                });


    }
});



