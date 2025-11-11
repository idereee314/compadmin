var format = 'image/png';

var addAimagLayerMethodName = "addAimagLayer";
var addSoumLayerMethodName = "addSoumLayer";
var addBagLayerMethodName = "addBagLayer";
var addObjectLayerMethodName = "addObjectLayer";
var addTrackingLayerMethodName = "addTrackingLayer";
var addObjectBySoumDistrictMethodName = "addObjectBySoumDistrict";
var addObjectByAimagCityMethodName = "addObjectByAimagCity";
var aimag = null;
var soum = null;
var bag = null;
var objectLayer = null;
var tracking = null;

function addAimagLayer(map, layerName, geoserver) {
    aimag = new ol.layer.Image({
        name: layerName,
        source: new ol.source.ImageWMS({
            ratio: 1,
            visible: false,
            url: geoserver + 'geoserver/geoserver/wms',
            params: {
                'FORMAT': format,
                'VERSION': '1.1.1',
                "STYLES": '',
                "LAYERS": 'geoserver:geo_admin_unit_level_one',
                "exceptions": 'application/vnd.ogc.se_inimage',
            }
        })
    });
    map.addLayer(aimag);
    console.log(layerName + ' Layer added');
}

function addSoumLayer(map, layerName, geoserver) {
    soum = new ol.layer.Image({
        name: layerName,
        source: new ol.source.ImageWMS({
            ratio: 1,
            visible: false,
            url: geoserver + 'geoserver/geoserver/wms',
            params: {
                'FORMAT': format,
                'VERSION': '1.1.1',
                "STYLES": '',
                "LAYERS": 'geoserver:geo_admin_unit_level_two',
                "exceptions": 'application/vnd.ogc.se_inimage',
            }
        })
    });
    map.addLayer(soum);
    console.log(layerName + ' Layer added');
}

function addBagLayer(map, layerName, geoserver) {
    bag = new ol.layer.Image({
        name: layerName,
        source: new ol.source.ImageWMS({
            ratio: 1,
            visible: false,
            url: geoserver + 'geoserver/geoserver/wms',
            params: {
                'FORMAT': format,
                'VERSION': '1.1.1',
                "STYLES": '',
                "LAYERS": 'geoserver:geo_admin_unit_level_three',
                "exceptions": 'application/vnd.ogc.se_inimage',
            }
        })
    });
    map.addLayer(bag);
    console.log(layerName + ' Layer added');
}

function addTrackingLayer(map, layerName, geoserver) {
    tracking = new ol.layer.Image({
        name: layerName,
        source: new ol.source.ImageWMS({
            ratio: 1,
            visible: false,
            url: geoserver + 'geoserver/routy/wms',
            params: {
                'FORMAT': format,
                'VERSION': '1.1.1',
                "STYLES": '',
                "LAYERS": 'routy:tracking_test',
                "exceptions": 'application/vnd.ogc.se_inimage',
            }
        })
    });
    map.addLayer(tracking);
    console.log(layerName + ' Layer added');
}

function addObjectLayer(map, layerName, geoserver, bagCode) {
    // console.log("bag horoo irlee", bagCode);
    
    map.removeLayer(objectLayer);

    objectLayer = new ol.layer.Tile({
        name: layerName,
        visible: true,
        title: "Обьект",
        source: new ol.source.TileWMS({
            url: geoserver + 'geoserver/uniq/wms',
            params: {
                'FORMAT': format,
                'VERSION': '1.1.1',
                tiled: true,
                "LAYERS": 'uniq:layer_listing_object_location',
                "exceptions": 'application/vnd.ogc.se_inimage',
                "viewparams": 'bag_code:' + bagCode
            },
            serverType: 'geoserver',
            crossOrigin: 'anonymous',
        })
    });

    map.addLayer(objectLayer);
    console.log(layerName + ' Layer added');
}

function addObjectBySoumDistrict(map, layerName, geoserver, soumCode) 
{
    objectLayer = new ol.layer.Tile({
        name: layerName,
        visible: true,
        title: "Обьект",
        source: new ol.source.TileWMS({
            url: geoserver + 'geoserver/routy/wms',
            params: {
                'FORMAT': format,
                'VERSION': '1.1.1',
                tiled: true,
                "LAYERS": 'routy:routy_listing_building_district',
                "exceptions": 'application/vnd.ogc.se_inimage',
                "viewparams": 'soum_code:' + soumCode
            },
            serverType: 'geoserver',
            crossOrigin: 'anonymous',
        })
    });

    map.addLayer(objectLayer);
    console.log(layerName + ' Layer added');
}

