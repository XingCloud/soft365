//App js.
$(document).ready(function() {
  change_type();
});
//
function change_type(){
	if($("input[name='upload_type']:checked").val() == '0'){
		$("#upload_file").show(function(){			
			$("#app_url").prop('disabled', false);
			$("#app_logo").prop('disabled', false);
			$("#app_image").prop('disabled', false);
			$("#app_icon").prop('disabled', false);
            $("#upload_url_app_url").prop('disabled', true);
		});
		$("#upload_url").hide(function(){
			$("#upload_url_app_url").prop('disabled', true);
		});
	}else{
		$("#upload_url").show(function(){
				$("#upload_url_app_url").prop('disabled', false);
				$("#app_icon").prop('disabled', true);
				$("#app_url").prop('disabled', true);
				$("#app_logo").prop('disabled', true);
				$("#app_image").prop('disabled', true);
		});
		$("#upload_file").hide(function(){
			$("#upload_url_app_url").prop('disabled', false);			
			$("#app_icon").prop('disabled', true);
			$("#app_url").prop('disabled', true);
			$("#app_logo").prop('disabled', true);
			$("#app_image").prop('disabled', true);
		});
		
		
	}
}

//
function add_image() {
		if($("#image_groups").children().length < 5){
	    $("#image_groups").append('<div class="controls">'+
				'<input type="file" placeholder="File" id="app_image" name="app_image[]" />'+ 
				'&nbsp;<span><a href="javascript:void(0);" onclick="add_image();" class="btn">'+
					'<i class="icon icon-plus"></i>增加</a></span>'+
				'&nbsp;<span><a href="javascript:void(0);" onclick="remove_image(this);" class="btn"> <i class="icon icon-remove"></i>删除</a></span>'+	
				'<span class="help-inline error"><font color="#FF0000">*</font></span></div>');
	}else{
		$(".alert").show(function(){
			$("#alert").html("只能增加5个图片.");
		})
	}
}
  function remove_image(obj){
	  $(obj).parent().parent().remove();
  }



// cancel tab
function cancal_tab(lang){
	$("#title_"+lang).remove();
	$("#tab_content_"+lang).remove();
}

function append_tab(lang,title){
	$("#lang_tab_title").append('<li id="title_'+lang+'" name="title_'+lang+'" ><a href="#tab_content_'+lang+'" data-toggle="tab">'+title+'</a></li>');
	$("#lang_tab_content").append('<div class="tab-pane" id="tab_content_'+lang+'" name="tab_content_'+lang+'">\
			<div class="control-group">\
				<div class="controls_diy">\
					<label for="inputName" class="label">名称</label>\
					<input type="input" placeholder="国际化名称" id="nick_name" name="nick_name[]"/>\
					<span class="help-inline error"><font color="#FF0000">*</font></span>\
				    <input type="hidden" id="nation" name="nation[]" value="'+lang+'" />\
			</div>\
		</div>\
			<div class="control-group">\
				<div class="controls_diy">\
					<label for="inputDesc" class="label">描述</label>\
					<textarea placeholder="Desc" rows="6" cols="150" id="Description" name="description[]"/></textarea>\
			</div></div>');
};

function active_one(lang){	
	if(($prev_title = $("#title_"+lang).prev("li[id^='title_']"))[0]){
		$prev_title.addClass('active');
	}else if(($next_title = $("#title_"+lang).next("li[id^='title_']"))[0]){
		$next_title.addClass('active');
	}
	if (($prev_tab = $("#tab_content_"+lang).prev("div[id^='tab_content_']"))[0]){
		$prev_tab.addClass('active');
	}else if (($next_tab = $("#tab_content_"+lang).next("div[id^='tab_content_']"))[0]){
		$next_tab.addClass('active');
	}
}

function select_lang(obj){
  if(obj.checked){
	  if ($("#title_"+obj.value).length==0 && $("#tab_content_"+obj.value).length==0){
		  append_tab(obj.value,obj.name);  
	  }	  
	  uncheck_other(obj.value);
  }else{
	  if($("#title_"+obj.value).hasClass('active') && $("#tab_content_"+obj.value).hasClass('active')){
		  active_one(obj.value);
	  }
	  cancal_tab(obj.value);	  
  }	
}
//取消其它选中的
function uncheck_other(lang){
	$("#lang_tab_title").children().each(function(){
		if ($(this).attr("name") != "title_"+lang){
			$(this).removeClass("active");
		}else{
			$(this).addClass("active");
		}
	});
	$("#lang_tab_content").children().each(function(){
		if ($(this).attr("name") != "tab_content_"+lang){
			$(this).removeClass("active");
		}else{
			$(this).addClass("active");
		}
	});
}
//
