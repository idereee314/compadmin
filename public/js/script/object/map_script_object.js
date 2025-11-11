var imageExtent = [9628837.998896, 5055333.171471, 13488444.190902, 6870205.771979];
var currentZoomLevel = 6;
var objectSelectedLayer = null;
var vectorLayerSoum = null;

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

    getFeaturesFromGeoServerByLayer(map, coordinate, objectLayer, 50, false).then(features => {
        
        editObjectLocation(coordinate, features);
    })
}

function editObjectLocation(coordinate, features)
{
    if(features != null)
    {        
        $.get('/location/object/edit/info?object_id=' + features[0].get('id'), function( data ) {
            $('#objectEditModal').modal();
            $('#objectEditModal').on('shown.bs.modal', function(){
                $('#objectEditModal .modal-content').html(data);

                $(this).off('shown.bs.modal');
            });
            $('#objectEditModal').on('hidden.bs.modal', function(){
                $('#objectEditModal .modal-content').empty();
            });
        });
    }
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

function addLocationById(map, layerName, id, type) {
    $.get('/location/object/center/unit/' + id + '/' + type, function(data) {
        if (data == null) {
            alert('Хоосон байна');
        } else {
            addSelectedLocationToMap(map, layerName, data);
        }
    });
}