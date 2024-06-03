<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once APPPATH . 'libraries/PHPMailer/src/Exception.php';
require_once APPPATH . 'libraries/PHPMailer/src/PHPMailer.php';
require_once APPPATH . 'libraries/PHPMailer/src/SMTP.php';

class Phpmailer_lib
{
    public function __construct()
    {
        $this->mail = new PHPMailer();
        
        $this->mail->isSMTP();
        $this->mail->SMTPAuth = true;
        $this->mail->Port = 465; // Ganti dengan port SMTP yang sesuai
        $this->mail->SMTPSecure = 'ssl';
        $this->mail->SMTPDebug = SMTP::DEBUG_LOWLEVEL;  
        // $this->mail->AuthType = 'PLAIN';
        $this->mail->AuthType = 'LOGIN';
        $this->mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => false,
            )
        );

        // $this->mail->Host = 'ssl://smtp.googlemail.com'; // Ganti dengan host SMTP yang sesuai
        // $this->mail->Username = 'bitautoemail.noreply@gmail.com'; // Ganti dengan username email Anda
        // $this->mail->Password = 'gnumqmnshwqavhnt'; // Ganti dengan password email Anda

        $this->mail->Host = 'ssl://mail.bhakti.co.id';//
        $this->mail->Username = 'bhaktiautoemail.noreply@bhakti.co.id';
        $this->mail->Password = '@9i3X5ku8';//
    }

    public function sendEmail($to, $subject, $message)
    {
       
        // $this->mail->setFrom('bitautoemail.noreply@gmail.com', 'Your Name'); // Ganti dengan alamat email dan nama pengirim
        $this->mail->setFrom('bhaktiautoemail.noreply@bhakti.co.id', 'Your Name');
        
        $this->mail->addAddress($to); // Tambahkan alamat email penerima
        $this->mail->Subject = $subject;
        $this->mail->Body = $message;

        if ($this->mail->send()) {
            return true;
        } else {
            echo $this->mail->ErrorInfo.'<br>';
            return false;
        }
    }
}
?>