// Organization

var imageExtent = [9628837.998896, 5055333.171471, 13488444.190902, 6870205.771979];
var currentZoomLevel = 6;
var vectorLayerAddress = null;
var vectorLayerPoint = null;

var baselayer = new ol.layer.Tile({
        source: new ol.source.OSM()
});

var nationalGeographicLayer = new ol.layer.Tile({
    visible: false,
    source: new ol.source.OSM({
        url: 'https://tile.thunderforest.com/cycle/{z}/{x}/{y}.png?apikey=47add5c582e740ab82323953d839dc02'
    })
});
var bingArealLayer = new ol.layer.Tile({
    visible: false,
    preload: Infinity,
    source: new ol.source.BingMaps({
        imagerySet: 'AerialWithLabelsOnDemand',
        key: 'Av5l5QRLL5moqSyVgGSdxKHlVWtIlwkRmewfyTy7mHa_7MnFm3TUx2jIm7P0E3dO'
    })
});
var topographicLayer = new ol.layer.Tile({
    visible: false,
    source: new ol.source.XYZ({
        url: 'https://server.arcgisonline.com/ArcGIS/rest/services/' +
        'World_Topo_Map/MapServer/tile/{z}/{y}/{x}'
    })
});

var proj = new ol.proj.Projection({
    code: 'EPSG:4326',
    units: 'm'
});

var view = new ol.View({
        center: ol.extent.getCenter(imageExtent),
        zoom: currentZoomLevel
    });

/** Popup preparation starting */
var container = null;
if($('#mappopup').length)
{
    container = $('#mappopup')[0];
}

var content = null;
if($('#mappopup-content').length)
{
    content = $('#mappopup-content')[0];
}

var closer = null;
if($('#mappopup-closer').length)
{
    closer = $('#mappopup-closer')[0];
}

var overlay = null;
if(container != null)
{    
    /**
     * Create an overlay to anchor the popup to the map.
     */
    overlay = new ol.Overlay({
        element: container,
        autoPan: true,
        autoPanAnimation: {
            duration: 250
        }
    });

    /**
     * Add a click handler to hide the popup.
     * @return {boolean} Don't follow the href.
     */
    closer.onclick = function() {
        overlay.setPosition(undefined);
        closer.blur();
        return false;
    };
}

var stylesSelectedLocation = [
    new ol.style.Style({
        stroke: new ol.style.Stroke({
            color: 'red',
            width: 2
        }),
        fill: new ol.style.Fill({
            color: 'rgba(0, 0, 255, 0)'
        }),
        zIndex: 2
    })
];

var stylesSelectedLocationBuilding = [
    new ol.style.Style({
        stroke: new ol.style.Stroke({
            color: 'red',
            width: 2
        }),
        fill: new ol.style.Fill({
            color: '#50b7f2'
        }),
        zIndex: 3
    })
];

/** Popup preparation end */
    
var map = new ol.Map({ 
    target: 'mapid',
    //renderer: 'canvas', 
    projection: proj,
    //controls: ol.control.defaults().extend([ new ol.control.ScaleLine({ units:'metric' }) ]),
    layers: [baselayer],
    overlays: [overlay],
    view: view,
    controls: [
            new ol.control.ScaleLine(),
            new ol.control.FullScreen()
        ]
    });
map.addLayer(topographicLayer);
map.addLayer(nationalGeographicLayer);
map.addLayer(bingArealLayer);


map.on('moveend', mapMoveEndEvent);
map.on('click', mapClickEvent);

var select = new ol.interaction.Select();
//make sure you add select interaction to map
map.addInteraction(select);

function mapClickEvent(evt)
{
    var coordinate = evt.coordinate;
    var coords = ol.proj.toLonLat(coordinate);
    var lat = coords[1];
    var lon = coords[0];
    var locTxt = "Latitude: " + lat + " Longitude: " + lon;
    var parcel = null;
    // Bag Level

    $.ajax({
        url: '/object/location/get/object/data',
        type: 'GET',
        data: {
            latitude: lat,
            longitude: lon
        },
        success: function(response) {
            if (response) {
                objectData = response[0];

                if (objectData != undefined) {
                    $("#object_name").val(objectData.object_name);
                    $("#object_name").attr('data-objectid', objectData.id);
                }

                getObjectGeoLayer(response[1]);

                content.innerHTML = '<p>Объектын нэр:</p><code>' + objectData.object_name + '</code><p>Объектын давхар:</p><code>' + objectData.object_floor + '</code><p>тайлбар:</p><code>' + objectData.description + '</code>';
                overlay.setPosition(coordinate);
            }
        },
        error: function (xhr, textStatus, error) {
            console.log(xhr.statusText);
            console.log(textStatus);
            console.log(error);
        },
        async: false          
    });
    
    
    // getFeaturesFromGeoServerByLayer(map, coordinate, objectLayer, 50, false).then(features => {
    //     editObjectLocation(coordinate, features);
    // })
}

function editObjectLocation(coordinate, features)
{
    if(features != null)
    {
        console.log(features);
    }
}

function mapMoveEndEvent(evt)
{
    var newZoomLevel = map.getView().getZoom();

    if (newZoomLevel != currentZoomLevel)
    {
        currentZoomLevel = newZoomLevel;
    }
    /*
    if(currentZoomLevel <= 8 && currentZoomLevel >= 0)
    {
        // Aimag Level

        changeLayerVisible(map, 'aimagLayer', true, geoserver, addAimagLayerMethodName);
        changeLayerVisible(map, 'soumLayer', false, geoserver, addSoumLayerMethodName);
        changeLayerVisible(map, 'bagLayer', false, geoserver, addBagLayerMethodName);
    }
    if(currentZoomLevel >= 9 && currentZoomLevel <= 10)
    {
        // Soum Level

        changeLayerVisible(map, 'aimagLayer', false, geoserver, addAimagLayerMethodName);
        changeLayerVisible(map, 'soumLayer', true, geoserver, addSoumLayerMethodName);
        changeLayerVisible(map, 'bagLayer', false, geoserver, addBagLayerMethodName);
    }
    if(currentZoomLevel >= 11)
    {
        // Bag Level

        changeLayerVisible(map, 'aimagLayer', false, geoserver, addAimagLayerMethodName);
        changeLayerVisible(map, 'soumLayer', false, geoserver, addSoumLayerMethodName);
        changeLayerVisible(map, 'bagLayer', true, geoserver, addBagLayerMethodName);
    }*/
}

function addTooltipForPlanning(coordinate, features)
{
    if(features != null)
    {
        var popupContent = '';

        for(var i = 0; i < features.length; i ++)
        {
            popupContent += '<p>Код:'+features[i].get('code')+'<br>Зориулалт: '+features[i].get('description')+'<br>Газрын нэр: '+features[i].get('gazner')+'</p><hr>';
        }

        content.innerHTML = popupContent;
        overlay.setPosition(coordinate);
    }
}

var stylesSelectedSoum = [
    new ol.style.Style({
        stroke: new ol.style.Stroke({
            color: 'red',
            width: 2
        }),
        fill: new ol.style.Fill({
            color: 'rgba(0, 0, 255, 0)',
            opacity: 0
        })
    })
];

function addSelectedSoumLayerToMap(map, layerName, geoserver, geoJsonObject)
{
    if(currentSoumLayer != null)
    {
        map.removeLayer(currentSoumLayer);
    }

    var format= new ol.format.GeoJSON();
    var vectorSources = new ol.source.Vector();
    var features = format.readFeatures(geoJsonObject);
    
    for(var i = 0; i < features.length; i++){
        features[i].getGeometry().transform('EPSG:4326', 'EPSG:3857');
        vectorSources.addFeature( features[i] );
    }

    currentSoumLayer = new ol.layer.Vector({
        source: vectorSources,
        name: layerName,
        style: stylesSelectedSoum
        // style: styleFunction
    });

    currentSoumLayer.setZIndex(5);

    map.addLayer(currentSoumLayer);
    
    fitToSoumLayer();
}

// var defaultBagCode = '1670';

// changeLayerVisible(map, 'objectLayer', true, geoserver, addObjectLayerMethodName, [defaultBagCode]);


function fitToSoumLayer()
{
    if(currentSoumLayer != null)
    {
        map.getView().fit(currentSoumLayer.getSource().getExtent(), map.getSize());
    }
}

function addSelectedLocationToMap(map, layerName, geoJsonObject) {
    var formatSoum = new ol.format.GeoJSON();
    var vectorSourcesSoum = new ol.source.Vector();
    var features = formatSoum.readFeatures(geoJsonObject);

    for (var i = 0; i < features.length; i++) {
        features[i].getGeometry().transform('EPSG:4326', 'EPSG:3857');
        vectorSourcesSoum.addFeature(features[i]);
    }

    map.removeLayer(vectorLayerAddress);

    vectorLayerAddress = new ol.layer.Vector({
        source: vectorSourcesSoum,
        name: layerName,
        style: stylesSelectedLocation
            // style: styleFunction
    });
    vectorLayerAddress.setZIndex(2);

    map.addLayer(vectorLayerAddress);

    fitToSoumLayer();
}

