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
        $.getScript('themes/default/js/datatablesptbr.js', function () {
            $(".js-tag-table").each(function () {
                const $table = $(this);
                if ($table.hasClass('dataTable') || $table.hasClass('initialized')) {
                    return;
                }

                const isMobile = window.innerWidth <= 768;
                const numColumns = $table.find("th").length;
                const columnsIndex = new Array(numColumns - 1).fill(1).map((_, i) => i + 1);

                let indexActionButtons;
                if (action.includes("school") || action.includes("activeDisableUser")) {
                    indexActionButtons = [2];
                } else if (action.includes("instructor") || action.includes("indexGradesStructure") || action.includes("courseplan") || action.includes("manageUsers")) {
                    indexActionButtons = [3];
                } else if (action.includes("classroom") || action.includes("student") || action.includes("curricularmatrix") || action.includes("professional")) {
                    indexActionButtons = [4];
                } else if (action.includes("inventory")) {
                    indexActionButtons = [numColumns - 1];
                } else {
                    indexActionButtons = [numColumns - 1]; // Default to last column
                }

                if (action.includes("student") && !action.includes("studentimc") && !action.includes("enrollmentonline")) {
                    $table.DataTable({
                        language: getLanguagePtbr(),
                        serverSide: true,
                        responsive: true,
                        ajax: $.fn.dataTable.pipeline({
                            url: `?r=student/getstudentajax`,
                            pages: 5,
                            method: "POST",
                            data: function (d) {
                                d.page = parseInt(d.start / d.length) + 1;
                                d.perPage = d.length;
                                d.search = { value: $('input[type="search"]').val() };
                                return d;
                            }
                        }),
                        select: { items: 'cell' },
                        columnDefs: [isMobile ? { "className": "none", "targets": columnsIndex } : { orderable: false, targets: [6] }],
                        searching: true,
                    });
                } else if (action.includes("stock")) {
                    // Specific handling for stock items table should match its classes
                    if ($table.hasClass("stock-items-table") || $table.hasClass("transactions-table") || $table.hasClass("js-tag-table")) {
                        $table.dataTable({
                            language: getLanguagePtbr(),
                            responsive: true,
                            select: { items: 'cell' },
                            ordering: false,
                            columnDefs: [isMobile ? { "className": "none", "targets": columnsIndex } : { orderable: false, targets: indexActionButtons }],
                        });
                    }
                } else if (action.includes("inventory")) {
                    // Special handling for inventory tables with CGridView filters
                    const hasFilters = $table.find('thead tr').length > 1;

                    const datatableConfig = {
                        language: getLanguagePtbr(),
                        responsive: true,
                        select: { items: 'cell' },
                        columnDefs: [
                            isMobile ? { "className": "none", "targets": columnsIndex } : { orderable: false, targets: indexActionButtons }
                        ],
                    };

                    // If table has filters (CGridView), configure DataTables to skip filter row
                    if (hasFilters) {
                        datatableConfig.orderCellsTop = true; // Use top row for ordering
                        datatableConfig.searching = true; // Enable global search
                        datatableConfig.paging = true; // Enable pagination
                    }

                    $table.dataTable(datatableConfig);
                } else {
                    $table.dataTable({
                        language: getLanguagePtbr(),
                        responsive: true,
                        select: { items: 'cell' },
                        columnDefs: [isMobile ? { "className": "none", "targets": columnsIndex } : { orderable: false, targets: indexActionButtons }],
                    });
                }
                $table.addClass('initialized');
            });


            // Search placeholder configuration map
            const searchPlaceholders = {
                'school': '  Pesquisar escola',
                'activeDisableUser': '  Pesquisar usuário',
                'enrollmentonline': '  Pesquisar pre-matrícula',
                'classroom': '  Pesquisar turma',
                'instructor': '  Pesquisar professor',
                'manageUsers': '  Pesquisar usuário',
                'student': '  Pesquisar aluno',
                'curricularmatrix': '  Pesquisar matriz',
                'courseplan': '  Pesquisar plano de aula',
                'professional': '  Pesquisar profissional',
                'curricularcomponents': ' Pesquisar componente',
                'inventory': '  Pesquisar registros',
                'lunch/index': '  Pesquisar cardápios',
            };

            // Special handling for stock (multiple containers)
            if (action.includes("stock")) {
                $('.stock-container .dataTables_filter input[type="search"]').attr('placeholder', '  Pesquisar Itens');
                $('.transactions-container .dataTables_filter input[type="search"]').attr('placeholder', '  Pesquisar Movimentações');
            } else {
                // Find matching placeholder
                const matchedKey = Object.keys(searchPlaceholders).find(key => action.includes(key));
                if (matchedKey) {
                    $('.dataTables_filter input[type="search"]').attr('placeholder', searchPlaceholders[matchedKey]);
                }
            }

            //Remove o texto da label original do datatable
            $(".dataTables_filter label").contents().filter(function () {
                return this.nodeType === 3;
            }).remove();

            //adiciona o ícone de pesquisa e loading
            $('.dataTables_filter label').prepend(
                '<img src="../../../themes/default/img/search-icon.svg">'
                //'<i class="icon-search"></i>'
            );

            $('#student-identification-table_filter').css("display", "flex").prepend(
                '<img class="loading-datatable-search" style="display:none; margin-top: 1.2%; height: 30px; width: 30px; padding: 4px" height="30px" width="30px" src="../../../themes/default/img/loadingTag.gif" alt="TAG Loading">'
            );

        });
    }
}
