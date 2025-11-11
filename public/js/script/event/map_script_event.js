var imageExtent = [9628837.998896, 5055333.171471, 13488444.190902, 6870205.771979];
var currentZoomLevel = 6;
var vectorLayerSoum = null;
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
        url: '/location/object/get/data',
        type: 'GET',
        data: {
            latitude: lat,
            longitude: lon
        },
        success: function(response) {
            if (response) {
                var layersToRemove = [];

                map.getLayers().forEach(function (layer) {
                    if (layer.get('name') != undefined && layer.get('name') === 'selectedLocation') {
                        layersToRemove.push(layer);
                    }
                });

                var len = layersToRemove.length;
                
                for(var i = 0; i < len; i++) {
                    map.removeLayer(layersToRemove[i]);
                }

                objectData = response[0];
                getObjectGeoLayer(response[1]);

                content.innerHTML = '<p>Объектын нэр:</p><code>' + objectData.object_name + '</code><p>Объектын давхар:</p><code>' + objectData.object_floor + '</code><p>тайлбар:</p><code>' + objectData.description + '</code>';
                overlay.setPosition(coordinate);

                if(objectData.object_name != "")
                {
                    $('#event-create-form input[name=object_locations]').tagsinput('add', {id: objectData.id, text: objectData.object_name});
                }
                else 
                {
                    alert('Обьектын нэр хоосон байна');
                }
            }
            else
            {
                if (vectorLayerSoum) {
                    var layersToRemove = [];

                    map.getLayers().forEach(function (layer) {
                        if (layer.get('name') != undefined && layer.get('name') === 'selected_building') {
                            layersToRemove.push(layer);
                        }
                    });

                    var len = layersToRemove.length;
                    
                    for(var i = 0; i < len; i++) {
                        map.removeLayer(layersToRemove[i]);
                    }
                    
                    $('#mappopup-closer-modal').trigger('click');
                }

                selectPoint(lat, lon);
                $('#event-create-form input[name=location_datas]').tagsinput('add', lat+';'+lon);
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
    var newZoomLevel = map.getView().getZoom();

    if (newZoomLevel != currentZoomLevel)
    {
        currentZoomLevel = newZoomLevel;
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

function addSelectedLocationToMap(map, layerName, geoJsonObject) {
    var formatSoum = new ol.format.GeoJSON();
    var vectorSourcesSoum = new ol.source.Vector();
    var features = formatSoum.readFeatures(geoJsonObject);

    for (var i = 0; i < features.length; i++) {
        features[i].getGeometry().transform('EPSG:4326', 'EPSG:3857');
        vectorSourcesSoum.addFeature(features[i]);
    }

    map.removeLayer(vectorLayerSoum);

    vectorLayerSoum = new ol.layer.Vector({
        source: vectorSourcesSoum,
        name: layerName,
        style: stylesSelectedLocation
            // style: styleFunction
    });
    vectorLayerSoum.setZIndex(2);

    map.addLayer(vectorLayerSoum);
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

    map.removeLayer(vectorLayerSoum);

    vectorLayerSoum = new ol.layer.Vector({
        source: vectorSourcesSoum,
        name: 'selected_building',
        style: stylesSelectedLocationBuilding
            // style: styleFunction
    });
    vectorLayerSoum.setZIndex(2);

    map.addLayer(vectorLayerSoum);
}

function fitToSoumLayer() {
    if (vectorLayerSoum != null) {
        map.getView().fit(vectorLayerSoum.getSource().getExtent(), map.getSize());
    }
}

function selectPoint(lat, lon) {
    var layersToRemove = [];

    map.getLayers().forEach(function (layer) {
        if (layer.get('name') != undefined && layer.get('name') === 'objectLayer') {
            layersToRemove.push(layer);
        }
    });

    var len = layersToRemove.length;
    
    for(var i = 0; i < len; i++) {
        map.removeLayer(layersToRemove[i]);
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

    vectorLayerPoint = new ol.layer.Vector({
        source: vectorSource,
        style: style,
        name: 'objectLayer'
    });

    map.addLayer(vectorLayerPoint);
}

function addLocationById(map, layerName, id, type) {
    $.get('/location/object/center/unit/' + id + '/' + type, function(data) {
        if (data == null) {
            alert('Хоосон байна');
        } else {
            addSelectedLocationToMap(map, layerName, data);
        }
    });
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

    vectorLayerPoint = new ol.layer.Vector({
        source: vectorSource,
        style: style
    });

    map.addLayer(vectorLayerPoint);
}
map.updateSize();