/**
  * 修改信息
  *
  */
showPersonalInformation = function(){
  $('#show-personal-information').modal('show');
  return;
}

$(".member-msg-save").click(function(event) {
   /* Act on the event */

    let data = {
        phone: $(".logined-phone-number").val(),
        address: $(".logined-address").val(),
        wechat: $(".logined-wechat").val(),
    };

    let api = 'api/privilege/v1/member/msg/update'+"/"+U_ID;
    axios.post(api,data).then((response) => {
        if( response.data.status == 0 ) {
            Vue.prototype.$message({
                message: '修改成功',
                type: 'success'
            });
            $('#show-personal-information').modal('hide');
        } else {
            swal("请求失败", response.data.message , "warning");
        }
    }).catch(error => {
        swal('系统错误','请联系技术部','error')
    })

});


/**
 * 修改密码
 */
changePersonalPassword = function() {
    $('#change-personal-password').modal('show');
}

$(".member-pwd-save").click(function (event) {

    let data = {
       old_pwd : $(".old-password").val(),
       new_pwd : $(".new-password").val(),
    }

    if ( data.old_pwd == '' ) {
        Vue.prototype.$message({
            message: '请输入旧密码',
            type: 'warning'
        });
        return false;
    }

    if ( data.new_pwd == '' ) {
        Vue.prototype.$message({
            message: '请输入新密码',
            type: 'warning'
        });
        return false;
    }

    if ( data.new_pwd != $(".repeat-password").val() ) {
        Vue.prototype.$message({
            message: '两次密码不一致',
            type: 'warning'
        });
        return false;
    }

    let api = 'api/privilege/v1/member/password/update/'+U_ID;
    axios.post(api,data).then((response) => {
        if( response.data.status == 0 ) {

            $('#show-personal-information').modal('hide')

            swal({
                title: "修改成功",
                type: "success"
            },function(isConfirm){
                _logout()
            });
        } else {
            Vue.prototype.$message({
                message: response.data.message,
                type: 'warning'
            })
        }
    }).catch(error => {
        swal('系统错误','请联系技术部','error')
    })


});




changePersonalAvatar = function() {
    $('#change-personal-avatar').modal('show');
}


$(".member-avatar-save").click(function () {
    let HAS_ERROR = false;

    let image = [];
    $('.change-avatar').find('.dz-preview').each(function() {
        image.push($(this).attr('data-pic-name'));
    });
    image = image.shift();
    if(image == undefined){
        Vue.prototype.$message({
            message: '请上传头像',
            type: 'warning'
        })
        HAS_ERROR = true;
    }else{
        image = JSON.stringify(image);
        image = image.slice(1,-1);
    }

    if ( ! HAS_ERROR ) {
        let data = {
            image: image,
        };

        let api = 'api/privilege/v1/member/image/update/'+U_ID;
        axios.post(api,data).then((response) => {
            if( response.data.status == 0 ) {

                $('#show-personal-avatar').modal('hide')

                swal({
                    title: "修改成功",
                    type: "success"
                },function(isConfirm){
                    _logout()
                });
            } else {
                Vue.prototype.$message({
                    message: response.data.message,
                    type: 'warning'
                })
            }
        }).catch(error => {
            swal('系统错误','请联系技术部','error')
        })



    }


})



/**
 * 退出登录
 */
logout = function() {
  swal({
    title: "确认退出系统",
    text: "",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: "确定",
    cancelButtonText: "取消",
    closeOnConfirm: false,
    closeOnCancel: true
  },
  function(isConfirm){
    if (isConfirm) {
        _logout()
    }
  });
}

_logout = function() {
    $("#logout-a").click();
}




$(document).ready(function() {
  // Handler for .ready() called.
  $(".main-footer").show();
});
