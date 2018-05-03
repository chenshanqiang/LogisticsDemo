<?php
	namespace app\index\model;
	use think\Db;
	use PHPExcel_IOFactory;
	use PHPExcel;
    use think\Session;

	class Admin extends \think\Model
	{
		/*登录验证*/
		public static function login($username,$password){
			$where['fullname'] = $username;
			$where['password'] = $password;

			$user = Db::name('dsp_logistic.user')->where($where)->find();   /*用户信息检测*/
			if($user){
           	 	unset($user["password"]);      	   					       /*销毁password*/
            	session_start();
                session("user_session", $user);	   					       /*创建session,里面只包含用户名，password已经销毁*/
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

        /*获取session*/
        public static function getsessioninfo(){
            return Session::get('user_session');
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

		/*查询订货确认单*/
		public static function querygoodsorderinfo(...$args){
			
			return (array('code'=>0,'msg'=>'','count'=>0,'data'=>[]));
		}

		/*查询维修，代用等订单*/
		/*最多四个参数:type  page  limit queryinfo*/
		public static function querycsInfomation(...$args){

			$totalargs = count($args);
			$type = $args[0];
			$pagenum = intval($args[1]?$args[1]:1);
			$length = intval($args[2]);

			$sqlone = "select count(*) from dsp_logistic.cs_info where cs_info_type='$type'";

			if($totalargs == 4){
				if($args[3]['areamanager'] != ""){
					$areamanger = $args[3]['areamanager'];
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
            $sqltwo ="select  dsp_logistic.cs_belong.* ,dsp_logistic.cs_info.*,dsp_logistic.delivery_info.transfer_fee_mode,dsp_logistic.logistics_info.transfer_order_num,";
            $sqltwo .= "dsp_logistic.delivery_info.delivery_info_receiver_name,dsp_logistic.return_info.return_info_receiver_name from dsp_logistic.cs_info ";
            $sqltwo .= "left join dsp_logistic.custom_info on dsp_logistic.custom_info.custom_info_id = dsp_logistic.cs_info.custom_info_id ";
            $sqltwo .= "left join dsp_logistic.delivery_info on dsp_logistic.delivery_info.delivery_info_id = dsp_logistic.cs_info.delivery_info_id ";
            $sqltwo .= "left join dsp_logistic.return_info on dsp_logistic.return_info.return_info_id = dsp_logistic.cs_info.return_info_id ";
         //   $sqltwo .= "left join dsp_logistic.payment_info on dsp_logistic.payment_info.payment_info_id = dsp_logistic.cs_info.payment_info_id ";
            $sqltwo .= "left join dsp_logistic.logistics_info on dsp_logistic.logistics_info.cs_id = dsp_logistic.cs_info.cs_id ";
            $sqltwo .= "left join dsp_logistic.cs_belong on dsp_logistic.cs_belong.cs_id = dsp_logistic.cs_info.cs_id ";
            $sqltwo .= "where dsp_logistic.cs_info.cs_info_type='$type' ";
			if($totalargs == 4){
				if($args[3]['areamanager'] != ""){
					$areamanger = $args[3]['areamanager'];
					$sqltwo.= " and fullname ='$areamanger'";
				}
				/*省去各种条件*/
			}
			$sqltwo .= "order By dsp_logistic.cs_info.cs_id DESC limit {$offset},{$length} ;";
			$tableobj = Db::query($sqltwo);
			if(!empty($tableobj)){
				return (array('code'=>0,'msg'=>'','count'=>$count,'data'=>$tableobj));
			}
		}

        /*根据条件查询审批确认单，五个参数最少四个:user_id  type  page  limit queryinfo*/
        public static function queryApproveConfirmOrder(...$args){
            $totalargs = count($args);
            $examine_user_id = $args[0];
            $type = $args[1];
            $pagenum = intval($args[2]?$args[2]:1);
            $length = intval($args[3]);

            $sqlone = "select count(*) from dsp_logistic.cs_examine ";
            $sqlone .= "left join dsp_logistic.cs_info on dsp_logistic.cs_info.cs_id = dsp_logistic.cs_examine.cs_id ";
         //   $sqlone .= "left join dsp_logistic.custom_info on dsp_logistic.custom_info.custom_info_id = dsp_logistic.cs_info.custom_info_id ";
            $sqlone .= "left join dsp_logistic.delivery_info on dsp_logistic.delivery_info.delivery_info_id = dsp_logistic.cs_info.delivery_info_id ";
            $sqlone .= "left join dsp_logistic.return_info on dsp_logistic.return_info.return_info_id = dsp_logistic.cs_info.return_info_id ";
         //   $sqlone .= "left join dsp_logistic.payment_info on dsp_logistic.payment_info.payment_info_id = dsp_logistic.cs_info.payment_info_id ";
            $sqlone .= "left join dsp_logistic.cs_belong on dsp_logistic.cs_belong.cs_id = dsp_logistic.cs_info.cs_id ";
            $sqlone.=" where examine_user_id = '$examine_user_id' and cs_info_type = '$type'";
            if($totalargs == 5){
                $startdate = $args[4]['startdate'];
                $enddate = $args[4]['enddate'];
                if($startdate != "" && $enddate != "" ){
                    $sqlone.= " and write_date >='$startdate' and write_date <='$enddate'";
                }
                if($args[4]['order_id'] != "")
                {
                    $cs_id = $args[4]['order_id'];
                    $sqlone.= " and dsp_logistic.cs_info.cs_id ='$cs_id'";
                }
                if($args[4]['orderstate'] != "")
                {
                    $cs_info_state = $args[4]['orderstate'];
                    $sqlone.= " and cs_info_state ='$cs_info_state'";
                }
                if($type == 2||$type == 5) //借样和配件没有返货信息
                {
                    if($args[4]['receiver_name'] != "")
                    {
                        $delivery_info_receiver_name = $args[4]['receiver_name'];
                        $sqlone.= " and delivery_info_receiver_name ='$delivery_info_receiver_name'";
                    }
                }
                else
                {
                    if($args[4]['receiver_name'] != "")
                    {
                        $return_info_receiver_name = $args[4]['receiver_name'];
                        $sqlone.= " and return_info_receiver_name ='$return_info_receiver_name'";
                    }
                }
                if($args[4]['department'] != "")
                {
                    $organize_id = $args[4]['department'];
                    $sqlone.= " and build_organize_id ='$organize_id'";

                }

                if($args[4]['areamanager'] != "")
                {
                    $user_id = $args[4]['areamanager'];
                    $sqlone.= " and build_user_id ='$user_id'";

                }
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
            $sqltwo = "select  dsp_logistic.cs_belong.* ,dsp_logistic.cs_info.*,";
            $sqltwo .= "dsp_logistic.delivery_info.delivery_info_receiver_name,dsp_logistic.return_info.return_info_receiver_name from dsp_logistic.cs_examine ";
            $sqltwo .= "left join dsp_logistic.cs_info on dsp_logistic.cs_info.cs_id = dsp_logistic.cs_examine.cs_id ";
          //  $sqltwo .= "left join dsp_logistic.custom_info on dsp_logistic.custom_info.custom_info_id = dsp_logistic.cs_info.custom_info_id ";
            $sqltwo .= "left join dsp_logistic.delivery_info on dsp_logistic.delivery_info.delivery_info_id = dsp_logistic.cs_info.delivery_info_id ";
            $sqltwo .= "left join dsp_logistic.return_info on dsp_logistic.return_info.return_info_id = dsp_logistic.cs_info.return_info_id ";
         //   $sqltwo .= "left join dsp_logistic.payment_info on dsp_logistic.payment_info.payment_info_id = dsp_logistic.cs_info.payment_info_id ";
            $sqltwo .= "left join dsp_logistic.cs_belong on dsp_logistic.cs_belong.cs_id = dsp_logistic.cs_info.cs_id ";
            $sqltwo.=" where examine_user_id = '$examine_user_id' and cs_info_type = '$type'";
            if($totalargs == 5){
                $startdate = $args[4]['startdate'];
                $enddate = $args[4]['enddate'];
                if($startdate != "" && $enddate != "" ){
                    $sqltwo.= " and write_date >='$startdate' and write_date <='$enddate'";
                }
                if($args[4]['order_id'] != "")
                {
                    $cs_id = $args[4]['order_id'];
                    $sqltwo.= " and dsp_logistic.cs_info.cs_id ='$cs_id'";
                }
                if($args[4]['orderstate'] != "")
                {
                    $cs_info_state = $args[4]['orderstate'];
                    $sqltwo.= " and cs_info_state ='$cs_info_state'";
                }
                if($type == 2||$type == 5) //借样和配件没有返货信息
                {
                    if($args[4]['receiver_name'] != "")
                    {
                        $delivery_info_receiver_name = $args[4]['receiver_name'];
                        $sqltwo.= " and delivery_info_receiver_name ='$delivery_info_receiver_name'";
                    }
                }
                else
                {
                    if($args[4]['receiver_name'] != "")
                    {
                        $return_info_receiver_name = $args[4]['receiver_name'];
                        $sqltwo.= " and return_info_receiver_name ='$return_info_receiver_name'";
                    }
                }
                if($args[4]['department'] != "")
                {
                    $organize_id = $args[4]['department'];
                    $sqltwo.= " and build_organize_id ='$organize_id'";

                }

                if($args[4]['areamanager'] != "")
                {
                    $user_id = $args[4]['areamanager'];
                    $sqltwo.= " and build_user_id ='$user_id'";

                }
            }
            $sqltwo .= " order By dsp_logistic.cs_examine.cs_examine_id DESC limit {$offset},{$length} ;";
            $tableobj = Db::query($sqltwo);
            if(!empty($tableobj)){
                $count = count($tableobj);
                for ($i = 0;$i < $count;$i++)
                {
                    if($type == 2||$type == 5) //借样和配件没有返货信息
                    {
                        $tableobj[$i]["receiver_name"] = $tableobj[$i]["delivery_info_receiver_name"];
                    }
                    else
                    {
                        //$tableobj[$i]["receiver_name"] = $tableobj[$i]["return_info_receiver_name"];
                        $tableobj[$i]["receiver_name"] = $tableobj[$i]["return_info_receiver_name"];
                    }
                    $tableobj[$i]["serial_number"] = $i+1;
                }
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
        public static function queryroleinfo(...$param)
        {
            $sql = "select * from  dsp_logistic.role ";
            $count = count($param);
            if($count == 1)
            {
                $sql.= "where role_id = '{$param[0]}'";
            }
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
                $sql.= " where dsp_logistic.organize.parent_id = {$param1} ";
            }
            elseif ($paramcount === 2) //两个参数 parent_id ,organize_id
            {
                $param1 = $param[0];
                $param2 = $param[1];
                $sql.= " where dsp_logistic.organize.parent_id = {$param1} or dsp_logistic.organize.organize_id = {$param2}";
            }
            $tableorganize = Db::query($sql);
            if(!empty($tableorganize))
                return $tableorganize;
            return null;
        }


        /*根据增加和更新组织架构*/
        public static function updatedepartment($department){
        	$sqlone = "delete FROM dsp_logistic.organize where organize_id > 0";
        	$result = Db::execute($sqlone);

        	for($item=0 ; $item < count($department); $item++){
        		$organize_id = intval($department[$item]['organize_id']);
        		$parent_id = intval($department[$item]['parent_id']);
        		$organize_name = $department[$item]['organize_name'];
        		$sqltwo="INSERT INTO dsp_logistic.organize (organize_id,parent_id,organize_name) VALUES ('$organize_id','$parent_id','$organize_name') ON DUPLICATE KEY UPDATE parent_id='$parent_id',organize_name = '$organize_name'";
        		$result = Db::execute($sqltwo);
        	}
        	return true;
        }

        public static function getmaxorganizeID(){
        	$sql = "SELECT max(organize_id) FROM dsp_logistic.organize";
            $organizeID = Db::query($sql);
            return $organizeID;
        }

        /*根据增加和更新用户*/
        public static function updateuser($user)
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
            $sql.="  VALUES ('{$user["fullname"]}', '{$password}', '{$phone}', '{$organize_id}', '{$job_id}', '{$role_id}');";
            $result = Db::execute($sql);
            return $result;

            $user_id = $user["user_id"];
            if($user_id != "")
            {
                $sql = " UPDATE dsp_logistic.user ";
                $sql.="  SET  fullname = '{$fullname}', password = '{$password}', phone = '{$phone}', organize_id = '{$organize_id}', job_id = '{$job_id}', role_id = '{$role_id}' ";
                $sql.=" where user_id = '{$user_id}'";
                $result = Db::execute($sql);
                return $result;
            }
            else
            {
                $sql = "INSERT INTO `dsp_logistic`.`user` (`fullname`, `password`, `phone`, `organize_id`, `job_id`, `role_id`)";
                $sql.="  VALUES ('{$fullname}', '{$password}', '{$phone}', '{$organize_id}', '{$job_id}', '{$role_id}');";
                $result = Db::execute($sql);
                return $result;
            }

        }
        /*获取删除用户*/
        public  static function deleteuser($userid)
        {
            $sql = "Delete FROM dsp_logistic.user where user_id = '{$userid}'";
            $retsql = Db::query($sql);
            return $retsql;

        }

        /*获取用户信息根据id*/
        public  static function getloginuserinfo($userid)
        {
            $sql = "SELECT * FROM dsp_logistic.user WHERE user_id = '{$userid}' LIMIT 1";
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

        /*新增更换确认单 hjh*/
        public static function updateroleinfo($role)
        {

            $sql = "UPDATE dsp_logistic.role ";
            $sql.="  SET  role_name = '{$role["role_name"]}', order_goods_permission = '{$role["order_goods_permission"]}', replace_permission = '{$role["replace_permission"]}', borrow_sample_permission = '{$role["borrow_sample_permission"]}', return_goods_permission = '{$role["return_goods_permission"]}',";
            $sql.= "fixing_permission =  '{$role["fixing_permission"]}',maintain_permission = '{$role["maintain_permission"]}',substitute_permission = '{$role["substitute_permission"]}',user_manage_permission = '{$role["user_manage_permission_manage"]}' ,logistic_input_permission = '{$role["logistic_input_permission"]}'";
            $sql.=" where role_id = '{$role["role_id"]}'";
            $result = Db::execute($sql);
            return "$result";
        }

        /*获取用户信息*/
        public static  function getuserinfobydepid($depid)
        {
            $sql = "SELECT * FROM dsp_logistic.user WHERE organize_id = {$depid}";
            $retsql = Db::query($sql);
            return $retsql;
        }

        /*模糊搜索型号*/
        public  static function serachmodelinfo($serachText, $product_type_id, $brand)
        {
            $sql = "SELECT * FROM dsp_logistic.product_info WHERE model LIKE '%{$serachText}%' AND product_type_id = '{$product_type_id}' AND brand_id = '{$brand}'";
            $retsql = Db::query($sql);
            return $retsql;
        }

        /*获取表中最大的id值*/
        public static function getmaxtableid($tablename,$tableID){
            $sql = "SELECT max({$tableID}) FROM dsp_logistic.{$tablename}";
            $organizeID = Db::query($sql);
            return $organizeID;
        }

        /*获取表中最大的id值*/
        public static function getmaxtableidretid($tablename,$tableID){
            $sql = "SELECT max({$tableID}) FROM dsp_logistic.{$tablename}";
            $organizeID = Db::query($sql);
            if (!empty($organizeID)){
                return $organizeID[0]["max({$tableID})"];
            }else{
                return -1;
            }
        }

        /*删除表的行*/
        public static function deleterowtableid($tablename,$tableID,$value){
            $sql = "DELETE FROM dsp_logistic.{$tablename} WHERE {$tableID} = '{$value}'";
            $organizeID = Db::query($sql);
            return $organizeID;
        }

        /*新增订单    暂时使用（更改确认单）*/
        public  static function addconfirmorder($info)
        {
            $cs_id = $info['cs_id'];
            $custom_info_id = $info['custom_info_id'];
            $delivery_info_id = $info['delivery_info_id'];
            $return_info_id = $info['return_info_id'];
            $payment_info_id = $info['payment_info_id'];
            $cur_process_user_id = $info['cur_process_user_id'];
            $pre_process_user_id = $info['pre_process_user_id'];
            $cs_info_type = $info['cs_info_type'];
            $can_edit = $info['can_edit'];
            $write_date = $info['write_date'];
            $cs_info_state = $info['cs_info_state'];
            $complete_date = $info['complete_date'];
            $product_number = $info['product_number'];
            $sql_value ="'{$cs_id}','{$custom_info_id}','{$delivery_info_id}','{$return_info_id}','{$payment_info_id}','{$cur_process_user_id}','{$pre_process_user_id}','{$cs_info_type}','{$can_edit}','{$write_date}','{$cs_info_state}','{$complete_date}','{$product_number}'";
            $sql = "INSERT INTO dsp_logistic.cs_info (cs_id,custom_info_id,delivery_info_id,return_info_id,payment_info_id,cur_process_user_id,pre_process_user_id
,cs_info_type,can_edit,write_date,cs_info_state,complete_date,product_number) VALUES ({$sql_value})";
            $sqlret = Db::execute($sql);
            return $sqlret;
        }
        /*新增 custom_info */
        public  static function addcustominfo($info)
        {
            $company_name = $info['company_name'];
            $company_phone = $info['company_phone'];
            $company_address = $info['company_address'];
            $legal_representative = $info['legal_representative'];
            $legal_phone = $info['legal_phone'];
            $company_contact = $info['company_contact'];
            $company_contact_phone = $info['company_contact_phone'];
            $sql_value ="'{$company_name}','{$company_phone}','{$company_address}','{$legal_representative}','{$legal_phone}','{$company_contact}','{$company_contact_phone}'";
            $sql = "INSERT INTO dsp_logistic.custom_info (company_name,company_phone,company_address,legal_representative,legal_phone,company_contact,company_contact_phone) VALUES ({$sql_value})";
            $sqlret = Db::execute($sql);
            return $sqlret;
        }
        /*新增 delivery_info */
        public static function adddeliveryinfo($info){
            $delivery_info_receiver_name = $info['delivery_info_receiver_name'];
            $receiver_phone = $info['receiver_phone'];
            $goods_yard_name = $info['goods_yard_name'];
            $goods_yard_phone = $info['goods_yard_phone'];
            $receiver_address = $info['receiver_address'];
            $is_insure = $info['is_insure'];
            $is_sign = $info['is_sign'];
            $insure_amout = $info['insure_amout'];
            $has_contract = $info['has_contract'];
            $transfer_fee_mode = $info['transfer_fee_mode'];
            $sql_value ="'{$delivery_info_receiver_name}','{$receiver_phone}','{$goods_yard_name}','{$goods_yard_phone}','{$receiver_address}','{$is_insure}','{$insure_amout}','{$is_sign}','{$has_contract}','{$transfer_fee_mode}'";
            $sql = "INSERT INTO dsp_logistic.delivery_info (delivery_info_receiver_name,receiver_phone,goods_yard_name,goods_yard_phone,receiver_address,is_insure,insure_amout,is_sign,has_contract,transfer_fee_mode) VALUES ({$sql_value})";
            $sqlret = Db::execute($sql);
            return $sqlret;
        }
        /*新增 return_info */
        public  static function addreturninfo($info)
        {
            $return_info_receiver_name = $info['return_info_receiver_name'];
            $return_info_receiver_phone = $info['return_info_receiver_phone'];
            $return_info_goods_yard_name = $info['return_info_goods_yard_name'];
            $return_info_goods_yard_phone = $info['return_info_goods_yard_phone'];
            $return_order_num = $info['return_order_num'];
            $return_info_receiver_address = $info['return_info_receiver_address'];
            $sql_value ="'{$return_info_receiver_name}','{$return_info_receiver_phone}','{$return_info_goods_yard_name}','{$return_info_goods_yard_phone}','{$return_order_num}','{$return_info_receiver_address}'";
            $sql = "INSERT INTO dsp_logistic.return_info (return_info_receiver_name,return_info_receiver_phone,return_info_goods_yard_name,return_info_goods_yard_phone,return_order_num,return_info_receiver_address) VALUES ({$sql_value})";
            $sqlret = Db::execute($sql);
            return $sqlret;
        }

        /*新增 payment_info*/
        public static function addpaymentinfo($info){
            $is_pad = $info['is_pad'];
            $paid_date = $info['paid_date'];
            $customization_fee = $info['customization_fee'];
            $paid_bank = $info['paid_bank'];
            $comment = $info['comment'];
            $time_stamp = $info['time_stamp'];
            $user_id = $info['user_id'];
            $sql_value ="'{$is_pad}','{$paid_date}','{$customization_fee}','{$paid_bank}','{$comment}','{$time_stamp}','{$user_id}'";
            $sql = "INSERT INTO dsp_logistic.payment_info (is_pad,paid_date,customization_fee,paid_bank,comment,time_stamp,user_id) VALUES ({$sql_value})";
            $sqlret = Db::execute($sql);
            return $sqlret;
        }
        /*新增 cs_belong*/
        public static function addbcsbelong($info){
            $cs_id = $info['cs_id'];
            $build_organize_id = $info['build_organize_id'];
            $build_user_id = $info['build_user_id'];
            $cs_belong_create_time = $info['cs_belong_create_time'];
            $build_user_name = $info['build_user_name'];
            $build_organize_name = $info['build_organize_name'];
            $sql_value ="'{$cs_id}','{$build_organize_id}','{$build_user_id}','{$cs_belong_create_time}','{$build_user_name}','{$build_organize_name}'";
            $sql = "INSERT INTO dsp_logistic.cs_belong (cs_id,build_organize_id,build_user_id,cs_belong_create_time,build_user_name,build_organize_name) VALUES ({$sql_value})";
            $sqlret = Db::execute($sql);
            return $sqlret;
        }
        /*新增 确认单清单经理部分 order_goods_manager*/
        public static function addordergoodsmanager($info){
            //id
            $cs_id = $info['cs_id'];
            $product_info_id = $info['product_info_id'];
            $unit_price = $info['unit_price'];
            $order_goods_manager_count = $info['order_goods_manager_count'];
            $specification = $info['specification'];
            $explain = $info['explain'];
            $type = $info['type'];
            $comment = $info['comment'];
            $bar_code = $info['bar_code'];
            $back_date = $info['back_date'];
            $replace_reason = $info['replace_reason'];
            $purchase_date = $info['purchase_date'];
            $deal_date = $info['deal_date'];
            $fault_condition = $info['fault_condition'];
            $sql_value ="'{$cs_id}','{$product_info_id}','{$unit_price}','{$order_goods_manager_count}','{$specification}','{$explain}','{$type}'";
            $sql_value .= ",'{$comment}','{$bar_code}','{$back_date}','{$replace_reason}','{$purchase_date}','{$deal_date}',{$fault_condition}";
            $sql = "INSERT INTO dsp_logistic.cs_belong (cs_id,product_info_id,unit_price,order_goods_manager_count,specification,explain,type";
            $sql .= ",comment,bar_code,back_date,replace_reason,purchase_date,deal_date,fault_condition) VALUES ({$sql_value})";
            $sqlret = Db::execute($sql);
            return $sqlret;
        }
        /*新增 确认单清单物流部分 order_goods_logistics*/
        public static function addordergoodslogistics($info){
            $order_goods_manager_id = $info['order_goods_manager_id'];
            $ogl_product_state = $info['ogl_product_state'];
            $ogl_unc_product_id = $info['ogl_unc_product_id'];
            $ogl_comment = $info['ogl_comment'];
            $user_id = $info['user_id'];
            $ogl_time_stamp = $info['ogl_time_stamp'];
            $ogl_explain = $info['ogl_explain'];
            $sql_value ="'{$order_goods_manager_id}','{$ogl_product_state}','{$ogl_unc_product_id}','{$ogl_comment}','{$user_id}','{$ogl_time_stamp}','{$ogl_explain}'";
            $sql = "INSERT INTO dsp_logistic.cs_belong (order_goods_manager_id,ogl_product_state,ogl_unc_product_id,ogl_comment,user_id,ogl_time_stamp,ogl_explain) VALUES ({$sql_value})";
            $sqlret = Db::execute($sql);
            return $sqlret;
        }
        /*新增 确认单审批 cs_examine*/
        public static function addcsexamine($info){
            $cs_id = $info['cs_id'];
            $submit_user_id = $info['submit_user_id'];
            $examine_user_id = $info['examine_user_id'];
            $cs_examine_date = $info['cs_examine_date'];
            $cs_examine_content = $info['cs_examine_content'];
            $cs_examine_result = $info['cs_examine_result'];
            $cs_examine_time_stamp = $info['cs_examine_time_stamp'];
            $cs_examine_comment = $info['cs_examine_comment'];
            $cs_examine_name = $info['cs_examine_name'];
            $cs_examine_state = $info['cs_examine_state'];
            $sql_value ="'{$cs_id}','{$submit_user_id}','{$examine_user_id}','{$cs_examine_date}','{$cs_examine_content}','{$cs_examine_result}','{$cs_examine_time_stamp}','{$cs_examine_comment}','{$cs_examine_name}','{$cs_examine_state}'";
            $sql = "INSERT INTO dsp_logistic.cs_belong (cs_id,submit_user_id,examine_user_id,cs_examine_date,cs_examine_content,cs_examine_result,cs_examine_time_stamp,cs_examine_comment,cs_examine_name,cs_examine_state) VALUES ({$sql_value})";
            $sqlret = Db::execute($sql);
            return $sqlret;
        }

        /*查找 所属领导信息（$role_name：总经理/财务部 /其它看数据库）*/
        public static function getdepleaderbyuserid($userid,$role_name){

            $sqlrole = "SELECT * FROM dsp_logistic.role WHERE role_name = '{$role_name}' LIMIT 1";
            $retrole = Db::query($sqlrole);
            $retuserinfo = self::getloginuserinfo($userid);
            if (empty($retuserinfo)){
                return;
            }

            if (empty($retrole)){
                return;
            }
            $org_id = $retuserinfo[0]['organize_id'];
            $role_id = $retrole[0]['role_id'];
            if ($role_name == '总经理'){
                 $sqlorg =  "SELECT * FROM dsp_logistic.user WHERE organize_id = '{$org_id}' LIMIT 1";
                 $retorg = Db::query($sqlorg);
                 if(empty($retorg)){
                     return;
                 }
                $org_pid = $retorg['parent_id'];
                $sqlleader = "SELECT * FROM dsp_logistic.user WHERE role_id = '{$role_id}' AND organize_id = '{$org_pid}' LIMIT 1";
                return Db::query($sqlleader);
            }else if($role_name != '财务部'){
                $sqlleader = "SELECT * FROM dsp_logistic.user WHERE role_id = '{$role_id}' AND organize_id = '{$org_id}' LIMIT 1";
                return Db::query($sqlleader);
            }else {
                $sqlleader = "SELECT * FROM dsp_logistic.user WHERE role_id = '{$role_id}' LIMIT 1";
                return Db::query($sqlleader);
            }
        }
    }
?>