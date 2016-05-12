/**
 * Galleria Classic Theme 2012-08-08
 * http://galleria.io
 *
 * Licensed under the MIT license
 * https://raw.github.com/aino/galleria/master/LICENSE
 *
 */

(function($) {

/*global window, jQuery, Galleria */

Galleria.addTheme({
    name: 'classic',
    author: 'Galleria',
    defaults: {
        transition: 'slide',
        thumbCrop:  'height',
    },
    init: function(options) {

        Galleria.requires(1.4, 'This version of Classic theme requires Galleria 1.4 or later');

        // cache some stuff
        var touch = Galleria.TOUCH;

        // show loader with opacity
        this.$('loader').show().css('opacity', 0.4);

        // some stuff for non-touch browsers
        if (! touch ) {
            this.addIdleState( this.get('image-nav-left'), { left:-50 });
            this.addIdleState( this.get('image-nav-right'), { right:-50 });
        }

        // bind some stuff
        this.bind('thumbnail', function(e) {

            if (! touch ) {
                // fade thumbnails
                $(e.thumbTarget).css('opacity', 0.6).parent().hover(function() {
                    $(this).not('.active').children().stop().fadeTo(100, 1);
                }, function() {
                    $(this).not('.active').children().stop().fadeTo(400, 0.6);
                });

                if ( e.index === this.getIndex() ) {
                    $(e.thumbTarget).css('opacity',1);
                }
            } else {
                $(e.thumbTarget).css('opacity', this.getIndex() ? 1 : 0.6).bind('click:fast', function() {
                    $(this).css( 'opacity', 1 ).parent().siblings().children().css('opacity', 0.6);
                });
            }
        });

        var activate = function(e) {
            $(e.thumbTarget).css('opacity',1).parent().siblings().children().css('opacity', 0.6);
        };

        this.bind('loadstart', function(e) {
            if (!e.cached) {
                this.$('loader').show().fadeTo(200, 0.4);
            }
            window.setTimeout(function() {
                activate(e);
            }, touch ? 300 : 0);
        });

        this.bind('loadfinish', function(e) {
            this.$('loader').fadeOut(200);
        });
    }
});

}(jQuery));
