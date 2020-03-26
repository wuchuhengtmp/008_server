<?php
/**
 * 拉新活动提现记录
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;

class RookieRecordController extends AuthController
{
    public function index($id)
    {
        // 实例化Model类
        $Page = new \Common\Model\PageModel();
        $Rookie = new \Common\Model\RookieModel();
        $RookieUser = new \Common\Model\RookieUserModel();

        // 搜索条件
        if (I('GET.search')) {
            $search = I('GET.search');
            $where['u.username'] = ['like', "%$search%"];
            // 搜索条件返还给模板
            $this->assign('search', $search);
        }
        if (I('GET.is_ex')) {
            $is_ex = I('GET.is_ex');
            $where['r.is_ex'] = ['eq', "$is_ex"];
            // 搜索条件返还给模板
            $this->assign('is_ex', $is_ex);
        }
        // 查询数据条数
        $count = $RookieUser->table("dmooo_rookie_user r")
                ->join("__USER__ u on r.user_id = u.uid")
                ->field('u.username,r.exchange,r.num,r.is_ex,r.rid')
                ->where($where)->count();

        // 分页显示输出
        $per = 15;
        if ($_GET['p']) {
            $p = $_GET['p'];
        } else {
            $p = 1;
        }
        $page = $Page->show($count,$per);

        // 查询数据
        $data = $RookieUser->table("dmooo_rookie_user r")
                ->join("__USER__ u on r.user_id = u.uid")
                ->field('u.username,r.exchange,r.num,r.is_ex,r.rid')
                ->where($where)->page($p.','.$per)->select();

        // 查询活动名称
        $rname = $Rookie->where("id = $id")->getField('name');

        // 模板渲染
        $this->assign('id', $id);
        $this->assign('rname', $rname);
        $this->assign('data', $data);
        $this->assign('page', $page);
        $this->display();
    }
}
