<?php

// 注：
// ================
// SMTP是发送邮件的协议，默认端口 25
// POP3是接收邮件的协议，收取邮件就是编写一个MUA作为客户端，从MDA把邮件获取到用户的电脑或者手机上。收取邮件最常用的协议是POP协议，目前版本号是3，俗称POP3。


// 例：
// ----------------
// 网易163邮箱示例
// send_mail  "xxxxxxx@163.com"
// password 'xxxxx'
// host 'smtp.163.com'

// 示例方法
function send_email($send_mail, $send_password, $host, $to_email, $title = '', $content = '', $path = '')
{
	// 实例化phpmailer
	$mail = new PHPMailer();

	// 设置发送邮件的协议：SMTP
	$mail->IsSMTP();
	//是否使用HTML格式
	$mail->IsHTML(true);
	// 超时时间
	$mail->Timeout  = 40;
	// 打开SMTP
	$mail->SMTPAuth = true;

	// 发送邮件的服务器
	$mail->Host = $host;
	
	// SMTP账户
	$mail->Username = $send_mail;

	// SMTP密码
	$mail->Password = $send_password;
	$mail->From = $send_mail;

	$mail->FromName = "xxx中心";
	if (is_array($to_email)) {
	    foreach ($to_email as $v) {
	        $mail->AddAddress("$v", "");
	    }
	} else {
	   $mail->AddAddress("$to_email", "");
	}

	if ($path) {
	    $filename = basename($path);
	    $mail->AddAttachment($path,$filename); // 添加附件,并指定名称
	}

	//设置字符集编码
	$mail->CharSet = "UTF-8";
	$mail->Subject = "=?UTF-8?B?".base64_encode($title)."?=";
	//邮件内容（可以是HTML邮件）
	$mail->Body = $content;

	$error_info = '';
	if (!$mail->Send()) {
	    $error_info = $mail->ErrorInfo;
	    $email_status = false;
	} else {
	    $email_status = true;
	}

    // add log

	return $email_status;
}

// test


