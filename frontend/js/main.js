$(document).ready(function() {
    checkFooterPosition();
});

function checkFooterPosition() {
    var html = $('html');
    var body = $('body');
    var footer = $('footer');
    var isScrollable = html[0].scrollHeight > $(window).height();

    if (isScrollable) {
        body.addClass('scrollable-footer');

        $(window).on('scroll', function() {
            var scrolledToBottom = $(window).scrollTop() + $(window).height() >= $(document).height();

            if (scrolledToBottom) {
                footer.show();
                footer.addClass('visible');
            } else {
                footer.fadeOut(); 
                footer.removeClass('visible');
            }
        });
    } else {
        body.addClass('no-scroll');
        footer.fadeIn();
        footer.addClass('visible');
    }
}
