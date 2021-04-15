<?php

/*
 * OpenCart Color/Size Attribute Variant Products Switcher extension.
 *
 * Copyright (c) 2021 Alex AU <alex.au@live.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * 
 */

// admin/controller/extension/module/avpscs.php

class ControllerExtensionModuleAvpscs extends Controller {
	
	//--- All form error ---
	private $error = array();
	//--- end All form error ---
	
	private $version = '1.0.0';
	
	private function log($msg,$forceLog=false) {
		if (($this->config->get('module_avpscs_log')) || ($forceLog)) {
			$log = new Log('avpscs.log');
			$log->write($msg);
		}
	}

	//--- Register events ---
	public function install() {
		$this->log('AVPSCS ' . $this->version . ' Extension install ', true);
		$this->log('Opencart version = ' . VERSION, true);

		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('module_avpscs',[
				'module_avpscs_status' 	=> 1 	//--- enable by default ---
			, 'module_avpscs_log' 		=> 0  //--- disable by default ---
		]); 

		if (VERSION<3) { 
			$this->load->model('extension/event');
			$this->model_extension_event->addEvent('avpscs','catalog/view/*/product/product/after','extension/module/avpscs/edit_product_page');
			$this->model_extension_event->addEvent('avpscs_hidefromdesign','admin/view/*/design/layout_form/before','extension/module/avpscs/hideFromDesignLayoutForm');
		}
		elseif (VERSION<4)	{
			$this->load->model('setting/event');
			$this->model_setting_event->addEvent('avpscs','catalog/view/product/product/after','extension/module/avpscs/edit_product_page');
			$this->model_setting_event->addEvent('avpscs_hidefromdesign','admin/view/design/layout_form/before','extension/module/avpscs/hideFromDesignLayoutForm');
		}
	}
	//--- end Register events ---

	//--- Unregister events ---
	public function uninstall() {
		$this->log('AVPSCS ' . $this->version . ' Extension uninstall ', true);
		if (VERSION<3) {
			$this->load->model('extension/event');
			$this->model_extension_event->deleteEvent('avpscs');
			$this->model_extension_event->deleteEvent('avpscs_hidefromdesign');
		}
		elseif (VERSION<4) {
			$this->load->model('setting/event');
			$this->model_setting_event->deleteEventByCode('avpscs');
			$this->model_setting_event->deleteEventByCode('avpscs_hidefromdesign');
		}
	}
	//--- end Unregister events ---

