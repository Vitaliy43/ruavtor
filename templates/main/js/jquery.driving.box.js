(function( $ ) {

    'use strict';

    $.fn.drivingBox = function( container, response ) {
        var box = $( this );
        var doc = $( document );
        var lastTop;
        var timer;
        
        response = response || 200;
        container = $( container );
        
        box.coords = box.offset();
        container.coords = container.offset();
        
        
        $( window ).resize( function() {
            var curTop = box.css( 'top' );
            
            box.css( 'top', 0 );
            
            box.coords = box.offset();
            container.coords = container.offset();
            
            box.css( 'top', curTop );
        });

        
        $( window ).scroll( function() {
            var maxTop = container.height() - box.outerHeight() - ( box.coords.top - container.coords.top );
            var scrollTop = doc.scrollTop();
            var top = 0;
            
            if ( box.coords.top < scrollTop ) {
                top = scrollTop - box.coords.top;
            }
            
            if ( top > maxTop ) {
                top = maxTop;
            }
            
            if ( lastTop === top ) {
                return;
            }
            
            lastTop = top;
            
            clearTimeout( timer );
            
            timer = setTimeout( function() {
                move( top );
            }, response );
            
        });
        
        
        function move( top ) {
            box.stop().animate({
                top: top
            });
        }
    
        return this;
    };

}( jQuery ));