<?php

namespace Detain\MyAdminModernBill;

use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Class Plugin
 *
 * @package Detain\MyAdminModernBill
 */
class Plugin {

	public static $name = 'ModernBill Plugin';
	public static $description = 'Allows handling of ModernBill based Payments through their Payment Processor/Payment System.';
	public static $help = '';
	public static $type = 'plugin';

	/**
	 * Plugin constructor.
	 */
	public function __construct() {
	}

	/**
	 * @return array
	 */
	public static function getHooks() {
		return [
			//'system.settings' => [__CLASS__, 'getSettings'],
			//'ui.menu' => [__CLASS__, 'getMenu'],
			'function.requirements' => [__CLASS__, 'getRequirements']
		];
	}

	/**
	 * @param \Symfony\Component\EventDispatcher\GenericEvent $event
	 */
	public static function getMenu(GenericEvent $event) {
		$menu = $event->getSubject();
		if ($GLOBALS['tf']->ima == 'admin') {
			function_requirements('has_acl');
					if (has_acl('client_billing'))
							$menu->add_link('admin', 'choice=none.abuse_admin', '//my.interserver.net/bower_components/webhostinghub-glyphs-icons/icons/development-16/Black/icon-spam.png', 'ModernBill');
		}
	}

	/**
	 * @param \Symfony\Component\EventDispatcher\GenericEvent $event
	 */
	public static function getRequirements(GenericEvent $event) {
		$loader = $event->getSubject();
		$loader->add_requirement('modernbill_client', '/../vendor/detain/myadmin-modernbill-plugin/src/modernbill_client.php');
		$loader->add_requirement('modernbill_invoice', '/../vendor/detain/myadmin-modernbill-plugin/src/modernbill_invoice.php');
		$loader->add_requirement('modernbill_invoices', '/../vendor/detain/myadmin-modernbill-plugin/src/modernbill_invoices.php');
		$loader->add_requirement('modernbill_packages', '/../vendor/detain/myadmin-modernbill-plugin/src/modernbill_packages.php');
		$loader->add_requirement('get_modernbill_client_by_id', '/../vendor/detain/myadmin-modernbill-plugin/src/modernbill.functions.inc.php');
		$loader->add_requirement('get_modernbill_client_by_email', '/../vendor/detain/myadmin-modernbill-plugin/src/modernbill.functions.inc.php');
		$loader->add_requirement('get_modernbill_clients', '/../vendor/detain/myadmin-modernbill-plugin/src/modernbill.functions.inc.php');
		$loader->add_requirement('get_modernbill_invoices', '/../vendor/detain/myadmin-modernbill-plugin/src/modernbill.functions.inc.php');
		$loader->add_requirement('get_modernbill_packages', '/../vendor/detain/myadmin-modernbill-plugin/src/modernbill.functions.inc.php');
	}

	/**
	 * @param \Symfony\Component\EventDispatcher\GenericEvent $event
	 */
	public static function getSettings(GenericEvent $event) {
		$settings = $event->getSubject();
		$settings->add_radio_setting('Billing', 'ModernBill', 'paypal_enable', 'Enable ModernBill', 'Enable ModernBill', PAYPAL_ENABLE, [true, false], ['Enabled', 'Disabled']);
		$settings->add_radio_setting('Billing', 'ModernBill', 'paypal_digitalgoods_enable', 'Enable Digital Goods', 'Enable Digital Goods', PAYPAL_DIGITALGOODS_ENABLE, [true, false], ['Enabled', 'Disabled']);
		$settings->add_text_setting('Billing', 'ModernBill', 'paypal_email', 'Login / Email ', 'Login / Email ', (defined('PAYPAL_EMAIL') ? PAYPAL_EMAIL : ''));
		$settings->add_text_setting('Billing', 'ModernBill', 'paypal_api_username', 'API Username', 'API Username', (defined('PAYPAL_API_USERNAME') ? PAYPAL_API_USERNAME : ''));
		$settings->add_text_setting('Billing', 'ModernBill', 'paypal_api_password', 'API Password', 'API Password', (defined('PAYPAL_API_PASSWORD') ? PAYPAL_API_PASSWORD : ''));
		$settings->add_text_setting('Billing', 'ModernBill', 'paypal_api_signature', 'API Signature', 'API Signature', (defined('PAYPAL_API_SIGNATURE') ? PAYPAL_API_SIGNATURE : ''));
		$settings->add_text_setting('Billing', 'ModernBill', 'paypal_sandbox_api_username', 'Sandbox API Username', 'Sandbox API Username', (defined('PAYPAL_SANDBOX_API_USERNAME') ? PAYPAL_SANDBOX_API_USERNAME : ''));
		$settings->add_text_setting('Billing', 'ModernBill', 'paypal_sandbox_api_password', 'Sandbox API Password', 'Sandbox API Password', (defined('PAYPAL_SANDBOX_API_PASSWORD') ? PAYPAL_SANDBOX_API_PASSWORD : ''));
		$settings->add_text_setting('Billing', 'ModernBill', 'paypal_sandbox_api_signature', 'Sandbox API Signature', 'Sandbox API Signature', (defined('PAYPAL_SANDBOX_API_SIGNATURE') ? PAYPAL_SANDBOX_API_SIGNATURE : ''));
	}

}
