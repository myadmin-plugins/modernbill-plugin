<?php
/**
 * modernbill_invoices()
 *
 * @return void
 * @throws \Exception
 * @throws \SmartyException
 */
	function modernbill_invoices()
	{
		add_js('tablesorter');
		page_title('ModernBill Client Invoice Information');
		add_output(render_form('modernbill_invoice_list'));
		return;
		$db = get_module_db('mb');
		$data = $GLOBALS['tf']->accounts->data;
		$table = new TFTable;
		$invoices = get_modernbill_invoices();
		$values = [
			'client_email' => 'Client',
			'invoice_id' => 'Invoice ID',
			'invoice_amount' => 'Amount',
			'invoice_amount_paid' => 'Amount Paid',
			'invoice_date_entered' => 'Date Entered',
			'invoice_date_due' => 'Date Due',
			'invoice_date_paid' => 'Date Paid',
			'invoice_payment_method' => 'Payment Method',
			'invoice_subtotal' => 'Subtotal'
		];
		if (count($invoices) > 0) {
			$smarty = new TFSmarty;
			$smarty->debugging = true;
			$rows = [];
			$smarty->assign('sortcol', 3);
			$smarty->assign('sortdir', 0);
			$smarty->assign('textextraction', "'complex'");
			$title = false;
			foreach ($invoices as $invoice) {
				$invoice['invoice_date_entered'] = date('Y-m-d', $invoice['invoice_date_entered']);
				$invoice['invoice_date_due'] = date('Y-m-d', $invoice['invoice_date_due']);
				$invoice['invoice_date_paid'] = date('Y-m-d', $invoice['invoice_date_paid']);
				function_requirements('has_acl');
				if ($GLOBALS['tf']->ima != 'admin' || !has_acl('client_billing')) {
					unset($invoice['client_email']);
				} else {
					$invoice['client_email'] = $table->make_link('choice=none.modernbill_client&amp;id='.$invoice['client_id'], $invoice['client_email']);
				}
				unset($invoice['client_id']);
				if (!$title) {
					$title = [];
					foreach (array_keys($invoice) as $key) {
						$title[] = ucwords(str_replace('_', ' ', $key));
					}
					$smarty->assign('table_header', $title);
					$title = true;
				}
				$rows[] = $invoice;
			}
			$smarty->assign('table_rows', $rows);
			add_output($smarty->fetch('tablesorter/tablesorter.tpl'));
		} else {
			add_output('No Invoices Found');
		}
	}
