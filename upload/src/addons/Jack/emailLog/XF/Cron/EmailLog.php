<?php

namespace Jack\emailLog\XF\Cron;

class EmailLog
{
	public static function runPruneEmailLog()
	{	
		$options = \XF::options();		
		$timezone = $options->guestTimeZone;
		$days = $options->pruneLogAfterXDays;

		if($timezone == ''){
			\XF::app()->error()->logError("Timezone information not configured in board defaults.");
			return;
		}
		
		// set timezone
		if ($timezone != '')
		{
			date_default_timezone_set($timezone);
		}
		
		// Delete logs older than x days ago
		$timestamp = time() - (86400 * $days);
		
		\XF::db()->delete('xf_jack_email_log', 'timestamp < ?', [$timestamp]);
	}
}