$(function () {
  $('[data-toggle="popover"]').popover({
    container: 'body',
    html: true,
  });
  new WOW().init();
});