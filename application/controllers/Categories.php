<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends CI_Controller {

   private $page_header = 'Categories Management';

   public function __construct()
   {
      parent::__construct();


      $this->load->model('categories_model', 'categories');
      $this->load->library(array('ion_auth', 'form_validation', 'template', 'table'));
      $this->load->helper('bootstrap_helper');
   }

	public function index()
	{  
      
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }
      /*
      $template = array(
         'table_open' => '<table id="table" class="table table-striped table-bordered table-hover">'
      );
      $this->table->set_template($template);
      
      $this->table->set_heading('Action', 'No', 'CategoryName', 'Description');

      $data['table'] = $this->table->generate();
      */
      $data['page_header']   = $this->page_header;
      $data['panel_heading'] = 'Categories List';
      $data['page']         = '';

      $this->template->backend('categories_v', $data);
	}

   public function get_categories()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $list = $this->categories->get_datatables();
      $data = array();
      $no = isset($_POST['start']) ? $_POST['start'] : 0;
      foreach ($list as $field) { 
         $id = $field->CategoryID;

         $url_view   = 'view_data('.$id.');';
         $url_update = 'update_data('.$id.');';
         $url_delete = 'delete_data('.$id.');';

         $no++;
         $row = array();
         $row[] = ajax_button($url_view, $url_update, $url_delete);
         $row[] = $no;
         $row[] = $field->CategoryName;
         $row[] = $field->Description;

         $data[] = $row;
      }
      
      $draw = isset($_POST['draw']) ? $_POST['draw'] : null;

      $output = array(
         "draw" => $draw,
         "recordsTotal" => $this->categories->count_rows(),
         "recordsFiltered" => $this->categories->count_filtered(),
         "data" => $data,
      );
      echo json_encode($output);
   }


   public function view()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $id = $this->input->post('CategoryID');

      $query = $this->categories->where('CategoryID', $id)->get();
      if($query){
         
         $img = 'No Picture';
         if(!empty($query->Picture)){
            $thumb_name = $this->get_thumb_name($query->Picture);
            $img = img(array('src' => base_url('assets/uploads/' . $thumb_name), 'alt'=>$query->CategoryName, 'title'=>$query->CategoryName, 'width'=>'200px', 'class'=>'img-fluid img-thumbnail'));
         }

         $data = array('CategoryID' => $query->CategoryID,
             'CategoryName' => $query->CategoryName,
             'Description' => $query->Description,
             'Picture' => $img);
      }

      echo json_encode($data);
   }

   public function form_data()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $row = array();
      if($this->input->post('CategoryID')){
         $id      = $this->input->post('CategoryID');
         $query   = $this->categories->where('CategoryID', $id)->get(); 
         if($query){
            $row = array('CategoryID' => $query->CategoryID,
             'CategoryName' => $query->CategoryName,
             'Description' => $query->Description,
             'Picture' => $query->Picture);
         }
         $row = (object) $row;
      }

      $img = 'No Picture';
      if(!empty($row->Picture)){
         $thumb_name = $this->get_thumb_name($row->Picture);
         $img = img(array('src' => base_url('assets/uploads/' . $thumb_name), 'alt'=>$row->CategoryName, 'title'=>$row->CategoryName, 'width'=>'200px', 'class'=>'img-fluid img-thumbnail'));
      }

      $data = array('hidden'=> form_hidden('CategoryID', !empty($row->CategoryID) ? $row->CategoryID : ''),
                  'CategoryName' => form_input(array('name'=>'CategoryName', 'id'=>'CategoryName', 'class'=>'form-control', 'value'=>!empty($row->CategoryName) ? $row->CategoryName : '')),
                  'Description' => form_input(array('name'=>'Description', 'id'=>'Description', 'class'=>'form-control', 'value'=>!empty($row->Description) ? $row->Description : '')),
                  'Picture'  => form_upload(array('name' => 'userfile', 'id' => 'userfile', 'accept'=>'image/*')). br(1) . $img
               );

      echo json_encode($data);
   }


   public function save_category()
   {   
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $rules = array(
            'insert' => array(array('field' => 'CategoryName', 'label' => 'Category Name', 'rules' => 'trim|is_unique[categories.CategoryName]|required|max_length[15]'),
                        array('field' => 'Description', 'label' => 'Description', 'rules' => 'trim|max_length[255]')),
            'update' => array(array('field' => 'CategoryName', 'label' => 'Category Name', 'rules' => 'trim|required|max_length[15]'), 
                        array('field' => 'Description', 'label' => 'Description', 'rules' => 'trim|max_length[255]'))                  
            );
        
      $row  = array('CategoryName' => $this->input->post('CategoryName'),
             'Description' => $this->input->post('Description'));

      $code = 0;

      if($this->input->post('CategoryID') == null){

         $this->form_validation->set_rules($rules['insert']);

         if ($this->form_validation->run() == true) {
            
            if (!empty($_FILES['userfile']['tmp_name'])) {
               
               $upload_data = $this->moo_upload();
               
               if(!empty($upload_data['error'])){
                  $code = 1;
                  $notifications = notifications('error', $upload_data['error']);

                  echo json_encode(array('message' => $notifications, 'code' => $code));
                  exit;
               }
               else{
                  $row['Picture'] = $upload_data['filename'];
               }
            }

            $this->categories->insert($row);

            $error =  $this->db->error();
            if($error['code'] <> 0){
               $code = 1;
               $notifications = $error['code'].' : '.$error['message'];
            }
            else{
               $notifications = 'Success Insert Data';
            }
         }
         else{
            $code = 1;
            $notifications = validation_errors('<p>', '</p>'); 
         }

      }

      else{
         
         $this->form_validation->set_rules($rules['update']);

         if ($this->form_validation->run() == true) {

            $id = $this->input->post('CategoryID');

            if (!empty($_FILES['userfile']['tmp_name'])) {              

               $upload_data = $this->moo_upload();

               if(!empty($upload_data['error'])){
                  $code = 1;
                  $notifications = notifications('error', $upload_data['error']);

                  echo json_encode(array('message' => $notifications, 'code' => $code));
                  exit;
               }
               else{
                  $query = $this->categories->fields('Picture')->where('CategoryID', $id)->get();
                  if (!empty($query->Picture)) {
                     $this->delete_img($query->Picture);
                  }

                  $row['Picture'] = $upload_data['filename'];
               }
            }

            $this->categories->where('CategoryID', $id)->update($row);
            
            $error =  $this->db->error();
            if($error['code'] <> 0){               
               $code = 1;               
               $notifications = $error['code'].' : '.$error['message'];
            }
            else{               
               $notifications = 'Success Update Data';
            }
         }
         else{
            $code = 1;
            $notifications = validation_errors('<p>', '</p>'); 
         }
      }

      $notifications = ($code == 0) ? notifications('success', $notifications) : notifications('error', $notifications);
      
      echo json_encode(array('message' => $notifications, 'code' => $code));
   }

   public function delete()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $code = 0;

      $id = $this->input->post('CategoryID');

      $this->delete_img($id);

      $this->categories->where('CategoryID', $id)->delete();

      $error =  $this->db->error();
      if($error['code'] <> 0){
         $code = 1;
         $notifications = $error['code'].' : '.$error['message'];
      }
      else{
         $notifications = 'Success Delete Data';
      }

      $notifications = ($code == 0) ? notifications('success', $notifications) : notifications('error', $notifications);
      
      echo json_encode(array('message' => $notifications, 'code' => $code));
   }

   private function get_thumb_name($img_name)
   {
     $parts = explode('.', $img_name);
     return (count($parts) == 2) ? $parts[0] . '_thumb.' . $parts[1] : $img_name;
   }

   private function delete_img($img_name)
   {
      $upload_path = FCPATH . 'assets/uploads/';   
      
      $tumb_name = $this->get_thumb_name($img_name);
      
      if (file_exists($upload_path . $img_name)) {
         @unlink($upload_path . $img_name);
         @unlink($upload_path . $tumb_name);

         return true;
      }

      return false;
   }

   public function moo_upload()
   {
      $upload_path = FCPATH . 'assets/uploads/';

      $rand = rand(1,1000);

      $filename = $_FILES['userfile']['name'];
      $tmp_name = $_FILES['userfile']['tmp_name'];

      $thumb_name  = $rand . $this->get_thumb_name($filename);
      $filename    = $rand . $filename;

      list($width, $height) = getimagesize($tmp_name);  
      
      $widthx  = 0.25 * $width;
      $heightx = ($widthx / $width) * $height;
      
      $this->load->library('image_moo');

      $this->image_moo
            ->load($tmp_name)
            ->save($upload_path . $filename)
            ->resize_crop($widthx, $heightx)
            ->save($upload_path . $thumb_name); 
      
   
      if($this->image_moo->errors){
         return array('error' => $this->image_moo->display_errors());
      }
      else{
         return array('filename' => $filename, 'thumb_name' => $thumb_name);
      }           
    }

}
