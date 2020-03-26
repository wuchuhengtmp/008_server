<section class="rt_wrap content">
 <div class="rt_content">
     <section>
      <h2><strong style="color:grey;">当前位置：数据备份 &raquo; 还原数据库</strong></h2>
     <h2><strong style="color:red;">友情提示：</strong></h2>
     <h2><strong style="color:red;">1、还原数据库功能请谨慎使用，在还原前请先备份当前数据库</strong></h2>
     <h2><strong style="color:red;">2、还原后网站内容将被替换为还原文件标注日期的内容</strong></h2>
     <h2><strong style="color:red;">3、无效的数据库备份文件请删除，垃圾文件将占空间</strong></h2>
      
      <table class="table">
       <tr>
            <th>备份名称</th>
            <th>卷数</th>
            <th>压缩</th>
            <th>数据大小</th>
            <th>备份时间</th>
            <th>状态</th>
            <th>操作</th>
       </tr>
	   <volist name="list" id="data">
	   <tr>
	     <td>{$data.time|date='Ymd-His',###}</td>
	     <td>{$data.part}</td>
	     <td>{$data.compress}</td>
	     <td>{$data.size|format_bytes}</td>
	     <td>{$key}</td>
	     <td>-</td>
	     <td class="action">
	       <a class="db-import" href="__CONTROLLER__/import?time={$data['time']}">还原</a>
				&nbsp;
		   <a class="ajax-get confirm" href="__CONTROLLER__/del?time={$data['time']}">删除</a>
		</td>
		</tr>
		</volist>
      </table>
      
     </section>
 </div>
</section>

<script type="text/javascript">
        $(".db-import").click(function(){
            var self = this, status = ".";
            $.get(self.href, success, "json");
            window.onbeforeunload = function(){ return "正在还原数据库，请不要关闭！" }
            return false;
        
            function success(data){
                if(data.status)
                {
                    if(data.gz)
                    {
                        data.info += status;
                        if(status.length === 5){
                            status = ".";
                        } else {
                            status += ".";
                        }
                    }
                    $(self).parent().prev().html('<font color="red">'+data.info+'</font>');
                    if(data.part){
                        $.get(self.href, 
                            {"part" : data.part, "start" : data.start}, 
                            success, 
                            "json"
                        );
                    }  else {
                        window.onbeforeunload = function(){ return null; }
                    }
                } else {
                    alert(data.info,'alert-error');
                }
            }
        });
    </script>