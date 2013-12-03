<?php if (!defined('THINK_PATH')) exit();?><!--头部部分-->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>应用中心管理</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="/Tpl/Public/css/bootstrap.css" rel="stylesheet">
    <link href="/Tpl/Public/css/datepicker.css" rel="stylesheet">
    <style>
		@CHARSET "UTF-8";
		.footer {
		    border-top: 1px solid #CCCCCC;
		    padding: 10px;
		    text-align: center;
		}
		body {
	        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
	      }
	</style>

    <link href="/Tpl/Public/css/bootstrap-responsive.css" rel="stylesheet">
	<script language="JavaScript">
		var _URL = '__URL__';
	</script>
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="shortcut icon" href="../assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
    <script language="javascript" src="/Tpl/Public/js/jquery-1.7.2.min.js" /></script>
    <script language="javascript" src="/Tpl/Public/js/bootstrap.js" /></script>
    <script language="javascript" src="/Tpl/Public/js/bootstrap-datepicker.js" /></script>
  
  <script type="text/javascript" src="/Tpl/Public/timepicker/jquery.timepicker.js"></script>
  <link rel="stylesheet" type="text/css" href="/Tpl/Public/timepicker/jquery.timepicker.css" />

  
    <script>
    	$(function(){
    		$('.datepicker').datepicker({format:'yyyy-mm-dd'});
    		$('.timepicker').timepicker({timeFormat:'H:i',step:60});
    	})
    </script>
  </head>

  <body>

	<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">应用中心管理</a>
          <div class="nav-collapse collapse">
			<?php if(isset($_SESSION['user_id'])): ?><p class="navbar-text pull-right">
	              [<?php echo ($_SERVER["SERVER_ADDR"]); ?>]Logged in as <a href="/User/view" class="navbar-link"><?php echo ($_SESSION['user_name']); ?></a> [<a href="/User/logout" class="navbar-link">Logout</a>]
	            </p><?php endif; ?>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
