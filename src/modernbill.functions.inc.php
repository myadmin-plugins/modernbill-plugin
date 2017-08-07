<?php
	/**
	 * ModernBill Related Functionality
	 * Last Changed: $LastChangedDate: 2017-07-30 20:24:37 -0400 (Sun, 30 Jul 2017) $
	 * @author detain
	 * @copyright 2017
	 * @package MyAdmin
	 * @category ModernBill
	 */

	/**
	 * get_modernbill_client_by_id()
	 * gets a modernbill client by ID
	 *
	 * @param integer $id the ID of the modernbill client to get
	 * @return false|array returns false if no client found or the array of client info
	 */
	function get_modernbill_client_by_id($id) {
		$id = (int)$id;
		$db = get_module_db('mb');
		$db->query("select * from client_info where client_id=$id", __LINE__, __FILE__);
		if ($db->num_rows() > 0) {
			$db->next_record(MYSQL_ASSOC);
			return $db->Record;
		}
		return false;
	}

	/**
	 * get_modernbill_client_by_email()
	 * gets a modernbill client by email
	 *
	 * @param string $email the email of the modernbill client to get
	 * @return false|array returns false if no client found or the array of client info
	 */
	function get_modernbill_client_by_email($email) {
		$db = get_module_db('mb');
		$db->query("select * from client_info where client_email='" . $db->real_escape($email) . "'", __LINE__, __FILE__);
		if ($db->num_rows() > 0) {
			$db->next_record(MYSQL_ASSOC);
			return $db->Record;
		}
		return false;
	}

	/**
	 * get_modernbill_clients()
	 * loads all the modernbill clients, returns all fields or you can specify which ones you want
	 * @param bool $fields
	 * @internal param array $array of fields you want returned, or leave blank for all
	 * @return array an array of clients
	 */
	function get_modernbill_clients($fields = false) {
		if ($fields === false) {
			$fields = [];
		}
		if (count($fields) == 0) {
			$fields[] = '*';
		}
		$db = clone $GLOBALS['mb_dbh'];
		$db->query('select '.implode(', ', $fields).' from client_info', __LINE__, __FILE__);
		$clients = [];
		if ($db->num_rows() > 0) {
			while ($db->next_record(MYSQL_ASSOC)) {
				$clients[] = $db->Record;
			}
		}
		return $clients;
	}

	/**
	 * get_modernbill_invoices()
	 * loads all the invoices, if you are an admin it loads all invoices, otherwise just the invoices for your customer id
	 *
	 * @return array an array of invoices
	 */
	function get_modernbill_invoices() {
		$db = get_module_db('mb');
		$data = $GLOBALS['tf']->accounts->data;
		function_requirements('has_acl');
		if ($GLOBALS['tf']->ima == 'admin' && has_acl('client_billing')) {
			$query = 'SELECT client_info.client_email
	 , client_invoice.client_id
	 , client_invoice.invoice_id
	 , client_invoice.invoice_amount
	 , client_invoice.invoice_amount_paid
	 , client_invoice.invoice_date_entered
	 , client_invoice.invoice_date_due
	 , client_invoice.invoice_date_paid
	 , client_invoice.invoice_payment_method
	 , client_invoice.invoice_subtotal
FROM
  client_invoice
LEFT OUTER JOIN client_info
ON client_invoice.client_id = client_info.client_id';
		} else {
			$query = "SELECT client_info.client_email
	 , client_invoice.client_id
	 , client_invoice.invoice_id
	 , client_invoice.invoice_amount
	 , client_invoice.invoice_amount_paid
	 , client_invoice.invoice_date_entered
	 , client_invoice.invoice_date_due
	 , client_invoice.invoice_date_paid
	 , client_invoice.invoice_payment_method
	 , client_invoice.invoice_subtotal
FROM
  client_invoice
LEFT OUTER JOIN client_info
ON client_invoice.client_id = client_info.client_id
WHERE
client_info.client_email='" . $db->real_escape($data['account_lid']) . "'";
		}
		//$query .= ' limit 100';
		$db->query($query, __LINE__, __FILE__);
		$results = [];
		while ($db->next_record(MYSQL_ASSOC)) {
			$results[] = $db->Record;
		}
		return $results;
	}

	/**
	 * get_modernbill_packages()
	 * gets all teh modernbill packages
	 *
	 * @return array an array of modernbill packages
	 */
	function get_modernbill_packages() {
		$db = get_module_db('mb');
		$data = $GLOBALS['tf']->accounts->data;
		function_requirements('has_acl');
		if ($GLOBALS['tf']->ima == 'admin' && has_acl('client_billing')) {
			$query = 'SELECT client_email
	 , client_package.client_id
	 , pack_name
	 , client_package.pack_price
	 , cp_comments
	 , domain
FROM
  client_info
LEFT JOIN client_package
ON client_package.client_id = client_info.client_id
LEFT JOIN package_type
ON client_package.pack_id = package_type.pack_id
WHERE
  cp_status = 2';
		} else {
			$query = "SELECT client_email
	 , client_package.client_id
	 , pack_name
	 , client_package.pack_price
	 , cp_comments
	 , domain
FROM
  client_info
LEFT JOIN client_package
ON client_package.client_id = client_info.client_id
LEFT JOIN package_type
ON client_package.pack_id = package_type.pack_id
WHERE
  cp_status = 2
  and client_info.client_email='" . $db->real_escape($data['account_lid']) . "'";
		}
		$db->query($query, __LINE__, __FILE__);
		$results = [];
		while ($db->next_record(MYSQL_ASSOC)) {
			$results[] = $db->Record;
		}
		return $results;
	}
