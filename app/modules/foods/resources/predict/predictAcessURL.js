$(function () {
    function fetchConsumptionData(itemId, callback) {
        $.ajax({
            url: '?r=foods/requisition/getConsumptionData',
            type: 'GET',
            data: {
                code_school: 'Nada com nada de Itai10',
                item_code: itemId
            },
            dataType: 'json',
            success: function (response) {
                console.log(response);
                callback(response);
            },
            
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    }
    
    function makeTerciaryRequest(itemId, callback) {
        $.ajax({
            url: '?r=foods/requisition/getData',
            type: 'GET',
            data: {
                school_code: 'Nada com nada de Itai10',
                item_code: itemId
            },
            dataType: 'json',
            success: function (response) {
                console.log(response);
                callback(response);
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    }

function drawPredictionChart(dataset1, dataset2) {
    var ctx = document.getElementById('prediction_chart').getContext('2d');
    var labels = dataset1.data.map(entry => entry.x);

    dataset1.borderWidth = 2;

    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [dataset1, dataset2] 
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
}


    $.ajax({
        url: '?r=foods/requisition/getInputData',
        type: 'GET',

        dataType: 'json',
        success: function (response) {
            $('#secondRequestData').empty();
            $('#example tbody').empty();

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
                    var itemId = value.item_id; // Obtendo o itemId específico
                
                    $('#myModal .modal-body').html('<canvas id="prediction_chart" style="width: 100%; height: 300px;"></canvas>');
                    $('#myModal .modal-body').append('<p>Item ID: ' + itemId + '</p>');
                
                    fetchConsumptionData(itemId, function (consumptionData) {
                        
                        makeTerciaryRequest(itemId, function (terciaryData) {
                            var dataset1Data = consumptionData.slice(0, -1);
                
                            var dataset2Data = [{ x: consumptionData[consumptionData.length - 2].Data_reposicao, y: consumptionData[consumptionData.length - 2].quantidade_em_estoque }]
                            .concat(consumptionData.slice(-1))
                            .concat(terciaryData);
                        
                        
                            var dataset1 = {
                                label: 'Entrada/saída de estoque',
                                data: dataset1Data.map(entry => ({ x: entry.Data_reposicao, y: entry.quantidade_em_estoque })),
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            };
                
                            var dataset2 = {
                                label: 'Previsão fim de estoque',
                                data: consumptionData.map(entry => ({ x: entry.Data_reposicao, y: entry.quantidade_em_estoque })),
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1,
                                borderDash: [5, 5],
                                fill: false 
                            };
                
                            drawPredictionChart(dataset1, dataset2);
                        });
                    });
                });

                
                






                    

            });

            $('#example').DataTable();
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
});


/// Analisando o código POST aqui:
$(function () {
    $('#sendDataButton').click(function () {
        $.ajax({
            url: '?r=foods/requisition/sendData',
            type: 'POST',
            dataType: 'text',
            success: function (response) {
                console.log(response);
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    });
});





