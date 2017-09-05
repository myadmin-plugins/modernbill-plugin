<?php
/**
 * modernbill_packages()
 *
 * @return void
 * @throws \Exception
 * @throws \SmartyException
 */
	function modernbill_packages() {
		add_js('tablesorter');
		page_title('ModernBill Client Package Information');
		function_requirements('has_acl');
		$values = [
			'client_email' => 'Client',
			'pack_name' => 'Package',
			'pack_price' => 'Price',
			'cp_comments' => 'Comments',
			'domain' => 'Domain'
		];
		$data = $GLOBALS['tf']->accounts->data;
		$table = new TFTable;
		function_requirements('get_modernbill_packages');
		$packages = get_modernbill_packages();
		if (count($packages) > 0) {
			$smarty = new TFSmarty;
			$smarty->debugging = true;
			$rows = [];
			$smarty->assign('sortcol', 1);
			$smarty->assign('sortdir', 0);
			$smarty->assign('textextraction', "'complex'");
			$title = false;
			foreach ($packages as $package) {
				if ($GLOBALS['tf']->ima != 'admin' || !has_acl('client_billing')) {
					unset($package['client_email']);
				} else {
					$package['client_email'] = $table->make_link('choice=none.modernbill_client&amp;client_email='.$package['client_email'], $package['client_email']);
				}
				unset($package['client_id']);
				if (!$title) {
					$title = [];
					foreach (array_keys($package) as $key)
						$title[] = $values[$key];
					$smarty->assign('table_header', $title);
					$title = true;
				}
				$rows[] = $package;
			}
			$smarty->assign('table_rows', $rows);
			add_output($smarty->fetch('tablesorter/tablesorter.tpl'));

		} else {
			add_output('No Packages Found');
		}
	}
