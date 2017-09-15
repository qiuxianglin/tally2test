<?php
/**
 * 箱号校验类
 * @author 葛阳 2016-09-08
 */
namespace Common\Model;

class ContainerCheckModel
{
	/**
	 * 集装箱号由4位公司代码和7位数字组成（如CBHU3202732），其中第七位数字就是校验码。首先将公司代码转换为数字，去掉11及其倍数，连加除以11，其余数为校验位。
	 * A=10 B=12 C=13 D=14 E=15 F=16 G=17 H=18 I=19 J=20 K=21 L=23 M=24 N=25 O=26 P=27 Q=28 R=29 S=30 T=31 U=32 V=34 W=35 X=36 Y=37 Z=38
	 * 
	 * 标准箱号构成基本概念：采用ISO6346（1995）标准。标准集装箱箱号由11位编码组成，包括三个部分：
	 * 1、 第一部分由4位英文字母组成。前三位代码 (Owner Code) 主要说明箱主、经营人，第四位代码说明集装箱的类型。列如CBHU 开头的标准集装箱是表明箱主和经营人为中远集运。
	 * 2、 第二部分由6位数字组成。是箱体注册码（Registration Code）, 用于一个集装箱箱体持有的唯一标识。
	 * 3、 第三部分为校验码（Check Digit）由前4位字母和6位数字经过校验规则运算得到，用于识别在校验时是否发生错误。即第11位数字。 根据校验规则箱号的每个字母和数字都有一个运算的对应值。箱号的前10位字母和数字的对应值从0到Z对应数值为0到38，11、22、33不能对11取模数，所以要除去。
	 * 
	 * 第N位的箱号对应值再分别乘以2的（N－1）次方 （N＝1，2，3………..10）
	 * 例如：
	 * 箱号为CBHU3202732的集装箱它的第1位代码为C，它的代码值＝代码的对应值×2的（1－1）次方 ＝13×1＝13。
	 * 类推第2位代码为B，它的代码值＝代码的对应值×2的（2－1 ）次方＝12×2＝24 
	 * 以此类推得到箱号前10位代码的代码值,将前10位的代码值乘积累加后对11取模箱号为CBHU3202732的集装箱前10位箱号的代码累加值＝4061，取11的模后为2，就是这个箱号第11位的识别码的数值。
	 * 以此类推，就能得到校验码。
	 */
	
	/**
	 * 检验箱号是否正确
	 * @param string $containerNo:集装箱号
	 * @return boolean
	 */
	public function checkNo($containerNo)
	{
		if(strlen($containerNo)!=11)
		{
			//箱号长度必须为11位
			return false;
		}else {
			//将箱号每1位分隔成数组
			$no_arr=str_split($containerNo);
			//将前4位转化成对应数值
			$n1=$this->changevalue($no_arr[0]);
			$n2=$this->changevalue($no_arr[1]);
			$n3=$this->changevalue($no_arr[2]);
			$n4=$this->changevalue($no_arr[3]);
			$n5=$no_arr[4];
			$n6=$no_arr[5];
			$n7=$no_arr[6];
			$n8=$no_arr[7];
			$n9=$no_arr[8];
			$n10=$no_arr[9];
			//将前10位的代码值乘积累加后对11取模箱号为CBHU3202732的集装箱前10位箱号的代码累加值＝4061，取11的模后为2，就是这个箱号第11位的识别码的数值。
			$sum=$n1+$n2*2+$n3*4+$n4*8+$n5*16+$n6*32+$n7*64+$n8*128+$n9*256+$n10*512;
			$n11=$sum%11;
			if($n11==$no_arr[10] or ($n11-$no_arr[10]==10))
			{
				return true;
			}else {
				return false;
			}
		}
	}
	
	/**
	 * 获取代码的计算数值
	 * @param string $code:公司代码
	 * @return number:代码对应的数值
	 */
	private function changevalue($code)
	{
		//将代码转化为大写
		$code=strtoupper($code);
		switch($code)
		{
			case 'A' :
				return 10;
				break;
			case 'B' :
				return 12;
				break;
			case 'C' :
				return 13;
				break;
			case 'D' :
				return 14;
				break;
			case 'E' :
				return 15;
				break;
			case 'F' :
				return 16;
				break;
			case 'G' :
				return 17;
				break;
			case 'H' :
				return 18;
				break;
			case 'I' :
				return 19;
				break;
			case 'J' :
				return 20;
				break;
			case 'K' :
				return 21;
				break;
			case 'L' :
				return 23;
				break;
			case 'M' :
				return 24;
				break;
			case 'N' :
				return 25;
				break;
			case 'O' :
				return 26;
				break;
			case 'P' :
				return 27;
				break;
			case 'Q' :
				return 28;
				break;
			case 'R' :
				return 29;
				break;
			case 'S' :
				return 30;
				break;
			case 'T' :
				return 31;
				break;
			case 'U' :
				return 32;
				break;
			case 'V' :
				return 34;
				break;
			case 'W' :
				return 35;
				break;
			case 'X' :
				return 36;
				break;
			case 'Y' :
				return 37;
				break;
			case 'Z' :
				return 38;
				break;
			default :
				return - 1000;
				break;
		}
	}
}
?>