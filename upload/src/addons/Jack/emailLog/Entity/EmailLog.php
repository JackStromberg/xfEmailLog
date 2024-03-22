<?php

namespace Jack\emailLog\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class EmailLog extends Entity
{

	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_jack_email_log';
		$structure->shortName = 'Jack:emailLog\emailLog';
		$structure->contentType = 'emailLog';
		$structure->primaryKey = 'log_id';
		$structure->columns = [
			'log_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'user_id' => ['type' => self::UINT, 'default' => 0, 'nullable' => false],
			'email' => ['type' => self::STR, 'default' => '', 'nullable' => false],
			'subject' => ['type' => self::STR, 'default' => '', 'required' => true],
			'timestamp' => ['type' => self::UINT, 'default' => \XF::$time],
		];
		$structure->getters = [
			'prefixes' => true,
			'draft_resource' => true
		];
		$structure->relations = [
			'User' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => ['user_id'],
				'primary' => true,
			]
		];
		$structure->defaultWith = [
			'User'
		];

		return $structure;
	}
}