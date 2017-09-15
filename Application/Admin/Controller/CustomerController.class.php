<?php
/**
 * 客户信息维护
 * 2016-11-21
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;

class CustomerController extends AuthController
{
	public function index()
	{
		//获取客户列表
		$customer = new \Common\Model\CustomerModel();
		$where="1";
		if(I('get.code'))
		{
			$code=I('get.code');
			$code = str_replace("'", "", $code);
			$where.=" and customer_code='$code'";
		}
		if(I('get.name'))
		{
			$name=I('get.name');
			$name = str_replace("'", "", $name);
			$where.=" and customer_name like '%$name%'";
		}
		if(I('get.category'))
		{
			$category=I('get.category');
			$where.=" and customer_category='$category'";
		}
		if(I('get.paytype'))
		{
			$paytype=I('get.paytype');
			$where.=" and paytype='$paytype'";
		}
		if(I('get.status')!=='')
		{
			$status=I('get.status');
			$where.=" and customer_status='$status'";
		}
		$count=$customer->where($where)->count();
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
			
		$list = $customer->where($where)->page($p.','.$per)->order('id desc')->select();
		$this->assign('cList',$list);
		$this->assign('page',$show);
		$this->display();
	}
	
	//新增客户
	public function add()
	{
		//费率本列表
		$Rate=new \Common\Model\RateModel();
		$ratelist=$Rate->getRateList();
		$this->assign('ratelist',$ratelist);
		$customer = new \Common\Model\CustomerModel();
		if(I('post.'))
		{
			layout(false);
			$code=strtoupper(trim(I('post.customer_code'),"'"));
			//判断客户代码唯一性
			$res_c=$customer->where("customer_code='$code'")->field('id')->find();
			if($res_c['id']!='')
			{
				$this->error('X客户代码不能重复，确保唯一！');
			}
			//对密码进行加密
			$pwd1 = $customer->encrypt(trim(I('post.customer_pwd')));
			$data = array(
					'customer_code'=>$code,
					'customer_pwd'=>$pwd1,
					'customer_name'=>trim(I('post.customer_name'),"'"),
					'customer_shortname'=>trim(I('post.customer_shortname'),"'"),
					'customer_category'=>trim(I('post.category')),
					'paytype'=>trim(I('post.paytype')),
					'rate_id'=>trim(I('post.rate_id')),
					'linkman'=>trim(I('post.linkman')),
					'telephone'=>trim(I('post.telephone')),
					'customer_status'=>trim(I('post.customer_status')),
					'contract_number'=>trim(I('post.contract_number'))
			);
			if(I('post.contract_life'))
			{
				$data['contract_life']=trim(I('post.contract_life'));
			}else {
				$data['contract_life']=null;
			}
			if(!$customer->create($data))
			{
				//对data数据进行验证
				$this->error($customer->getError());
			}else{
				//验证通过 可以对数据进行操作
				$res=$customer->add($data);
				if ($res)
				{
					$this->success('新增客户成功！',U('index'));
				}else {
					$this->error('X操作失败！');
				}
			}
		}else {
			$this->display();
		}
	}
	
	//编辑客户
	public function edit($id)
	{
		//费率本列表
		$Rate=new \Common\Model\RateModel();
		$ratelist=$Rate->getRateList();
		$this->assign('ratelist',$ratelist);
		$customer= new \Common\Model\CustomerModel();
		//获取客户信息
		$msg=$customer->getCustomerMsg($id);
		$this->assign('msg',$msg);
		if(I('post.'))
		{
			layout(false);
			$code=strtoupper(trim(I('post.customer_code'),"'"));
			//判断客户代码唯一性
			$res_c=$customer->where("customer_code='$code' and id!='$id'")->field('id')->find();
			if($res_c['id']!='')
			{
				$this->error('X客户代码已被使用，不能重复，确保唯一！');
			}
			$data = array(
					'customer_code'=>$code,
					'customer_name'=>trim(I('post.customer_name'),"'"),
					'customer_shortname'=>trim(I('post.customer_shortname'),"'"),
					'customer_category'=>trim(I('post.category')),
					'paytype'=>trim(I('post.paytype')),
					'rate_id'=>trim(I('post.rate_id')),
					'linkman'=>trim(I('post.linkman')),
					'telephone'=>trim(I('post.telephone')),
					'customer_status'=>trim(I('post.customer_status')),
					'contract_number'=>trim(I('post.contract_number'))
			);
			if(I('post.contract_life'))
			{
				$data['contract_life']=trim(I('post.contract_life'));
			}else {
				$this->error('合同有效期不能为空！');
			}
			if(I('post.customer_pwd')!='')
			{
				$data['customer_pwd']=$customer->encrypt(trim(I('post.customer_pwd')));
			}
			if(!$customer->create($data))
			{
				//对data数据进行验证
				$this->error($customer->getError());
			}else{
				//验证通过 可以对数据进行操作
				$res=$customer->where("id='$id'")->save($data);
				if ($res!==false)
				{
					$this->success('编辑客户成功！',U('index'));
				}else {
					$this->error('X操作失败！');
				}
			}
		}else {
			$this->display();
		}
	}
	
	//修改客户状态
	public function changestatus($id,$status)
	{
		$customer = new \Common\Model\CustomerModel();
		$data=array(
				'customer_status'=>$status
		);
		if(!$customer->create($data))
		{
			//对数据进行验证
			$this->error($customer->getError());
		}else{
			//验证通过 可以对数据进行操作
			$res=$customer->where("id=$id")->save($data);
			if($res!==false)
			{
				echo '1';
			}else {
				echo '0';
			}
		}
	}

	//重置密码
	public function resetpwd1($id)
	{
		$customer = new \Common\Model\CustomerModel();
		$pwd = $customer->encrypt('88888888');
		$data=array(
				'customer_pwd'=>$pwd,
		);
		if(!$customer->create($data))
		{
			//对data数据进行验证
			$this->error($customer->getError());
		}else{
			//验证通过 可以对数据进行操作
			$res=$customer->where("id=$id")->save($data);
			if($res!==false)
			{
				echo '1';
			}else {
				echo '0';
			}
		}
	}
	
	//批量导入客户列表
	public function import()
	{
		if (I ( 'post.' )) 
		{
			layout(false);
			$customer = new \Common\Model\CustomerModel();
			if ($_FILES ['file'] ['tmp_name']) 
			{
				//判断文件格式
				$type=getFileExt($_FILES ['file'] ['name']);
				if($type!='.csv')
				{
					$this->error('文件格式不正确，必须为CSV文件！');
				}
    			header("Content-type:text/html;charset=gbk");
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
    			
				//判断导入的数据中是否有重复的客户代码
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
				//客户列表
				$clist = $customer->field('customer_code')->select();
				$code_array=array();
				foreach($clist as $cl)
				{
					$code_array[]=$cl['customer_code'];
				}
				foreach ( $array as $tmp ) 
				{
					if(in_array($tmp [0], $code_array))
					{
						$exist_str.=$tmp [0].',';
						$exist_num++;
 					}else {
						$category = $tmp[3];
						switch ($category) {
							case '代理' :
								$customer_category = 1;
								break;
							case '货主' :
								$customer_category = 2;
								break;
							case '港区' :
								$customer_category = 3;
								break;
							case '其他' :
								$customer_category = 4;
								break;
							default :
								$customer_category = 1;
								break;
						}
						switch ($tmp[4])
						{
							case '线下结算':
								$paytype='0';
								break;
							case '现结':
								$paytype='1';
								break;
							case '月结':
								$paytype='2';
								break;
							case '预付':
								$paytype='3';
								break;
							default:
								break;
						}
						$rate_code = $tmp[5];
						$rate = new \Common\Model\RateModel();
						$res_r = $rate->where("code='$rate_code'")->field('id')->find();
						$pwd = $customer->encrypt('88888888');
						if($tmp[9])
						{
							$contract_life=$tmp[9];
						}else {
							$contract_life=null;
						}
						$data = array(
								'customer_code'=>$tmp[0],
								'customer_pwd'=>$pwd,
								'customer_name'=>$tmp[1],
								'customer_shortname'=>$tmp[2],
								'customer_category'=>$customer_category,
								'paytype'=>$paytype,
								'rate_id'=>$res_r['id'],
								'linkman'=>$tmp[6],
								'telephone'=>$tmp[7],
								'contract_number'=>$tmp[8],
								'contract_life'=>$contract_life,
								'customer_status'=>'Y'
						);
						if(!$customer->create($data))
						{
							//对data数据进行验证
							$this->error($customer->getError());
						}else{
							//验证通过 可以对数据进行操作
							$res = $customer->add ($data);
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
					$str.='，其中已存在数据为'.$exist_num.'条，重复的客户为：'.$exist_str.'。';
				}
				$this->success ( $str, U ( 'index' ) ,5);
			}
		} else {
			$this->display ();
		}
	}
	
	//下载批量导入客户示例文件
	public function down()
	{
		$fpath='./Public/ad/customer.csv';
		$filename=realpath("$fpath");
		Header( "Content-type:  application/csv");
		Header( "Accept-Ranges:  bytes ");
		Header( "Accept-Length: " .filesize($filename));
		header( "Content-Disposition:  attachment;  filename= customer.csv");
		readfile($filename);
	}
	
	//导出客户列表
	public function export()
	{
		ob_start();
    	header("Content-type:application/vnd.ms-excel");
    	header("Content-Disposition:filename=客户列表.csv");
	
		echo "ID,客户代码,客户名称,客户简称,客户类别,结算方式,费率标准,联系人,联系电话,合同号,合同有效期,客户状态\n";
		$customer = new \Common\Model\CustomerModel();
		$clist=$customer->select();
		foreach($clist as $u)
		{
			$id=$u['id'];
			$customercode=$u['customer_code'];
			$customername=$u['customer_name'];
			$customershortname=$u['customer_shortname'];
			switch ($u['customer_category']) {
				case '1' :
					$category = '代理';
					break;
				case '2' :
					$category = '货主';
					break;
				case '3' :
					$category = '港区';
					break;
				case '4' :
					$category = '其他';
					break;
				default :;
			}
			switch ($u['paytype'])
			{
				case '0':
					$payType='线下结算';
					break;
				case '1':
					$payType='现结';
					break;
				case '2':
					$payType='月结';
					break;
				case '3':
					$payType='预付';
					break;
				default:
					break;
			}
			$rate_id = $u['rate_id'];
			$rate = new \Common\Model\RateModel();
			$res = $rate->where("id='$rate_id'")->field('code')->find();
			$accountingcode=$res['code'];
			$contacter=$u['linkman'];
			$telephone=$u['telephone'];
			$contract_num=$u['contract_number'];
			$contract_life=$u['contract_life'];
			if($u['customer_status']=='Y')
			{
				$customerstatus='正常';
			}else {
				$customerstatus='冻结';
			}
			echo $id.",".$customercode.",".$customername.",".$customershortname.",".$category.",".$payType.",".$accountingcode.",".$contacter.",".$telephone.",".$contract_num.",".$contract_life.",".$customerstatus."\n";
		}
	}
	
	public function authority(){
		$Customer = M('customer');
		if(!empty($_POST)){
			layout(false);
			$data['authority'] = json_encode($_POST);
			$id = $_POST['id'];
			$res = $Customer->where('id='.$id)->save($data);
			if ($res!==false)
				{
					$this->success('保存成功',U('index'));
				}else {
					$this->error('保存失败');
				}	
		}else{
			$id = $_GET['id'];
			$cus = $Customer->field('authority')->where('id='.$id)->find();
			$authority = json_decode($cus['authority'],true);
			$this->assign('authority',$authority);
			$this->display();
		}
	}
}