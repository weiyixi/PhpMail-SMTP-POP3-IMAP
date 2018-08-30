<?php
/**
 * Created by PhpStorm.
 * User: weiyixi
 * Date: 2018/8/30
 * Time: 11:30
 */


require_once __DIR__ . '/src/__autoload.php';
set_time_limit(0);

$host = "tls://pop.126.com"; //‘tls：//’为ssl协议加密，端口走加密端口
$user = "XXXXXXXX@126.com"; //邮箱
$pass = "XXXXX"; //密码
$rec = new \src\pop3\Pop3($host, 995, 3);
//打开连接
if (!$rec->open())
    die($rec->err_str);
echo "open ";
//登录
if (!$rec->login($user, $pass)) {
    var_dump($rec->err_str);
    die;
}
echo "login";

if (!$rec->stat())
    die($rec->err_str);
echo "You  have" . $rec->messages . "emails,total size:" . $rec->size . "<br>";
if ($rec->messages > 0) {
    //读取邮件列表
    if (!$rec->listmail())
        die($rec->err_str);
    echo "Your mail list：<br>";
    for ($i = 1; $i <= count($rec->mail_list); $i++) {
        echo "mailId:" . $rec->mail_list[$i]['num'] . "Size：" . $rec->mail_list[$i]['size'] . "<BR>";
    }
    //获取一个邮件
    //read One email
    $rec->getmail(1);
    echo "getHeader：<br>";
    for ($i = 0; $i < count($rec->head); $i++) {
        echo htmlspecialchars($rec->head[$i]) . "<br>\n";
    }

    echo "getContent：<BR>";
    for ($i = 0; $i < count($rec->body); $i++) {
        echo htmlspecialchars($rec->body[$i]) . "<br>\n";
    }
}
$rec->close();

die;