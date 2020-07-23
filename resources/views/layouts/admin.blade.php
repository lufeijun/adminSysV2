<!doctype html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ $pagename  }}</title>
  <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon" />
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
  {{-- <link rel="stylesheet" href="{{ asset('css/2020/ionicons.min.css') }}"> --}}
  <link rel="stylesheet" href="{{ asset('css/2020/AdminLTE.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/2020/skins/skin-blue.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/2020/font-awesome.min.css') }}">

  {{-- <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/bootstrap-dialog.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/sweetalert.css') }}">
  <link rel="stylesheet" href="{{ asset('css/common.css') }}">
  <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
  <link rel="stylesheet" href="{{ asset('css/iconfont/iconfont.css?v=1.0.0') }}"> --}}



 {{--  <script src="{{ asset('js/jquery-2.2.3.min.js') }}"></script>
  <script src="{{ asset('js/sweetalert.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap-dialog.min.js') }}"></script>
  <script src="{{ asset('js/common.js') }}"></script>
  <script src="{{ asset('js/dropzone.js') }}"></script>
  <script src="{{ asset('js/loading.js') }}"></script>
  <link rel='stylesheet' href="{{ asset('css/element-ui.css') }}"></link>
  <script src="{{ asset('js/element-ui.js') }}"></script>

  <script type="text/javascript" src="{{ asset('js/base.js?v='.time()) }}"></script>

  <link rel="stylesheet" href="{{ asset('css/base.css') }}"> --}}

  @php
    $firstMenuName = App\System\Menu::getFirstMenuName();
    $menuArr = config('custom.menu.menu');
    if ( isset( $menuArr[$firstMenuName] ) ) {
      $menuArr = $menuArr[$firstMenuName];
    } else {
      $menuArr = [];
    }
    $routePath = Request::path();
    $routeController = class_basename(explode('@', \Route::currentRouteAction())[0]);
    $roles = App\System\Admin::getLoginedMessage('roles');

    foreach ($menuArr as $key => $value) {
      $urls = array_column($value['threeMenu'],'current');
      foreach ($urls  as $url ) {
        foreach ($url as $containUrl) {
          if( strpos($routePath,$containUrl) !== false ){
            session(['secondActive'=>$key]);
            break;
          }
        }
      }
    }

  @endphp
</head>
<body class="hold-transition skin-blue sidebar-mini fixed">
<script>
var isEngineES5Compliant = 'create' in Object && 'isArray' in Array && 'x'[0] === 'x';
if (!isEngineES5Compliant) {
  window.location.href = "{{ url('bad-browser') }}";
}
</script>


