<?php
/**
 * Created by PhpStorm.
 * User: weiyixi
 * Date: 2018/8/30
 * Time: 11:30
 */
require_once __DIR__ . '/../src/__autoload.php';
set_time_limit(0);
//IMAP收件
//  argument is the directory into which attachments are to be saved:
$mailbox = new \src\PhpImap\Mailbox('{imap.qq.com:993/imap/ssl}INBOX', 'XXXXXXX@qq.com', 'XXXXXXX', __DIR__);
//Read all messaged into an array:
$mailsIds = $mailbox->searchMailbox('ALL');
if (!$mailsIds) {
    die('Mailbox is empty');
}
// Get the first message and save its attachment(s) to disk:
//获取一份邮件对象 打印出来
$mail = $mailbox->getMail($mailsIds[0]);
var_dump($mail);
die;
