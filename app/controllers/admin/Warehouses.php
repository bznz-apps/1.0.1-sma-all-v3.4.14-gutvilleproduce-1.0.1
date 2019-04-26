<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Warehouses extends MY_Controller
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

      $this->lang->admin_load('warehouses', $this->Settings->user_language);
      $this->load->library('form_validation');

      // leave only the Models i will need
      $this->load->admin_model('suppliers_model');
      $this->load->admin_model('companies_model');
      $this->load->admin_model('sales_model');
      $this->load->admin_model('products_model');
      $this->load->admin_model('receiving_model');
      $this->load->admin_model('warehouses_model');

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
  //  PALLETS
  //
  // *************************************************************************

  // ---------------------------------------------------------------------------
  // PALLETS - ADD
  // ---------------------------------------------------------------------------

  function addPallet_view()
  {
    $this->data['warehouses'] = $this->warehouses_model->getAllWarehouses();
    $this->data['receiving_reports'] = $this->receiving_model->getAllReceivingReports();
    $this->data['products'] = $this->site->getAllProducts();
    $this->page_construct('warehouses/add_pallet', $meta, $this->data);
  }

  function handleAddPallet_logic()
  {
      // ***********************************************************************
      // CHECK PERMISSIONS
      // ***********************************************************************

      // ***********************************************************************
      // FORM VALIDATION RULES
      // ***********************************************************************

      // $this->form_validation->set_message('is_natural_no_zero', lang("no_zero_required"));
      $this->form_validation->set_rules('warehouse_id', 'warehouse_id', 'required');
      $this->form_validation->set_rules('receiving_id', 'receiving_id', 'required');

      // ***********************************************************************
      // RUN FORM VALIDATION
      // ***********************************************************************

      if ($this->form_validation->run() == true) {

      } else {
          $this->session->set_flashdata('error', validation_errors());
          admin_redirect('warehouses/addPallet_view');
      }

      // IF VALIDATION ERROR, SET ERROR FLASH MESSAGE AND REDIRECT TO FORM VIEW
      // IF VALIDATION SUCCESS, PROCEED TO WORK WITH THE DATABASE MODEL

      // ***********************************************************************
      // CUSTOM FORM INPUTS VALIDATION
      // ***********************************************************************

      if (empty($_POST['product_id'])) {
        $this->session->set_flashdata('error', 'Please add at least 1 item to this pallet.');
        admin_redirect('warehouses/addPallet_view');
      }

      // ***********************************************************************
      // MODEL DATABASE OPERATION RESULTS
      // ***********************************************************************

      // for each item, update warehouse, with product_id and qty
      // generate pallet barcode and qr code

      // for each product item,
      // Update Product ID
      //  - update db_product_qty to be = to the sum of db_product_qty + input_product_id qty
      // Update Warehouse ID - table 'warehouses_products'
      //  - loop through each row, and if our product item id found there, udpate qty

      $dataToInsert = array(
          'warehouse_id' => $this->input->post('warehouse_id'),
          'receiving_report_id' => $this->input->post('receiving_id'),
          'description' => $this->input->post('pallet_note'),
          'image' => $this->input->post('pallet_image'),
          'created_at' => date('Y-m-d H:i:s'),
      );

      $new_pallet_id = $this->warehouses_model->addPallet($dataToInsert);

      if ($new_pallet_id == true) {
        // $this->session->set_flashdata('message', 'New Supply Order Added Successfully, OID: ' . $new_pallet_id);
        // admin_redirect('suppliers/getSupplyOrders_view');
      } else {
        $this->session->set_flashdata('error', 'Something went wrong, please try again later.');
        admin_redirect('warehouses/addPallet_view');
      }

      // GRAB ITEMS

      $items = array();

      if (sizeof($_POST['product_id']) > 0) {

          $i =  sizeof($_POST['product_id']);

          for ($r = 0; $r < $i; $r++) {

              $item = array(
                  'pallet_id' => $new_pallet_id,
                  'product_id' => $_POST['product_id'][$r],
                  'quantity' => $_POST['product_quantity'][$r],
                  // 'product_name' => $_POST['product_name'][$r],
              );

              array_push($items, $item);
              // OR USE: // $items[] = $item;

              // UPDATE QTY

              $this->warehouses_model->updateProductQty(
                  $_POST['product_id'][$r],
                  $_POST['product_quantity'][$r]
              );
              $this->warehouses_model->updateWarehouseQty(
                  $_POST['warehouse_id'],
                  $_POST['product_id'][$r],
                  $_POST['product_quantity'][$r]
              );

          }

      }

      $addPalletItems = $this->warehouses_model->addPalletItems($items);

      // if ($this->warehouses_model->addPalletItems($dataToInsert) == true) {
      if ($addPalletItems == true) {
          $this->session->set_flashdata('message', 'New Pallet Added Successfully');
          admin_redirect('warehouses/getPallets_view');
      } else {
          $this->session->set_flashdata('error', 'Something went wrong, please try again later.');
          // $this->session->set_flashdata('error', 'result is ' . sizeof($items));
          admin_redirect('warehouses/addPallet_view');
      }

  }

  // ---------------------------------------------------------------------------
  // PALLETS - GET
  // ---------------------------------------------------------------------------

  function getPallets_view()
  {
    $this->page_construct('warehouses/list_of_pallets', $meta, $this->data);
  }

  function handleGetPallets_logic()
  {
      $this->sma->checkPermissions('index');

      // Using the datatables library instead of using models
      $this->load->library('datatables');

      // ID, Date (created_at) , code, supply_order_id, receiving_report_id, manifest_id, warehouse_id, rack_id, image, attachment

      // Query
      $this->datatables
        ->select($this->db->dbprefix('NEW_pallets') . ".id as id, created_at, code, supply_order_id, receiving_report_id, manifest_id, warehouse_id, rack_id, image, attachment")
        // ->select($this->db->dbprefix('NEW_pallets') . ".id as id, " . $this->db->dbprefix('NEW_supply_orders') . ".supply_order as supply_order_id, " . "created_at")
        // ->join('NEW_supply_orders', 'NEW_supply_orders.id=NEW_pallets.supply_order_id', 'left')
        ->from("NEW_pallets")
          ->add_column(
              "Actions",
              "<div class=\"text-center\">

                  <a href='#' class='tip po' title='<b>"
                    // . $this->lang->line("delete_supplier")
                    . "Delete Pallet"
                    . "</b>' data-content=\"<p>"
                    . lang('r_u_sure')
                    . "</p><a class='btn btn-danger po-delete' href='"
                    . admin_url('warehouses/handleDeletePallet_logic/$1')
                    . "'>" . lang('i_m_sure')
                    . "</a> <button class='btn po-close'>"
                    . lang('no')
                    . "</button>\"  rel='popover'>
                    <i class=\"fa fa-trash-o\"></i>
                  </a>

              </div>",
              "id"
            );

      echo $this->datatables->generate();
  }

  function viewPallet_view($id) {

    $pallet = $this->warehouses_model->getPalletByID($id);

    // -------------------------------------------------------------------------

    $supply_order_id = $pallet->$supply_order_id;
    $manifest_id = $pallet->$manifest_id;
    $receiving_report_id = $pallet->$receiving_report_id;
    $warehouse_id = $pallet->$warehouse_id;
    $rack_id = $pallet->$rack_id;

    // -------------------------------------------------------------------------

    $supply_order_data = $this->suppliers_model->getSupplyOrderByID($supply_order_id);
    // $supply_order_data = $this->receiving_model->getCompanyByID($manifest_id);
    $receiving_data = $this->receiving_model->getReceivingReportByID($receiving_report_id);
    $warehouse_data = $this->warehouses_model->getWarehouseByID($warehouse_id);
    $rack_data = $this->warehouses_model->getRackByID($rack_id);

    // -------------------------------------------------------------------------

    $supply_order_number = $supply_order_data->supply_order_number;
    $receiving_ref_no = $receiving_data->receiving_report_number;
    $manifest_ref_no = $receiving_data->manifest_ref_no;
    $warehouse = $warehouse_data->name;

    $rack_column = $rack_data->column;
    $rack_row = $rack_data->row;
    $rack_z_index = $rack_data->z_index;
    $rack_floor_level = $rack_data->floor_level;

    // -------------------------------------------------------------------------

    $this->data['pallet'] = $pallet;
    $this->data['pallet_id'] = $id;
    $this->data['created_at'] = $pallet->created_at;
    $this->data['code'] = $pallet->code;
    $this->data['supply_order_number'] = $supply_order_number;
    $this->data['receiving_ref_no'] = $receiving_ref_no;
    $this->data['manifest_ref_no'] = $manifest_ref_no;
    $this->data['warehouse'] = $warehouse;
    $this->data['rack'] = $rack_column . "-" . $rack_row . "-" . $rack_z_index . "-" . $rack_floor_level;
    $this->data['image'] = $pallet->image;
    $this->data['attachment'] = $pallet->attachment;

    // -------------------------------------------------------------------------

    $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('warehouses/getPallets_view'), 'page' => "Pallets"), array('link' => '#', 'page' => "Pallet " . $pallet->code));
    $meta = array('page_title' => "Pallet " . $pallet->code, 'bc' => $bc);

    // -------------------------------------------------------------------------

    $this->page_construct('warehouses/view_pallet', $meta, $this->data);
  }

  function handleGetPalletItems_logic($pallet_id = null) {
      $this->sma->checkPermissions('index');
      $this->load->library('datatables');
      // Query
      $this->datatables
      ->select($this->db->dbprefix('NEW_pallet_items') . ".id as id, product_id, quantity")
      ->from("NEW_pallet_items")
      ->where('pallet_id', $pallet_id)
      ->add_column(
          "Actions",
          "<div class=\"text-center\">
              <a href='#' class='tip po' title='<b>"
                . $this->lang->line("delete_supplier")
                . "</b>' data-content=\"<p>"
                . lang('r_u_sure')
                . "</p><a class='btn btn-danger po-delete' href='"
                . admin_url('warehouses/handleDeletePalletItem_logic/$1')
                . "'>" . lang('i_m_sure')
                . "</a> <button class='btn po-close'>"
                . lang('no')
                . "</button>\"  rel='popover'>
                <i class=\"fa fa-trash-o\"></i>
              </a>
          </div>",
          "id"
      );

        echo $this->datatables->generate();
  }

  // ---------------------------------------------------------------------------
  // PALLETS - EDIT
  // ---------------------------------------------------------------------------

  function editPallet_view($id) {
    $this->page_construct('warehouses/edit_pallet', $meta, $this->data);
  }

  function handleEditPallet_logic($id) {
  }

  // ---------------------------------------------------------------------------
  // PALLETS - DELETE
  // ---------------------------------------------------------------------------

  function handleDeletePallet_logic($id) {

      $this->sma->checkPermissions(NULL, TRUE);

      if ($this->input->get('id')) {
          $id = $this->input->get('id');
      }

      if ($this->warehouses_model->deletePallet($id)) {
          if($this->input->is_ajax_request()) {
              $this->sma->send_json(array('error' => 0, 'msg' => "Pallet Deleted"));
          }
          $this->session->set_flashdata('message', "Pallet Deleted");
      }

  }

  // ---------------------------------------------------------------------------
  // PALLETS - MISC
  // ---------------------------------------------------------------------------

  function printPalletBarcodeLabel()
  {
    $this->page_construct('warehouses/print_pallet_barcode_label', $meta, $this->data);
  }

  // *************************************************************************
  //
  //  RACKS REPORT
  //
  // *************************************************************************

  // ---------------------------------------------------------------------------
  // RACKS - ADD
  // ---------------------------------------------------------------------------

  function addRack_view()
  {
    $this->page_construct('warehouses/add_rack', $meta, $this->data);
  }

  function handleAddRack_logic()
  {
  }

  // ---------------------------------------------------------------------------
  // RACKS - GET
  // ---------------------------------------------------------------------------

  function getRacks_view()
  {
    $this->page_construct('warehouses/list_of_racks', $meta, $this->data);
  }

  function handleGetRack_logic()
  {
  }

  function viewRack_view($id) {
    $this->page_construct('warehouses/view_rack', $meta, $this->data);
  }

  function handleGetRackItems_logic($rack_id = null) {
  }

  // ---------------------------------------------------------------------------
  // RACKS - EDIT
  // ---------------------------------------------------------------------------

  function editRack_view($id) {
    $this->page_construct('warehouses/edit_rack', $meta, $this->data);
  }

  function handleEditRack_logic($id) {
  }

  // ---------------------------------------------------------------------------
  // RACKS - DELETE
  // ---------------------------------------------------------------------------

  function handleDeleteRack_logic($id) {

      $this->sma->checkPermissions(NULL, TRUE);

      if ($this->input->get('id')) {
          $id = $this->input->get('id');
      }

      if ($this->warehouses_model->deleteRack($id)) {
          if($this->input->is_ajax_request()) {
              $this->sma->send_json(array('error' => 0, 'msg' => "Rack Deleted"));
          }
          $this->session->set_flashdata('message', "Rack Deleted");
      }
  }

}