<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="{{ App\System\Admin::getHomeUrlByRole() }}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><img style="width: 46px;" src="{{ asset('img/logo.png?v=2') }}" alt="logo"></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><img style="width: 60px;" src="{{ asset('img/logo.png?v=2') }}" alt="logo">&nbsp;信息管理系统</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      @foreach (App\System\Admin::getLoginedMessage('menu_first_granted',[]) as $firstMenu => $firstMenuUrl )
        <a href="{{ url($firstMenuUrl) }}" class="float-left-button
        @if ( $firstMenuName == $firstMenu )
          first-menu-active
        @endif
        ">
          {{ $firstMenu }}
        </a>
      @endforeach

      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account Menu -->
          {{-- <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a style="width: 60px;" href="#" class="dropdown-toggle float-left-button" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              工具
            </a>
            <ul class="dropdown-menu">
              <!-- Menu Body -->
              <li class="user-body personal-setting" onclick="window.open('http://mail.zhufaner.com/')">
                <div  class="">
                  企业邮箱
                </div>
              </li>
              <li class="user-body personal-setting" onclick="window.open('https://drive.google.com/drive/u/0/shared-with-me')">
                <div >
                  Google Drive
                </div>
              </li>
            </ul>
          </li> --}}
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="{{ App\System\Image::getImageFromSerivce( App\System\Admin::getLoginedMessage('image','0') ) }}" class="user-image" alt="User Image" title="查看、修改个人信息" style="width:30px;height:30px;object-fit:cover;">
            </a>
            <ul class="dropdown-menu">
              <!-- Menu Body -->
              <li class="user-body personal-setting" onclick="showPersonalInformation();">
                <div class="col-xs-12">
                  个人信息
                </div>
              </li>
              <li class="user-body personal-setting" onclick="changePersonalAvatar();">
                <div class="col-xs-12">
                  修改头像
                </div>
              </li>
              <li class="user-body personal-setting" onclick="changePersonalPassword();">
                <div class="col-xs-12">
                  修改密码
                </div>
              </li>
              <li class="user-body personal-setting" onclick="logout();">
                <div class="col-xs-12">
                  退出登录
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ App\System\Image::getImageFromSerivce( App\System\Admin::getLoginedMessage('image','0.jpg') ) }}" class="img-circle" alt="User Image" style="width:50px;height:45px;object-fit:cover;">
        </div>
        <div class="pull-left info">
          <p>{{ App\System\Admin::getLoginedMessage('name','无') }}</p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i>在线</a>
        </div>
      </div>


      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        @foreach ($menuArr as $title => $menu)
          @if ( App\System\Menu::checkMenuGranted($menu['check'],'second') )
            <li class="treeview
            @if ( session('secondActive') == $title )
              active
            @endif
            ">
              <a href="#">
                <img src="{{ asset('img/menu/'.$menu['image']) }}" class="menu-image">
                <span>{{ $title }}</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                @foreach ($menu['threeMenu'] as $threeMenu)
                  @if ( App\System\Menu::checkMenuGranted( $threeMenu['check'],'three' ) )
                    <li>
                      <a href="{{ url($threeMenu['url']) }}"
                      @if (  App\System\Menu::isCurrentUrl($routePath,$threeMenu['current']) )
                        class="active"
                      @endif
                      >
                        {{ $threeMenu['name'] }}
                      </a>
                    </li>
                  @endif
                @endforeach
              </ul>
            </li>
          @endif
        @endforeach

      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="background-color: #f8f8f8;">

    <div class="load-overlay">
      <div class="load-box">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>

    <!-- Main content -->
    <div id="app">
      <section class="content">
          @yield('content')
      </section>
    </div>
    <!-- /.content -->

  </div>


  <div class="modal fade" id="show-personal-information" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">个人信息</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row" style="padding: 5px 60px;">
            <div class="col-lg-6 strong-bloder">姓名</div>
            <div class="col-lg-6 strong-bloder">手机号</div>
            <div class="col-lg-12" style="height: 15px;"></div>
            <div class="col-lg-6">{{ App\System\Admin::getLoginedMessage('name','无') }}</div>
            <div class="col-lg-6"><input type="number" class="form-control logined-phone-number" placeholder="请填写手机号码" value="{{ App\System\Admin::getLoginedMessage('phone','无') }}"></div>
            <div class="col-lg-12" style="height: 15px;"></div>
            <div class="col-lg-6 strong-bloder">住址</div>
            <div class="col-lg-6 strong-bloder">微信号</div>
            <div class="col-lg-12" style="height: 15px;"></div>
            <div class="col-lg-6"><input type="text" class="form-control logined-address" placeholder="请填写住址" value="{{ App\System\Admin::getLoginedMessage('address','无') }}"></div>
            <div class="col-lg-6"><input type="text" class="form-control logined-wechat" placeholder="请填写微信号" value="{{ App\System\Admin::getLoginedMessage('wechat','无') }}"></div>
            <div class="col-lg-12" style="height: 15px;"></div>
            <div class="col-lg-6 strong-bloder">邮箱／登录名</div>
            <div class="col-lg-6 strong-bloder">区域／城市</div>
            <div class="col-lg-12" style="height: 15px;"></div>
            <div class="col-lg-6 logined-email">{{ App\System\Admin::getLoginedMessage('email','无') }}</div>
            <div class="col-lg-6">{{ App\System\Admin::getLoginedMessage('city','无') }}</div>
            <div class="col-lg-12" style="height: 15px;"></div>
            <div class="col-lg-6 strong-bloder">角色</div>
            <div class="col-lg-6 strong-bloder">状态</div>
            <div class="col-lg-12" style="height: 15px;"></div>
            <div class="col-lg-6">{{ implode('，',App\System\Admin::getLoginedMessage('roles',[])) }}</div>
            <div class="col-lg-6">在职</div>
            <div class="col-lg-6 login-error-message">
              <img src="{{ asset('/img/warn.png') }}">
              <span style="color: #FF0000; margin-left: 10px;"></span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">取 消</button>
          <button type="button" class="btn btn-primary member-msg-save">保 存</button>
        </div>
      </div>
    </div>
  </div>


    <div class="modal fade" id="change-personal-password" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">个人信息</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" style="padding: 5px 60px;">
                        <div class="col-lg-12">原密码</div>
                        <div class="col-lg-12"><input type="password" class="form-control old-password" placeholder="请填写原密码" style="width:100%"></div>
                        <div class="col-lg-12" style="height: 15px;"></div>
                        <div class="col-lg-12">新密码</div>
                        <div class="col-lg-12"><input type="password" class="form-control new-password" placeholder="请填写新密码" style="width:100%"></div>
                        <div class="col-lg-12" style="height: 15px;"></div>
                        <div class="col-lg-12">重复新密码</div>
                        <div class="col-lg-12"><input type="password" class="form-control repeat-password" placeholder="请确认新密码" style="width:100%"></div>
                        <div class="col-lg-12" style="height: 15px;"></div>
                        <div class="login-error-message col-xs-6" style="margin-top: 248px;">
                            <img src="{{ asset('/img/warn.png') }}">
                            <span style="color: #FF0000; margin-left: 10px;"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取 消</button>
                    <button type="button" class="btn btn-primary member-pwd-save">保 存</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="change-personal-avatar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">个人信息</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" style="padding: 5px 60px;">
                        <div class="col-lg-12 avatar-upload-img change-avatar" id="user-image-upload" style="padding: 0;height: 300px;border: 5px dotted #efefef;cursor: pointer;">
                            <h1 style="font-size: 80px;color: #efefef;text-align: center;z-index: 1;margin-top: 68px;">
                                添加图片
                                <div style="font-size: 50px;">支持拖拽到此区域上传</div>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取 消</button>
                    <button type="button" class="btn btn-primary member-avatar-save">保 存</button>
                </div>
            </div>
        </div>
    </div>


  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer" style="display: none;">
    <!-- To the right -->
    <div class="pull-right hidden-xs" style="color: #00659e; margin-right: 15px;">口号</div>
    <!-- Default to the left -->
    <strong>©{{ date('Y') }} XXX公司 京ICP备16001669号
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane active" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript::;">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript::;">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="pull-right-container">
                  <span class="label label-danger pull-right">70%</span>
                </span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>


  {{-- 退出 --}}
  <div style="display: none;">
    <a id="logout-a" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"></a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
      @csrf
    </form>
  </div>

