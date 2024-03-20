<?php

namespace Jack\emailLog\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;

class EmailLog extends AbstractController
{
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('emailLog');
	}

	public function actionIndex(){
		$this->setSectionContext('emailLog');

		$logs = $this->finder('Jack\emailLog:EmailLog')->setDefaultOrder('log_id', 'desc');

		$viewParams = [
			'logs' => $logs
		];

		return $this->view('Jack\emailLog:emailLog\index', 'listEmailLog', $viewParams);
	}

}