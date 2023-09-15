
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
                background: #eee;
                margin: 0 auto;
                position: relative;
                padding: 0;
            }
            .container{
                width: 2000px;
                height: 1414px;
                background: #ddd;
                margin: 0 auto;
                float: left;
                position: relative;
                padding: 0;
                margin: 0;
                background: url('/assets/images/event_certificates/elf_cup.png') no-repeat center center;
                background-size: contain;
                border-top: 2px solid #fff;
            }
            .content{
                position: relative;
                margin-top: 1000px;
            }
            .org{
                position: absolute;
                width: 500px;
                height: 90px;
                bottom: 360px;
                left: 900px;
                border-radius: 8px;
                font-family: 'Times New Roman', Times;
                font-style: normal;
                font-weight: bold;
                font-size: 18px;
            }
            .name{
                position: absolute;
                width: 500px;
                height: 70px;
                bottom: 360px;
                left: 830px;
                /* right: 100px; */
                border-radius: 8px;
                font-family: 'Times New Roman', Times;
                /* font-style: normal; */
                font-weight: bold;
                font-size: 50px;
                line-height: 26px;
                color: #222;
                text-align: left;
            }
            .entry{
                position: absolute;
                width: 500px;
                height: 40px;
                bottom: 250px;
                left: 420px;
                border-radius: 8px;
                font-family: 'Times New Roman', Times;
                font-style: normal;
                font-weight: bold;
                font-size: 30px;
                color: #222;
                line-height: 25px;
                text-align: left;
            }
            .award{
                position: absolute;
                width: 500px;
                height: 40px;
                bottom: 250px;
                left: 1130px;
                border-radius: 8px;
                font-family: 'Times New Roman', Times;
                font-style: normal;
                font-weight: bold;
                font-size: 30px;
                color: #222;
                line-height: 25px;
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
            .qrcode{
                position: absolute;
                width: 250px;
                height: 90px;
                bottom: 260px;
                right: 30px;
                border-radius: 8px;
                font-family: 'Times New Roman', Times;
                font-weight: bold;
                font-style: normal;
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
        <div class="main_container">
            <div class="container">
                <div class="content">
                    
                    
                    <!-- <div class="org">
                        {{ @$reg->academy->is_other ? @$reg->academy_name : @$reg->academy->name }}
                    </div> -->
                    <div class="name">
                        {{ @$reg->member->lastname }} {{ @$reg->member->firstname }}
                    </div>
                    <div class="entry">
                        {{$reg->entry->name}} / {{@$reg->belt->name}} /{{ @$reg->weight->weight }}кг 
                    </div>
                    <div class="award">
                        {{$reg->award->place_number}}
                    </div>
                </div>
            </div>
        </div>
        <div style="break-after:always; clear:both"></div> 
    </body>
</html>