</div>
{{-- 引入 js --}}
<script src="{{ mix('js/app.js') }}"></script>
<script src="{{ asset('js/LTE.js') }}"></script>
<script>
API_ENDPOINT = "{{ url('') }}";
CSRF_TOKEN = '{{ csrf_token() }}';
EMAIL = '{{ App\System\Admin::getLoginedMessage('email') }}';
U_ID = '{{ App\System\Admin::getLoginedMessage('id') }}';

$( document ).ready(function() {
  @if ( ! isset( $is_check_login ) || $is_check_login )
    setInterval("checkLogin()",120000);
  @endif
});
</script>
<script>
  BROSWER_NOT_SUPPORT = false;
  let reCountUploadHeight = function() {
    $(".avatar-upload-img").each(function(index, el) {
      let height = (Math.ceil( ($(this).find('.dz-preview').length + 1 ) / 4)) * 230 + 60;
      if (height > 300) {
        $(this).css({height: height + 'px'});
      } else {
        $(this).css({height: '300px'});
      }
    });
  };

  reCountUploadHeight();

  $("#user-image-upload").each(function() {
    Dropzone.autoDiscover = false;
    let myDropzone = new Dropzone(this, {
      url: "/api/upload/image",
      fallback: function() {
        BROSWER_NOT_SUPPORT = true;
        alert('你的浏览器不支持拖动上传图片，请使用谷歌 Chrome 浏览器！');
      },
      acceptedFiles: ".jpg,.png,.git,.jpeg",
    });
    let _this = $(this);
    myDropzone.on('drop', function(){
    });

    myDropzone.on("success", function(file) {
      var fileName = file.xhr.response;
      $(file.previewElement).attr('data-pic-name', fileName);
      $(file.previewElement).find('.dz-size').remove();
      $(file.previewElement).find('.dz-filename').remove();
      $(file.previewElement).find('.dz-success-mark').remove();
      $(file.previewElement).find('.dz-error-mark').click(function() {
        $(this).parents('.dz-preview').remove();
        reCountUploadHeight();
      });
      $(".dz-image").click(function(event) {
           /* Act on the event */
          let src = $(this).parents('.dz-preview').attr('data-pic-name');
      });
      reCountUploadHeight();
    });

  });

  $('.dz-preview .dz-error-mark').click(function() {
    $(this).parents('.dz-preview').remove();
    reCountUploadHeight();
  });

  $('.avatar-upload-img > h1').click(function(event) {
    $(this).parents('.avatar-upload-img').click();
  });
  $(".dz-image").click(function(event) {
    /* Act on the event */
    let src = $(this).find('img').attr('src');
  });
</script>

</body>
</html>
