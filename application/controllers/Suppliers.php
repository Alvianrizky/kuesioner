<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Suppliers extends CI_Controller {

   protected $page_header = 'Suppliers Management';

   public function __construct()
   {
      parent::__construct();


      $this->load->model('suppliers_model', 'suppliers');
      $this->load->library(array('ion_auth', 'form_validation', 'template'));
      $this->load->helper('bootstrap_helper');
   }

	public function index()
	{  
      
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $data['page_header']   = $this->page_header;
      $data['panel_heading'] = 'Supplier List';
      $data['page']         = '';

      $this->template->backend('suppliers_v', $data);
	}

   public function get_suppliers()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $list = $this->suppliers->get_datatables();
      $data = array();
      $no = isset($_POST['start']) ? $_POST['start'] : 0;
      foreach ($list as $field) { 
         $id = $field->SupplierID;

         $url_view   = 'view_data('.$id.');';
         $url_update = 'update_data('.$id.');';
         $url_delete = 'delete_data('.$id.');';

         $no++;
         $row = array();
         $row[] = ajax_button($url_view, $url_update, $url_delete);
         $row[] = $no;
         $row[] = $field->CompanyName;
         $row[] = $field->ContactName;
         $row[] = $field->ContactTitle;
         $row[] = $field->Address;
         $row[] = $field->City;
         $row[] = $field->Region;
         $row[] = $field->PostalCode;
         $row[] = $field->Country;
         $row[] = $field->Phone;
         $row[] = $field->Fax;
         $row[] = $field->HomePage;

         $data[] = $row;
      }
      
      $draw = isset($_POST['draw']) ? $_POST['draw'] : null;

      $output = array(
         "draw" => $draw,
         "recordsTotal" => $this->suppliers->count_rows(),
         "recordsFiltered" => $this->suppliers->count_filtered(),
         "data" => $data,
      );
      echo json_encode($output);
   }


   public function view()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $id = $this->input->post('SupplierID');

      $query = $this->suppliers->where('SupplierID', $id)->get();
      if($query){
         $data = array('SupplierID' => $query->SupplierID,
            'CompanyName' => $query->CompanyName,
            'ContactName' => $query->ContactName,
            'ContactTitle' => $query->ContactTitle,
            'Address'      => $query->Address,
            'City'         => $query->City,
            'Region'     => $query->Region,
            'PostalCode' => $query->PostalCode,
            'Country'    => $query->Country,
            'Phone'    => $query->Phone,
            'Fax'      => $query->Fax,
            'HomePage'  => $query->HomePage);
      }

      echo json_encode($data);
   }

   public function form_data()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $row = array();
      if($this->input->post('SupplierID')){
         $id      = $this->input->post('SupplierID');
         $query   = $this->suppliers->where('SupplierID', $id)->get(); 
         if($query){
            $row = array(
               'SupplierID'    => $query->SupplierID,
               'CompanyName'   => $query->CompanyName,
               'ContactName'   => $query->ContactName,
               'ContactTitle'  => $query->ContactTitle,
               'Address'       => $query->Address,
               'City'          => $query->City,
               'Region'        => $query->Region,
               'PostalCode'    => $query->PostalCode,
               'Country'       => $query->Country,
               'Phone'         => $query->Phone,
               'Fax'           => $query->Fax,
               'HomePage'      => $query->HomePage
            );
         }
         $row = (object) $row;
      }

      $data = array('hidden'=> form_hidden('SupplierID', !empty($row->SupplierID) ? $row->SupplierID : ''),
             'CompanyName' => form_input(array('name'=>'CompanyName', 'id'=>'CompanyName', 'class'=>'form-control', 'value'=>!empty($row->CompanyName) ? $row->CompanyName : '')),
             'ContactName' => form_input(array('name'=>'ContactName', 'id'=>'ContactName', 'class'=>'form-control', 'value'=>!empty($row->ContactName) ? $row->ContactName : '')),
             'ContactTitle' => form_input(array('name'=>'ContactTitle', 'id'=>'ContactTitle', 'class'=>'form-control', 'value'=>!empty($row->ContactTitle) ? $row->ContactTitle : '')),
             'Address' => form_input(array('name'=>'Address', 'id'=>'Address', 'class'=>'form-control', 'value'=>!empty($row->Address) ? $row->Address : '')),
             'City' => form_input(array('name'=>'City', 'id'=>'City', 'class'=>'form-control', 'value'=>!empty($row->City) ? $row->City : '')),
             'Region' => form_input(array('name'=>'Region', 'id'=>'Region', 'class'=>'form-control', 'value'=>!empty($row->Region) ? $row->Region : '')),
             'PostalCode' => form_input(array('name'=>'PostalCode', 'id'=>'PostalCode', 'class'=>'form-control', 'value'=>!empty($row->PostalCode) ? $row->PostalCode : '')),
             'Country' => form_input(array('name'=>'Country', 'id'=>'Country', 'class'=>'form-control', 'value'=>!empty($row->Country) ? $row->Country : '')),
             'Phone' => form_input(array('name'=>'Phone', 'id'=>'Phone', 'class'=>'form-control', 'value'=>!empty($row->Phone) ? $row->Phone : '')),
             'Fax' => form_input(array('name'=>'Fax', 'id'=>'Fax', 'class'=>'form-control', 'value'=>!empty($row->Fax) ? $row->Fax : '')),
             'HomePage' => form_input(array('name'=>'HomePage', 'id'=>'HomePage', 'class'=>'form-control', 'value'=>!empty($row->HomePage) ? $row->HomePage : ''))
            );

      echo json_encode($data);
   }


   public function save_shipper()
   {   
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $rules = array(
            'insert' => array(                     
                     array('field' => 'SupplierID', 'label' => 'Supplier ID', 'rules' => 'required|is_unique[suppliers.SupplierID]|max_length[5]'),
                     array('field' => 'CompanyName', 'label' => 'Company Name', 'rules' => 'trim|callback_alpha_dash_space|required|max_length[40]'),                      
                     array('field' => 'ContactTitle', 'label' => 'Contact Title', 'rules' => 'callback_alpha_dash_space|max_length[30]'),
                     array('field' => 'Address', 'label' => 'Address', 'rules' => 'max_length[60]'),
                     array('field' => 'City', 'label' => 'City', 'rules' => 'max_length[15]'),
                     array('field' => 'Region', 'label' => 'Region', 'rules' => 'callback_alpha_dash_space|max_length[15]'),
                     array('field' => 'Country', 'label' => 'Country', 'rules' => 'max_length[15]'),
                     array('field' => 'PostalCode', 'label' => 'PostalCode', 'rules' => 'max_length[10]'),
                     array('field' => 'Phone', 'label' => 'Phone', 'rules' => 'max_length[24]'),
                     array('field' => 'Fax', 'label' => 'Fax', 'rules' => 'max_length[24]')
                     ),
            'update' => array(
                     array('field' => 'SupplierID', 'label' => 'Supplier ID', 'rules' => 'required|max_length[5]'),
                     array('field' => 'CompanyName', 'label' => 'Company Name', 'rules' => 'trim|callback_alpha_dash_space|required|max_length[40]'),                      
                     array('field' => 'ContactTitle', 'label' => 'Contact Title', 'rules' => 'callback_alpha_dash_space|max_length[30]'),
                     array('field' => 'Address', 'label' => 'Address', 'rules' => 'max_length[60]'),
                     array('field' => 'City', 'label' => 'City', 'rules' => 'max_length[15]'),
                     array('field' => 'Region', 'label' => 'Region', 'rules' => 'callback_alpha_space|max_length[15]'),
                     array('field' => 'Country', 'label' => 'Country', 'rules' => 'max_length[15]'),
                     array('field' => 'PostalCode', 'label' => 'PostalCode', 'rules' => 'max_length[10]'),
                     array('field' => 'Phone', 'label' => 'Phone', 'rules' => 'max_length[24]'),
                     array('field' => 'Fax', 'label' => 'Fax', 'rules' => 'max_length[24]')
                     )                  
            );
        
      $row = array('CompanyName' => $this->input->post('CompanyName'),
            'ContactName' => $this->input->post('ContactName'),
            'ContactTitle' => $this->input->post('ContactTitle'),
            'Address'      => $this->input->post('Address'),
            'City'         => $this->input->post('City'),
            'Region'     => $this->input->post('Region'),
            'PostalCode' => $this->input->post('PostalCode'),
            'Country'    => $this->input->post('Country'),
            'Phone'    => $this->input->post('Phone'),
            'Fax'      => $this->input->post('Fax'),
            'HomePage' => $this->input->post('HomePage'));

      $code = 0;

      if($this->input->post('SupplierID') == null){

         $this->form_validation->set_rules($rules['insert']);

         if ($this->form_validation->run() == true) {
            
            $this->suppliers->insert($row);

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

            $id = $this->input->post('SupplierID');

            $this->suppliers->where('SupplierID', $id)->update($row);
            
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

      $id = $this->input->post('SupplierID');

      $this->suppliers->where('SupplierID', $id)->delete();

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

   public function alpha_dash_space($str)
   {
      if($str != null){
         if (! preg_match('/^[a-zA-Z\s]+$/', $str)) {
            $this->form_validation->set_message('alpha_dash_space', 'The %s field may only contain alpha characters & White spaces');
            return FALSE;
         }  
         else {
           return TRUE;
         }
      }
   }

   public function alpha_space($str) 
   {
      if($str != null){
         if (preg_match('/^[a-zA-Z\s]+$/', $str) !== 0) {
            return true;
         } else {
            $this->form_validation->set_message('alpha_space', '%s is not valid.');
            return false;
         }
      }
      return true;
   }

}
