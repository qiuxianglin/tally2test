<?php
/**
 * 用户组管理
 * 2016-11-22
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class UserGroupController extends AuthController
{
    public function index()
    {
    	//获取用户组列表
    	$group=new \Common\Model\UserGroupModel();
    	$grouplist=$group->getUserGroupList();
    	$this->assign('glist',$grouplist);
        $this->display();
    }
    
    //新增用户组
    public function addgroup()
    {
    	//获取权限列表
    	$auth_rule = new \Common\Model\UserAuthRuleModel();
    	$rule_arr=$auth_rule->getRuleList();
    	$this->assign('rlist',$rule_arr);
    	if(I('post.'))
    	{
    		layout(false);
    		$rule_array=I('post.rules');
    		$rules = implode(',', array_values($rule_array));
    		$data=array(
    				'title'=>trim(I('post.title')),
    				'status'=>I('post.status'),
    				'rules'=>$rules
    		);
    		$group = new \Common\Model\UserGroupModel();
    		if(!$group->create($data))
    		{
    			// 验证不通过，返回错误信息
    			$this->error($group->getError());
    		}else {
    			// 验证成功
    			$res=$group->add($data);
    			if ($res!==false)
    			{
    				$this->success('新增用户组成功！',U('index'),3);
    			}else {
    				$this->error('操作失败！');
    			}
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //编辑用户组
    public function editgroup($group_id)
    {
    	//获取管理员组信息
    	$group=new \Common\Model\UserGroupModel();
    	$gMsg=$group->getUserGroupMsg($group_id);
    	$this->assign('msg',$gMsg);
    	//获取权限列表
    	$auth_rule=new \Common\Model\UserAuthRuleModel();
    	$rule_arr=$auth_rule->getRuleList();
    	$this->assign('rlist',$rule_arr);
    	if(I('post.'))
    	{
    		layout(false);
    		$rule_array=I('post.rules');
    		$rules = implode(',', array_values($rule_array));
    		$data=array(
    				'title'=>trim(I('post.title')),
    				'status'=>I('post.status'),
    				'rules'=>$rules
    		);
    		if(!$group->create($data))
    		{
    			// 验证不通过，返回错误信息
    			$this->error($group->getError());
    		}else {
    			// 验证成功
    			$res=$group->where("id='$group_id'")->save($data);
    			if($res===false)
    			{
    				$this->error('操作失败!');
    			}else {
    				$this->success('编辑成功!',U('index'),3);
    			}
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //修改分组状态
    public function changestatus($id,$status)
    {
    	$data=array(
    			'status'=>$status
    	);
    	$group=new \Common\Model\UserGroupModel();
    	if(!$group->create($data))
    	{
    		// 验证不通过，返回错误信息
    		// $this->error($group->getError());
    		echo '0';
    	}else {
    		// 验证成功
    		$res=$group->where("id=$id")->save($data);
    		if($res===false)
    		{
    			echo '0';
    		}else {
    			echo '1';
    		}
    	}
    }
}