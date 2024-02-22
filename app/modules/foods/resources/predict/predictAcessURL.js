
// $(function() {
//     $.ajax({
//         url: '?r=foods/requisition/getData',
//         type: 'GET',
//         data: {
//             school_code: 'Nada com nada de Itai10',
//             item_code: '001'
//         },
//         dataType: 'json',
//         success: function(response) {
//             $('#example tbody').empty();

//             response.forEach(function(value, index) {
//                 var buttonId = 'modalButton' + index; // Cria um identificador único para cada botão
//                 $('#example tbody').append(
//                     `<tr class="text-center">
//                         <td class="text-center">${value.school_id}</td>
//                         <td class="text-center">${value.item_code}</td>
//                         <td class="text-center">${value.data_predict}</td>
//                         <td class="text-center">${value.quantidade_consumida}</td>
//                         <td class="text-center">Data de fim de estoque</td>
//                         <td class="text-center"><button href="#new-course-class" class="t-statistic-data" style="font-size: 25px; vertical-align: middle;" data-toggle="modal" data-target="#myModal"></button></td></tr>
//                         <td class="text-center"><a id="${buttonId}" class="t-statistic-data" style="font-size: 25px; vertical-align: middle;" data-toggle="modal" data-target="#myModal"></a></td>
//                     </tr>`
//                 );

//                 // Adiciona um evento de clique para cada botão
//                 $('#' + buttonId).click(function() {
//                     $('#myModal .modal-body').text(value.quantidade_consumida); // Exibe o valor no modal
//                 });
//             });

//             $('#example').DataTable();
//         },
//         error: function(xhr, status, error) {
//             console.error(error);
//         }
//     });
// });

// uuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuu

$(function () {
    // Primeira requisição
    $.ajax({
        url: '?r=foods/requisition/getInputData',
        type: 'GET',
        data: {
            school_code: 'Nada com nada de Itai10',
            item_code: '005'
        },
        dataType: 'json',
        success: function (response) {
            $('#secondRequestData').empty();

            response.forEach(function (value, index) {
                var buttonId = 'modalButton' + index;
                var dataValidade = new Date(value.data_validade);
                var formattedDataValidade = dataValidade.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric' });

                var dataPredicao = new Date(value.data_predicao);
                var formattedDataPredicao = dataPredicao.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric' });

                $('#example tbody').append(
                    `<tr class="text-center">
                        <td class="text-center">${value.item_nome}</td>
                        <td class="text-center">${value.item_id}</td>
                        <td class="text-center">${formattedDataPredicao}</td>
                        <td class="text-center">${value.quantidade_consumida}</td>
                        <td class="text-center">${formattedDataValidade}</td>
                        <td class="text-center"><a id="${buttonId}" class="t-statistic-data" style="font-size: 25px; vertical-align: middle;" data-toggle="modal" data-target="#myModal"></a></td>
                    </tr>`
                );

                $('#' + buttonId).click(function () {
                    $('#myModal .modal-body').html('<div id="prediction_chart" style="width: 100%; height: 300px;"></div>');
                    drawPredictionChart(value.quantidade_consumida);
                });
                
            });

            $('#example').DataTable();
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });

    function drawPredictionChart(itemId) {
        google.charts.load('current', { 'packages': ['corechart'] });
        google.charts.setOnLoadCallback(function () {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Year');
            data.addColumn('number', 'Previsão de fim de estoque');
            data.addColumn('number', 'Entrada e saída de alimentos');
    
            var predictionData = [
                ['2004', 1000, 400],
                ['2005', 1170, 460],
                ['2006', 660, 1120],
                ['2007', 1030, 540]
            ];
    
            data.addRows(predictionData);
    
            var options = {
                legend: { position: 'bottom' },
                width: 500,
                x: 10,
                y: 22
            };
    
            var chart = new google.visualization.LineChart(document.getElementById('prediction_chart'));
            chart.draw(data, options);
    
            $('#myModal .modal-body').append('<p>Item ID: ' + itemId + '</p>');
        });
    }
    
});
