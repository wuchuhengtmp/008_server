<script type="text/javascript">
$(document).ready(function(){
	//取消全选
	$('#unselect').click(function(){
		$("input:checkbox").removeAttr("checked");
	});
	//全选
	$('#selectall').click(function(){
		$("input:checkbox").prop("checked","checked");
	});
});
</script>
<section class="rt_wrap content">
 <div class="rt_content">
     <section>
      <h2><strong style="color:grey;">当前位置：数据备份 &raquo; 备份数据库</strong></h2>
     <h2><strong style="color:red;">友情提示：</strong></h2>
     <h2><strong style="color:red;">1、数据库备份频率保持15天一次即可，无须频繁备份，导致产生不必要的垃圾文件而占用空间。</strong></h2>
     <h2><strong style="color:red;">2、使用数据库备份的同时，请使用“网站设置”->“数据备份”->“备份文件”功能，以便数据库恢复的时候能还原相关的文件。</strong></h2>
      <div class="page_title">
        <a id="export" href="javascript:;" autocomplete="off" class="fr top_rt_btn" style="margin-right: 10px">立即备份</a>
		<a id="optimize" href="__CONTROLLER__/optimize" class="fr top_rt_btn" style="margin-right: 10px">优化表</a>
		<a id="repair" href="__CONTROLLER__/repair" class="fr top_rt_btn" style="margin-right: 10px">修复表</a>
      </div>
      <form id="export-form" action="__CONTROLLER__/export" method="post">
      <table class="table">
       <tr>
           <th>ID</th>
           <th>表名</th>
           <th>表名注释</th>
           <th>数据量</th>
           <th>数据大小</th>
           <th>创建时间</th>
           <th>备份状态</th>
           <th>操作</th>
       </tr>
       <volist name="list" id="table">
       <tr>
           <td>
              <input class="ids" checked="checked" type="checkbox" name="tables[]" value="{$table.name}">
		   </td>
		   <td>{$table.name}</td>
		   <td>{$table.comment}</td>
		   <td>{$table.rows}</td>
		   <td>{$table.data_length|format_bytes}</td>
		   <td>{$table.create_time}</td>
		   <td class="info">未备份</td>
		   <td class="action">
		      <a class="ajax-get no-refresh" href="__CONTROLLER__/optimize?tables={$table['name']}">优化表</a>
				&nbsp;
			  <a class="ajax-get no-refresh" href="__CONTROLLER__/repair?tables={$table['name']}">修复表</a>
		   </td>
	   </tr>
	   </volist>
	   <tr>
	      <td colspan="8" align="right" style="text-align:right;">
	        <input type="button" class="link_btn" id="unselect" value="取消选择">
			<input type="button" class="link_btn" id="selectall" value="全选">
		  </td>
	   </tr>
      </table>
      </form>
     </section>
 </div>
</section>

<script type="text/javascript">
    (function($){
        var $form = $("#export-form"), $export = $("#export"), tables 
        $optimize = $("#optimize"), $repair = $("#repair");

        $optimize.add($repair).click(function(){
            $.post(this.href, $form.serialize(), function(data){
                if(data.status){
                    alert(data.info,'alert-success');
                } else {
                	alert(data.info,'alert-error');
                }
                setTimeout(function(){
	                $('#top-alert').find('button').click();
	                $(that).removeClass('disabled').prop('disabled',false);
	            },1500);
            }, "json");
            return false;
        });

        $export.click(function(){
            $export.parent().children().addClass("disabled");
            $export.html("正在发送备份请求...");
            $.post(
                $form.attr("action"),
                $form.serialize(),
                function(data){
                    if(data.status){
                        tables = data.tables;
                        $export.html( data.info +"开始备份，请不要关闭本页面！");
                        backup(data.tab);
                        window.onbeforeunload = function(){ return "正在备份数据库，请不要关闭！" }
                    } else {
                    	alert(data.info,'alert-error');
                        $export.parent().children().removeClass("disabled");
                        $export.html("立即备份");
                        setTimeout(function(){
        	                $('#top-alert').find('button').click();
        	                $(that).removeClass('disabled').prop('disabled',false);
        	            },1500);
                    }
                },
                "json"
            );
            return false;
        });

        function backup(tab, status){
            status && showmsg(tab.id, "<font color=\"red\">开始备份...(0%)</font>");
            $.get($form.attr("action"), tab, function(data){
                if(data.status){
                    showmsg(tab.id, '<font color="red">'+data.info+'</font>');

                    if(!$.isPlainObject(data.tab)){
                        $export.parent().children().removeClass("disabled");
                        $export.html("备份完成，点击重新备份！");
                        window.onbeforeunload = function(){ return null }
                        return;
                    }
                    backup(data.tab, tab.id != data.tab.id);
                } else {
                    alert(data.info,'alert-error');
                    $export.parent().children().removeClass("disabled");
                    $export.html("立即备份");
                    setTimeout(function(){
    	                $('#top-alert').find('button').click();
    	                $(that).removeClass('disabled').prop('disabled',false);
    	            },1500);
                }
            }, "json");

        }

        function showmsg(id, msg){
            $form.find("input[value=" + tables[id] + "]").closest("tr").find(".info").html(msg);
        }
    })(jQuery);
</script>