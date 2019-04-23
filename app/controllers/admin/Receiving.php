<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Receiving extends MY_Controller
{

    // getRecords
    // add
    // edit
    // delete
    // some_special_action

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

        $this->lang->admin_load('receiving', $this->Settings->user_language);
        $this->load->library('form_validation');

        // leave only the Models i will need
        $this->load->admin_model('suppliers_model');
        $this->load->admin_model('companies_model');
        $this->load->admin_model('sales_model');
        $this->load->admin_model('products_model');
        $this->load->admin_model('receiving_model');
        $this->load->admin_model('suppliers_model');
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

    // public function index($warehouse_id = null)
    // {
    //     // This function is usually used to load index page of a feature
    //     // Here it should load elements for the Receiving screen where we should
    //     // see all receiving records, all records will be loaded by another function
    //     // called getReceivings, which would load data using DataTables library
    //
    //     // I will change that pattern and will use one function to load some
    //     // CRUD feature screen, and other function to handle the CRUD logic...
    //
    //     $this->sma->checkPermissions();
    //
    //     $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('sales')));
    //     $meta = array('page_title' => lang('sales'), 'bc' => $bc);
    //     $this->page_construct('receiving/index', $meta, $this->data);
    // }

    ////////////////////////////////////////////////////////////////////////////
    // *************************************************************************
    //
    //  RECEIVING REPORT
    //
    // *************************************************************************
    ////////////////////////////////////////////////////////////////////////////

    // ---------------------------------------------------------------------------
    // RECEIVING - ADD
    // ---------------------------------------------------------------------------

    function addReceiving_view()
    {
      // $this->sma->checkPermissions();

      $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('receiving/getReceivings_view'), 'page' => "Receiving"), array('link' => '#', 'page' => "Add Receiving"));
      $meta = array('page_title' => "Receiving", 'bc' => $bc);

      $this->data['warehouses'] = $this->warehouses_model->getAllWarehouses();
      $this->data['supply_orders'] = $this->suppliers_model->getAllSupplyOrders();

      $this->page_construct('receiving/add_receiving', $meta, $this->data);
    }

    function handleAddReceiving_logic()
    {
      // CHECK PERMISSIONS

      // FORM VALIDATION RULES

      $this->form_validation->set_rules('warehouse_id', 'warehouse_id', 'required');
      $this->form_validation->set_rules('supply_order', 'supply_order', 'required');
      $this->form_validation->set_rules('manifest_ref_no', 'manifest_ref_no', 'required');

      // RUN FORM VALIDATION

      if ($this->form_validation->run() == true) {

      } else {
          $this->session->set_flashdata('error', validation_errors());
          admin_redirect('receiving/addReceiving_view');
      }

      // if (empty($_POST['product_id'])) {
      //   $this->session->set_flashdata('error', 'Please add at least 1 item to the order.');
      //   admin_redirect('receiving/addManifest_view');
      // }

      // INCREMENTING: RECEIVING REPORT NUMBER

      $default_starter_receiving_report_number = 1000;
      $receiving_reports_count_total_rows = $this->db->count_all_results('NEW_receiving_reports_count');
      $new_receiving_report_number = 1;
      $last_receiving_report_number = 0;

      // CHECK IF TABLE 'NEW_receiving_reports_count' IS EMPTY OR HAS RESULTS

      if ($receiving_reports_count_total_rows == 0) {

          // IF EMPTY, INIT RECEIVING REPORT NUMBER AND CREATE RECORD

          $receiving_reports_count_data = array(
              'starter_receiving_report_number' => $default_starter_receiving_report_number,
              'last_receiving_report_number' => $default_starter_receiving_report_number,
          );
          $this->db->insert('NEW_receiving_reports_count', $receiving_reports_count_data);
          $new_receiving_report_number = $default_starter_receiving_report_number;

      } else {

        // IF RECORD FOUND, GET LAST RECEIVING REPORT NUMBER SAVED AND UPDATE +1

        $last_receiving_report_number = $this->db->get('NEW_receiving_reports_count')->row()->last_receiving_report_number;
        $new_receiving_report_number = $last_receiving_report_number + 1;
        $dataForReceivingReportsCount = array(
            'last_receiving_report_number' => $new_receiving_report_number,
        );
        $this->db->update('NEW_receiving_reports_count', $dataForReceivingReportsCount, array('starter_receiving_report_number' => $default_starter_receiving_report_number));

      }

      // MODEL DATABASE OPERATION RESULTS

      $dataToInsert = array(
          'receiving_report_number' => $new_receiving_report_number,
          'warehouse_id' => $this->input->post('warehouse_id'),
          'supply_order_id' => $this->input->post('supply_order'),
          'manifest_ref_no' => $this->input->post('manifest_ref_no'),
          'comments' => $this->input->post('receiving_comments'),
          'image' => $this->input->post('manifest_image'),
          'attachment' => $this->input->post('manifest_document'),
          'created_at' => date('Y-m-d H:i:s'),
      );

      if ($this->receiving_model->addReceivingReport($dataToInsert)) {
      // if ($supply_order_complete == true) {
        $this->session->set_flashdata('message', 'New Receiving Report Added Successfully');
        admin_redirect('receiving/getReceivings_view');
      } else {
        $this->session->set_flashdata('error', 'Something went wrong, please try again later.');
        // $this->session->set_flashdata('error', 'result is ' . sizeof($items));
        admin_redirect('receiving/addReceiving_view');
      }
    }

    // ---------------------------------------------------------------------------
    // RECEIVING - GET
    // ---------------------------------------------------------------------------

    function getReceivings_view()
    {
      $this->page_construct('receiving/list_of_receivings', $meta, $this->data);
    }

    function handleGetReceiving_logic()
    {
      // $this->sma->checkPermissions('index');

      // Using the datatables library instead of using models
      $this->load->library('datatables');

      // Query
      $this->datatables
        ->select($this->db->dbprefix('NEW_receiving_reports') . ".id as id, created_at, receiving_report_number, supply_order_id, manifest_ref_no, image, attachment, comments")
        // THIS BELOW IS FAILING AGAIN... JUST IN SUPPLIER ORDERS
        // ->select($this->db->dbprefix('NEW_receiving_reports') . ".id as id, " . $this->db->dbprefix('NEW_supply_orders') . ".supply_order as supply_order_id, " . "created_at")
        // ->join('NEW_supply_orders', 'NEW_supply_orders.id=NEW_receiving_reports.supply_order_id', 'left')
        ->from("NEW_receiving_reports")
            ->add_column(
                "Actions",
                "<div class=\"text-center\">

                    <a href='#' class='tip po' title='<b>"
                      // . $this->lang->line("delete_supplier")
                      . "Delete Receiving Report"
                      . "</b>' data-content=\"<p>"
                      . lang('r_u_sure')
                      . "</p><a class='btn btn-danger po-delete' href='"
                      . admin_url('receiving/handleDeleteReceiving_logic/$1')
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

    function viewReceiving_view($id) {

        // get receiving report
        // get all pallet ids where this receiving report id is found
        // for each pallet, load items

        $receiving_report = $this->receiving_model->getReceivingReportByID($id);
        $pallets = $this->warehouses_model->getAllPalletsByReceivingReportID($id);

        $palletsWithItems = array();

        // foreach ($pallets as $pallet => $value) {
        foreach ($pallets as $pallet) {
            // code...
            $items = $this->warehouses_model->getAllPalletItemsByPalletID($pallet->id);

            $onePalletWithItems = array(
                'id' => $pallet->id,
                'code' => $pallet->code,
                'barcode_symbology' => $pallet->barcode_symbology,
                'supply_order_id' => $pallet->supply_order_id,
                'manifest_id' => $pallet->manifest_id,
                'receiving_report_id' => $pallet->receiving_report_id,
                'warehouse_id' => $pallet->warehouse_id,
                'description' => $pallet->description,
                'created_at' => $pallet->created_at,
                'rack_id' => $pallet->rack_id,
                'image' => $pallet->image,
                'attachment' => $pallet->attachment,
                'items' => $items,
            );

            array_push($palletsWithItems, $onePalletWithItems);
        };

        // echo '<pre>'; print_r($palletsWithItems); echo '</pre>';

        $this->data['receiving_report'] = $receiving_report;
        $this->data['pallets'] = $pallets;
        $this->data['palletsWithItems'] = $palletsWithItems;

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('receiving/getReceivings_view'), 'page' => "Receiving Reports"), array('link' => '#', 'page' => "Receiving Report " . $receiving_report->receiving_report_number));
        $meta = array('page_title' => "Receiving Report " . $receiving_report->receiving_report_number, 'bc' => $bc);

        $this->page_construct('receiving/view_receiving', $meta, $this->data);

    }

    function handleGetReceivingItems_logic($receiving_id = null) {
        $this->sma->checkPermissions('index');

        // Using the datatables library instead of using models
        $this->load->library('datatables');

        // Query
        $this->datatables
          ->select($this->db->dbprefix('NEW_pallets') . ".id as id, created_at, code, rack_id, image, attachment")
          // THIS BELOW IS FAILING AGAIN... JUST IN SUPPLIER ORDERS
          // ->select($this->db->dbprefix('NEW_pallets') . ".id as id, " . $this->db->dbprefix('NEW_supply_orders') . ".supply_order as supply_order_id, " . "created_at")
          // ->join('NEW_supply_orders', 'NEW_supply_orders.id=NEW_pallets.supply_order_id', 'left')
          ->from("NEW_pallets")
          ->where('receiving_report_id', $receiving_id)
          ->add_column(
              "Actions",
              "<div class=\"text-center\">

                  <a href='#' class='tip po' title='<b>"
                    // . $this->lang->line("delete_supplier")
                    . "Delete Supply Order"
                    . "</b>' data-content=\"<p>"
                    . lang('r_u_sure')
                    . "</p><a class='btn btn-danger po-delete' href='"
                    . admin_url('receiving/handleDeleteManifest_logic/$1')
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
    // RECEIVING - EDIT
    // ---------------------------------------------------------------------------

    function editReceiving_view($id) {
      $this->page_construct('receiving/edit_receiving', $meta, $this->data);
    }

    function handleEditReceiving_logic($id) {
    }

    // ---------------------------------------------------------------------------
    // RECEIVING - DELETE
    // ---------------------------------------------------------------------------

    function handleDeleteReceiving_logic($id) {
        $this->sma->checkPermissions(NULL, TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->receiving_model->deleteReceivingReport($id)) {
            if($this->input->is_ajax_request()) {
                $this->sma->send_json(array('error' => 0, 'msg' => "Receiving Report Deleted"));
            }
            $this->session->set_flashdata('message', "Receiving Report Deleted");
        }
    }

    ////////////////////////////////////////////////////////////////////////////
    // *************************************************************************
    //
    //  MANIFESTS
    //
    // *************************************************************************
    ////////////////////////////////////////////////////////////////////////////

    function getManifests_view()
    {
      $this->page_construct('receiving/list_of_manifests', $meta, $this->data);
    }

    function handleGetManifests_logic()
    {
        $this->sma->checkPermissions('index');

        // Using the datatables library instead of using models
        $this->load->library('datatables');

        // Query
        $this->datatables
          ->select($this->db->dbprefix('NEW_supply_order_manifests') . ".id as id, supply_order_id, created_at")
          // THIS BELOW IS FAILING AGAIN... JUST IN SUPPLIER ORDERS
          // ->select($this->db->dbprefix('NEW_supply_order_manifests') . ".id as id, " . $this->db->dbprefix('NEW_supply_orders') . ".supply_order as supply_order_id, " . "created_at")
          // ->join('NEW_supply_orders', 'NEW_supply_orders.id=NEW_supply_order_manifests.supply_order_id', 'left')
          ->from("NEW_supply_order_manifests")
          // ->where('supply_order_id', $supply_order_id)
          ->add_column(
              "Actions",
              "<div class=\"text-center\">

                  <a href='#' class='tip po' title='<b>"
                    // . $this->lang->line("delete_supplier")
                    . "Delete Supply Order"
                    . "</b>' data-content=\"<p>"
                    . lang('r_u_sure')
                    . "</p><a class='btn btn-danger po-delete' href='"
                    . admin_url('receiving/handleDeleteManifest_logic/$1')
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
    // MANIFESTS - ADD
    // ---------------------------------------------------------------------------

    function addManifest_view()
    {
      // $this->sma->checkPermissions();

      $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('receiving/getManifests_view'), 'page' => "Manifests"), array('link' => '#', 'page' => "Add Manifest"));
      $meta = array('page_title' => "Manifests", 'bc' => $bc);

      $this->data['supply_orders'] = $this->suppliers_model->getAllSupplyOrders();

      $this->page_construct('receiving/add_manifest', $meta, $this->data);
    }

    function handleAddManifest_logic()
    {
        // CHECK PERMISSIONS

        // FORM VALIDATION RULES

        $this->form_validation->set_rules('supply_order', 'supply_order', 'required');
        $this->form_validation->set_rules('supp_ord_description', 'supp_ord_description', 'required');

        // RUN FORM VALIDATION

        if ($this->form_validation->run() == true) {

        } else {
            $this->session->set_flashdata('error', validation_errors());
            admin_redirect('receiving/addManifest_view');
        }

        // if (empty($_POST['product_id'])) {
        //   $this->session->set_flashdata('error', 'Please add at least 1 item to the order.');
        //   admin_redirect('receiving/addManifest_view');
        // }

        // MODEL DATABASE OPERATION RESULTS

        $dataToInsert = array(
            'supply_order_id' => $this->input->post('supply_order'),
            'description' => $this->input->post('supp_ord_description'),
            'created_at' => date('Y-m-d H:i:s'),
        );

        if ($this->receiving_model->addManifest($dataToInsert)) {
        // if ($supply_order_complete == true) {
          $this->session->set_flashdata('message', 'New Manifest Added Successfully');
          admin_redirect('receiving/getManifests_view');
        } else {
          $this->session->set_flashdata('error', 'Something went wrong, please try again later.');
          // $this->session->set_flashdata('error', 'result is ' . sizeof($items));
          admin_redirect('receiving/addManifest_view');
        }
    }

    // ---------------------------------------------------------------------------
    // MANIFESTS - GET
    // ---------------------------------------------------------------------------

    function viewManifest_view($id) {
      $this->page_construct('receiving/view_manifest', $meta, $this->data);
    }

    function handleGetManifestItems_logic($manifest_id = null) {
    }

    // ---------------------------------------------------------------------------
    // MANIFESTS - EDIT
    // ---------------------------------------------------------------------------

    function editManifest_view($id) {
      $this->page_construct('receiving/edit_manifest', $meta, $this->data);
    }
    function handleEditManifest_logic($id) {
    }

    // ---------------------------------------------------------------------------
    // MANIFESTS - DELETE
    // ---------------------------------------------------------------------------

    function handleDeleteManifest_logic($id) {
        $this->sma->checkPermissions(NULL, TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->receiving_model->deleteManifest($id)) {
            if($this->input->is_ajax_request()) {
                $this->sma->send_json(array('error' => 0, 'msg' => "Manifest Deleted"));
            }
            $this->session->set_flashdata('message', "Manifest Deleted");
        }
    }

    // *************************************************************************
    // *************************************************************************
    // *************************************************************************

}
