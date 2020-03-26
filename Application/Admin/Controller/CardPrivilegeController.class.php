<?php
/**
 * 商城系统-商品分类管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class CardPrivilegeController extends AuthController
{
    public function index()
    {
    //分类名称
    $CardCat=new \Common\Model\CardCatModel();
    $catlist=$CardCat->getCardCatList();
    $thecate = [];
    foreach ($catlist as $k=>$cat){
        $idx = $cat['id'];
        $thecate[$idx] = $cat['category_name'];
    }
    $this->assign('catlist',$catlist);

    $where="is_delete='N'";
    if(trim(I('get.search')))
    {
        $search=I('get.search');
        $where.=" and title like '%$search%'";
    }

    if(trim(I('get.cate_id'))){
        $cate_id = intval(I('get.cate_id'));
        $this->assign('cate_id',$cate_id);
        $where.=" and cate_id = '$cate_id'";
    }

    $Goods=new \Common\Model\CardPrivilegeModel();
    $count=$Goods->where($where)->count();
    $per = 15;
    if($_GET['p'])
    {
        $p=$_GET['p'];
    }else {
        $p=1;
    }
    // 分页显示输出
    $Page=new \Common\Model\PageModel();
    $show= $Page->show($count,$per);
    $this->assign('page',$show);

    $goodslist = $Goods->where($where)->page($p.','.$per)->order('sort desc,id desc')->select();
    foreach ($goodslist as $k=>$goods){
        $cat_id = $goods['cate_id'];
        $goodslist[$k]['cate_name'] = $thecate[$cat_id];
    }
    $this->assign('goodslist',$goodslist);
    $this->display();
}

    //添加商品
    public function add()
    {
        if(I('post.')) {
            layout(false);
            if(trim(I('post.title'))) {
                //上传标题图片
                if(!empty($_FILES['logo']['name'])) {
                    $config = array(
                        'mimes'         =>  array(), //允许上传的文件MiMe类型
                        'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
                        'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
                        'rootPath'      =>  './Public/Upload/Goods/', //保存根路径
                        'savePath'      =>  '', //保存路径
                        'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
                    );
                    $upload = new \Think\Upload($config);
                    // 上传单个文件
                    $info = $upload->uploadOne($_FILES['logo']);
                    if(!$info) {
                        // 上传错误提示错误信息
                        $this->error($upload->getError());
                    }else{
                        // 上传成功
                        // 文件完成路径
                        $filepath=$config['rootPath'].$info['savepath'].$info['savename'];
                        $logo=substr($filepath,1);
                    }
                }else {
                    $logo='';
                    $tmp_img='';
                }

                //保存到数据库
                $data=array(
                    'cate_id'=>I('post.cate_id'),
                    'title'=>trim(I('post.title')),
                    'sub_title'=>trim(I('post.sub_title')),
                    'logo'=>$logo,
                    'url'=>trim(I('post.url')),
                    'status'=>I('post.status'),
                    'tequan'=>I('post.tequan'),
                    'sort'=>intval(I('post.sort')),
                    'addtime'=>time(),
                    'is_delete'=>'N'
                );

                $Goods=new \Common\Model\CardPrivilegeModel();
                if(!$Goods->create($data)) {
                    // 验证不通过
                    // 删除图片
                    if($filepath) {
                        @unlink($filepath);
                    }
                    $this->error($Goods->getError());
                }else {
                    // 验证成功
                    $res_add=$Goods->add($data);
                    if($res_add!==false) {
                        $this->success('新增商品成功！',U('index'));
                    }else {
                        // 删除图片
                        if($filepath) {
                            @unlink($filepath);
                        }
                        $this->error('新增商品失败！');
                    }
                }
            }else {
                $this->error('商品名称不能为空！');
            }
        }else {
            //获取商品分类列表
            $GoodsCat=new \Common\Model\CardCatModel();
            $catlist=$GoodsCat->getCardCatList();
            $this->assign('catlist',$catlist);

            $this->display();
        }
    }

    //编辑商品
    public function edit($id)
    {
        //获取商品信息
        $Goods=new \Common\Model\CardPrivilegeModel();
        $msg=$Goods->getGoodsMsg($id);
        if(I('post.')) {
            layout(false);
            if(trim(I('post.title'))) {
                //上传标题图片
                if(!empty($_FILES['logo']['name'])) {
                    $config = array(
                        'mimes'         =>  array(), //允许上传的文件MiMe类型
                        'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
                        'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
                        'rootPath'      =>  './Public/Upload/Goods/', //保存根路径
                        'savePath'      =>  '', //保存路径
                        'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
                    );
                    $upload = new \Think\Upload($config);
                    // 上传单个文件
                    $info = $upload->uploadOne($_FILES['logo']);
                    if(!$info) {
                        // 上传错误提示错误信息
                        $this->error($upload->getError());
                    }else{
                        // 上传成功
                        // 文件完成路径
                        $filepath=$config['rootPath'].$info['savepath'].$info['savename'];
                        $logo=substr($filepath,1);
                    }
                }else {
                    $logo='';
                    $tmp_img='';
                }

                //保存到数据库
                $data=array(
                    'cate_id'=>I('post.cate_id'),
                    'title'=>trim(I('post.title')),
                    'sub_title'=>trim(I('post.sub_title')),
                    'url'=>trim(I('post.url')),
                    'status'=>I('post.status'),
                    'tequan'=>I('post.tequan'),
                    'sort'=>intval(I('post.sort')),
                    'addtime'=>time(),
                    'is_delete'=>'N'
                );
                if(!empty($logo)){
                    $data['logo'] = $logo;
                }

                $Goods=new \Common\Model\CardPrivilegeModel();
                if(!$Goods->create($data)) {
                    // 验证不通过
                    // 删除图片
                    if($filepath) {
                        @unlink($filepath);
                    }
                    $this->error($Goods->getError());
                }else {
                    // 验证成功
                    $res_add=$Goods->where("id = '$id'")->save($data);
                    if($res_add!==false) {
                        $this->success('新增商品成功！',U('index'));
                    }else {
                        // 删除图片
                        if($filepath) {
                            @unlink($filepath);
                        }
                        $this->error('新增商品失败！');
                    }
                }
            }else {
                $this->error('商品名称不能为空！');
            }
        }else {
            $this->assign('msg',$msg);

            $GoodsCat=new \Common\Model\CardCatModel();
            $catlist=$GoodsCat->getCardCatList();
            $this->assign('catlist',$catlist);

            $this->display();
        }
    }

    //删除商品
    public function del($id)
    {
        //只做逻辑删除，不做物理删除
        $Goods=new \Common\Model\CardPrivilegeModel();
        $data=array(
            'status'=>'N',
            'is_delete'=>'Y'
        );
        $res=$Goods->where("id='$id'")->save($data);
        if($res!==false)
        {
            echo '1';
        }else {
            echo '0';
        }
    }

    //彻底删除商品
    public function del2($goods_id)
    {
        //删除商品
        $Goods=new \Common\Model\GoodsModel();
        $res=$Goods->del($goods_id);
        if($res!==false)
        {
            echo '1';
        }else {
            echo '0';
        }
    }

    //批量转移商品
    public function transfer($all_id,$cat_id)
    {
        $all_id=substr($all_id,0,-1);
        $update="UPDATE __PREFIX__card_privilege SET cate_id=$cat_id WHERE id in($all_id)";
        $res=M()->execute($update);
        if($res!==false)
        {
            echo '1';
        }else {
            echo '0';
        }
    }

    //修改商品上下架状态
    public function changeshow($id,$status)
    {
        $data=array(
            'is_show'=>$status
        );
        $Goods=new \Common\Model\GoodsModel();
        if(!$Goods->create($data))
        {
            // 验证不通过
            echo '0';
        }else {
            // 验证成功
            $res=$Goods->where("goods_id=$id")->save($data);
            if($res===false)
            {
                echo '0';
            }else {
                echo '1';
            }
        }
    }

    //修改商品推荐状态
    public function changetop($id,$status)
    {
        $data=array(
            'tequan'=>$status
        );
        $Goods=new \Common\Model\CardPrivilegeModel();
        if(!$Goods->create($data))
        {
            // 验证不通过
            echo '0';
        }else {
            // 验证成功
            $res=$Goods->where("id=$id")->save($data);
            if($res===false)
            {
                echo '0';
            }else {
                echo '1';
            }
        }
    }

    //修改商品特价状态
    public function changesale($id,$status)
    {
        $data=array(
            'status'=>$status
        );
        $Goods=new \Common\Model\CardPrivilegeModel();
        if(!$Goods->create($data))
        {
            // 验证不通过
            echo '0';
        }else {
            // 验证成功
            $res=$Goods->where("id=$id")->save($data);
            if($res===false)
            {
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
        $sql = "UPDATE __PREFIX__card_privilege SET sort = CASE id ";
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

    //删除原视频
    public function deloldvideo($goods_id)
    {
        $Goods=new \Common\Model\GoodsModel();
        $msg=$Goods->getGoodsMsg($goods_id);
        if($msg===false) {
            echo '0';
        }else {
            $oldvideo=$msg['video'];
            //修改video为空
            $data=array(
                'video'=>''
            );
            $res_save=$Goods->where("goods_id=$goods_id")->save($data);
            if($res_save!==false) {
                $oldvideo='.'.$oldvideo;
                @unlink($oldvideo);
                echo '1';
            }else {
                echo '0';
            }
        }
    }

    //商品回收站
    public function recycle()
    {
        if(I('get.search'))
        {
            $search=I('get.search');
            $where="goods_name like '%$search%' and is_delete='Y'";
        }else {
            $where="is_delete='Y'";
        }
        $Goods=new \Common\Model\GoodsModel();
        $count=$Goods->where($where)->count();
        $per = 15;
        if($_GET['p'])
        {
            $p=$_GET['p'];
        }else {
            $p=1;
        }
        // 分页显示输出
        $Page=new \Common\Model\PageModel();
        $show= $Page->show($count,$per);
        $this->assign('page',$show);

        $goodslist = $Goods->where($where)->page($p.','.$per)->order('is_top desc,sort desc,goods_id desc')->select();
        $this->assign('goodslist',$goodslist);
        $this->display();
    }

    //回收站商品详情
    public function recycleMsg($goods_id)
    {
        //获取商品分类列表
        $GoodsCat=new \Common\Model\GoodsCatModel();
        $catlist=$GoodsCat->getCatList();
        $this->assign('catlist',$catlist);
        //获取商品信息
        $Goods=new \Common\Model\GoodsModel();
        $msg=$Goods->getGoodsMsg($goods_id);
        $this->assign('msg',$msg);
        $this->display();
    }

    //恢复商品
    public function restore($id)
    {
        $data=array(
            'is_delete'=>'N',
            'is_show'=>'Y'
        );
        $Goods=new \Common\Model\GoodsModel();
        if(!$Goods->create($data))
        {
            // 验证不通过
            echo '0';
        }else {
            // 验证成功
            $res=$Goods->where("goods_id=$id")->save($data);
            if($res===false)
            {
                echo '0';
            }else {
                echo '1';
            }
        }
    }

    //获取商品列表
    public function getGoodsList($cat_id)
    {
        $Goods=new \Common\Model\GoodsModel();
        $list=$Goods->where("cat_id='$cat_id' and is_show='Y' and is_delete='N'")->select();
        if($list!==false)
        {
            echo json_encode($list);
        }else {
            echo '0';
        }
    }
}