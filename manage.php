<?php
include_once "db.php";
/**
 * 1.建立資料庫及資料表來儲存檔案資訊
 * 2.建立上傳表單頁面
 * 3.取得檔案資訊並寫入資料表
 * 4.製作檔案管理功能頁面
 */
if (!empty($_FILES) && $_FILES['file']['error'] == 0) {
    $data['name'] = $_FILES['file']['name'];
    $data['type'] = $_FILES['file']['type'];
    $data['size'] = $_FILES['file']['size'];
    $data['descrption'] = $_POST['descrption'];
    save('manage', $data);

    move_uploaded_file($_FILES['file']['tmp_name'], 'materials/' . $_FILES['file']['name']);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>檔案管理功能</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table {
            border-collapse: collapse;
            margin: 10px auto;
            width: 60%;

        }

        td {
            border: 1px solid #ccc;
            padding: 5px 10px;
            text-align: center;
        }

        .imgs {
            width: 150px;

        }
    </style>
</head>

<body>
    <h1 class="header">檔案管理練習</h1>
    <!----建立上傳檔案表單及相關的檔案資訊存入資料表機制----->

    <form action="manage.php" method="post" enctype="multipart/form-data">
        <input type="file" name="file"><br>
        <textarea name="descrption" id=""></textarea><br>
        <input type="submit" value="上傳">
    </form>

    <!----透過資料表來顯示檔案的資訊，並可對檔案執行更新或刪除的工作----->
    <table>
        <tr>
            <td>id</td>
            <td>縮圖</td>
            <td>檔名</td>
            <td>類型</td>
            <td>大小</td>
            <td>描述</td>
            <td>操作</td>
        </tr>
        <?php
        $rows = all('manage');
        foreach ($rows as $row) {
        ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td>
                    <a href="./materials/<?= $row['name']; ?>" download>
                        <?php
                        switch ($row['type']) {
                            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                                echo "<img src='./word.webp' class='icon'>";
                                break;
                            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                                echo "<img src='./excel.webp' class='icon'>";
                                break;
                            default:
                                echo "<img src='./materials/{$row['name']}' class='imgs'>";
                        }
                        ?>
                    </a>
                </td>
                <td><?= $row['name']; ?></td>
                <td><?= $row['type']; ?></td>
                <td><?= $row['size']; ?></td>
                <td><?= $row['descrption']; ?></td>
                <td>
                    <a href="#">編輯</a><a href="#">刪除</a>
                </td>
            </tr>
        <?php
        }
        ?>
    </table>



</body>

</html>