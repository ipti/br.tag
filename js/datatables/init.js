const action = window.location.search;
$(initDatatable);

function initDatatable() {

    //
    // Pipelining function for DataTables. To be used to the `ajax` option of DataTables
    //
    $.fn.dataTable.pipeline = function (opts) {
        // Configuration options
        let conf = $.extend(
            {
                pages: 5, // number of pages to cache
                url: '', // script url
                data: null, // function or object with parameters to send to the server
                // matching how `ajax.data` works in DataTables
                method: 'GET', // Ajax HTTP method
            },
            opts
        );

        // Private variables for storing the cache
        let cacheLower = -1;
        let cacheUpper = null;
        let cacheLastRequest = null;
        let cacheLastJson = null;

        return function (request, drawCallback, settings) {
            let ajax = false;
            let requestStart = request.start;
            let drawStart = request.start;
            let requestLength = request.length;
            let requestEnd = requestStart + requestLength;

            if (settings.clearCache) {
                // API requested that the cache be cleared
                ajax = true;
                settings.clearCache = false;
            } else if (cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper) {
                // outside cached data - need to make a request
                ajax = true;
            } else if (
                JSON.stringify(request.order) !== JSON.stringify(cacheLastRequest.order) ||
                JSON.stringify(request.columns) !== JSON.stringify(cacheLastRequest.columns) ||
                JSON.stringify(request.search) !== JSON.stringify(cacheLastRequest.search)
            ) {
                // properties changed (ordering, columns, searching)
                ajax = true;
            }

            // Store the request for checking next time around
            cacheLastRequest = $.extend(true, {}, request);

            if (ajax) {
                // Need data from the server
                if (requestStart < cacheLower) {
                    requestStart = requestStart - requestLength * (conf.pages - 1);

                    if (requestStart < 0) {
                        requestStart = 0;
                    }
                }

                cacheLower = requestStart;
                cacheUpper = requestStart + requestLength * conf.pages;

                request.start = requestStart;
                request.length = requestLength * conf.pages;

                // Provide the same `data` options as DataTables.
                if (typeof conf.data === 'function') {
                    // As a function it is executed with the data object as an arg
                    // for manipulation. If an object is returned, it is used as the
                    // data object to submit
                    let d = conf.data(request);
                    if (d) {
                        $.extend(request, d);
                    }
                } else if ($.isPlainObject(conf.data)) {
                    // As an object, the data given extends the default
                    $.extend(request, conf.data);
                }

                return $.ajax({
                    type: conf.method,
                    url: conf.url,
                    data: request,
                    dataType: 'json',
                    cache: false,
                    beforeSend: function () {
                        $(".loading-datatable-search").show();
                    },
                    success: function (json) {
                        $(".loading-datatable-search").hide();
                        cacheLastJson = $.extend(true, {}, json);

                        if (cacheLower != drawStart) {
                            json.data.splice(0, drawStart - cacheLower);
                        }
                        if (requestLength >= -1) {
                            json.data.splice(requestLength, json.data.length);
                        }

                        drawCallback(json);
                    },
                });
            } else {
                json = $.extend(true, {}, cacheLastJson);
                json.draw = request.draw; // Update the echo for each response
                json.data.splice(0, requestStart - cacheLower);
                json.data.splice(requestLength, json.data.length);

                drawCallback(json);
            }
        };
    };

    // Register an API method that will empty the pipelined data, forcing an Ajax
    // fetch on the next draw (i.e. `table.clearPipeline().draw()`)
    $.fn.dataTable.Api.register('clearPipeline()', function () {
        return this.iterator('table', function (settings) {
            settings.clearCache = true;
        });
    });

    if ($(".js-tag-table").has(".empty").length > 0) {
        return;
    }

    if ($(".js-tag-table").length) {
        const isMobile = window.innerWidth <= 768;
        const numColumns = $(".js-tag-table th").length;
        const columnsIndex = new Array(numColumns - 1).fill(1).map((_, i) => i + 1);


        $.getScript('themes/default/js/datatablesptbr.js', function () {
            let indexActionButtons;
            if (action.includes("school")
                || action.includes("activeDisableUser")) {
                indexActionButtons = [2];
            }
            if (action.includes("instructor")
                || action.includes("courseplan")
                || action.includes("manageUsers")) {
                indexActionButtons = [3];
            }
            if (action.includes("classroom")
                || action.includes("student")
                || action.includes("curricularmatrix")
                || action.includes("professional")) {
                indexActionButtons = [4];
            }
            if (action.includes("student")) {
                $(".js-tag-table").DataTable({
                    language: getLanguagePtbr(),
                    serverSide: true,
                    responsive: true,
                    ajax: $.fn.dataTable.pipeline({
                        url: `?r=student/getstudentajax`,
                        pages: 5, // number of pages to cache
                        method: "POST", // Ajax HTTP method
                        data: function (d) {
                            d.page = parseInt(d.start / d.length) + 1;
                            d.perPage = d.length;
                            d.search = { value: $('input[type="search"]').val() };
                            return d;
                        }
                    }),
                    select: {
                        items: 'cell'
                    },
                    // "bLengthChange": false,
                    columnDefs: [isMobile ? { "className": "none", "targets": columnsIndex } : { orderable: false, targets: [6] }],
                    searching: true,
                });
            }else if ((action.includes("stock"))){
                $(".stock-items-table").dataTable({
                    language: getLanguagePtbr(),
                    responsive: true,
                    // pageLength: 20,
                    select: {
                        items: 'cell'
                    },
                    ordering: false,
                    // "bLengthChange": false,
                    columnDefs: [isMobile ? { "className": "none", "targets": columnsIndex } : { orderable: false, targets: indexActionButtons }],
                });
                $(".transactions-table").dataTable({
                    language: getLanguagePtbr(),
                    responsive: true,
                    // pageLength: 10,
                    select: {
                        items: 'cell'
                    },
                    ordering: false,
                    // "bLengthChange": false,
                    columnDefs: [isMobile ? { "className": "none", "targets": columnsIndex } : { orderable: false, targets: indexActionButtons }],
                });
            }else {
                $(".js-tag-table").dataTable({
                    language: getLanguagePtbr(),
                    responsive: true,
                    select: {
                        items: 'cell'
                    },
                    // "bLengthChange":     ,
                    columnDefs: [isMobile ? { "className": "none", "targets": columnsIndex } : { orderable: false, targets: indexActionButtons }],
                });
            }

            //Definido placeholder para cada módulo
            if(action.includes("school")) $('.dataTables_filter input[type="search"]').attr('placeholder', '  Pesquisar escola')
            else if(action.includes("activeDisableUser")) $('.dataTables_filter input[type="search"]').attr('placeholder', '  Pesquisar usuário')
            else if(action.includes("classroom")) $('.dataTables_filter input[type="search"]').attr('placeholder', '  Pesquisar turma')
            else if(action.includes("instructor")) $('.dataTables_filter input[type="search"]').attr('placeholder', '  Pesquisar professor')
            else if(action.includes("manageUsers")) $('.dataTables_filter input[type="search"]').attr('placeholder', '  Pesquisar usuário')
            else if(action.includes("student")) $('.dataTables_filter input[type="search"]').attr('placeholder', '  Pesquisar aluno')
            else if(action.includes("curricularmatrix")) $('.dataTables_filter input[type="search"]').attr('placeholder', '  Pesquisar matriz')
            else if(action.includes("courseplan")) $('.dataTables_filter input[type="search"]').attr('placeholder', '  Pesquisar plano de aula')
            else if(action.includes("professional")) $('.dataTables_filter input[type="search"]').attr('placeholder', '  Pesquisar profissional')
            else if(action.includes("curricularcomponents")) $('.dataTables_filter input[type="search"]').attr('placeholder', ' Pesquisar componente')
            else if(action.includes("stock")) {
                $('.stock-container .dataTables_filter input[type="search"]').attr('placeholder', '  Pesquisar Itens')
                $('.transactions-container .dataTables_filter input[type="search"]').attr('placeholder', '  Pesquisar Movimentações')
            }else if(action.includes("lunch/index")) $('.dataTables_filter input[type="search"]').attr('placeholder', '  Pesquisar cardápios')

            //Remove o texto da label original do datatable
            $(".dataTables_filter label").contents().filter(function() {
                return this.nodeType === 3;
            }).remove();

            //adiciona o ícone de pesquisa e loading
            $('.dataTables_filter label').prepend(
                '<img src="../../../themes/default/img/search-icon.svg">'
            );

            $('#student-identification-table_filter').css("display", "flex").prepend(
                '<img class="loading-datatable-search" style="display:none; margin-top: 1.2%; height: 30px; width: 30px; padding: 4px" height="30px" width="30px" src="../../../themes/default/img/loadingTag.gif" alt="TAG Loading">'
            );

        });
    }
}
