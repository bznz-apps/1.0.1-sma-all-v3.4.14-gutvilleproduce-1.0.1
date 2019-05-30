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
  //  QUALITY REPORT
  //
  // *************************************************************************


  // ---------------------------------------------------------------------------
  // INSPECTION - GENERATE NEXT REPORT NO
  // ---------------------------------------------------------------------------

  function getNextReportNo()
  {
      // INCREMENTING REPORT NUMBER

      $default_starter_no = 1;
      $count_total_rows = $this->db->count_all_results('NEW_quality_control_reports_count');
      $new_no = 1;
      $last_no = 0;

      // CHECK IF TABLE 'NEW_quality_control_reports_count' IS EMPTY OR HAS RESULTS

      if ($count_total_rows == 0) {

          // IF EMPTY, INIT REPORT NUMBER AND CREATE RECORD

          $countData = array(
              'starter_no' => $default_starter_no,
              'last_no' => $default_starter_no,
          );
          $this->db->insert('NEW_quality_control_reports_count', $countData);
          $new_no = $default_starter_no;

      } else {

        // IF RECORD FOUND, GET LAST REPORT NUMBER SAVED AND UPDATE +1

        $last_no = $this->db->get('NEW_quality_control_reports_count')->row()->last_no;
        $new_no = $last_no + 1;

      }

      echo json_encode($new_no);
  }

  // ---------------------------------------------------------------------------
  // QUALITY - ADD
  // ---------------------------------------------------------------------------

  function addInspection_view()
  {
    $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('quality/getInspections_view'), 'page' => "Inspections"), array('link' => '#', 'page' => "Add Inspection"));
    $meta = array('page_title' => "Add Inspection", 'bc' => $bc);

    $this->data['warehouses'] = $this->warehouses_model->getAllWarehouses();
    $this->data['receiving_reports'] = $this->receiving_model->getAllReceivingReports();

    $this->page_construct('quality/add_inspection', $meta, $this->data);
  }

  function handleAddInspection_logic()
  {

      // $this->form_validation->set_rules('some_input_name', 'some_input_name', 'required');

      // if ($this->form_validation->run() == true) {
      // } else {
      //     $this->session->set_flashdata('error', validation_errors());
      //     admin_redirect('quality/addInspection_view');
      // }

      if (empty($_POST['item_temp_id'])) {
          $this->session->set_flashdata('error', 'Please add at least 1 item to the inspection.');
          admin_redirect('quality/addInspection_view');
      }

      // // AUTOINCREMENT
      //
      // $default_starter_no = 1;
      // $count_total_rows = $this->db->count_all_results('NEW_quality_control_reports_count');
      // $new_no = 1;
      // $last_no = 0;
      //
      // if ($count_total_rows == 0) {
      //
      //     $countData = array(
      //         'starter_no' => $default_starter_no,
      //         'last_no' => $default_starter_no,
      //     );
      //     $this->db->insert('NEW_quality_control_reports_count', $countData);
      //     $new_no = $default_starter_no;
      //
      // } else {
      //
      //   $last_no = $this->db->get('NEW_quality_control_reports_count')->row()->last_no;
      //   $new_no = $last_no + 1;
      //   $dataForCountNo = array(
      //       'last_no' => $new_no,
      //   );
      //   $this->db->update('NEW_quality_control_reports_count', $dataForCountNo, array('starter_no' => $default_starter_no));
      //
      // }

      // IF LAST_NO FOUND ------------------------------------------------------

      $report_no = $this->input->post('inspection_report_no');

      $last_no = $this->db->get('NEW_quality_control_reports_count')->row()->last_no;

      if ($last_no == NULL || $report_no > $last_no) {
          $this->db->update('NEW_quality_control_reports_count', array('last_no' => $report_no));
      }

      /* *****************************************************************
        IMAGE UPLOAD
      ***************************************************************** */

      // #1 library upload must be included here in the controller
      // #2 #3 form input name at view inout must be the same here
      // #4 make sure to pass this $photo variable to the model when saving like:
      // 'image' => $photo,

      $this->load->library('upload'); // #1

      if ($_FILES['inspection_image']['size'] > 0) { // #2
          $config['upload_path'] = $this->upload_path;
          $config['allowed_types'] = $this->image_types;
          $config['max_size'] = $this->allowed_file_size;
          $config['max_width'] = $this->Settings->iwidth;
          $config['max_height'] = $this->Settings->iheight;
          $config['overwrite'] = FALSE;
          $config['max_filename'] = 25;
          $config['encrypt_name'] = TRUE;
          $this->upload->initialize($config);
          if (!$this->upload->do_upload('inspection_image')) { // #3
              $error = $this->upload->display_errors();
              $this->session->set_flashdata('error', $error);
              admin_redirect('quality/addInspection_view');
          }
          $photo = $this->upload->file_name; // #4
          $data['image'] = $photo;
          $this->load->library('image_lib');
          $config['image_library'] = 'gd2';
          $config['source_image'] = $this->upload_path . $photo;
          $config['new_image'] = $this->thumbs_path . $photo;
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
              $wm['source_image'] = $this->upload_path . $photo;
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

      if ($_FILES['inspection_attachment']['size'] > 0) { // #3
          $this->load->library('upload'); // #1
          $config['upload_path'] = $this->digital_upload_path;
          $config['allowed_types'] = $this->digital_file_types;
          $config['max_size'] = $this->allowed_file_size;
          $config['overwrite'] = false;
          $config['encrypt_name'] = true;
          $this->upload->initialize($config);
          if (!$this->upload->do_upload('inspection_attachment')) { // #4
              $error = $this->upload->display_errors();
              $this->session->set_flashdata('error', $error);
              redirect($_SERVER["HTTP_REFERER"]);
          }
          $doc = $this->upload->file_name; // #4
          $data['attachment'] = $doc;
      }

      // ***********************************************************************
      // MODEL DATABASE OPERATION RESULTS
      // ***********************************************************************

      $dataToInsert = array(
          'inspection_no' => $report_no,
          'created_at' => date('Y-m-d H:i:s'),
          'warehouse_id' => $this->input->post('warehouse_id'),
          'receiving_id' => $this->input->post('receiving_id'),
          'lot_n' => $this->input->post('lot_no'),
          'product' => $this->input->post('product_type'),
          'total_qty_sampled' => $this->input->post('total_qty_sampled'),
          'grower_shipper' => $this->input->post('grower_shipper'),
          'inspection_address' => $this->input->post('inspection_address'),
          'inspection_date' => $this->input->post('date'),
          'inspection_name' => $this->input->post('inspection_name'),
          'product_origin' => $this->input->post('product_origin'),
          'additional_issues' => $this->input->post('additional_issues'),
          'comments' => $this->input->post('notes_comments'),
          'image' => $photo,
          'attachment' => $doc,
      );

      $quality_report_id = $this->quality_model->addQualityReport($dataToInsert);

      if ($quality_report_id == true) {
        // $this->session->set_flashdata('message', 'New Supply Order Added Successfully, OID: ' . $quality_report_id);
        // admin_redirect('suppliers/getSupplyOrders_view');
      } else {
        $this->session->set_flashdata('error', 'Something went wrong, please try again later.');
        admin_redirect('quality/addInspection_view');
      }

      // GRAB ITEMS

      $items = array();

      if (sizeof($_POST['item_temp_id']) > 0) {

          $i =  sizeof($_POST['item_temp_id']);

          for ($r = 0; $r < $i; $r++) {

              $item = array(
                  'inspection_id' => $quality_report_id,
                  'sise' => $_POST['inspection_item_sise'][$r],
                  'sample' => $_POST['inspection_item_sample'][$r],
                  'temp' => $_POST['inspection_item_tem'][$r],
                  'presion' => $_POST['inspection_item_presion'][$r],
                  'ripe' => $_POST['inspection_item_ripe'][$r],
                  'mold' => $_POST['inspection_item_mold'][$r],
                  'clean' => $_POST['inspection_item_clean'][$r],
                  'color' => $_POST['inspection_item_color'][$r],
                  'firm' => $_POST['inspection_item_firm'][$r],
                  'mechanical_damage' => $_POST['inspection_item_mechanical_damage'][$r],
                  'weight' => $_POST['inspection_item_weight'][$r],
                  'scars_russet_bruset' => $_POST['inspection_item_scars'][$r],
                  'over_ripe' => $_POST['inspection_item_over_ripe'][$r],
                  'total' => $_POST['inspection_item_total'][$r],
              );

              array_push($items, $item);
              // OR USE: // $items[] = $item;
          }

      }

      $report_complete = $this->quality_model->addQualityReportItems($items);

      // if ($this->suppliers_model->addSupplyOrder($dataToInsert) == true) {
      if ($report_complete == true) {
        $this->session->set_flashdata('message', 'New Quality Inspection Added Successfully');
        admin_redirect('quality/getInspections_view');
      } else {
        $this->session->set_flashdata('error', 'Something went wrong, please try again later.');
        // $this->session->set_flashdata('error', 'result is ' . sizeof($items));
        admin_redirect('quality/addInspection_view');
      }

  }

  // ---------------------------------------------------------------------------
  // QUALITY - GET
  // ---------------------------------------------------------------------------

  function getInspections_view()
  {
    $this->page_construct('quality/list_of_inspections', $meta, $this->data);
  }

  function handleGetInspections_logic()
  {
    // $this->sma->checkPermissions('index');

    // Using the datatables library instead of using models
    $this->load->library('datatables');

    // Query
    $this->datatables
      ->select($this->db->dbprefix('NEW_quality_control_reports') . ".id as id, created_at, inspection_date, inspection_no, receiving_id, lot_n, grower_shipper, inspection_name")
      // THIS BELOW IS FAILING AGAIN... JUST IN SUPPLIER ORDERS
      // ->select($this->db->dbprefix('NEW_receiving_reports') . ".id as id, " . $this->db->dbprefix('NEW_supply_orders') . ".supply_order as supply_order_id, " . "created_at")
      // ->join('NEW_supply_orders', 'NEW_supply_orders.id=NEW_receiving_reports.supply_order_id', 'left')
      ->from("NEW_quality_control_reports")
          ->add_column(
              "Actions",
              "<div class=\"text-center\">

                  <a href='#' class='tip po' title='<b>"
                    // . $this->lang->line("delete_supplier")
                    . "Delete Inspection"
                    . "</b>' data-content=\"<p>"
                    . lang('r_u_sure')
                    . "</p><a class='btn btn-danger po-delete' href='"
                    . admin_url('quality/handleDeleteInspection_logic/$1')
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

  function viewInspection_view($id) {

      $inspection_report = $this->quality_model->getQualityReportByID($id);

      // echo '<pre>'; print_r($palletsWithItems); echo '</pre>';

      $this->data['inspection_id'] = $id;
      $this->data['inspection_created_at'] = $inspection_report->created_at;
      $this->data['inspection_date'] = $inspection_report->inspection_date;
      $this->data['inspection_no'] = $inspection_report->inspection_no;
      $this->data['inspection_receiving_id'] = $inspection_report->receiving_id;
      $this->data['inspection_lot_n'] = $inspection_report->lot_n;
      $this->data['inspection_grower_shipper'] = $inspection_report->grower_shipper;
      $this->data['inspection_name'] = $inspection_report->inspection_name;

      $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('quality/getInspections_view'), 'page' => "Inspections"), array('link' => '#', 'page' => "Inspection No " . $inspection_report->inspection_no));
      $meta = array('page_title' => "Inspection No " . $inspection_report->inspection_no, 'bc' => $bc);

      $this->page_construct('quality/view_inspection', $meta, $this->data);

  }

  function handleGetInspectionItems_logic($inspection_id = null) {
      $this->sma->checkPermissions('index');
      $this->load->library('datatables');
      // Query
      $this->datatables
      ->select($this->db->dbprefix('NEW_quality_control_report_item') . ".id as id, sise, sample, temp, presion, ripe, mold, clean, color, firm, mechanical_damage, weight, scars_russet_bruset, over_ripe, total")
      ->from("NEW_quality_control_report_item")
      ->where('inspection_id', $inspection_id)
      ->add_column(
          "Actions",
          "<div class=\"text-center\">
              <a href='#' class='tip po' title='<b>"
                . $this->lang->line("delete_supplier")
                . "</b>' data-content=\"<p>"
                . lang('r_u_sure')
                . "</p><a class='btn btn-danger po-delete' href='"
                . admin_url('warehouses/handleDeleteInspectionItem_logic/$1')
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

      $this->sma->checkPermissions(NULL, TRUE);

      if ($this->input->get('id')) {
          $id = $this->input->get('id');
      }

      if ($this->quality_model->deleteQualityReport($id)) {
          if($this->input->is_ajax_request()) {
              $this->sma->send_json(array('error' => 0, 'msg' => "Inspection Deleted"));
          }
          $this->session->set_flashdata('message', "Inspection Deleted");
      }

  }


}
