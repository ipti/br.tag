console.log('Professional Allocation JS loaded');

// Função para abrir o modal
function openAllocationModal(reset) {
    if (reset) {
        clearAllocationForm();
        $('#allocationModalLabel').text('Nova Lotação');
    }
    $('#allocation-modal').modal('show');
}

function closeAllocationModal() {
    $('#allocation-modal').modal('hide');
}

function editAllocation(element) {
    console.log('editAllocation called', element);

    // Agora pegamos o ID direto do href ou atributo, pois CButtonColumn gear links com ID na URL
    // Mas configuramos 'url' => '$data->id', então o href será "ID".
    // Ou usamos $(element).attr('href') se for um link.
    // O template gerou <a class="btn-edit-allocation" href="ID">...</a>
    debugger;
    var id = $(element).attr('href');
    if (!id || id === '#') {
        // Fallback para tentar data-id se mudarmos a estratégia
        id = $(element).data('id');
    }

    console.log('Fetching allocation for ID:', id);

    if (!id) {
        console.error('ID not found for allocation edit');
        return;
    }

    if (typeof ProfessionalAllocationConfig === 'undefined' || !ProfessionalAllocationConfig.viewUrl) {
        console.error('ProfessionalAllocationConfig.viewUrl is undefined');
        alert('Erro de configuração JS.');
        return;
    }

    // AJAX para buscar dados
    $.ajax({
        url: ProfessionalAllocationConfig.viewUrl,
        type: 'GET',
        data: { id: id },
        success: function (response) {
            console.log('View response:', response);
            if (response.success && response.data) {
                var data = response.data;

                // Preencher campos
                $('#ProfessionalAllocation_id').val(data.id);
                $('#ProfessionalAllocation_professional_fk').val(data.professional_fk);
                $('#location-type-select').val(data.location_type).change();

                handleLocationTypeChange(data.location_type);

                if (data.location_type === 'school') {
                    $('#ProfessionalAllocation_school_inep_fk').val(data.school_inep_fk);
                    $('#ProfessionalAllocation_location_name').val('');
                } else {
                    $('#ProfessionalAllocation_school_inep_fk').val('');
                    $('#ProfessionalAllocation_location_name').val(data.location_name);
                }

                $('#ProfessionalAllocation_role').val(data.role);
                $('#ProfessionalAllocation_contract_type').val(data.contract_type);
                $('#ProfessionalAllocation_workload').val(data.workload);
                $('#ProfessionalAllocation_status').val(data.status); // Populates status

                // Atualizar título e abrir modal
                $('#allocationModalLabel').text('Editar Lotação');
                $('#allocation-modal').modal('show');
            } else {
                alert('Erro ao carregar dados: ' + (response.message || 'Erro desconhecido'));
            }
        },
        error: function (xhr, status, error) {
            console.error('AJAX error:', status, error);
            alert('Erro na requisição ao buscar dados.');
        }
    });
}

function clearAllocationForm() {
    $('#ProfessionalAllocation_id').val('');
    $('#location-type-select').val('');
    $('#ProfessionalAllocation_school_inep_fk').val('');
    $('#ProfessionalAllocation_location_name').val('');
    $('#ProfessionalAllocation_role').val('');
    $('#ProfessionalAllocation_contract_type').val('');
    $('#ProfessionalAllocation_workload').val('');
    $('#ProfessionalAllocation_status').val('1'); // Default to Active

    handleLocationTypeChange('');
}

function handleLocationTypeChange(type) {
    if (type === 'school') {
        $('#school-selection-row').show();
        $('#location-name-row').hide();
    } else if (type === 'secretariat' || type === 'other') {
        $('#school-selection-row').hide();
        $('#location-name-row').show();

        if (type === 'secretariat' && $('#ProfessionalAllocation_location_name').val() === '') {
            $('#ProfessionalAllocation_location_name').val('Secretaria Municipal de Educação');
        }
    } else {
        $('#school-selection-row').hide();
        $('#location-name-row').hide();
    }
}

// Listener para o select de tipo
$(document).on('change', '#location-type-select', function () {
    handleLocationTypeChange($(this).val());
});

// Listener para botão Nova Lotação
$(document).on('click', '#btn-nova-lotacao', function (e) {
    e.preventDefault();
    openAllocationModal(true);
});

// Listeners para botões de salvar
$(document).on('click', '#btn-save-allocation', function (e) {
    e.preventDefault();
    saveAllocation();
});

// Listener para o botão de edição
$(document).on('click', '.btn-edit-allocation', function (e) {
    e.preventDefault();
    console.log('Edit button clicked via class delegation', this);
    debugger;
    editAllocation(this);
});

// Listener para o botão de exclusão
$(document).on('click', '.btn-delete-allocation', function (e) {
    e.preventDefault();
    console.log('Delete button clicked via class delegation', this);
    // CButtonColumn gera href com o ID
    var id = $(this).attr('href');
    if (!id || id === '#') id = $(this).data('id');

    deleteAllocation(id);
});

function saveAllocation() {
    console.log('saveAllocation called');
    var data = {
        'ProfessionalAllocation': {
            'id': $('#ProfessionalAllocation_id').val(),
            'professional_fk': $('#ProfessionalAllocation_professional_fk').val(),
            'school_year': $('input[name="ProfessionalAllocation[school_year]"]').val(),
            'location_type': $('#location-type-select').val(),
            'school_inep_fk': $('#ProfessionalAllocation_school_inep_fk').val(),
            'location_name': $('#ProfessionalAllocation_location_name').val(),
            'role': $('#ProfessionalAllocation_role').val(),
            'contract_type': $('#ProfessionalAllocation_contract_type').val(),
            'workload': $('#ProfessionalAllocation_workload').val(),
            'status': $('#ProfessionalAllocation_status').val()
        }
    };

    if (typeof ProfessionalAllocationConfig === 'undefined') {
        console.error('ProfessionalAllocationConfig is undefined');
        alert('Erro de configuração JS.');
        return;
    }

    $.ajax({
        url: ProfessionalAllocationConfig.saveUrl,
        type: 'POST',
        data: data,
        success: function (response) {
            console.log('Save response:', response);
            if (response.success) {
                closeAllocationModal();
                $.fn.yiiGridView.update('professional-allocation-grid');
                clearAllocationForm();
                location.reload();
            } else {
                var errorMsg = '';
                if (response.errors) {
                    $.each(response.errors, function (k, v) { errorMsg += v + '\n'; });
                }
                alert('Erro ao salvar:\n' + errorMsg);
            }
        },
        error: function (xhr, status, error) {
            console.error('AJAX error:', status, error);
            alert('Erro na requisição.');
        }
    });
}

function deleteAllocation(id) {
    if (!confirm('Deseja realmente excluir esta lotação?')) return;

    if (typeof ProfessionalAllocationConfig === 'undefined') {
        console.error('ProfessionalAllocationConfig is undefined');
        alert('Erro de configuração JS.');
        return;
    }

    $.ajax({
        url: ProfessionalAllocationConfig.deleteUrl,
        type: 'POST',
        data: { id: id },
        success: function (response) {
            if (response.success) {
                $.fn.yiiGridView.update('professional-allocation-grid');
                location.reload();
            } else {
                alert('Erro ao excluir.');
            }
        },
        error: function () {
            alert('Erro na requisição.');
        }
    });
}

$(document).ready(function () {
    handleLocationTypeChange($('#location-type-select').val());
});
