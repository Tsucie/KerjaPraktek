const appUrl = $('meta[name="route"]').attr('content');

$(document).ready(function () {
  $('.nav-item').removeClass('active');
  $('a[href="#product-section"]').parent().addClass('active');
});