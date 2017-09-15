<?php
namespace Index\Controller;
use Think\Controller;

class TestController extends Controller
{
	public function test()
	{
		$id=1;
		$totalPrice=26000;
		$Rate=new \Common\Model\RateModel();
		$due=$Rate->due($id, $totalPrice);
		dump($due);
	}
	
	public function pay()
	{
		$this->display();
	}
	
	public function invoice() 
	{
		if (IS_POST) 
		{
			layout(false);
			if(I('post.ORDERID')=='' or I('post.VSLNAME')=='' or I('post.VSLNAME')=='' or I('post.BLNO')=='' or I('post.APPLYCODE')=='' or I('post.PAYCODE')=='')
			{
				$this->error('委托编号、中文船名、航次、提单号、申报公司代码、付费方代码不能为空！');
			}
			$ORDERID = I ( 'post.ORDERID' );
			$ORDER_DATE = date('YmdHis');
			$VSLNAME = I ( 'post.VSLNAME' );
			$VOYAGE = I ( 'post.VOYAGE' );
			$BLNO = I ( 'post.BLNO' );
			$APPLYCODE = I ( 'post.APPLYCODE' );
			$APPLYNAME = I ( 'post.APPLYNAME' );
			$PAYCODE = I ( 'post.PAYCODE' );
			$PAYMEN = I ( 'post.PAYMEN' );
			$CARGONAME = I ( 'post.CARGONAME' );
			$NUMBERSOFPACKAGES = I ( 'post.NUMBERSOFPACKAGES' );
			$PACKAGE = I ( 'post.PACKAGE' );
			$MARK = I ( 'post.MARK' );
			$LCL = I ( 'post.LCL' );
			$CONSIGNEE = I ( 'post.CONSIGNEE' );
			$UNPACKAGINGPLACE = I ( 'post.UNPACKAGINGPLACE' );
			$CLASSES = I ( 'post.CLASSES' );
			$UNDGNO = I ( 'post.UNDGNO' );
			$CONTACTUSER = I ( 'post.CONTACTUSER' );
			$CONTACT = I ( 'post.CONTACT' );
			$NOTE = I ( 'post.NOTE' );
			$TRANSIT = I ( 'post.TRANSIT' );
			$CATEGORY = I ( 'post.CATEGORY' );
				
			$CTNNO_1 = I ( 'post.CTNNO_1' );
			if ($CTNNO_1 != '') 
			{
				$CTNSIZE_1 = I ( 'post.CTNSIZE_1' );
				$CTNTYPE_1 = I ( 'post.CTNTYPE_1' );
				$SEALNO_1 = I ( 'post.SEALNO_1' );
				$NUMBERSOFPACKAGES_1 = I ( 'post.NUMBERSOFPACKAGES_1' );
				$WEIGHT_1 = I ( 'post.WEIGHT_1' );
				$VOLUME_1 = I ( 'post.VOLUME_1' );
				$FLFLAG_1 = I ( 'post.FLFLAG_1' );
				$CLASSES_1 = I ( 'post.CLASSES_1' );
				$UNDGNO_1 = I ( 'post.UNDGNO_1' );
				$ctn1 = '<CTN>
				            <CTNNO>' . $CTNNO_1 . '</CTNNO>
				            <CTNSIZE>' . $CTNSIZE_1 . '</CTNSIZE>
				            <CTNTYPE>' . $CTNTYPE_1 . '</CTNTYPE>
				            <SEALNO>' . $SEALNO_1 . '</SEALNO>
				            <NUMBERSOFPACKAGES>' . $NUMBERSOFPACKAGES_1 . '</NUMBERSOFPACKAGES>
				            <WEIGHT>' . $WEIGHT_1 . '</WEIGHT>
				            <VOLUME>' . $VOLUME_1 . '</VOLUME>
				            <FLFLAG>' . $FLFLAG_1 . '</FLFLAG>
				            <CLASSES>' . $CLASSES_1 . '</CLASSES>
				            <UNDGNO>' . $UNDGNO_1 . '</UNDGNO>
				         </CTN>';
			}
				
			$CTNNO_2 = I ( 'post.CTNNO_2' );
			if ($CTNNO_2 != '') 
			{
				$CTNSIZE_2 = I ( 'post.CTNSIZE_2' );
				$CTNTYPE_2 = I ( 'post.CTNTYPE_2' );
				$SEALNO_2 = I ( 'post.SEALNO_2' );
				$NUMBERSOFPACKAGES_2 = I ( 'post.NUMBERSOFPACKAGES_2' );
				$WEIGHT_2 = I ( 'post.WEIGHT_2' );
				$VOLUME_2 = I ( 'post.VOLUME_2' );
				$FLFLAG_2 = I ( 'post.FLFLAG_2' );
				$CLASSES_2 = I ( 'post.CLASSES_2' );
				$UNDGNO_2 = I ( 'post.UNDGNO_2' );
				$ctn2 = '<CTN>
                    <CTNNO>' . $CTNNO_2 . '</CTNNO>
                    <CTNSIZE>' . $CTNSIZE_2 . '</CTNSIZE>
                    <CTNTYPE>' . $CTNTYPE_2 . '</CTNTYPE>
                    <SEALNO>' . $SEALNO_2 . '</SEALNO>
                    <NUMBERSOFPACKAGES>' . $NUMBERSOFPACKAGES_2 . '</NUMBERSOFPACKAGES>
                    <WEIGHT>' . $WEIGHT_2 . '</WEIGHT>
                    <VOLUME>' . $VOLUME_2 . '</VOLUME>
                    <FLFLAG>' . $FLFLAG_2 . '</FLFLAG>
                    <CLASSES>' . $CLASSES_2 . '</CLASSES>
                    <UNDGNO>' . $UNDGNO_2 . '</UNDGNO>
              </CTN>';
			}
				
			$CTNNO_3 = I ( 'post.CTNNO_3' );
			if ($CTNNO_3 != '') 
			{
				$CTNSIZE_3 = I ( 'post.CTNSIZE_3' );
				$CTNTYPE_3 = I ( 'post.CTNTYPE_3' );
				$SEALNO_3 = I ( 'post.SEALNO_3' );
				$NUMBERSOFPACKAGES_3 = I ( 'post.NUMBERSOFPACKAGES_3' );
				$WEIGHT_3 = I ( 'post.WEIGHT_3' );
				$VOLUME_3 = I ( 'post.VOLUME_3' );
				$FLFLAG_3 = I ( 'post.FLFLAG_3' );
				$CLASSES_3 = I ( 'post.CLASSES_3' );
				$UNDGNO_3 = I ( 'post.UNDGNO_3' );
				$ctn3 = '<CTN>
                    <CTNNO>' . $CTNNO_3 . '</CTNNO>
                    <CTNSIZE>' . $CTNSIZE_3 . '</CTNSIZE>
                    <CTNTYPE>' . $CTNTYPE_3 . '</CTNTYPE>
                    <SEALNO>' . $SEALNO_3 . '</SEALNO>
                    <NUMBERSOFPACKAGES>' . $NUMBERSOFPACKAGES_3 . '</NUMBERSOFPACKAGES>
                    <WEIGHT>' . $WEIGHT_3 . '</WEIGHT>
                    <VOLUME>' . $VOLUME_3 . '</VOLUME>
                    <FLFLAG>' . $FLFLAG_3 . '</FLFLAG>
                    <CLASSES>' . $CLASSES_3 . '</CLASSES>
                    <UNDGNO>' . $UNDGNO_3 . '</UNDGNO>
              </CTN>';
			}
				
			$CTNNO_4 = I ( 'post.CTNNO_4' );
			if ($CTNNO_4 != '') 
			{
				$CTNSIZE_4 = I ( 'post.CTNSIZE_4' );
				$CTNTYPE_4 = I ( 'post.CTNTYPE_4' );
				$SEALNO_4 = I ( 'post.SEALNO_4' );
				$NUMBERSOFPACKAGES_4 = I ( 'post.NUMBERSOFPACKAGES_4' );
				$WEIGHT_4 = I ( 'post.WEIGHT_4' );
				$VOLUME_4 = I ( 'post.VOLUME_4' );
				$FLFLAG_4 = I ( 'post.FLFLAG_4' );
				$CLASSES_4 = I ( 'post.CLASSES_4' );
				$UNDGNO_4 = I ( 'post.UNDGNO_4' );
				$ctn4 = '<CTN>
                    <CTNNO>' . $CTNNO_4 . '</CTNNO>
                    <CTNSIZE>' . $CTNSIZE_4 . '</CTNSIZE>
                    <CTNTYPE>' . $CTNTYPE_4 . '</CTNTYPE>
                    <SEALNO>' . $SEALNO_4 . '</SEALNO>
                    <NUMBERSOFPACKAGES>' . $NUMBERSOFPACKAGES_4 . '</NUMBERSOFPACKAGES>
                    <WEIGHT>' . $WEIGHT_4 . '</WEIGHT>
                    <VOLUME>' . $VOLUME_4 . '</VOLUME>
                    <FLFLAG>' . $FLFLAG_4 . '</FLFLAG>
                    <CLASSES>' . $CLASSES_4 . '</CLASSES>
                    <UNDGNO>' . $UNDGNO_4 . '</UNDGNO>
              </CTN>';
			}
				
			$CTNNO_5 = I ( 'post.CTNNO_5' );
			if ($CTNNO_5 != '') 
			{
				$CTNSIZE_5 = I ( 'post.CTNSIZE_5' );
				$CTNTYPE_5 = I ( 'post.CTNTYPE_5' );
				$SEALNO_5 = I ( 'post.SEALNO_5' );
				$NUMBERSOFPACKAGES_5 = I ( 'post.NUMBERSOFPACKAGES_5' );
				$WEIGHT_5 = I ( 'post.WEIGHT_5' );
				$VOLUME_5 = I ( 'post.VOLUME_5' );
				$FLFLAG_5 = I ( 'post.FLFLAG_5' );
				$CLASSES_5 = I ( 'post.CLASSES_5' );
				$UNDGNO_5 = I ( 'post.UNDGNO_5' );
				$ctn5 = '<CTN>
                    <CTNNO>' . $CTNNO_5 . '</CTNNO>
                    <CTNSIZE>' . $CTNSIZE_5 . '</CTNSIZE>
                    <CTNTYPE>' . $CTNTYPE_5 . '</CTNTYPE>
                    <SEALNO>' . $SEALNO_5 . '</SEALNO>
                    <NUMBERSOFPACKAGES>' . $NUMBERSOFPACKAGES_5 . '</NUMBERSOFPACKAGES>
                    <WEIGHT>' . $WEIGHT_5 . '</WEIGHT>
                    <VOLUME>' . $VOLUME_5 . '</VOLUME>
                    <FLFLAG>' . $FLFLAG_5 . '</FLFLAG>
                    <CLASSES>' . $CLASSES_5 . '</CLASSES>
                    <UNDGNO>' . $UNDGNO_5 . '</UNDGNO>
              </CTN>';
			}
				
			$ctns = $ctn1 . $ctn2 . $ctn3 . $ctn4 . $ctn5;
			if($ctns=='')
			{
				$this->error('配箱不能为空！');
			}
			
			$requestdata = '<?xml version="1.0" encoding="UTF-8"?>
		<Manifest>
			<BILL>
				 <ORDERID>' . $ORDERID . '</ORDERID>
				 <ORDER_DATE>' . $ORDER_DATE . '</ORDER_DATE>
		         <VSLNAME>' . $VSLNAME . '</VSLNAME>
		         <VOYAGE>' . $VOYAGE . '</VOYAGE>
		         <BLNO>' . $BLNO . '</BLNO>
		         <APPLYCODE>' . $APPLYCODE . '</APPLYCODE>
          		 <APPLYNAME>' . $APPLYNAME . '</APPLYNAME>
          		 <PAYCODE>' . $PAYCODE . '</PAYCODE>
          		 <PAYMEN>' . $PAYMEN . '</PAYMEN>
          		 <CARGONAME>' . $CARGONAME . '</CARGONAME>
          		 <NUMBERSOFPACKAGES>' . $NUMBERSOFPACKAGES . '</NUMBERSOFPACKAGES>
          		 <PACKAGE>' . $PACKAGE . '</PACKAGE>
         		 <MARK>' . $MARK . '</MARK>
          		 <LCL>' . $LCL . '</LCL>
          		 <CONSIGNEE>' . $CONSIGNEE . '</CONSIGNEE>
          		 <UNPACKAGINGPLACE>' . $UNPACKAGINGPLACE . '</UNPACKAGINGPLACE>
          		 <CLASSES>' . $CLASSES . '</CLASSES>
          		 <UNDGNO>' . $UNDGNO . '</UNDGNO>
          		 <CONTACTUSER>' . $CONTACTUSER . '</CONTACTUSER>
          		 <CONTACT>' . $CONTACT . '</CONTACT>
          		 <NOTE>' . $NOTE . '</NOTE>
          		 <TRANSIT>' . $TRANSIT . '</TRANSIT>
          		 <CATEGORY>' . $CATEGORY . '</CATEGORY>
			</BILL>
            <CTNS>
          		 		' . $ctns . '
          	</CTNS>
		</Manifest>';
			$sign_str = "requestdata=$requestdata&key=tally";
			$sign = md5 ( $sign_str );
			$data = array (
					'requestdata' => $requestdata,
					'sign' => $sign
			);
			$url = 'http://221.226.22.178:8081/app.php?c=Delegate&a=dtd';
			$res = https_request ( $url, $data );dump ( $res );
			// 去除bom头 trim($res,chr(239).chr(187).chr(191)
			$res2 = json_decode ( trim ( $res, chr ( 239 ) . chr ( 187 ) . chr ( 191 ) ), true );
			//$res2=json_decode($res,TRUE);
			$this->success($res2['msg'],'',10);
			//dump ( $res2 );
		} else {
			$this->display ();
		}
	}
	
