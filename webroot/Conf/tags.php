<?php
return array(
		'action_begin'=>array('Login'), // 在action_init 标签位添加Test行为
		'app_begin'=>array(  //因为项目中也可能用到语言行为,最好放在项目开始的地方
				'CheckLang'      //检测语言
		),
);