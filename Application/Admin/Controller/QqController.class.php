<?php
/**
 * 其他管理-QQ客服管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class QqController extends AuthController
{
    public function index()
    {
    	//获取分类列表
    	$qq=new \Common\Model\QqModel();
    	$qlist=$qq->getQqList();
    	$this->assign('qlist',$qlist);
    	//获取客服QQ样式配置
    	require_once './Public/inc/config.php';
    	$this->assign('qq_css',QQ_CSS);
    	$this->assign('contact_phone',CONTACT_PHONE);
        $this->display();
    }
    
    //添加客服QQ
    public function add()
    {
    	if(I('post.'))
    	{
    		layout(false);
    		$data=array(
    				'title'=>trim(I('post.title')),
    				'num'=>trim(I('post.num')),
    				'sort'=>trim(I('post.sort'))
    		);
    		$qq=new \Common\Model\QqModel();
    		if(!$qq->create($data))
    		{
    			// 如果创建失败 表示验证没有通过 输出错误提示信息
    			$this->error($qq->getError());
    		}else {
    			// 验证成功
    			$res=$qq->add($data);
    			if($res!==false)
    			{
    				$this->success('添加成功！');
    			}else {
    				$this->error('操作失败！');
    			}
    		}
    	}
    }
    
    //编辑客服QQ
    public function edit($id)
    {
    	//获取分类列表
    	$qq=new \Common\Model\QqModel();
    	$qlist=$qq->getQqList();
    	$this->assign('qlist',$qlist);
    	//根据ID获取QQ信息
    	$msg=$qq->getMsg($id);
    	$this->assign('msg',$msg);
    	if(I('post.'))
    	{
    		layout(false);
    		$data=array(
    				'title'=>trim(I('post.title')),
    				'num'=>trim(I('post.num')),
    				'sort'=>trim(I('post.sort'))
    		);
    		if(!$qq->create($data))
    		{
    			// 如果创建失败 表示验证没有通过 输出错误提示信息
    			$this->error($qq->getError());
    		}else {
    			// 验证成功
    			$res=$qq->where("id=$id")->save($data);
    			if($res!==false)
    			{
    				$this->success('编辑成功！',U('index'));
    			}else {
    				$this->error('操作失败！');
    			}
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //删除客服QQ
    public function del($id)
    {
    	$qq=new \Common\Model\QqModel();
    	$res=$qq->where("id='$id'")->delete();
    	if($res!==false)
    	{
    		echo '1';
    	}else {
    		echo '0';
    	}
    }
    
    //批量修改排序
    public function changesort()
    {
    	$sort_array=I('post.sort');
    	$ids = implode(',', array_keys($sort_array));
    	$sql = "UPDATE __PREFIX__qq SET sort = CASE id ";
    	foreach ($sort_array as $id => $sort) {
    		$sql .= sprintf("WHEN %d THEN %d ", $id, $sort);
    	}
    	$sql.= "END WHERE id IN ($ids)";
    	$res = M()->execute($sql);
    	layout(false);
    	if($res===false)
    	{
    		$this->error('操作失败!');
    	}else {
    		$this->success('排序成功!');
    	}
    }
    
    //切换客服QQ样式
    public function changeCss()
    {
    	if(I('post.'))
    	{
    		//QQ
    		$qq_css=I('post.qq_css');
    		$old_qq_css=I('post.old_qq_css');
    		//咨询电话
    		$contact_phone=I('post.contact_phone');
    		$old_contact_phone=I('post.old_contact_phone');
    		
    		//载入系统配置文件
    		$old_str=file_get_contents('./Public/inc/config.php');
    		//替换QQ_CSS
    		$find_str="define('QQ_CSS','$old_qq_css');";
    		$replace_str="define('QQ_CSS','$qq_css');";
    		$str=str_replace($find_str,$replace_str,$old_str);
    		//更换咨询电话
    		$find_str_phone="define('CONTACT_PHONE','$old_contact_phone');";
    		$replace_str_phone="define('CONTACT_PHONE','$contact_phone');";
    		$str=str_replace($find_str_phone,$replace_str_phone,$str);
    		//写入系统配置文件
    		file_put_contents('./Public/inc/config.php',$str);
    		layout(false);
    		$this->success('切换客服QQ样式成功！');
    	}
    }
}