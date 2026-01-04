var vectorLayer = null;

var map = new ol.Map({
    target: 'map',
    layers: [
        new ol.layer.Tile({
        source: new ol.source.OSM()
        })
    ],
    view: new ol.View({
        center: ol.proj.fromLonLat([37.41, 8.82]),
        zoom: 4
    })
});


var stylesSelectedMap = [
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

function getOrgAddressToMap()
{
    $.ajax({
        url: '/listing/organization/address/map',
        type: 'POST',
        data: {
            organization: $("#organization_id").val(),
        },
        success: function(response) {
            var vectorSource = new ol.source.Vector({
                features: new ol.format.GeoJSON().readFeatures(response),
            });

            vectorLayer = new ol.layer.Vector({
                source: vectorSource,
                style: stylesSelectedMap
            });
                        
            map.addLayer(vectorLayer);

            $('#AddressAddModal').find("#close").trigger('click');
            $('.form-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
            
            var tab_id = $("#organization_tabs").find("li.active a").data("tabid");
            $(".tab-content").find("#" + tab_id).empty();
            $("#organization_tabs").find("li.active a").trigger('click');
        }         
    })
}