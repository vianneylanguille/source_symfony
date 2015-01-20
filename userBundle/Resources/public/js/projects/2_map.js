var geocoder;
var map;
var objects;

var labels=[];
var associations=[];
var markers = [];
var infowindows = [];
var colors = ["808080", "336666", "357723"];
default_location=new google.maps.LatLng(48.817348,2.371005);
var previousOpen=-1;

function initialize() {
  var mapOptions = {
    zoom: 11,
    center: default_location,
	scaleControl: true
  }
  map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
}

//functions for displaying and removing markers
function addMarker(obj) {
    lat=obj.lat
    lng=obj.lng
    descr=obj.description+"<br><a href='"+voirProjetUrl+obj.id+"'>Plus de détails...</a>"
    titre =obj.project_name
    id = obj.id
    start_date = new Date(obj.start_date*1000);
    end_date = new Date(obj.end_date*1000);
    asso = obj.association.associationName
    var content = '<div style="min-height:100px;"><b>'+titre+' ('+asso+') du '+start_date.toLocaleDateString()+' au '+end_date.toLocaleDateString()+'</b><br>'+descr+'</div>'
    var infowindow = new google.maps.InfoWindow({content: content, maxWidth: 300});
    var marker = new google.maps.Marker({
      position: new google.maps.LatLng(lat,lng),
      map: map,
      icon: "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|"+colors[id % colors.length],
      title: titre});
    google.maps.event.addListener(marker, 'click', function() {infowindow.open(map,marker);});
    markers[id]=marker
    infowindows[id]=infowindow
}

function setAllMap(map) {
  for (var key in markers) {
    markers[key].setMap(map);
  }
}

function deleteMarkers() {
  setAllMap(null);
  markers = [];
  infowindows = [];
}

function codeAddress(address) {
  //returns [lat, lng] from adress
    return geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(new google.maps.LatLng(results[0].geometry.location.lat(),results[0].geometry.location.lng()));
            map.setZoom(10)

      } else {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });
  }
  
 function showMarkers(){
    deleteMarkers()  
    var filteredDivs = $("#example div.item "); 
    $.each(filteredDivs, function (index, div) {
            addMarker(objects[parseInt(div.attr('id'))])
        });
    setAllMap(map)
    }
   
function getDuration(obj){
return parseInt((obj.end_date - obj.start_date)/86400.0)
}

function sortJSON(data, key, way) {
    data.sort(function(a, b) {
        var x;
        var y;
        switch(key){
            case 'duration':
                x = getDuration(a);
                y = getDuration(b);
                break;
            case 'association':
                x = a.association.associationName;
                y = b.association.associationName;
                break;
            default:
                x = a[key];
                y = b[key];
        }
        if (way === '+' ) { return ((x < y) ? -1 : ((x > y) ? 1 : 0)); }
        if (way === '-') { return ((x > y) ? -1 : ((x < y) ? 1 : 0)); }
    });
    return data
}

function filterData(data){
if($("#label_select").val() != null)
    data = $.grep(data, function(proj, i){return $("#label_select").val().indexOf(proj.labels.id+"") > -1});
if($("#association_select").val() != null)
    data = $.grep(data, function(proj, i){return $("#association_select").val().indexOf(proj.association.id+"") > -1});
   return data
}

function sortData(data){
critere = $('#tri').val();
data = sortJSON(data, critere.substring(0, critere.length-1), critere.charAt(critere.length-1));
return data
}
   
function displayProjects(data){
    data = filterData(data)
    data = sortData(data)
    deleteMarkers()
    $("#results").empty()
    $.each(data, function(idx, obj){
        addMarker(obj)
        start_date = new Date(obj.start_date*1000)
        $("#results").append(
        
        
        '<ul class="item" id="'+obj.id+'"><li>'+
           '<div class="wrapper_el_projets">'+
                '<a href=""  class="expandlink" onclick="return false">'+
                '<div class="projet_el_liste_haut">'+
                    '<div class="hautgauche"> <img src='+webPath+'/'+obj.association.associationHeadshot+'></img></div> '+
                    '<div class="hautdroit"> <h2> <span>'+obj.project_name+'</span>  </h2>'+
                       '<span class="soustitre"> <span>'+getDuration(obj)+' jours ('+start_date.toLocaleDateString()+
                       ' - '+end_date.toLocaleDateString()+') </span> <span>'+obj.city+'</span></br>'+
                       '<span>'+obj.short_description+'</span>'+
                       '</span>'+
                    '</div>'+
               '</div>'+
               '<div class="projet_el_liste_bas">'+
                   ' <span> ' + obj.description +' </span>'+
               '</div>'+
               '</a>'+
           '</div></li></ul>'
)    
    });
    if(data.length==0)$("#results").append('Pas de résultats.');
    $("#results ul.item ").click( function() {
                var i = parseInt($(this).attr('id'))
                map.setCenter(markers[i].getPosition());
                if(previousOpen != -1){infowindows[previousOpen].close();}
                infowindows[i].open(map,markers[i]);
                previousOpen = i
            });  
}

function populate(id, table, id_select, option){
    if(table.indexOf(id) == -1){
                    $(id_select).append(option)
                    table.push(id)
                }
}

$(document).ready(function() {   

    google.maps.event.addDomListener(window, 'load', initialize);
    geocoder = new google.maps.Geocoder();
    var request = $.ajax({
          url: url,
          type: "POST",
          dataType: "json"
          });
   //chargement resultats
    request.done(function( data ) {
            objects = data
            $.each(data, function(idx, obj){
                //populate label selection tool
                populate(obj.labels.id, labels, "#label_select", "<option value="+obj.labels.id+">"+obj.labels.name+"</option>")
                populate(obj.association.id, associations, "#association_select", "<option value="+obj.association.id+">"+obj.association.associationName+"</option>")
            });
            displayProjects(data)
            
            //label selection tool
            $("#label_select").change(function(){displayProjects(data)});
            
            //association selection tool
            $("#association_select").change(function(){displayProjects(data)});
            
            //tri tool
            $("#tri").change(function(){displayProjects(data)});
            
                //centrer la carte sur ce projet et ouvrir l'infowindow
            
            
           //showMarkers()
            
            $("#loading").fadeOut()    
            $("#search_zone").fadeIn()    
            $("#label_select").chosen({width: "60%"} );
            $("#association_select").chosen({width: "55%"} );
            $("#tri").data("placeholder","Trier les projets...").chosen({width: "20%"} );
        });
        
        

    request.fail(function( jqXHR, textStatus ) {
      $('#results').html( "Request failed: " + textStatus+jqXHR.status );
      $("#loading").fadeOut()    
      $("#search_zone").fadeIn()  
    });
    
    $("#loc_submit").click(function(){
        if($("#loc").val() !=""){
            codeAddress($("#loc").val());
        }
    });
    
    $("#loc").keyup(function(event){
    if(event.keyCode == 13){
        $("#loc_submit").click();
    }
});
    
      $('.wrapper_el_projets').click(function(){
			$('.wrapper_el_projets').css({"border" : "", "border-radius": "", "box-shadow" : " 1px 1px 2px 2px #fff" });
			$(this).css({"border" : " 1px solid #ccc", "border-radius": " 5px", "box-shadow" : " 1px 1px 2px 2px #ccc" });
			$('.projet_el_liste_bas').css({"display" : "none"});
			$(this).children('.expandlink').children('.projet_el_liste_bas').css('display','block');
    });
      
    });
