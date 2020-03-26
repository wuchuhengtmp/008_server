<section class="rt_wrap content mCustomScrollbar">
 <div class="rt_content">
     <section>
      <h2><strong style="color:grey;">当前位置：会员管理 &raquo; 批量导入会员</strong></h2>
      <h2><strong style="color:red;">1、批量导入会员时请将会员信息填写完整，切勿重复导入；</strong></h2>
      <h2><strong style="color:red;">2、请使用系统提供的“导入会员列表模板.csv”文件来进行导入操作，其它文件一律无效</strong></h2>
      <h2><strong style="color:red;">3、下载模板文件=》<a href="__PUBLIC__/Upload/User/user_tmp.csv">导入会员列表模板.csv</a></strong></h2>
      <h2><strong style="color:red;">4、字段说明：性别可填写男、女、保密，血型可填写A/B/AB/O/其他，出生日期请填写正确的日期格式，如1990-01-01，用户名/手机号码/邮箱请填写其中之一，密码不填写默认123456。</strong></h2>
      
      <form action="__ACTION__" method="post" enctype="multipart/form-data">
      <input type="hidden" name="tmp" value="1">
      <ul class="ulColumn2">
       <li>
        <span class="item_name" style="width:120px;">会员列表文件：</span>
        <label class="uploadImg">
         <input type="file" name="file"/>
         <span>上传文件</span>
        </label>
       </li>
       <li>
        <span class="item_name" style="width:120px;"></span>
        <input type="submit" class="link_btn" value="确定导入会员"/>
       </li>
      </ul>
      </form>
     </section>
 </div>
</section>