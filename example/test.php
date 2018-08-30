<?php

require_once __DIR__ . '/src/__autoload.php';
set_time_limit(0);

/**
 * @param $sendto_email  收件地址
 * @param $sendto_name   收件人
 * @param $subject       邮件标题
 * @param $body          邮件内容
 * @param $user_name     送件人
 * @param $post_array =[
 *          'server_address'=>'smtp.qq.com',    //smtp  服务器
 *          'port'=>465,                        //端口
 *          'mail_id'=>'AAAA@qq.com',          //账号
 *          'mail_pwd'=>'密码',                   //密码
 *          'mail_address'=>'发件人邮箱'
 * ]
 * @return bool
 * @throws \src\PHPMailer\Exception
 */
var_dump(smtp_mail('XXXXXX@qq.com', 'XXXX', 'hahah', 'aaaaaa', 'XXX', [
    'server_address' => 'smtp.126.com',    //smtp  服务器
    'port' => 465,                        //端口
    'mail_id' => 'XXXXX@126.com',          //账号
    'mail_pwd' => 'XXXXX',                   //密码
    'mail_address' => 'XXXXXX@126.com'
]));
function smtp_mail($sendto_email, $sendto_name, $subject, $body, $user_name, $post_array)
{
    $mail = new \src\PHPMailer\PHPMailer();
    $mail->SMTPDebug = 0;   // 开启Debug
    $mail->IsSMTP();                // 使用SMTP模式发送新建
    $mail->Host = $post_array['server_address']; // QQ企业邮箱SMTP服务器地址
    $mail->Port = $post_array['port'];  //邮件发送端口，一定是465
    $mail->SMTPAuth = true;         // 打开SMTP认证，本地搭建的也许不会需要这个参数
    $mail->SMTPSecure = "ssl";  // 打开SSL加密，这一句一定要
    $mail->Username = $post_array['mail_id'];   // SMTP用户名
    $mail->Password = $post_array['mail_pwd'];        // 为QQ邮箱SMTP的独立密码，即授权码
    $mail->From = $post_array['mail_address'];      // 发件人邮箱
    $mail->FromName = $user_name;// $post_array['mail_address'];  // 发件人

    $mail->CharSet = "UTF-8";            // 这里指定字符集！
    $mail->Encoding = "base64";
    if (!$mail->AddAddress($sendto_email, $sendto_name)) {
        return false;
    }  // 收件人邮箱和姓名
    $mail->addCC($post_array['mail_address']);//抄送
    $mail->IsHTML(false);  // send as HTML
    // 邮件主题
    $mail->Subject = $subject;

    $mail->Body = $body;      //isHTML是true 的时候  这里body有效
    $mail->AltBody = str_replace("<br/>", "\r\n", $body);  //isHTML是false 的时候  这里body有效
    if (!$mail->Send()) {
        return false;
    } else {
        return true;
    }
}


