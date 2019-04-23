<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Suppliers extends MY_Controller
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

        $this->lang->admin_load('suppliers', $this->Settings->user_language);
        $this->load->library('form_validation');

        // leave only the Models i will need
        $this->load->admin_model('suppliers_model');
        $this->load->admin_model('companies_model');
        $this->load->admin_model('sales_model');
        $this->load->admin_model('products_model');

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

    function index($action = NULL)
    {
        $this->sma->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['action'] = $action;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('suppliers')));
        $meta = array('page_title' => lang('suppliers'), 'bc' => $bc);
        $this->page_construct('suppliers/index', $meta, $this->data);
    }

    function getSuppliers()
    {
        $this->sma->checkPermissions('index');

        $this->load->library('datatables');
        $this->datatables
            ->select("id, company, name, email, phone, city, country, vat_no, gst_no")
            ->from("companies")
            ->where('group_name', 'supplier')
            ->add_column("Actions", "<div class=\"text-center\"><a class=\"tip\" title='" . $this->lang->line("list_products") . "' href='" . admin_url('products?supplier=$1') . "'><i class=\"fa fa-list\"></i></a> <a class=\"tip\" title='" . $this->lang->line("list_users") . "' href='" . admin_url('suppliers/users/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-users\"></i></a> <a class=\"tip\" title='" . $this->lang->line("add_user") . "' href='" . admin_url('suppliers/add_user/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-plus-circle\"></i></a> <a class=\"tip\" title='" . $this->lang->line("edit_supplier") . "' href='" . admin_url('suppliers/edit/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . $this->lang->line("delete_supplier") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('suppliers/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
        //->unset_column('id');
        echo $this->datatables->generate();
    }

    function deleteSupplyOrder($id = NULL)
    {
        $this->sma->checkPermissions(NULL, TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        // if ($this->products_model->deleteProduct($id)) {
        if ($this->suppliers_model->deleteSupplyOrder($id)) {
            if($this->input->is_ajax_request()) {
                // $this->sma->send_json(array('error' => 0, 'msg' => lang("product_deleted")));
                $this->sma->send_json(array('error' => 0, 'msg' => "supply order deleted"));
            }
            // $this->session->set_flashdata('message', lang('product_deleted'));
            $this->session->set_flashdata('message', "supply order deleted");
            // admin_redirect('welcome');
            // admin_redirect('supply');
        }

    }

    function view($id = NULL)
    {
        $this->sma->checkPermissions('index', true);
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['supplier'] = $this->companies_model->getCompanyByID($id);
        $this->load->view($this->theme.'suppliers/view',$this->data);
    }

    function add()
    {
        $this->sma->checkPermissions(false, true);

        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'is_unique[companies.email]');

        if ($this->form_validation->run('companies/add') == true) {

            $data = array('name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'group_id' => '4',
                'group_name' => 'supplier',
                'company' => $this->input->post('company'),
                'address' => $this->input->post('address'),
                'vat_no' => $this->input->post('vat_no'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'postal_code' => $this->input->post('postal_code'),
                'country' => $this->input->post('country'),
                'phone' => $this->input->post('phone'),
                'cf1' => $this->input->post('cf1'),
                'cf2' => $this->input->post('cf2'),
                'cf3' => $this->input->post('cf3'),
                'cf4' => $this->input->post('cf4'),
                'cf5' => $this->input->post('cf5'),
                'cf6' => $this->input->post('cf6'),
                'gst_no' => $this->input->post('gst_no'),
            );
        } elseif ($this->input->post('add_supplier')) {
            $this->session->set_flashdata('error', validation_errors());
            admin_redirect('suppliers');
        }

        if ($this->form_validation->run() == true && $sid = $this->companies_model->addCompany($data)) {
            $this->session->set_flashdata('message', $this->lang->line("supplier_added"));
            $ref = isset($_SERVER["HTTP_REFERER"]) ? explode('?', $_SERVER["HTTP_REFERER"]) : NULL;
            admin_redirect($ref[0] . '?supplier=' . $sid);
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'suppliers/add', $this->data);
        }
    }

    function edit($id = NULL)
    {
        $this->sma->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $company_details = $this->companies_model->getCompanyByID($id);
        if ($this->input->post('email') != $company_details->email) {
            $this->form_validation->set_rules('code', lang("email_address"), 'is_unique[companies.email]');
        }

        if ($this->form_validation->run('companies/add') == true) {
            $data = array('name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'group_id' => '4',
                'group_name' => 'supplier',
                'company' => $this->input->post('company'),
                'address' => $this->input->post('address'),
                'vat_no' => $this->input->post('vat_no'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'postal_code' => $this->input->post('postal_code'),
                'country' => $this->input->post('country'),
                'phone' => $this->input->post('phone'),
                'cf1' => $this->input->post('cf1'),
                'cf2' => $this->input->post('cf2'),
                'cf3' => $this->input->post('cf3'),
                'cf4' => $this->input->post('cf4'),
                'cf5' => $this->input->post('cf5'),
                'cf6' => $this->input->post('cf6'),
                'gst_no' => $this->input->post('gst_no'),
            );
        } elseif ($this->input->post('edit_supplier')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->companies_model->updateCompany($id, $data)) {
            $this->session->set_flashdata('message', $this->lang->line("supplier_updated"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            $this->data['supplier'] = $company_details;
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'suppliers/edit', $this->data);
        }
    }

    function users($company_id = NULL)
    {
        $this->sma->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $company_id = $this->input->get('id');
        }


        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['modal_js'] = $this->site->modal_js();
        $this->data['company'] = $this->companies_model->getCompanyByID($company_id);
        $this->data['users'] = $this->companies_model->getCompanyUsers($company_id);
        $this->load->view($this->theme . 'suppliers/users', $this->data);

    }

    function add_user($company_id = NULL)
    {
        $this->sma->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $company_id = $this->input->get('id');
        }
        $company = $this->companies_model->getCompanyByID($company_id);

        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'is_unique[users.email]');
        $this->form_validation->set_rules('password', $this->lang->line('password'), 'required|min_length[8]|max_length[20]|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('confirm_password'), 'required');

        if ($this->form_validation->run('companies/add_user') == true) {
            $active = $this->input->post('status');
            $notify = $this->input->post('notify');
            list($username, $domain) = explode("@", $this->input->post('email'));
            $email = strtolower($this->input->post('email'));
            $password = $this->input->post('password');
            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'phone' => $this->input->post('phone'),
                'gender' => $this->input->post('gender'),
                'company_id' => $company->id,
                'company' => $company->company,
                'group_id' => 3
            );
            $this->load->library('ion_auth');
        } elseif ($this->input->post('add_user')) {
            $this->session->set_flashdata('error', validation_errors());
            admin_redirect('suppliers');
        }

        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data, $active, $notify)) {
            $this->session->set_flashdata('message', $this->lang->line("user_added"));
            admin_redirect("suppliers");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['company'] = $company;
            $this->load->view($this->theme . 'suppliers/add_user', $this->data);
        }
    }

    function import_csv()
    {
        $this->sma->checkPermissions('add', true);
        $this->load->helper('security');
        $this->form_validation->set_rules('csv_file', $this->lang->line("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            if (DEMO) {
                $this->session->set_flashdata('warning', $this->lang->line("disabled_in_demo"));
                redirect($_SERVER["HTTP_REFERER"]);
            }

            if (isset($_FILES["csv_file"])) {

                $this->load->library('upload');

                $config['upload_path'] = 'files/';
                $config['allowed_types'] = 'csv';
                $config['max_size'] = '2000';
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload('csv_file')) {

                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    admin_redirect("suppliers");
                }

                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen("files/" . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 5001, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);
                $rw = 2;
                $updated = '';
                $data = array();
                foreach ($arrResult as $key => $value) {
                    $supplier = [
                        'company'       => isset($value[0]) ? trim($value[0]) : '',
                        'name'          => isset($value[1]) ? trim($value[1]) : '',
                        'email'         => isset($value[2]) ? trim($value[2]) : '',
                        'phone'         => isset($value[3]) ? trim($value[3]) : '',
                        'address'       => isset($value[4]) ? trim($value[4]) : '',
                        'city'          => isset($value[5]) ? trim($value[5]) : '',
                        'state'         => isset($value[6]) ? trim($value[6]) : '',
                        'postal_code'   => isset($value[7]) ? trim($value[7]) : '',
                        'country'       => isset($value[8]) ? trim($value[8]) : '',
                        'vat_no'        => isset($value[9]) ? trim($value[9]) : '',
                        'gst_no'        => isset($value[10]) ? trim($value[10]) : '',
                        'cf1'           => isset($value[11]) ? trim($value[11]) : '',
                        'cf2'           => isset($value[12]) ? trim($value[12]) : '',
                        'cf3'           => isset($value[13]) ? trim($value[13]) : '',
                        'cf4'           => isset($value[14]) ? trim($value[14]) : '',
                        'cf5'           => isset($value[15]) ? trim($value[15]) : '',
                        'cf6'           => isset($value[16]) ? trim($value[16]) : '',
                        'group_id'      => 4,
                        'group_name'    => 'supplier',
                    ];
                    if (empty($supplier['company']) || empty($supplier['name']) || empty($supplier['email'])) {
                        $this->session->set_flashdata('error', lang('company').', '.lang('name').', '.lang('email').' '.lang('are_required'). ' (' . lang('line_no') . ' ' . $rw . ')');
                        admin_redirect("suppliers");
                    } else {
                        if ($this->Settings->indian_gst && empty($supplier['state'])) {
                            $this->session->set_flashdata('error', lang('state').' '.lang('is_required'). ' (' . lang('line_no') . ' ' . $rw . ')');
                            admin_redirect("suppliers");
                        }
                        if ($supplier_details = $this->companies_model->getCompanyByEmail($supplier['email'])) {
                            if ($supplier_details->group_id == 4) {
                                $updated .= '<p>'.lang('supplier_updated').' ('.$supplier['email'].')</p>';
                                $this->companies_model->updateCompany($supplier_details->id, $supplier);
                            }
                        } else {
                            $data[] = $supplier;
                        }
                        $rw++;
                    }
                }

                // $this->sma->print_arrays($data, $updated);
            }

        } elseif ($this->input->post('import')) {
            $this->session->set_flashdata('error', validation_errors());
            admin_redirect('suppliers');
        }

        if ($this->form_validation->run() == true && !empty($data)) {
            if ($this->companies_model->addCompanies($data)) {
                $this->session->set_flashdata('message', $this->lang->line("suppliers_added").$updated);
                admin_redirect('suppliers');
            }
        } else {
            if (isset($data) && empty($data)) {
                if ($updated) {
                    $this->session->set_flashdata('message', $updated);
                } else {
                    $this->session->set_flashdata('warning', lang('data_x_suppliers'));
                }
                admin_redirect('suppliers');
            }

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'suppliers/import', $this->data);
        }
    }

    function delete($id = NULL)
    {
        $this->sma->checkPermissions(NULL, TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->companies_model->deleteSupplier($id)) {
            $this->sma->send_json(array('error' => 0, 'msg' => lang("supplier_deleted")));
        } else {
            $this->sma->send_json(array('error' => 1, 'msg' => lang("supplier_x_deleted_have_purchases")));
        }
    }

    function suggestions($term = NULL, $limit = NULL)
    {
        // $this->sma->checkPermissions('index');
        if ($this->input->get('term')) {
            $term = $this->input->get('term', TRUE);
        }
        $limit = $this->input->get('limit', TRUE);
        $rows['results'] = $this->companies_model->getSupplierSuggestions($term, $limit);
        $this->sma->send_json($rows);
    }

    function getSupplier($id = NULL)
    {
        // $this->sma->checkPermissions('index');
        $row = $this->companies_model->getCompanyByID($id);
        $this->sma->send_json(array(array('id' => $row->id, 'text' => $row->company)));
    }

    function supplier_actions()
    {
        if (!$this->Owner && !$this->GP['bulk_actions']) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    $this->sma->checkPermissions('delete');
                    $error = false;
                    foreach ($_POST['val'] as $id) {
                        if (!$this->companies_model->deleteSupplier($id)) {
                            $error = true;
                        }
                    }
                    if ($error) {
                        $this->session->set_flashdata('warning', lang('suppliers_x_deleted_have_purchases'));
                    } else {
                        $this->session->set_flashdata('message', $this->lang->line("suppliers_deleted"));
                    }
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('customer'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('company'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('email'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('phone'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('address'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('city'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('state'));
                    $this->excel->getActiveSheet()->SetCellValue('H1', lang('postal_code'));
                    $this->excel->getActiveSheet()->SetCellValue('I1', lang('country'));
                    $this->excel->getActiveSheet()->SetCellValue('J1', lang('vat_no'));
                    $this->excel->getActiveSheet()->SetCellValue('K1', lang('gst_no'));
                    $this->excel->getActiveSheet()->SetCellValue('L1', lang('scf1'));
                    $this->excel->getActiveSheet()->SetCellValue('M1', lang('scf2'));
                    $this->excel->getActiveSheet()->SetCellValue('N1', lang('scf3'));
                    $this->excel->getActiveSheet()->SetCellValue('O1', lang('scf4'));
                    $this->excel->getActiveSheet()->SetCellValue('P1', lang('scf5'));
                    $this->excel->getActiveSheet()->SetCellValue('Q1', lang('scf6'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $customer = $this->site->getCompanyByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $customer->company);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $customer->name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $customer->email);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $customer->phone);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $customer->address);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $customer->city);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $customer->state);
                        $this->excel->getActiveSheet()->SetCellValue('H' . $row, $customer->postal_code);
                        $this->excel->getActiveSheet()->SetCellValue('I' . $row, $customer->country);
                        $this->excel->getActiveSheet()->SetCellValue('J' . $row, $customer->vat_no);
                        $this->excel->getActiveSheet()->SetCellValue('K' . $row, $customer->gst_no);
                        $this->excel->getActiveSheet()->SetCellValue('L' . $row, $customer->cf1);
                        $this->excel->getActiveSheet()->SetCellValue('M' . $row, $customer->cf2);
                        $this->excel->getActiveSheet()->SetCellValue('N' . $row, $customer->cf3);
                        $this->excel->getActiveSheet()->SetCellValue('O' . $row, $customer->cf4);
                        $this->excel->getActiveSheet()->SetCellValue('P' . $row, $customer->cf5);
                        $this->excel->getActiveSheet()->SetCellValue('Q' . $row, $customer->cf6);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'suppliers_' . date('Y_m_d_H_i_s');
                    $this->load->helper('excel');
                    create_excel($this->excel, $filename);
                }
            } else {
                $this->session->set_flashdata('error', $this->lang->line("no_supplier_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    // *************************************************************************
    //
    // NEW FEATURE - SUPPLY ORDERS
    //
    // *************************************************************************

    function getSupplyOrders_view()
    {
      $this->page_construct('suppliers/list_of_supply_orders', $meta, $this->data);
    }

    function handleGetSupplyOrders_logic()
    {
        $this->sma->checkPermissions('index');

        // Using the datatables library instead of using models
        $this->load->library('datatables');

        // Query
        $this->datatables

          // DOCUMENTATION *****************************************************
          // USE THIS TO SELECT ALL FIELDS FROM ONE TABLE

          // SIMPLE, NO JOINS, NO NOTHING, NO CONCATENATION
          //   ->select("id, supply_order_number, supplier_id, created_at")
          //   ->from("NEW_supply_orders")
          //   // ->where('group_name', 'supplier')

          // DOCUMENTATION *****************************************************
          // USE THIS TO UNDERSTAND THE CONCAT PART
          // SYNTAX IS CORRECT BUT WONT WORK CAUSE 'select HAS TO BE IN 1 SINGLE LINE

          // ->select(
          //     $this->db->dbprefix('NEW_supply_orders')  . ".id as id,
          //     supply_order_number,
          //     CONCAT(
          //         " . $this->db->dbprefix('companies') . ".company,
          //         ' - ',
          //         " . $this->db->dbprefix('companies') . ".name)
          //         as supplier_id,
          //     created_at",
          //     // true
          // )
          // ->join('companies', 'companies.id=NEW_supply_orders.supplier_id', 'left')
          // ->from("NEW_supply_orders")
          //
          // ANOTHER LOOK:
          //
          // CONCAT(
          //       " . $this->db->dbprefix('companies') . ".company,
          //       ' - ',
          //       " . $this->db->dbprefix('companies') . ".name
          // ) as supplier_id,

          // DOCUMENTATION *****************************************************
          // IF USE THE CODE ABOVE, THERE WILL BE AN ERROR ON THE DATATABLE LOAD
          // USE BELOW WHERE THE 'select' TAKES ONE LINE

          // ->select($this->db->dbprefix('NEW_supply_orders')  . ".id as id, supply_order_number, CONCAT(" . $this->db->dbprefix('companies') . ".company, ' - ', " . $this->db->dbprefix('companies') . ".name) as supplier_id, created_at", false)
          // ->join('companies', 'companies.id=NEW_supply_orders.supplier_id', 'left')
          // ->from("NEW_supply_orders")

          // DOCUMENTATION *****************************************************
          // USE CODE BELOW WHERE THE 'select' TAKES ONE LINE AND WE DONT CONCATENATE VALUES
          // ->select($this->db->dbprefix('NEW_supply_orders') . ".id as id, supply_order_number, " . $this->db->dbprefix('companies') . ".company as supplier_id, " . "created_at")
          ->select($this->db->dbprefix('NEW_supply_orders') . ".id as id, created_at, supply_order_number, " . $this->db->dbprefix('companies') . ".company as supplier_id")
          ->join('companies', 'companies.id=NEW_supply_orders.supplier_id', 'left')
          ->from("NEW_supply_orders")

            /*
            ->add_column(
              "Actions","<div class=\"text-center\"><a class=\"tip\" title='" . $this->lang->line("list_products") . "' href='" . admin_url('products?supplier=$1') . "'><i class=\"fa fa-list\"></i></a> <a class=\"tip\" title='" . $this->lang->line("list_users") . "' href='" . admin_url('suppliers/users/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-users\"></i></a> <a class=\"tip\" title='" . $this->lang->line("add_user") . "' href='" . admin_url('suppliers/add_user/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-plus-circle\"></i></a> <a class=\"tip\" title='" . $this->lang->line("edit_supplier") . "' href='" . admin_url('suppliers/edit/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . $this->lang->line("delete_supplier") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('suppliers/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
            */

            // ->add_column(
            //     "Actions",
            //     "<div class=\"text-center\">
            //
            //         <a class=\"tip\" title='" . $this->lang->line("list_products") . "' href='" . admin_url('products?supplier=$1') . "'>
            //           <i class=\"fa fa-list\"></i>
            //         </a>
            //         <a class=\"tip\" title='" . $this->lang->line("list_users") . "' href='" . admin_url('suppliers/users/$1') . "' data-toggle='modal' data-target='#myModal'>
            //           <i class=\"fa fa-users\"></i>
            //         </a>
            //         <a class=\"tip\" title='" . $this->lang->line("add_user") . "' href='" . admin_url('suppliers/add_user/$1') . "' data-toggle='modal' data-target='#myModal'>
            //           <i class=\"fa fa-plus-circle\"></i>
            //         </a>
            //         <a class=\"tip\" title='" . $this->lang->line("edit_supplier") . "' href='" . admin_url('suppliers/edit/$1') . "' data-toggle='modal' data-target='#myModal'>
            //           <i class=\"fa fa-edit\"></i>
            //         </a>
            //
            //         <a href='#' class='tip po' title='<b>" . $this->lang->line("delete_supplier") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('suppliers/deleteSupplyOrder/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'>
            //           <i class=\"fa fa-trash-o\"></i>
            //         </a>
            //
            //     </div>",
            //     "id"
            //   );

            ->add_column(
                "Actions",
                "<div class=\"text-center\">

                    <a href='#' class='tip po' title='<b>"
                      // . $this->lang->line("delete_supplier")
                      . "Delete Supply Order"
                      . "</b>' data-content=\"<p>"
                      . lang('r_u_sure')
                      . "</p><a class='btn btn-danger po-delete' href='"
                      . admin_url('suppliers/deleteSupplyOrder/$1')
                      . "'>" . lang('i_m_sure')
                      . "</a> <button class='btn po-close'>"
                      . lang('no')
                      . "</button>\"  rel='popover'>
                      <i class=\"fa fa-trash-o\"></i>
                    </a>

                </div>",
                "id"
            );

              // EDIT ICON ACTION
              // <a
              //   class=\"tip\" title='" . $this->lang->line("edit_supplier") . "'
              //   href='" . admin_url('suppliers/edit/$1') . "'
              //   data-toggle='modal'
              //   data-target='#myModal'>
              //       <i class=\"fa fa-edit\"></i>
              // </a>

              // MORE ICON ACTIONS EXAMPLES
              // ->add_column(
              //   "Actions",
              //   "<div class=\"text-center\">
              //       <a class=\"tip\" title='"
              //           . $this->lang->line("list_products")
              //           . "' href='" . admin_url('products?supplier=$1')
              //           . "'><i class=\"fa fa-list\"></i></a>
              //           <a class=\"tip\" title='" . $this->lang->line("list_users") . "' href='" . admin_url('suppliers/users/$1') . "' data-toggle='modal' data-target='#myModal'>
              //               <i class=\"fa fa-users\"></i>
              //           </a>
              //           <a class=\"tip\" title='" . $this->lang->line("add_user") . "' href='" . admin_url('suppliers/add_user/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-plus-circle\"></i></a> <a class=\"tip\" title='" . $this->lang->line("edit_supplier") . "' href='" . admin_url('suppliers/edit/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . $this->lang->line("delete_supplier") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('suppliers/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");

        //->unset_column('id');
        echo $this->datatables->generate();
    }

    function viewSupplyOrder_view($id) {

      echo "Preview Supply Order ID: " . $id;

      // $this->data['products'] = $this->site->getAllProducts();


      // $this->data === select * from 'NEW_supply_orders' where id === $id

      // echo $this->data;

      // echo '<pre>'; print_r($this->data); echo '</pre>';

      // GET ALL FIELDS ABOUT THIS RECORD ID
      // - PASS THEM AS $this->data
      // $supply_order = $this->db->get_where("NEW_supply_orders", array('id' => $id));
      $supply_order = $this->suppliers_model->getSupplyOrderByID($id);
      // echo '<pre>'; print_r($supply_order); echo '</pre>';

      $supplier_company_data = $this->companies_model->getCompanyByID($supply_order->supplier_id);
      $supplier_company = $supplier_company_data->company;
      $supplier_company_salesperson = $supplier_company_data->name;

      $this->data['supply_order'] = $supply_order;
      $this->data['supply_order_id'] = $id;
      $this->data['supply_order_number'] = $supply_order->supply_order_number;
      $this->data['created_at'] = $supply_order->created_at;
      $this->data['supplier_id'] = $supply_order->supplier_id;
      $this->data['supplier_company'] = $supplier_company;
      $this->data['message_to_supplier'] = $supply_order->message_to_supplier;
      $this->data['message_to_receiver'] = $supply_order->message_to_receiver;
      $this->data['image'] = $supply_order->image;
      $this->data['attachment'] = $supply_order->attachment;

      $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('suppliers/getSupplyOrders_view'), 'page' => "Supply Orders"), array('link' => '#', 'page' => "Supply Order " . $supply_order->supply_order_number));
      $meta = array('page_title' => "Supply Order " . $supply_order->supply_order_number, 'bc' => $bc);

      $this->page_construct('suppliers/view_supply_order', $meta, $this->data);
    }

      function handleGetSupplyOrderItems_logic($supply_order_id = null) {

        $this->sma->checkPermissions('index');
        $this->load->library('datatables');
        // Query
        $this->datatables

        // *********************************************************************

        // IDEALLY THIS SHOULD WORK, BUT FOR SOME REASON IT IS NOT FINDING THE 'product' TABLE AT:
        // $this->db->dbprefix('product'):

        // ->select($this->db->dbprefix('NEW_supply_order_items') . ".id as id, " . $this->db->dbprefix('products') . ".id as product_id, " . "quantity")
        // ->join('products', 'products.id=NEW_supply_order_items.product_id', 'left')
        // ->from("NEW_supply_order_items")
        // // ->where('supply_order_id', $supply_order_id)

        // ---------------------------------------------------------------------

        // WORKS IF YOU CHANGE 'products' TABLE FOR THE 'sales' TABLE

        // ->select($this->db->dbprefix('NEW_supply_order_items') . ".id as id, " . $this->db->dbprefix('sales') . ".id as product_id, " . "quantity")
        // ->join('sales', 'sales.id=NEW_supply_order_items.product_id', 'left')
        // ->from("NEW_supply_order_items")
        // // ->where('supply_order_id', $supply_order_id)

        // ---------------------------------------------------------------------

        // MY SOLUTION IS TO PASS JUST THE 'product_id' AS SAVED IN THE TABLE
        // THEN THERE IN THE TABLE REPLACE WITH PRODUCT NAME, DOING ANOTHER REQUEST
        // THIS IS TO KEEP WITH THIS PATTERN, CAUSE IT MIGHT WORK IN OTHER TABLES

        ->select($this->db->dbprefix('NEW_supply_order_items') . ".id as id, product_id, quantity")
        ->from("NEW_supply_order_items")
        ->where('supply_order_id', $supply_order_id)

        // *********************************************************************


        ->add_column(
            "Actions",
            "<div class=\"text-center\">
                <a href='#' class='tip po' title='<b>"
                  . $this->lang->line("delete_supplier")
                  . "</b>' data-content=\"<p>"
                  . lang('r_u_sure')
                  . "</p><a class='btn btn-danger po-delete' href='"
                  . admin_url('suppliers/deleteSupplyOrder/$1')
                  . "'>" . lang('i_m_sure')
                  . "</a> <button class='btn po-close'>"
                  . lang('no')
                  . "</button>\"  rel='popover'>
                  <i class=\"fa fa-trash-o\"></i>
                </a>
            </div>",
            "id"
        );


        // if ($supply_order_id == 63) {
          echo $this->datatables->generate();
        // }

    }

    function editSupplyOrder_view($id) {
      echo "Edit Supply Order ID: " . $id;
    }
    function handleEditSupplyOrder_logic($id) {
      echo "Edit Supply Order ID: " . $id;
    }


    function addSupplyOrder_view()
    {
      // $this->sma->checkPermissions();
      // $sale_id = $this->input->get('sale_id') ? $this->input->get('sale_id') : NULL;
      //
      // $this->form_validation->set_message('is_natural_no_zero', lang("no_zero_required"));
      // $this->form_validation->set_rules('customer', lang("customer"), 'required');
      // $this->form_validation->set_rules('biller', lang("biller"), 'required');
      // $this->form_validation->set_rules('sale_status', lang("sale_status"), 'required');
      // $this->form_validation->set_rules('payment_status', lang("payment_status"), 'required');
      //
      // if ($this->form_validation->run() == true) {}

      //     $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
      //     $this->data['quote_id'] = $quote_id ? $quote_id : $sale_id;
      //     $this->data['billers'] = $this->site->getAllCompanies('biller');
      //     $this->data['warehouses'] = $this->site->getAllWarehouses();
      //     $this->data['tax_rates'] = $this->site->getAllTaxRates();
      //     $this->data['units'] = $this->site->getAllBaseUnits();
      //     //$this->data['currencies'] = $this->sales_model->getAllCurrencies();
      //     $this->data['slnumber'] = ''; //$this->site->getReference('so');
      //     $this->data['payment_ref'] = ''; //$this->site->getReference('pay');

          // $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('add_sale')));
          // $meta = array('page_title' => lang('add_sale'), 'bc' => $bc);

          $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('suppliers/getSupplyOrders_view'), 'page' => "Supply Orders"), array('link' => '#', 'page' => "Add Supply Order"));
          $meta = array('page_title' => "Add Supply Order", 'bc' => $bc);

          $this->data['suppliers'] = $this->companies_model->getAllSupplierCompanies();
          $this->data['products'] = $this->site->getAllProducts();
          $this->page_construct('suppliers/add_supply_order', $meta, $this->data);
      // }

    }

    ////////////////////////////////////////////////////////////////////////////

    function handleAddSupplyOrder_logic()
    {
      // ***********************************************************************
      // CHECK PERMISSIONS
      // ***********************************************************************

      // ***********************************************************************
      // FORM VALIDATION RULES
      // ***********************************************************************

      // DOCUMENTATION
      // $this->form_validation->set_rules( arg1, arg2, arg3 );
      // arg1: form input name
      // arg2: word to replace at: 'The "arg2" key is required.'
      // arg3: form validation requirements...

      // $this->form_validation->set_message('is_natural_no_zero', lang("no_zero_required"));
      $this->form_validation->set_rules('supplier', 'supplier', 'required');

      // ***********************************************************************
      // RUN FORM VALIDATION
      // ***********************************************************************

      if ($this->form_validation->run() == true) {

      } else {
          $this->session->set_flashdata('error', validation_errors());
          admin_redirect('suppliers/addSupplyOrder_view');
      }

      // IF VALIDATION ERROR, SET ERROR FLASH MESSAGE AND REDIRECT TO FORM VIEW
      // IF VALIDATION SUCCESS, PROCEED TO WORK WITH THE DATABASE MODEL

      // ***********************************************************************
      // CUSTOM FORM INPUTS VALIDATION
      // ***********************************************************************

      // if (empty($_POST['supplier'])) {
      //   $this->session->set_flashdata('error', 'Please add a Supplier');
      //   admin_redirect('suppliers/addSupplyOrder_view');
      // }

      // ADD LATER
      // Use this to create an if statement that checks if 'product_id' is not empty
      // and if it is not empty check that size is > 0
      // isset($_POST['product_id']) ? sizeof($_POST['product_id']) : 0

      if (empty($_POST['product_id'])) {
        $this->session->set_flashdata('error', 'Please add at least 1 item to the order.');
        admin_redirect('suppliers/addSupplyOrder_view');
      }

      // ADD LATER
      // CUSTOM ARRAY FILLED WITH CUSTOM ERROR MESSAGES
      // ADD OR PUSH ERROR MESSAGES TO THE ARRAY
      // IN THE END, IF THIS ARRAY LENGTH > 0, SET FLASH DATA AND REDIRECT
      // $error_messages = array('error1' => 'Some error 1', 'error2' => 'Some error 2');
      // $this->session->set_flashdata('error', $error_messages);
      // admin_redirect('suppliers/addSupplyOrder_view');

          // *******************************************************************
          // IF FORM VALIDATION SUCCESS, CONNECT WITH MODEL AND INSERT DATA
          // *******************************************************************

          // PREPARE DATA ARRAY THAT WILL BE PASSED TO THE MODEL

          // increment supply_order_number ...
          // find where we can set the initial value for those increments ...

          // IF ARRAY OF RECORDS TO BE INSERTED AS NEW RECORDS ON A TABLE,
          // SEND ARRAY AND MODEL SHOULD LOOP THROUGH EACH AND INSERT IT

          // SMA SERVICES
          // - SEND EMAIL
          // - SEND NOTIFICATION
          // - SEND SMS
          // - ADD EVENT IN CALENDAR
          // - PDF
          // - UPLOAD IMAGE, UPLOAD IMAGE GALLERY, UPLOAD ATTACHMENT


      // ***********************************************************************
      // TEST FORM INPUT VALUES
      // ***********************************************************************

      // echo "Hi from addSupplyOrder_viewLogic()";
      //
      // if ($this->input->post('supplier')) {
      //     $supplier = $this->input->post('supplier');
      //     echo '<br>';
      //     echo 'Supplier ID is: ' . $supplier;
      // }
      //
      // if ($this->input->post('msgToSupplier')) {
      //     $msgToSupplier = $this->input->post('msgToSupplier');
      //     echo '<br>';
      //     echo 'Message to Supplier is: ' . $msgToSupplier;
      // }
      //
      // if ($this->input->post('msgToReceiving')) {
      //     $msgToReceiving = $this->input->post('msgToReceiving');
      //     echo '<br>';
      //     echo 'Message to Receiving is: ' . $msgToReceiving;
      // }
      //
      // // $supplyOrderItems = $this->input->post('supplyOrderItem');
      // // if (is_array($supplyOrderItems)) {
      // //   foreach ($supplyOrderItems as $supplyOrderItem => $k) {
      // //     echo "supplyOrderItem is : " . $k . "<br/>";
      // //   }
      // // }
      //
      // // if ($this->input->post('supplyOrderItem')) {
      // //     $supplyOrderItem = $this->input->post('supplyOrderItem');
      // //     echo '<br>';
      // //     echo '• ' . $supplyOrderItem;
      // // }
      //
      // $i = isset($_POST['product_id']) ? sizeof($_POST['product_id']) : 0;
      // echo '<br>';
      // echo 'Size of product_id: ' . $i;
      // if (sizeof($_POST['product_id']) > 0) {
      //   echo '<br>';
      //   echo 'List of Supply Order Items:';
      //   echo '<br>';
      //
      //   for ($r = 0; $r < $i; $r++) {
      //       $product_id = $_POST['product_id'][$r];
      //       $product_name = $_POST['product_name'][$r];
      //       $product_quantity = $_POST['product_quantity'][$r];
      //       echo '<br>';
      //       echo '• ' . $product_id;
      //       echo '<br>';
      //       echo '• ' . $product_name;
      //       echo '<br>';
      //       echo '• ' . $product_quantity;
      //       echo '<br>';
      //   }
      // }

      // ***********************************************************************
      // SUPPLY ORDER INCREMENT LOGIC
      // ***********************************************************************

      // test insert

      // echo $this->suppliers_model->addSupplyOrder($dataToInsert);

      // add check to see if values are empty

      // BEDORE WRITING TO THE DATABASE OR BEFORE SENDING THIS ARRAY TO THE
      // DATABASE, MAKE SURE ALL ARRAY ELEMENTS CONTAIN VALUES AND THAT THEY ARE
      // NOT EMPTY, OTHERWSIE THE MODEL WONT INSERT THE NEW DATA TO THE DB TABLE
      // OK this wasnt the case, i had msgToSupplier instead of msg_to_supplier

      // DOCUMENTATION
      //  $dataToInsert = array(
      //      'supplier_id' => $this->input->post('supplier'),
      //  );
      //
      // Look at:
      // 'supplier_id' => $this->input->post('supplier'),
      //
      // 'supplier_id' is the database table field name
      // 'supplier' is the input name at $this->input->post('supplier')

      // INCREMENTING: SUPPLY ORDER NUMBER
      // get supply_orders-starter_number
      // get supply orders table row or records length
      // add ++

      $default_starter_supply_order_number = 1000;
      $supply_orders_count_total_rows = $this->db->count_all_results('NEW_supply_orders_count');
      $new_supply_order_number = 1;
      $last_supply_order_number = 0;

      // CHECK IF TABLE 'NEW_supply_orders_count' IS EMPTY OR HAS RESULTS

      if ($supply_orders_count_total_rows == 0) {

          // IF EMPTY, INIT SUPPLY ORDER NUMBER AND CREATE RECORD

          $supply_orders_count_data = array(
              'starter_supply_order_number' => $default_starter_supply_order_number,
              'last_supply_order_number' => $default_starter_supply_order_number,
          );
          $this->db->insert('NEW_supply_orders_count', $supply_orders_count_data);
          $new_supply_order_number = $default_starter_supply_order_number;

          // TEST
          // $this->session->set_flashdata('message', 'Result: ' . $supply_orders_count_total_rows);
          // admin_redirect('suppliers/addSupplyOrder_view');

      } else {

        // IF RECORD FOUND, GET LAST SUPPLY ORDER NUMBER SAVED AND UPDATE +1

        $last_supply_order_number = $this->db->get('NEW_supply_orders_count')->row()->last_supply_order_number;
        $new_supply_order_number = $last_supply_order_number + 1;
        $dataForSuppyOrderCount = array(
            'last_supply_order_number' => $new_supply_order_number,
        );
        $this->db->update('NEW_supply_orders_count', $dataForSuppyOrderCount, array('starter_supply_order_number' => $default_starter_supply_order_number));

        // get last row...
        // $last_supply_order_number = $this->db->order_by('last_supply_order_number',"desc")
        //             ->limit(1)
        //             ->get('last_supply_order_number')
        //             ->row();

        // TEST
        // $this->session->set_flashdata('message', 'Result: ' . $new_supply_order_number);
        // admin_redirect('suppliers/addSupplyOrder_view');

      }

      // ***********************************************************************
      // MODEL DATABASE OPERATION RESULTS
      // ***********************************************************************

      $dataToInsert = array(
          'supplier_id' => $this->input->post('supplier'),
          'supply_order_number' => $new_supply_order_number,
          'message_to_supplier' => $this->input->post('msgToSupplier'),
          'message_to_receiving' => $this->input->post('msgToReceiving'),
          'created_at' => date('Y-m-d H:i:s'),
      );

      $supply_order_id = $this->suppliers_model->addSupplyOrder($dataToInsert);

      if ($supply_order_id == true) {
        // $this->session->set_flashdata('message', 'New Supply Order Added Successfully, OID: ' . $supply_order_id);
        // admin_redirect('suppliers/getSupplyOrders_view');
      } else {
        $this->session->set_flashdata('error', 'Something went wrong, please try again later.');
        admin_redirect('suppliers/addSupplyOrder_view');
      }

      // GRAB SUPPLY ORDER ITEMS

      $items = array();

      if (sizeof($_POST['product_id']) > 0) {

          $i =  sizeof($_POST['product_id']);

          for ($r = 0; $r < $i; $r++) {

              $item = array(
                  'supply_order_id' => $supply_order_id,
                  'product_id' => $_POST['product_id'][$r],
                  'quantity' => $_POST['product_quantity'][$r],
                  // 'product_name' => $_POST['product_name'][$r],
              );

              array_push($items, $item);
              // OR USE: // $items[] = $item;
          }

      }

      $supply_order_complete = $this->suppliers_model->addSupplyOrderItems($items);

      // if ($this->suppliers_model->addSupplyOrder($dataToInsert) == true) {
      if ($supply_order_complete == true) {
        $this->session->set_flashdata('message', 'New Supply Order Added Successfully');
        admin_redirect('suppliers/getSupplyOrders_view');
      } else {
        $this->session->set_flashdata('error', 'Something went wrong, please try again later.');
        // $this->session->set_flashdata('error', 'result is ' . sizeof($items));
        admin_redirect('suppliers/addSupplyOrder_view');
      }

      // ***********************************************************************
      // Image
      // ***********************************************************************

      if ($_FILES['supply_order_image']['size'] > 0) {

          $config['upload_path'] = $this->upload_path;
          $config['allowed_types'] = $this->image_types;
          $config['max_size'] = $this->allowed_file_size;
          $config['max_width'] = $this->Settings->iwidth;
          $config['max_height'] = $this->Settings->iheight;
          $config['overwrite'] = FALSE;
          $config['encrypt_name'] = TRUE;
          $config['max_filename'] = 25;
          $this->upload->initialize($config);
          if (!$this->upload->do_upload('supply_order_image')) {
              $error = $this->upload->display_errors();
              $this->session->set_flashdata('error', $error);
              admin_redirect("products/edit/" . $id);
          }
          $photo = $this->upload->file_name;
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

      // ***********************************************************************
      // Image Gallery
      // ***********************************************************************

      if ($_FILES['userfile']['name'][0] != "") {

          $config['upload_path'] = $this->upload_path;
          $config['allowed_types'] = $this->image_types;
          $config['max_size'] = $this->allowed_file_size;
          $config['max_width'] = $this->Settings->iwidth;
          $config['max_height'] = $this->Settings->iheight;
          $config['overwrite'] = FALSE;
          $config['encrypt_name'] = TRUE;
          $config['max_filename'] = 25;
          $files = $_FILES;
          $cpt = count($_FILES['userfile']['name']);
          for ($i = 0; $i < $cpt; $i++) {

              $_FILES['userfile']['name'] = $files['userfile']['name'][$i];
              $_FILES['userfile']['type'] = $files['userfile']['type'][$i];
              $_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$i];
              $_FILES['userfile']['error'] = $files['userfile']['error'][$i];
              $_FILES['userfile']['size'] = $files['userfile']['size'][$i];

              $this->upload->initialize($config);

              if (!$this->upload->do_upload()) {
                  $error = $this->upload->display_errors();
                  $this->session->set_flashdata('error', $error);
                  admin_redirect("products/edit/" . $id);
              } else {

                  $pho = $this->upload->file_name;

                  $photos[] = $pho;

                  $this->load->library('image_lib');
                  $config['image_library'] = 'gd2';
                  $config['source_image'] = $this->upload_path . $pho;
                  $config['new_image'] = $this->thumbs_path . $pho;
                  $config['maintain_ratio'] = TRUE;
                  $config['width'] = $this->Settings->twidth;
                  $config['height'] = $this->Settings->theight;

                  $this->image_lib->initialize($config);

                  if (!$this->image_lib->resize()) {
                      echo $this->image_lib->display_errors();
                  }

                  if ($this->Settings->watermark) {
                      $this->image_lib->clear();
                      $wm['source_image'] = $this->upload_path . $pho;
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
              }
          }
          $config = NULL;
      } else {
          $photos = NULL;
      }

      // ***********************************************************************
      // Attachments
      // ***********************************************************************

      if ($_FILES['document']['size'] > 0) {
          $this->load->library('upload');
          $config['upload_path'] = $this->digital_upload_path;
          $config['allowed_types'] = $this->digital_file_types;
          $config['max_size'] = $this->allowed_file_size;
          $config['overwrite'] = false;
          $config['encrypt_name'] = true;
          $this->upload->initialize($config);
          if (!$this->upload->do_upload('document')) {
              $error = $this->upload->display_errors();
              $this->session->set_flashdata('error', $error);
              redirect($_SERVER["HTTP_REFERER"]);
          }
          $photo = $this->upload->file_name;
          $data['attachment'] = $photo;
      }

      // ***********************************************************************
      // WRITE TO DB, MODELS
      // ***********************************************************************

      // NEW_supply_orders
      // Load all form inputs and write to table NEW_supply_orders

      // NEW_supply_order_items
      // List of Items for this Order
      // For each order item, create a new record in the database, in table NEW_supply_order_items

      // ***** IF ERROR ******************************************************
      // $this->session->set_flashdata('error', 'Could Not Send Supply Order, Try Again Later.');
      // admin_redirect('suppliers/addSupplyOrder_view');
      // ***** IF SUCCESS ********************************************************
      // $this->session->set_flashdata('message', 'Supply Order Sent Successfully');
      // admin_redirect('suppliers/getSupplyOrders_view');

    }

}
//
