window._ = require('lodash');
window.Swal = require('sweetalert2')

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

axios.defaults.headers.common['Access-Control-Request-Headers'] = 'Origin,X-Requested-With,Content-Type,Accept'
axios.defaults.headers.common['Content-Type'] = 'application/json; charset=utf-8'
/*
window.axios.defaults.withCredentials = true;
window.axios.defaults.headers.common['X-XSRF-TOKEN'] = 'eyJpdiI6IldxN28rRThRYXRSMk5oTkpkOGJOUWc9PSIsInZhbHVlIjoiWm55YWZWU3Izc1JYaVJQV3VrdjdaOFh0S2o5N3pqMkdoaWNUVnM2K2ViUXQ5NnVtY0VBSStMOFFxNFVWWnpEbkgzVDNEUDJqemJFN2ZPZFQ2dGczTjJyYmIzNmw3VkU5U3RRUUdGNmlZWTdxdExGU0RmZmZRWFZKZTFPTnNiUDIiLCJtYWMiOiI4N2MzMjE3ODQxZmQ1YmJiZDNiMGEzZGM0OTE2MjI1NGIyOWJkMmNiY2MzODkwMWFkNmFlY2JkMmJhNzM2ZDc5In0=';
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';*/
//window.axios.defaults.headers.common['Access-Control-Allow-Origin'] =  'htttp://192.168.0.14:8080';
/*
//Proxy
const expr = require('express');

window.express = expr();
window.express.use((req,res,next) => {
    res.header('Access-Control-Allow-Origin', '*');
    next();
});

window.listen(8080,() => console.log(`listening on 8080`));*/

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });