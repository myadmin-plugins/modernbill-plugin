<?php

    function modernbill_invoice()
    {
        $db = get_module_db('mb');
        $invoice = (int)$GLOBALS['tf']->variables->request['id'];
        $data = $GLOBALS['tf']->accounts->data;
        function_requirements('has_acl');
        if ($GLOBALS['tf']->ima == 'admin' && has_acl('client_billing')) {
            $db->query("select * from client_invoice left join client_info on client_invoice.client_id=client_info.client_id where invoice_id=$invoice");
        } else {
            $db->query("select * from client_invoice left join client_info on client_invoice.client_id=client_info.client_id where invoice_id=$invoice and  client_info.client_email='" . $db->real_escape($data['account_lid']) . "'");
        }
        if ($db->num_rows() == 0) {
            add_output('No matching invoice found or you do not have permission to view this invoice.');
            return;
        }
        $db->next_record(MYSQL_ASSOC);
        $client_id = $db->Record['client_id'];
        $firstname = $db->Record['client_fname'];
        $lastname = $db->Record['client_lname'];
        $address = $db->Record['client_address'];
        $city = $db->Record['client_city'];
        $state = $db->Record['client_state'];
        $zip = $db->Record['client_zip'];
        $country = $db->Record['client_country'];
        $phone = $db->Record['client_phone1'];
        $amount = $db->Record['invoice_amount'] - $db->Record['invoice_amount_paid'];
        //echo nl2br(print_r($db->Record, TRUE));
        $pp_url = 'https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=orders@trouble-free.net&currency_code=USD&item_name=Interserver%2C+Inc&item_number='.$invoice .
            '&custom=&image_url=http%3A//cms.interserver.net/modernbill/images/logo_217_68.gif&return=http%3A//interserver.net&notify_url=https%3A//my.interserver.net/payments/paypal_ipn.php &no_note=1&no_shipping=1&rm=1&first_name=' .
            $firstname.'&last_name='.$lastname.'&address1='.$address.'&city='.$city.'&state='.$state.'&zip='.$zip.'&amount='.$amount;
        $table = new TFTable();
        $table->set_options('style="min-width: 800px;"');
        $table->set_title('Invoice');
        $table->set_col_options('style="padding-left: 20px;"');
        $table->add_field('Interserver, Inc<br>
110 Meadowlands Pkwy<br>
Suite 100<br>
Secaucus, NJ 07094<br>
Phone: 877-566-8398', 'l');
        $table->set_col_options('style="padding-left: 0px;"');
        $table->add_field('<a href="'.$pp_url.'" target=_blank><img src="https://www.paypal.com/images/lgo/pp3.gif" border=0></a>');
        $table->add_row();
        $table->set_colspan(2);
        $table->set_col_options('style="padding-left: 20px;padding-right: 20px;"');
        $table->add_field('&nbsp;');
        $table->add_row();
        $table->set_col_options('style="padding-left: 20px;"');
        $table->add_field("ID: $client_id<br>
$firstname $lastname<br>
$address<br>
$city, $state $zip<br>
$country<br>
$phone
", 'l');
        $table->set_col_options('');
        $table2 = new TFTable();
        $table2->set_options('style="width: 100%;"');
        $table2->hide_title();
        $table2->set_row_options('style="border-top: 1px solid; color: #000000;"');
        $table2->add_field('Invoice Number');
        $table2->add_field('Created On');
        $table2->add_field('Due Date');
        $table2->add_field('Amount');
        $table2->add_field('Total Due');
        $table2->add_row();
        $table2->set_row_options('style="color: #555555;"');
        $table2->add_field($db->Record['invoice_id']);
        $table2->add_field(date('m/d/Y', $db->Record['invoice_date_entered']));
        $table2->add_field(date('m/d/Y', $db->Record['invoice_date_due']));
        $table2->add_field(number_format((float)$db->Record['invoice_amount'], 2));
        $table2->add_field(number_format((float)$amount, 2));
        $table2->add_row();
        $table2->set_row_options('style="border-top: 1px solid; color: #000000;"');
        $table2->add_field('Batch Date');
        $table2->add_field('AuthRet');
        $table2->add_field('AuthCode');
        $table2->add_field('AVS');
        $table2->add_field('TransID');
        $table2->add_row();
        $table2->set_row_options('style="color: #555555;"');
        $table2->add_field(date('m/d/Y', $db->Record['batch_stamp']));
        $table2->add_field($db->Record['auth_return']);
        $table2->add_field($db->Record['auth_code']);
        $table2->add_field($db->Record['avs_code']);
        $table2->add_field($db->Record['trans_id']);
        $table2->add_row();
        $table2->set_row_options('');
        $table->add_field($table2->get_table());
        $table->add_row();
        $table->set_colspan(2);
        $table->add_field($db->Record['invoice_snapshot']);
        $table->add_row();
        $db->query("select * from client_register where invoice_id=$invoice");
        $table2 = new TFTable();
        $table2->set_options('style="width: 100%;"');
        $table2->set_title('Register History for Invoice #'.$invoice);
        $table2->add_field('Date');
        $table2->add_field('Client');
        $table2->add_field('Description');
        $table2->add_field('Invoice');
        $table2->add_field('Due/Debit');
        $table2->add_field('Paid/Credit');
        $table2->add_field('Balance');
        $table2->add_row();
        $bal = 0;
        $table2->set_row_options('style="border-top: 1px solid #555555; color: #555555;"');
        while ($db->next_record(MYSQL_ASSOC)) {
            if ($db->Record['reg_payment'] != '') {
                $bal = bcadd($bal, $db->Record['reg_payment'], 2);
            }
            if ($db->Record['reg_bill'] != '') {
                $bal = bcsub($bal, $db->Record['reg_bill'], 2);
            }

            $table2->add_field(date('m/d/Y', $db->Record['reg_date']));
            $table2->add_field($db->Record['client_id']);
            $table2->add_field($db->Record['reg_desc']);
            $table2->add_field($db->Record['invoice_id']);
            $table2->add_field(number_format((float)$db->Record['reg_bill'], 2));
            $table2->add_field(number_format((float)$db->Record['reg_payment'], 2));
            if ($bal < 0) {
                $table2->add_field('<span style="color: red;">'.number_format((float)$bal, 2).'</span>');
            } else {
                $table2->add_field(number_format((float)$bal, 2));
            }
            $table2->add_row();
        }
        $table->set_colspan(2);
        $table->add_field($table2->get_table());
        $table->add_row();
        add_output($table->get_table());
    }
