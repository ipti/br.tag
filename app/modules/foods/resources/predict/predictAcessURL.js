
// $(function () {
//     // Informação sobre o consumo de alimentos (entrada/saída) de alimentos
//     function fetchConsumptionData(item_id, callback) {
//         console.log(item_id)
//         $.ajax({
//             url: '?r=foods/requisition/getConsumptionData',
//             type: 'GET',
//             data: {
//                 code_school: 'Nada com nada de Itai10',
//                 item_code: item_id
//             },
//             dataType: 'json',
//             success: function (response) {
//                 console.log(response);
//                 callback(response);

//                 // // Mostrar apenas quantidade_consumida e quantidade_comprada
//                 // var consumptionDataHtml = '<ul>';
//                 // response.forEach(function (data) {
//                 //     consumptionDataHtml += '<li>Previsão fim de estoque: ' + data.quantidade_consumida + ', Entrada e saída de alimentos: ' + data.quantidade_comprada + ', Data de reposição: ' + data.Data_reposicao + '</li>';
//                 // });
//                 // consumptionDataHtml += '</ul>';
//                 // $('#secondRequestData').html(consumptionDataHtml);
//             },
//             error: function (xhr, status, error) {
//                 console.error(error);
//             }
//         });
//     }

//     // Informação sobre os dados da saída do modelo (de predição)
//     function makeTerciaryRequest(callback) {
//         $.ajax({
//             url: '?r=foods/requisition/getData',
//             type: 'GET',
//             dataType: 'json',
//             success: function (response) {
//                 console.log(response);
//                 callback(response);
//             },
//             error: function (xhr, status, error) {
//                 console.error(error);
//             }
//         });
//     }

//     function drawPredictionChart(dataset1, dataset2) {
//         var ctx = document.getElementById('prediction_chart').getContext('2d');
//         var labels = dataset1.data.map(entry => entry.label);

//         var datasets = [
//             {
//                 label: dataset1.label,
//                 data: dataset1.data.map(entry => entry.consumption),
//                 backgroundColor: dataset1.backgroundColor,
//                 borderColor: dataset1.borderColor,
//                 borderWidth: dataset1.borderWidth
//             },
//             {
//                 label: dataset2.label,
//                 data: dataset2.data.map(entry => entry.consumption),
//                 backgroundColor: dataset2.backgroundColor,
//                 borderColor: dataset2.borderColor,
//                 borderWidth: dataset2.borderWidth
//             }
//         ];

//         var myChart = new Chart(ctx, {
//             type: 'line',
//             data: {
//                 labels: labels,
//                 datasets: datasets
//             },
//             options: {
//                 scales: {
//                     yAxes: [{
//                         ticks: {
//                             beginAtZero: true
//                         }
//                     }]
//                 }
//             }
//         });
//     }

//     // Primeira requisição
//     $.ajax({
//         url: '?r=foods/requisition/getInputData',
//         type: 'GET',
//         data: {
//             school_code: 'Nada com nada de Itai10',
//             item_code: '005'
//         },
//         dataType: 'json',
//         success: function (response) {
//             $('#secondRequestData').empty();
//             $('#example tbody').empty();

//             response.forEach(function (value, index) {
//                 var buttonId = 'modalButton' + index;
//                 var dataValidade = new Date(value.data_validade);
//                 var formattedDataValidade = dataValidade.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric' });

//                 var dataPredicao = new Date(value.data_predicao);
//                 var formattedDataPredicao = dataPredicao.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric' });

//                 $('#example tbody').append(
//                     `<tr class="text-center">
//                       <td class="text-center">${value.item_nome}</td>
//                       <td class="text-center">${value.item_id}</td>
//                       <td class="text-center">${formattedDataPredicao}</td>
//                       <td class="text-center">${value.quantidade_consumida}</td>
//                       <td class="text-center">${formattedDataValidade}</td>
//                       <td class="text-center"><a id="${buttonId}" class="t-statistic-data" style="font-size: 25px; vertical-align: middle;" data-toggle="modal" data-target="#myModal"></a></td>
//                     </tr>`
//                 );

//                 $('#' + buttonId).click(function () {
//                     var itemId = value.item_id; // Capturando o item_id
                
//                     $('#myModal .modal-body').html('<canvas id="prediction_chart" style="width: 100%; height: 300px;"></canvas>');
//                     $('#myModal .modal-body').append('<p>Item ID: ' + itemId + '</p>');
                
//                     fetchConsumptionData(itemId, function (consumptionData) {
//                         var dataset1 = {
//                             label: 'Previsão fim de estoque',
//                             data: consumptionData.map(entry => ({ label: entry.Data_reposicao, consumption: entry.quantidade_em_estoque })),
//                             backgroundColor: 'rgba(255, 99, 132, 0.2)',
//                             borderColor: 'rgba(255, 99, 132, 1)',
//                             borderWidth: 1
//                         };
                
//                         makeTerciaryRequest(function (terciaryData) {
//                             var dataset2 = {
//                                 label: 'Dados da terceira requisição',
//                                 data: terciaryData.map(entry => ({ label: entry.data_predict, consumption: entry.quantidade_consumida })),
//                                 backgroundColor: 'rgba(75, 192, 192, 0.2)',
//                                 borderColor: 'rgba(75, 192, 192, 1)',
//                                 borderWidth: 1
//                             };
                
//                             drawPredictionChart(dataset1, dataset2);
//                         });
//                     });
//                 });

//             });

//             $('#example').DataTable();
//         },
//         error: function (xhr, status, error) {
//             console.error(error);
//         }
//     });

// });








// // function fetchConsumptionData(itemCode, callback) {
// //     $.ajax({
// //         url: '?r=foods/requisition/getConsumptionData',
// //         type: 'GET',
// //         data: {
// //             code_school: 'Nada com nada de Itai10',
// //             item_code: itemCode // Usando o item_code passado como parâmetro
// //         },
// //         dataType: 'json',
// //         success: function (response) {
// //             console.log(response);
// //             callback(response);
// //         },
// //         error: function (xhr, status, error) {
// //             console.error(error);
// //         }
// //     });
// // }

// // function makeTerciaryRequest(itemCode, callback) {
// //     $.ajax({
// //         url: '?r=foods/requisition/getData',
// //         type: 'GET',
// //         data: {
// //             item_code: itemCode // Usando o item_code passado como parâmetro
// //         },
// //         dataType: 'json',
// //         success: function (response) {
// //             console.log(response);
// //             callback(response);
// //         },
// //         error: function (xhr, status, error) {
// //             console.error(error);
// //         }
// //     });
// // }




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
                
                    fetchConsumptionData(itemId, function (consumptionData) { // Passando o itemId como argumento
                        var dataset1 = {
                            label: 'Previsão fim de estoque',
                            data: consumptionData.map(entry => ({ x: entry.Data_reposicao, y: entry.quantidade_em_estoque })),
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        };
                
                        makeTerciaryRequest(itemId, function (terciaryData) {
                            var dataset2 = {
                                label: 'Dados da terceira requisição',
                                data: terciaryData.map(entry => ({ x: entry.data_predict, y: entry.quantidade_consumida })),
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
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



