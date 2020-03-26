<?php
/**
 * 热门搜索设置
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class HotSearchController extends AuthController
{
    public function index()
    {
        $where='1';
        //关键词
        if(trim(I('get.search'))) {
            $search=trim(I('get.search'));
            $where.=" and search like '%$search%'";
        }
        $HotSearch=new \Common\Model\HotSearchModel();
        $count=$HotSearch->where($where)->count();
        $per = 15;
        if($_GET['p'])
        {
            $p=$_GET['p'];
        }else {
            $p=1;
        }
        $Page=new \Common\Model\PageModel();
        $show= $Page->show($count,$per);// 分页显示输出
        $this->assign('page',$show);
        
        $list = $HotSearch->where($where)->page($p.','.$per)->order('num desc,id asc')->select();
        $this->assign('list',$list);
        
        $this->display();
    }
    
    //添加热门搜索
    public function add()
    {
        if(I('post.')){
            layout(false);
            if( trim(I('post.search')) and trim(I('post.num')) ) {
                $data=array(
                    'search'=>trim(I('post.search')),
                    'num'=>trim(I('post.num')),
                    'type'=>trim(I('post.type')),
                );
                $HotSearch=new \Common\Model\HotSearchModel();
                if(!$HotSearch->create($data)){
                    //验证不通过
                    $this->error($HotSearch->getError());
                }else {
                    //验证通过
                    $res_add=$HotSearch->add($data);
                    if($res_add!==false){
                        $this->success('添加热门搜索成功！',U('index'));
                    }else {
                        $this->error('添加热门搜索失败！');
                    }
                }
            }else {
                $this->error('搜索关键词、搜索次数不能为空！');
            }
        }else {
            $this->display();
        }
    }
    
    //编辑热门搜索
    public function edit($id)
    {
        $HotSearch=new \Common\Model\HotSearchModel();
        if(I('post.')){
            layout(false);
            if( trim(I('post.search')) and trim(I('post.num')) ) {
                $data=array(
                    'search'=>trim(I('post.search')),
                    'num'=>trim(I('post.num')),
                    'type'=>trim(I('post.type')),
                );
                if(!$HotSearch->create($data)){
                    //验证不通过
                    $this->error($HotSearch->getError());
                }else {
                    //验证通过
                    $res_add=$HotSearch->where("id=$id")->save($data);
                    if($res_add!==false){
                        $this->success('编辑热门搜索成功！',U('index'));
                    }else {
                        $this->error('编辑热门搜索失败！');
                    }
                }
            }else {
                $this->error('搜索关键词、搜索次数不能为空！');
            }
        }else {
            //获取热门搜索信息
            $msg=$HotSearch->getMsg($id);
            $this->assign('msg',$msg);
            
            $this->display();
        }
    }
    
    //删除热门搜索
    public function del($id)
    {
        $HotSearch=new \Common\Model\HotSearchModel();
        $res_del=$HotSearch->where("id=$id")->delete();
        if($res_del!==false){
            echo '1';
        }else {
            echo '0';
        }
    }
    
    //批量删除热门搜索
    public function batchdel($all_id)
    {
        $all_id=substr($all_id,0,-1);
        if($all_id){
            $HotSearch=new \Common\Model\HotSearchModel();
            $res_del=$HotSearch->where("id in ($all_id)")->delete();
            if($res_del!==false)
            {
                echo '1';
            }else {
                echo '0';
            }
        }else {
            echo '0';
        }
    }
}