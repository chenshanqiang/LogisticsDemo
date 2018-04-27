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


		/*获取select选项值*/
		public static function getclassinfo($tablename,$tableID){
			$sql = "select * from ".$tablename;
			$sql.= " order by ".$tableID." asc";
			$tableobj = Db::query($sql);
			if(!empty($tableobj)){
				return $tableobj;
			}
		}

		/*根据条件查询待审核物品订单*/
		public static function queryexaminegoodsorder(...$args){
			$totalargs = count($args);
			$pagenum = intval($args[0]?$args[0]:1);
			$length = intval($args[1]);

			$sqlone = "select count(*) from dsp_logistic.cs_info where state like '%%'";

			if($totalargs == 3){
				if($args[2]['areamanger'] != ""){
					$areamanger = $args[2]['areamanger'];
					$sqlone.= " and fullname ='$areamanger'";
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
            $sqltwo = "SELECT dsp_logistic.cs_info.*,dsp_logistic.custom_info.*,dsp_logistic.delivery_info.* ,dsp_logistic.return_info.*  dsp_logistic.payment_info.* dsp_logistic.logistics_info.* from dsp_logistic.cs_info ";
            $sqltwo .= "left join dsp_logistic.custom_info on dsp_logistic.custom_info.custom_info_id = dsp_logistic.cs_info.custom_info_id ";
            $sqltwo .= "left join dsp_logistic.delivery_info on dsp_logistic.delivery_info.delivery_info_id = dsp_logistic.cs_info.delivery_info_id ";
            $sqltwo .= "left join dsp_logistic.return_info on dsp_logistic.return_info.return_info_id = dsp_logistic.cs_info.return_info_id ";
            $sqltwo .= "left join dsp_logistic.payment_info on dsp_logistic.payment_info.payment_info_id = dsp_logistic.cs_info.payment_info_id ";
            $sqltwo .= "left join dsp_logistic.logistics_info on dsp_logistic.logistics_info.cs_id = dsp_logistic.cs_info.id ";
			if($totalargs == 3){
				if($args[2]['areamanger'] != ""){
					$areamanger = $args[2]['areamanger'];
					$sqltwo.= " and fullname ='$areamanger'";
				}
				/*省去各种条件*/
			}
			$sqltwo .= " order By dsp_logistic.cs_info.id DESC limit {$offset},{$length} ;";
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

        /*根据条件查询用户信息*/
        public static function queryuserinfo($pagenum,$length)
        {
            $sql = "select count(*) from dsp_logistic.user ;";
            $countobj = Db::query($sql);
            $count = $countobj[0]['count(*)'];
            if($count == 0){
                return (array('code'=>0,'msg'=>'','count'=>$count,'data'=>[]));
            }
            $pagetot = ceil($count/$length);
            if($pagenum >= $pagetot){
                $pagenum = $pagetot;
            }

            $offset = ($pagenum - 1)*$length;
            $sql = "SELECT dsp_logistic.user.*,dsp_logistic.organize.*,dsp_logistic.role.* ,dsp_logistic.job.*  from dsp_logistic.user ";
            $sql .= "left join dsp_logistic.organize on dsp_logistic.organize.organize_id = dsp_logistic.user.organize_id ";
            $sql .= "left join dsp_logistic.role on dsp_logistic.role.role_id = dsp_logistic.user.role_id ";
            $sql .= "left join dsp_logistic.job on dsp_logistic.job.job_id = dsp_logistic.user.job_id ";
            $sql .= " order By dsp_logistic.user.organize_id DESC limit {$offset},{$length} ;";
            $tableobj = Db::query($sql);
            if(!empty($tableobj)){
                $datacount = count($tableobj);
                for ($i = 0;$i<$datacount;$i++)
                {
                    $parentid = $tableobj[$i]["parent_id"];
                    if($parentid === 0) //只有总部门，子部门为空
                    {
                        //$name =
                        $tableobj[$i]["companyname"]=$tableobj[$i]["organize_name"];
                        $tableobj[$i]["organize_name"] = "";
                    }
                    else {
                        $sql ="select * from dsp_logistic.organize where `organize_id` = '{$parentid}'";

                        $conpanytalbe = Db::query($sql);
                        if(!empty($conpanytalbe))
                            $tableobj[$i]["companyname"]=$conpanytalbe[0]["organize_name"];
                        else{
                            $tableobj[$i]["companyname"]="";
                        }
                    }
                    $tableobj[$i]["serialnumber"]=$i+1;


                }
                return (array('code'=>0,'msg'=>'','count'=>$count,'data'=>$tableobj));
            }
            return (array('code'=>0,'msg'=>'','count'=>$count,'data'=>[]));
        }

        /*根据查询角色信息*/
        public static function queryroleinfo()
        {
            $sql = "select * from  dsp_logistic.role";
            $tablerole = Db::query($sql);
            if(!empty($tablerole))
                return $tablerole;
            return null;
        }
        /*根据查询职位信息*/
        public static function queryjobinfo()
        {
            $sql = "select * from  dsp_logistic.job ";
            $tablejob = Db::query($sql);
            if(!empty($tablejob))
                return $tablejob;
            return null;
        }
        /*根据查询部门信息*/
        public static function querydepartmentinfo(...$param)
        {
            $paramcount = count($param);
            $sql = "select * from  dsp_logistic.organize";
            if($paramcount === 1)
            {
                $param1 = $param[0];
                $sql.= " where dsp_logistic.organize.parent_id = {$param1}";
            }
            $tableorganize = Db::query($sql);
            if(!empty($tableorganize))
                return $tableorganize;
            return null;
        }
        /*根据查询部门信息*/
        public static function adduser($user)
        {
            $fullname = $user["fullname"];
            $password = $user["password"];
            $phone = $user["phone"];
            $organize_id = $user["department_id"];
            if(empty($organize_id)) //如果子部门不选，则保存总部门
            {
                $organize_id = $user["company_id"];
            }

            $job_id = $user["job_id"];
            $role_id = $user["role_id"];
            $sql = "INSERT INTO `dsp_logistic`.`user` (`fullname`, `password`, `phone`, `organize_id`, `job_id`, `role_id`)";
            $sql.="  VALUES ('{$fullname}', '{$password}', '{$phone}', '{$organize_id}', '{$job_id}', '{$role_id}');";
            $result = Db::execute($sql);
            return $result;
        }

        /*获取用户信息根据id*/
        public  static function getloginuserinfo($userid)
        {
            $sql = "SELECT * FROM dsp_logistic.user WHEN user_id = '{$userid}'";
            $retsql = Db::query($sql);
            return $retsql;
        }

        /*查询单号是否存在 hjh*/
        public function queryhasconfirmorder($orderis)
        {
            $sql = "SELECT * FROM dsp_logistic.cs_info WHERE id = {$orderis}";
            $retsql = Db::query($sql);
            return $retsql;
        }
        /*新增更换确认单 hjh 未完*/
        public   function replaceconfirmorder($orderinfo)
        {
            $id = $orderinfo['trackingnumber'];
            //$custom_info_id = $orderinfo['']


            $sql = "INSERT INTO dsp_logistic.cs_info id (id,) VALUES ('{$id}')";
        }

        /*获取用户信息*/
        public static  function getuserinfobydepid($depid)
        {
            $sql = "SELECT * FROM dsp_logistic.user WHERE organize_id = {$depid}";
            $retsql = Db::query($sql);
            return $retsql;
        }
    }

?>