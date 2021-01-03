require('./bootstrap');

import $ from 'jquery';

window.toggleTableRow = (rowId, actionId) => {
    const $row = $('#' + rowId);
    const $action = $row.find('#' + actionId);
    $row.children().toggle(0);
    if ($action.hasClass('d-none')) {
        $action.removeClass('d-none');
    } else {
        $action.addClass('d-none');
    }
};
