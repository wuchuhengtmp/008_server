<?php
/**
 * 拉新活动管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;

class RookieController extends AuthController
{
    // 拉新活动
    public function index()
    {
        // 实例化Model类
        $Rookie = new \Common\Model\RookieModel();
        $Page = new \Common\Model\PageModel();

        // 搜索条件
        if (I('GET.search')) {
            $search = I('GET.search');
            $where['name'] = ['like', "%$search%"];
            // 搜索条件返还给模板
            $this->assign('search', $search);
        }

        // 查询数据条数
        $count = $Rookie->where($where)->count();

        // 分页显示输出
        $per = 15;
        if ($_GET['p']) {
            $p = $_GET['p'];
        } else {
            $p = 1;
        }
        $page = $Page->show($count,$per);

        // 查询数据
        $data = $Rookie->where($where)->page($p.','.$per)->select();

        // 模板渲染
        $this->assign('data', $data);
        $this->assign('page', $page);
        $this->display();
    }

    // 增加拉新活动
    public function add()
    {
        // 实例化Model类
        $Rookie = new \Common\Model\RookieModel();

        if (I('POST.')) {
            layout(false);

            // 新增内容
            $content = $_POST['content'];
            // 转移ueditor文件：将file和img从ueditor_tmp文件夹中转移到正式目录ueditor中
            if(!empty($content))
            {
                $ueditor = new \Admin\Common\Controller\UeditorController;
                $content = $ueditor->add($content);
            }

            // 转成标准时间格式
            $start_time = str_replace('T', ' ', I('POST.start_time'));
            $end_time = str_replace('T', ' ', I('POST.end_time'));
            $exs_time = str_replace('T', ' ', I('POST.exs_time'));
            $exe_time = str_replace('T', ' ', I('POST.exe_time'));
            /*
            $start_time = $start_time.':00';
            $end_time = $end_time.':00';
            $exs_time = $exs_time.':00';
            $exe_time = $exe_time.':00';
            */
            //echo $start_time;exit;
            // 组装数据
            $data = [
                'name'          => I('POST.name'),
                'start_time'    => $start_time,
                'end_time'      => $end_time,
                'lv_num'        => I('POST.lv_num'),
                'add_time'      => date("Y-m-d H:i:s"),
                'exs_time'      => $exs_time,
                'exe_time'      => $exe_time,
                'ex_type'       => I('POST.ex_type'),
                'content'       => $content,
            ];
            // 数据验证
            if (!$Rookie->create($data)) {
                // 删除内容中的图片和文件
                if (!empty($content)) {
                    $ueditor=new \Admin\Common\Controller\UeditorController;
                    $ueditor->del($content);
                }

                $this->error($Rookie->getError());
            }

            // 入库
            $res = $Rookie->add($data);
            if ($res) {
                $this->success('添加成功',U('index'));
            } else {
                $this->error('系统出错，请重试');
            }

        } else {
            $this->display();
        }
    }

    //  修改拉新活动
    public function edit($id)
    {
        // 实例化Model类
        $Rookie = new \Common\Model\RookieModel();
        $RookieDetails = new \Common\Model\RookieDetailsModel();

        // 查询数据
        $data = $Rookie->where("id = $id")->find();

        if (I('POST.')) {
            layout(false);

            //编辑内容
            $content = $_POST['content'];
            //先上传新的内容，再删除原有内容中被删除的文件
            if (! empty ( $content ) || !empty($data['content']))
            {
                $ueditor = new \Admin\Common\Controller\UeditorController;
                $content = $ueditor->edit($content,$data['content']);
            }

            // 删除已经设置的奖励
            $old_num = $Rookie->where("id = $id")->getField('lv_num');
            if ($old_num != I('POST.lv_num')) {
                $RookieDetails->where("rid = $id")->delete();
            }

            // 转成标准时间格式
            $start_time = str_replace('T', ' ', I('POST.start_time'));
            $end_time = str_replace('T', ' ', I('POST.end_time'));
            $exs_time = str_replace('T', ' ', I('POST.exs_time'));
            $exe_time = str_replace('T', ' ', I('POST.exe_time'));
            $start_time = $start_time.':00';
            $end_time = $end_time.':00';
            $exs_time = $exs_time.':00';
            $exe_time = $exe_time.':00';

            // 组装数据
            $data = [
                'name'          => I('POST.name'),
                'start_time'    => $start_time,
                'end_time'      => $end_time,
                'lv_num'        => I('POST.lv_num'),
                'exs_time'      => $exs_time,
                'exe_time'      => $exe_time,
                'ex_type'       => I('POST.ex_type'),
                'content'       => $content,
            ];

            // 入库
            $res = $Rookie->where("id = $id")->save($data);
            if ($res) {
                $this->success('修改成功',U('index'));
            } else {
                $this->error('系统出错，请重试');
            }

        } else {

            // 时间空格转T
            $data['start_time'] = str_replace(" ", "T", $data['start_time']);
            $data['end_time'] = str_replace(" ", "T", $data['end_time']);
            $data['exs_time'] = str_replace(" ", "T", $data['exs_time']);
            $data['exe_time'] = str_replace(" ", "T", $data['exe_time']);

            // 富文本内容html转实体
            $data['content'] = htmlspecialchars_decode(html_entity_decode($data['content']));
            $data['content'] = str_replace("&#39;", '"', $data['content']);

            // 模板渲染
            $this->assign('data', $data);
            $this->assign('id', $id);
            $this->display();
        }
    }

    //  删除拉新活动
    public function del()
    {
        // 实例化Model类
        $Rookie = new \Common\Model\RookieModel();

        layout(false);

        // 删除
        $id = I('POST.id');
        // 删除富文本中的内容
        $content = $Rookie->where("id = $id")->getField('content');
        if (!empty($content)) {
            $ueditor=new \Admin\Common\Controller\UeditorController;
            $ueditor->del($content);
        }

        $res = $Rookie->where("id = $id")->delete();

        if ($res) {
            echo 1;
        } else {
            echo 0;
        }


    }

    // 奖励设置
    public function reward($id)
    {
        // 实例化Model类
        $Rookie = new \Common\Model\RookieModel();
        $RookieDetails = new \Common\Model\RookieDetailsModel();

        // 查询等级个数
        $lv_num = $Rookie->where("id = $id")->getField('lv_num');

        if ($_POST) {
            layout(false);
            // 开启事务
            $RookieDetails->startTrans();
            // 删除已经添加过的数据
            $res = $RookieDetails->where("rid = $id")->delete();
            if ($res !== false) {
                // 循环组装数据
                for ($i=1; $i < $lv_num+1; $i++) {
                    $data[] = [
                        "rid"               => $id,
                        "lv"                => I("POST.lv$i"),
                        "start_interval"    => I("POST.start_interval$i"),
                        "end_interval"      => I("POST.end_interval$i"),
                        "reward_type"       => I("POST.reward_type$i"),
                        "reward_num"        => I("POST.reward_num$i"),
                    ];
                }
                // 入库
                $res = $RookieDetails->addAll($data);
                if ($res) {
                    // 数据提交
                    $RookieDetails->commit();
                    $this->success("添加成功", U('index'));
                } else {
                    // 数据回滚
                    $RookieDetails->rollback();
                    $this->error("添加失败，请重试");
                }
            } else {
                // 数据回滚
                $RookieDetails->rollback();
                $this->error("添加失败，请重试");
            }
        } else {
            $this->assign('id',$id);
            
            // 查询数据,需要按照等级升序查询
            $data = $RookieDetails->where("rid = $id")->order("lv")->select();
            // 如果已经添加过，则输出值
            if ($data) {
                $this->assign('data', $data);
            }
            // 模板渲染
            // print_r($data);die;
            $this->assign('lv_num', $lv_num);
            $this->display();
        }
    }
}
