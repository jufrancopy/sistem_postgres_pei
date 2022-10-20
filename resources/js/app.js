require('./bootstrap');
require('./modal');
require('./completed');


// global.$ = global.jQuery = require('jquery');
require('datatables.net')();
require('datatables.net-buttons/js/buttons.colVis.js')();
require('datatables.net-buttons/js/buttons.html5.js')();
require('datatables.net-buttons/js/buttons.print.js')();

window.Swal = require('sweetalert2')