	/**
	 * Hide module from the list on Layouts page in admin panel
	 * https://forum.opencart.com/viewtopic.php?p=799279#p799279
	 */
	public function hideFromDesignLayoutForm(&$route, &$data, &$template=null) {
		foreach ($data['extensions'] as $key=>$extension) {
			if ($extension['code'] == 'avpscs') {
				unset($data['extensions'][$key]);
			}
		}
		return null;
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/avpscs')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->request->post['module_avpscs_agcolor']) {
			$this->error['module_avpscs_agcolor'] = $this->language->get('error_attribute_group_color');
		}
		if (!$this->request->post['module_avpscs_agsize']) {
			$this->error['module_avpscs_agsize'] = $this->language->get('error_attribute_group_size');
		}
		return !$this->error;
	}

	public function index() { //--- controller first run ----
		
		//--- Default Load ---
		$this->load->language('extension/module/avpscs'); //--- Load language ---
		$this->document->setTitle($this->language->get('heading_title')); //--- Set Module Title Name ----
		$this->load->model('setting/setting'); //--- Load default setting model ---
		//--- End Default Load ---

		//--- Extra Load ---
		$this->load->model('catalog/attribute_group');
		$data['attribute_groups'] = $this->model_catalog_attribute_group->getAttributeGroups();
		//--- End Extra Load ---

		//--- Submit form and checking part ---
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_avpscs', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success'); //--- Session Success message ---
			//--- Redirect extension page ---
			if ((VERSION>=2) && (VERSION<3)) {
				$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
			}
			if ((VERSION>=3) && (VERSION<4)) {
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
			}
		}
		//--- End Submit form and Checking part ---
		
		//--- Assign the language data for parsing it to view ----
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_none'] = $this->language->get('text_none');

		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_log'] = $this->language->get('entry_log');
		$data['entry_attribute_group_color'] = $this->language->get('entry_attribute_group_color');
		$data['entry_attribute_group_size'] = $this->language->get('entry_attribute_group_size');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		//--- End Assign the language data for parsing it to view ----

		//--- assign error part ---
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		if (isset($this->error['module_avpscs_agcolor'])) {
			$data['error_attribute_group_color'] = $this->language->get('error_attribute_group_color');
		} else {
			$data['error_attribute_group_color'] = '';
		}
		if (isset($this->error['module_avpscs_agsize'])) {
			$data['error_attribute_group_size'] = $this->language->get('error_attribute_group_size');
		} else {
			$data['error_attribute_group_size'] = '';
		}
		//--- end assign error part ---
		
		//--- assign breadcrumbs part into view ---
		$data['breadcrumbs'] = array();
		if (VERSION<3) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'), // come from /language/en-gb/en-gb.php
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_extension'), // come from /language/en-gb/en-gb.php
				'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
			);
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'), 
				'href' => $this->url->link('extension/module/avpscs', 'token=' . $this->session->data['token'], true)
			);
		}
		elseif (VERSION<4) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'), // come from /language/en-gb/en-gb.php
				'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
			);
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_extension'), // come from /language/en-gb/en-gb.php
				'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
			);
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'), 
				'href' => $this->url->link('extension/module/avpscs', 'user_token=' . $this->session->data['user_token'], true)
			);
		}
		//--- End assign breadcrumbs part into view ---
		
		//--- assign actions ---
		if (VERSION<3) {
			//--- assign form submit action path ---
			$data['action'] = $this->url->link('extension/module/avpscs', 'token=' . $this->session->data['token'], true);
			//--- assign form cancel action path ---
			$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);
		}
		elseif (VERSION<4) {
			//--- assign form submit action path ---
			$data['action'] = $this->url->link('extension/module/avpscs', 'user_token=' . $this->session->data['user_token'], true);
			//--- assign form cancel action path ---
			$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		}
		//--- end assign actions ---

		//--- Set data ---
		if (isset($this->request->post['module_avpscs_status'])) {
			$data['module_avpscs_status'] = $this->request->post['module_avpscs_status'];
		} else {
			$data['module_avpscs_status'] = $this->config->get('module_avpscs_status');
		}

		if (isset($this->request->post['module_avpscs_log'])) {
			$data['module_avpscs_log'] = $this->request->post['module_avpscs_log'];
		} else {
			$data['module_avpscs_log'] = $this->config->get('module_avpscs_log');
		}

		if (isset($this->request->post['module_avpscs_sort_order'])) {
			$data['module_avpscs_sort_order'] = $this->request->post['module_avpscs_sort_order'];
		} else {
			$data['module_avpscs_sort_order'] = $this->config->get('module_avpscs_sort_order');
		}
		
		if (isset($this->request->post['module_avpscs_agcolor'])) {
			$data['module_avpscs_agcolor'] = $this->request->post['module_avpscs_agcolor'];
		} else {
			$data['module_avpscs_agcolor'] = $this->config->get('module_avpscs_agcolor');
		}
		
		if (isset($this->request->post['module_avpscs_agsize'])) {
			$data['module_avpscs_agsize'] = $this->request->post['module_avpscs_agsize'];
		} else {
			$data['module_avpscs_agsize'] = $this->config->get('module_avpscs_agsize');
		}
		//--- End Set data ---
		
		//--- Assign admin common header, left menu, footer to view ---
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		//--- end Assign admin common header, left menu, footer to view ---

		$this->response->setOutput($this->load->view('extension/module/avpscs', $data));
	}

}