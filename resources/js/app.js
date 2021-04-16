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

window.addRecipeToRow = () => {
    const medicine = $('#medicine');
    const medicineId = medicine.val();
    const medicineLabel = $('#medicine option:selected').text();

    const amount = $('#amount');
    const amountValue = amount.val();

    const unit = $('#unit');
    const unitId = unit.val();
    const unitLabel = $('#unit option:selected').text();

    const prescription = $('#prescription');
    const prescriptionValue = prescription.val();

    const ids = $('#medicines tbody tr input[name="medicines[]"]').map((_, e) => e.value).get();
    const exist = ids.find(id => +id === +medicineId || id === medicineId);

    if (!medicineId || !unitId || !amountValue || !prescriptionValue) {
        return;
    }

    medicine.val('');
    amount.val('');
    unit.val('');
    prescription.val('');

    if (exist) {
        return;
    }

    $('#medicines tbody').append(`
        <tr id="medicines-${medicineId}">
            <td class="d-none">
                <input name="amounts[]" type="hidden" value="${amountValue}">
                <input name="units[]" type="hidden" value="${unitId}">
                <input name="medicines[]" type="hidden" value="${medicineId}">
                <input name="prescriptions[]" type="hidden" value="${prescriptionValue}">
            </td>
            <td class="align-middle">${amountValue}</td>
            <td class="align-middle">${unitLabel}</td>
            <td class="align-middle">${medicineLabel}</td>
            <td class="align-middle">${prescriptionValue}</td>
            <td class="align-middle col-action">
                <div class="d-flex flex-row justify-content-end align-items-center">
                    <button type="button" class="btn btn-danger"
                            onclick="removeSelectToRow('medicines', 'medicines-${medicineId}')">
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

$('#qualifyModal').on('show.bs.modal', function (event) {
    const button = $(event.relatedTarget);
    const startTime = button.data('medical-record-id');

    const modal = $(this);
    modal.find('#medical_record_id').val(startTime);
})
