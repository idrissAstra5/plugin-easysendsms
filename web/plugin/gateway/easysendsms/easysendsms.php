<?php

/**
 * This file is part of playSMS.
 *
 * playSMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * playSMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with playSMS. If not, see <http://www.gnu.org/licenses/>.
 */
defined('_SECURE_') or die('Forbidden');

if (!auth_isadmin()) {
	auth_block();
}

include $core_config['apps_path']['plug'] . "/gateway/easysendsms/config.php";

switch (_OP_) {
	case "manage":
		$tpl = array(
			'name' => 'easysendsms',
			'vars' => array(
				'DIALOG_DISPLAY' => _dialog(),
				'Manage easysendsms' => _('Manage easysendsms'),
				'Gateway name' => _('Gateway name'),
				/* 'Easy Send SMS URL' => _mandatory(_('Easy Send SMS URL')), */
				'User' => _('User'),
				'Password' => _('Password'),
				'Module sender ID' => _('Module sender ID'),
				'Module timezone' => _('Module timezone'),
				'Save' => _('Save'),
				'Notes' => _('Notes'),
				'Your callback URL is' => _('Your callback URL is'),
				'CALLBACK_URL' => _HTTP_PATH_PLUG_ . '/gateway/easysendsms/callback.php',
				'HINT_FILL_PASSWORD' => _hint(_('Fill to change the Password')),
				'HINT_MODULE_SENDER' => _hint(_('Max. 16 numeric or 11 alphanumeric char. empty to disable')),
				'HINT_TIMEZONE' => _hint(_('Eg: +0700 for Jakarta/Bangkok timezone')),
				'BUTTON_BACK' => _back('index.php?app=main&inc=core_gateway&op=gateway_list'),
				'status_active' => $status_active,
				/* 'easysendsms_param_url' => $plugin_config['easysendsms']['url'], */
				'easysendsms_param_user' => $plugin_config['easysendsms']['user'],
				'easysendsms_param_module_sender' => $plugin_config['easysendsms']['module_sender'],
				'easysendsms_param_datetime_timezone' => $plugin_config['easysendsms']['datetime_timezone'] 
			) 
		);
		_p(tpl_apply($tpl));
		break;
	
	case "manage_save":
		//$up_url = ($_REQUEST['up_url'] ? $_REQUEST['up_url'] : $plugin_config['easysendsms']['default_url']);
		$up_url = $plugin_config['easysendsms']['url'];
		$up_user = $_REQUEST['up_user'];
		$up_password = $_REQUEST['up_password'];
		$up_module_sender = $_REQUEST['up_module_sender'];
		$up_datetime_timezone = $_REQUEST['up_datetime_timezone'];
		if ($up_url) {
			$items = array(
				'url' => $up_url,
				'user' => $up_user,
				'module_sender' => $up_module_sender,
				'datetime_timezone' => $up_datetime_timezone 
			);
			if ($up_password) {
				$items['password'] = $up_password;
			}
			if (registry_update(0, 'gateway', 'easysendsms', $items)) {
				$_SESSION['dialog']['info'][] = _('Gateway module configurations has been saved');
			} else {
				$_SESSION['dialog']['danger'][] = _('Fail to save gateway module configurations');
			}
		} else {
			$_SESSION['dialog']['danger'][] = _('All mandatory fields must be filled');
		}
		header("Location: " . _u('index.php?app=main&inc=gateway_easysendsms&op=manage'));
		exit();
		break;
}
