<?php

class Kecamatan extends CI_Controller {

	function __construct()
	{
		parent::__construct();
        $this->load->model('Kabupaten_model');
        $this->load->model('Kecamatan_model');
	}

	function index()
	{
        $data['list_status_publish'] = selectlist2(array('table'=>'status_publish','title'=>'All Status','selected'=>$data['id_status_publish']));

        render('apps/kecamatan/index', $data, 'apps');
	}

    function records()
    {
        $data = $this->Kecamatan_model->records();

        render('apps/kecamatan/records', $data, 'blank');
    }

    function add($id='')
    {
        if ($id)
        {
            $data = $this->Kecamatan_model->findById($id);

            if (!$data)
            {
                die('404');
            }

            $data           = quote_form($data);
            $data['judul']  = 'Edit';
            $data['proses'] = 'Update';
        }

        else
        {
            $data['judul'] = 'Add';
            $data['proses'] = 'Savesssss';
            $data['kd_kec'] = '';
            $data['kode_kecamatan'] = '';
            $data['kecamatan'] = '';
            $data['id_kecamatan'] = '';
            $data['kode_kabupaten'] = '';
            $data['kd_kab'] = '';

            $data['status_publish']     = '';
            // $data['create_date']        = date("m/d/Y g:i A", strtotime(date('h:i')));
            $data['modify_date']        = date("m/d/Y g:i A", strtotime(date('h:i')));
            $data['user_id_create']     = '';
            $data['user_id_modify']     = '';
        }

        $img_thumb                      = image($data['img'],'small');
        $imagemanager                   = imagemanager('img',$img_thumb,277,150);
        $data['img']                    = $imagemanager['browse'];
        $data['imagemanager_config']    = $imagemanager['config'];


        $data['list_status_publish'] = selectlist2(array('table'=>'status_publish','title'=>'All Status','selected'=>$data['status_publish']));

        $data['list_provinsi'] = selectlist2(
            array(
            'table'=>'ref_provinsi',
            'id' => 'kode_provinsi',
            'name' => 'provinsi',
            'title'=>'Pilih provinsi',
            'selected'=>$data['provinsi'],
            'provinsi'=>'provinsi',
            'where'=> 'is_delete=0')
        );

        $data['list_kabupaten'] = selectlist2(
            array(
            'table'=>'ref_kabupaten',
            'id' => 'kode_kabupaten',
            'name' => 'kabupatenkota',
            'title'=>'Pilih kabupaten',
            'selected'=>$data['kode_kabupaten'],
            'kabupaten'=>'kabupaten',
            'where'=> 'is_delete=0')
        );

        render('apps/kecamatan/add', $data, 'apps');
    }

    function proses($idedit='')
    {
        $this->layout   = 'none';
        $post           = purify($this->input->post());
        $ret['error']   = 1;

        $this->form_validation->set_rules('kd_kec', '"kd_kec"', 'required');
        $this->form_validation->set_rules('kode_kecamatan', '"Kode Kecamatan"', 'required');
        $this->form_validation->set_rules('kecamatan', '"Kecamatan"', 'required');
        $this->form_validation->set_rules('kode_kabupaten', '"Kode kabupaten"', 'required');


        if ($this->form_validation->run() == FALSE)
        {
            $ret['message']  = validation_errors(' ',' ');
        }
        else
        {
            $kabupaten   = $this->Kabupaten_model->findBy(array('kode_kabupaten'=>$post['kode_kabupaten']),1);
            $this->db->trans_start();
            if ($idedit)
            {
                auth_update();
                $ret['message'] = 'Update Success';
                $act            = "Update News";
                $post['kd_kab'] = $kabupaten['kd_kab'];
                $idedit         = $this->Kecamatan_model->update($post, $idedit);
            }
            else
            {
                auth_insert();
                $ret['message'] = 'Insert Success';
                $act            = "Insert News";
                $post['kd_kab'] = $kabupaten['kd_kab'];
                $post['kode_kabupaten'] = $kabupaten['kode_kabupaten'];
                $idedit         = $this->Kecamatan_model->insert($post);

            }

            $ret['error']   = 0;
            $this->db->trans_complete();
        }

        echo json_encode($ret);
    }

    function del()
    {
        auth_delete();
        $id     = $this->input->post('iddel');
        $data   = $this->Kecamatan_model->delete($id);
        detail_log();
        insert_log("Delete Pages");
    }

}
