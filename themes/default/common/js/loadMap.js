var map;
var currentInfoWindow = null;

/**
 * Get from wikimapia the boundary by city's name, Latitude and Longitude.
 * And execute a callback when the json is done.
 *
 * @param city STRING
 * @param cityLat FLOAT
 * @param cityLng FLOAT
 * @param callback Function
 */
function getCityBoundary(city, cityLat, cityLng, callback){
    var apiUrl = "http://api.wikimapia.org/";
    var apiFunction = "function=place.search";
    var apiKey = "key=19A0CB25-17334BD0-8C833A92-7DD95EAF-9851CC97-A9743644-3F4A6F2F-3B4821C1"
    var apiQuery = "q="+city+"&lat="+cityLat+"&lon="+cityLng;
    var apiPack = "format=json&pack=&language=pt&data_blocks=main%2Cgeometry%2Clocation%2C&page=1&count=50&category=88";
    var apiUrlFull = apiUrl+"?"+apiFunction+"&"+apiKey+"&"+apiQuery+"&"+apiPack;

    var cityBoundary = [];
    return $.getJSON(apiUrlFull, function(json){
        $.each(json.places[0].polygon, function(i, data){
            var lng = data.x;
            var lat = data.y;
            cityBoundary[i] = {lat: lat, lng: lng};
        });
        callback(cityBoundary);
    });
}


function initMap() {
    var cityAxis = {lat:-11.14603,lng:-37.6200178};
    var cityBoundary;

    map = new google.maps.Map($('#map-canvas')[0], {
        center: cityAxis,
        disableDefaultUI: false,
        draggable: true,
        scrollwheel: true,
        mapTypeControl: false,
        mapTypeControlOptions: {
            mapTypeIds: ["myType"]
        },
        zoom: 11,
    });

    var mapType = new google.maps.StyledMapType([
        {
            featureType: 'landscape',
            stylers: [{visibility:'off'}]
        },
        {
            featureType: 'road',
            stylers: [{visibility:'on'}]
        },
        {
            elementType: 'labels',
            stylers: [{visibility:'on'}]
        },
    ]);

    map.mapTypes.set("myType", mapType);
    map.setMapTypeId("myType");

    getCityBoundary("Boquim", cityAxis.lat, cityAxis.lng, function(result){
        cityBoundary = result;

        var city = new google.maps.Polygon({
            path: cityBoundary,
            strokeColor: '#496cad',
            strokeOpacity: 0.8,
            strokeWeight: 3,
            fillColor: '#496cad',
            fillOpacity: 0.1,

        });

        city.setMap(map);
    });

    $.getJSON(URLGetMapInfos, {lat: cityAxis.lat, lng: cityAxis.lng}, function(json){
        var image = {
            url: "/themes/default/common/img/oneSchool-32x42.png",
            scaledSize: new google.maps.Size(20, 26),
        };
        $.each(json, function(i, data){
            var schoolAxis = new google.maps.LatLng(data.latitude, data.longitude);
            var marker = new google.maps.Marker({
                position: schoolAxis,
                map: map,
                title: data.name,
                icon: image
            });

            var location = (data.location == 1) ? "Urbana" : "Rural";
            var situation = (data.situation == 1) ? "Ativa" : "Inativa";

            var content =
                '<p><b>'+data.name+'</b></p>'+
                '<div>'+
                    '<b>Código:</b> '+ data.inep_id +'<br>'+
                    '<b>Nº de Matrículas:</b> '+ data.enrollmentCount +'<br>'+
                    '<b>Nº de Turmas:</b> '+ data.classroomCount +'<br>'+
                    '<b>Localização:</b> '+ location +'<br>'+
                    '<b>Situação de Funcionamento:</b> '+ situation +'<br>'+
                '</div>';

            var info = new google.maps.InfoWindow({
                content: content
            });
            marker.addListener('click', function(){
                if(currentInfoWindow != null)
                    currentInfoWindow.close();
                currentInfoWindow = info;
                info.open(map, marker);
            });
        });
    });
}
