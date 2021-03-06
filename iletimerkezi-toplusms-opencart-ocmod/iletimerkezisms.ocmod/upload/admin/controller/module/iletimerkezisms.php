<?php
class ControllerModuleIletimerkezisms extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('module/iletimerkezisms');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('iletimerkezisms', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->load->model('localisation/order_status');
		$data['order_statuses']  = $this->model_localisation_order_status->getOrderStatuses();
		$data['iletimerkezisms'] = $this->model_setting_setting->getSetting('iletimerkezisms');
		//die(var_export($data['iletimerkezisms'],1));

		if(!empty($data['iletimerkezisms']['iletimerkezisms_username'])) {
        	$iletimerkezisms_username = $data['iletimerkezisms']['iletimerkezisms_username'];
        } else {
        	
        	$iletimerkezisms_username = '';
		}

        if(!empty($data['iletimerkezisms']['iletimerkezisms_password'])) {
            $iletimerkezisms_password = $data['iletimerkezisms']['iletimerkezisms_password'];
        } else {
            $iletimerkezisms_password = '';
        }

        if(empty($iletimerkezisms_username)||empty($iletimerkezisms_password)) {
        	$data['balance'] = 'Sms göndermek ve bakiyenizin gözükebilmesi için üyelik bilgilerinizi giriniz.';
        } else {        	
        	$this->load->library('sms');
			$sms = new Sms();
			$res = $sms->getBalance($iletimerkezisms_username,$iletimerkezisms_password);
        	$data['balance'] = $res.' SMS <a target="_blank" href="https://www.iletimerkezi.com/index.php?function=default&obj1=signinViaGet&gsm='.$iletimerkezisms_username.'&password='.$iletimerkezisms_password.'">SMS Satın Al!</a>';
        }		

		$data['heading_title']       = $this->language->get('heading_title');		
		$data['text_enabled']        = $this->language->get('text_enabled');
		$data['text_disabled']       = $this->language->get('text_disabled');
		$data['text_content_top']    = $this->language->get('text_content_top');
		$data['text_content_bottom'] = $this->language->get('text_content_bottom');
		$data['text_column_left']    = $this->language->get('text_column_left');
		$data['text_column_right']   = $this->language->get('text_column_right');		
		$data['entry_banner']        = $this->language->get('entry_banner');
		$data['entry_dimension']     = $this->language->get('entry_dimension');
		$data['entry_layout']        = $this->language->get('entry_layout');
		$data['entry_position']      = $this->language->get('entry_position');
		$data['entry_status']        = $this->language->get('entry_status');
		$data['entry_sort_order']    = $this->language->get('entry_sort_order');		
		$data['button_save']         = $this->language->get('button_save');
		$data['button_cancel']       = $this->language->get('button_cancel');
		$data['button_add_module']   = $this->language->get('button_add_module');
		$data['button_remove']       = $this->language->get('button_remove');
		$data['text_edit'] 			 = $this->language->get('text_edit');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['dimension'])) {
			$data['error_dimension'] = $this->error['dimension'];
		} else {
			$data['error_dimension'] = array();
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/iletimerkezisms', 'token=' . $this->session->data['token'], 'SSL'),
   		);

		$data['action'] = $this->url->link('module/iletimerkezisms', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');


		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		$this->load->model('design/banner');

		$data['banners'] = $this->model_design_banner->getBanners();

		$this->template = 'module/iletimerkezisms.tpl';
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/iletimerkezisms.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/account')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
?>