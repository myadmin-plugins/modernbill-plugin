<?php

/**
 * @param $string
 * @return string
 */
function smartslashes($string)
{
    if (get_magic_quotes_gpc()) {
        return $string;
    }
    return addslashes($string);
}

/**
 * @param $array
 * @return array|mixed
 */
function my_array_shift(&$array)
{
    $array[] = $array[0];
    reset($array);
    $i = 0;
    $newArray = [];
    $val = each($array)[1];
    $key = each($array)[0];
    while (each($array)) {
        if (0 < $i) {
            $newArray[$key] = $val;
        } else {
            $toReturn = $array[$key];
        }
        ++$i;
    }
    $array = $newArray;
    asort($array);
    reset($array);
    if (is_array($toReturn)) {
        return [1 => $toReturn];
    }
    return $toReturn;
}

/**
 * @param $path
 * @return mixed
 */
function get_dir_array($path)
{
    $handle = opendir($path);
    while ($file = readdir($handle)) {
        if (!($file != '.') || !($file != '..') || !($file != 'CVS') || !($file != 'index.html')) {
            $file = explode('.', $file);
            $array[$file[0]] = ucwords($file[0]);
        }
    }
    closedir($handle);
    return $array;
}

/**
 * @param null $t
 * @return string
 */
function mb_functions($t = null)
{
    $retval = '4.4.1';
    switch ($t) {
            case 'action_functions':
                if (function_exists('mb_action_functions')) {
                    exit('F1');
                }
                $retval = sha1(mb_action_functions());
                return $retval;
            case 'auth_functions':
                if (function_exists('mb_auth_functions')) {
                    exit('F1');
                }
                $retval = sha1(mb_auth_functions());
                return $retval;
            case 'cc_functions':
                if (function_exists('mb_cc_functions')) {
                    exit('F1');
                }
                $retval = sha1(mb_cc_functions());
                return $retval;
            case 'db_core_functions':
                if (function_exists('mb_db_core_functions')) {
                    exit('F1');
                }
                $retval = sha1(mb_db_core_functions());
                return $retval;
            case 'db_functions':
                if (function_exists('mb_db_functions')) {
                    exit('F1');
                }
                $retval = sha1(mb_db_functions());
                return $retval;
            case 'display_functions':
                if (function_exists('mb_display_functions')) {
                    exit('F1');
                }
                $retval = sha1(mb_display_functions());
                return $retval;
            case 'email_functions':
                if (function_exists('mb_email_functions')) {
                    exit('F1');
                }
                $retval = sha1(mb_email_functions());
                return $retval;
            case 'faq_functions':
                if (function_exists('mb_faq_functions')) {
                    exit('F1');
                }
                $retval = sha1(mb_faq_functions());
                return $retval;
            case 'idn_functions':
                if (function_exists('mb_idn_functions')) {
                    exit('F1');
                }
                $retval = sha1(mb_idn_functions());
                return $retval;
            case 'misc_functions':
                if (function_exists('mb_misc_functions')) {
                    exit('F1');
                }
                $retval = sha1(mb_misc_functions());
                return $retval;
            case 'order_functions':
                if (function_exists('mb_order_functions')) {
                    exit('F1');
                }
                $retval = sha1(mb_order_functions());
                return $retval;
            case 'pw_functions':
                if (function_exists('mb_pw_functions')) {
                    exit('F1');
                }
                $retval = sha1(mb_pw_functions());
                return $retval;
            case 'select_functions':
                if (function_exists('mb_select_functions')) {
                    exit('F1');
                }
                $retval = sha1(mb_select_functions());
                return $retval;
            case 'sql_select_functions':
                if (function_exists('mb_sql_select_functions')) {
                    exit('F1');
                }
                $retval = sha1(mb_sql_select_functions());
                return $retval;
            case 'validate_functions':
                if (function_exists('mb_validate_functions')) {
                    exit('F1');
                }
                $retval = sha1(mb_validate_functions());
                return $retval;
            case 'xml_functions':
                if (function_exists('mb_xml_functions')) {
                    exit('F1');
                }
                $retval = sha1(mb_xml_functions());
        }
    return $retval;
}

/**
 * @param $flag
 * @return string
 */
function readmore_manual($flag)
{
    global $sphera_enabled;
    global $_CONFIG;
    global $standard_url;
    $manual_index = 'http://manual.modernbill.com/adminhelp/english/Admin_Manual_v4.htm#';
    $manual_dir = !empty($_CONFIG['admin_manual_url']) ? $_CONFIG['admin_manual_url'] : $manual_index;
    $support_link = $manual_dir.$flag;
    $link = "<a href=\"javascript:void(0);\" onclick=\"window.open('".$support_link."','help_file','scrollbars=yes,location=no,menubar=no,status=no,titlebar=no,resizable,height=400px width=650px,left=1,top=1, copyhistory=yes');\">".READMORE.'</a>';
    return $link;
}

    function register_session()
    {
        global $dbh;
        global $l_error;
        global $op;
        global $failed_error;
        if ($op == 'login' || $op == 'renew_key' || $op == 'fetch_key') {
            if ($dbh) {
                dbConnect();
            }
            $host = 'http://www.modernsupport.com';
            $port = ':80';
            $path = '/l/_api.php';
            $formdata = [
                'l_email' => $_SERVER['UNIQUE_DATA']['_email'], 'l_license' => $_SERVER['UNIQUE_DATA']['_license'], 'l_host' => $_SERVER['UNIQUE_DATA']['_domain'], 'l_hash' => md5($_SERVER['UNIQUE_DATA']['_email'] . $_SERVER['UNIQUE_DATA']['_license'].'mBx'
                                                                                                                                                                                    . $_SERVER['UNIQUE_DATA']['_domain'])
            ];
            $formdataS = urlencode(myadmin_stringify($formdata));
            $getstring = '?id='.$formdataS;
            $fp = @fopen(@$host.$port.$path.$getstring, 'rb');
            if ($fp) {
                $to = 'licensefail@modernbill.com';
                $from = "From: licensefail@modernbill.com\n";
                $subject = 'License Connection Failed for '.$_SERVER['UNIQUE_DATA']['_email'].' '.$_SERVER['UNIQUE_DATA']['_license'];
                $body = $_SERVER['UNIQUE_DATA'][RegEmail].PHP_EOL.$_SERVER['UNIQUE_DATA']['_license'].PHP_EOL.('('.$errno . ", {$errstr}, {$errfile}, {$errline})");
                @mail($to, $subject, @strip_tags($body), $from);
            } else {
                $buffer = null;
                while (!$fp || feof($fp)) {
                    $buffer .= fgets($fp, 1024);
                }
                mb_ereg("\\{(.*)\\}", $buffer, $args);
                $this_response = explode('|', $args[1]);
                fclose($fp);
                switch ($this_response[0]) {
                    case 1:
                        $keydata = str_replace('_', '|', $this_response[5]);
                        $l_config_type = md5('license_4');
                        $_res1 = mysql_query("DELETE FROM config WHERE config_type = '".$l_config_type."'");
                        $_res2 = mysql_query("INSERT INTO config (config_type,config_41) VALUES ('".$l_config_type."','{$keydata}')");
                        header('location: index.php?op=logout');
                        break;
                    default:
                        if ($this_response[0] == 0 && ($op == 'renew_key' || $op == 'fetch_key')) {
                            $failed_error = $this_response[1].' - '.$this_response[6];
                        }
                        $to = 'licensefail@modernbill.com';
                        $from = "From: licensefail@modernbill.com\n";
                        $subject = 'License Renewal Failed for '.$_SERVER['UNIQUE_DATA']['_email'].' '.$_SERVER['UNIQUE_DATA']['_license'];
                        $body = $_SERVER['UNIQUE_DATA']['_email'].PHP_EOL.$_SERVER['UNIQUE_DATA']['_license'].PHP_EOL.$args[1];
                        @mail($to, $subject, @strip_tags($body), $from);
                }
            }
        }
    }

/**
 * @return null|string
 */
function return_enckey()
{
    global $this_lek_config;
    $ek_hash = $this_lek_config['config_41'];
    $ek_pin = $this_lek_config['config_3'];
    $mb_lek = 'mak#f%e9S^euSrn!fFSahgUs6U/YO&643()SgDfsuv32hnWEas976ghd';
    $lek_pw = base64_encode(md5($ek_pin).(':'.$ek_pin.':') . md5($mb_lek));
    $ek_hash = str_replace('-----BEGIN LEKHASH-----', '', $ek_hash);
    $ek_hash = str_replace('-----END LEKHASH-----', '', $ek_hash);
    $ek_hash = preg_replace("'(\r|\n)'", '', $ek_hash);
    [$md5data, $keydata] = explode(':', $ek_hash);
    if ($md5data != md5($keydata.$lek_pw)) {
        return null;
    }
    $enc_key = base64_decode(encrpyt($lek_pw, base64_decode($keydata), 1, 1));
    return $enc_key;
}

/**
 * @param $keydata
 * @return int|mixed|string
 */
function decode_key($keydata)
{
    $pwd = 'lafcrp8w94n5sznav7345623s65rf9fjfhght6a54s2349679467hwt645vf87fy067';
    $keydata = str_replace('-----BEGIN LICENSE-----', '', $keydata);
    $keydata = str_replace('-----END LICENSE-----', '', $keydata);
    $keydata = preg_replace("'(\r|\n)'", '', $keydata);
    [$md5data, $keydata] = explode('|', $keydata);
    if ($md5data != md5($keydata.base64_encode($pwd).$pwd)) {
        return -1;
    }
    $keydata = base64_decode($keydata);
    $keydata = encrpyt(base64_encode($pwd).$pwd, $keydata, 1, 1);
    [$keydata, $md5data] = explode(':', $keydata);
    if ($md5data != md5($keydata)) {
        return -1;
    }
    $keydata = myadmin_unstringify(urldecode($keydata));
    return $keydata;
}

/**
 * @param $keydata
 * @return int|mixed
 */
function decode_key1($keydata)
{
    $keydata = str_replace(['-----BEGIN LICENSE-----', '-----END LICENSE-----'], ['', ''], $keydata);
    $keydata = implode('', mb_split("\n", $keydata));
    [$md5data1, $keydata1] = explode('|', $keydata);
    $keydata2 = str_replace($md5data1.'|'.$keydata1.'|', '', $keydata);
    $obj = new mblg(implode(
        [
                                    'W', '+', 'd', 'F', 'p', '#', '-', 'q', 'g', 'B', '%', '~', 'I', 'M', 'a', 'r', 'L', '4', 'x', ':', '^', 'u', '}', 'U', '[', 'k', 'R', 'G', 'h', '5', 'l', 'A', '{', 't', 'f', '1', 'K', 'H', 'J', ')', 'v', 'N', 'T', '&', ';', 'n', 'S', ']', 'b', 'Z', 'z', 'Y',
                                    'o',
                                    'C',
                                    'Q',
                                    'm'
                            ]
    ));
    $keydata2 = $obj->decrypt(base64_decode(str_rot13(strrev(icase(str_replace('|', '/', $keydata2))))));
    $keydata2 = myadmin_unstringify(urldecode($keydata2));
    unset($keydata1);
    unset($md5data1);
    unset($obj);
    if ($keydata2['_version'] != sha1(str_rot13(sha1($keydata2['_id'].$keydata2['_domain'].$keydata2['_id'].$keydata2['_regto'].$keydata2['_id'].$keydata2['_license'].$keydata2['_id'].$keydata2['_email'].$keydata2['_id'])))) {
        unset($keydata2);
        return -2;
    }
    return $keydata2;
}

/**
 * @param $data
 * @return string
 */
function hex2bin_custom($data)
{
    $len = mb_strlen($data);
    return pack('H'.$len, $data);
}

/**
 * @param null $pwd
 * @param null $data
 * @param null $decrypt
 * @param null $is_license
 * @return string
 */
function encrpyt($pwd = null, $data = null, $decrypt = null, $is_license = null)
{
    global $enc_type;
    $use_enc_type = $is_license ? 'RC4' : $enc_type;
    switch ($use_enc_type) {
            case 'tripleDES':
                $pwd = mb_substr($pwd, 0, 24);
                if ($decrypt) {
                    $td = mcrypt_module_open('tripledes', '', 'ecb', '');
                    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
                    mcrypt_generic_init($td, $pwd, $iv);
                    $decrypted_data = mdecrypt_generic($td, $data);
                    mcrypt_generic_deinit($td);
                    mcrypt_module_close($td);
                    return $decrypted_data;
                }
                $td = mcrypt_module_open('tripledes', '', 'ecb', '');
                $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
                mcrypt_generic_init($td, $pwd, $iv);
                $encrypted_data = mcrypt_generic($td, $data);
                mcrypt_generic_deinit($td);
                mcrypt_module_close($td);
                return $encrypted_data;
            case '3DES':
                $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_TripleDES, MCRYPT_MODE_ECB), MCRYPT_RAND);
                $key = crc32($pwd);
                if ($decrypt) {
                    return mcrypt_decrypt(MCRYPT_TripleDES, $key, hex2bin_custom($data), MCRYPT_MODE_ECB, $iv);
                }
                return bin2hex(mcrypt_encrypt(MCRYPT_TripleDES, $key, $data, MCRYPT_MODE_ECB, $iv));
        }
    $data = $decrypt ? urldecode($data) : $data;
    $key[] = $box[] = $temp_swap = '';
    $pwd_length = 0;
    $pwd_length = mb_strlen($pwd);
    $i = 0;
    for (; $i <= 255; ++$i) {
        $key[$i] = ord(mb_substr($pwd, $i % $pwd_length, 1));
        $box[$i] = $i;
    }
    $x = 0;
    $i = 0;
    for (; $i <= 255; ++$i) {
        $x = ($x + $box[$i] + $key[$i]) % 256;
        $temp_swap = $box[$i];
        $box[$i] = $box[$x];
        $box[$x] = $temp_swap;
    }
    $temp = $k = $cipherby = $cipher = '';
    $a = $j = 0;
    $i = 0;
    for ($iMax = mb_strlen($data); $i < $iMax; ++$i) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $temp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $temp;
        $k = $box[($box[$a] + $box[$j]) % 256];
        $cipherby = ord(mb_substr($data, $i, 1)) ^ $k;
        $cipher .= chr($cipherby);
    }
    if ($decrypt) {
        return urldecode(urlencode($cipher));
    }
    return urlencode($cipher);
}

/**
 * @param $user_type
 * @param $tamper_test
 * @param null $param1
 * @param null $param2
 */
function secure_access($user_type, $tamper_test, $param1 = null, $param2 = null)
{
    global $this_admin;
    global $this_user;
    global $https;
    global $secure_url;
    if (sha1('36b18d99b63d16495acdd6a7d4df9531b8920e8b') != $tamper_test) {
        echo "             <font color=\"BLUE\"><tt>\n             -----------[ERROR]------------<br />\n             Files have been tampered with.<br />\n             Please upload all files again.<br />\n             -----------[ERROR]------------<br />\n             </tt></font>\n             ";
        exit();
    }
    switch ($user_type) {
            case 'admin':
            switch ($user_type) {
            }
            if (!testlogin() || !$this_admin || $this_user) {
                break;
            } else {
                return;
            }
            // no break
            case 'all':
                if (!testlogin()) {
                    break;
                } else {
                    return;
                }
                // no break
            case 'cron':
                return;
            case 'user':
                if (!testlogin() || !$this_user) {
                }
        }
    header('Location: '.$https . "://{$secure_url}" . ($login_page.'?op=logout'));
    exit();
}

/**
 * @param string $type
 */
function sca($type = 'admin')
{
    global $this_admin;
    global $this_user;
    global $https;
    global $secure_url;
    global $tamper_test;
    if (sha1('36b18d99b63d16495acdd6a7d4df9531b8920e8b') != $tamper_test) {
        echo "             <font color=\"RED\"><tt>\n             -----------[ERROR]------------<br />\n             Files have been tampered with.<br />\n             Please upload all files again.<br />\n             -----------[ERROR]------------<br />\n             2</tt></font>\n             ";
        exit();
    }

    switch ($type) {
            case 'admin':
            switch ($type) {
            }
            if (!testlogin() || !$this_admin || $this_user) {
                break;
            } else {
                return;
            }
            // no break
            case 'all':
                if (!testlogin()) {
                    break;
                } else {
                    return;
                }
                // no break
            case 'cron':
                return;
            case 'user':
                if (!testlogin() || !$this_user) {
                }
        }
    header('Location: '.$https . "://{$secure_url}" . ($login_page.'?op=logout'));
    exit();
}

/**
 * @param $client_id
 * @param $encryption_key
 * @param bool $md5
 * @return string
 */
function dcc($client_id, $encryption_key, $md5 = false)
{
    global $dbh;
    global $_PAYMENTS;
    global $tamper_test;
    if ($dbh) {
        dbConnect();
    }
    if (sha1('36b18d99b63d16495acdd6a7d4df9531b8920e8b') != $tamper_test) {
        echo "             <font color=\"RED\"><tt>\n             -----------[ERROR]------------<br />\n             Files have been tampered with.<br />\n             Please upload all files again.<br />\n             -----------[ERROR]------------<br />\n             3</tt></font>\n             ";
        exit();
    }
    $sql = 'SELECT client_stamp,billing_cc_num FROM client_info WHERE client_id = '.$client_id;
    [$client_stamp, $billing_cc_num] = adodb_one_array($sql);
    global $enc_type;
    if ($enc_type == 'tripleDES') {
        $billing_cc_num = urldecode($billing_cc_num);
    }
    if ($_PAYMENTS['lek_on']) {
        $encryption_key = return_enckey();
    } else {
        $encryption_key = $md5 ? $encryption_key : md5($encryption_key);
    }
    $plain_txt_cc = encrpyt($client_stamp.$encryption_key, $billing_cc_num, 1, 0);
    return $plain_txt_cc;
}

