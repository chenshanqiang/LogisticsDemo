<?php
	namespace app\index\model;
	use think\Db;
	use PHPExcel_IOFactory;
	use PHPExcel;

	class Admin extends \think\Model
	{
		/*登录验证*/
		public static function login($username,$password){
			$where['username'] = $username;
			$where['password'] = $password;

			$user = Db::name('数据库表名')->where($where)->find();  /*用户信息检测*/
			if($user){
           	 	unset($user["password"]);      	   					/*销毁password*/
            	session("user_session", $user);	   					/*创建session,里面只包含用户名，password已经销毁*/
				return true;
			}else{
				return false;
			}
		}

		/*退出登录*/
		public static function logout(){
			session("user_session", NULL);        					/*user_session置空，表示注销当前用户*/
			return true;
		}

		/*根据条件查询待审核物品订单*/
		public static function queryexaminegoodsorder(...$args){
			$totalargs = count($args);
			$pagenum = intval($args[0]?$args[0]:1);
			$length = intval($args[1]);

			$sqlone = "select count(*) from dsppasmartvideo.fileprograminfo where filename like '%%'";
			if($totalargs == 3){
				if($args[2]['areamanger'] != ""){
					$areamanger = $args[2]['areamanger'];
					$sqlone.= " and mediatype ='$areamanger'";
				}
				/*省去各种条件*/
			}
			$countobj = Db::query($sqlone);
			$count = $countobj[0]['count(*)'];
			if($count == 0){
				return (array('code'=>0,'msg'=>'','count'=>$count,'data'=>[]));
			}
			$pagetot = ceil($count/$length);
			if($pagenum >= $pagetot){
				$pagenum = $pagetot;
			}

			$offset = ($pagenum - 1)*$length;
			$sqltwo = "select * from dsppasmartvideo.fileprograminfo where filename like '%%'";
			if($totalargs == 3){
				if($args[2]['areamanger'] != ""){
					$areamanger = $args[2]['areamanger'];
					$sqltwo.= " and mediatype ='$areamanger'";
				}
				/*省去各种条件*/
			}
			$sqltwo .= " order by idfile DESC limit {$offset},{$length}";
			$tableobj = Db::query($sqltwo);
			if(!empty($tableobj)){
				return (array('code'=>0,'msg'=>'','count'=>$count,'data'=>$tableobj));
			}
		}

		/*测试新建表格对象，并且填写数据*/
		public static function testexcel(){
			$PHPExcel = new PHPExcel(); //实例化PHPExcel类，类似于在桌面上新建一个Excel表格
			$PHPSheet = $PHPExcel->getActiveSheet(); 
			$PHPSheet->setTitle('demo'); 
			$PHPSheet->setCellValue('A1','姓名')->setCellValue('B1','分数');
			$PHPSheet->setCellValue('A2','张三')->setCellValue('B2','50');
			$PHPWriter = PHPExcel_IOFactory::createWriter($PHPExcel,'Excel2007');
		  	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        	header('Content-Disposition: attachment;filename="01simple.xlsx"');
        	header('Cache-Control: max-age=0');
        	$PHPWriter->save("php://output");
		}

		/*根据模板导出表格测试*/
		public static function testtemplateexport(){
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load("F:/website/Apache24/htdocs/LogisticsOrder/public/templates/26template.xlsx");
			$objPHPExcel->getActiveSheet()->setTitle('sheetone');
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setCellValue('C3', '研发一部');
			$objPHPExcel->getActiveSheet()->getStyle('C3')->getFont()->setName('Candara');
			$objPHPExcel->getActiveSheet()->getStyle('C3')->getFont()->setSize(16);
			$objPHPExcel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007'); 
		  	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        	header('Content-Disposition: attachment;filename="01simple.xlsx"');
        	header('Cache-Control: max-age=0');
        	$objWriter->save("php://output");
		}
	}

?>