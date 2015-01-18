<?php
/**
*
* @package VC
* @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
*
*/

namespace bpat1434\nocaptcharecaptcha\captcha;

/**
* Implementation of the "NoCAPTCHA" ReCAPTCHA from Google.
*
* @package VC
*/
class nocaptcharecaptcha extends \phpbb\captcha\plugins\captcha_abstract
{
	protected $nocaptcha_verify_host = 'www.google.com';
	protected $nocaptcha_verify_path = '/recaptcha/api/siteverify';

	public function __construct()
	{
	}

	public function init($type)
	{
		global $config, $db, $user;

		parent::init($type);
	}

	public static function is_available()
	{
		global $config, $user;
		$user->add_lang_ext('bpat1434/nocaptcharecaptcha', 'nocaptcharecaptcha');

		return (isset($config['nocaptcharecaptcha_sitekey']) && !empty($config['nocaptcharecaptcha_sitekey'])) &&
			(isset($config['nocaptcharecaptcha_secretkey']) && !empty($config['nocaptcharecaptcha_secretkey']));
	}

	/**
	*  API function
	*/
	public function has_config()
	{
		return true;
	}

	static public function get_name()
	{
		return 'CAPTCHA_NOCAPTCHARECAPTCHA';
	}

	/**
	* This function is implemented because required by the upper class, but is never used for reCaptcha.
	*/
	public function get_generator_class()
	{
		throw new \Exception('No generator class given.');
	}

	public function acp_page($id, &$module)
	{
		global $config, $db, $template, $user;

		$captcha_vars = array(
			'nocaptcharecaptcha_sitekey'	=> 'NOCAPTCHARECAPTCHA_SITEKEY',
			'nocaptcharecaptcha_secretkey'	=> 'NOCAPTCHARECAPTCHA_SECRETKEY',
		);

		$module->tpl_name = '@bpat1434_nocaptcharecaptcha/nocaptcharecaptcha_acp';
		$module->page_title = 'ACP_VC_SETTINGS';
		$form_key = 'acp_captcha';
		add_form_key($form_key);

		$submit = request_var('submit', '');

		if ($submit && check_form_key($form_key))
		{
			$captcha_vars = array_keys($captcha_vars);
			foreach ($captcha_vars as $captcha_var)
			{
				$value = request_var($captcha_var, '');
				set_config($captcha_var, $value);
			}

			add_log('admin', 'LOG_CONFIG_VISUAL');
			trigger_error($user->lang['CONFIG_UPDATED'] . adm_back_link($module->u_action));
		}
		else if ($submit)
		{
			trigger_error($user->lang['FORM_INVALID'] . adm_back_link($module->u_action));
		}
		else
		{
			foreach ($captcha_vars as $captcha_var => $template_var)
			{
				$var = (isset($_REQUEST[$captcha_var])) ? request_var($captcha_var, '') : ((isset($config[$captcha_var])) ? $config[$captcha_var] : '');
				$template->assign_var($template_var, $var);
			}

			$template->assign_vars(array(
				'CAPTCHA_PREVIEW'	=> $this->get_demo_template($id),
				'CAPTCHA_NAME'		=> $this->get_service_name(),
				'U_ACTION'			=> $module->u_action,
			));

		}
	}

	// not needed
	public function execute_demo()
	{
	}

	// not needed
	public function execute()
	{
	}

	public function get_template()
	{
		global $config, $user, $template, $phpbb_root_path, $phpEx;

		$contact_link = phpbb_get_board_contact_link($config, $phpbb_root_path, $phpEx);
		$explain = $user->lang(($this->type != CONFIRM_POST) ? 'CONFIRM_EXPLAIN' : 'POST_CONFIRM_EXPLAIN', '<a href="' . $contact_link . '">', '</a>');

		$template->assign_vars(array(
			'NOCAPTCHARECAPTCHA_SITEKEY'     => (isset($config['nocaptcharecaptcha_sitekey']) ? $config['nocaptcharecaptcha_sitekey'] : ''),
			'S_NOCAPTCHARECAPTCHA_AVAILABLE' => self::is_available(),
			'S_TYPE'	                     => $this->type,
			'L_CONFIRM_EXPLAIN'	             => $explain,
		));

		return '@bpat1434_nocaptcharecaptcha/nocaptcharecaptcha.html';
	}

	public function get_demo_template($id)
	{
		return $this->get_template();
	}

	public function get_hidden_fields()
	{
		$hidden_fields = array();
		return $hidden_fields;
	}

	public function uninstall()
	{
		$this->garbage_collect(0);
	}

	public function install()
	{
		return;
	}

	public function validate()
	{
		if (!parent::validate())
		{
			return false;
		}
		else
		{
			return $this->nocaptcha_verify_response();
		}
	}

	protected function nocaptcha_verify_response()
	{
		global $config, $user;

		$requestUrl = 'https://' . $this->nocaptcha_verify_host . $this->nocaptcha_verify_path . '?';
		$requestUrl .= http_build_query(array(
			'secret' => $config['nocaptcharecaptcha_secretkey'],
			'response' => request_var('g-recaptcha-response', ''),
			'remoteip' => $user->data['session_ip']
		), '', '&');
		
		$ch = curl_init($requestUrl);
		curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 10,
			CURLOPT_USERAGENT => 'PHP/phpBB/NoCAPTCHAReCAPTCHA-Extension',
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => array(
				'secret' => $config['nocaptcharecaptcha_secretkey'],
				'response' => request_var('g-recaptcha-response', ''),
				'remoteip' => $user->data['session_ip']
			)
		));

		$response = curl_exec($ch);

		if ($response !== false)
		{
			$response = json_decode($response, true);
			if (isset($response['success']) && $response['success'])
			{
				$retval = false;
			}
			else
			{
				if (isset($response['error-codes']) && $response['error-codes'])
				{
					$errors = '';

					foreach ($response['error-codes'] as $errorCode)
					{
						switch($errorCode)
						{
							case 'missing-input-secret':
								$errors .= ' A configuration value is missing.';
								break;

							case 'invalid-input-secret':
								$errors .= ' A configuration value is invalid or malformed.';
								break;

							case 'missing-input-response':
								$errors .= ' The response parameter is missing.';
								break;

							case 'invalid-input-response':
								$errors .= ' The response parameter is invalid or malformed.';
								break;
						}
					}

					error_log('NoCAPTCHA ReCAPTCHA Error Encountered: ' . $errors, 0);

					$retval = $user->lang['NOCAPTCHARECAPTCHA_ERROR'];
				}
				else
				{
					$retval = $user->lang['NOCAPTCHARECAPTCHA_RESPONSE_ERROR'];
				}
			}
		}
		else
		{
			$retval = $user->lang['NOCAPTCHARECAPTCHA_RESPONSE_ERROR'];
		}

		return $retval;
	}
}

?>