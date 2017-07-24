<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width">
    <!--[if gte IE 9]>
    <style type="text/css">
        .gradient {
            filter: none;
        }
    </style>
    <![endif]-->
    <style type="text/css">
        *, *:after, *:before {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            -ms-box-sizing: border-box;
            box-sizing: border-box;
        }

        html, body {
            background: #ccc;
            font: bold 14px/20px "Trajan Pro", "Times New Roman", Times, serif;
            color: #430400;
            padding: 0;
            margin: 0;
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.15);
        }

        .error-page-wrap {
            width: 310px;
            height: 310px;
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            margin-left: auto;
            margin-right: auto;
            margin-top: -155px;
        }

        .error-page-wrap:before {
            box-shadow: 0 0 200px 150px #fff;
            width: 310px;
            height: 310px;
            border-radius: 50%;
            position: absolute;
            z-index: -1;
            content: '';
            margin: 0;
            padding: 0;
            display: block;
        }

        .error-page {
            width: 310px;
            height: 310px;
            border-radius: 50%;
            top: 0px;
            position: relative;
            text-align: center;
            background: #d36242;
            background: -moz-linear-gradient(top, #d36242 0%, darkred 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #d36242), color-stop(100%, darkred));
            background: -webkit-linear-gradient(top, #d36242 0%, darkred 100%);
            background: -o-linear-gradient(top, #d36242 0%, darkred 100%);
            background: -ms-linear-gradient(top, #d36242 0%, darkred 100%);
            background: linear-gradient(to bottom, #d36242 0%, darkred 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='$firstColor', endColorstr='$secondColor', GradientType=0);
        }

        .error-page:before {
            width: 63px;
            height: 63px;
            content: '';
            z-index: -1;
            display: block;
            position: relative;
            top: -19px;
            left: 44px;
        }

        .error-page:after {
            width: 310px;
            height: 17px;
            margin: 0 auto;
            top: 25px;
            content: '';
            z-index: -1;
            display: block;
            position: relative;
            background: -moz-radial-gradient(center, ellipse cover, rgba(0, 0, 0, 0.65) 0%, rgba(35, 26, 26, 0) 59%, rgba(60, 44, 44, 0) 100%);
            background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, rgba(0, 0, 0, 0.65)), color-stop(59%, rgba(35, 26, 26, 0)), color-stop(100%, rgba(60, 44, 44, 0)));
            background: -webkit-radial-gradient(center, ellipse cover, rgba(0, 0, 0, 0.65) 0%, rgba(35, 26, 26, 0) 59%, rgba(60, 44, 44, 0) 100%);
            background: -o-radial-gradient(center, ellipse cover, rgba(0, 0, 0, 0.65) 0%, rgba(35, 26, 26, 0) 59%, rgba(60, 44, 44, 0) 100%);
            background: -ms-radial-gradient(center, ellipse cover, rgba(0, 0, 0, 0.65) 0%, rgba(35, 26, 26, 0) 59%, rgba(60, 44, 44, 0) 100%);
            background: radial-gradient(ellipse at center, rgba(0, 0, 0, 0.65) 0%, rgba(35, 26, 26, 0) 59%, rgba(60, 44, 44, 0) 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#a6000000', endColorstr='#003c2c2c', GradientType=1);
        }

        .error-page h1 {
            font-size: 100px;
            margin: 55px auto 0 auto;
        }

        .error-page h1:before {
            width: 260px;
            height: 1px;
            position: relative;
            margin: 0 auto;
            top: 70px;
            content: '';
            display: block;
            background: -moz-radial-gradient(center, ellipse cover, rgba(111, 25, 25, 0.65) 0%, rgba(75, 38, 38, 0) 70%, rgba(60, 44, 44, 0) 100%);
            background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, rgba(111, 25, 25, 0.65)), color-stop(70%, rgba(75, 38, 38, 0)), color-stop(100%, rgba(60, 44, 44, 0)));
            background: -webkit-radial-gradient(center, ellipse cover, rgba(111, 25, 25, 0.65) 0%, rgba(75, 38, 38, 0) 70%, rgba(60, 44, 44, 0) 100%);
            background: -o-radial-gradient(center, ellipse cover, rgba(111, 25, 25, 0.65) 0%, rgba(75, 38, 38, 0) 70%, rgba(60, 44, 44, 0) 100%);
            background: -ms-radial-gradient(center, ellipse cover, rgba(111, 25, 25, 0.65) 0%, rgba(75, 38, 38, 0) 70%, rgba(60, 44, 44, 0) 100%);
            background: radial-gradient(ellipse at center, rgba(111, 25, 25, 0.65) 0%, rgba(75, 38, 38, 0) 70%, rgba(60, 44, 44, 0) 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#a66f1919', endColorstr='#003c2c2c', GradientType=1);
        }

        .error-page h1:after {
            width: 260px;
            height: 1px;
            content: '';
            display: block;
            opacity: 0.2;
            margin: 0 auto;
            top: 50px;
            position: relative;
            background: -moz-radial-gradient(center, ellipse cover, rgba(247, 173, 148, 0.65) 0%, rgba(255, 255, 255, 0.01) 99%, rgba(255, 255, 255, 0) 100%);
            background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, rgba(247, 173, 148, 0.65)), color-stop(99%, rgba(255, 255, 255, 0.01)), color-stop(100%, rgba(255, 255, 255, 0)));
            background: -webkit-radial-gradient(center, ellipse cover, rgba(247, 173, 148, 0.65) 0%, rgba(255, 255, 255, 0.01) 99%, rgba(255, 255, 255, 0) 100%);
            background: -o-radial-gradient(center, ellipse cover, rgba(247, 173, 148, 0.65) 0%, rgba(255, 255, 255, 0.01) 99%, rgba(255, 255, 255, 0) 100%);
            background: -ms-radial-gradient(center, ellipse cover, rgba(247, 173, 148, 0.65) 0%, rgba(255, 255, 255, 0.01) 99%, rgba(255, 255, 255, 0) 100%);
            background: radial-gradient(ellipse at center, rgba(247, 173, 148, 0.65) 0%, rgba(255, 255, 255, 0.01) 99%, rgba(255, 255, 255, 0) 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#a6f7ad94', endColorstr='#00ffffff', GradientType=1);
        }

        .error-page h2 {
            margin: 66px 0 30px 0;
            font-size: 17px;
        }

        .error-page h2:before {
            height: 1px;
            position: absolute;
            margin: 0 auto;
            content: '';
            display: block;
        }

        .error-page h2:after {
            width: 130px;
            height: 1px;
            content: '';
            display: block;
            opacity: 0.2;
            margin: 0 auto;
            top: 11px;
            position: relative;
            background: -moz-radial-gradient(center, ellipse cover, rgba(247, 173, 148, 0.65) 0%, rgba(255, 255, 255, 0.01) 99%, rgba(255, 255, 255, 0) 100%);
            background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, rgba(247, 173, 148, 0.65)), color-stop(99%, rgba(255, 255, 255, 0.01)), color-stop(100%, rgba(255, 255, 255, 0)));
            background: -webkit-radial-gradient(center, ellipse cover, rgba(247, 173, 148, 0.65) 0%, rgba(255, 255, 255, 0.01) 99%, rgba(255, 255, 255, 0) 100%);
            background: -o-radial-gradient(center, ellipse cover, rgba(247, 173, 148, 0.65) 0%, rgba(255, 255, 255, 0.01) 99%, rgba(255, 255, 255, 0) 100%);
            background: -ms-radial-gradient(center, ellipse cover, rgba(247, 173, 148, 0.65) 0%, rgba(255, 255, 255, 0.01) 99%, rgba(255, 255, 255, 0) 100%);
            background: radial-gradient(ellipse at center, rgba(247, 173, 148, 0.65) 0%, rgba(255, 255, 255, 0.01) 99%, rgba(255, 255, 255, 0) 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#a6f7ad94', endColorstr='#00ffffff', GradientType=1);
        }

        .error-back {
            text-decoration: none;
            color: #430400;
            font-size: 15px;
        }

        .error-back:hover {
            color: #EB957D;
            text-shadow: 0 0 3px black;
        }

        #footer {
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        #footer p {
            text-align: center;
            color: #666666;
        }
    </style>
</head>
<body>
<div class="error-page-wrap">
    <article class="error-page gradient">
        <hgroup>
            <h2>Đang xử lý</h2>

            <h2>oops! The page is being processed</h2>
        </hgroup>
        <a class="error-back" href="javascript:history.go(-1)">&laquo; Back</a> | <a class="error-back"
         href="<?php echo $site_url; ?>">Home &raquo;</a>
    </article>
</div>
<div id="footer">
    <p>&copy; <?php echo date('Y') . ' ' . $site_name; ?></p>
</div>
</body>
</html>