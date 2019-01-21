<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{$site_name}} - @yield('title')</title>
    <meta name="description" content="这是一个 index 页面">
    <meta name="keywords" content="index">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="icon" type="image/png" href="{{url('/assets/i/favicon.png','','',true)}}">
    <link rel="apple-touch-icon-precomposed"
          href="{{url('/assets/i/favicon.png','','',true)}}/assets/i/app-icon72x72@2x.png">
    <meta name="apple-mobile-web-app-title" content="Amaze UI"/>
    <script src="{{url('/assets/i/favicon.png','','',true)}}/assets/js/echarts.min.js"></script>
    <link rel="stylesheet" href="{{url('/assets/css/amazeui.min.css','','',true)}}"/>
    <link rel="stylesheet" href="{{url('/assets/css/amazeui.datatables.min.css','','',true)}}"/>
    <link rel="stylesheet" href="{{url('/assets/css/app.css','','',true)}}">
    <script src="{{url('/assets/js/jquery.min.js','','',true)}}"></script>

</head>

<body data-type="index">
<script src="{{url('/assets/js/theme.js','','',true)}}"></script>
<div class="am-g tpl-g">

@include("layout.fengge")
@include("layout.leftmenu")


@section("content")
    <!-- 内容区域 -->
    @show
</div>
@section("foot")
@show
<script src="{{url('/assets/js/amazeui.min.js','','',true)}}"></script>
<script src="{{url('/assets/js/amazeui.datatables.min.js','','',true)}}"></script>
<script src="{{url('/assets/js/dataTables.responsive.min.js','','',true)}}"></script>
<script src="{{url('/assets/js/app.js','','',true)}}"></script>

</body>

</html>