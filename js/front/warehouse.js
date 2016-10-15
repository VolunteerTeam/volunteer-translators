var map;
var markers = [];

function initialize(Lat,Lng,markers) {

    var mapOptions = {
        zoom: 3,
        minZoom: 3, 
        maxZoom: 18,
        center: new google.maps.LatLng(Lat, Lng),
        scrollwheel: true
    };
    map = new google.maps.Map(document.getElementById('google-map'), mapOptions);


    
var clusterStyles = [
  {
    textColor: 'black',
    url: 'http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/images/m5.png',
    height: 50,
    width: 50
  },
 {
    textColor: 'black',
    url: 'http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/images/m5.png',
    height: 50,
    width: 50
  },
 {
    textColor: 'black',
    url: 'http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/images/m5.png',
    height: 50,
    width: 50
  }
];
    var mcOptions = {gridSize: 1, styles: clusterStyles, maxZoom: 15};
    var mc = new MarkerClusterer(map, markers, mcOptions);

}

$(function(){

    $.get('/goroda/json',{},function(res){

        $.each(res.list,function(i,c){

 

        });

        initialize(57,60,markers);

    },'json');

});