$(document).ready(function() {
    checkFooterPosition();
    adjustMainHeight();
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

function adjustMainHeight() {
    const urlParams = new URLSearchParams(window.location.search);
    const currentPage = urlParams.get('page');

    let navHeight = $('nav').outerHeight();
    let footerHeight = $('footer').outerHeight();
    let viewportHeight = $(window).height();

    let mainHeight = viewportHeight - footerHeight - navHeight;

    if (currentPage !== "o") {
        $('#content').css('height', mainHeight + 'px');
    }

    if (currentPage == "r") {
        $('main').css('height', mainHeight + 'px');
    }
}