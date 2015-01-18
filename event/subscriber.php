<?php

namespace bpat1434\nocaptcharecaptcha\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use \bpat1434\nocaptcharecaptcha\captcha\nocaptcharecaptcha as NoCAPTCHAReCAPTCHA;

/**
 * Event listener
 */
class subscriber implements EventSubscriberInterface
{
	/* @var $config \phpbb\config\config */
	protected $config;
	/* @var $template \phpbb\template\template */
	protected $template;
	/* @var $user \phpbb\user */
	protected $user;

	public function __construct(\phpbb\config\config $config, \phpbb\template\template $template, \phpbb\user $user)
	{
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup' => 'load_language_on_setup',
			'core.adm_page_header' => 'insert_nocaptcharecaptcha_available_var',
			'core.page_header_after' => 'insert_nocaptcharecaptcha_available_var'
		);
	}

	public function load_language_on_setup($event)
	{
		$this->user->add_lang_ext('bpat1434/nocaptcharecaptcha', 'nocaptcharecaptcha');
	}

	public function insert_nocaptcharecaptcha_available_var($event)
	{
		$this->template->assign_var('S_NOCAPTCHARECAPTCHA_AVAILABLE', NoCAPTCHAReCAPTCHA::is_available());
	}
}