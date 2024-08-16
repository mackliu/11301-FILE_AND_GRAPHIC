<?php

/****
 * 1.建立資料庫及資料表
 * 2.建立上傳圖案機制
 * 3.取得圖檔資源
 * 4.進行圖形處理
 *   ->圖形縮放
 *   ->圖形加邊框
 *   ->圖形驗證碼
 * 5.輸出檔案
 */

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>圖形處理</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1 class="header">圖形處理練習</h1>
    <!---建立檔案上傳機制--->
    <form action="?" method="post" enctype="multipart/form-data">
        <label for="img">選擇圖片檔案上傳:</label>
        <input type="file" name="img" id="img">
        <input type="submit" value="上傳">
    </form>

    <!----縮放圖形----->
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // 設定上傳目標資料夾
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["img"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // 檢查檔案是否為圖片
        $check = getimagesize($_FILES["img"]["tmp_name"]);
        if ($check === false) {
            die("檔案不是圖片。");
        }

        // 將檔案上傳到伺服器
        if (move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) {
            echo "檔案 " . basename($_FILES["img"]["name"]) . " 已經上傳。";

            // 使用 GD 函式庫縮小圖片
            switch ($imageFileType) {
                case 'jpg':
                case 'jpeg':
                    $source = imagecreatefromjpeg($target_file);
                    break;
                case 'png':
                    $source = imagecreatefrompng($target_file);
                    break;
                case 'gif':
                    $source = imagecreatefromgif($target_file);
                    break;
                default:
                    die("不支援的圖片格式。");
            }

            // 獲取原始圖片大小
            list($width, $height) = getimagesize($target_file);

            // 計算新尺寸
            $new_width = $width * 0.5;
            $new_height = $height * 0.5;

            // 創建新圖片資源
            $thumb = imagecreatetruecolor($new_width, $new_height);

            // 將原圖片縮放並複製到新圖片
            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

            // 儲存縮小後的圖片
            $output_file = $target_dir . "resized_" . basename($target_file);
            switch ($imageFileType) {
                case 'jpg':
                case 'jpeg':
                    imagejpeg($thumb, $output_file);
                    break;
                case 'png':
                    imagepng($thumb, $output_file);
                    break;
                case 'gif':
                    imagegif($thumb, $output_file);
                    break;
            }

            echo "圖片已成功縮小並儲存為 " . $output_file;
            echo "<img src='{$output_file}' alt='縮小後的圖片'>";
            // 釋放記憶體
            imagedestroy($source);
            imagedestroy($thumb);
        } else {
            echo "上傳過程中出錯。";
        }
    }
    ?>


    <!----圖形加邊框----->


    <!----產生圖形驗證碼----->



</body>

</html>