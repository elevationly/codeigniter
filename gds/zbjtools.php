<?php
header("Access-Control-Allow-Origin:*");//解决跨域请求问题
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers:x-requested-with, content-type');
header("content-Type: text/html; charset=utf-8");//字符编码设置
$dbhost = 'localhost:3306'; // mysql服务器主机地址
$dbuser = 'root';            // mysql用户名
$dbpass = '123456';          // mysql用户名密码
$conn = mysqli_connect($dbhost, $dbuser, $dbpass);
if(! $conn )
{
    die('连接失败: ' . mysqli_error($conn));
}
// 设置编码，防止中文乱码
mysqli_query($conn , "set names utf8");

$sql =  "SELECT * FROM ci_cangku where locationName like'连江凤城%' AND inventoryNew >0 AND isDelete=0" ;

mysqli_select_db( $conn, 'zbjtools' );
$result = mysqli_query( $conn, $sql );
$arr = array();
// 输出每行数据
while($row = $result->fetch_assoc()) {
    $count=count($row);//不能在循环语句中，由于每次删除row数组长度都减小
    for($i=0;$i<$count;$i++){
        unset($row[$i]);//删除冗余数据
    }
    array_push($arr,$row);

}

//print_r($arr);
echo json_encode($arr,JSON_UNESCAPED_UNICODE);//json编码
$conn->close();

?>
