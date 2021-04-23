<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Motor_jenis extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Motor_jenis_model');
	}
	function index(){
		$data['list_status_publish'] = selectlist2(array('table'=>'status_publish','title'=>'All Status','selected'=>$data['id_status_publish']));
		render('apps/motor_jenis/index',$data,'apps');
	}
	public function add($id=''){
		if($id){
			$data = $this->Motor_jenis_model->findById($id);
           
			if(!$data){
				die('404');
			}
			$data['judul']  = 'Edit';
			$data['proses'] = 'Update';
			$data['id']     = $id;
		}
		else{
			$data['last_edited']                  = '';
			$data['last_edited_show']             = 'invis';
			$data['judul']                        = 'Add';
			$data['proses']                       = 'Simpan';
			$data['teaser']                       = '';
			$data['id']     						= '';
			$data['name']                         = '';
		}

		$data['list_status_publish'] = selectlist2(array('table'=>'status_publish','title'=>'All Status','selected'=>$data['id_status_publish']));
		render('apps/motor_jenis/add',$data,'apps');
	}
	function records(){
		$data = $this->Motor_jenis_model->records();
		render('apps/motor_jenis/records',$data,'blank');
	}	
	
	function proses($idedit=''){
		$this->layout 			= 'none';
		$post 					= purify(null_empty($this->input->post()));
		$ins_id 				= '';

		$this->form_validation->set_rules('name', '"name"', 'required'); 
		$this->form_validation->set_rules('id_status_publish', '"status"', 'required');
		if ($this->form_validation->run() == FALSE){
			$ret['message']  = validation_errors(' ',' ');
		}
		else{   
			$this->db->trans_start();   
			if($idedit){
				auth_update();
				$ret['message'] = 'Update Success';
				$act			= "Update Motor Jenis";
				$this->Motor_jenis_model->update($post,$idedit);
			} else {
				auth_insert();
				$ret['message'] = 'Insert Success';
				$act            = "Insert Motor Jenis";
				$xDat           = $this->Motor_jenis_model->insert($post);
			}
			detail_log();
			insert_log($act);
			$this->db->trans_complete();
			$this->session->set_flashdata('message',$ret['message']);
			$ret['error'] = 0;
		}
		echo json_encode($ret);
	}
	function del(){
		auth_delete();
		$id = $this->input->post('iddel');
		$this->Motor_jenis_model->delete($id);
		detail_log();
		insert_log("Delete Motor Jenis");
	}
}

/* End of file Motor_jenis.php */
/* Location: ./application/controllers/apps/Motor_jenis.php */