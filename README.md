## 简介
  利用laravel7.5 和 adminLTE 组合的后台管理系统

## 基本特性
- **包含系统成员管理、角色管理、角色权限管理**
- **支持后台用户修改基本信息，包括头像、电话，地址等信息**
- **支持用户包含多个角色**
- **实现权限控制，权限分为菜单权限、功能权限**

## 安装
- 复制 .env 配置，cp .env.example .env
- 生成application encryption，php artisan key:generate
- 设置好数据库配置，将 laravel.sql 的数据导入数据库
- 生成后台的登录账户密码：admin@ooxx.com、123456789


## 如果有报错

- 使用git克隆整个项目，并将bootstrap/cache/* ，storage/* 权限设置为777； 
- 执行composer install，处理了依赖关系，下载相关安装包；  
- 在public目录下建立sys_images，并将权限赋为777。       
