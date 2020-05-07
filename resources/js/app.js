require('./bootstrap');
require('chart.js');
require('datatables.net-bs4');

$(document).ready(() => {
    $("#countriestable").DataTable({
        columnDefs: [{
            targets: [0],
            orderable: false,
        }],
        stateSave: true
    });
});