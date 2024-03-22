<?php

namespace Jack\emailLog\XF\Mail;

use function count;

class Mail extends XFCP_Mail
{

	public function setContent($subject, $htmlBody, $textBody = null) : Mail
	{
    	$mail = parent::setContent($subject, $htmlBody, $textBody);

		$emails = $mail->message->getTo();

		if (count($emails) > 0)
		{
			foreach($emails as $emailAddress => $username){
				$log = \XF::em()->create('Jack\emailLog:EmailLog');
				if(!empty($username)){
					$user = \XF::finder('XF:User')->where('username', $username)->fetchOne();
					$log->user_id = $user->user_id;
				}
				$log->email = $emailAddress;
				$log->subject = $mail->message->getSubject();
				$log->save();
			}
			
		}

		return $mail;
	}

}