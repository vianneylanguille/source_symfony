$(document).ready(function(){









//  Scrolling down when clicking on the word ---------------------------------------
/*
$('a[href*=#]').each(function() {
	if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'')
	&& location.hostname == this.hostname
	&& this.hash.replace(/#/,'') ) { // remove the hash in front of the anchor part of the url, basically making sure that we are at an anchor
		var $targetId = $(this.hash), $targetAnchor = $('[name=' + this.hash.slice(1) +']');
		var $target = $targetId.length ? $targetId : $targetAnchor.length ? $targetAnchor : false;
		if ($target) {
			var targetOffset = $target.offset().top;
			$(this).click(function() {
					$("#nav li a").removeClass("active");
					$(this).addClass('active');
			$('html, body').animate({scrollTop: targetOffset}, 2000);
			return false;
			});
		}
	}
});
*/
// -------------------------------------------------------------------------------------------
//  ----------------------Animation for the onglet effect of the site ----------------
// -------------------------------------------------------------------------------------------


var $window = $(window);
var winHeight = $window.height();
var winWidth = $window.width();

var $eclore = $('#eclore');
var $reseau = $('#reseau');
var $proj = $('#projet');
var $cloud = $('#cloud');
var $container = $('#container');
var $footer = $('footer');

var $descrmod = $('div.descr.mod');
var $descrbleu = $('div.descr.bleu');
var $descrvert = $('.descr.vert');
var $descrorange = $('.descr.orange');

var $ongletbleu = $('a.onglet.bleu');
var $ongletvert = $('a.onglet.vert');
var $ongletorange = $('a.onglet.orange');
var $ongletorange = $('a.onglet.orange');

var $descriptions = $('.descriptions');
var $onglets = $('.onglet');

var isdeveloped = 0;



 // Animating the word cloud and the onglet-------------------------

 function colorOngletIn(col){
	 var descr = ".descr.mod";
	 ChangeCol($(descr),col)
}

function colorIn(n){
	var fore = $('.cloud_f')[n];
    $('#cloud_bkg').stop().animate({opacity: 0.2},{queue:false, duration:2000, easing: 'easeOutExpo'});
	$(fore).stop().fadeIn('2000');
};

 function colorOut(){
    $('#cloud_bkg').stop().animate({opacity: 1},{queue:false, duration:2000, easing: 'easeOutExpo'});
	$('.cloud_f').stop().fadeOut('2000')
};
 
   //When mouse rolls over
   $('#area_projet').mouseover(
		function(){colorOngletIn('bleu');colorIn(0);}
	); 
	$('#area_reseau').mouseover(
		function(){colorOngletIn('orange');colorIn(2);}
	); 
	$('#area_eclore').mouseover(
		function(){colorOngletIn('vert');colorIn(1);}
	); 
	$ongletbleu.mouseover(
		function(){colorOngletIn('bleu');colorIn(0);}
	); 
	$ongletorange.mouseover(
		function(){colorOngletIn('orange');colorIn(2);}
	); 
	$ongletvert.mouseover(
		function(){colorOngletIn('vert');colorIn(1);}
	); 
    //When mouse is removed
   $('#navarea a').mouseout(function(){
	colorOut() ;
    });
	$onglets.mouseout(function(){
	colorOut() ;
    });






 // Auxilliary Changing color of an element --------------------------------------------------------
 function ChangeCol($el,col) {
 $el.addClass(col).removeClass('bleu').removeClass('orange').removeClass('vert').addClass(col);
 }
 
// Auxilliary function swapping the onglet place --------------------------------------------------------
 
 function swaponglet(col) {
 var descri = ".descriptions."+ col;
 var ongl = ".onglet."+ col;
$(descri).insertBefore('.descriptions:nth-child(1)');
$(ongl).insertBefore('.onglet:nth-child(1)');
//alert(ongl);
}
// main function 

function devpanel(col) {
	var $element ; var descrel;
	if (col == 'bleu'){$element = $proj; $descrel = $descrbleu;};
	if (col == 'orange'){$element = $reseau; $descrel = $descrorange;};
	if (col == 'vert'){$element = $eclore; $descrel = $descrvert};
	$descrmod.css('display','none');
	$descrbleu.css('display','none');$descrvert.css('display','none');$descrorange.css('display','none');
	$descrel.css('display','inline-block');
	if (isdeveloped == 0) {
		$container.css('left','-'+ winWidth +'px');
		$element.css('display','inline-block');$cloud.css('display','none');$container.css('width','');
		$container.animate({left: 0},{queue:false, duration:3000, easing: 'easeOutExpo'});	
		// change footer color and launch specific animation
		ChangeCol($footer,col);ChangeCol($('.menu'),col);swaponglet(col);LauchCarouProj();LauchCarouActu();
		//change the height of the left line
		$descriptions.css('height',$element.css('height'));
		// mark that the onglet are developed
		isdeveloped = 1;
		}
	if (isdeveloped == 1) {
		$descrmod.css('display','none');
		$eclore.css('display','none');$proj.css('display','none');$reseau.css('display','none');
		$element.css('display','inline-block');
		ChangeCol($footer,col);ChangeCol($('.menu'),col);swaponglet(col);LauchCarouProj();LauchCarouActu();
		$descriptions.css('height',$element.css('height'));
	}	
};

//-------- Basic CSS change



$ongletbleu.click( function() {
	devpanel('bleu');
	});
$ongletvert.click( function() {
	devpanel('vert');
	});
$ongletorange.click( function() {
	devpanel('orange');
	});
$(area_projet).click( function() {
	devpanel('bleu');
	});
$(area_reseau).click( function() {
	devpanel('orange');
	});
$(area_eclore).click( function() {
	devpanel('vert');
	});




//  Animation for the parallax (scrolling effect) part of the site ---------------------------------------

/*
var $window = $(window);
var $proj = $('#projet');
var $banproj = $('#banner_projet');
var $wordproj = $('#word_projet');
var $reseau = $('#reseau');
var $banreseau = $('#banner_reseau');
var $wordreseau = $('#word_reseau');
var $eclore = $('#eclore');
var $baneclore = $('#banner_eclore');
var $wordeclore = $('#word_eclore');
var windowHeight = $window.height();
	

	
function newPos($el, windowHeight, scrollpos, vel, origin){
	var x = $el.css('backgroundPosition').slice(0,$el.css('backgroundPosition').indexOf(' ')).trim();
//	var baseunit = windowHeight;
//	alert((scrollpos - origin) * vel);
	return x  + ' ' + (  (scrollpos - origin) * (vel-1)  )  + "px"; // adjuster start
}
	
	
function Move(){
    var pos = $window.scrollTop();
//	alert(newPos($banproj, windowHeight, pos, 300, 200));
//   $banproj.css('backgroundPosition', '' + newPos($banproj, windowHeight, pos, -0.95, 58.8 ));
   $wordproj.css('backgroundPosition', '' + newPos($wordproj, windowHeight, pos, 0.2 , 1460));
//   $banreseau.css('backgroundPosition', '' + newPos($banreseau, windowHeight, pos, -0.95, 140+58.8  ));
   $wordreseau.css('backgroundPosition', '' + newPos($wordreseau, windowHeight, pos, 0.2 , 2625 ));
//   $baneclore.css('backgroundPosition', '' + newPos($baneclore, windowHeight, pos, -0.95, 280+58.8  ));
   $wordeclore.css('backgroundPosition', '' + newPos($wordeclore, windowHeight, pos, 0.2 , 3940 ));
}

$window.resize(function(){
   Move();
});

$window.bind('scroll', function(){
	Move();
});

$(function() {
    Move();
});

*/


function LauchCarouProj() {

$('#projcarousel').carouFredSel({

		auto    : {
	        items           : 3,
	        duration        : 1000,
	        easing          : "easeInOutCubic",
		},


        items  : 3,
		responsive  : true,
		
		prev : {
	        button      : "#carousel_prev",
	        key         : "left",
	        items       : 1,
	        easing      : "easeInOutCubic",
	        duration    : 750
	    },
	    next : {
	        button      : "#carousel_next",
	        key         : "right",
	        items       : 1,
	        easing      : "easeInOutCubic",
	        duration    : 1250
		}	
		
});

}



function LauchCarouActu() {

$("#foccarousel").carouFredSel({
	responsive	: true,
	scroll		: {
		fx			: "crossfade" ,
		duration : 1500,
	},
	items		: {
		visible		: 1,
		height		: 390,
	}
});

}

$('#tweet').tweecool({
        //settings
         username : 'ResEclore', 
         limit : 4  
      });
		






    // Using custom configuration
	

});