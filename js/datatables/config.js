/**
 * DataTables Configuration Module
 * 
 * This module provides a centralized configuration system for DataTables initialization.
 * Each page/module can have its own specific configuration.
 * 
 * Usage:
 * 1. Add a new configuration in the pageConfigs object
 * 2. Define a match function to identify when to use this config
 * 3. Provide the DataTables configuration object or function
 */

/**
 * Get DataTables configuration for a specific page
 * @param {string} action - The current page action/URL
 * @param {jQuery} $table - The table element
 * @param {boolean} isMobile - Whether the device is mobile
 * @param {Array} columnsIndex - Array of column indices
 * @param {Array} indexActionButtons - Indices of action button columns
 * @returns {Object} DataTables configuration object
 */
function getDatatableConfig(action, $table, isMobile, columnsIndex, indexActionButtons) {

    // Configuration map for page-specific DataTables settings
    const pageConfigs = {
        student: {
            match: () => action.includes("student") && !action.includes("studentimc") && !action.includes("enrollmentonline"),
            config: {
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
            }
        },

        stock: {
            match: () => action.includes("stock") && ($table.hasClass("stock-items-table") || $table.hasClass("transactions-table") || $table.hasClass("js-tag-table")),
            config: {
                language: getLanguagePtbr(),
                responsive: true,
                select: { items: 'cell' },
                ordering: false,
                columnDefs: [isMobile ? { "className": "none", "targets": columnsIndex } : { orderable: false, targets: indexActionButtons }],
            }
        },

        inventory: {
            match: () => action.includes("inventory"),
            config: () => {
                const hasFilters = $table.find('thead tr').length > 1;
                return {
                    language: getLanguagePtbr(),
                    responsive: true,
                    select: { items: 'cell' },
                    columnDefs: [isMobile ? { "className": "none", "targets": columnsIndex } : { orderable: false, targets: indexActionButtons }],
                    // Special config for tables with CGridView filters
                    ...(hasFilters && {
                        orderCellsTop: true,  // Use top row for ordering
                        searching: true,      // Enable global search
                        paging: true,         // Enable pagination
                    })
                };
            }
        },

        // Default configuration for all other pages
        default: {
            match: () => true,
            config: {
                language: getLanguagePtbr(),
                responsive: true,
                select: { items: 'cell' },
                columnDefs: [isMobile ? { "className": "none", "targets": columnsIndex } : { orderable: false, targets: indexActionButtons }],
            }
        }
    };

    // Find matching configuration
    const pageConfig = Object.values(pageConfigs).find(cfg => cfg.match());

    // Return config (execute if function, return directly if object)
    return typeof pageConfig.config === 'function'
        ? pageConfig.config()
        : pageConfig.config;
}

/**
 * Get search placeholder text for a specific page
 * @param {string} action - The current page action/URL
 * @returns {string|null} Placeholder text or null if no match
 */
function getSearchPlaceholder(action) {
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

    // Find matching placeholder
    const matchedKey = Object.keys(searchPlaceholders).find(key => action.includes(key));
    return matchedKey ? searchPlaceholders[matchedKey] : null;
}
