$(function () {
    function fetchConsumptionData(itemId, callback) {
        $.ajax({
            url: '?r=foods/requisition/getConsumptionData',
            type: 'GET',
            data: {
                // Mudar isso para a escola (o que tem é so teste)
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
                // Mudar isso para a escola (o que tem é so teste)
                
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

    function drawPredictionChart(dataset1, dataset2, dataset3, itemName) {
        var ctx = document.getElementById('prediction_chart').getContext('2d');
        var labels = dataset1.data.slice(-10).map(entry => entry.x); // Ajuste para mostrar apenas os últimos 10 rótulos
        
        dataset1.borderWidth = 2;
    
        // Ajuste para mostrar apenas os últimos 10 dados dos conjuntos de dados
        dataset1.data = dataset1.data.slice(-10);
        dataset2.data = dataset2.data.slice(-10);
        dataset3.data = dataset3.data.slice(-10);
    
        // Calcule o ponto final da linha verde
        var greenLineEnd = {
            x: dataset2.data[dataset2.data.length - 1].x,
            y: dataset2.data[dataset2.data.length - 1].y
        };
    
        // Calcule o ponto inicial da linha vermelha
        var redLineStart = {
            x: dataset3.data[0].x,
            y: dataset3.data[0].y
        };
    
        // Crie um novo conjunto de dados para a nova linha laranja
        var orangeLineData = [
            greenLineEnd,
            redLineStart
        ];
    
        var dataset4 = {
            label: 'Acabando',
            data: orangeLineData,
            backgroundColor: 'rgba(255, 165, 0, 0.2)', // Cor laranja com opacidade
            borderColor: 'rgba(255, 165, 0, 1)', // Cor laranja sólida
            borderWidth: 1,
            fill: false
        };
    
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [dataset1, dataset2, dataset3, dataset4] 
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                title: {
                    display: true,
                    text: 'Consumo de ' + itemName
                }
            }
        });
    }
    

    $.ajax({
        url: '?r=foods/requisition/getInputData',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            $('#example').DataTable().clear().draw();

            response.forEach(function (value, index) {
                var buttonId = 'modalButton' + index;
                var dataValidade = new Date(value.data_validade);
                var formattedDataValidade = dataValidade.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric' });

                var dataPredicao = new Date(value.data_predicao);
                var formattedDataPredicao = dataPredicao.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric' });

                // Adiciona os dados diretamente ao DataTable
                $('#example').DataTable().row.add([
                    value.item_nome,
                    value.item_id,
                    formattedDataPredicao,
                    value.quantidade_consumida,
                    formattedDataValidade,
                    '<a id="' + buttonId + '" class="t-statistic-data" style="font-size: 25px; vertical-align: middle;" data-toggle="modal" data-target="#myModal"></a>'
                ]).draw(false); // Desenha a tabela sem recarregar os dados

                $('#' + buttonId).click(function () {
                    var itemId = value.item_id; // Obtendo o itemId específico
                    var itemName = value.item_nome;

                    $('#myModal .modal-body').html('<canvas id="prediction_chart" style="width: 100%; height: 300px;"></canvas>');
                    // $('#myModal .modal-body').append('<p>Item ID: ' + itemId + '</p>');

                    fetchConsumptionData(itemId, function (consumptionData) {
                        makeTerciaryRequest(itemId, function (terciaryData) {
                            var dataset1Data = consumptionData.slice(0, -1);
                            var dataset2Data = [{ x: consumptionData[consumptionData.length - 2].Data_reposicao, y: consumptionData[consumptionData.length - 2].quantidade_em_estoque }]
                                .concat(consumptionData.slice(-1))
                                .concat(terciaryData);

                            var dataset1 = {
                                label: 'Estoque',
                                data: dataset1Data.map(entry => ({ x: new Date(entry.Data_reposicao).toLocaleDateString('pt-BR', { year: 'numeric', month: '2-digit', day: '2-digit' }), y: entry.quantidade_em_estoque })),
                                backgroundColor: 'rgba(255, 255, 255, 0.2)',
                                borderColor: 'rgba(1, 114, 203, 1)',
                                fontSize: 10,
                                borderWidth: 1
                            };

                            var dataset2 = {
                                label: 'Melhor intervalo de compra',
                                data: consumptionData.map(entry => ({ x: new Date(entry.Data_reposicao).toLocaleDateString('pt-BR', { year: 'numeric', month: '2-digit', day: '2-digit' }), y: entry.quantidade_em_estoque })),
                                backgroundColor: 'rgba(255, 255, 255, 0.2)',
                                borderColor: 'rgba(40, 161, 56, 1)',
                                borderWidth: 1,
                                fontSize: 10,
                                fill: false
                            };


                            // Defina os pontos de dados para a nova linha
                            var newDataPoints = [
                                { x: '26/07/2023', y: 27 },
                                { x: '01/08/2023', y: 0 }
                            ];

                            // Crie um novo conjunto de dados para a nova linha
                            var dataset3 = {
                                label: 'Previsão - Fim do estoque',
                                data: newDataPoints,
                                backgroundColor: 'rgba(255, 255, 255, 0.2)',
                                borderColor: 'rgba(210, 28, 28, 1)',
                                borderDash: [5, 5],
                                borderWidth: 1,
                                fontSize: 10,
                                fill: false
                            };
                            drawPredictionChart(dataset1, dataset2, dataset3, itemName);
                        });
                    });
                });
            });
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
});


// ///POST:
// $(function () {
//     $('#sendDataButton').click(function () {
//         $.ajax({
//             url: '?r=foods/requisition/sendData',
//             type: 'POST',
//             dataType: 'text',
//             success: function (response) {
//                 console.log(response);
//             },
//             error: function (xhr, status, error) {
//                 console.error(error);
//             }
//         });
//     });
// });