function addObjectByAimagCity(map, layerName, geoserver, soumCode) {
    objectLayer = new ol.layer.Tile({
        name: layerName,
        visible: true,
        title: "Обьект",
        source: new ol.source.TileWMS({
            url: geoserver + 'geoserver/routy/wms',
            params: {
                'FORMAT': format,
                'VERSION': '1.1.1',
                tiled: true,
                "LAYERS": 'routy:routy_listing_building_district',
                "exceptions": 'application/vnd.ogc.se_inimage',
                "viewparams": 'soum_code:' + soumCode
            },
            serverType: 'geoserver',
            crossOrigin: 'anonymous',
        })
    });

    map.addLayer(objectLayer);
    console.log(layerName + ' Layer added');
}

function changeLayerVisible(map, layerName, visible, geoserver, addLayerMethodName = null, addLayerMethodParams = [], isDelete = false) {
    console.log(addLayerMethodName);

    var isFound = false;
    map.getLayers().forEach(function(layer) {
        if (typeof layer !== 'undefined' && layer.get('name') != undefined & layer.get('name') === layerName) {
            if (isDelete) {
                map.removeLayer(layer);
                console.log(layerName + ' deleted');
            } else {
                layer.setVisible(visible);
                isFound = true;
            }
        }
    });

    if (!isFound && visible) {
        console.log(layerName + " Invoked");
        if (addLayerMethodName != "" && addLayerMethodName != null) {
            console.log(addLayerMethodName + " Started");
            var baseLayerMethod = window[addLayerMethodName];
            var methodParams = [map, layerName, geoserver];
            var methodParams = methodParams.concat(addLayerMethodParams);

            if (typeof baseLayerMethod === "function") {
                baseLayerMethod.apply(null, methodParams);

                console.log(addLayerMethodName + " Finished");
            }
        }
    }
}

function removeAllLayer(map, layerList = {}) {
    for (var key in layerList) {
        map.removeLayer(layerList[key]);
    }
}

function getLayerByName(map, layerName) {
    var selectedLayer = null;
    map.getLayers().forEach(function(layer) {
        if (typeof layer !== 'undefined' && layer.get('name') != undefined & layer.get('name') === layerName) {
            selectedLayer = layer;
        }
    });

    return selectedLayer;
}

async function getFeaturesFromGeoServerByLayer(map, coordinate, wmsLayer, featureCount, isArray = false) {
    var returnFeatures = null;

    if (wmsLayer != null) {
        var view = map.getView();
        var viewResolution = view.getResolution();
        if (isArray) {
            for (var key in wmsLayer) {
                var source = wmsLayer[key].getSource();
                var url = source.getGetFeatureInfoUrl(coordinate, viewResolution, view.getProjection(), { 'INFO_FORMAT': 'application/json', 'FEATURE_COUNT': featureCount });
                
                if(url) {
                    await $.get( url, function( data ) {
                        if(data != null && data.features != undefined  && data.features.length > 0)
                        {
                            var parser = new ol.format.GeoJSON();
                            var features = parser.readFeatures(data);
                            returnFeatures = features;
                        }
                    })
                }
            }

        } else {
            var source = wmsLayer.getSource();
            var url = source.getGetFeatureInfoUrl(coordinate, viewResolution, view.getProjection(), { 'INFO_FORMAT': 'application/json', 'FEATURE_COUNT': featureCount });
            
            if(url) {
                await $.get( url, function( data ) {
                    if(data != null && data.features != undefined  && data.features.length > 0)
                    {
                        var parser = new ol.format.GeoJSON();
                        var features = parser.readFeatures(data);
                        returnFeatures = features;
                    }
                })
            }    
        }
    }
    return returnFeatures;
}

function parseResponse(data) {
    //console.log("data-----"+data);
}

//Function to add replaceAll to Strings
String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};

function escapeCommasSemiColons(input) {
    if (input != "") {
        var output = input.replaceAll(",", "\\,"); //replace all the commas
        output = output.replaceAll(";", "\\;"); //replace all the SemiColons
        console.log(output);
    } else {
        output = '0';
    }
    return output;
}