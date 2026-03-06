var map;
var currentInfoWindow = null;
var toogle = false;
var markers = [];

/**
 * Get from wikimapia the boundary by city's name, Latitude and Longitude.
 * And execute a callback when the json is done.
 *
 * @param city STRING
 * @param cityLat FLOAT
 * @param cityLng FLOAT
 * @param callback Function
 */
function getCityBoundary(city, cityLat, cityLng, callback) {
    let apiUrl = "http://api.wikimapia.org/";
    let apiFunction = "function=place.search";
    let apiKey = "key=19A0CB25-17334BD0-8C833A92-7DD95EAF-9851CC97-A9743644-3F4A6F2F-3B4821C1"
    let apiQuery = "q=" + city + "&lat=" + cityLat + "&lon=" + cityLng;
    let apiPack = "format=json&pack=&language=pt&data_blocks=main%2Cgeometry%2Clocation%2C&page=1&count=50&category=88";
    let apiUrlFull = apiUrl + "?" + apiFunction + "&" + apiKey + "&" + apiQuery + "&" + apiPack;

    let cityBoundary = [];
    return $.getJSON(apiUrlFull, function (json) {
        let cityPlace = null;
        $.each(json.places, function (i, place) {
            if (place.title.toUpperCase() == city.toUpperCase()) {
                cityPlace = place;
            }
        });
        if (cityPlace == null) cityPlace = json.places[0];
        $.each(cityPlace.polygon, function (i, data) {
            let lng = data.x;
            let lat = data.y;
            cityBoundary[i] = { lat: lat, lng: lng };
        });
        callback(cityBoundary);
    });
}

$("#map-canvas").click(function () {
    if (toogle)
        toogle = false;
    else if (!toogle && currentInfoWindow != null) {
        currentInfoWindow.close();
        currentInfoWindow = null;
    }
});




function initMap() {
    let cityAxis = { lat: -11.14603, lng: -37.6200178 };
    let cityBoundary;

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

    let mapType = new google.maps.StyledMapType([
        {
            featureType: 'landscape',
            stylers: [{ visibility: 'off' }]
        },
        {
            featureType: 'road',
            stylers: [{ visibility: 'on' }]
        },
        {
            elementType: 'labels',
            stylers: [{ visibility: 'on' }]
        },
    ]);

    map.mapTypes.set("myType", mapType);
    map.setMapTypeId("myType");

    getCityBoundary("boquim", cityAxis.lat, cityAxis.lng, function (result) {
        cityBoundary = result;

        let city = new google.maps.Polygon({
            path: cityBoundary,
            strokeColor: '#3F45EA',
            strokeOpacity: 0.8,
            strokeWeight: 3,
            fillColor: '#3F45EA',
            fillOpacity: 0.1,

        });

        city.setMap(map);
    });

    getCityBoundary("Santa Luzia do Itanhy", cityAxis.lat, cityAxis.lng, function (result) {
        cityBoundary = result;

        let city = new google.maps.Polygon({
            path: cityBoundary,
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 3,
            fillColor: '#FF0000',
            fillOpacity: 0.1,

        });

        city.setMap(map);
    });
    $.getJSON(URLGetMapInfos, { lat: cityAxis.lat, lng: cityAxis.lng }, function (json) {
        let markerImage = {
            url: $baseScriptUrl + "/common/img/oneSchool-32x42.png",
            scaledSize: new google.maps.Size(20, 26)
        };
        let clusterImage = {
            styles: [{
                url: $baseScriptUrl + "/common/img/manySchool-42x42.png",
                height: 42,
                width: 42,
                textSize: 1, //1
                textColor: "white", //"white"
            }],
            zoomOnClick: false
        };
        $.each(json, function (i, data) {
            let schoolAxis = new google.maps.LatLng(data.latitude, data.longitude);
            let marker = new google.maps.Marker({
                id: i,
                position: schoolAxis,
                map: map,
                title: data.name,
                icon: markerImage
            });

            let location = (data.location == 1) ? "Urbana" : "Rural";
            let situation = (data.situation == 1) ? "Ativa" : "Inativa";

            let markerContent =
                '<p><b>' + data.name + '</b></p>' +
                '<div>' +
                '<b>Código:</b> ' + data.inep_id + '<br>' +
                '<b>Nº de Matrículas:</b> ' + data.enrollmentCount + '<br>' +
                '<b>Nº de Turmas:</b> ' + data.classroomCount + '<br>' +
                '<b>Localização:</b> ' + location + '<br>' +
                '<b>Situação de Funcionamento:</b> ' + situation + '<br>' +
                '</div>';

            let markerInfo = new google.maps.InfoWindow({
                content: markerContent
            });
            marker.addListener('click', function () {
                markerInfo.open(map, marker);
                if (currentInfoWindow != null)
                    currentInfoWindow.close();

                if (currentInfoWindow == markerInfo)
                    currentInfoWindow = null;
                else {
                    toogle = true;
                    currentInfoWindow = markerInfo;
                }
            });
            markers.push(marker);
        });
        let markerCluster = new MarkerClusterer(map, markers, clusterImage);
        markerCluster.addListener("clusterclick", function (mCluster) {
            let clusterContent = "";
            let clusterMarkers = mCluster.getMarkers();
            for (var i = 0; i < clusterMarkers.length; i++) {
                let coordinates = clusterMarkers[i].position.toString().slice(1, -1);
                let lat = coordinates.split(", ")[0];
                let lng = coordinates.split(", ")[1];
                clusterContent += "<p><a style='color:#575655; cursor:pointer' onclick='moveTo(" + lat + "," + lng + "," + clusterMarkers[i].id + ")'><b>" + clusterMarkers[i].getTitle() + "</b></a></p>";
            }
            let clusterInfo = new google.maps.InfoWindow({
                content: clusterContent
            });
            clusterInfo.setPosition(mCluster.getCenter());
            clusterInfo.open(map);
            if (currentInfoWindow != null)
                currentInfoWindow.close();

            if (currentInfoWindow == clusterInfo)
                currentInfoWindow = null;
            else {
                toogle = true;
                currentInfoWindow = clusterInfo;
            }
        });
    });
}

function moveTo(lat, lng, markerID) {
    let center = new google.maps.LatLng(lat, lng);
    map.setZoom(17);
    map.panTo(center);
    google.maps.event.trigger(markers[markerID], 'click');
}
