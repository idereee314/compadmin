
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <title>Condition cup 2022</title>

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
                background: url('/assets/images/bg_mnue_open.jpg') no-repeat center center;
                background-size: contain;
                border-top: 2px solid #fff;
            }
            .content{
                position: relative;
                margin-top: 738px;
            }
            .image_container{
                position: absolute;
                width: 161px;
                height: 218px;
                background: #222;
                bottom: 72px;
                left: 35px;
                border-radius: 8px;
            }
            .image_container img{
                border-radius: 8px;
            }
            .org{
                position: absolute;
                width: 250px;
                height: 20px;
                bottom: 216px;
                left: 221px;
                border-radius: 8px;
                font-family: 'Times New Roman', Times, serif;
                font-style: italic;
                font-weight: bold;
                font-size: 18px;
                color: #222;
                text-align: center;
            }
            .name{
                position: absolute;
                width: 250px;
                height: 40px;
                bottom: 133px;
                right: 50px;
                border-radius: 8px;
                font-family: 'Times New Roman', Times, serif;
                font-style: italic;
                font-weight: bold;
                font-size: 14px;
                line-height: 14px;
                color: #222;
                text-align: center;
            }
            .weight{
                position: absolute;
                width: 250px;
                height: 40px;
                bottom: 70px;
                left: 220px;
                border-radius: 8px;
                font-family: 'Times New Roman', Times, serif;
                font-style: italic;
                font-weight: bold;
                font-size: 14px;
                color: #222;
                line-height: 14px;
                text-align: center;
            }
            .category{
                position: absolute;
                width: 105px;
                height: 20px;
                bottom: 71px;
                right: 50px;
                border-radius: 8px;
                font-family: 'Times New Roman', Times, serif;
                font-style: italic;
                font-weight: bold;
                font-size: 14px;
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
                        {{ @$reg->member->lastname }} {{ @$reg->member->firstname }}
                    </div>
                    <div class="name">
                        {{ @$reg->academy->is_other ? @$reg->academy_name : @$reg->academy->name }}
                    </div>
                    <div class="weight">
                        {{$reg->entry->name}} /{{@$reg->belt->name}}/ {{ @$reg->weight->weight }}кг
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