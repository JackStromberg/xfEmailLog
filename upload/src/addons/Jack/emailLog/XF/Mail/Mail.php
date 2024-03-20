<?php

namespace Jack\emailLog\XF\Mail;

use function count;

class Mail extends XFCP_Mail
{

    public function queue()
	{
		if ($this->setupError)
		{
			$this->logSetupError($this->setupError);
			return false;
		}

        $message = $this->getSendableMessage();
		if (!$message->getTo())
		{
			return false;
		}

		$emails = $message->getTo();
		if (count($emails) > 0)
		{
			foreach($emails as $emailAddress => $username){
				$log = \XF::em()->create('Jack\emailLog:EmailLog');
				$user = \XF::finder('XF:User')->where('username', $username)->fetchOne();
				$log->user_id = $user->user_id;
				$log->email = $emailAddress;
				$log->subject = $message->getSubject();
				$log->save();
			}
			
		}

		return $this->mailer->queue($message);
    }

}