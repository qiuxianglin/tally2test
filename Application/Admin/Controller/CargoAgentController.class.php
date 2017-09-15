<?php
/**
 * 货代信息维护
 * 2016-11-21
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;

class CargoAgentController extends AuthController
{
    public function index()
    {
        //获取货代列表
        $CargoAgent=new \Common\Model\CargoAgentModel();
        $where="1";
        if(I('get.code'))
        {
            $code=I('get.code');
            $code = str_replace("'", "", $code);
            $where.=" and code='$code'";
        }
        if(I('get.name'))
        {
            $name=I('get.name');
            $name = str_replace("'", "", $name);
            $where.=" and name like'%$name%'";
        }
        $count=$CargoAgent->where($where)->count();
        $per = 15;
        if($_GET['p'])
        {
            $p=$_GET['p'];
        }else {
            $p=1;
        }
        $Page= new \Think\Page($count,$per);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->rollPage=10; // 分页栏每页显示的页数
        $Page -> setConfig('header','共%TOTAL_ROW%条');
        $Page -> setConfig('first','首页');
        $Page -> setConfig('last','共%TOTAL_PAGE%页');
        $Page -> setConfig('prev','上一页');
        $Page -> setConfig('next','下一页');
        $Page -> setConfig('link','indexpagenumb');//pagenumb 会替换成页码
        $Page -> setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% 第 '.I('p',1).' 页/共 %TOTAL_PAGE% 页 (<font color="red">'.$per.'</font> 条/页 共 %TOTAL_ROW% 条)');
        $show= $Page->show();// 分页显示输出
         
        $list = $CargoAgent->where($where)->page($p.','.$per)->order('id desc')->select();
        $this->assign('CargoAgentList',$list);
        $this->assign('page',$show);
        $this->display();
    }
    
    //新增货代
    public function add()
    {
        $CargoAgent=new \Common\Model\CargoAgentModel();
        if(I('post.'))
        {
            layout(false);
            $code=strtoupper(trim(I('post.code'),"'"));
            //判断货代代码唯一性
            $res_c=$CargoAgent->where("code='$code'")->field('id')->find();
            if($res_c['id']!='')
            {
                $this->error('X货代代码不能重复，确保唯一！');
            }
            $data = array(
            		'code'=>$code,
            		'name'=>trim(I('post.name'),"'"),
            		'name_en'=>trim(I('post.name_en'),"'"),
            		'contacter'=>trim(I('post.contacter')),
            		'telephone'=>trim(I('post.telephone')),
            		'remark'=>trim(I('post.remark'))
            );
            if(!$CargoAgent->create($data))
            {
            	//对data数据进行验证
            	$this->error($CargoAgent->getError());
            }else{
            	//验证通过 可以对数据进行处理
            	$res=$CargoAgent->add($data);
            	if ($res)
            	{
            		$this->success('新增货代成功！',U('index'));
            	}else {
            		$this->error('X操作失败！');
            	}
            }
        }else {
            $this->display();
        }
    }
    
    //编辑货代
    public function edit($id)
    {
        $CargoAgent=new \Common\Model\CargoAgentModel();
        //获取货代信息
        $msg=$CargoAgent->getCargoAgentMsg($id);
        $this->assign('msg',$msg);
        if(I('post.'))
        {
            layout(false);
            $code=strtoupper(trim(I('post.code'),"'"));
            //判断货代代码唯一性
            $res_c=$CargoAgent->where("code='$code' and id!=$id")->field('id')->find();
            if($res_c['id']!='')
            {
                $this->error('X货代代码不能重复，确保唯一！');
            }
            $data = array(
            		'code'=>$code,
            		'name'=>trim(I('post.name'),"'"),
            		'name_en'=>trim(I('post.name_en'),"'"),
            		'contacter'=>trim(I('post.contacter')),
            		'telephone'=>trim(I('post.telephone')),
            		'remark'=>trim(I('post.remark'))
            );
            if(!$CargoAgent->create($data)){
            	//对data数据进行验证
            	$this->error($CargoAgent->getError());
            }else{
            	//验证通过 可以对数据进行操作
            	$res=$CargoAgent->where("id=$id")->save($data);
            	if ($res)
            	{
            		$this->success('编辑货代成功！',U('index'));
            	}else {
            		$this->error('X操作失败！');
            	}
            }
        }else {
            $this->display();
        }
    }
}
?>