	//支付回执模拟
	public function payment()
	{
		if (IS_POST)
		{
			layout(false);
			/* if(I('post.ORDERID')=='' or I('post.VSLNAME')=='' or I('post.VSLNAME')=='' or I('post.BLNO')=='' or I('post.APPLYCODE')=='' or I('post.PAYCODE')=='')
			 {
			$this->error('委托编号、中文船名、航次、提单号、申报公司代码、付费方代码不能为空！');
			} */
			$ORDERID = I ( 'post.ORDERID' );
			$ORDER_DATE = I ( 'post.ORDER_DATE' );
			$VSLNAME = I ( 'post.VSLNAME' );
			$VOYAGE = I ( 'post.VOYAGE' );
			$BLNO = I ( 'post.BLNO' );
			$PAYCODE = I ( 'post.PAYCODE' );
			$PAYMEN = I ( 'post.PAYMEN' );
			$AMOUNT = I ( 'post.AMOUNT' );
			$CARGONAME = I ( 'post.CARGONAME' );
			$NUMBERSOFPACKAGES = I ( 'post.NUMBERSOFPACKAGES' );
			$LCL = I ( 'post.LCL' );
			$CONSIGNEE = I ( 'post.CONSIGNEE' );
			$UNPACKAGINGPLACE = I ( 'post.UNPACKAGINGPLACE' );
			$CLASSES = I ( 'post.CLASSES' );
			$UNDGNO = I ( 'post.UNDGNO' );
			$CONTACTUSER = I ( 'post.CONTACTUSER' );
			$CONTACT = I ( 'post.CONTACT' );
			$NOTE = I ( 'post.NOTE' );
	
			$requestdata = '<?xml version="1.0" encoding="UTF-8"?>
		<Manifest>
				 <ORDERID>' . $ORDERID . '</ORDERID>
				 <ORDER_DATE>' . $ORDER_DATE . '</ORDER_DATE>
		         <VSLNAME>' . $VSLNAME . '</VSLNAME>
		         <VOYAGE>' . $VOYAGE . '</VOYAGE>
  		         <BLNO>' . $BLNO . '</BLNO>
		         <PAYCODE>' . $PAYCODE . '</PAYCODE>
          		 <PAYMEN>' . $PAYMEN . '</PAYMEN>
          		 <AMOUNT>' . $AMOUNT . '</AMOUNT>
          		 <PAYTYPE></PAYTYPE>
          		 <CARGONAME>' . $CARGONAME . '</CARGONAME>
          		 <NUMBERSOFPACKAGES>' . $NUMBERSOFPACKAGES . '</NUMBERSOFPACKAGES>
          		 <RCVFLAG></RCVFLAG>
          		 <LCL>' . $LCL . '</LCL>
          		 <CONSIGNEE>' . $CONSIGNEE . '</CONSIGNEE>
          		 <UNPACKAGINGPLACE>' . $UNPACKAGINGPLACE . '</UNPACKAGINGPLACE>
          		 <CLASSES>' . $CLASSES . '</CLASSES>
          		 <UNDGNO>' . $UNDGNO . '</UNDGNO>
          		 <CONTACTUSER>' . $CONTACTUSER . '</CONTACTUSER>
          		 <CONTACT>' . $CONTACT . '</CONTACT>
          		 <NOTE>' . $NOTE . '</NOTE>
         </Manifest>';
			
			$sign_str = "requestdata=$requestdata&key=tally";
			$sign = md5 ( $sign_str );
			$data = array (
					'requestdata' => $requestdata,
					'sign' => $sign
			);
			$url = 'http://localhost/xztally/app.php?c=Delegate&a=payment';
			$res = https_request ( $url, $data );
			// 去除bom头 trim($res,chr(239).chr(187).chr(191)
			$res2 = json_decode ( trim ( $res, chr ( 239 ) . chr ( 187 ) . chr ( 191 ) ), true );
			//$res2=json_decode($res,TRUE);
			$this->success($res2['msg'],'',10);
			//dump ( $res2 );
		}else {
			$this->display ();
		}
	}
}