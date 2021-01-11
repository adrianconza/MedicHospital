require('./bootstrap');

import $ from 'jquery';

window.toggleTableRow = (rowId, actionId) => {
    const row = $(`#${rowId}`);
    const action = row.find(`#${actionId}`);
    row.children().toggle(0);
    if (action.hasClass('d-none')) {
        action.removeClass('d-none');
    } else {
        action.addClass('d-none');
    }
};

window.addSelectToRow = (selectId, tableId, varName) => {
    const select = $(`#${selectId}`);
    const selectedId = select.val();
    const selectedLabel = $(`#${selectId} option:selected`).text();
    select.val('');
    const ids = $(`#${tableId} tbody tr input[name="${varName}[]"]`).map((_, e) => e.value).get();
    const exist = ids.find(id => +id === +selectedId || id === selectedId);
    if (exist || !selectedId) {
        return;
    }
    $(`#${tableId} tbody`).append(`
        <tr id="${tableId}-${selectedId}">
            <td class="d-none">
                <input name="${varName}[]" type="hidden" value="${selectedId}" >
            </td>
            <td class="align-middle">${selectedLabel}</td>
            <td class="align-middle col-action">
                <div class="d-flex flex-row justify-content-end align-items-center">
                    <button type="button" class="btn btn-danger"
                            onclick="removeSelectToRow('${tableId}', '${tableId}-${selectedId}')">
                        Eliminar
                    </button>
                </div>
            </td>
        </tr>
    `);
};

window.removeSelectToRow = (tableId, rowId) => {
    $(`#${tableId} tbody #${rowId}`).remove();
};

$('#scheduleModal').on('show.bs.modal', function (event) {
    const button = $(event.relatedTarget);
    const startTime = button.data('start-time');
    const endTime = button.data('end-time');
    const medicalSpecialityId = button.data('medical-speciality-id');
    const medicalSpeciality = button.data('medical-speciality');
    const doctorId = button.data('doctor-id');
    const doctor = button.data('doctor');

    const modal = $(this);
    modal.find('.modal-body #start_time').val(startTime);
    modal.find('.modal-body #start_time_show').val(startTime);
    modal.find('.modal-body #end_time').val(endTime);
    modal.find('.modal-body #end_time_show').val(endTime);
    modal.find('.modal-body #medical_speciality').val(medicalSpecialityId);
    modal.find('.modal-body #medical_speciality_show').val(medicalSpeciality);
    modal.find('.modal-body #doctor').val(doctorId);
    modal.find('.modal-body #doctor_show').val(doctor);
})
