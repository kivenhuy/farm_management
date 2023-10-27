/*
  Add custom scripts here
*/
global.$ = global.jQuery = require('jquery');
require('popper.js');
require('bootstrap');
// require('highcharts');
require('parsleyjs');

$.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')} });
