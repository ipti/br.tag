$('#save_button').click(function() { 
    $('#quiz-form').submit();
});

$('#delete_button').click(function() {
    if(confirm('Deseja excluir o questionário')){
        var host = window.location.href;
        var url = host.split('?')[1];
        var action = 'index.php?' + url.replace('update','delete');
        $('#quiz-form').attr('action', action);
        $('#quiz-form').submit();
    } 
});

$('#save_group_button').click(function() { 
    $('#group-form').submit();
});

$('#delete_group_button').click(function() {
    if(confirm('Deseja excluir o grupo')){
        var host = window.location.href;
        var url = host.split('?')[1];
        var action = 'index.php?' + url.replace('update','delete');
        $('#group-form').attr('action', action);
        $('#group-form').submit();
    } 
});