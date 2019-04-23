<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Shipping extends MY_Controller
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

      $this->lang->admin_load('shipping', $this->Settings->user_language);
      $this->load->library('form_validation');

      // leave only the Models i will need
      $this->load->admin_model('suppliers_model');
      $this->load->admin_model('companies_model');
      $this->load->admin_model('sales_model');
      $this->load->admin_model('products_model');
      $this->load->admin_model('receiving_model');
      $this->load->admin_model('shipping_model');
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
  //  PICK UP ORDERS REPORT
  //
  // *************************************************************************

  // ---------------------------------------------------------------------------
  // PICK UP ORDERS - ADD
  // ---------------------------------------------------------------------------

  function addPickUpOrder_view()
  {
    $this->page_construct('shipping/add_pick_up_order', $meta, $this->data);
  }

  function handleAddPickUpOrder_logic()
  {
  }

  // ---------------------------------------------------------------------------
  // PICK UP ORDERS - GET
  // ---------------------------------------------------------------------------

  function getPickUpOrders_view()
  {
    $this->page_construct('shipping/list_of_pick_up_orders', $meta, $this->data);
  }

  function handleGetPickUpOrder_logic()
  {
  }

  function viewPickUpOrder_view($id) {
    $this->page_construct('shipping/view_pick_up_order', $meta, $this->data);
  }

  function handleGetPickUpOrderItems_logic($pick_up_order_id = null) {
  }

  // ---------------------------------------------------------------------------
  // PICK UP ORDERS - EDIT
  // ---------------------------------------------------------------------------

  function editPickUpOrder_view($id) {
    $this->page_construct('shipping/edit_pick_up_order', $meta, $this->data);
  }

  function handleEditPickUpOrder_logic($id) {
  }

  // ---------------------------------------------------------------------------
  // PICK UP ORDERS - DELETE
  // ---------------------------------------------------------------------------

  function handleDeletePickUpOrder_logic($id) {
  }

  // *************************************************************************
  //
  //  BILLS OF LADING REPORT
  //
  // *************************************************************************

  // ---------------------------------------------------------------------------
  // BILLS OF LADING - ADD
  // ---------------------------------------------------------------------------

  function addBillOfLading_view()
  {
    $this->page_construct('shipping/add_BOL', $meta, $this->data);
  }

  function handleAddBillOfLading_logic()
  {
  }

  // ---------------------------------------------------------------------------
  // BILLS OF LADING - GET
  // ---------------------------------------------------------------------------

  function getBillsOfLading_view()
  {
    $this->page_construct('shipping/list_of_BOLs', $meta, $this->data);
  }

  function handleGetBillOfLading_logic()
  {
  }

  function viewBillOfLading_view($id) {
    $this->page_construct('shipping/view_BOL', $meta, $this->data);
  }

  function handleGetBillOfLadingItems_logic($BOL_id = null) {
  }

  // ---------------------------------------------------------------------------
  // BILLS OF LADING - EDIT
  // ---------------------------------------------------------------------------

  function editBillOfLading_view($id) {
    $this->page_construct('shipping/edit_BOL', $meta, $this->data);
  }

  function handleEditBillOfLading_logic($id) {
  }

  // ---------------------------------------------------------------------------
  // BILLS OF LADING - DELETE
  // ---------------------------------------------------------------------------

  function handleDeleteBillOfLading_logic($id) {
  }


}
