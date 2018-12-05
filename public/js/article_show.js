$(document).ready(function() {
    var $like = $('.js-like-article');

    $('.js-like-article').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        console.log("Heart clicked!");

        var $link = $(e.currentTarget);
        $link.toggleClass('fa-heart-o').toggleClass('fa-heart');

        $.ajax({
            method: 'POST',
            url: $link.attr('href')
        }).done(function(data){
            $('.js-like-article-count').html(data.hearts);
        });
    });
});
