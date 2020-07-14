<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>住范儿后台</title>
    <link rel="stylesheet" href="{{ asset('css/2020/bootstrap.min.css') }}">
    <script src="{{ asset('js/2020/jquery-2.2.3.min.js') }}"></script>
</head>
<style>
    .panel-heading {
        font-size: 18px;
    }
    .panel-body {
        text-align: center;
        height: 300px;
        margin-top: 200px;
        font-size: 30px;
        color: #dc2828;
    }
</style>
<body>

<div class="container" style="margin-top: 30px;">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-danger">
                <div class="panel-heading">温馨提示</div>
                <div class="panel-body">
                    404 页面未找到。<span id="time">3</span>秒钟之后会自动返回...
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var time = 3;
    setInterval("showTime()","1000");
    var showTime = function(){
        if (--time == 0) {
            // history.go(-1);
            if(window.history.length > 1){
                window.history.go( -1 );
            }else{
                window.location.href = '{{ App\System\Admin::getHomeUrlByRole() }}';
            }
        } else {
            $('#time').text(time);
        }
    }
</script>
<script>
    var _hmt = _hmt || [];
    (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?a048b76016f4c322fdc5eda28509d042";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>
</body>
</html>
