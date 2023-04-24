function create(element, id){
    $.ajax({
            type: 'POST',
            url: `/?r=sedsp/default/CreateRA&id=${id}`,
            success: function (data) {
                $(element).html(data);
            }
        });
}
function generate(element, url){
    $.ajax({
            type: 'POST',
            url: url,
            success: function (data) {
                $(element).html(data);
            },
            error: function (error) {
                const approved = confirm('Aluno nao encontrado na SED, deseja envia-lo?');
                if (approved) {
                    create(element, error.responseJSON.id);
                }
            }
        });
}
$(document).ready(function () {
    $('.generate').click(function (event) {
        event.preventDefault();

        const element = this;
        const url = $(this).attr('href');

        generate(element, url);

        return false;
    });
});