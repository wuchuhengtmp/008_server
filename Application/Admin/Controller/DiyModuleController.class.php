<?php
/**
 * 样式自定义-功能模块
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class DiyModuleController extends AuthController
{
    public function index()
    {
        //获取功能模块列表
        $DiyModule=new \Common\Model\DiyModuleModel();
        $list=$DiyModule->getModuleList();
        $this->assign('list',$list);
        
        $this->display();
    }
    
    //添加功能模块
    public function add()
    {
        if($_POST){
            layout(false);
            if(trim(I('post.name'))){
                //上传文件
                if(!empty($_FILES['img']['name']))
                {
                    $config = array(
                        'mimes'         =>  array(), //允许上传的文件MiMe类型
                        'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
                        'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
                        'rootPath'      =>  './Public/Upload/Diy/module/', //保存根路径
                        'savePath'      =>  '', //保存路径
                        'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
                        'subName'       =>  '', //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
                    );
                    $upload = new \Think\Upload($config);
                    // 上传单个文件
                    $info = $upload->uploadOne($_FILES['img']);
                    if(!$info) {
                        // 上传错误提示错误信息
                        $this->error($upload->getError());
                    }else{
                        // 上传成功
                        // 文件完成路径
                        $filepath=$config['rootPath'].$info['savepath'].$info['savename'];
                        $img=substr($filepath,1);
                    }
                }
                $link = trim(I('post.link'));
                if(trim(I('post.is_link')) == 'N'){
                    $link = null;
                }
                //保存到数据库
                $data=array(
                    'name'=>trim(I('post.name')),
                    'is_link'=>trim(I('post.is_link')),
                    'link'=>$link,
                    'icon'=>$img,
                    'sort'=>trim(I('post.sort')),
                    'is_index_show'=>trim(I('post.is_index_show')),
                );
                $DiyModule=new \Common\Model\DiyModuleModel();
                if(!$DiyModule->create($data)) {
                    // 验证不通过
                    // 删除图片
                    if($filepath){
                        @unlink($filepath);
                    }
                    $this->error($DiyModule->getError());
                }else {
                    // 验证成功
                    $res_add=$DiyModule->add($data);
                    if($res_add!==false) {
                        $this->success('新增成功！',U('index'));
                    }else {
                        //删除图片
                        if($filepath){
                            @unlink($filepath);
                        }
                        $this->error('操作失败！');
                    }
                }
            }else {
                $this->error('功能模块名称不能为空！');
            }
        }else {
            $this->display();
        }
    }
    
    //编辑功能模块
    public function edit($id)
    {
        //获取功能模块信息
        $DiyModule=new \Common\Model\DiyModuleModel();
        $msg=$DiyModule->getModuleMsg($id);
        
        if($_POST){
            layout(false);
            if(trim(I('post.name'))){
                //上传文件
                if(!empty($_FILES['img']['name']))
                {
                    $config = array(
                        'mimes'         =>  array(), //允许上传的文件MiMe类型
                        'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
                        'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
                        'rootPath'      =>  './Public/Upload/Diy/module/', //保存根路径
                        'savePath'      =>  '', //保存路径
                        'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
                        'subName'       =>  '', //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
                    );
                    $upload = new \Think\Upload($config);
                    // 上传单个文件
                    $info = $upload->uploadOne($_FILES['img']);
                    if(!$info) {
                        // 上传错误提示错误信息
                        $this->error($upload->getError());
                    }else{
                        // 上传成功
                        // 文件完成路径
                        $filepath=$config['rootPath'].$info['savepath'].$info['savename'];
                        $img=substr($filepath,1);
                    }
                }else {
                    $img=$msg['icon'];
                }
                $link = trim(I('post.link'));
                if(trim(I('post.is_link')) == 'N'){
                    $link = null;
                }
                //保存到数据库
                $data=array(
                    'name'=>trim(I('post.name')),
                    'is_link'=>trim(I('post.is_link')),
                    'link'=>$link,
                    'icon'=>$img,
                    'sort'=>trim(I('post.sort')),
                    'is_index_show'=>trim(I('post.is_index_show')),
                );
                if(!$DiyModule->create($data)) {
                    // 验证不通过
                    // 删除图片
                    if($filepath){
                        @unlink($filepath);
                    }
                    $this->error($DiyModule->getError());
                }else {
                    // 验证成功
                    $res_add=$DiyModule->where("id=$id")->save($data);
                    if($res_add!==false) {
                        // 原图片存在，并且上传了新图片的情况下，删除原标题图片
                        if($msg['icon'] and $img!=$msg['icon']) {
                            $old_icon='.'.$msg['icon'];
                            @unlink($old_icon);
                        }
                        $this->success('编辑成功！',U('index'));
                    }else {
                        //删除图片
                        if($filepath){
                            @unlink($filepath);
                        }
                        $this->error('操作失败！');
                    }
                }
            }else {
                $this->error('功能模块名称不能为空！');
            }
        }else {
            $this->assign('msg',$msg);
            
            $this->display();
        }
    }
    
    //修改首页显示状态
    public function changeIndexShow($id,$status)
    {
        $data=array(
            'is_index_show'=>$status
        );
        $DiyModule=new \Common\Model\DiyModuleModel();
        if(!$DiyModule->create($data)) {
            // 验证不通过
            echo '0';
        }else {
            // 验证成功
            $res=$DiyModule->where("id=$id")->save($data);
            if($res===false) {
                echo '0';
            }else {
                echo '1';
            }
        }
    }

    //修改是否外链接
    public function changeIsLink($id,$status)
    {
        $data=array(
            'is_link'=>$status
        );
        $DiyModule=new \Common\Model\DiyModuleModel();
        if(!$DiyModule->create($data)) {
            // 验证不通过
            echo '0';
        }else {
            // 验证成功
            $res=$DiyModule->where("id=$id")->save($data);
            if($res===false) {
                echo '0';
            }else {
                echo '1';
            }
        }
    }
    
    //批量修改排序
    public function changesort()
    {
        $sort_array=I('post.sort');
        $ids = implode(',', array_keys($sort_array));
        $sql = "UPDATE __PREFIX__diy_module SET sort = CASE id ";
        foreach ($sort_array as $id => $sort) {
            $sql .= sprintf("WHEN %d THEN %d ", $id, $sort);
        }
        $sql.= "END WHERE id IN ($ids)";
        $res = M()->execute($sql);
        layout(false);
        if($res===false) {
            $this->error('操作失败!');
        }else {
            $this->success('排序成功!');
        }
    }
    
    //更新设置功能模块
    public function set()
    {
        layout(false);
        $DiyModule=new \Common\Model\DiyModuleModel();
        $moduleList=$DiyModule->getModuleList('Y');
        if($moduleList!==false) {
            //设置缓存
            //不设置过期时间
            S('diy_moduleList',$moduleList,array('type'=>'file','expire'=>0));
            $this->success('更新成功！');
        }else {
            $this->error('设置失败！');
        }
    }
}