
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
                width: 1488px;
                height: 2104px;
                background: #eee;
                margin: 0 auto;
                position: relative;
                padding: 0;
            }
            .container{
                width: 744px;
                height: 1052px;
                background: #ddd;
                margin: 0 auto;
                float: left;
                position: relative;
                padding: 0;
                margin: 0;
                background: url('assets/images/bg.jpg') no-repeat center center;
                background-size: contain;
            }
            .image_container{
                width: 188px;
                height: 334px;
                border-radius: 8px;
                margin-top: 658px;
                margin-left: 83px;
                float: left;
            }
            .image_container img{
                width: 188px;
                height: 334px;
                border-radius: 8px;
            }
            .org{
                width: 215px;
                height: 30px;
                border-radius: 8px;
                font-family: 'Times New Roman', Times, serif;
                font-style: italic;
                font-weight: bold;
                font-size: 22px;
                color: #222;
                float: left;
            }
            .name{
                width: 348px;
                height: 30px;
                border-radius: 8px;
                font-family: 'Times New Roman', Times, serif;
                font-style: italic;
                font-weight: bold;
                font-size: 26px;
                color: #222;
                float: left;
            }
            .weight{
                width: 150px;
                height: 40px;
                border-radius: 8px;
                font-family: 'Times New Roman', Times, serif;
                font-style: italic;
                font-weight: bold;
                font-size: 36px;
                color: #222;
                float: left;
            }
            .category{
                width: 150px;
                height: 30px;
                border-radius: 8px;
                font-family: 'Times New Roman', Times, serif;
                font-style: italic;
                font-weight: bold;
                font-size: 26px;
                color: #222;
                float: left;
            }

        </style>
        
        
    </head>
    <body>
        
        <div class="main_container">
            @forelse(@$regs as $chunk)
            @foreach($chunk as $reg)
            <div class="container">
                <div class="image_container">
                    <img src="assets/images/pic.jpg" width="100%" height="100%" />
                </div>
                <div class="org">
                    {{ @$reg->academy->name }}
                </div>
                <div class="name">
                    {{ @$reg->member->lastname }} {{ @$reg->member->firstname }}
                </div>
                <div class="weight">
                    {{ @$reg->weight->weight }}
                </div>
                <div class="category">
                    {{ @$reg->entry->name }}
                </div>
            </div>
            @endforeach
            <!--
            <div class="container">
                <div class="image_container">
                    <img src="assets/images/pic.jpg" width="100%" height="100%" />
                </div>
                <div class="org">
                    Байгууллага
                </div>
                <div class="name">
                    Баатарцогт Нандин-Эрдэнэ
                </div>
                <div class="weight">
                    80кг
                </div>
                <div class="category">
                    Ангилал
                </div>
            </div>
            <div class="container">
                <div class="image_container">
                    <img src="assets/images/pic.jpg" width="100%" height="100%" />
                </div>
                <div class="org">
                    Байгууллага
                </div>
                <div class="name">
                    Баатарцогт Нандин-Эрдэнэ
                </div>
                <div class="weight">
                    80кг
                </div>
                <div class="category">
                    Ангилал
                </div>
            </div>
            <div class="container">
                <div class="image_container">
                    <img src="assets/images/pic.jpg" width="100%" height="100%" />
                </div>
                <div class="org">
                    Байгууллага
                </div>
                <div class="name">
                    Баатарцогт Нандин-Эрдэнэ
                </div>
                <div class="weight">
                    80кг
                </div>
                <div class="category">
                    Ангилал
                </div>
            </div>
            -->
            @empty
            @endforelse
        </div>
    </body>
</html>
