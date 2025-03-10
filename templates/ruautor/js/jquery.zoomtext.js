 
	  $.fn.zoomtext = function (c) {
	    var a = {
	        min: 8, //минимальный размер шрифта  - 0 без ограничения
	        max: 32, //максимальный размер шрифта - 0 без ограничения
	        increment: "+=1", //приращение или размер например "24px"
	        recovery: !1, //вернуть исходный размер
	        skip: !1  //нераспространять параметры шрифта на этих потомков  - селектор потомков "*"
	    }, a = $.extend(a, c);
	    c = $("*", this).add(this);
	    c.each(function (a, c) {
	        var b = $(this).css("fontSize");
	        !$(this).data("fontSize") && $(this).data("fontSize", b).css("fontSize", b)
	    });
	    a.skip && (c = c.not($(a.skip, this)));
	    return c.each(function (c, d) {
	        var b = $(this).css("fontSize"),
	            b = $("<div/>", {
	                css: {
	                    fontSize: b
	                }
	            }).css("fontSize", a.increment).css("fontSize");
	        a.max && parseFloat(b) > a.max && (b = a.max);
	        a.min && parseFloat(b) < a.min && (b = a.min);
	        a.recovery && (b = $(this).data("fontSize"));
	        $(this).css({
	            fontSize: b
	        })
	    })
	};
	   $(function()
	     {
	       $('#increase').click(function(event)
	         {event.preventDefault();
	         $('#content').zoomtext({increment: "+=1"});
	         }
	       );
	 
	       $('#reset').click(function(event)
	         {event.preventDefault();
	         $('#content').zoomtext({recovery: true});
	         }
	       );
	 
	      $('#decrease').click(function(event)
	         {event.preventDefault();
	         $('#content').zoomtext({increment: "-=3"});
	         }
	       );
	      $('#font24').click(function(event)
	         {event.preventDefault();
	         $('#content').zoomtext({increment: "24px"});
	         }
	       );
	       $('#increase_мах').click(function(event)
	         {event.preventDefault()
	         $('#content').zoomtext({increment: "+=5", max : 0,skip : '*'});
	         }
	       );
	 
	 
	 
	     }
	   )
	  