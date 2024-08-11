<?php

namespace Jack\emailLog\XF\Mail;

use function count;

class Mail extends XFCP_Mail
{

	public function setContent($subject, $htmlBody, $textBody = null) : Mail
	{
    	$mail = parent::setContent($subject, $htmlBody, $textBody);

		$versionId = \XF::$versionId;
		if($versionId > 2030000)
			$emails = $mail->email->getTo();
		else
			$emails = $mail->message->getTo();

		if (count($emails) > 0)
		{
			if ($versionId >2030000){

				foreach($emails as $email){
					$log = \XF::em()->create('Jack\emailLog:EmailLog');
					if(!empty($email->getName())){
						$user = \XF::finder('XF:User')->where('username', $email->getName())->fetchOne();
						// Account for add-ons that delete the user but send an email with an associated username
						if(!empty($user))
							$log->user_id = $user->user_id;
					}
					$log->email = $email->getAddress();
					$log->subject = $subject;
					$log->save();
				}
			}else{
				foreach($emails as $emailAddress => $username){
					$log = \XF::em()->create('Jack\emailLog:EmailLog');
					if(!empty($username)){
						$user = \XF::finder('XF:User')->where('username', $username)->fetchOne();
						// Account for add-ons that delete the user but send an email with an associated username
						if(!empty($user))
							$log->user_id = $user->user_id;
					}
					$log->email = $emailAddress;
					$log->subject = $mail->message->getSubject();
					$log->save();
				}
			}
			
		}

		return $mail;
	}

}