/**
 * @param $client_id
 * @param null $encryption_key
 * @param $billing_cc_num
 * @param null $client_stamp
 * @return string
 */
function ecc($client_id, $encryption_key = null, $billing_cc_num, $client_stamp = null)
{
    global $dbh;
    global $_PAYMENTS;
    global $tamper_test;
    global $enc_type;
    if ($dbh) {
        dbConnect();
    }
    if (sha1('36b18d99b63d16495acdd6a7d4df9531b8920e8b') != $tamper_test) {
        echo "             <font color=\"RED\"><tt>\n             -----------[ERROR]------------<br />\n             Files have been tampered with.<br />\n             Please upload all files again.<br />\n             -----------[ERROR]------------<br />\n             4</tt></font>\n             ";
        exit();
    }
    if ($client_stamp) {
        $sql = 'SELECT client_stamp FROM client_info WHERE client_id = '.$client_id;
        [$client_stamp] = adodb_one_array($sql);
    }
    if ($_PAYMENTS['lek_on']) {
        $encryption_key = return_enckey();
    } else {
        $encryption_key = $encryption_key ? md5($encryption_key) : null;
    }
    $ecc = encrpyt($client_stamp.$encryption_key, $billing_cc_num, 0, 0);
    global $enc_type;
    if ($enc_type == 'tripleDES') {
        $ecc = urlencode($ecc);
    }
    return $ecc;
}

/**
 * @param $id
 * @param $name
 * @param $variable_name
 * @return string
 */
