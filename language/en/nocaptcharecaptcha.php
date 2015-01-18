<?php

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
	'CAPTCHA_NOCAPTCHARECAPTCHA'   => 'NoCAPTCHA ReCAPTCHA',
	'NOCAPTCHARECAPTCHA_SITEKEY'   => 'Site Key',
	'NOCAPTCHARECAPTCHA_SECRETKEY' => 'Secret Key',
	'NOCAPTCHARECAPTCHA_SITEKEY_EXPLAIN' => 'Site key as provided to you by <a href="http://www.google.com/recaptcha" target="_blank">Google</a>',
	'NOCAPTCHARECAPTCHA_SECRETKEY_EXPLAIN' => 'Secret key associated with the site key provided by <a href="http://www.google.com/recaptcha" target="_blank">Google</a>',
	'NOCAPTCHARECAPTCHA_NOT_AVAILABLE' => 'NoCAPTCHA ReCAPTCHA is not currently availalbe.',
	'NOCAPTCHARECAPTCHA_ERROR' => 'Visual confirmation failed.',
	'NOCAPTCHARECAPTCHA_SOCKET_ERROR' => 'An error occurred while contacting the Google servers.',
	'NOCAPTCHARECAPTCHA_RESPONSE_ERROR' => 'Please check the box to verify you are not a robot.'
));