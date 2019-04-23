<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Quality extends MY_Controller
{

  function __construct()
  {
      parent::__construct();

      // If user is not logged in, send to login page

      if (!$this->loggedIn) {
          $this->session->set_userdata('requested_page', $this->uri->uri_string());
          $this->sma->md('login');
      }

      // If user belongs to any of the specified user groups below, deny access

      // if ($this->Customer || $this->Supplier) { // || Any Other User Group That Cant Access This Page URLs
      //     $this->session->set_flashdata('warning', lang('access_denied'));
      //     redirect($_SERVER["HTTP_REFERER"]);
      // }

      $this->lang->admin_load('quality', $this->Settings->user_language);
      $this->load->library('form_validation');

      // leave only the Models i will need
      $this->load->admin_model('suppliers_model');
      $this->load->admin_model('companies_model');
      $this->load->admin_model('sales_model');
      $this->load->admin_model('products_model');
      $this->load->admin_model('receiving_model');
      $this->load->admin_model('quality_model');

      // Some other useful libraries
      $this->digital_upload_path = 'files/';
      $this->upload_path = 'assets/uploads/';
      $this->thumbs_path = 'assets/uploads/thumbs/';
      $this->image_types = 'gif|jpg|jpeg|png|tif';
      $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
      $this->allowed_file_size = '1024';
      $this->data['logo'] = true;
      $this->popup_attributes = array('width' => '900', 'height' => '600', 'window_name' => 'sma_popup', 'menubar' => 'yes', 'scrollbars' => 'yes', 'status' => 'no', 'resizable' => 'yes', 'screenx' => '0', 'screeny' => '0');
  }

  // *************************************************************************
  //
  //  QUALITY REPORT
  //
  // *************************************************************************

  // ---------------------------------------------------------------------------
  // QUALITY - ADD
  // ---------------------------------------------------------------------------

  function addInspection_view()
  {
    $this->page_construct('quality/add_inspection', $meta, $this->data);
  }

  function handleAddInspection_logic()
  {
  }

  // ---------------------------------------------------------------------------
  // QUALITY - GET
  // ---------------------------------------------------------------------------

  function getInspections_view()
  {
    $this->page_construct('quality/list_of_inspections', $meta, $this->data);
  }

  function handleGetInspection_logic()
  {
  }

  function viewInspection_view($id) {
    $this->page_construct('quality/view_inspection', $meta, $this->data);
  }

  function handleGetInspectionItems_logic($inspection_id = null) {
  }

  // ---------------------------------------------------------------------------
  // QUALITY - EDIT
  // ---------------------------------------------------------------------------

  function editInspection_view($id) {
    $this->page_construct('quality/edit_inspection', $meta, $this->data);
  }

  function handleEditInspection_logic($id) {
  }

  // ---------------------------------------------------------------------------
  // QUALITY - DELETE
  // ---------------------------------------------------------------------------

  function handleDeleteInspection_logic($id) {
  }


}
