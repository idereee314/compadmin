
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <title>Mongolian National Pro 2023</title>

        <style>
            body{
                margin: 0;
                padding: 0;
            }
            .main_container{
                width: 1050px;
                height: 1484px;
                background: #eee;
                margin: 0 auto;
                position: relative;
                padding: 0;
            }
            .container{
                width: 525px;
                height: 742px;
                background: #ddd;
                margin: 0 auto;
                float: left;
                position: relative;
                padding: 0;
                margin: 0;
                /* background: url('/assets/images/mnpro2023.jpg') no-repeat center center; */
                background: url('/assets/images/mandat_mnpro.png') no-repeat center center;
                background-size: contain;
                border-top: 2px solid #fff;
            }
            .content{
                position: relative;
                margin-top: 738px;
            }
            .image_container{
                position: absolute;
                width: 130px;
                height: 130px;
                background: #222;
                bottom: 375px;
                left: 50px;
                border-radius: 50%;
            }
            .image_container img{
                border-radius: 100%;
            }
            .org{
                position: absolute;
                width: 200px;
                height: 20px;
                bottom: 390px;
                left: 230px;
                border-radius: 8px;
                font-family: 'Times New Roman', Times;
                font-style: italic;
                font-weight: bold;
                font-size: 18px;
                
            }
            .name{
                position: absolute;
                width: 280px;
                height: 70px;
                bottom: 400px;
                left: 230px;
                /* right: 100px; */
                border-radius: 8px;
                font-family: 'Times New Roman', Times;
                /* font-style: italic; */
                font-weight: bold;
                font-size: 30px;
                line-height: 26px;
                color: #222;
                text-align: left;
            }
            .weight{
                position: absolute;
                width: 250px;
                height: 40px;
                bottom: 195px;
                left: 150px;
                border-radius: 8px;
                font-family: 'Times New Roman', Times;
                font-style: italic;
                font-weight: bold;
                font-size: 20px;
                color: #222;
                line-height: 20px;
                text-align: left;
            }
            .belt{
                position: absolute;
                width: 250px;
                height: 40px;
                bottom: 233px;
                left: 130px;
                border-radius: 8px;
                font-family: 'Times New Roman', Times;
                font-weight: bold;
                font-style: italic;
                font-size: 20px;
                color: #222;
                line-height: 20px;
                text-align: left;
            }
            .qrcode{
                position: absolute;
                width: 250px;
                height: 90px;
                bottom: 260px;
                right: 30px;
                border-radius: 8px;
                font-family: 'Times New Roman', Times;
                font-weight: bold;
                font-style: italic;
                font-size: 20px;
                color: #222;
                line-height: 20px;
                text-align: right;
            }
            .regid{
                position: absolute;
                width: 250px;
                height: 40px;
                bottom: 150px;
                right: 80px;
                border-radius: 8px;
                font-family: 'Times New Roman', Times;
                font-weight: bold;
                font-style: italic;
                font-size: 20px;
                color: #222;
                line-height: 20px;
                text-align: right;
            }
            .category{
                position: absolute;
                width: 250px;
                height: 40px;
                bottom: 273px;
                left: 130px;
                border-radius: 8px;
                font-family: 'Times New Roman', Times;
                font-style: italic;
                font-weight: bold;
                font-size: 20px;
                text-align: left;
                color: #222;
            }

            footer {page-break-after: always;}
        </style>
        
        
    </head>
    <body>
        @forelse(@$regs as $chunk)
        <div class="main_container">
            @foreach($chunk as $reg)
            <div class="container">
                <div class="content">
                    <div class="image_container">
                        <img src="{{ \Storage::disk('s3')->url($reg->member->profile_url) }}" width="100%" height="100%" />
                    </div>
                    <div class="org">
                        {{ @$reg->academy->is_other ? @$reg->academy_name : @$reg->academy->name }}
                    </div>
                    <div class="name">
                        {{ @$reg->member->lastname }} {{ @$reg->member->firstname }}
                    </div>
                    <div class="weight">
                        {{ @$reg->weight->weight }}кг
                    </div>
                    <div class="category">{{$reg->entry->name}}</div>
                    <div class="belt">
                        {{@$reg->belt->name}}
                    </div>
                    
                    <div class="qrcode">
                        <img src="data:image/png;base64,{{\DNS2D::getBarcodePNG(strval(@$reg->id), 'QRCODE')}}" width="150px" height="150px">
                    </div>
                    <div class="regid">
                        {{@$reg->id}}
                    </div>
                </div>
            </div>
            @endforeach                              
        </div>
        <div style="break-after:always; clear:both"></div> 
        @empty
        @endforelse
    </body>
</html>