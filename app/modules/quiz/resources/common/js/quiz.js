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

    return{
        save: function(){
            if(validate()){
                var data = {description: description, question_id: questionId, answer: answer}
                $.ajax({
                    method: "POST",
                    url: "index.php?r=quiz/default/createOption",
                    data: JSON.stringify({QuestionOption: data}),
                    dataType: 'json'
                  })
                .done(function(data){
                    if(typeof data.errorCode != 'undefined' && data.errorCode == '0'){
                        var element = $('tr')
                            .attr('option-id', data.id)
                            .append($('td').text(1))
                            .append($('td').text(description))
                            .append($('td')
                                .append($('button').attr({'class': 'btn btn-primary'}).text('Editar').click(Option.update))
                                .append($('button').attr({'class': 'btn btn-primary'}).text('Excluir').click(Option.delete))
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