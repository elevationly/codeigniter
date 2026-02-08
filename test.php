<!DOCTYPE html>
<html>
<head>
    <title>图形识别样例</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
</head>
<body>

<form action="" method="post" enctype="multipart/form-data" name="upload_form">

    <label>选择图片文件</label>

    <input name="imgfile" type="file" accept="image/gif, image/jpeg"/>

    <input name="upload" type="submit" value="上传" />

</form>

<?php
// 判断是
/**
 * Created by PhpStorm.
 * User: marico
 * Date: 2019-08-04
 * Time: 16:13
 */
// var_dump(file_exists("test.jpg"));die();
//新建一个图像对象
if (isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD'])=='POST') {

    // 判断是否上传图片
    if (isset($_FILES) && !empty($_FILES)) {
        // 读取图片地址
        $file = $_FILES['imgfile']['tmp_name'];
        // 判断文件是否存在
        if (file_exists($file)) {
            $image = new ZBarCodeImage($file);
            // 创建一个二维码识别器
            $scanner = new ZBarCodeScanner();
            //识别图像
            $barcode = $scanner->scan($image);
            //循环输出二维码信息
            if (!empty($barcode)) {
                foreach ($barcode as $code) {
                    // echo $code['type'];//图像的条码类型
                    echo sprintf('条码识别结果：%s', $code['data']).PHP_EOL;//条码的数据
                }
            } else {
                // 未获取到上传图片
                echo '未识别到任何条形码'.PHP_EOL;
            }
            // 打印图片
            echo sprintf('<br /> <img src="data:image/jpeg;base64,%s" />', base64_encode(file_get_contents($file))).PHP_EOL;
        } else {
            // 未获取到上传图片
            echo '未获取到上传图片'.PHP_EOL;
        }
    } else {
        // 未获取到上传图片
        echo '未获取到上传图片'.PHP_EOL;
    }
}
?>

</body>

</html>

