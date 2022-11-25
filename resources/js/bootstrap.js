window._ = require('lodash');

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');
    require('../../public/material/js/core/bootstrap-material-design.min.js');
    require('../../public/material/js/plugins/perfect-scrollbar.jquery.min.js');
    require('../../public/material/js/material-dashboard.js');
    require('../../public/material/demo/demo');
    require('../../public/material/js/settings.js');
    require('../../public/material/js/plugins/jquery.validate.min.js');
    require('../../public/material/js/plugins/jquery.bootstrap-wizard.js');
    require('../../public/material/js/plugins/bootstrap-selectpicker.js');
    require('../../public/material/js/plugins/bootstrap-datetimepicker.min.js');
    require('../../public/material/js/plugins/bootstrap-tagsinput.js');
    require('../../public/material/js/plugins/jasny-bootstrap.min.js');
    require('../../public/material/js/plugins/fullcalendar.min.js');
    require('../../public/material/js/plugins/jquery-jvectormap.js');
    require('bootstrap');
    toastr = require('toastr');
    toastr.options = {
        "progressBar": true,
    };

} catch (e) {}
import "datatables.net";
import "datatables.net-bs5";
import "datatables.net-responsive-bs5";
import "datatables.net-responsive";
import "core-js";
import "arrive";
import "chartist";
import "bootstrap-notify";
import "moment";
import "sweetalert2";
import "select2";

// toastr.info('Hola mundo')
window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });