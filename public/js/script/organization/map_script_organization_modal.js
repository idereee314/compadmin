// Modal

var imageExtent = [9628837.998896, 5055333.171471, 13488444.190902, 6870205.771979];
var currentZoomLevel = 6;
var vectorLayerSelected = null;
var locationLayer = null;
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
if($('#mappopup_modal').length)
{
    container = $('#mappopup_modal')[0];
}

var content = null;
if($('#mappopup-content-modal').length)
{
    content = $('#mappopup-content-modal')[0];
}

var closer = null;
if($('#mappopup-closer-modal').length)
{
    closer = $('#mappopup-closer-modal')[0];
}

var overlay = null;
if(container != null)
{    
    /**
     * Create an overlay to anchor the popup to the map2.
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

var stylesSelectedLocationModal = [
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
    
var map2 = new ol.Map({ 
    target: 'mapid_modal',
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
map2.addLayer(topographicLayer);
map2.addLayer(nationalGeographicLayer);
map2.addLayer(bingArealLayer);


map2.on('moveend', mapMoveEndEvent);
map2.on('click', mapClickEvent);

var select = new ol.interaction.Select();
//make sure you add select interaction to map2
map2.addInteraction(select);

function mapClickEvent(evt)
{
    var coordinate = evt.coordinate;
    var coords = ol.proj.toLonLat(coordinate);
    var lat = coords[1];
    var lon = coords[0];
    var locTxt = "Latitude: " + lat + " Longitude: " + lon;
    // Bag Level

    $.ajax({
        url: '/location/object/get/data',
        type: 'GET',
        data: {
            latitude: lat,
            longitude: lon
        },
        success: function(response) {
            if (response) {
                var layersToRemove = [];

                map2.getLayers().forEach(function (layer) {
                    if (layer.get('name') != undefined && layer.get('name') === 'organization_address') {
                        layersToRemove.push(layer);
                    }
                });

                var len = layersToRemove.length;
                
                for(var i = 0; i < len; i++) {
                    map2.removeLayer(layersToRemove[i]);
                }

                objectData = response[0];

                if (objectData != undefined && objectData != '') {
                    $("#location_data").val("");
                    $("#entrance_id").empty();

                    $("#object_id").val(objectData.id);
                    $("#object_name").val(objectData.object_name);

                    $.each(objectData.entrances, function (i, item) {
                        $("#entrance_id").append($('<option>', { 
                            value: item.entrance_id,
                            text : item.name,
                        }));
                    });
                    $("#entrance_id").val($("#entrance_id option:first").val());
                }

                getObjectGeoLayer(response[1]);

                content.innerHTML = '<p>Объектын нэр:</p><code>' + objectData.object_name + '</code><p>Объектын давхар:</p><code>' + objectData.object_floor + '</code><p>тайлбар:</p><code>' + objectData.description + '</code>';
                overlay.setPosition(coordinate);
            }
            else
            {
                if (vectorLayerSelected) {
                    var layersToRemove = [];

                    map2.getLayers().forEach(function (layer) {
                        if (layer.get('name') != undefined && layer.get('name') === 'selected_building') {
                            layersToRemove.push(layer);
                        }
                    });

                    var len = layersToRemove.length;
                    
                    for(var i = 0; i < len; i++) {
                        map2.removeLayer(layersToRemove[i]);
                    }

                    $('#mappopup-closer-modal').trigger('click');
                }

                selectPoint(lat, lon);

                $("#object_id").val("");
                $("#object_name").val("");
                $("#location_data").val(lat+','+lon);
            }
        },
        error: function (xhr, textStatus, error) {
            console.log(xhr.statusText);
            console.log(textStatus);
            console.log(error);
        },
        async: false          
    });
}

function mapMoveEndEvent(evt)
{
    var newZoomLevel = map2.getView().getZoom();

    if (newZoomLevel != currentZoomLevel)
    {
        currentZoomLevel = newZoomLevel;
    }
    /*
    if(currentZoomLevel <= 8 && currentZoomLevel >= 0)
    {
        // Aimag Level

        changeLayerVisible(map2, 'aimagLayer', true, geoserver, addAimagLayerMethodName);
        changeLayerVisible(map2, 'soumLayer', false, geoserver, addSoumLayerMethodName);
        changeLayerVisible(map2, 'bagLayer', false, geoserver, addBagLayerMethodName);
    }
    if(currentZoomLevel >= 9 && currentZoomLevel <= 10)
    {
        // Soum Level

        changeLayerVisible(map2, 'aimagLayer', false, geoserver, addAimagLayerMethodName);
        changeLayerVisible(map2, 'soumLayer', true, geoserver, addSoumLayerMethodName);
        changeLayerVisible(map2, 'bagLayer', false, geoserver, addBagLayerMethodName);
    }
    if(currentZoomLevel >= 11)
    {
        // Bag Level

        changeLayerVisible(map2, 'aimagLayer', false, geoserver, addAimagLayerMethodName);
        changeLayerVisible(map2, 'soumLayer', false, geoserver, addSoumLayerMethodName);
        changeLayerVisible(map2, 'bagLayer', true, geoserver, addBagLayerMethodName);
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

function addSelectedSoumLayerToMap(map2, layerName, geoserver, geoJsonObject)
{
    if(currentSoumLayer != null)
    {
        map2.removeLayer(currentSoumLayer);
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
    map2.addLayer(currentSoumLayer);
    
    fitToSelectedLayerModal();
}

function addSelectedLocationToMapModal(map2, layerName, geoJsonObject) {
    var formatSoum = new ol.format.GeoJSON();
    var vectorSourcesModal = new ol.source.Vector();
    var features = formatSoum.readFeatures(geoJsonObject);

    for (var i = 0; i < features.length; i++) {
        features[i].getGeometry().transform('EPSG:4326', 'EPSG:3857');
        vectorSourcesModal.addFeature(features[i]);
    }

    map2.removeLayer(vectorLayerSelected);

    vectorLayerSelected = new ol.layer.Vector({
        source: vectorSourcesModal,
        name: layerName,
        style: stylesSelectedLocationModal
            // style: styleFunction
    });

    vectorLayerSelected.setZIndex(2);

    map2.addLayer(vectorLayerSelected);

    fitToSelectedLayerModal();
}

function locationToMapModal(map2, layerName, geoJsonObject) {
    var formatSoum = new ol.format.GeoJSON();
    var vectorSourcesModal = new ol.source.Vector();
    var features = formatSoum.readFeatures(geoJsonObject);

    for (var i = 0; i < features.length; i++) {
        features[i].getGeometry().transform('EPSG:4326', 'EPSG:3857');
        vectorSourcesModal.addFeature(features[i]);
    }

    map2.removeLayer(locationLayer);

    locationLayer = new ol.layer.Vector({
        source: vectorSourcesModal,
        name: layerName,
        style: stylesSelectedLocationModal
            // style: styleFunction
    });

    locationLayer.setZIndex(2);

    map2.addLayer(locationLayer);

    fitToExistingLayerModal();
}

function addLocationByIdModal(locationId, locationType) {
    map2.getLayers().forEach(function(layer) {
        if (typeof layer !== 'undefined' && layer.get('name') != undefined & layer.get('name') === "organization_address") {
            map2.removeLayer(layer);
        }
        
        if (typeof layer !== 'undefined' && layer.get('name') != undefined & layer.get('name') === "selectedLocation") {
            map2.removeLayer(layer);
        }
    });

    addLocationById(map2, 'selected_location', locationId);
}

function addLocationById(map2, layerName, id, type) {
    $.get('/location/object/center/unit/' + id + '/' + type, function(data) {
        if (data == null) {
            alert('Хоосон байна');
        } else {
            addSelectedLocationToMapModal(map2, layerName, data);
        }
    });
}

function centerOrganizationAddressModal(map2, layerName, objectId) {
    $.ajax({
        url: '/location/object/center/'+objectId,
        type: 'GET',
        success: function(response) {
            if (response != "") {
                addSelectedLocationToMapModal(map2, layerName, response);
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

function centerOrganizationPoin(pointGeom)
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

function getObjectGeoLayer(objectGeoJSON)
{
    var formatSoum = new ol.format.GeoJSON();
    var vectorSourcesModal = new ol.source.Vector();
    var features = formatSoum.readFeatures(objectGeoJSON);

    for (var i = 0; i < features.length; i++) {
        features[i].getGeometry().transform('EPSG:4326', 'EPSG:3857');
        vectorSourcesModal.addFeature(features[i]);
    }

    map2.removeLayer(vectorLayerSelected);

    vectorLayerSelected = new ol.layer.Vector({
        source: vectorSourcesModal,
        name: 'selected_building',
        style: stylesSelectedLocationBuilding
    });
    vectorLayerSelected.setZIndex(2);

    map2.addLayer(vectorLayerSelected);
}

function fitToSelectedLayerModal() {
    if (vectorLayerSelected != null) {
        map2.getView().fit(vectorLayerSelected.getSource().getExtent(), map2.getSize());
    }
}

function fitToExistingLayerModal() {
    if (locationLayer != null) {
        map2.getView().fit(locationLayer.getSource().getExtent(), map2.getSize());
    }
}

function drawPoint(lon, lat) {

    // Geometries
    var point = new ol.geom.Point([parseFloat(lon), parseFloat(lat)]);
    point.transform('EPSG:4326', map2.getView().getProjection())

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
        style: style,
        name: "organization_point"
    });

    var layersToRemove = [];

    map2.getLayers().forEach(function (layer) {
        if (layer.get('name') != undefined && layer.get('name') === 'organization_address') {
            layersToRemove.push(layer);
        }
    });

    var len = layersToRemove.length;
    
    for(var i = 0; i < len; i++) {
        map2.removeLayer(layersToRemove[i]);
    }

    map2.addLayer(vectorLayerPoint);
}

function fitToPointLayer()
{
    if (vectorLayerPoint != null) {
        map2.getView().fit(vectorLayerPoint.getSource().getExtent(), map2.getSize());
    }
}

function selectPoint(lat, lon) {
    var layersToRemove = [];

    map2.getLayers().forEach(function (layer) {
        if (layer.get('name') != undefined && layer.get('name') === 'organization_address') {
            layersToRemove.push(layer);
        }
    });

    var len = layersToRemove.length;
    
    for(var i = 0; i < len; i++) {
        map2.removeLayer(layersToRemove[i]);
    }

    // Geometries
    var point = new ol.geom.Point(
        ol.proj.transform([lon, lat], 'EPSG:4326', 'EPSG:3857')
    );
    
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
        style: style,
        name: 'organization_address'
    });

    map2.addLayer(vectorLayerPoint);
}