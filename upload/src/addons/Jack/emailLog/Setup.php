<?php

namespace Jack\emailLog;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Alter;
use XF\Db\Schema\Create;

class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

    // ################################ INSTALLATION ####################

	public function installStep1()
	{
		$sm = $this->schemaManager();

		foreach ($this->getTables() AS $tableName => $closure)
		{
			$sm->createTable($tableName, $closure);
		}
	}

    public function installStep2()
    {

	}

    public function installStep3()
    {

    }

    public function uninstallStep1()
    {
        $sm = $this->schemaManager();

		foreach (array_keys($this->getTables()) AS $tableName)
		{
			$sm->dropTable($tableName);
		}
    }

    public function uninstallStep2()
	{

    }

    public function uninstallStep3()
	{

    }

    public function postInstall(array &$stateChanges)
	{

	}
	

    // Helpers
    protected function getTables()
	{
		$tables = [];

		$tables['xf_jack_email_log'] = function(Create $table)
		{
			$table->addColumn('log_id', 'int', 10)->autoIncrement();
			$table->addColumn('user_id', 'int', 10)->setDefault(0);
			$table->addColumn('email', 'VARCHAR', 120);
			$table->addColumn('subject', 'text');
			$table->addColumn('timestamp', 'int', 10)->setDefault(0);
			$table->addPrimaryKey('log_id');
			$table->addKey('user_id');
		};

		return $tables;
	}

}