function addLocationByCode(locationId) {
    
    map.getLayers().forEach(function(layer) {
        if (typeof layer !== 'undefined' && layer.get('name') != undefined & layer.get('name') === "organization_address") {
            map.removeLayer(layer);
        }
        
        if (typeof layer !== 'undefined' && layer.get('name') != undefined & layer.get('name') === "selectedLocation") {
            map.removeLayer(layer);
        }
    });

    addLocationById(map2, 'selected_location', locationId);
    
    // if(locationCode.length == 3){
    //     changeLayerVisible(map, 'selectedLocation', true, geoserver, addObjectByAimagCityMethodName, [locationId]);

    //     addLocationById(map, 'selected_location', locationCode);
    // }
    // else if(locationCode.length == 5)
    // {
    //     changeLayerVisible(map, 'selectedLocation', true, geoserver, addObjectBySoumDistrictMethodName, [locationId]);

    //     addLocationById(map, 'selected_location', locationCode);
    // }
    // else
    // {
    //     changeLayerVisible(map, 'selectedLocation', true, geoserver, addObjectLayerMethodName, [locationId]);

    //     addLocationById(map, 'selected_location', locationCode);
    // }
}

function addLocationById(map, layerName, id) {
    $.ajax({
        url: '/location/object/center/'+id,
        type: 'GET',
        success: function(response) {
            if (response != "") {
                addSelectedLocationToMap(map, layerName, response);
            }
        },
        error: function (xhr, textStatus, error) {
            console.log(xhr.statusText);
            console.log(textStatus);
            console.log(error);
        },
        async: false,
        processData: false,
        contentType: false        
    });
}

function centerOrganizationAddress(map, layerName, objectId) {
    $.ajax({
        url: '/location/object/center/'+objectId,
        type: 'GET',
        success: function(response) {
            if (response != "") {
                addSelectedLocationToMap(map, layerName, response);
            }
        },
        error: function (xhr, textStatus, error) {
            console.log(xhr.statusText);
            console.log(textStatus);
            console.log(error);
        },
        async: false,
        processData: false,
        contentType: false        
    });
}

function centerOrganizationPoin(map, layerName, pointGeom)
{
    $.get('/listing/organization/address/get/organization/address/by/point/' + pointGeom, function(data) {

        if (data == null) {
            alert('Хоосон байна');
        } else {
            drawPoint(data['lg'], data['lt']);
            
            fitToPointLayer();
        }
    });
}

function addSelectedPointToMap(map, layerName, geoJsonObject) {
    var formatSoum = new ol.format.GeoJSON();
    var vectorSourcesSoum = new ol.source.Vector();
    var features = formatSoum.readFeatures(geoJsonObject);

    for (var i = 0; i < features.length; i++) {
        features[i].getGeometry().transform('EPSG:4326', 'EPSG:3857');
        vectorSourcesSoum.addFeature(features[i]);
    }

    map.removeLayer(vectorLayerAddress);

    vectorLayerAddress = new ol.layer.Vector({
        source: vectorSourcesSoum,
        name: layerName,
        style: stylesSelectedLocation
            // style: styleFunction
    });
    vectorLayerAddress.setZIndex(2);

    map.addLayer(vectorLayerAddress);

    fitToSoumLayer();
}

function getObjectGeoLayer(objectGeoJSON)
{
    var formatSoum = new ol.format.GeoJSON();
    var vectorSourcesSoum = new ol.source.Vector();
    var features = formatSoum.readFeatures(objectGeoJSON);

    for (var i = 0; i < features.length; i++) {
        features[i].getGeometry().transform('EPSG:4326', 'EPSG:3857');
        vectorSourcesSoum.addFeature(features[i]);
    }

    map.removeLayer(vectorLayerAddress);

    vectorLayerAddress = new ol.layer.Vector({
        source: vectorSourcesSoum,
        name: 'selected_building',
        style: stylesSelectedLocationBuilding
            // style: styleFunction
    });
    vectorLayerAddress.setZIndex(2);

    map.addLayer(vectorLayerAddress);
}

function fitToSoumLayer() {
    if (vectorLayerAddress != null) {
        map.getView().fit(vectorLayerAddress.getSource().getExtent(), map.getSize());
    }
}

function fitToPointLayer()
{
    if (vectorLayerPoint != null) {
        map.getView().fit(vectorLayerPoint.getSource().getExtent(), map.getSize());
    }
}

function drawPoint(lon, lat) {
    // Geometries
    var point = new ol.geom.Point([parseFloat(lon), parseFloat(lat)]);
    point.transform('EPSG:4326', map.getView().getProjection())

    // Features
    var pointFeature = new ol.Feature(point);

    // Source and vector layer
    var vectorSource = new ol.source.Vector({
        projection: 'EPSG:4326',
        features: [pointFeature]
    });

    var style = new ol.style.Style({
        fill: new ol.style.Fill({
            color: '#0a66fa'
        }),
        stroke: new ol.style.Stroke({
            width: 2,
            color: '#0a66fa'
        }),
        image: new ol.style.Circle({
            fill: new ol.style.Fill({
                color: '#0a66fa'
            }),
            stroke: new ol.style.Stroke({
                width: 1,
                color: '#0a66fa'
            }),
            radius: 7
        }),
    });

    vectorLayerPoint = new ol.layer.Vector({
        source: vectorSource,
        style: style
    });

    map.addLayer(vectorLayerPoint);
}