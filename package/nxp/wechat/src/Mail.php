<?php

namespace Nxp\Wechat;


require_once __DIR__ . "/../Mail/class.phpmailer.php";
require_once __DIR__ . "/../Mail/class.smtp.php";

class Mail
{
    public function __construct()
    {
        $this->mail = new \PHPMailer();
    }

    public function sendMail($params)
    {
        $this->mail->isSMTP();// 使用SMTP服务
        $this->mail->CharSet = "utf8";// 编码格式为utf8，不设置编码的话，中文会出现乱码
        $this->mail->Host = config('parameter.MAIL.host');// 发送方的SMTP服务器地址
        $this->mail->SMTPAuth = true;// 是否使用身份验证
        $this->mail->Username = config('parameter.MAIL.username');// 发送方的邮箱用户名
        $this->mail->Password = config('parameter.MAIL.password');// 发送方的邮箱密码，注意用邮箱这里填写的是“客户端授权密码”而不是邮箱的登录密码！
        $this->mail->SMTPSecure = "ssl";// 使用ssl协议方式
        $this->mail->Port = config('parameter.MAIL.port');// qq邮箱的ssl协议方式端口号是465/587
        $this->mail->Form = "=?utf-8?B?" . base64_encode("美乐") . "?=";
        $this->mail->Helo = "=?utf-8?B?" . base64_encode("美乐") . "?=";
        $this->mail->setFrom(config('parameter.MAIL.username'), "=?utf-8?B?" . base64_encode('美乐') . "?=");// 设置发件人信息
        if (is_array($params['address'])) {
            foreach ($params['address'] as $k => $v) {
                $this->mail->addAddress($v, $v);     //设置收件的地址
            }
        } else if (is_string($params['address'])) {
                $this->mail->addADDress($params['address'], $params['address']);
        } else {
            Err('请输入正确的邮箱格式');
        }
        $this->mail->IsHTML(true);
        $this->mail->Subject = "=?utf-8?B?" . base64_encode($params['title']) . "?=";// 邮件标题
        $this->mail->Body = $params['message'];
        if (!$this->mail->send()) {
            return "Mailer Error: " . $this->mail->ErrorInfo;// 输出错误信息
        } else {
            return 'Message has been sent.';
        }
    }
}