<div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
        	<input type="hidden" id="select_product_name" value="<?php echo ($selectProductName); ?>"></input>
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li class="nav-header" style="text-transform:none;">菜单管理</li>
              <li class="<?php if($Think.MODULE_NAME == 'User' && $Think.ACTION_NAME == 'view'){echo 'active';}?>" ><a href="/User/view">查看用户信息</a></li>
              <li class="<?php if($Think.MODULE_NAME == 'User' && $Think.ACTION_NAME == 'edit'){echo 'active';}?>"><a href="/User/edit">编辑用户信息</a></li>
              <li class="<?php if($Think.MODULE_NAME == 'User' && $Think.ACTION_NAME == 'change'){echo 'active';}?>"><a href="/User/change">修改密码</a></li>
              <li class="divider"/>
              <li class="<?php if($Think.MODULE_NAME == 'UserManage'){echo 'active';}?>"><a href="/UserManage/index">用户管理</a></li>
              <li class="<?php if($Think.MODULE_NAME == 'StdPop'){echo 'active';}?>"><a href="/StdPop/index">标准弹窗</a></li>
              <li class="<?php if($Think.MODULE_NAME == 'ContentPop'){echo 'active';}?>" ><a href="/ContentPop/index">内容弹窗</a></li>
              <li class="<?php if($Think.MODULE_NAME == 'Country'){echo 'active';}?>" ><a href="/Country/index">国家名称</a></li>
              <li class="<?php if($Think.MODULE_NAME == 'Lang'){echo 'active';}?>" ><a href="/Lang/index">语言名称</a></li>
              <li class="<?php if($Think.MODULE_NAME == 'Oemid'){echo 'active';}?>" ><a href="/Oemid/index">Oemid</a></li>
              <li class="<?php if($Think.MODULE_NAME == 'Config' && $Think.ACTION_NAME == 'edit'){echo 'active';}?>"><a href="/Config/edit">全局配置</a></li>
              <li class="divider"/>
              <li class="<?php if($Think.MODULE_NAME == 'Config' && $Think.ACTION_NAME == 'poplist'){echo 'active';}?>"><a href="/Config/poplist">查看弹窗次序</a></li>
              <li class="<?php if($Think.MODULE_NAME == 'Stat'){echo 'active';}?>" ><a href="/Stat/">统计数据</a></li>
              <!-- li class="<?php if($Think.MODULE_NAME == 'Soft'){echo 'active';}?>" ><a href="/Soft/index">应用管理</a></li>
              <li class="<?php if($Think.MODULE_NAME == 'Link'){echo 'active';}?>" ><a href="/Link/index">链接管理</a></li>
			  <li class="<?php if($Think.MODULE_NAME == 'Analytic' && ($Think.ACTION_NAME == 'index' ||$Think.ACTION_NAME == 'detail' ) ){echo 'active';}?>" ><a href="/Analytic/index">统计信息</a></li>
			              <li class="<?php if($Think.MODULE_NAME == 'Analytic' && $Think.ACTION_NAME == 'charts'){echo 'active';}?>"><a href="/Analytic/charts">分布查看</a></li -->
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
<script language="javascript" src="/Tpl/Public/js/apps.js" /></script>
<style type="text/css">
.controls_diy {
  *display: inline-block;
  *padding-left: 20px;
  margin-left: 18px;
  *margin-left: 0;
}
</style>
<div id="create_alert"></div>
<div class="container-fluid">
	<div class="row-fluid">
		<form class="bs-docs-example form-horizontal" method='post'
			enctype="multipart/form-data" name="name" id="form1"
			ACTION="/UserManage/save">

			<legend>用户信息</legend>
			<input type="hidden" name="id" value="<?php echo ($user['id']); ?>"/>
			<div class="control-group">
				<label for="inputOem" class="control-label">用户状态</label>
				<div class="controls">
					<select id="status" name="status">
						<?php if(is_array($status)): $i = 0; $__LIST__ = $status;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if($key == $user['status']): ?>selected<?php endif; ?> ><?php echo ($v); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
					</select> <span class="help-inline error"><font color="#FF0000">*</font></span>
				</div>
			</div>
			
			<div class="control-group">
				<label for="inputOem" class="control-label">用户名</label>
				<div class="controls">
					<input type="text" placeholder="name" id="name" name="name" value="<?php echo ($user['name']); ?>" /> <span
						class="help-inline error"><font color="#FF0000">*</font></span> 
				</div>
			</div>
			
			<div class="control-group">
				<label for="inputOem" class="control-label">用户说明</label>
				<div class="controls">
					<input type="text" placeholder="desc" id="desc" name="desc" value="<?php echo ($user['desc']); ?>" /> <span
						class="help-inline error"><font color="#FF0000">*</font></span> 
				</div>
			</div>
			
			<div class="control-group">
				<label for="inputOem" class="control-label">密码</label>
				<div class="controls">
					<input type="text" placeholder="password" id="password" name="password" value=""  /> 
				</div>
			</div>
			
			<div class="control-group">
				<label for="inputOem" class="control-label">email</label>
				<div class="controls">
					<input type="text" placeholder="email" id="email" name="email" value="<?php echo ($user['email']); ?>"  /> <span
						class="help-inline error"><font color="#FF0000">*</font></span> 
				</div>
			</div>
			
			<div class="control-group">
				<label for="inputOem" class="control-label">权限</label>
				<div class="controls">
					<?php if(is_array($privilege)): $i = 0; $__LIST__ = $privilege;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><input <?php if(in_array($key,$user['privilege'])): ?>checked='checked'<?php endif; ?> type="checkbox" id="<?php echo ($key); ?>" name="privilege[]" value="<?php echo ($key); ?>" style="width:25px;"  />&nbsp;<label style="display:inline;" for='<?php echo ($key); ?>'><?php echo ($v); ?></label>&nbsp;&nbsp;&nbsp;<?php endforeach; endif; else: echo "" ;endif; ?> 
				</div>
			</div>
			
			
			<div class="control-group">
				<div class="controls">
					<button class="btn btn-primary" type="submit">保存</button>
					<a class="btn" href="/UserManage/index">取消</a>
				</div>
			</div>
		</form>
	</div>
</div>
<!-- 版权信息区域 -->
<footer class="footer">
        &copy; 2013 Elex-Tech All Rights Reserved
    </footer>

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

  </body>
</html>