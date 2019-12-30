<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

   protected $page_header = 'Products Management';

   public function __construct()
   {
      parent::__construct();


      $this->load->model(array('Categories_model'=>'categories', 'Suppliers_model'=>'suppliers', 'Products_model'=>'products'));
      $this->load->library(array('ion_auth', 'form_validation', 'template'));
      $this->load->helper('bootstrap_helper');
   }

	public function index()
	{  
      
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $data['page_header']   = $this->page_header;
      $data['panel_heading'] = 'Product List';
      $data['page']         = '';

      $this->template->backend('products_v', $data);
	}

   public function get_products()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $list = $this->products->get_datatables();
      $data = array();
      $no = isset($_POST['start']) ? $_POST['start'] : 0;
      foreach ($list as $field) { 
         $id = $field->ProductID;

         $url_view   = 'view_data('.$id.');';
         $url_update = 'update_data('.$id.');';
         $url_delete = 'delete_data('.$id.');';

         $no++;
         $row = array();
         $row[] = ajax_button($url_view, $url_update, $url_delete);
         $row[] = $no;
         $row[] = $field->ProductName;
         $row[] = $field->CompanyName;
         $row[] = $field->CategoryName;
         $row[] = $field->QuantityPerUnit;
         $row[] = $field->UnitPrice;
         $row[] = $field->UnitsInStock;
         $row[] = $field->UnitsOnOrder;
         $row[] = $field->ReorderLevel;
         $row[] = $field->Discontinued;

         $data[] = $row;
      }
      
      $draw = isset($_POST['draw']) ? $_POST['draw'] : null;

      $output = array(
         "draw" => $draw,
         "recordsTotal" => $this->products->count_rows(),
         "recordsFiltered" => $this->products->count_filtered(),
         "data" => $data,
      );
      echo json_encode($output);
   }


   public function view()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $id = $this->input->post('ProductID');

     $query = $this->products
         ->with_categories('fields:CategoryName')
         ->with_suppliers('fields:CompanyName')
         ->where('ProductID', $id)
         ->get();

      $data = array();
      if($query){
         $data = array('ProductID' => $query->ProductID,
            'ProductName' => $query->ProductName,
            'SupplierID' => $query->suppliers->CompanyName,
            'CategoryID' => $query->categories->CategoryName,
            'QuantityPerUnit'      => $query->QuantityPerUnit,
            'UnitPrice'         => $query->UnitPrice,
            'UnitsInStock'     => $query->UnitsInStock,
            'UnitsOnOrder' => $query->UnitsOnOrder,
            'ReorderLevel'    => $query->ReorderLevel,
            'Discontinued'    => $query->Discontinued
         );
      }

      echo json_encode($data);
   }

   public function form_data()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $opt_supplier     = $this->suppliers->as_dropdown('CompanyName')->get_all();
      $opt_category     = $this->categories->as_dropdown('CategoryName')->get_all();

      $row = array();
      if($this->input->post('ProductID')){
         $id      = $this->input->post('ProductID');
         $query   = $this->products->where('ProductID', $id)->get(); 
         if($query){
            $row = array(
            'ProductID'       => $query->ProductID,
            'ProductName'     => $query->ProductName,
            'SupplierID'      => $query->SupplierID,
            'CategoryID'      => $query->CategoryID,
            'QuantityPerUnit' => $query->QuantityPerUnit,
            'UnitPrice'       => $query->UnitPrice,
            'UnitsInStock'    => $query->UnitsInStock,
            'UnitsOnOrder'    => $query->UnitsOnOrder,
            'ReorderLevel'    => $query->ReorderLevel,
            'Discontinued'    => $query->Discontinued
            );
         }
         $row = (object) $row;
      }

      $data = array('hidden'=> form_hidden('ProductID', !empty($row->ProductID) ? $row->ProductID : ''),
             'ProductName' => form_input(array('name'=>'ProductName', 'id'=>'datepicker', 'class'=>'form-control', 'value'=>!empty($row->ProductName) ? $row->ProductName : '')),
             'SupplierID' => form_dropdown('SupplierID', $opt_supplier, !empty($row->SupplierID) ? $row->SupplierID : '', 'class="chosen-select"'),
             'CategoryID' => form_dropdown('CategoryID', $opt_category, !empty($row->CategoryID) ? $row->CategoryID : '', 'class="chosen-select"'),
             'QuantityPerUnit' => form_input(array('name'=>'QuantityPerUnit', 'id'=>'QuantityPerUnit', 'class'=>'form-control', 'value'=>!empty($row->QuantityPerUnit) ? $row->QuantityPerUnit : '')),
             'UnitPrice' => form_input(array('name'=>'UnitPrice', 'id'=>'UnitPrice', 'class'=>'form-control', 'value'=>!empty($row->UnitPrice) ? $row->UnitPrice : '')),
             'UnitsInStock' => form_input(array('name'=>'UnitsInStock', 'id'=>'UnitsInStock', 'class'=>'form-control', 'value'=>!empty($row->UnitsInStock) ? $row->UnitsInStock : '')),
             'UnitsOnOrder' => form_input(array('name'=>'UnitsOnOrder', 'id'=>'UnitsOnOrder', 'class'=>'form-control', 'value'=>!empty($row->UnitsInOrder) ? $row->UnitsInOrder : '')),
             'ReorderLevel' => form_input(array('name'=>'ReorderLevel', 'id'=>'ReorderLevel', 'class'=>'form-control', 'value'=>!empty($row->ReorderLevel) ? $row->ReorderLevel : '')),
             'Discontinued' => form_input(array('name'=>'Discontinued', 'id'=>'Discontinued', 'class'=>'form-control', 'value'=>!empty($row->Discontinued) ? $row->Discontinued : ''))
            );

      echo json_encode($data);
   }


   public function save_product()
   {   
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $rules = array(
            'insert' => array(                     
                     array('field' => 'ProductName', 'label' => 'Company Name', 'rules' => 'trim|required|max_length[40]'), 
                     array('field' => 'SupplierID', 'label' => 'Supplier ID', 'rules' => 'integer|max_length[11]'),                     
                     array('field' => 'CategoryID', 'label' => 'CategoryID', 'rules' => 'integer|max_length[11]'),
                     array('field' => 'QuantityPerUnit', 'label' => 'QuantityPerUnit', 'rules' => 'max_length[20]'),
                     array('field' => 'UnitPrice', 'label' => 'UnitPrice', 'rules' => 'integer'),
                     array('field' => 'UnitsInStock', 'label' => 'UnitsInStock', 'rules' => 'integer|max_length[6]'),
                     array('field' => 'UnitsInOrder', 'label' => 'UnitsInOrder', 'rules' => 'integer|max_length[6]'),
                     array('field' => 'ReorderLevel', 'label' => 'ReorderLevel', 'rules' => 'integer|max_length[6]'),
                     array('field' => 'UnitsOnOrder', 'label' => 'UnitsOnOrder', 'rules' => 'integer'),
                     array('field' => 'Discontinued', 'label' => 'Discontinued', 'rules' => 'integer')
                     ),
            'update' => array(
                     array('field' => 'ProductID', 'label' => 'Product ID', 'rules' => 'required|max_length[11]'),
                     array('field' => 'ProductName', 'label' => 'Company Name', 'rules' => 'trim|required|max_length[40]'), 
                     array('field' => 'SupplierID', 'label' => 'Supplier ID', 'rules' => 'integer|max_length[11]'),                     
                     array('field' => 'CategoryID', 'label' => 'CategoryID', 'rules' => 'integer|max_length[11]'),
                     array('field' => 'QuantityPerUnit', 'label' => 'QuantityPerUnit', 'rules' => 'max_length[20]'),
                     array('field' => 'UnitPrice', 'label' => 'UnitPrice', 'rules' => 'integer'),
                     array('field' => 'UnitsInStock', 'label' => 'UnitsInStock', 'rules' => 'integer|max_length[6]'),
                     array('field' => 'UnitsInOrder', 'label' => 'UnitsInOrder', 'rules' => 'integer|max_length[6]'),
                     array('field' => 'ReorderLevel', 'label' => 'ReorderLevel', 'rules' => 'integer|max_length[6]'),
                     array('field' => 'UnitsOnOrder', 'label' => 'UnitsOnOrder', 'rules' => 'integer'),
                     array('field' => 'Discontinued', 'label' => 'Discontinued', 'rules' => 'integer')
                     )                  
            );
        
      $row = array('ProductName' => $this->input->post('ProductName'),
            'SupplierID' => $this->input->post('SupplierID'),
            'CategoryID' => $this->input->post('CategoryID'),
            'QuantityPerUnit'      => $this->input->post('QuantityPerUnit'),
            'UnitPrice'         => $this->input->post('UnitPrice'),
            'UnitsInStock'     => $this->input->post('UnitsInStock'),
            'UnitsOnOrder' => $this->input->post('UnitsOnOrder'),
            'ReorderLevel'    => $this->input->post('ReorderLevel'),
            'Discontinued'    => $this->input->post('Discontinued'));

      $code = 0;

      if($this->input->post('ProductID') == null){

         $this->form_validation->set_rules($rules['insert']);

         if ($this->form_validation->run() == true) {
            
            $this->products->insert($row);

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

            $id = $this->input->post('ProductID');

            $this->products->where('ProductID', $id)->update($row);
            
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

      $id = $this->input->post('ProductID');

      $this->products->where('ProductID', $id)->delete();

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
}
