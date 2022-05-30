jQuery(window).ready(function() {
    spinner.addClass('is-active');
    doSomething(function(response) {
        if ('number' === typeof response && 1 === response) {
            // Do something.
        }
    }, 'json');
});
