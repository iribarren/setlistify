/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
import Vue from 'vue';
import Pager from './components/Pager.vue'

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

const $ = require('jquery');
global.$ = global.jQuery = $;

// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');
require("bootstrap/dist/css/bootstrap.css");


$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});

(function () {
    'use strict'
  
    feather.replace()
}())  

/**
* Create a fresh Vue Application instance
*/
new Vue({
  el: '#app',
  components: { Pager },
});