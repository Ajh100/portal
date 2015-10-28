# 主站

## 测试数据库

	DB_TYPE:mysql
	DB_HOST:104.131.144.192
	DB_NAME:jh100
	DB_USER:xxg3053
	DB_PWD:xxg111063053

## 注意
 
1. win:
	 启用URL_MODEL=2模式，在apache下需要添加.htaccess文件
	 静态文件使用__PUBLIC__后期可以使用nginx
2. ubuntu
	php默认支持pathinfo功能
	apache2启动Rewrite功能：`sudo a2enmod rewrite`
	一点需要注意的地方：sudo vim /etc/apache2/sites-enabled/000-default
	将其中的：
	AllowOverride None
	修改为：
	AllowOverride All

	重启 sudo /etc/init.d/apache2 restart


## 帮助

[ThinkPhP框架API](http://document.thinkphp.cn/manual_3_2.html) 