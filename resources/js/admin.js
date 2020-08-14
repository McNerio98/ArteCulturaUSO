
try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('admin-lte');
} catch (e) {}


window.axios = require('axios');