<?php
class MY_Controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function str_number(){
        $count = $this->mysql_model->get_count('invoice',array());
        $var=sprintf("%05d", $count+1);//ÃÃºÂ³Ã4ÃÂ»ÃÃ½Â£Â¬Â²Â»ÃÃ£ÃÂ°ÃÃ¦Â²Â¹0
        $number="CK".$var;
        return $number;
    }

	public function str_number1(){
		$count = $this->mysql_model->get_count('orders',array());
		$var=sprintf("%05d", $count+1);//ÃÃºÂ³Ã4ÃÂ»ÃÃ½Â£Â¬Â²Â»ÃÃ£ÃÂ°ÃÃ¦Â²Â¹0
		$number="LLD".$var;
		return $number;
	}

    public function str_number2(){
        $count = $this->mysql_model->get_count('lading',array());
        $var=sprintf("%05d", $count+1);//ÃÃºÂ³Ã4ÃÂ»ÃÃ½Â£Â¬Â²Â»ÃÃ£ÃÂ°ÃÃ¦Â²Â¹0
        $number="THD".$var;
        return $number;
    }

	//ÂµÂ¼ÃÃ«excelÃÃÂ¼Ã¾
	public function importexcel($filename)
	{

 //ini_set("display_errors", "On");
// error_reporting(E_ALL | E_STRICT);

       require_once './application/libraries/PHPExcel/Classes/PHPExcel.php';
       require_once './application/libraries/PHPExcel/Classes/PHPExcel/IOFactory.php';
       require_once './application/libraries/PHPExcel/Classes/PHPExcel/Reader/Excel5.php';
	 $file = $filename;
 $files = explode('.' , $file);
 $num = count($files);
 $extension=$files[$num-1];

  if( $extension =='xlsx' )
{
 $objReader = new PHPExcel_Reader_Excel2007();
}
else
{

 $objReader = PHPExcel_IOFactory::createReader('Excel5');

}

        // $objReader = new PHPExcel_Reader_Excel2007();
	  // $objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format


       $objPHPExcel = $objReader->load($filename); //$filenameÂ¿ÃÃÃÃÃÃÃÂ´Â«ÂµÃÃÃÂ¼Ã¾Â£Â¬Â»Ã²ÃÃÃÃÃÂ¸Â¶Â¨ÂµÃÃÃÂ¼Ã¾
	 // sdafadsf
       $sheet = $objPHPExcel->getSheet(0);
       $highestRow = $sheet->getHighestRow(); // ÃÂ¡ÂµÃÃÃÃÃÃÃ½
       $highestColumn = $sheet->getHighestColumn(); // ÃÂ¡ÂµÃÃÃÃÃÃÃ½

       $k = 0;
     //ÃÂ­Â»Â·Â¶ÃÃÂ¡excelÃÃÂ¼Ã¾,Â¶ÃÃÂ¡ÃÂ»ÃÃµ,Â²Ã¥ÃÃ«ÃÂ»ÃÃµ
	   $strquestion="";
	   //ÃÃ¥Â¿ÃÃÃÃÂ±Â±Ã­ÃÃ¯ÃÃ¦ÂµÃÃÃ¹ÃÃÃÃÃÂ¢
	  // $this->mysql_model->query('delete from ci_invoice_cursor',2);
	   $this->mysql_model->delete('invoice_cursor','');

   for($j=2;$j<=$highestRow;$j++)
        {
		 $a = $objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue();//Â»Ã±ÃÂ¡AÃÃÂµÃÃÂµ
         $b = $objPHPExcel->getActiveSheet()->getCell("B".$j)->getValue();//Â»Ã±ÃÂ¡BÃÃÂµÃÃÂµ
		 $c = $objPHPExcel->getActiveSheet()->getCell("C".$j)->getValue();//Â»Ã±ÃÂ¡cÃÃÂµÃÃÂµ
		 $d = $objPHPExcel->getActiveSheet()->getCell("D".$j)->getValue();//Â»Ã±ÃÂ¡dÃÃÂµÃÃÂµ
		 $e = $objPHPExcel->getActiveSheet()->getCell("E".$j)->getValue();//Â»Ã±ÃÂ¡eÃÃÂµÃÃÂµ
	     $f = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();//Â»Ã±ÃÂ¡fÃÃÂµÃÃÂµ
		 $g = $objPHPExcel->getActiveSheet()->getCell("G".$j)->getValue();//Â»Ã±ÃÂ¡gÃÃÂµÃÃÂµ
		 $h = $objPHPExcel->getActiveSheet()->getCell("H".$j)->getValue();//Â»Ã±ÃÂ¡hÃÃÂµÃÃÂµ

		 if($a==""){
			 header("Content-type:text/html;charset=utf-8");
			 //echo iconv("GB2312","UTF-8",'ÂµÂ¼ÃÃ«ÃÃªÂ³Ã');
			 echo $this->characet("ÂµÂ¼ÃÃ«ÃÃªÂ³Ã");
			 exit();
		 }
		//  $count = $this->mysql_model->get_count('goods',array('number'=>$b));

		   $rs=$this->mysql_model->get_rows('goods',array('number'=>$b));

           if(!empty($rs))
			{
		      $data['invNumber']="$b";
			  $data['describe']="$c";
			  $data['qty']="$d";
			  $data['company']="$e";
			  $data['price']="$f";
			  $data['amount']="$g";
			   $data['billName']="$h";
			  $data['good_id']=$rs['id'];
			 $this->mysql_model->insert('invoice_cursor',$data);

			 //echo "true";
			// echo "ÂµÂ¼ÃÃ«Â³ÃÂ¹Â¦!ÃÃ¯ÃÃÂ±Ã ÂºÃÃÂªÂ£Âº".$b."<br/>";
		     }
			 //else
			// {
			// echo "Â²Â»Â´Ã¦ÃÃÃÃ¯ÃÃÂºÃÃÂªÂ£Âº".$b.",<br/>";
			// }



        }

	}

	//ÂµÂ¼ÃÃ«excelÃÃÂ¼Ã¾
	public function importexcelorders($filename)
	{

 ini_set("display_errors", "On");
 error_reporting(E_ALL | E_STRICT);

       require_once './application/libraries/PHPExcel/Classes/PHPExcel.php';
       require_once './application/libraries/PHPExcel/Classes/PHPExcel/IOFactory.php';
       require_once './application/libraries/PHPExcel/Classes/PHPExcel/Reader/Excel5.php';
	 $file = $filename;
 $files = explode('.' , $file);
 $num = count($files);
 $extension=$files[$num-1];

  if( $extension =='xlsx' )
{
 $objReader = new PHPExcel_Reader_Excel2007();
}
else
{

 $objReader = PHPExcel_IOFactory::createReader('Excel5');

}

        // $objReader = new PHPExcel_Reader_Excel2007();
	  // $objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format


       $objPHPExcel = $objReader->load($filename); //$filenameÂ¿ÃÃÃÃÃÃÃÂ´Â«ÂµÃÃÃÂ¼Ã¾Â£Â¬Â»Ã²ÃÃÃÃÃÂ¸Â¶Â¨ÂµÃÃÃÂ¼Ã¾
	 // sdafadsf
       $sheet = $objPHPExcel->getSheet(0);
       $highestRow = $sheet->getHighestRow(); // ÃÂ¡ÂµÃÃÃÃÃÃÃ½
	   //echo $highestRow."===========";
       $highestColumn = $sheet->getHighestColumn(); // ÃÂ¡ÂµÃÃÃÃÃÃÃ½

       $k = 0;
     //ÃÂ­Â»Â·Â¶ÃÃÂ¡excelÃÃÂ¼Ã¾,Â¶ÃÃÂ¡ÃÂ»ÃÃµ,Â²Ã¥ÃÃ«ÃÂ»ÃÃµ
	   $strquestion="";
	   //ÃÃ¥Â¿ÃÃÃÃÂ±Â±Ã­ÃÃ¯ÃÃ¦ÂµÃÃÃ¹ÃÃÃÃÃÂ¢
	  // $this->mysql_model->query('delete from ci_invoice_cursor',2);
	   $this->mysql_model->delete('orders_cursor','');

   for($orderint=2;$orderint<=$highestRow;$orderint++)

        {
		 $a = $objPHPExcel->getActiveSheet()->getCell("A".$orderint)->getValue();//Â»Ã±ÃÂ¡AÃÃÂµÃÃÂµ
         $b = $objPHPExcel->getActiveSheet()->getCell("B".$orderint)->getValue();//Â»Ã±ÃÂ¡BÃÃÂµÃÃÂµ
		 $c = $objPHPExcel->getActiveSheet()->getCell("C".$orderint)->getValue();//Â»Ã±ÃÂ¡cÃÃÂµÃÃÂµ
		 $d = $objPHPExcel->getActiveSheet()->getCell("D".$orderint)->getValue();//Â»Ã±ÃÂ¡dÃÃÂµÃÃÂµ
		 $e = $objPHPExcel->getActiveSheet()->getCell("E".$orderint)->getValue();//Â»Ã±ÃÂ¡eÃÃÂµÃÃÂµ
	     $f = $objPHPExcel->getActiveSheet()->getCell("F".$orderint)->getValue();//Â»Ã±ÃÂ¡fÃÃÂµÃÃÂµ
		 $g = $objPHPExcel->getActiveSheet()->getCell("G".$orderint)->getValue();//Â»Ã±ÃÂ¡gÃÃÂµÃÃÂµ
		 $h = $objPHPExcel->getActiveSheet()->getCell("H".$orderint)->getValue();//Â»Ã±ÃÂ¡hÃÃÂµÃÃÂµ
		 $i = $objPHPExcel->getActiveSheet()->getCell("I".$orderint)->getValue();//Â»Ã±ÃÂ¡hÃÃÂµÃÃÂµ
		 $j = $objPHPExcel->getActiveSheet()->getCell("J".$orderint)->getValue();//Â»Ã±ÃÂ¡hÃÃÂµÃÃÂµ
		 $k = $objPHPExcel->getActiveSheet()->getCell("K".$orderint)->getValue();//Â»Ã±ÃÂ¡hÃÃÂµÃÃÂµ
		 $l = $objPHPExcel->getActiveSheet()->getCell("L".$orderint)->getValue();//Â»Ã±ÃÂ¡hÃÃÂµÃÃÂµ

		 if($a==""){
			 header("Content-type:text/html;charset=utf-8");
			 //echo iconv("GB2312","UTF-8",'ÂµÂ¼ÃÃ«ÃÃªÂ³Ã');
			 echo $this->characet("ÂµÂ¼ÃÃ«ÃÃªÂ³Ã");
			 exit();
		 }

		//  $count = $this->mysql_model->get_count('goods',array('number'=>$b));


		 $rs=$this->mysql_model->get_rows('stock',array('goodsnumber'=>$b));


	 // print_r($rs);
           if(!empty($rs))
			{
		      $data['invNumber']="$b";
			  $data['amount']="$c";
			  $data['description']="$d";
			  $data['qty']="$e";
			  $data['billName']="$f";
			  $data['describe']="$g";
			  $data['price']="$h";
			  $data['company']="$i";
			  $data['good_id']=$rs['id'];
			  $data['ordernumber']="$k";
			  $data['locationName']="$l";
			 $this->mysql_model->insert('orders_cursor',$data);

			 //echo "true";
			// echo "ÂµÂ¼ÃÃ«Â³ÃÂ¹Â¦!ÃÃ¯ÃÃÂ±Ã ÂºÃÃÂªÂ£Âº".$b."<br/>";
		     }
			// else
			// {
			// echo "Â²Â»Â´Ã¦ÃÃÃÃ¯ÃÃÂºÃÃÂªÂ£Âº".$b.",<br/>";
			// }



        }

	}

	//ÂµÂ¼ÃÃ«excelÃÃÂ¼Ã¾
	public function importexcelorderss($filename)
	{

 ini_set("display_errors", "On");
 error_reporting(E_ALL | E_STRICT);

       require_once './application/libraries/PHPExcel/Classes/PHPExcel.php';
       require_once './application/libraries/PHPExcel/Classes/PHPExcel/IOFactory.php';
       require_once './application/libraries/PHPExcel/Classes/PHPExcel/Reader/Excel5.php';
	 $file = $filename;
 $files = explode('.' , $file);
 $num = count($files);
 $extension=$files[$num-1];

  if( $extension =='xlsx' )
{
 $objReader = new PHPExcel_Reader_Excel2007();
}
else
{

 $objReader = PHPExcel_IOFactory::createReader('Excel5');

}

        // $objReader = new PHPExcel_Reader_Excel2007();
	  // $objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format


       $objPHPExcel = $objReader->load($filename); //$filenameÂ¿ÃÃÃÃÃÃÃÂ´Â«ÂµÃÃÃÂ¼Ã¾Â£Â¬Â»Ã²ÃÃÃÃÃÂ¸Â¶Â¨ÂµÃÃÃÂ¼Ã¾
	 // sdafadsf
       $sheet = $objPHPExcel->getSheet(0);
       $highestRow = $sheet->getHighestRow(); // ÃÂ¡ÂµÃÃÃÃÃÃÃ½
	   //echo $highestRow."===========";
       $highestColumn = $sheet->getHighestColumn(); // ÃÂ¡ÂµÃÃÃÃÃÃÃ½

       $k = 0;
     //ÃÂ­Â»Â·Â¶ÃÃÂ¡excelÃÃÂ¼Ã¾,Â¶ÃÃÂ¡ÃÂ»ÃÃµ,Â²Ã¥ÃÃ«ÃÂ»ÃÃµ
	   $strquestion="";
	   //ÃÃ¥Â¿ÃÃÃÃÂ±Â±Ã­ÃÃ¯ÃÃ¦ÂµÃÃÃ¹ÃÃÃÃÃÂ¢
	  // $this->mysql_model->query('delete from ci_invoice_cursor',2);
	 //  $this->mysql_model->delete('orders_cursor','');
        $datas = [];
        $is_insert = true;
        $youwu = '';
        $chongfu = '';
   for($orderint=3;$orderint<=$highestRow;$orderint++)

        {
		 $a = $objPHPExcel->getActiveSheet()->getCell("A".$orderint)->getValue();//Â»Ã±ÃÂ¡AÃÃÂµÃÃÂµ
         $b = $objPHPExcel->getActiveSheet()->getCell("B".$orderint)->getValue();//Â»Ã±ÃÂ¡BÃÃÂµÃÃÂµ
		 $c = $objPHPExcel->getActiveSheet()->getCell("C".$orderint)->getValue();//Â»Ã±ÃÂ¡cÃÃÂµÃÃÂµ
		 $d = $objPHPExcel->getActiveSheet()->getCell("D".$orderint)->getValue();//Â»Ã±ÃÂ¡dÃÃÂµÃÃÂµ
		 $e = $objPHPExcel->getActiveSheet()->getCell("E".$orderint)->getValue();//Â»Ã±ÃÂ¡eÃÃÂµÃÃÂµ
	     $f = $objPHPExcel->getActiveSheet()->getCell("F".$orderint)->getValue();//Â»Ã±ÃÂ¡fÃÃÂµÃÃÂµ
		 $g = $objPHPExcel->getActiveSheet()->getCell("G".$orderint)->getValue();//Â»Ã±ÃÂ¡gÃÃÂµÃÃÂµ
		 $h = $objPHPExcel->getActiveSheet()->getCell("H".$orderint)->getValue();//Â»Ã±ÃÂ¡hÃÃÂµÃÃÂµ
		 $i = $objPHPExcel->getActiveSheet()->getCell("I".$orderint)->getValue();//Â»Ã±ÃÂ¡hÃÃÂµÃÃÂµ
		 $j = $objPHPExcel->getActiveSheet()->getCell("J".$orderint)->getValue();//Â»Ã±ÃÂ¡hÃÃÂµÃÃÂµ
		 $k = $objPHPExcel->getActiveSheet()->getCell("K".$orderint)->getValue();//Â»Ã±ÃÂ¡hÃÃÂµÃÃÂµ
		 $l = $objPHPExcel->getActiveSheet()->getCell("L".$orderint)->getValue();//Â»Ã±ÃÂ¡hÃÃÂµÃÃÂµ
		 $m = $objPHPExcel->getActiveSheet()->getCell("M".$orderint)->getValue();//Â»Ã±ÃÂ¡hÃÃÂµÃÃÂµ
		 $n = $objPHPExcel->getActiveSheet()->getCell("N".$orderint)->getValue();//Â»Ã±ÃÂ¡hÃÃÂµÃÃÂµ
		 $o = $objPHPExcel->getActiveSheet()->getCell("O".$orderint)->getValue();//Â»Ã±ÃÂ¡hÃÃÂµÃÃÂµ
		 $p = $objPHPExcel->getActiveSheet()->getCell("P".$orderint)->getValue();//Â»Ã±ÃÂ¡pÃÃÂµÃÃÂµ


		 $l=$this->excelTime($l);
		 $m=$this->excelTime($m);
			 $amonut=$f*$i;
			// echo $amonut."=====";
            /*header("Content-type:text/html;charset=utf-8");
            echo $this->characet($a);
            exit();*/
            /*if($a==""){
                header("Content-type:text/html;charset=utf-8");
                //echo iconv("GB2312","UTF-8",'ÂµÂ¼ÃÃ«ÃÃªÂ³Ã');
                echo $this->characet("ÂµÂ¼ÃÃ«ÃÃªÂ³Ã");
                exit();
            }*/
		 if(trim($k)!="未到货" && trim($k)!="已到货" && trim($k)!=""){
             $is_insert = false;
             $youwu .= $b.',';
			 continue;
		 }


		  $count_stock = $this->mysql_model->get_count('stock',array('goodsnumber'=>"$b",'ordernumber'=>"$h",'isDelete'=>0));
		  $count_cangku = $this->mysql_model->get_count('cangku',array('goodsnumber'=>"$b",'ordernumber'=>"$h",'isDelete'=>0));
		if($count_stock>0 || $count_cangku>0){
            $is_insert = false;
            $chongfu .= $b.'('.$h.'),';
			 continue;

		}

		 //$rs=$this->mysql_model->get_rows('stock',array('goodsnumber'=>$b));
		//echo $h."=======";
		//exit();
	 // print_r($rs);
          // if(!empty($rs))
			//{
		      $data['goodsnumber']="$b";
			  $data['mdescription']="$c";
			  $data['inventoryOld']="$d";
			  $data['number']=(trim((string)$e)===''||$e===null)?'0':$e;
			  $data['inventoryNew']="$f";
			  $data['mainUnit']="$g";
			  $data['ordernumber']="$h";
			  $data['price']="$i";
			  $data['amount']="$amonut";
			  $data['locationName']="$j";
			  $data['flagNo']="$k";
			  $data['flagtime']="$l";
			  $data['flagcontact']="$n";
			  $data['Arrivaltime']="$m";
			  $data['beizhu']="$o";
			  $data['daohuo']="$p";


			 $datas[] = $data;
			 //echo "true";
			// echo "ÂµÂ¼ÃÃ«Â³ÃÂ¹Â¦!ÃÃ¯ÃÃÂ±Ã ÂºÃÃÂªÂ£Âº".$b."<br/>";
		     //}
			// else
			// {
			// echo "Â²Â»Â´Ã¦ÃÃÃÃ¯ÃÃÂºÃÃÂªÂ£Âº".$b.",<br/>";
			// }



        }
        if($is_insert){
            $this->db->trans_start();
            $this->mysql_model->insert('stock',$datas);
            $this->mysql_model->insert('cangku',$datas);
            $bool = $this->db->trans_complete();
            if($bool){
                header("Content-type:text/html;charset=utf-8");
                echo "导入成功";
                exit();
            }else{
                header("Content-type:text/html;charset=utf-8");
                echo '导入失败！';
            }
        }else{
            $str = '';
            if(!empty($youwu)){
                $youwu_display = str_replace(',', '<br>', trim($youwu, ','));
                $str .= "以下物料编号的'是否到货'状态不是'未到货'或'已到货'：<br>".$youwu_display."<br>只能上传未到货的订单，请勿上传！<br>";
            }
            if(!empty($chongfu)){
                $chongfu_display = str_replace('),', ')<br>', trim($chongfu, ','));
                $str .= "以下物料编号+采购订单号已存在：<br>".$chongfu_display."<br>存在重复，请勿上传！<br>";
            }
            header("Content-type:text/html;charset=utf-8");
            echo $str;
        }

    }

    public function excelTime($date, $time = false) {
            if (function_exists('GregorianToJD')) {
                if (is_numeric($date)) {
                    $jd = GregorianToJD(1, 1, 1970);
                    $gregorian = JDToGregorian($jd + intval($date) - 25569);
                    $date = explode('/', $gregorian);
                    $date_str = str_pad($date[2], 4, '0', STR_PAD_LEFT) . "-" . str_pad($date[0], 2, '0', STR_PAD_LEFT) . "-" . str_pad($date[1], 2, '0', STR_PAD_LEFT) . ($time ? " 00:00:00" : '');
                    return $date_str;
                }
            } else {
                $date = $date > 25568 ? $date + 1 : 25569; /*There was a bug if Converting date before 1-1-1970 (tstamp 0)*/
                $ofs = (70 * 365 + 17 + 2) * 86400;
                $date = date("Y-m-d", ($date * 86400) - $ofs) . ($time ? " 00:00:00" : '');
            }
            return $date;
        }


	public function characet($data){
 	  if( !empty($data) ){
 	    $fileType = mb_detect_encoding($data , array('UTF-8','GBK','LATIN1','BIG5')) ;
 	    if( $fileType != 'UTF-8'){
 	      $data = mb_convert_encoding($data ,'utf-8' , $fileType);
 	    }
 	  }
 	  return $data;
 	}

	//ÂµÂ¼ÃÃ«excelÃÃÂ¼Ã¾
	public function importexcelcangku($filename)
	{

 ini_set("display_errors", "On");
 error_reporting(E_ALL | E_STRICT);

       require_once './application/libraries/PHPExcel/Classes/PHPExcel.php';
       require_once './application/libraries/PHPExcel/Classes/PHPExcel/IOFactory.php';
       require_once './application/libraries/PHPExcel/Classes/PHPExcel/Reader/Excel5.php';
	 $file = $filename;
 $files = explode('.' , $file);
 $num = count($files);
 $extension=$files[$num-1];

  if( $extension =='xlsx' )
{
 $objReader = new PHPExcel_Reader_Excel2007();
}
else
{

 $objReader = PHPExcel_IOFactory::createReader('Excel5');

}

        // $objReader = new PHPExcel_Reader_Excel2007();
	  // $objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format


       $objPHPExcel = $objReader->load($filename); //$filenameÂ¿ÃÃÃÃÃÃÃÂ´Â«ÂµÃÃÃÂ¼Ã¾Â£Â¬Â»Ã²ÃÃÃÃÃÂ¸Â¶Â¨ÂµÃÃÃÂ¼Ã¾
	 // sdafadsf
       $sheet = $objPHPExcel->getSheet(0);
       $highestRow = $sheet->getHighestRow(); // ÃÂ¡ÂµÃÃÃÃÃÃÃ½
	   //echo $highestRow."===========";
       $highestColumn = $sheet->getHighestColumn(); // ÃÂ¡ÂµÃÃÃÃÃÃÃ½

       $k = 0;
     //ÃÂ­Â»Â·Â¶ÃÃÂ¡excelÃÃÂ¼Ã¾,Â¶ÃÃÂ¡ÃÂ»ÃÃµ,Â²Ã¥ÃÃ«ÃÂ»ÃÃµ
	   $strquestion="";
	   //ÃÃ¥Â¿ÃÃÃÃÂ±Â±Ã­ÃÃ¯ÃÃ¦ÂµÃÃÃ¹ÃÃÃÃÃÂ¢
	  // $this->mysql_model->query('delete from ci_invoice_cursor',2);
	 //  $this->mysql_model->delete('orders_cursor','');

   for($orderint=3;$orderint<=$highestRow;$orderint++)

        {
		 $a = $objPHPExcel->getActiveSheet()->getCell("A".$orderint)->getValue();//Â»Ã±ÃÂ¡AÃÃÂµÃÃÂµ
         $b = $objPHPExcel->getActiveSheet()->getCell("B".$orderint)->getValue();//Â»Ã±ÃÂ¡BÃÃÂµÃÃÂµ
		 $c = $objPHPExcel->getActiveSheet()->getCell("C".$orderint)->getValue();//Â»Ã±ÃÂ¡cÃÃÂµÃÃÂµ
		 $d = $objPHPExcel->getActiveSheet()->getCell("D".$orderint)->getValue();//Â»Ã±ÃÂ¡dÃÃÂµÃÃÂµ
		 $e = $objPHPExcel->getActiveSheet()->getCell("E".$orderint)->getValue();//Â»Ã±ÃÂ¡eÃÃÂµÃÃÂµ
	     $f = $objPHPExcel->getActiveSheet()->getCell("F".$orderint)->getValue();//Â»Ã±ÃÂ¡fÃÃÂµÃÃÂµ
		 $g = $objPHPExcel->getActiveSheet()->getCell("G".$orderint)->getValue();//Â»Ã±ÃÂ¡gÃÃÂµÃÃÂµ
		 $h = $objPHPExcel->getActiveSheet()->getCell("H".$orderint)->getValue();//Â»Ã±ÃÂ¡hÃÃÂµÃÃÂµ
		 $i = $objPHPExcel->getActiveSheet()->getCell("I".$orderint)->getValue();//Â»Ã±ÃÂ¡hÃÃÂµÃÃÂµ
		 $j = $objPHPExcel->getActiveSheet()->getCell("J".$orderint)->getValue();//Â»Ã±ÃÂ¡hÃÃÂµÃÃÂµ
		 $k = $objPHPExcel->getActiveSheet()->getCell("K".$orderint)->getValue();//Â»Ã±ÃÂ¡hÃÃÂµÃÃÂµ
		 $l = $objPHPExcel->getActiveSheet()->getCell("L".$orderint)->getValue();//Â»Ã±ÃÂ¡hÃÃÂµÃÃÂµ
		 $m = $objPHPExcel->getActiveSheet()->getCell("M".$orderint)->getValue();//Â»Ã±ÃÂ¡hÃÃÂµÃÃÂµ
		 $n = $objPHPExcel->getActiveSheet()->getCell("N".$orderint)->getValue();//Â»Ã±ÃÂ¡hÃÃÂµÃÃÂµ
		 $o = $objPHPExcel->getActiveSheet()->getCell("O".$orderint)->getValue();//Â»Ã±ÃÂ¡hÃÃÂµÃÃÂµ
		 $l=$this->excelTime($l);
		 $m=$this->excelTime($m);
		 //if("=E".$orderint."*"."I".$orderint==$j){
			 $amonut=$f*$i;
		// }

		if(trim($k)!="未到货" && trim($k)!="已到货" && trim($k)!=""){
			 header("Content-type:text/html;charset=utf-8");
			 echo "以下物料编号$b的'是否到货'状态不是'未到货'或'已到货'，请勿上传！";

			 continue;
		 }

		 if($a==""){
			 header("Content-type:text/html;charset=utf-8");
			// echo iconv("GB2312","UTF-8",'ÂµÂ¼ÃÃ«ÃÃªÂ³Ã');
			echo $this->characet("ÂµÂ¼ÃÃ«ÃÃªÂ³Ã");
			 exit();
		 }


		 $count = $this->mysql_model->get_count('stock',array('goodsnumber'=>"$b",'ordernumber'=>"$h",'mdescription'=>"$c",'inventoryOld'=>"$d",'isDelete'=>0));
		if($count>0){
			header("Content-type:text/html;charset=utf-8");
			 echo "以下物料编号+采购订单号已存在：$b 存在重复，请勿上传！";
			 continue;

		}

		//  $count = $this->mysql_model->get_count('goods',array('number'=>$b));


		 //$rs=$this->mysql_model->get_rows('stock',array('goodsnumber'=>$b));
		//echo $h."=======";
		//exit();
	 // print_r($rs);
          // if(!empty($rs))
			//{
			  $data['goodsnumber']="$b";
			  $data['mdescription']="$c";
			  $data['inventoryOld']="$d";
			  $data['number']="$e";
			  $data['inventoryNew']="$f";
			  $data['mainUnit']="$g";
			  $data['ordernumber']="$h";
			  $data['price']="$i";
			  $data['amount']="$amonut";
			  $data['locationName']="$j";
			  $data['flagNo']="$k";
			  $data['flagtime']="$l";
			  $data['flagcontact']="$n";
			  $data['Arrivaltime']="$m";
			  $data['beizhu']="$o";
			 $this->mysql_model->insert('cangku',$data);
			 $this->mysql_model->insert('stock',$data);

			 //echo "true";
			// echo "ÂµÂ¼ÃÃ«Â³ÃÂ¹Â¦!ÃÃ¯ÃÃÂ±Ã ÂºÃÃÂªÂ£Âº".$b."<br/>";
		     //}
			// else
			// {
			// echo "Â²Â»Â´Ã¦ÃÃÃÃ¯ÃÃÂºÃÃÂªÂ£Âº".$b.",<br/>";
			// }



        }

	}


	//ÂµÂ¼ÃÃ«excelÃÃÂ¼Ã¾
    public function importexcelgoods($filename)
    {

ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

       require_once './application/libraries/PHPExcel/Classes/PHPExcel.php';
       require_once './application/libraries/PHPExcel/Classes/PHPExcel/IOFactory.php';
       require_once './application/libraries/PHPExcel/Classes/PHPExcel/Reader/Excel5.php';
	 $file = $filename;
 $files = explode('.' , $file);
 $num = count($files);
 $extension=$files[$num-1];

  if( $extension =='xlsx' )
{
 $objReader = new PHPExcel_Reader_Excel2007();
}
else
{

 $objReader = PHPExcel_IOFactory::createReader('Excel5');

}

        // $objReader = new PHPExcel_Reader_Excel2007();
	  // $objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format


       $objPHPExcel = $objReader->load($filename); //$filenameÂ¿ÃÃÃÃÃÃÃÂ´Â«ÂµÃÃÃÂ¼Ã¾Â£Â¬Â»Ã²ÃÃÃÃÃÂ¸Â¶Â¨ÂµÃÃÃÂ¼Ã¾
	 // sdafadsf
       $sheet = $objPHPExcel->getSheet(0);
       $highestRow = $sheet->getHighestRow(); // ÃÂ¡ÂµÃÃÃÃÃÃÃ½
       $highestColumn = $sheet->getHighestColumn(); // ÃÂ¡ÂµÃÃÃÃÃÃÃ½
       $k = 0;
     //ÃÂ­Â»Â·Â¶ÃÃÂ¡excelÃÃÂ¼Ã¾,Â¶ÃÃÂ¡ÃÂ»ÃÃµ,Â²Ã¥ÃÃ«ÃÂ»ÃÃµ
	   $strquestion="";
	   //ÃÃ¥Â¿ÃÃÃÃÂ±Â±Ã­ÃÃ¯ÃÃ¦ÂµÃÃÃ¹ÃÃÃÃÃÂ¢
	  // $this->mysql_model->query('delete from ci_invoice_cursor',2);
	   //$this->mysql_model->delete('invoice_cursor','');

   for($j=3;$j<=$highestRow;$j++)
        {

		 $a = $objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue();//Â»Ã±ÃÂ¡AÃÃÂµÃÃÂµ
         $b = $objPHPExcel->getActiveSheet()->getCell("B".$j)->getValue();//Â»Ã±ÃÂ¡BÃÃÂµÃÃÂµ
		 $c = $objPHPExcel->getActiveSheet()->getCell("C".$j)->getValue();//Â»Ã±ÃÂ¡cÃÃÂµÃÃÂµ
		 $d = $objPHPExcel->getActiveSheet()->getCell("D".$j)->getValue();//Â»Ã±ÃÂ¡dÃÃÂµÃÃÂµ
		 $e = $objPHPExcel->getActiveSheet()->getCell("E".$j)->getValue();//Â»Ã±ÃÂ¡eÃÃÂµÃÃÂµ
		 if($a==""){
			 header("Content-type:text/html;charset=utf-8");
			// echo iconv("GB2312","UTF-8",'ÂµÂ¼ÃÃ«ÃÃªÂ³Ã');
			echo $this->characet("ÂµÂ¼ÃÃ«ÃÃªÂ³Ã");
			 exit();
		 }
		//  $count = $this->mysql_model->get_count('goods',array('number'=>$b));

		  // $rs=$this->mysql_model->get_rows('goods',array('number'=>$b));

          // if(!empty($rs))
//{
		      $data['number']="$b";
			  $data['name']="$c";
			  $data['unitName']="$d";
			  $data['purPrice']="$e";

			  $count = $this->mysql_model->get_count('goods',array('number'=>"$b",'isDelete'=>0));
		if($count>0){
			header("Content-type:text/html;charset=utf-8");
			 echo "Â±Ã ÂºÃÃÂª$b"."ÂµÃÃÃ¯ÃÃÂ´Ã¦ÃÃÃÃÂ¸Â´Â£Â¬ÂµÂ¼ÃÃ«ÃÂ§Â°ÃÂ£Â¡";
			 echo $count."======";
			 continue;

		}

			 $this->mysql_model->insert('goods',$data);

			 //echo "true";
			// echo "ÂµÂ¼ÃÃ«Â³ÃÂ¹Â¦!ÃÃ¯ÃÃÂ±Ã ÂºÃÃÂªÂ£Âº".$b."<br/>";
		   //  }
			 //else
			// {
			// echo "Â²Â»Â´Ã¦ÃÃÃÃ¯ÃÃÂºÃÃÂªÂ£Âº".$b.",<br/>";
			// }



        }

	}

    //ÂµÂ¼ÃÃ«excelÃÃÂ¼Ã¾
    public function importExcelMaterial($filename)
    {

        ini_set("display_errors", "On");
        error_reporting(E_ALL | E_STRICT);

        require_once './application/libraries/PHPExcel/Classes/PHPExcel.php';
        require_once './application/libraries/PHPExcel/Classes/PHPExcel/IOFactory.php';
        require_once './application/libraries/PHPExcel/Classes/PHPExcel/Reader/Excel5.php';
        $file = $filename;
        $files = explode('.' , $file);
        $num = count($files);
        $extension=$files[$num-1];

        if( $extension =='xlsx' )
        {
            $objReader = new PHPExcel_Reader_Excel2007();
        }
        else
        {

            $objReader = PHPExcel_IOFactory::createReader('Excel5');

        }

        // $objReader = new PHPExcel_Reader_Excel2007();
        // $objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format


        $objPHPExcel = $objReader->load($filename); //$filenameÂ¿ÃÃÃÃÃÃÃÂ´Â«ÂµÃÃÃÂ¼Ã¾Â£Â¬Â»Ã²ÃÃÃÃÃÂ¸Â¶Â¨ÂµÃÃÃÂ¼Ã¾
        // sdafadsf
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // ÃÂ¡ÂµÃÃÃÃÃÃÃ½
        $highestColumn = $sheet->getHighestColumn(); // ÃÂ¡ÂµÃÃÃÃÃÃÃ½

        // Â¿ÂªÃÃ´ÃÃÃÃ±
        $this->db->trans_start();

        for($j=3;$j<=$highestRow;$j++)
        {

            $a = $objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue();//Â»Ã±ÃÂ¡AÃÃÂµÃÃÂµ
            $b = $objPHPExcel->getActiveSheet()->getCell("B".$j)->getValue();//Â»Ã±ÃÂ¡BÃÃÂµÃÃÂµ
            $c = $objPHPExcel->getActiveSheet()->getCell("C".$j)->getValue();//Â»Ã±ÃÂ¡cÃÃÂµÃÃÂµ
            $d = $objPHPExcel->getActiveSheet()->getCell("D".$j)->getValue();//Â»Ã±ÃÂ¡dÃÃÂµÃÃÂµ
            $e = $objPHPExcel->getActiveSheet()->getCell("E".$j)->getValue();//Â»Ã±ÃÂ¡EÃÃÂµÃÃÂµ
            $f = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();//Â»Ã±ÃÂ¡FÃÃÂµÃÃÂµ
            $g = $objPHPExcel->getActiveSheet()->getCell("G".$j)->getValue();//Â»Ã±ÃÂ¡GÃÃÂµÃÃÂµ
            $h = $objPHPExcel->getActiveSheet()->getCell("H".$j)->getValue();//Â»Ã±ÃÂ¡HÃÃÂµÃÃÂµ
            $i = $objPHPExcel->getActiveSheet()->getCell("I".$j)->getValue();//Â»Ã±ÃÂ¡HÃÃÂµÃÃÂµ

            if($a==""){
                break;
            }

            $data = [
                'number' => "$b",
                'name' => "$c",
                'spec' => "$d",
                'jianxing' => "$e",
                'unitName' => "$f",
                'unitCost' => $g,
                'goods' => "$h",
                'remark' => "$i"
            ];

            // ÃÃÂ¶ÃÃÃÂ·Ã±ÃÂªÂ¿ÃÂ£Â¬ÃÂªÂ¿ÃÃÃ¸Â¹Ã½
            if (empty($b)) {
                continue;
            }

            $count = $this->mysql_model->get_count('material',array('number'=>"$b",'isDelete'=>0));
            if($count>0){
                header("Content-type:text/html;charset=utf-8");
                echo iconv("GB2312","UTF-8","Â±Ã ÂºÃÃÂª$b"."ÂµÃÃÃ¯ÃÃÂ´Ã¦ÃÃÃÃÂ¸Â´Â£Â¬ÂµÂ¼ÃÃ«ÃÂ§Â°ÃÂ£Â¡");
                echo $count."======";
                continue;

            }

            $this->mysql_model->insert('material',$data);
        }
        // ÃÃ¡Â½Â»ÃÃÃÃ±
        $this->db->trans_commit();
        // ÃÃÃÂ¾ÂµÂ¼ÃÃ«ÃÃªÂ³Ã
        header("Content-type:text/html;charset=utf-8");
        // echo iconv("GB2312","UTF-8",'ÂµÂ¼ÃÃ«ÃÃªÂ³Ã');
        echo $this->characet("ÂµÂ¼ÃÃ«ÃÃªÂ³Ã");
    }

	//ÂµÂ¼ÃÃ«excelÃÃÂ¼Ã¾
	public function importexcelc($filename)
	{

 ini_set("display_errors", "On");
 error_reporting(E_ALL | E_STRICT);

       require_once './application/libraries/PHPExcel/Classes/PHPExcel.php';
       require_once './application/libraries/PHPExcel/Classes/PHPExcel/IOFactory.php';
       require_once './application/libraries/PHPExcel/Classes/PHPExcel/Reader/Excel5.php';
	 $file = $filename;
 $files = explode('.' , $file);
 $num = count($files);
 $extension=$files[$num-1];

  if( $extension =='xlsx' )
{
 $objReader = new PHPExcel_Reader_Excel2007();
}
else
{

 $objReader = PHPExcel_IOFactory::createReader('Excel5');

}

        // $objReader = new PHPExcel_Reader_Excel2007();
	  // $objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format


       $objPHPExcel = $objReader->load($filename); //$filenameÂ¿ÃÃÃÃÃÃÃÂ´Â«ÂµÃÃÃÂ¼Ã¾Â£Â¬Â»Ã²ÃÃÃÃÃÂ¸Â¶Â¨ÂµÃÃÃÂ¼Ã¾
	 // sdafadsf
       $sheet = $objPHPExcel->getSheet(0);
       $highestRow = $sheet->getHighestRow(); // ÃÂ¡ÂµÃÃÃÃÃÃÃ½
       $highestColumn = $sheet->getHighestColumn(); // ÃÂ¡ÂµÃÃÃÃÃÃÃ½
       $k = 0;
     //ÃÂ­Â»Â·Â¶ÃÃÂ¡excelÃÃÂ¼Ã¾,Â¶ÃÃÂ¡ÃÂ»ÃÃµ,Â²Ã¥ÃÃ«ÃÂ»ÃÃµ
	   $strquestion="";
	   //ÃÃ¥Â¿ÃÃÃÃÂ±Â±Ã­ÃÃ¯ÃÃ¦ÂµÃÃÃ¹ÃÃÃÃÃÂ¢
	  // $this->mysql_model->query('delete from ci_invoice_cursor',2);
	   //$this->mysql_model->delete('invoice_cursor','');

   for($j=3;$j<=$highestRow;$j++)
        {

		 $a = $objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue();//Â»Ã±ÃÂ¡AÃÃÂµÃÃÂµ
         $b = $objPHPExcel->getActiveSheet()->getCell("B".$j)->getValue();//Â»Ã±ÃÂ¡BÃÃÂµÃÃÂµ
		 $c = $objPHPExcel->getActiveSheet()->getCell("C".$j)->getValue();//Â»Ã±ÃÂ¡cÃÃÂµÃÃÂµ
		 $d = $objPHPExcel->getActiveSheet()->getCell("D".$j)->getValue();//Â»Ã±ÃÂ¡dÃÃÂµÃÃÂµ
		 $e = $objPHPExcel->getActiveSheet()->getCell("E".$j)->getValue();//ÃÂ´ÃÂ¬
		 $f = $objPHPExcel->getActiveSheet()->getCell("F".$j)->getValue();//WBSÃÂªÃÃÂºÃ
		 $g = $objPHPExcel->getActiveSheet()->getCell("G".$j)->getValue();//Â¹Â¤ÂµÂ¥ÂºÃ
		 $h = $objPHPExcel->getActiveSheet()->getCell("H".$j)->getValue();//ÃÃ®ÃÂ¿ÃÃ¨Â¼Ã
		 $i = $objPHPExcel->getActiveSheet()->getCell("I".$j)->getValue();//ÃÃ¯ÃÃÃÃªÃÃ«
		 $jj = $objPHPExcel->getActiveSheet()->getCell("J".$j)->getValue();//Â±Â¸ÃÂ¢
		 if($a==""){
          header("Content-type:text/html;charset=utf-8");
		  //echo iconv("GB2312","UTF-8",'ÂµÂ¼ÃÃ«ÃÃªÂ³Ã');
		  echo $this->characet("ÂµÂ¼ÃÃ«ÃÃªÂ³Ã");
		  exit();
		 }
		//  $count = $this->mysql_model->get_count('goods',array('number'=>$b));

		   //$rs=$this->mysql_model->get_rows('contact',array('number'=>$b));
			 //echo iconv("GB2312","UTF-8","$rs");
		 // exit();
			$rs=$this->db->query("select count(*) as num from ci_contact where number='$b' and isDelete=0");
			$result=$rs->row_array();
			//print_r($result['num']);
			//exit();
			//echo $result['num'];
			//exit();
           if($result['num']==0)
{
		      $data['number']="$b";
			  $data['name']="$c";
			  $data['cCategory']="2";
			  $data['cCategoryName']="$d";
			  $data['disable']="$e";
			  $data['wbs']="$f";
			  $data['gdnumber']="$g";
			  $data['design']="$h";
			  $data['apply']="$i";
			  $data['remark_']="$jj";
			 $this->mysql_model->insert('contact',$data);



			 //echo "true";
			// echo "ÂµÂ¼ÃÃ«Â³ÃÂ¹Â¦!ÃÃ¯ÃÃÂ±Ã ÂºÃÃÂªÂ£Âº".$b."<br/>";
		     }
			// else
			 //{
				//echo "Â²Â»Â´Ã¦ÃÃÃÃ¯ÃÃÂºÃÃÂªÂ£Âº".$b.",<br/>";
				//echo iconv("GB2312","UTF-8","ÃÃÂ´Ã¦ÃÃÂ±Ã ÂºÃÃÂªÂ£Âº".$b.",<br/>");
				//exit();
			// }



        }




	}


	//ÂµÂ¼ÃÃ«excelÃÃÂ¼Ã¾
	public function importexcelxiangmu($filename)
	{

 ini_set("display_errors", "On");
 error_reporting(E_ALL | E_STRICT);

       require_once './application/libraries/PHPExcel/Classes/PHPExcel.php';
       require_once './application/libraries/PHPExcel/Classes/PHPExcel/IOFactory.php';
       require_once './application/libraries/PHPExcel/Classes/PHPExcel/Reader/Excel5.php';
	 $file = $filename;
 $files = explode('.' , $file);
 $num = count($files);
 $extension=$files[$num-1];

  if( $extension =='xlsx' )
{
 $objReader = new PHPExcel_Reader_Excel2007();
}
else
{

 $objReader = PHPExcel_IOFactory::createReader('Excel5');

}

        // $objReader = new PHPExcel_Reader_Excel2007();
	  // $objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format


       $objPHPExcel = $objReader->load($filename); //$filenameÂ¿ÃÃÃÃÃÃÃÂ´Â«ÂµÃÃÃÂ¼Ã¾Â£Â¬Â»Ã²ÃÃÃÃÃÂ¸Â¶Â¨ÂµÃÃÃÂ¼Ã¾
	 // sdafadsf
       $sheet = $objPHPExcel->getSheet(0);
       $highestRow = $sheet->getHighestRow(); // ÃÂ¡ÂµÃÃÃÃÃÃÃ½
	   //echo $highestRow."===========";
       $highestColumn = $sheet->getHighestColumn(); // ÃÂ¡ÂµÃÃÃÃÃÃÃ½

       $k = 0;
     //ÃÂ­Â»Â·Â¶ÃÃÂ¡excelÃÃÂ¼Ã¾,Â¶ÃÃÂ¡ÃÂ»ÃÃµ,Â²Ã¥ÃÃ«ÃÂ»ÃÃµ
	   $strquestion="";
	   //ÃÃ¥Â¿ÃÃÃÃÂ±Â±Ã­ÃÃ¯ÃÃ¦ÂµÃÃÃ¹ÃÃÃÃÃÂ¢
	  // $this->mysql_model->query('delete from ci_invoice_cursor',2);
	 //  $this->mysql_model->delete('orders_cursor','');

   for($orderint=2;$orderint<=$highestRow;$orderint++)

        {
		 $a = $objPHPExcel->getActiveSheet()->getCell("A".$orderint)->getValue();//Â»Ã±ÃÂ¡AÃÃÂµÃÃÂµ
         $b = $objPHPExcel->getActiveSheet()->getCell("B".$orderint)->getValue();//Â»Ã±ÃÂ¡BÃÃÂµÃÃÂµ
		 $c = $objPHPExcel->getActiveSheet()->getCell("C".$orderint)->getValue();//Â»Ã±ÃÂ¡cÃÃÂµÃÃÂµ
		 $d = $objPHPExcel->getActiveSheet()->getCell("D".$orderint)->getValue();//Â»Ã±ÃÂ¡dÃÃÂµÃÃÂµ
		 $e = $objPHPExcel->getActiveSheet()->getCell("E".$orderint)->getValue();//Â»Ã±ÃÂ¡eÃÃÂµÃÃÂµ
	     $f = $objPHPExcel->getActiveSheet()->getCell("F".$orderint)->getValue();//Â»Ã±ÃÂ¡fÃÃÂµÃÃÂµ
		 $g = $objPHPExcel->getActiveSheet()->getCell("G".$orderint)->getValue();//Â»Ã±ÃÂ¡gÃÃÂµÃÃÂµ
		 $h = $objPHPExcel->getActiveSheet()->getCell("H".$orderint)->getValue();//Â»Ã±ÃÂ¡hÃÃÂµÃÃÂµ
		 $i = $objPHPExcel->getActiveSheet()->getCell("I".$orderint)->getValue();//Â»Ã±ÃÂ¡hÃÃÂµÃÃÂµ
		 $j = $objPHPExcel->getActiveSheet()->getCell("J".$orderint)->getValue();//Â»Ã±ÃÂ¡hÃÃÂµÃÃÂµ
		 $k = $objPHPExcel->getActiveSheet()->getCell("K".$orderint)->getValue();//Â»Ã±ÃÂ¡hÃÃÂµÃÃÂµ
		 if("=F".$orderint."*"."H".$orderint==$i){

			 $i=$f*$h;
		 }

		 if($a==""){
			 header("Content-type:text/html;charset=utf-8");
			 //echo iconv("GB2312","UTF-8",'ÂµÂ¼ÃÃ«ÃÃªÂ³Ã');
			 echo $this->characet("ÂµÂ¼ÃÃ«ÃÃªÂ³Ã");
			 exit();
		 }



		//  $count = $this->mysql_model->get_count('goods',array('number'=>$b));

		//$count = $this->mysql_model->get_count('xiangmuku',array('ordernumber'=>"$c",'mdescription'=>"$e",'number'=>"$d",'isDelete'=>0));
		/*if($count>0){
			header("Content-type:text/html;charset=utf-8");
			 echo "Â±Ã ÂºÃÃÂª$b"."ÂµÃÃÃ¯ÃÃÂ´Ã¦ÃÃÃÃÂ¸Â´Â£Â¬ÂµÂ¼ÃÃ«ÃÂ§Â°ÃÂ£Â¡");
			 continue;

		}
		*/

		 //$rs=$this->mysql_model->get_rows('stock',array('goodsnumber'=>$b));
		//echo $h."=======";
		//exit();
	 // print_r($rs);
          // if(!empty($rs))
			//{
		      $data['name']="$b";
			  $data['ordernumber']="$c";
			  $data['number']="$d";
			  $data['mdescription']="$e";
			  $data['num']="$f";
			  $data['mainUnit']="$g";
			  $data['price']="$h";
			  $data['amount']="$i";
			  $data['duiwu']="$j";
			  $data['beizhu']="$k";
			 $this->mysql_model->insert('xiangmuku',$data);

			 //echo "true";
			// echo "ÂµÂ¼ÃÃ«Â³ÃÂ¹Â¦!ÃÃ¯ÃÃÂ±Ã ÂºÃÃÂªÂ£Âº".$b."<br/>";
		     //}
			// else
			// {
			// echo "Â²Â»Â´Ã¦ÃÃÃÃ¯ÃÃÂºÃÃÂªÂ£Âº".$b.",<br/>";
			// }



        }

	}



	function mbStrSplit ($string, $len=1) {
  $start = 0;
  $strlen = mb_strlen($string);
  while ($strlen) {
    $array[] = mb_substr($string,$start,$len,"utf8");
    $string = mb_substr($string, $len, $strlen,"utf8");
    $strlen = mb_strlen($string);
  }
  return $array;
}


}