function generic_select_menu($id, $name, $variable_name)
{
    global $details_view;
    global $_SELECTS;
    $select_menu = '<select name="' .$name. '">';
    foreach ($_SELECTS[$variable_name] as $key => $value) {
        $select_menu .= '<option value="' .$key. '"';
        if ($id == $key) {
            $select_menu .= ' SELECTED ';
            if ($details_view) {
                $thisvar = $value;
                break;
            }
        } else {
            $select_menu .= '>'.$value . "</option>\n";
        }
    }
    $select_menu .= '</select>';
    if ($details_view) {
        return $thisvar;
    }
    return $select_menu;
}

    $_CONFIG['modules']['mod_license']['enabled'] = false;
    $version = $current_version = '4.4.1';
    $build_type = 'K:001';
    $version_name = 'ModernBill .:. Client Billing System';
    $onoff = '';
    if ($onoff != 1) {
        @extract($HTTP_SERVER_VARS, @EXTR_SKIP);
        @extract($HTTP_COOKIE_VARS, @EXTR_SKIP);
        @extract($HTTP_POST_FILES, @EXTR_SKIP);
        @extract($HTTP_POST_VARS, @EXTR_SKIP);
        @extract($HTTP_GET_VARS, @EXTR_SKIP);
        @extract($HTTP_ENV_VARS, @EXTR_SKIP);
        @extract(@$_SERVER, @EXTR_SKIP);
        @extract(@$_COOKIE, @EXTR_SKIP);
        @extract(@$_POST, @EXTR_SKIP);
        @extract(@$_GET, @EXTR_SKIP);
        @extract(@$_ENV, @EXTR_SKIP);
    }
    if ($DIR && ($HTTP_COOKIE_VARS[dir] || $HTTP_POST_VARS[dir] || $HTTP_GET_VARS[dir] || $_COOKIE[dir] || $_POST[dir] || $_GET[dir])) {
        $ip = $HTTP_SERVER_VARS[REMOTE_ADDR];
        $host = gethostbyaddr($ip);
        $url = $HTTP_SERVER_VARS['HTTP_HOST'].$HTTP_SERVER_VARS['REQUEST_URI'];
        $admin = $SERVER_ADMIN ? $SERVER_ADMIN : 'security@modernbill.com';
        $body = "IP:\t".$ip."\nHOST:\t{$host}\nURL:\t{$url}\nVER:\t{$version}\nTIME:\t".date('Y/m/d: h:i:s').PHP_EOL;
        @mail($admin, 'Possible breakin attempt.', $body, @'From: '.$admin . "\r\n");
        echo str_repeat(' ', 300).PHP_EOL;
        flush();
        echo ' <html><head><body><center><h3><tt><b><font color=RED>Security violation from: ';
        echo $ip;
        echo ' @ ';
        echo $host;
        echo '</font></b></tt></h3></center><hr><pre>';
        @system(@'traceroute '.@escapeshellcmd($ip).' 2>&1');
        echo '</pre><hr><center><h2><tt><b><font color=RED>The admin has been alerted.</font></b></tt></h2></center></body></html>';
        exit();
    }
    require_once $DIR.'include/config/config.locale.php';
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
    $ADODB_SESSION_DRIVER = 'mysql';
    $ADODB_SESSION_CONNECT = $locale_db_host;
    $ADODB_SESSION_DB = $locale_db_name;
    $ADODB_SESSION_USER = $locale_db_login;
    $ADODB_SESSION_PWD = $locale_db_pass;
    require $DIR.'include/misc/mod_adodb/adodb.inc.php';
    $dbh =& ADONewConnection($ADODB_SESSION_DRIVER);
    $dbh->connect($ADODB_SESSION_CONNECT, $ADODB_SESSION_USER, $ADODB_SESSION_PWD, $ADODB_SESSION_DB);
    require $DIR.'include/misc/mod_adodb/session/adodb-cryptsession.php';
    if ($argv[2] != 'cron') {
        'session_data';
        adodb_sess_open(false, false, false);
        @session_start();
        @session_register('referrer_array', 'cycle_count', 'language', 'theme', 'uri', 'this_admin', 'this_user');
    }
    if ($_SESSION['this_admin']) {
        ini_set('session.gc_maxlifetime', 7200);
    }
    require $DIR.'include/misc/heart/db_main_functions.inc.php';
    $_sql = 'SELECT * FROM config ORDER BY config_type';
    $_res = adodb_query($_sql, 'A');
    $_CONFIG['modules'] = [];
    $_CONFIG['ordergroups'] = [];
    $_CONFIG['servergroups'] = [];
    while (!$_res->EOF) {
        ${'this_'.$_res->fields['config_type'].'_config'} = array_diff($_res->fields, ['']);
        if (preg_match("/\\bmod_/", $_res->fields['config_type'])) {
            $_CONFIG['modules'][] = $_res->fields['config_type'];
        } elseif (preg_match("/\\bvortech_type/", $_res->fields['config_type'])) {
            $_CONFIG['ordergroups'][] = $_res->fields['config_type'];
        } elseif (preg_match("/\\bserver_type/", $_res->fields['config_type'])) {
            $_CONFIG['servergroups'][] = $_res->fields['config_type'];
        }
        $_res->movenext();
    }
    require_once $DIR.'include/config/config.servers.php';
    require_once $DIR.'include/misc/heart/auth_functions.inc.php';
    require_once $DIR.'include/config/config.main.php';
    require_once $DIR.'include/config/config.version.php';
    $version .= ":B-{$version_build}:{$build_type}:";
    if ($this_admin && $lic_agree) {
        $_sql = "UPDATE config\n                SET config_60 = '1',\n                    config_59 = '".$version."',\n                    config_58 = '". time() .("',\n                    config_57 = '". $this_admin['admin_email']."'\n              WHERE config_type='main'");
        @adodb_query($_sql);
    }
    global $cycle_count;
    global $language;
    $language = !isset($language) ? $default_language : $language;
    $language = $new_language ? $new_language : $language;
    $_SESSION[language] = $language = preg_replace('/[^a-zA-Z]/', '', $language);
    if (!$signup_form && $argv[2] != 'cron') {
        session_register('language');
    }
    $translation_file = file_exists($DIR.('include/translations/'.$language.'.trans.inc.php')) ? $language : $default_language;
    require_once $DIR.('include/translations/'.$translation_file.'.trans.inc.php');
    if ($db_table == 'config' || $tile == 'sysconfig') {
        $translation_file = file_exists($DIR.('include/translations_config/'.$translation_file).'_config.trans.inc.php') ? $translation_file : 'en';
        require_once $DIR.('include/translations_config/'.$translation_file).'_config.trans.inc.php';
    }
    require_once $DIR.'include/config/config.set_themes.php';
    $default_theme = !$this_admin && !$this_user ? $default_admin_theme : $default_user_theme;
    if ($new_theme) {
        $_SESSION['use_this_theme'] = $_SESSION['new_theme'] = $new_theme;
    } else {
        $_SESSION['use_this_theme'] = $default_theme;
    }
    $_SESSION['theme'] = preg_replace('/[^a-zA-Z]/', '', $_SESSION['use_this_theme']);
    if (!$signup_form && $argv[2] != 'cron') {
        session_register('theme');
    }
    if (!is_array(${'this_theme_'.$_SESSION['theme'].'_config'}) && !mb_ereg('insert_theme|logout', $op)) {
        echo "<br><tt><font color=red>\n             [Error T] ".strip_tags($_SESSION['theme'])." is not a valid theme!\n             <br>\n             <br>\n             <br>\n             <b>Why do I see this error?</b>\n             <ol>\n             <li> Your browser has cached a theme that does not exist. Please refresh this page.\n             <br>\n             <br>\n             ___ OR ___\n             <br>\n             <br>\n             <li> The default ".strip_tags($_SESSION['theme'])." theme is not found in the database.\n             You may need to edit the config table manually to reset the default theme.\n             Please contact support for the sql query.\n             <br>\n             <br>\n             ___ OR ___\n             <br>\n             <br>\n             <li> The current ".strip_tags($_SESSION['theme'])." theme files are not found on your server.\n             If you created a new theme, you also need to upload or copy the supporting theme files here: include/config/themes/".strip_tags($theme)."/*\n             <br>\n             <br>\n             </font></tt>";
        @session_destroy();
        exit();
    }
    $theme_dir = file_exists($DIR.'include/config/themes/'.$_SESSION['theme'].'/theme.config.inc.php') ? $_SESSION['theme'] : $default_theme;
    require_once $DIR.('include/config/themes/'.$theme_dir.'/theme.config.inc.php');
    require_once $DIR.'include/config/config.breadcrumbs.php';
    require_once $DIR.'include/config/config.selects.php';
    require_once $DIR.'include/config/config.email.php';
    require_once $DIR.'include/config/config.payments.php';
    require_once $DIR.'include/config/config.registrars.php';
    require_once $DIR.'include/config/config.invbilling.php';
    require_once $DIR.'include/config/config.batch.php';
    require_once $DIR.'include/config/config.version.php';
    require_once $DIR.'include/config/config.currency.php';
    require_once $DIR.'include/config/config.tax.php';
    require_once $DIR.'include/config/config.lek.php';
    require_once $DIR.'include/config/config.manual.php';
    require_once $DIR.'include/config/config.usergui.php';
    require_once $DIR.'include/config/config.set_ordergroups.php';
    if (file_exists($DIR.'newkey.php')) {
        echo '<FONT SIZE=2><pre>Loading new license key...';
        $fd = fopen($DIR.'newkey.php', 'rb');
        if ($fd) {
            exit('<font color=red>NOT OK (Can not read newkey.php file on your server.)</font>');
        }
        while (!feof($fd)) {
            $licensekey .= fgets($fd, 4096);
        }
        fclose($fd);
        echo "<font color=green>OK</font>\nValidating new license key...";
        $MB_key_array = decode_key($licensekey);
        if ($MB_key_array == -1) {
            unset($MB_key_array);
            exit('<font color=red>NOT OK (Your license key is not valid. Please generate a new license key.) [1]</font>');
        }
        if ($MB_key_array == -2) {
            unset($MB_key_array);
            exit('<font color=red>NOT OK (Your license key is not valid for this version of ModernBill. Please generate a new license key.)</font>');
        }
        $MB_key_array['_domain'] = $MB_key_array['RegDomain'];
        $MB_key_array['_regto'] = $MB_key_array['RegCompany'];
        $MB_key_array['_license'] = $MB_key_array['RegLicense'];
        $MB_key_array['_email'] = $MB_key_array['RegEmail'];
        $MB_key_array['_expires'] = $MB_key_array['ExpDate'];
        $MB_key_array['_id'] = $MB_key_array['VerTier'];
        $secure_install_url = parse_url('http://'.strtolower($secure_url));
        $valid_domain = $secure_install_url['host'];
        $RegDomain = strtolower($MB_key_array['_domain']);
        switch ($MB_key_array['_id']) {
            case 41:
                $valid_reg_domain = $RegDomain == $valid_domain || eregi($valid_domain, $RegDomain) ? true : false;
                break;
            default:
                $valid_reg_domain = $RegDomain == $valid_domain ? true : false;
        }
        if ($valid_reg_domain) {
            if ($MB_key_array['_id'] < 30 && $MB_key_array['_expires'] < time()) {
                unset($MB_key_array);
                exit('<font color=red>LICENSE HAS EXPIRED (You need a NEW license key created.)</font>');
            }
            echo "<font color=green>DOMAIN MATCH OK</font>\n";
        } else {
            echo '<font color=red>INSTALL DOMAIN '.$valid_domain . " DOES NOT MATCH LICENSE DOMAIN {$RegDomain} (You need a NEW license key created for {$valid_domain}.)</font>\n";
            exit();
        }
        echo 'Installing new license key...';
        $l_config_type = md5('license_4');
        $_res1 = mysql_query_logger("DELETE FROM config WHERE config_type = '".$l_config_type."'");
        if ($_res1) {
            echo "<font color=red>NOT OK (Deleting old license failed.)</font>\n";
            exit();
        }
        $_res2 = mysql_query_logger("INSERT INTO config (config_type,config_41) VALUES ('".$l_config_type."','{$licensekey}')");
        if ($_res2) {
            echo "<font color=red>NOT OK (Inserting new license failed.)</font>\n";
            exit();
        }
        echo "<font color=green>OK</font>\n\n";
        echo '<font color=blue size=1><pre>'.$licensekey . "</pre></font>\n\n";
        echo '<font size=2 color=red><b>Please delete the newkey.php file now and refresh this page.</b></font></pre></FONT>';
        exit();
    }
    $l_config_type = md5('license_4');
    $licensekey = ${'this_'.$l_config_type.'_config'}['config_41'];
    $_SERVER['UNIQUE_DATA'] = decode_key($licensekey);
    if ($_SERVER['UNIQUE_DATA'] == -1) {
        unset($_SERVER['UNIQUE_DATA']);
        exit('<font color=red>NOT OK (Your license key is not valid for this version of ModernBill. Please generate a new license key.)</font>');
    }
    if ($_SERVER['UNIQUE_DATA'] == -2) {
        unset($_SERVER['UNIQUE_DATA']);
        exit('<font color=red>NOT OK (Your license key is not valid for this version of ModernBill. Please generate a new license key.)</font>');
    }
    $_SERVER['UNIQUE_DATA']['_domain'] = $_SERVER['UNIQUE_DATA']['RegDomain'];
    $_SERVER['UNIQUE_DATA']['_regto'] = $_SERVER['UNIQUE_DATA']['RegCompany'];
    $_SERVER['UNIQUE_DATA']['_license'] = $_SERVER['UNIQUE_DATA']['RegLicense'];
    $_SERVER['UNIQUE_DATA']['_email'] = $_SERVER['UNIQUE_DATA']['RegEmail'];
    $_SERVER['UNIQUE_DATA']['_expires'] = $_SERVER['UNIQUE_DATA']['ExpDate'];
    $_SERVER['UNIQUE_DATA']['_id'] = $_SERVER['UNIQUE_DATA']['VerTier'];
    // @codingStandardsIgnoreStart
    if (defined('RegDomain') || defined('RegCompany') || defined('RegLicense') || defined('RegEmail') || defined('ExpDate') || defined('VerTier')) {
        exit('<font color=red>[Error L3] Invalid license key. Please contact customer support.</font>');
    }
    if (file_exists($DIR.'include/misc/heart/db_core_functions.inc.php')) {
        exit('<font color=red>[Error L4] Invalid license key. Please contact customer support.</font>');
    }
    define('RegDomain', $_SERVER['UNIQUE_DATA']['_domain']);
    define('VerTier', $_SERVER['UNIQUE_DATA']['_id']);
    require $DIR.'include/misc/heart/db_core_functions.inc.php';
    if (function_exists('mb_db_core_functions')) {
        exit('<font color=red>[Error L5] Invalid license key. Please contact customer support.</font>');
    }
    if (!defined('RegDomain') || !defined('RegCompany') || !defined('RegLicense') || !defined('RegEmail') || !defined('ExpDate') || !defined('VerTier')) {
        exit('<font color=red>[Error L6] Invalid license key. Please contact customer support.</font>');
    }
    switch ($_SERVER['UNIQUE_DATA']['_id']) {
        case 41:
            $exp_num = 0;
            define('ProductEdition', 'MBv4 Developers License');
            $MaxClients = 0;
            break;
        case 30:
            $exp_num = 0;
            define('ProductEdition', 'MBv4 Owned License 50');
            $MaxClients = 50;
            break;
        case 31:
            $exp_num = 0;
            define('ProductEdition', 'MBv4 Owned License 100');
            $MaxClients = 100;
            break;
        case 32:
            $exp_num = 0;
            define('ProductEdition', 'MBv4 Owned License 250');
            $MaxClients = 250;
            break;
        case 33:
            $exp_num = 0;
            define('ProductEdition', 'MBv4 Owned License 500');
            $MaxClients = 500;
            break;
        case 34:
            $exp_num = 0;
            define('ProductEdition', 'MBv4 Owned License 1000');
            $MaxClients = 1000;
            break;
        case 35:
            $exp_num = 0;
            define('ProductEdition', 'MBv4 Owned License Unlimited');
            $MaxClients = 0;
            break;
        case 20:
            $exp_num = 1;
            define('ProductEdition', 'MBv4 Lease License 10');
            $MaxClients = 10;
            break;
        case 21:
            $exp_num = 1;
            define('ProductEdition', 'MBv4 Lease License 50');
            $MaxClients = 50;
            break;
        case 22:
            $exp_num = 1;
            define('ProductEdition', 'MBv4 Lease License Unlimited');
            $MaxClients = 0;
            break;
        case 23:
            $exp_num = 1;
            define('ProductEdition', 'MBv4 Lease License 2500');
            $MaxClients = 2500;
            break;
        default:
            $_SERVER['UNIQUE_DATA']['_id'] = 10;
            $exp_num = 1;
            define('ProductEdition', 'MBv4 Demo License');
            $MaxClients = 10;
    }
    // @codingStandardsIgnoreEnd
    $version .= $_SERVER['UNIQUE_DATA']['_id'];
    if ($op == 'sid='.sha1(sha1('mb_vkey'.date('m/d/y')))) {
        exit('<font size=1 color=blue><pre>'.$version . "\n (" . $_SERVER['UNIQUE_DATA']['_id'] . (")\n\n" . $licensekey.'<pre></font>'));
    }
    if ($op == 'sid='.sha1(sha1(sha1('mb_kkey'.date('m/d/y').'dttb06')))) {
        $l_config_type = md5('license_4');
        $_res1 = mysql_query_logger("DELETE FROM config WHERE config_type = '".$l_config_type."'");
        unset($_SERVER['UNIQUE_DATA']);
    }
    if (is_array($_SERVER['UNIQUE_DATA'])) {
        echo '<FONT COLOR=RED><br><tt>[Error 1] Invalid license key. Please contact customer support.</tt></FONT>';
        @session_destroy();
        exit();
    }
    if ($_SERVER['UNIQUE_DATA']['_id'] < 30) {
        $grace_period = 604800;
        $grace_period2 = 2592000;
        $l_today_stamp = time();
        $time_left = $_SERVER['UNIQUE_DATA']['_expires'] - $l_today_stamp;
        $time_extended = $_SERVER['UNIQUE_DATA']['_expires'] + $grace_period2;
        $try_connect = $time_left < $grace_period || $_SERVER['UNIQUE_DATA']['_expires'] < $l_today_stamp && $l_today_stamp < $time_extended ? true : false;
        $force_connect = $op == 'renew_key' ? true : false;
        if ($try_connect || $force_connect) {
            register_session();
        }
        if ($_SERVER['UNIQUE_DATA']['_expires'] < time()) {
            if ($_SERVER['UNIQUE_DATA']['_id'] < 20) {
                echo "<table width=90% align=left><tr><td bgcolor=FFFFFF><FONT COLOR=RED>\n\t\t\t\t\t[Error 2-".$_SERVER['UNIQUE_DATA']['_id']."] Demo license is expired.\n\t\t\t\t\tPlease purchase a leased or owned license <a href=http://www.modernbill.com/download/index.htm>here</a> to continue using ModernBill.\n\t\t\t\t  </FONT></td></tr></table>";
                @session_destroy();
                exit();
            }
            echo "<table width=90% align=left><tr><td bgcolor=FFFFFF><FONT FACE=ARIAL COLOR=RED SIZE=2>\n                    [Error 2-".$_SERVER['UNIQUE_DATA']['_id'].('] This license failed to auto-renew and has expired. Please <a href='.$https . "://{$secure_url}") . "index.php?op=renew_key>click here</a> to auto-renew your license now.\n                    </FONT>";
            if (empty($failed_error)) {
                echo "<blockquote>\n                    <i><font color=blue>".$failed_error."</font></i>\n                    <br>\n                    <br>\n                    <FONT FACE=ARIAL COLOR=BLACK SIZE=2>\n\t\t\t\t\t<b>NOTICE:</b> Before contacting support, please verify the following:\n\t\t\t\t\t<ol>\n\t\t\t\t\t\t<li> Login to your <a href=http://www.modernsupport.com/mbleased/>Billing Account</a> and verify that the <b>status is active</b> and there is <b>no outstanding balance</b>.\n\t\t\t\t\t\t<li> Login to your <a href=http://www.modernsupport.com/modernbill/>Member's Area</a> and verify that your <b>account is registered</b> and you have a <b>license key on file</b> for this domain.\n                        <li> This installation has <b>internet access</b> and there is <b>no firewall blocking incoming port 80 connections</b> from our licensing server.\n                    </ol>\n\t\t\t\t\tOnce you have verified and/or corrected the issues listed above, please try to <a href={$https}://{$secure_url}"."index.php?op=renew_key>auto-renew</a> your license again.\n\t\t\t\t\t<br><br>\n\t\t\t\t\tIf you are still experiencing problems, please create a <a href=http://www.modernsupport.com/modernbill/index.php?type=ticket>priority support ticket</a>.\n                  </FONT>";
            }
            echo '</td></tr></table>';
            exit();
        }
    }
    if (30 <= $_SERVER['UNIQUE_DATA']['_id'] && $op == 'fetch_key') {
        register_session();
    }
    $secure_install_url = parse_url('http://'.strtolower($secure_url));
    $valid_domain = $secure_install_url['host'];
    $RegDomain = strtolower($_SERVER['UNIQUE_DATA']['_domain']);
    $valid_reg_domain = $RegDomain == $valid_domain ? true : false;
    switch ($_SERVER['UNIQUE_DATA']['_id']) {
        case 41:
            $valid_reg_domain = $RegDomain == $valid_domain || eregi($valid_domain, $RegDomain) ? true : false;
            break;
        default:
            $valid_reg_domain = $RegDomain == $valid_domain ? true : false;
    }
    if ($valid_reg_domain) {
        echo "<br><tt><font color=red>\n             [Error 61] The install domain <b>".$secure_install_url['host']."</b> does not match the registered domain <b>{$RegDomain}</b>!\n             <hr>\n             <br>\n             <br>\n             <b>Why do I see this error?</b>\n             <ol>\n             <li> You may have registered the wrong domain. <i>Please contact support for a new key file.</i>\n             <br>\n             <br>\n             ___ OR ___\n             <br>\n             <br>\n             <li> You have not configured the <b>\$standard_url</b> and <b>\$secure_url</b> variables properly here:<br>\n             <b>include/config/config.locale.php</b><br>\n             <br>\n             <b>Examples:</b><br>\n             <br>\n             <u>Your current settings are:</u><br>\n             <b>\$standard_url</b> = \"{$standard_url}\";<br>\n             <b>\$secure_url</b>   = \"{$standard_url}\";<br>\n             <b>\$https</b> = \"{$https}\";<br>\n             <br>\n             <u>Your settings <b>should</b> be:</u><br>\n             <b>\$standard_url</b> = \"{$RegDomain}{$REQUEST_URI}\";<br>\n             <b>\$secure_url</b> = \"{$RegDomain}{$REQUEST_URI}\";<br>\n             <b>\$https</b> = \"{$https}\";<br>\n             <br>\n             <i>Your actual settings may vary. Please contact support if you have questions.</i>\n             <br>\n             </font></tt>";
        @session_destroy();
        exit();
    }
    $_sql = 'SELECT count(*) as num FROM client_info';
    $client_count = $_TOTALS['client_count'] = adodb_one_data($_sql, 'N');
    if (0 < $MaxClients) {
        $remaining_clients = $MaxClients - $_TOTALS['client_count'];
        if (0 < $remaining_clients) {
            if ($remaining_clients < 10) {
                $low_warning = "<table border=1 cellpadding=2>\n                             <tr>\n                              <td bgcolor=FFFFFF align=center>\n                                <font color=red>\n                                Warning:<br>".$remaining_clients." clients remaining!\n                                <hr size=1>\n                                You will be locked out if<br>you exceed your client quota.<Br><a href=http://www.modernbill.com/download/index.htm>Upgrade Now</a>\n                                </font>\n                              </td>\n                             </tr>\n                           </table>";
            }
        } elseif ($this_admin) {
            echo '<br><tt><font color=red>NO MORE CLIENTS REMAINING. <a href=http://www.modernbill.com/download/index.htm>PLEASE UPGRADE YOUR LICENSE</a>.</font></tt>Once you have completed the steps above, you may <a href=index.php?op=renew_key>click here</a> to auto-renew your leased license key.';
            @session_destroy();
            exit();
        }
    }
    unset($_SERVER['UNIQUE_DATA']);
    require_once $DIR.'include/misc/heart/cc_functions.inc.php';
    require_once $DIR.'include/misc/heart/pw_functions.inc.php';
    require_once $DIR.'include/misc/heart/misc_functions.inc.php';
    require_once $DIR.'include/misc/heart/select_functions.inc.php';
    require_once $DIR.'include/misc/heart/sql_select_functions.inc.php';
    require_once $DIR.'include/misc/heart/email_functions.inc.php';
    require_once $DIR.'include/misc/heart/faq_functions.inc.php';
    require_once $DIR.'include/misc/heart/order_functions.inc.php';
    require_once $DIR.'include/misc/heart/display_functions.inc.php';
    require_once $DIR.'include/misc/heart/xml_functions.inc.php';
    require_once $DIR.'include/misc/heart/action_functions.inc.php';
    if (is_dir($DIR.'include/misc/mod_pdf') && file_exists($DIR.'include/misc/mod_pdf/fpdf.php')) {
        define(FPDF_FONTPATH, $DIR.'include/misc/mod_pdf/font/');
        require_once $DIR.'include/misc/mod_pdf/fpdf.php';
    }
    $_ALLOWABLE_REGISTRARS = [2 => 'mod_opensrs', 3 => 'mod_emailregister', 4 => 'mod_enom', 5 => 'mod_planetdomain', 6 => 'mod_godaddy', 7 => 'mod_directi', 9 => 'mod_nominet', 11 => 'mod_onlinenic', 12 => 'mod_srsplus', 14 => 'mod_registercom', 15 => 'mod_dnsbe', 16 => 'mod_sidn'];
    $mod_enabled = $base_module_enabled = $_dir = $server_api_enabled = false;
    $dh = opendir($DIR.'include/misc/');
    while ($_dir = readdir($dh)) {
        if (mb_ereg('mod_', $_dir) && file_exists($DIR . ('include/misc/'.$_dir.'/mod_config.php'))) {
            $var = 'this_'.$_dir.'_config';
            if (isset($$var)) {
                $_sql = "INSERT INTO config (config_type) VALUES ('".$_dir."')";
                @adodb_query($_sql);
            }
            require $DIR.('include/misc/'.$_dir.'/mod_config.php');
            $module_is_approved = true;
            if ($module_is_approved) {
                if (($mod_enabled || $base_module_enabled || $include_override) && file_exists($DIR.('include/misc/'.$_dir.'/mod_functions.inc.php'))) {
                    include_once $DIR.('include/misc/'.$_dir.'/mod_functions.inc.php');
                }
                if ($module_is_approved) {
                    $installed_modules[] = $_dir;
                }
            }
        }
        $mod_enabled = $base_module_enabled = $server_id = $registrar_id = $include_override = null;
    }
    closedir($dh);
    $enable_virtual_terminal = eregi('authorizenet', $use_gateway_api) || $_GATEWAYS[$use_gateway_api]['vt_enabled'] && $_GATEWAYS[$use_gateway_api]['mod_enabled'] ? true : false;
    $authnet_style_enabled = eregi('authorizenet', $use_gateway_api) ? true : false;
