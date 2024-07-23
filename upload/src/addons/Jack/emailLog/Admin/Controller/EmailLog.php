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

		// Filter by text
		$filter = $this->filter('_xfFilter', [
			'text' => 'str',
			'prefix' => 'bool'
		]);

		$filterString = '';
		if(!empty($filter['text']))
			if($filter['prefix'])
				$filterString = "{$filter['text']}%";
			else
				$filterString = "%{$filter['text']}%";


		// Pagination
		$input = $this->filter([
			'page' => 'uint',
			'user_id' => 'uint'
		]);

		$limitPerPage = 25;
		if(empty($input['page']))
			$page=1;
		else
			$page=$input['page'];

		$finder = $this->finder('Jack\emailLog:EmailLog');
		$total = $finder->total();
		
		if(empty($filterString)){
        	$logs = $finder->setDefaultOrder('log_id', 'desc')
						->limitByPage($page, $limitPerPage)
						->fetch();
		}else{
			$logs = $finder->setDefaultOrder('log_id', 'desc')
						->where('email', 'LIKE', $filterString)
						->fetch();
		}
		
		$viewParams = [
			'total' => $total,
			'page' => $page,
			'limitPerPage' => $limitPerPage,
			'logs' => $logs
		];

		return $this->view('Jack\emailLog:emailLog\index', 'listEmailLog', $viewParams);
	}

}