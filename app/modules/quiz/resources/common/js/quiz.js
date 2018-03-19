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
                    type: "GET",
                    url: "index.php",
                    data: { r: 'quiz/default/createOption', QuestionOption: data},
                    dataType: 'json',
                    contentType: "application/json; charset=utf-8"
                  })
                .done(function(data){
                    if(typeof data.errorCode != 'undefined' && data.errorCode == '0'){
                        var element = $('<tr></tr>')
                            .attr({'option-id': data.id, 'option-description': data.description, 'option-answer': data.answer})
                            .append($('<td></td>').text(container.find('tr').lenght + 1))
                            .append($('<td></td>').text(data.description))
                            .append($('<td></td>').attr({'class': 'center-button'})
                                .append($('<button></button>').attr({'class': 'btn btn-primary space-button font-button'}).text('Editar').click(Option.initUpdate))
                                .append($('<button></button>').attr({'class': 'btn btn-primary space-button font-button'}).text('Excluir').click(Option.initDelete))
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
        update: function(){
            if(Option.validate()){
                var data = {id: id.val(), description: description.val(), question_id: questionId.val(), answer: answer.val()};
                $.ajax({
                    type: "GET",
                    url: "index.php",
                    data: { r: 'quiz/default/updateOption', id: id.val(), QuestionOption: data},
                    dataType: 'json',
                    contentType: "application/json; charset=utf-8"
                  })
                .done(function(data){
                    if(typeof data.errorCode != 'undefined' && data.errorCode == '0'){
                       var elementActive = container.find('tr[option-id="'+data.id+'"]');
                        var element = $('<tr></tr>')
                            .attr('option-id', data.id)
                            .append($('<td></td>').text(elementActive.children('td:eq(0)').text()))
                            .append($('<td></td>').text(data.description))
                            .append($('<td></td>').attr({'class': 'center-button'})
                                .append($('<button></button>').attr({'class': 'btn btn-primary space-button font-button'}).text('Editar').click(Option.initUpdate))
                                .append($('<button></button>').attr({'class': 'btn btn-primary space-button font-button'}).text('Excluir').click(Option.initDelete))
                            );
                        elementActive.replaceWith(element);
                    }
                    else if(typeof data.errorCode != 'undefined' && data.errorCode == '1'){
                        alert(data.msg);
                    }
                    Option.clear();
                    Option.initInsert();
                })
                .fail(function(data){
                    alert('Erro ao alterar item');
                    Option.clear();
                    Option.initInsert();
                });
            }
        },
        delete: function(){
            var data = {id: id.val(), question_id: questionId.val()};
            $.ajax({
                type: "GET",
                url: "index.php",
                data: { r: 'quiz/default/deleteOption', id: id.val(), QuestionOption: data},
                dataType: 'json',
                contentType: "application/json; charset=utf-8"
                })
            .done(function(data){
                if(typeof data.errorCode != 'undefined' && data.errorCode == '0'){
                    var elementActive = container.find('tr[option-id="'+data.id+'"]');
                    elementActive.remove();
                }
                else if(typeof data.errorCode != 'undefined' && data.errorCode == '1'){
                    alert(data.msg);
                }

                Option.clear();
                Option.initInsert();
            })
            .fail(function(data){
                alert('Erro ao alterar item');
                Option.clear();
                Option.initInsert();
            });
        },
        initInsert: function(){
            id.val('');
            button.unbind('click');
            button.bind('click', Option.insert);
        },
        initUpdate: function(e){
            e.preventDefault();
            var element = $(this).closest('tr');
            id.val(element.attr('option-id'));
            description.val(element.attr('option-description'));
            answer.val(element.attr('option-answer'));
            button.unbind('click');
            button.bind('click', Option.update);
        },
        initDelete: function(e){
            e.preventDefault();
            var element = $(this).closest('tr');
            id.val(element.attr('option-id'));
            if(confirm('Deseja excluir o item?')){
                Option.delete();
            }
        },
        init: function(){
            id.val('');
            button.unbind('click');
            button.bind('click', Option.insert);
            Option.buildTable(dataOption);
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
        },
        buildTable: function(data){
            $.each(data, function(K, v){
                var element = $('<tr></tr>')
                            .attr({'option-id': v.id, 'option-description': v.description, 'option-answer': v.answer})
                            .append($('<td></td>').text(container.find('tr').length + 1))
                            .append($('<td></td>').text(v.description))
                            .append($('<td></td>').attr({'class': 'center-button'})
                                .append($('<button></button>').attr({'class': 'btn btn-primary space-button font-button'}).text('Editar').click(Option.initUpdate))
                                .append($('<button></button>').attr({'class': 'btn btn-primary space-button font-button'}).text('Excluir').click(Option.initDelete))
                            );
                container.append(element);
            });
        }
    }
}();

Option.init();