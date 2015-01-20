$(document).ready(function(){

//  Unfolding the menu ---------------------------------------

// We start by adding data to each node corresponding to the value they have to expand
var menu1 = $("div[class*='menu'] ul li")[0];
var menu2 = $("div[class*='menu'] ul li")[1];
var menu3 = $("div[class*='menu'] ul li")[2];
//var menu4 = $("div[class*='menu'] ul li")[3];

$.data(menu1, 'taillef', {l: 8.9});
$.data(menu2, 'taillef', {l: 8.9});
$.data(menu3, 'taillef', {l: 8.9});
//$.data(menu4, 'taillef', {l: 11.4});

//$.data(menu1,'taillef').l;

   //When mouse rolls over
   $("div[class*='menu'] ul li:lt(3)").mouseover(function(){
        $(this).stop().animate({height: $.data(this,'taillef').l +'em'},{queue:false, duration:600, easing: 'easeOutExpo'})
    });
    //When mouse is removed
    $("div[class*='menu'] ul li:lt(3)").mouseout(function(){
        $(this).stop().animate({height:'2.5em'},{queue:false, duration:600, easing: 'easeOutExpo'})
    });
 

$('<div class="hover">  </div>').appendTo($('div[class*="menu"] ul li h3~a'));
//$('<li>Deuxième élément bis</li>').insertAfter($('li'));
$('div[class*="menu"] ul li h3~a').hover( //Mouseover, fadeIn the hidden hover class 
 function(){ $(this).children('div').stop(true,true).fadeIn('2000');}, //Mouseout, fadeOut the hover class 
 function(){ $(this).children('div').stop(true,true).fadeOut('2000'); }
 ) ;
 
 });