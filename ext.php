<?php
/**
*
* @package phpBB Extension - Acme Demo
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace bpat1434\nocaptcharecaptcha;

class ext extends \phpbb\extension\base
{
	public function is_enableable()
	{
		return extension_loaded('curl');
	}

	function disable_step($old_state)
	{
		switch ($old_state)
		{
			case '':
				$config = $this->container->get('config');
				
				// Check if NoCAPTCHA ReCAPTCHA is currently the default captcha
				if ($config['captcha_plugin'] === $this->container->get('bpat1434.nocaptcharecaptcha.captcha.nocaptcharecaptcha')->get_service_name())
				{
					// It's the default captcha, set the default captcha to phpBB's default GD captcha.
					$config->set('captcha_plugin', 'core.captcha.plugins.gd');
				}
				$retval = 'default_captcha_changed';
			break;

			default:
				$retval = parent::disable_step($old_state);
			break;
		}

		return $retval;
	}
}