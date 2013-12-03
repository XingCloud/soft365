<?php
namespace redis;

//内容弹窗的redis操作模型，Hash结构
class ContentPop extends StdPop {
	
	// 弹窗redis键名
	const redis_key = 'content_pop';
	
	// 弹窗类型
	const type = 'content_pop';
	
}