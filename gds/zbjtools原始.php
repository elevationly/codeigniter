<?php
header("Access-Control-Allow-Origin:*");//解决跨域请求问题
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers:x-requested-with, content-type');
header("content-Type: text/html; charset=utf-8");//字符编码设置
$servername = "localhost";
//数据库账号
$username = "root";
//数据库密码
$password = "123456";
//数据库名
$dbname = "zbjtools";

// 创建连接
$conn =mysqli_connect($servername, $username, $password, $dbname);
// 检测连接
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//连接数据库表
$sql = "SELECT * FROM ci_cangku where locationName like'连江凤城%' AND inventoryNew >0 AND isDelete=0" ;
$result = $conn->query($sql);
$arr = array();
// 输出每行数据
while($row = $result->fetch_assoc()) {
    $count=count($row);//不能在循环语句中，由于每次删除row数组长度都减小
     echo($count);
    for($i=0;$i<$count;$i++){
        unset($row[$i]);//删除冗余数据
    }
    array_push($arr,$row);

}

//print_r($arr);
echo json_encode($arr,JSON_UNESCAPED_UNICODE);//json编码
$conn->close();

?>
