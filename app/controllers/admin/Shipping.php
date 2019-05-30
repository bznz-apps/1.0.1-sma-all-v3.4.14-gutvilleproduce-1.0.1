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
  //  PICK UP ORDERS REPORT
  //
  // *************************************************************************

  function getNextPickUpOrderReportNo()
  {
      // INCREMENTING REPORT NUMBER

      $default_starter_no = 1;
      $count_total_rows = $this->db->count_all_results('NEW_pickup_orders_count');
      $new_no = 1;
      $last_no = 0;

      // CHECK IF TABLE 'NEW_pickup_orders_count' IS EMPTY OR HAS RESULTS

      if ($count_total_rows == 0) {

          // IF EMPTY, INIT REPORT NUMBER AND CREATE RECORD

          $countData = array(
              'starter_no' => $default_starter_no,
              'last_no' => $default_starter_no,
          );
          $this->db->insert('NEW_pickup_orders_count', $countData);
          $new_no = $default_starter_no;

      } else {

        // IF RECORD FOUND, GET LAST REPORT NUMBER SAVED AND UPDATE +1

        $last_no = $this->db->get('NEW_pickup_orders_count')->row()->last_no;
        $new_no = $last_no + 1;

      }

      echo json_encode($new_no);
  }

  // ---------------------------------------------------------------------------
  // PICK UP ORDERS - ADD
  // ---------------------------------------------------------------------------

  function addPickUpOrder_view()
  {
      $this->data['sales'] = $this->sales_model->getAllSales();
      $this->page_construct('shipping/add_pick_up_order', $meta, $this->data);
  }

  function handleAddPickUpOrder_logic()
  {
      $this->form_validation->set_rules('sale_id', 'sale_id', 'required');

      // if ($this->form_validation->run() == true) {
      // } else {
      //     $this->session->set_flashdata('error', validation_errors());
      //     admin_redirect('shipping/addPickUpOrder_view');
      // }

      if (empty($_POST['sale_id'])) {
          $this->session->set_flashdata('error', 'Sale no is required.');
          admin_redirect('shipping/addPickUpOrder_view');
      }

      // -----------------------------------------------------------------------

      // AUTOINCREMENT

      // $default_starter_no = 1;
      // $count_total_rows = $this->db->count_all_results('NEW_pickup_orders_count');
      // $new_no = 1;
      // $last_no = 0;
      //
      // if ($count_total_rows == 0) {
      //
      //     $count_data = array(
      //         'starter_no' => $default_starter_no,
      //         'last_no' => $default_starter_no,
      //     );
      //     $this->db->insert('NEW_pickup_orders_count', $count_data);
      //     $new_no = $default_starter_no;
      //
      // } else {
      //
      //   $last_no = $this->db->get('NEW_pickup_orders_count')->row()->last_no;
      //   $new_no = $last_no + 1;
      //   $dataForCountNo = array(
      //       'last_no' => $new_no,
      //   );
      //   $this->db->update('NEW_pickup_orders_count', $dataForCountNo, array('starter_no' => $default_starter_no));
      //
      // }

      // IF LAST_NO FOUND ------------------------------------------------------

      $report_no = $this->input->post('pick_up_order_no');

      $last_no = $this->db->get('NEW_pickup_orders_count')->row()->last_no;

      if ($last_no == NULL || $report_no > $last_no) {
          $this->db->update('NEW_pickup_orders_count', array('last_no' => $report_no));
      }

      // ***********************************************************************
      // MODEL DATABASE OPERATION RESULTS
      // ***********************************************************************

      $dataToInsert = array(
          'pickup_order_no' => $report_no,
          'created_at' => date('Y-m-d H:i:s'),
          'sale_id' => $this->input->post('sale_id'),
          'sold_to' => $this->input->post('sold_to'),
          'ship_to' => $this->input->post('ship_to'),
          'order_no' => $this->input->post('order_no'),
          'sales_load' => $this->input->post('sales_load'),
          'order_date' => $this->input->post('order_date'),
          'ship_date' => $this->input->post('ship_date'),
          'pay_terms' => $this->input->post('pay_terms'),
          'sale_terms' => $this->input->post('sale_terms'),
          'customer_PO' => $this->input->post('customer_PO'),
          'delivery' => $this->input->post('delivery'),
          'via' => $this->input->post('via'),
          'salesperson' => $this->input->post('salesperson'),
          'carrier' => $this->input->post('carrier'),
          'trailer_lic' => $this->input->post('trailer_lic'),
          'broker' => $this->input->post('broker'),
          'state' => $this->input->post('state'),
          'qty' => $this->input->post('qty'),
          'pallets' => $this->input->post('pallets'),
          'weight' => $this->input->post('weight'),
          'legend1' => $this->input->post('legend1'),
          'legend2' => $this->input->post('legend2'),
          'legend3' => $this->input->post('legend3'),
      );

      $pick_up_order_id = $this->shipping_model->addPickUpOrder($dataToInsert);

      if ($pick_up_order_id == true) {
        // $this->session->set_flashdata('message', 'New Pick Up Order Added Successfully, OID: ' . $pick_up_order_id);
        // admin_redirect('shipping/getPickUpOrders_view');
      } else {
        $this->session->set_flashdata('error', 'Something went wrong, please try again later.');
        admin_redirect('shipping/addPickUpOrder_view');
      }

      // ***********************************************************************
      // ITEMS
      // ***********************************************************************

      $items = array();

      if (sizeof($_POST['product_id']) > 0) {

          $i =  sizeof($_POST['product_id']);

          for ($r = 0; $r < $i; $r++) {

              $item = array(
                  'pickup_order_id' => $pick_up_order_id,
                  'product_id' => $_POST['product_id'][$r],
                  'qty' => $_POST['product_quantity'][$r],
                  // 'UOM' => $_POST['inspection_item_tem'][$r],
                  'price' => $_POST['product_price'][$r],
                  // 'brkg' => $_POST['inspection_item_ripe'][$r],
                  // 'item_description' => $_POST['inspection_item_mold'][$r],
              );

              array_push($items, $item);
              // OR USE: // $items[] = $item;
          }

      }

      $items_added = $this->shipping_model->addPickUpOrderItems($items);

      // ***********************************************************************
      // REDIRECT
      // ***********************************************************************

      // if ($this->suppliers_model->addSupplyOrder($dataToInsert) == true) {
      if ($items_added == true || sizeof($_POST['product_id']) == 0) {
        $this->session->set_flashdata('message', 'New Pick Up Order Added Successfully');
        admin_redirect('shipping/getPickUpOrders_view');
      } else {
        $this->session->set_flashdata('error', 'Something went wrong, please try again later.');
        // $this->session->set_flashdata('error', 'result is ' . sizeof($items));
        admin_redirect('shipping/addPickUpOrder_view');
      }

  }

  // ---------------------------------------------------------------------------
  // PICK UP ORDERS - GET
  // ---------------------------------------------------------------------------

  function getPickUpOrders_view()
  {
    $this->page_construct('shipping/list_of_pick_up_orders', $meta, $this->data);
  }

  function handleGetPickUpOrders_logic()
  {
      // $this->sma->checkPermissions('index');

      // Using the datatables library instead of using models
      $this->load->library('datatables');

      // Query
      $this->datatables
        // replace sale_id for sale_no
        ->select($this->db->dbprefix('NEW_pickup_orders') . ".id as id, created_at, pickup_order_no, sale_id, customer_PO, order_no, sold_to, ship_to")
        // THIS BELOW IS FAILING AGAIN... JUST IN SUPPLIER ORDERS
        // ->select($this->db->dbprefix('NEW_receiving_reports') . ".id as id, " . $this->db->dbprefix('NEW_supply_orders') . ".supply_order as supply_order_id, " . "created_at")
        // ->join('NEW_supply_orders', 'NEW_supply_orders.id=NEW_receiving_reports.supply_order_id', 'left')
        ->from("NEW_pickup_orders")
            ->add_column(
                "Actions",
                "<div class=\"text-center\">

                    <a href='#' class='tip po' title='<b>"
                      // . $this->lang->line("delete_supplier")
                      . "Delete Pick Up Order"
                      . "</b>' data-content=\"<p>"
                      . lang('r_u_sure')
                      . "</p><a class='btn btn-danger po-delete' href='"
                      . admin_url('quality/handleDeletePickUpOrder_logic/$1')
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

  function viewPickUpOrder_view($id) {
      $pick_up_order_data = $this->shipping_model->getPickUpOrderByID($id);

      // echo '<pre>'; print_r($palletsWithItems); echo '</pre>';

      $this->data['pick_up_order_id'] = $id;
      $this->data['pick_up_order_data'] = $pick_up_order_data;
      $this->data['pick_up_order_no'] = $pick_up_order_data->pickup_order_no;

      $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('shipping/getPickUpOrders_view'), 'page' => "Pick Up Orders"), array('link' => '#', 'page' => "Pick Up Order No " . $pick_up_order_data->pickup_order_no));
      $meta = array('page_title' => "Pick Up Order No " . $pick_up_order_data->pickup_order_no, 'bc' => $bc);

      $this->page_construct('shipping/view_pick_up_order', $meta, $this->data);
  }

  function handleGetPickUpOrderItems_logic($pick_up_order_id = null) {
      $this->sma->checkPermissions('index');
      $this->load->library('datatables');
      // Query
      $this->datatables
      ->select($this->db->dbprefix('NEW_pickup_order_item') . ".id as id, product_id, qty, price")
      ->from("NEW_pickup_order_item")
      ->where('pickup_order_id', $pick_up_order_id)
      ->add_column(
          "Actions",
          "<div class=\"text-center\">
              <a href='#' class='tip po' title='<b>"
                . $this->lang->line("delete_supplier")
                . "</b>' data-content=\"<p>"
                . lang('r_u_sure')
                . "</p><a class='btn btn-danger po-delete' href='"
                . admin_url('shipping/handleDeletePickUpOrderItem_logic/$1')
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
  // BOL - GENERATE NEXT REPORT NO
  // ---------------------------------------------------------------------------

  function getNextBOLReportNo()
  {
      // INCREMENTING REPORT NUMBER

      $default_starter_no = 1;
      $count_total_rows = $this->db->count_all_results('NEW_bills_of_lading_count');
      $new_no = 1;
      $last_no = 0;

      // CHECK IF TABLE 'NEW_bills_of_lading_count' IS EMPTY OR HAS RESULTS

      if ($count_total_rows == 0) {

          // IF EMPTY, INIT REPORT NUMBER AND CREATE RECORD

          $countData = array(
              'starter_no' => $default_starter_no,
              'last_no' => $default_starter_no,
          );
          $this->db->insert('NEW_bills_of_lading_count', $countData);
          $new_no = $default_starter_no;

      } else {

        // IF RECORD FOUND, GET LAST REPORT NUMBER SAVED AND UPDATE +1

        $last_no = $this->db->get('NEW_bills_of_lading_count')->row()->last_no;
        $new_no = $last_no + 1;

      }

      echo json_encode($new_no);
  }

  // ---------------------------------------------------------------------------
  // BILLS OF LADING - ADD
  // ---------------------------------------------------------------------------

  function addBillOfLading_view()
  {
      $this->data['sales'] = $this->sales_model->getAllSales();
      $this->data['warehouses'] = $this->warehouses_model->getAllWarehouses();
      $this->data['billers'] = $this->companies_model->getAllBillerCompanies();
      $this->page_construct('shipping/add_BOL', $meta, $this->data);
  }

  function handleAddBillOfLading_logic()
  {

      // ***********************************************************************
      // CHECK PERMISSIONS
      // ***********************************************************************

      // ***********************************************************************
      // FORM VALIDATION RULES
      // ***********************************************************************

      // $this->form_validation->set_message('is_natural_no_zero', lang("no_zero_required"));
      $this->form_validation->set_rules('sale_id', 'sale_id', 'required');
      $this->form_validation->set_rules('biller_id', 'biller_id', 'required');
      $this->form_validation->set_rules('warehouse_id', 'warehouse_id', 'required');

      // ***********************************************************************
      // RUN FORM VALIDATION
      // ***********************************************************************

      if ($this->form_validation->run() == true) {

      } else {
          $this->session->set_flashdata('error', validation_errors());
          admin_redirect('shipping/addBillOfLading_view');
      }

      // IF VALIDATION ERROR, SET ERROR FLASH MESSAGE AND REDIRECT TO FORM VIEW
      // IF VALIDATION SUCCESS, PROCEED TO WORK WITH THE DATABASE MODEL

      // ***********************************************************************
      // CUSTOM FORM INPUTS VALIDATION
      // ***********************************************************************

      // if (empty($_POST['sale_product_id'])) {
      //   $this->session->set_flashdata('error', 'Please add at least 1 item to this pallet.');
      //   admin_redirect('shipping/addBillOfLading_view');
      // }

      if (sizeof($_POST['sale_product_id']) > 0) {

          $i =  sizeof($_POST['sale_product_id']);

          for ($r = 0; $r < $i; $r++) {

              // $shipping_qty = $_POST['sale_product_shipping_qty'][$r];
              // $is_ship_qty_set = isset($_POST['sale_product_shipping_qty'][$r]) ? true : false;
              // if ($is_ship_qty_set == false) {
              if ($_POST['sale_product_shipping_qty'][$r] == "") {
                  $this->session->set_flashdata('error', 'Shipping quantity must not be empty.');
                  admin_redirect('shipping/addBillOfLading_view');
              }

          }

      }

      // ***********************************************************************
      // AUTOINCREMENT
      // ***********************************************************************

      // $default_starter_no = 1;
      // $count_total_rows = $this->db->count_all_results('NEW_bills_of_lading_count');
      // $new_no = 1;
      // $last_no = 0;
      //
      // if ($count_total_rows == 0) {
      //
      //     $count_data = array(
      //         'starter_no' => $default_starter_no,
      //         'last_no' => $default_starter_no,
      //     );
      //     $this->db->insert('NEW_bills_of_lading_count', $count_data);
      //     $new_no = $default_starter_no;
      //
      // } else {
      //
      //     $last_no = $this->db->get('NEW_bills_of_lading_count')->row()->last_no;
      //     $new_no = $last_no + 1;
      //     $dataForCountNo = array(
      //         'last_no' => $new_no,
      //     );
      //     $this->db->update('NEW_bills_of_lading_count', $dataForCountNo, array('starter_no' => $default_starter_no));
      //
      // }

      // IF LAST_NO FOUND ------------------------------------------------------

      $report_no = $this->input->post('bill_of_lading_no');

      $last_no = $this->db->get('NEW_bills_of_lading_count')->row()->last_no;

      if ($last_no == NULL || $report_no > $last_no) {
          $this->db->update('NEW_bills_of_lading_count', array('last_no' => $report_no));
      }

      // ***********************************************************************
      // MODEL DATABASE OPERATION RESULTS
      // ***********************************************************************

      // for each product item,
      // Update Product ID
      //  - update db_product_qty to be = to the sum of db_product_qty + input_product_id qty
      // Update Warehouse ID - table 'warehouses_products'
      //  - loop through each row, and if our product item id found there, udpate qty

      /* *****************************************************************
        IMAGE UPLOAD
      ***************************************************************** */

      // #1 library upload must be included here in the controller
      // #2 #3 form input name at view inout must be the same here
      // #4 make sure to pass this $photo variable to the model when saving like:
      // 'image' => $photo,

      $this->load->library('upload'); // #1

      if ($_FILES['driver_signature_img']['size'] > 0) { // #2
          $config['upload_path'] = $this->upload_path;
          $config['allowed_types'] = $this->image_types;
          $config['max_size'] = $this->allowed_file_size;
          $config['max_width'] = $this->Settings->iwidth;
          $config['max_height'] = $this->Settings->iheight;
          $config['overwrite'] = FALSE;
          $config['max_filename'] = 25;
          $config['encrypt_name'] = TRUE;
          $this->upload->initialize($config);
          if (!$this->upload->do_upload('driver_signature_img')) { // #3
              $error = $this->upload->display_errors();
              $this->session->set_flashdata('error', $error);
              admin_redirect('shipping/addBillOfLading_view');
          }
          $photoDriverSignature = $this->upload->file_name; // #4
          $data['image'] = $photoDriverSignature;
          $this->load->library('image_lib');
          $config['image_library'] = 'gd2';
          $config['source_image'] = $this->upload_path . $photoDriverSignature;
          $config['new_image'] = $this->thumbs_path . $photoDriverSignature;
          $config['maintain_ratio'] = TRUE;
          $config['width'] = $this->Settings->twidth;
          $config['height'] = $this->Settings->theight;
          $this->image_lib->clear();
          $this->image_lib->initialize($config);
          if (!$this->image_lib->resize()) {
              echo $this->image_lib->display_errors();
          }
          if ($this->Settings->watermark) {
              $this->image_lib->clear();
              $wm['source_image'] = $this->upload_path . $photoDriverSignature;
              $wm['wm_text'] = 'Copyright ' . date('Y') . ' - ' . $this->Settings->site_name;
              $wm['wm_type'] = 'text';
              $wm['wm_font_path'] = 'system/fonts/texb.ttf';
              $wm['quality'] = '100';
              $wm['wm_font_size'] = '16';
              $wm['wm_font_color'] = '999999';
              $wm['wm_shadow_color'] = 'CCCCCC';
              $wm['wm_vrt_alignment'] = 'top';
              $wm['wm_hor_alignment'] = 'left';
              $wm['wm_padding'] = '10';
              $this->image_lib->initialize($wm);
              $this->image_lib->watermark();
          }
          $this->image_lib->clear();
          $config = NULL;
      }

      /* *****************************************************************
        IMAGE UPLOAD
      ***************************************************************** */

      // #1 library upload must be included here in the controller
      // #2 #3 form input name at view inout must be the same here
      // #4 make sure to pass this $photo variable to the model when saving like:
      // 'image' => $photo,

      $this->load->library('upload'); // #1

      if ($_FILES['driver_license_copy']['size'] > 0) { // #2
          $config['upload_path'] = $this->upload_path;
          $config['allowed_types'] = $this->image_types;
          $config['max_size'] = $this->allowed_file_size;
          $config['max_width'] = $this->Settings->iwidth;
          $config['max_height'] = $this->Settings->iheight;
          $config['overwrite'] = FALSE;
          $config['max_filename'] = 25;
          $config['encrypt_name'] = TRUE;
          $this->upload->initialize($config);
          if (!$this->upload->do_upload('driver_license_copy')) { // #3
              $error = $this->upload->display_errors();
              $this->session->set_flashdata('error', $error);
              admin_redirect('shipping/addBillOfLading_view');
          }
          $photoDriverLicense = $this->upload->file_name; // #4
          $data['image'] = $photoDriverLicense;
          $this->load->library('image_lib');
          $config['image_library'] = 'gd2';
          $config['source_image'] = $this->upload_path . $photoDriverLicense;
          $config['new_image'] = $this->thumbs_path . $photoDriverLicense;
          $config['maintain_ratio'] = TRUE;
          $config['width'] = $this->Settings->twidth;
          $config['height'] = $this->Settings->theight;
          $this->image_lib->clear();
          $this->image_lib->initialize($config);
          if (!$this->image_lib->resize()) {
              echo $this->image_lib->display_errors();
          }
          if ($this->Settings->watermark) {
              $this->image_lib->clear();
              $wm['source_image'] = $this->upload_path . $photoDriverLicense;
              $wm['wm_text'] = 'Copyright ' . date('Y') . ' - ' . $this->Settings->site_name;
              $wm['wm_type'] = 'text';
              $wm['wm_font_path'] = 'system/fonts/texb.ttf';
              $wm['quality'] = '100';
              $wm['wm_font_size'] = '16';
              $wm['wm_font_color'] = '999999';
              $wm['wm_shadow_color'] = 'CCCCCC';
              $wm['wm_vrt_alignment'] = 'top';
              $wm['wm_hor_alignment'] = 'left';
              $wm['wm_padding'] = '10';
              $this->image_lib->initialize($wm);
              $this->image_lib->watermark();
          }
          $this->image_lib->clear();
          $config = NULL;
      }

      /* *****************************************************************
        IMAGE UPLOAD
      ***************************************************************** */

      // #1 library upload must be included here in the controller
      // #2 #3 form input name at view inout must be the same here
      // #4 make sure to pass this $photo variable to the model when saving like:
      // 'image' => $photo,

      $this->load->library('upload'); // #1

      if ($_FILES['temp_recorder_copy']['size'] > 0) { // #2
          $config['upload_path'] = $this->upload_path;
          $config['allowed_types'] = $this->image_types;
          $config['max_size'] = $this->allowed_file_size;
          $config['max_width'] = $this->Settings->iwidth;
          $config['max_height'] = $this->Settings->iheight;
          $config['overwrite'] = FALSE;
          $config['max_filename'] = 25;
          $config['encrypt_name'] = TRUE;
          $this->upload->initialize($config);
          if (!$this->upload->do_upload('temp_recorder_copy')) { // #3
              $error = $this->upload->display_errors();
              $this->session->set_flashdata('error', $error);
              admin_redirect('shipping/addBillOfLading_view');
          }
          $photoTempRecorder = $this->upload->file_name; // #4
          $data['image'] = $photoTempRecorder;
          $this->load->library('image_lib');
          $config['image_library'] = 'gd2';
          $config['source_image'] = $this->upload_path . $photoTempRecorder;
          $config['new_image'] = $this->thumbs_path . $photoTempRecorder;
          $config['maintain_ratio'] = TRUE;
          $config['width'] = $this->Settings->twidth;
          $config['height'] = $this->Settings->theight;
          $this->image_lib->clear();
          $this->image_lib->initialize($config);
          if (!$this->image_lib->resize()) {
              echo $this->image_lib->display_errors();
          }
          if ($this->Settings->watermark) {
              $this->image_lib->clear();
              $wm['source_image'] = $this->upload_path . $photoTempRecorder;
              $wm['wm_text'] = 'Copyright ' . date('Y') . ' - ' . $this->Settings->site_name;
              $wm['wm_type'] = 'text';
              $wm['wm_font_path'] = 'system/fonts/texb.ttf';
              $wm['quality'] = '100';
              $wm['wm_font_size'] = '16';
              $wm['wm_font_color'] = '999999';
              $wm['wm_shadow_color'] = 'CCCCCC';
              $wm['wm_vrt_alignment'] = 'top';
              $wm['wm_hor_alignment'] = 'left';
              $wm['wm_padding'] = '10';
              $this->image_lib->initialize($wm);
              $this->image_lib->watermark();
          }
          $this->image_lib->clear();
          $config = NULL;
      }

      // ATTACHMENT UPLOAD -----------------------------------------------------

      // #1 library upload must be included here in the controller
      // #2 #3 form input name at view inout must be the same here
      // #4 make sure to pass this $doc variable to the model when saving like:
      // 'attachment' => $doc,

      if ($_FILES['document']['size'] > 0) { // #3
          $this->load->library('upload'); // #1
          $config['upload_path'] = $this->digital_upload_path;
          $config['allowed_types'] = $this->digital_file_types;
          $config['max_size'] = $this->allowed_file_size;
          $config['overwrite'] = false;
          $config['encrypt_name'] = true;
          $this->upload->initialize($config);
          if (!$this->upload->do_upload('document')) { // #4
              $error = $this->upload->display_errors();
              $this->session->set_flashdata('error', $error);
              redirect($_SERVER["HTTP_REFERER"]);
          }
          $doc = $this->upload->file_name; // #4
          $data['attachment'] = $doc;
      }

      $dataToInsert = array(
          'bol_no' => $report_no,
          'sale_id' => $this->input->post('sale_id'),
          'biller_id' => $this->input->post('biller_id'),
          'warehouse_id' => $this->input->post('warehouse_id'),
          'default_po' => $this->input->post('default_po'),
          'bill_to' => $this->input->post('bill_to'),
          'ship_to' => $this->input->post('ship_to'),
          'bill_to_po' => $this->input->post('bill_to_po'),
          'ship_to_po' => $this->input->post('ship_to_po'),
          'sale_terms' => $this->input->post('sale_terms'),
          'payment_terms' => $this->input->post('payment_terms'),
          'shipping_date' => $this->input->post('shipping_date'),
          'carrier_name' => $this->input->post('carrier_name'),
          'truck_broker' => $this->input->post('truck_broker'),
          'driver_name' => $this->input->post('driver_name'),
          'driver_license' => $this->input->post('driver_license'),
          'driver_phone' => $this->input->post('driver_phone'),
          'truck_trailer' => $this->input->post('truck_trailer'),
          'time_out' => $this->input->post('time_out'),
          'temperature' => $this->input->post('temperature'),
          'tem_recorder_no' => $this->input->post('recorder_no'),
          'tem_seal_no' => $this->input->post('seal_no'),

          'image_of_signature' => $photoDriverSignature,
          'image_of_drivers_license' => $photoDriverLicense,
          'image_of_temp_recorder' => $photoTempRecorder,
          'attachment_of_other_doc_1' => $doc,

          'created_at' => date('Y-m-d H:i:s'),
      );

      $new_BOL_id = $this->shipping_model->addBOL($dataToInsert);

      if ($new_BOL_id == true) {
        // $this->session->set_flashdata('message', 'New Bill of Lading Added Successfully, OID: ' . $new_BOL_id);
        // admin_redirect('suppliers/getSupplyOrders_view');
      } else {
        $this->session->set_flashdata('error', 'Something went wrong, please try again later.');
        admin_redirect('shipping/addBillOfLading_view');
      }

      // GRAB ITEMS

      $items = array();

      if (sizeof($_POST['sale_product_id']) > 0) {

          $i =  sizeof($_POST['sale_product_id']);

          for ($r = 0; $r < $i; $r++) {

              $item = array(
                  'bol_id' => $new_BOL_id,
                  'product_id' => $_POST['sale_product_id'][$r],
                  'qty' => $_POST['sale_product_quantity'][$r],
                  'shipping_qty' => $_POST['sale_product_shipping_qty'][$r],
              );

              array_push($items, $item);
              // OR USE: // $items[] = $item;

          }

      }

      $bolItems = $this->shipping_model->addBOLItems($items);

      // if ($this->warehouses_model->addPalletItems($dataToInsert) == true) {
      if ($bolItems == true) {
          $this->session->set_flashdata('message', 'New Bill of Lading Added Successfully');
          admin_redirect('shipping/getBillsOfLading_view');
      } else {
          $this->session->set_flashdata('error', 'Something went wrong, please try again later.');
          // $this->session->set_flashdata('error', 'result is ' . sizeof($items));
          admin_redirect('shipping/addBillOfLading_view');
      }

  }

  // ---------------------------------------------------------------------------
  // BILLS OF LADING - GET
  // ---------------------------------------------------------------------------

  function getBillsOfLading_view()
  {
    $this->page_construct('shipping/list_of_BOLs', $meta, $this->data);
  }

  function handleGetBillsOfLading_logic()
  {
      // $this->sma->checkPermissions('index');

      // Using the datatables library instead of using models
      $this->load->library('datatables');

      // Query
      $this->datatables
        ->select($this->db->dbprefix('NEW_bills_of_lading') . ".id as id, bol_no, sale_id, default_po, shipping_date, ship_to, bill_to, carrier_name")
        // THIS BELOW IS FAILING AGAIN... JUST IN SUPPLIER ORDERS
        // ->select($this->db->dbprefix('NEW_receiving_reports') . ".id as id, " . $this->db->dbprefix('NEW_supply_orders') . ".supply_order as supply_order_id, " . "created_at")
        // ->join('NEW_supply_orders', 'NEW_supply_orders.id=NEW_receiving_reports.supply_order_id', 'left')
        ->from("NEW_bills_of_lading")
            ->add_column(
                "Actions",
                "<div class=\"text-center\">

                    <a href='#' class='tip po' title='<b>"
                      // . $this->lang->line("delete_supplier")
                      . "Delete Bill of Lading"
                      . "</b>' data-content=\"<p>"
                      . lang('r_u_sure')
                      . "</p><a class='btn btn-danger po-delete' href='"
                      . admin_url('quality/handleDeleteBillOfLading_logic/$1')
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

  function viewBillOfLading_view($id) {

      $bill_of_lading = $this->shipping_model->getBOLByID($id);

      // echo '<pre>'; print_r($palletsWithItems); echo '</pre>';

      $this->data['bol_id'] = $id;
      $this->data['bol_data'] = $bill_of_lading;

      $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('shipping/getBillsOfLading_view'), 'page' => "Bills of Lading"), array('link' => '#', 'page' => "Bill of Lading No " . $bill_of_lading->bol_no));
      $meta = array('page_title' => "Bill of Lading No " . $bill_of_lading->bol_no, 'bc' => $bc);

      $this->page_construct('shipping/view_BOL', $meta, $this->data);
  }

  function handleGetBillOfLadingItems_logic($bol_id = null) {

      $this->sma->checkPermissions('index');
      $this->load->library('datatables');
      // Query
      $this->datatables
      ->select($this->db->dbprefix('NEW_bill_of_lading_item') . ".id as id, product_id, qty, shipping_qty")
      ->from("NEW_bill_of_lading_item")
      ->where('bol_id', $bol_id)
      ->add_column(
          "Actions",
          "<div class=\"text-center\">
              <a href='#' class='tip po' title='<b>"
                . $this->lang->line("delete_bol_item")
                . "</b>' data-content=\"<p>"
                . lang('r_u_sure')
                . "</p><a class='btn btn-danger po-delete' href='"
                . admin_url('warehouses/handleDeleteBillOfLadingItem_logic/$1')
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
