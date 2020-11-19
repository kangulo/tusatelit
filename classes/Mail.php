<?php
declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

class Mail
{
    private $to;
    private $from;
    private $headers;
    private $message;
    private $subject;
    
    
    public function __construct($to = NULL, $headers = array())
    {
        $this->to = $to == NULL ? "contact@respondercorp.com" : $to;
        if(empty($headers))
        {
            $this->headers = array("MIME-Version: 1.0","Content-type: text/html; charset=UTF-8","from: Respondercorp.com <noreply@respondercorp.com>");
        }
        else
        {
            $this->headers = $headers;
        }
    }
    
    public function setTo($to)
    {
        $email = filter_var($to,FILTER_VALIDATE_EMAIL);
        if($email)
        {
            $this->to = $email;
        }
        else
        {
            return FALSE;
        }
    }
    
    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }
    
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }
    
    public function setHeaders($headers)
    {
        if(is_array($headers))
        {
            $this->headers = $headers;
        }
    }

    public function send($message = NULL)
    {
        $message = $message == NULL ? $this->message : $message;
        $headers = implode("\r\n", $this->headers);
        return wp_mail($this->to,$this->subject,$message,$headers);
    }
    
    public function setMessageFromTemplate($type = 'REGULAR_EMAIL',$vars = array())
    {
    	switch ($type) {
            case "CONFIRMATION_EMAIL":
                $this->subject = "Welcome! Please Confirm Your Email";            
                $this->message = file_get_contents(get_template_directory_uri().'/email_templates/confirm_email.html');
                if(!empty($vars))
                {
                    foreach($vars as $var_name => $var_content)
                    {
                        $this->message = str_replace("[$var_name]", $var_content, $this->message);
                    }
                }
                
                break;

            case "REGULAR_EMAIL":
                $this->subject = "Regular Email";            
                $this->message = file_get_contents(get_template_directory_uri().'/email_templates/regular_email.html');
                if(!empty($vars))
                {
                    foreach($vars as $var_name => $var_content)
                    {
                        $this->message = str_replace("[$var_name]", $var_content, $this->message);
                    }
                }
                
                break;

            case "INVITATION_EMAIL":
                $this->subject = "Invitation Email";            
                $this->message = file_get_contents(get_template_directory_uri().'/email_templates/invitation_email.html');
                if(!empty($vars))
                {
                    foreach($vars as $var_name => $var_content)
                    {
                        $this->message = str_replace("[$var_name]", $var_content, $this->message);
                    }
                }
                
                break;
            
            default:
                $this->subject = "Regular Email";            
                $this->message = file_get_contents(get_template_directory_uri().'/email_templates/regular_email.html');
                if(!empty($vars))
                {
                    foreach($vars as $var_name => $var_content)
                    {
                        $this->message = str_replace("[$var_name]", $var_content, $this->message);
                    }
                }
                break;
        }
    }
}