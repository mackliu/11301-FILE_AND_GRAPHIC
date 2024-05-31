<?php

$dsn = "mysql:host=localhost;charset=utf8;dbname=school";
$pdo = new PDO($dsn, 'root', '');


function all($table, $where)
{
    global $pdo;
    $sql = "SELECT * FROM `{$table}` {$where}";
    $rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

    return $rows;
}

function find($table, $arg)
{
    global $pdo;

    $sql = "SELECT * FROM `{$table}` WHERE ";

    if (is_array($arg)) {

        $tmp = array2sql($arg);

        $sql .= join(" && ", $tmp);
    } else {

        $sql .= " `id`='{$arg}'";
    }

    //echo $sql;

    $row = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

    return $row;
}


function save($table, $array)
{

    if (isset($array['id'])) {
        //update
        update($table, $array, $array['id']);
    } else {
        //insert
        insert($table, $array);
    }
}


/**
 * 更新資料表中的資料
 * @param string $table 資料表名稱
 * @param array $cols 欄位名稱和對應的值
 * @param mixed $arg 條件參數，可以是陣列或單一值
 * @return int 返回受影響的行數
 */
function update($table, $cols, $arg)
{
    //宣告全域變數
    global $pdo;

    //建立SQL語法
    $sql = "UPDATE `{$table}` SET ";

    //使用迴圈將欄位名稱和值組合成字串
    $tmp = array2sql($cols);

    $sql .= join(",", $tmp);

    if (is_array($arg)) {
        $tt = array2sql($arg);

        $sql .= " WHERE " . join(" && ", $tt);
    } else {
        $sql .= " WHERE `id`='{$arg}'";
    }
    echo $sql;
    return $pdo->exec($sql);
}

function insert($table, $cols)
{
    global $pdo;

    $sql = "INSERT INTO `{$table}` ";

    $sql .= "(`" . join("`,`", array_keys($cols)) . "`)";

    $sql .= " VALUES('" . join("','", $cols) . "')";

    //echo $sql;

    return $pdo->exec($sql);
}

function del($table, $arg)
{
    global $pdo;

    $sql = "DELETE FROM `{$table}` WHERE ";

    if (is_array($arg)) {
        $tmp = array2sql($arg);

        $sql .= join(" && ", $tmp);
    } else {
        $sql .= " `id`='{$arg}'";
    }

    return $pdo->exec($sql);
}

function array2sql($array)
{
    foreach ($array as $key => $value) {
        $tmp[] = "`$key`='$value'";
    }

    return $tmp;
}

function q($sql)
{
    global $pdo;
    return $pdo->query($sql)->fetchAll();
}

/**
 * 在頁面上快速顯示陣列內容
 * direct dump
 * @param $array 輸入的參數需為陣列
 */
function dd($array)
{
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}
