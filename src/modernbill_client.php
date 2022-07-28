<?php
/**
 * modernbill_client()
 *
 * @return void
 * @throws \Exception
 * @throws \SmartyException
 */
    function modernbill_client()
    {
        page_title('ModernBill Client Information');
        $db = get_module_db('mb');
        $data = $GLOBALS['tf']->accounts->data;
        $table = new TFTable();
        function_requirements('has_acl');
        if ($GLOBALS['tf']->ima == 'admin' && has_acl('client_billing')) {
            add_js('tablesorter');
            if (isset($GLOBALS['tf']->variables->request['id'])) {
                function_requirements('get_modernbill_client_by_id');
                $client = get_modernbill_client_by_id($GLOBALS['tf']->variables->request['id']);
            } else {
                function_requirements('get_modernbill_clients');
                $clients = get_modernbill_clients(
                    [
                    'client_id',
                    'client_email',
                    'client_fname',
                    'client_lname',
                    'client_company',
                    'client_status'
                    ]
                );
                $smarty = new TFSmarty();
                $smarty->debugging = true;
                $rows = [];
                $smarty->assign('sortcol', 1);
                $smarty->assign('sortdir', 0);
                $smarty->assign('textextraction', "'complex'");
                $title = false;
                $values = [
                    'client_id' => 'Client ID',
                    'client_email' => 'Email',
                    'client_fname' => 'First Name',
                    'client_lname' => 'Last Name',
                    'client_company' => 'Company',
                    'client_status' => 'Status'
                ];
                foreach ($clients as $client) {
                    $client['client_email'] = $table->make_link('choice=none.modernbill_client&amp;id='.$client['client_id'], $client['client_id']);
                    unset($client['client_id']);
                    if (!$title) {
                        $title = [];
                        foreach (array_keys($client) as $key) {
                            $title[] = $values[$key];
                        }
                        $smarty->assign('table_header', $title);
                        $title = true;
                    }
                    $rows[] = $client;
                }
                $smarty->assign('table_rows', $rows);
                add_output($smarty->fetch('tablesorter/tablesorter.tpl'));
                return;
            }
        } else {
            function_requirements('get_modernbill_client_by_email');
            $client = get_modernbill_client_by_email($data['account_lid']);
        }
        if (is_array($client)) {
            $table = new TFTable();
            $table->set_title('Client Information');
            $table->add_field('Client ID');
            $table->add_field($client['client_id']);
            $table->add_field('Client Email');
            $table->add_field($client['client_email']);
            $table->add_row();
            $table->add_field('First Name');
            $table->add_field($client['client_fname']);
            $table->add_field('Last Name');
            $table->add_field($client['client_lname']);
            $table->add_row();
            $table->add_field('Company');
            $table->add_field($client['client_company']);
            $table->add_field('Status');
            $table->add_field($client['client_status']);
            $table->add_row();
            $table->add_field('Address');
            $table->add_field($client['client_address']);
            $table->add_field('Address #2');
            $table->add_field($client['client_address_2']);
            $table->add_row();
            $table->add_field('City');
            $table->add_field($client['client_city']);
            $table->add_field('State');
            $table->add_field($client['client_state']);
            $table->add_row();
            $table->add_field('Zip');
            $table->add_field($client['client_zip']);
            $table->add_field('Country');
            $table->add_field($client['client_country']);
            $table->add_row();
            $table->add_field('Phone Number');
            $table->add_field($client['client_phone1']);
            $table->add_field('Phone Number #2');
            $table->add_field($client['client_phone2']);
            $table->add_row();
            add_output($table->get_table());
        //echo '<pre>';
            //print_r($client);
        } else {
            add_output('No ModernBill Client Information Found');
        }
    }
