<style>
/* Limit image width to avoid overflow the container */
img {
  max-width: 100%; /* This rule is very important, please do not ignore this! */
}

#canvas-edit {
  height: 100%;
  width: 100%;
  background-color: #ffffff;
  cursor: default;
  border: 1px solid black;
}
</style>
<form method="POST" id="picture-edit-form" class="form-horizontal smart-form" action="javascript:;">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="pull-left">
                <h3 class="panel-title">{{ trans('display.general_edit') }}</h3>
            </div>
            <div class="pull-right">
                <button class="btn btn-sm" data-action="collapse" data-toggle="tooltip" data-placement="top" data-title="Collapse"><i class="fa fa-angle-up"></i></button>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body no-padding">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4 col-sm-12 d-flex flex-column">
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6">
                                <label class="pull-right">{{trans('display.select_picture_type')}} <span class="asterisk">*</span></label>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <select class="form-control" name="picture_type" id="picture_type">
                                    <option value="" selected="">{{ trans('display.select_picture_type') }}</option>
                                    @foreach($pictureType as $type)
                                        <option value="{{ $type->id }}" data-width="{{$type->width}}" data-height="{{$type->height}}" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}" {{ $type->id == $eventPicture->picture_type_id ? 'selected' : '' }}>{{ $type->description }} {{ $type->width }}X{{ $type->height }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6">
                                <label class="pull-right">{{ trans('display.select_image') }} <span class="asterisk">*</span></label>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
                                    <span class="input-group-addon btn btn-success btn-file"><span class="fileinput-new">Select file</span><span class="fileinput-exists">Change</span>
                                    <input type="hidden"><input type="file" name="fileInput-edit" id="fileInput-edit" accept="image/*"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-15">
                            <span class="input-group-btn"><button type="button" class="btn btn-primary">X</button></span>
                            <input class="form-control no-border-left" id="x-axis" type="text">
                        </div>
                        <div class="input-group mb-15">
                            <span class="input-group-btn"><button type="button" class="btn btn-primary">Y</button></span>
                            <input class="form-control no-border-left" id="y-axis" type="text">
                        </div>
                        <div class="input-group mb-15">
                            <span class="input-group-btn"><button type="button" class="btn btn-primary">width</button></span>
                            <input class="form-control no-border-left" id="image-width" type="text">
                        </div>
                        <div class="input-group mb-15">
                            <span class="input-group-btn"><button type="button" class="btn btn-primary">height</button></span>
                            <input class="form-control no-border-left" id="image-height" type="text">
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-12">
                        <canvas id="canvas-edit">
                            Your browser does not support the HTML5 canvas element.
                        </canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" id="close" class="btn btn-default" data-dismiss="modal">{{ trans('display.general_close') }}</button>
        <button type="submit" class="btn btn-success">{{ trans('display.general_save') }}</button>
    </div>
</form>
<script>
var canvas  = $("#canvas-edit");
var context = canvas.get(0).getContext("2d");

function make_base()
{
    base_image = new Image();
    // base_image.src = "{{ asset('/storage/images/'.$eventPicture->url) }}";
    base_image.src = "{{ $imagePath }}";
    base_image.onload = function(){
        scaleToFit(this);
        context.drawImage(base_image, 0, 0);
    }
}

make_base();

$('#fileInput-edit').on( 'change', function(){
    if($("#picture_type").find('option:selected').val() != ""){
        context.clearRect(0, 0, canvas.width, canvas.height);
        canvas.cropper('destroy');
        
        if (this.files && this.files[0]) {
            if ( this.files[0].type.match(/^image\//) ) {
                var reader = new FileReader();
                reader.onload = function(evt) {
                    var img = new Image();

                    img.onload = function() {
                        if(this.width >= $("#picture_type").find('option:selected').data("width") && this.height >= $("#picture_type").find('option:selected').data("height")){
                            context.canvas.height = img.height;
                            context.canvas.width  = img.width;
                            context.drawImage(img, 0, 0);

                            var cropper = canvas.cropper({
                                crop: function(event){
                                    $("#x-axis").val(Math.round(event.detail.x));
                                    $("#y-axis").val(Math.round(event.detail.y));

                                    $("#image-width").val(Math.round(event.detail.width));
                                    $("#image-height").val(Math.round(event.detail.height));
                                },
                                data: {
                                    width: $("#picture_type").find('option:selected').data("width"), // ditto
                                    height: $("#picture_type").find('option:selected').data("height"), // value is irrelevant here
                                    x: 0, y: 0, scaleX: 1, scaleY: 1
                                }
                            });
                                                        
                            $("#picture_type").on('change', function(){
                                var cropper = canvas.cropper({
                                    crop: function(event){
                                        $("#x-axis").val(Math.round(event.detail.x));
                                        $("#y-axis").val(Math.round(event.detail.y));

                                        $("#image-width").val(Math.round(event.detail.width));
                                        $("#image-height").val(Math.round(event.detail.height));
                                    },
                                    data: {
                                        width: $(this).find('option:selected').data("width"), // ditto
                                        height: $(this).find('option:selected').data("height"), // value is irrelevant here
                                    }
                                });
                            });
                        }
                        else
                        {
                            alert("Оруулсан зурагны хэмжээ төрлийн хэмжээнээс их байх ёстой");

                            $(".fileinput-filename").text('');
                            $('#fileInput').val('');
                        }
                    };
                    
                    img.src = evt.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
            else {
                alert("Invalid file type! Please select an image file.");
            }
        }
        else {
            alert('No file(s) selected.');
        }
    }
    else
    {
        alert("Зурагны төрлөө сонгоно уу");

        $('#fileInput').val(''); 
    }
    
});

function scaleToFit(img){
    // get the scale
    var scale = Math.min(canvas.width / img.width, canvas.height / img.height);
    // get the top left position of the image
    var x = (canvas.width / 2) - (img.width / 2) * scale;
    var y = (canvas.height / 2) - (img.height / 2) * scale;
    context.drawImage(img, x, y, img.width * scale, img.height * scale);
}
</script>