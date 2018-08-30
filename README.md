### PhpMail-SMTP-POP3-IMAP 邮件接收发送  Mail receiving and sending
最近遇到一个工程需要自动发件（SMTP）收件(IMAP/POP3)功能,发现很难找到一个PHP的完整包来使用<br/>
所以自己综合phpMailer，PhpImap，csdn博客等各开源代码，做一个收发件都包括的工程，供新人使用。<br/>

##imap 收件示例
	require_once __DIR__ . '/src/__autoload.php';
	set_time_limit(0);
	//IMAP收件
	//argument is the directory into which attachments are to be saved:
	$mailbox = new \src\PhpImap\Mailbox('{imap.qq.com:993/imap/ssl}INBOX', 'XXXXXXX@qq.com', 'XXXXXXX', __DIR__);
	//读取所有邮件id到数组:
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
##pop3 收件示例
	require_once __DIR__ . '/src/__autoload.php';
	set_time_limit(0);
    
    $host = "tls://pop.126.com"; //‘tls：//’为ssl协议加密，端口走加密端口
    $user = "XXXXXX@126.com"; //邮箱
    $pass = "XXXXXX"; //密码
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
##SMTP 发件

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
    

