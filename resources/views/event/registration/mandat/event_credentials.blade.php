
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <title>UniQ Certificate</title>

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
                background: url('/assets/images/{{$eventConfig->sport_id}}_certificate.jpg') no-repeat center center;
                background-size: contain;
                border-top: 2px solid #fff;
            }
            .content{
                position: relative;
                margin-top: 738px;
            }
            .image_container{
                position: absolute;
                width: 120px;
                height: 120px;
                background: #222;
                bottom: 375px;
                left: 50px;
                border-radius: 50%;
            }
            .image_container img{
                border-radius: 50%;
            }
            .org{
                position: absolute;
                width: 200px;
                height: 20px;
                bottom: 390px;
                left: 230px;
                border-radius: 8px;
                font-family: 'Times New Roman', Times;
                font-style: normal;
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
                /* font-style: normal; */
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
                left: 40px;
                border-radius: 8px;
                font-family: 'Times New Roman', Times;
                font-style: normal;
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
                left: 40px;
                border-radius: 8px;
                font-family: 'Times New Roman', Times;
                font-weight: bold;
                font-style: normal;
                font-size: 20px;
                color: #222;
                line-height: 20px;
                text-align: left;
            }
            .gender{
                position: absolute;
                width: 250px;
                height: 20px;
                bottom: 180px;
                left: 40px;
                border-radius: 8px;
                font-family: 'Times New Roman', Times;
                font-weight: bold;
                font-style: normal;
                font-size: 20px;
                color: #222;
                line-height: 20px;
                text-align: left;
            }
            .birthdate{
                position: absolute;
                width: 250px;
                height: 20px;
                bottom: 320px;
                left: 40px;
                border-radius: 8px;
                font-family: 'Times New Roman', Times;
                font-weight: bold;
                font-style: normal;
                font-size: 20px;
                color: #222;
                line-height: 20px;
                text-align: left;
            }
            .eventName{
                position: absolute;
                width: 400px;
                height: 20px;
                bottom: 650px;
                left: 10px;
                border-radius: 8px;
                font-family: 'Times New Roman', Times;
                font-style: normal;
                font-weight: bold;
                font-size: 30px;
            }
            .qrcode{
                position: absolute;
                width: 250px;
                height: 40px;
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
                right: 60px;
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
                width: 280px;
                height: 40px;
                bottom: 273px;
                left: 40px;
                border-radius: 8px;
                font-family: 'Times New Roman', Times;
                font-style: normal;
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
                    <div class="eventName"> 
                        {{ @$eventConfig->event->name }}
                    </div>
                    <div class="org">
                        {{ @$reg->academy->is_other ? @$reg->academy_name : @$reg->academy->name }}
                    </div>
                    <div class="name">
                        {{ @$reg->member->lastname }} {{ @$reg->member->firstname }}
                    </div>
                    <div class="weight">
                        Жин : {{ @$reg->weight->weight }}кг
                    </div>
                    <div class="category">
                        Ангилал : {{$reg->entry->name}}
                    </div>
                    <div class="belt">
                        Бүс : {{@$reg->belt->name}}
                    </div>
                    <div class="gender">
                        Хүйс : {{ Config::get("enums.gender_code")[@$reg->member->gender_code] }}
                    </div>
                    <div class="birthdate">
                        Төрсөн огноо : {{@$reg->member->birth}}
                    </div>
                    
                    <div class="qrcode">
                        <img src="data:image/png;base64,{{\DNS2D::getBarcodePNG(strval(@$reg->id), 'QRCODE')}}" width="100px" height="100px">
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