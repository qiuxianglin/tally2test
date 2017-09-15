<?php
/**
 * 用户管理
 * 2016-11-22
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class UserController extends AuthController
{
    public function index()
    {
    	//获取用户组列表
    	$group=new \Common\Model\UserGroupModel();
    	$grouplist=$group->getUserGroupList();
    	$this->assign('glist',$grouplist);
    	//获取部门列表
    	$department = new \Common\Model\DepartmentModel();
    	$deptList=$department->getDepartmentList();
    	$this->assign('deptList',$deptList);
    	$where="1";
    	if(I('get.staffno'))
    	{
    		$staffNo=I('get.staffno');
    		$staffNo = str_replace("'", "", $staffNo);
    		$where.=" and staffno='$staffNo'";
    	}
    	if(I('get.user_name'))
    	{
    		$user_name=I('get.user_name');
    		$user_name = str_replace("'", "", $user_name);
    		$where.=" and user_name like '%$user_name%'";
    	}
    	if(I('get.department_id'))
    	{
    		$department_id=I('get.department_id');
    		//查询一级部门下的二级部门
    		$res_d=$department->where("pid='$department_id'")->field('id')->select();
    		if(!empty($res_d))
    		{
    			foreach($res_d as $de)
    			{
    				$d.=$de['id'].',';
    			}
    			$d=$d.$department_id;
    			$where.=" and department_id in($d)";
    		}else {
    			$where.=" and department_id='$department_id'";
    		}
    	}
    	if(I('get.group_id'))
    	{
    		$group_id=I('get.group_id');
    		$where.=" and group_id='$group_id'";
    	}
    	if(I('get.status')!=='')
    	{
    		$status=I('get.status');
    		$where.=" and user_status='$status'";
    	}
    	$user=new \Common\Model\UserModel();
    	$count=$user->where($where)->count();
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
    	 
    	$userlist = $user->where($where)->order('uid desc')->page($p.','.$per)->select();
    	$this->assign('ulist',$userlist);// 赋值数据集
    	$this->assign('page',$show);
        $this->display();
    }
    
    //新增用户
    public function add()
    {
    	//获取用户组列表
    	$group=new \Common\Model\UserGroupModel();
    	$grouplist=$group->getUserGroupList();
    	$this->assign('glist',$grouplist);
    	//获取部门列表
    	$department = new \Common\Model\DepartmentModel();
    	$deptList=$department->getDepartmentList();
    	$this->assign('deptList',$deptList);
    	if(I('post.'))
		{
			layout(false);
			$code=strtoupper(trim(I('post.staffno'),"'"));
			//判断工号唯一性
			$User=new \Common\Model\UserModel();
			$res_c=$User->where("staffno='$code'")->field('uid')->find();
			if($res_c['id']!='')
			{
				$this->error('X工号不能重复，确保唯一！');
			}
			$admin_id = $_SESSION['admin_id'];
			$admin = new \Admin\Model\AdminModel();
			$res_a = $admin->where("uid='$admin_id'")->field('adminname')->find();
			//对密码进行加密
			$pwd = $User->encrypt(trim(I('post.user_pwd')));
			$data = array(
				    'staffno'=>$code,
					'user_name'=>trim(I('post.user_name'),"'"),
					'user_pwd'=>$pwd,
					'department_id'=>trim(I('post.department_id')),
					'position'=>trim(I('post.position')),
					'group_id'=>trim(I('post.group_id')),
					'user_status'=>I('post.user_status'),
					'operator'=>$res_a['adminname'],
					'operationtime'=>date('Y-m-d H:i:s')
			);
			if(!$User->create($data))
			{
				//对data数据进行验证
				$this->error($User->getError());
			}else{
				//验证通过 可以对数据进行处理
				$res=$User->add($data);
				if ($res!==false)
				{
					$this->success('新增用户成功！',U('index'));
				}else {
					$this->error('X操作失败！');
				}
			}
		}else {
			$this->display();
		}
    }
    
    //编辑用户
    public function edit($id)
    {
    	//获取用户组列表
    	$group=new \Common\Model\UserGroupModel();
    	$grouplist=$group->getUserGroupList();
    	$this->assign('glist',$grouplist);
    	//获取部门列表
    	$department = new \Common\Model\DepartmentModel;
    	$deptList=$department->getDepartmentList();
    	$this->assign('deptList',$deptList);
    	//获取用户信息
    	$User=new \Common\Model\UserModel();
    	$msg=$User->getUserMsg($id);
    	$this->assign('msg',$msg);
    	if(I('post.'))
    	{
    		layout(false);
    		$code=strtoupper(trim(I('post.staffno'),"'"));
    		//判断工号唯一性
    		$res_c=$User->where("staffno='$code' and uid!='$id'")->field('uid')->find();
    		if($res_c['id']!='')
    		{
    			$this->error('X工号不能重复，确保唯一！');
    		}
    		//对密码进行加密
    		$pwd = $User->encrypt(I('post.user_pwd'));
    		$admin_id = $_SESSION['admin_id'];
    		$admin = new \Admin\Model\AdminModel();
    		$res_a = $admin->where("uid='$admin_id'")->field('adminname')->find();
    		$data = array(
    				'staffno'=>$code,
    				'user_name'=>trim(I('post.user_name'),"'"),
    				'user_pwd'=>$pwd,
    				'department_id'=>trim(I('post.department_id')),
    				'position'=>trim(I('post.position')),
    				'group_id'=>trim(I('post.group_id')),
    				'user_status'=>I('post.user_status'),
					'operator'=>$res_a['adminname'],
    				'operationtime'=>date('Y-m-d H:i:s')
    		);
    		if(I('post.user_pwd')!='')
    		{
    			$data['user_pwd']=$User->encrypt(trim(I('post.user_pwd')));
    		}
    		if(!$User->create($data))
    		{
    			//对data数据进行验证
    			$this->error($User->getError());
    		}else{
    			//验证通过 可以对数据进行处理
    			$res=$User->where("uid=$id")->save($data);
    			if ($res)
    			{
    				$this->success('编辑用户成功！',U('index'));
    			}else {
    				$this->error('X操作失败！');
    			}
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //修改用户状态
    public function changestatus($id,$status)
    {
    	$user=new \Common\Model\UserModel();
    	$data=array(
    			'user_status'=>$status
    	);
    	$res=$user->where("uid=$id")->save($data);
    	if($res!==false)
    	{
    		echo '1';
    	}else {
    		echo '0';
    	}
    }
    
    //重置密码
    public function resetpwd1($id)
    {
        // $customer = new \Common\Model\CustomerModel();
        $user = new \Common\Model\UserModel();
        $pwd = $user->encrypt('88888888');
        $data=array(
                'user_pwd'=>$pwd,
        );
        if(!$user->create($data))
        {
            //对data数据进行验证
            $this->error($user->getError());
        }else{
            //验证通过 可以对数据进行操作
            $res=$user->where("uid='$id'")->save($data);
            if($res!==false)
            {
                echo '1';
            }else {
                echo '0';
            }
        }
    }
    
    //批量导入用户列表
    public function import()
    {
    	if (I ( 'post.' ))
    	{
    		layout(false);
    		$user=new \Common\Model\UserModel();
    		if ($_FILES ['file'] ['tmp_name'])
    		{
    			//判断文件格式
    			$type=getFileExt($_FILES ['file'] ['name']);
    			if($type!='.csv')
    			{
    				$this->error('文件格式不正确，必须为CSV文件！');
    			}
    			//读取CSV文件
    			$file = fopen($_FILES ['file'] ['tmp_name'],'r');
    			while ($data = fgetcsv($file)) 
    			{ //每次读取CSV里面的一行内容
    				$array[] = $data;
    			}
    			//删除第一行栏目
    			unset($array[0]);
    			$array=array_values($array);
    			$array = eval('return '.iconv('gbk','utf-8',var_export($array,true)).';');
    			
    			//判断导入的数据中是否有重复的用户工号
    			foreach ( $array as $tmp1 )
    			{
    				$repeat_arr[]=$tmp1[0];
    			}
    			$repeat = array_diff_assoc ( $repeat_arr,  array_unique ( $repeat_arr ) );
    			if(!empty($repeat))
    			{
    				foreach($repeat as $key=>$value)
    				{
    					$repeat_str.=$value.',';
    				}
    				$repeat_str=substr($repeat_str,0,-1);
    				$this->error ( '导入的表格中存在重复数据，分别为：'.$repeat_str.'。', '' ,20);
    			}
    			
    			$exist_num = 0; //已存在数
    			$count = 0;  //成功数
    			$total = 0;  //总数
    			//用户列表
    			$ulist = $user->field('staffno')->select();
    			$code_array=array();
    			foreach($ulist as $ul)
    			{
    				$code_array[]=$ul['staffno'];
				}
				
				$Departmentinfo=new \Common\Model\DepartmentModel();
				//获取部门名称列表
				$res_dn=$Departmentinfo->field('department_name')->select();
				foreach($res_dn as $dn)
				{
					$dn_array[]=$dn['department_name'];
				}
				//判断是否有不存在的部门
				foreach ( $array as $tmp )
				{
					if(!in_array($tmp [2], $dn_array))
					{
						$this->error ( ''.$tmp [2].'部门不存在，请检查导入的表格数据', '' ,5);
					}
				}
				
				$UserGroup=new \Common\Model\UserGroupModel();
				//获取用户组名称列表
				$res_gn=$UserGroup->field('title')->select();
				foreach($res_gn as $gn)
				{
					$gn_array[]=$gn['title'];
				}
				//判断是否有不存在的用户组
				foreach ( $array as $tmp )
				{
					if(!in_array($tmp[4], $gn_array))
					{
						$this->error ( ''.$tmp [4].'用户组不存在，请检查导入的表格数据', '' ,5);
					}
				}
				foreach ( $array as $tmp )
				{
    				if(in_array($tmp [0], $code_array))
    				{
    					$exist_str.=$tmp [0].',';
    					$exist_num++;
    				}else {
    					//部门
    					$departmentname=$tmp [2];
    					$res_d=$Departmentinfo->where("department_name='$departmentname'")->field('id')->find();
    					$data['department_id']=$res_d['id'];
    					
    					//用户组
    					$group_name=$tmp [4];
    					$res_g=$UserGroup->where("title='$group_name'")->field('id')->find();
    					$data['group_id']=$res_g['id'];

    					$admin_id = $_SESSION['admin_id'];
    					$admin = new \Admin\Model\AdminModel();
    					$res_a = $admin->where("uid='$admin_id'")->field('adminname')->find();
    					$pwd = $user->encrypt('88888888');
    					$data = array(
    							'staffno'=>$tmp[0],
    							'user_pwd'=>$pwd,
    							'user_name'=>$tmp[1],
    							'department_id'=>$res_d['id'],
    							'position'=>$tmp[3],
    							'group_id'=>$res_g['id'],
    							'user_status'=>'Y',
    							'operator'=>$res_a['adminname'],
    							'operationtime'=>date('Y-m-d H:i:s'),
    							'shift_id'=>null
    					);
    					if(!$user->create($data))
    					{
    						//对data数据进行验证
    						$this->error($user->getError());
    					}else{
    						//验证通过 可以对数据进行操作
    						$res = $user->add ( $data );
    						if ($res != 0)
    						{
    							$count ++;
    						}
    					}
    				}
    				$total ++;
				}
				$exist_str=substr($exist_str,0,-1);
				$str='共有'.$total.'条数据，导入'.$count.'条数据成功';
				if(!empty($exist_str))
				{
					$str.='，其中已存在数据为'.$exist_num.'条，重复的用户为：'.$exist_str.'。';
				}
				$this->success ( $str, U ( 'index' ),5 );
    		}
    	} else {
    		$this->display ();
    	}
    }
    
    //下载批量导入用户示例文件
    public function down()
    {
    	$fpath='./Public/ad/user.csv';
    	$filename=realpath("$fpath");
    	Header( "Content-type:  application/csv");
    	Header( "Accept-Ranges:  bytes ");
    	Header( "Accept-Length: " .filesize($filename));
    	header( "Content-Disposition:  attachment;  filename= user.csv");
    	readfile($filename);
    }
    
    //导出用户列表
    public function export()
    {
    	ob_start();
    	header("Content-type:application/vnd.ms-excel");
    	header("Content-Disposition:filename=用户列表.csv");
    
    	echo "ID,工号,姓名,所属部门,职务,用户组,最后登录时间,用户状态\n";
    	$User=new \Common\Model\UserModel();
    	$ulist=$User->select();
    	$Department=new \Common\Model\DepartmentModel();
    	$UserGroup=new \Common\Model\UserGroupModel();
    	foreach($ulist as $u)
    	{
    		$id=$u['id'];
    		$staffno=$u['staffno'];
    		$username=$u['user_name'];
    		//部门
    		$res_d=$Department->getDepartmentMsg($u['department_id']);
    		$department_name=$res_d['department_name'];
    		
    		$position=$u['position'];
    		//用户组
    		$res_g=$UserGroup->getUserGroupMsg($u['group_id']);
    		$group_name=$res_g['title'];
    		
    		$lastlogintime=$u['last_logintime'];
    	    //状态
    		$status=$u['user_status'];
    		if($status=='1')
    		{
    			$status_str='正常';
    		}else{
    			$status_str='冻结';
    		}
    		echo $id.",".$staffno.",".$username.",".$department_name.",".$position.",".$group_name.",".$lastlogintime."\t,".$status_str."\n";
    	}
    }
}