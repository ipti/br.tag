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

$('#save_question_group_button').click(function() { 
    $('#questiongroup-form').submit();
});

$('#delete_question_group_button').click(function() {
    if(confirm('Deseja excluir o grupo')){
        var host = window.location.href;
        var url = host.split('?')[1];
        var action = 'index.php?' + url.replace('update','delete');
        $('#questiongroup-form').attr('action', action);
        $('#questiongroup-form').submit();
    } 
});


$('#save_question_button').click(function() { 
    $('#question-form').submit();
});

$('#delete_question_button').click(function() {
    if(confirm('Deseja excluir a questão')){
        var host = window.location.href;
        var url = host.split('?')[1];
        var action = 'index.php?' + url.replace('update','delete');
        $('#question-form').attr('action', action);
        $('#question-form').submit();
    } 
});

var Option = function(){
    var description = $('#QuestionOption_description');
    var answer = $('#QuestionOption_answer');
    var questionId = $('#QuestionOption_question_id');
    var id = $('#QuestionOption_id');
    var container = $('#container_option');
    var button = $('#save_option_button');

    return{
        insert: function(){
            if(Option.validate()){
                var data = {description: description.val(), question_id: questionId.val(), answer: answer.val()};
                $.ajax({
                    type: "POST",
                    url: "index.php",
                    data: { r: 'quiz/default/createOption', QuestionOption: data},
                    dataType: 'json'
                  })
                .done(function(data){
                    if(typeof data.errorCode != 'undefined' && data.errorCode == '0'){
                        var element = $('<tr></tr>')
                            .attr('option-id', data.id)
                            .append($('<td></td>').text(1))
                            .append($('<td></td>').text(data.description))
                            .append($('<td></td>')
                                .append($('<button></button>').attr({'class': 'btn btn-primary'}).text('Editar').click(Option.initUpdate))
                                .append($('<button></button>').attr({'class': 'btn btn-primary'}).text('Excluir').click(Option.initDelete))
                            );
                        container.append(element);
                        Option.clear();
                    }
                })
                .fail(function(data){
                    alert('Erro ao salvar item');
                });
            }
        },
        initUpdate: function(){
            id.val($(this).closest('tr').attr('option-id'));
            button.unbind('click');
            button.bind('click', Option.update);
        },
        initDelete: function(){
            id.val($(this).closest('tr').attr('option-id'));
            button.unbind('click');
            button.bind('click', Option.update);
        },
        init: function(){
            id.val('');
            button.unbind('click');
            button.bind('click', Option.insert);
        },
        validate: function(){
            if(description.val() == '' || answer.val() == '' || questionId.val() == '' ){
                alert('Preencha todos os campos!');
                return false;
            }
            return true;
        },
        clear: function(){
            description.val('');
            answer.val('');
            id.val('');
        }
    }
}();

Option.init();