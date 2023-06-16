<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>UniQ Membership Card</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: none;
        }

        .main_container {
            background: #eee;
            margin: 0 auto;
            position: relative;
            padding: 0;
        }

        .container {
            width: 550px;
            height: 742px;
            background: #ddd;
            margin: 0 auto;
            float: left;
            position: relative;
            padding: 0;
            background: url('/assets/images/membershipCard.jpg') no-repeat center center;
            background-size: contain;
            border-top: 2px solid #fff;
        }

        .content {
            position: relative;
            margin-top: 738px;
        }

        .image_container {
            position: absolute;
            width: 130px;
            height: 130px;
            background: #222;
            bottom: 375px;
            left: 50px;
            border-radius: 50%;
        }

        .image_container img {
            border-radius: 100%;
            border: 5px solid #000;
            background: none;
        }

        .eventName {
            position: absolute;
            width: 500px;
            height: 20px;
            bottom: 650px;
            left: 30px;
            border-radius: 8px;
            font-family: 'Times New Roman', Times, serif;
            font-style: normal;
            font-weight: bold;
            font-size: 30px;
            text-align: center;
        }

        .membershipNo {
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

        .name {
            position: absolute;
            width: 280px;
            height: 70px;
            bottom: 400px;
            left: 230px;
            border-radius: 8px;
            font-family: 'Times New Roman', Times;
            font-weight: bold;
            font-size: 30px;
            line-height: 26px;
            color: #222;
            text-align: left;
        }

        .birthdate {
            position: absolute;
            width: 250px;
            height: 20px;
            bottom: 320px;
            left: 40px;
            border-radius: 8px;
            font-family: 'Times New Roman', Times;
            
            font-style: normal;
            font-size: 20px;
            color: #222;
            line-height: 20px;
            text-align: left;
        }

        .gender {
            position: absolute;
            width: 250px;
            height: 40px;
            bottom: 270px;
            left: 40px;
            border-radius: 8px;
            font-family: 'Times New Roman', Times;
            font-style: normal;
            
            font-size: 20px;
            text-align: left;
            color: #222;
        }

        .belt {
            position: absolute;
            width: 250px;
            height: 40px;
            bottom: 240px;
            left: 40px;
            border-radius: 8px;
            font-family: 'Times New Roman', Times;
            
            font-style: normal;
            font-size: 20px;
            color: #222;
            line-height: 20px;
            text-align: left;
        }

        .country {
            position: absolute;
            width: 250px;
            height: 40px;
            bottom: 210px;
            left: 40px;
            border-radius: 8px;
            font-family: 'Times New Roman', Times;
            font-style: normal;
            
            font-size: 20px;
            color: #222;
            line-height: 20px;
            text-align: left;
        }

        .plan {
            position: absolute;
            width: 250px;
            height: 20px;
            bottom: 203px;
            left: 40px;
            border-radius: 8px;
            font-family: 'Times New Roman', Times;
            
            font-style: normal;
            font-size: 20px;
            color: #222;
            line-height: 20px;
            text-align: left;
        }

        .expires {
            position: absolute;
            width: 250px;
            height: 20px;
            bottom: 160px;
            left: 40px;
            border-radius: 8px;
            font-family: 'Times New Roman', Times;
            
            font-style: normal;
            font-size: 20px;
            color: #222;
            line-height: 20px;
            text-align: left;
        }

        .academy {
            position: absolute;
            width: 250px;
            height: 20px;
            bottom: 120px;
            left: 40px;
            border-radius: 8px;
            font-family: 'Times New Roman', Times;
            font-style: normal;
            font-size: 20px;
            color: #222;
            line-height: 20px;
            text-align: left;
        }

        .qrcode {
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

        .barcode {
            position: absolute;
            width: 250px;
            height: 90px;
            bottom: 200px;
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

        .regid {
            position: absolute;
            width: 250px;
            height: 40px;
            bottom: 150px;
            right: 80px;
            border-radius: 8px;
            font-family: 'Times New Roman', Times;
            font-weight: bold;
            font-style: normal;
            font-size: 20px;
            color: #222;
            line-height: 20px;
            text-align: right;
        }

        footer {
            page-break-after: always;
        }
    </style>
</head>
<body>
<div class="main_container">
    <div class="container">
        <div class="content">
            <div class="eventName">
                UniQ Sport Membership Card
            </div>

            <div class="image_container">
                <img src="{{\Storage::disk('s3')->url($member->profile_url)}}" width="100%" height="100%" />
            </div>
            <div class="membershipNo">
                Membership № : 00001
            </div>
            <div class="name">
                {{$member->lastname}} {{$member->firstname}}
            </div>
            <div class="birthdate">
                Нас : <strong>{{$member->age}}</strong>
            </div>
            <div class="belt">
                Жюү жицү Бүс : <strong>purple</strong>
            </div>
            <div class="gender">
                Хүйс : <strong>{{ Config::get("enums.gender_code")[@$member->gender_code] }}</strong>
            </div>
            <div class="country">
                Улс : <strong>{{$countries->name }}</strong>
            </div>
            <div class="plan">
                Гишүүнчлэлийн төрөл : <strong>Энгийн</strong>
            </div>
            <div class="expires">
                Хүчинтэй хугацаа : <strong> 2024.12.31 </strong>
            </div>
            <div class="academy">
            Академи : <strong>{{$academy->name}}</strong>
            </div>
            <div class="qrcode">
                <img src="data:image/png;base64,{{\DNS2D::getBarcodePNG(strval(@$member->id), 'QRCODE')}}" width="150px" height="150px">
            </div>
            <div class="regid">
                {{@$member->id}}
            </div>
            
        </div>
    </div>
</div>
<div style="break-after:always; clear:both"></div>
</body>

</html>
