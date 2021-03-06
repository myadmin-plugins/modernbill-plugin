<?php

namespace Detain\MyAdminModernBill;

use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Class Plugin
 *
 * @package Detain\MyAdminModernBill
 */
class Plugin
{
	public static $name = 'ModernBill Plugin';
	public static $description = 'Allows handling of ModernBill based Payments through their Payment Processor/Payment System.';
	public static $help = '';
	public static $type = 'plugin';

	/**
	 * Plugin constructor.
	 */
	public function __construct()
	{
	}

	/**
	 * @return array
	 */
	public static function getHooks()
	{
		return [
			//'system.settings' => [__CLASS__, 'getSettings'],
			//'ui.menu' => [__CLASS__, 'getMenu'],
			'function.requirements' => [__CLASS__, 'getRequirements']
		];
	}

	/**
	 * @param \Symfony\Component\EventDispatcher\GenericEvent $event
	 */
	public static function getMenu(GenericEvent $event)
	{
		$menu = $event->getSubject();
		if ($GLOBALS['tf']->ima == 'admin') {
			if (has_module_db('innertell')) {
				function_requirements('has_acl');
				if (has_acl('client_billing')) {
					$menu->add_menu('billing', 'virtual', 'Legacy Billing (ModernBill)', '/lib/webhostinghub-glyphs-icons/icons/business-32/Black/icon-abacus.png');
					$menu->add_link('virtual', 'choice=none.modernbill_client', '/lib/webhostinghub-glyphs-icons/icons/communication-32/Black/icon-businesscardalt.png', _('View Client Info'));
					$menu->add_link('virtual', 'choice=none.modernbill_packages', '/lib/webhostinghub-glyphs-icons/icons/business-32/Black/icon-tagalt-pricealt.png', _('View Packages'));
					$menu->add_link('virtual', 'choice=none.modernbill_invoices', '/lib/webhostinghub-glyphs-icons/icons/business-32/Black/icon-invoice.png', _('View Invoices'));
				}
			}
		}
	}

	/**
	 * @param \Symfony\Component\EventDispatcher\GenericEvent $event
	 */
	public static function getRequirements(GenericEvent $event)
	{
		/**
		 * @var \MyAdmin\Plugins\Loader $this->loader
		 */
		$loader = $event->getSubject();
		$loader->add_page_requirement('modernbill_client', '/../vendor/detain/myadmin-modernbill-plugin/src/modernbill_client.php');
		$loader->add_page_requirement('modernbill_invoice', '/../vendor/detain/myadmin-modernbill-plugin/src/modernbill_invoice.php');
		$loader->add_page_requirement('modernbill_invoices', '/../vendor/detain/myadmin-modernbill-plugin/src/modernbill_invoices.php');
		$loader->add_page_requirement('modernbill_packages', '/../vendor/detain/myadmin-modernbill-plugin/src/modernbill_packages.php');
		$loader->add_requirement('get_modernbill_client_by_id', '/../vendor/detain/myadmin-modernbill-plugin/src/modernbill.functions.inc.php');
		$loader->add_requirement('get_modernbill_client_by_email', '/../vendor/detain/myadmin-modernbill-plugin/src/modernbill.functions.inc.php');
		$loader->add_requirement('get_modernbill_clients', '/../vendor/detain/myadmin-modernbill-plugin/src/modernbill.functions.inc.php');
		$loader->add_requirement('get_modernbill_invoices', '/../vendor/detain/myadmin-modernbill-plugin/src/modernbill.functions.inc.php');
		$loader->add_requirement('get_modernbill_packages', '/../vendor/detain/myadmin-modernbill-plugin/src/modernbill.functions.inc.php');
	}

	/**
	 * @param \Symfony\Component\EventDispatcher\GenericEvent $event
	 */
	public static function getSettings(GenericEvent $event)
	{
		/**
		 * @var \MyAdmin\Settings $settings
		 **/
		$settings = $event->getSubject();
		$settings->add_radio_setting(_('Billing'), _('ModernBill'), 'paypal_enable', _('Enable ModernBill'), _('Enable ModernBill'), PAYPAL_ENABLE, [true, false], ['Enabled', 'Disabled']);
		$settings->add_radio_setting(_('Billing'), _('ModernBill'), 'paypal_digitalgoods_enable', _('Enable Digital Goods'), _('Enable Digital Goods'), PAYPAL_DIGITALGOODS_ENABLE, [true, false], ['Enabled', 'Disabled']);
		$settings->add_text_setting(_('Billing'), _('ModernBill'), 'paypal_email', _('Login').' / '._('Email'), _('Login').' / '._('Email'), (defined('PAYPAL_EMAIL') ? PAYPAL_EMAIL : ''));
		$settings->add_text_setting(_('Billing'), _('ModernBill'), 'paypal_api_username', _('API Username'), _('API Username'), (defined('PAYPAL_API_USERNAME') ? PAYPAL_API_USERNAME : ''));
		$settings->add_text_setting(_('Billing'), _('ModernBill'), 'paypal_api_password', _('API Password'), _('API Password'), (defined('PAYPAL_API_PASSWORD') ? PAYPAL_API_PASSWORD : ''));
		$settings->add_text_setting(_('Billing'), _('ModernBill'), 'paypal_api_signature', _('API Signature'), _('API Signature'), (defined('PAYPAL_API_SIGNATURE') ? PAYPAL_API_SIGNATURE : ''));
		$settings->add_text_setting(_('Billing'), _('ModernBill'), 'paypal_sandbox_api_username', _('Sandbox API Username'), _('Sandbox API Username'), (defined('PAYPAL_SANDBOX_API_USERNAME') ? PAYPAL_SANDBOX_API_USERNAME : ''));
		$settings->add_text_setting(_('Billing'), _('ModernBill'), 'paypal_sandbox_api_password', _('Sandbox API Password'), _('Sandbox API Password'), (defined('PAYPAL_SANDBOX_API_PASSWORD') ? PAYPAL_SANDBOX_API_PASSWORD : ''));
		$settings->add_text_setting(_('Billing'), _('ModernBill'), 'paypal_sandbox_api_signature', _('Sandbox API Signature'), _('Sandbox API Signature'), (defined('PAYPAL_SANDBOX_API_SIGNATURE') ? PAYPAL_SANDBOX_API_SIGNATURE : ''));
	}
}
