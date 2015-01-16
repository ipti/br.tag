    //Cria o Dialogo de IMPORTAÇÃO
    var myImportFileDialog = $("#import-file-dialog").dialog({
        autoOpen: false,
        height: 190,
        width: 380,
        modal: true,
        draggable: false,
        resizable: false,
        buttons: [
            {text: btnImport,
                click: function() {
                    var file = $("#file").val();
                    console.log(file);
                    $("#import-file-form").submit();
                    $(this).dialog("close");
                }
            },
            {text: btnCancel,
                click: function() {
                    $(this).dialog("close");
                }
            }
        ],
    });
    
    //Cria o Diálogo de Importação para a sincronização
    var mySyncImportFileDialog = $("#syncImport-file-dialog").dialog({
        autoOpen: false,
        height: 190,
        width: 380,
        modal: true,
        draggable: false,
        resizable: false,
        buttons: [
            {text: btnImport,
                click: function() {
                    var file = $("#file").val();
                    console.log(file);
                    $("#syncImport-file-form").submit();
                    $(this).dialog("close");
                }
            },
            {text: btnCancel,
                click: function() {
                    $(this).dialog("close");
                }
            }
        ],
    });