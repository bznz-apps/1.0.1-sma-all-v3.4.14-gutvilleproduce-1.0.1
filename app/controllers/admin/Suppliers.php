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

        if ($this->Customer || $this->Supplier) { // || Any Other User Group That Cant Access This Page URLs
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
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

    function getSupplyOrders()
    {
      $this->page_construct('suppliers/list_of_supply_orders', $meta, $this->data);
    }

    function getSupplyOrdersLogic()
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
          ->select($this->db->dbprefix('NEW_supply_orders') . ".id as id, supply_order_number, " . $this->db->dbprefix('companies') . ".company as supplier_id, " . "created_at")
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


        //->unset_column('id');
        echo $this->datatables->generate();
    }

    function previewSupplyOrder($id) {
      echo "Preview Supply Order ID: " . $id;
    }
    function editSupplyOrder($id) {
      echo "Edit Supply Order ID: " . $id;
    }


    function addSupplyOrder()
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
      // if ($this->form_validation->run() == true) {
      //
      //     $reference = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('so');
      //     if ($this->Owner || $this->Admin) {
      //         $date = $this->sma->fld(trim($this->input->post('date')));
      //     } else {
      //         $date = date('Y-m-d H:i:s');
      //     }
      //     $warehouse_id = $this->input->post('warehouse');
      //     $customer_id = $this->input->post('customer');
      //     $biller_id = $this->input->post('biller');
      //     $total_items = $this->input->post('total_items');
      //     $sale_status = $this->input->post('sale_status');
      //     $payment_status = $this->input->post('payment_status');
      //     $payment_term = $this->input->post('payment_term');
      //     $due_date = $payment_term ? date('Y-m-d', strtotime('+' . $payment_term . ' days', strtotime($date))) : null;
      //     $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
      //     $customer_details = $this->site->getCompanyByID($customer_id);
      //     $customer = !empty($customer_details->company) && $customer_details->company != '-' ? $customer_details->company : $customer_details->name;
      //     $biller_details = $this->site->getCompanyByID($biller_id);
      //     $biller = !empty($biller_details->company) && $biller_details->company != '-' ? $biller_details->company : $biller_details->name;
      //     $note = $this->sma->clear_tags($this->input->post('note'));
      //     $staff_note = $this->sma->clear_tags($this->input->post('staff_note'));
      //     $quote_id = $this->input->post('quote_id') ? $this->input->post('quote_id') : null;
      //
      //     $total = 0;
      //     $product_tax = 0;
      //     $product_discount = 0;
      //     $digital = FALSE;
      //     $gst_data = [];
      //     $total_cgst = $total_sgst = $total_igst = 0;
      //     $i = isset($_POST['product_code']) ? sizeof($_POST['product_code']) : 0;
      //     for ($r = 0; $r < $i; $r++) {
      //         $item_id = $_POST['product_id'][$r];
      //         $item_type = $_POST['product_type'][$r];
      //         $item_code = $_POST['product_code'][$r];
      //         $item_name = $_POST['product_name'][$r];
      //         $item_option = isset($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' && $_POST['product_option'][$r] != 'null' ? $_POST['product_option'][$r] : null;
      //         $real_unit_price = $this->sma->formatDecimal($_POST['real_unit_price'][$r]);
      //         $unit_price = $this->sma->formatDecimal($_POST['unit_price'][$r]);
      //         $item_unit_quantity = $_POST['quantity'][$r];
      //         $item_serial = isset($_POST['serial'][$r]) ? $_POST['serial'][$r] : '';
      //         $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : null;
      //         $item_discount = isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : null;
      //         $item_unit = $_POST['product_unit'][$r];
      //         $item_quantity = $_POST['product_base_quantity'][$r];
      //
      //         if (isset($item_code) && isset($real_unit_price) && isset($unit_price) && isset($item_quantity)) {
      //             $product_details = $item_type != 'manual' ? $this->sales_model->getProductByCode($item_code) : null;
      //             // $unit_price = $real_unit_price;
      //             if ($item_type == 'digital') {
      //                 $digital = TRUE;
      //             }
      //             $pr_discount = $this->site->calculateDiscount($item_discount, $unit_price);
      //             $unit_price = $this->sma->formatDecimal($unit_price - $pr_discount);
      //             $item_net_price = $unit_price;
      //             $pr_item_discount = $this->sma->formatDecimal($pr_discount * $item_unit_quantity);
      //             $product_discount += $pr_item_discount;
      //             $pr_item_tax = $item_tax = 0;
      //             $tax = "";
      //
      //             if (isset($item_tax_rate) && $item_tax_rate != 0) {
      //
      //                 $tax_details = $this->site->getTaxRateByID($item_tax_rate);
      //                 $ctax = $this->site->calculateTax($product_details, $tax_details, $unit_price);
      //                 $item_tax = $ctax['amount'];
      //                 $tax = $ctax['tax'];
      //                 if (!$product_details || (!empty($product_details) && $product_details->tax_method != 1)) {
      //                     $item_net_price = $unit_price - $item_tax;
      //                 }
      //                 $pr_item_tax = $this->sma->formatDecimal(($item_tax * $item_unit_quantity), 4);
      //                 if ($this->Settings->indian_gst && $gst_data = $this->gst->calculteIndianGST($pr_item_tax, ($biller_details->state == $customer_details->state), $tax_details)) {
      //                     $total_cgst += $gst_data['cgst'];
      //                     $total_sgst += $gst_data['sgst'];
      //                     $total_igst += $gst_data['igst'];
      //                 }
      //             }
      //
      //             $product_tax += $pr_item_tax;
      //             $subtotal = (($item_net_price * $item_unit_quantity) + $pr_item_tax);
      //             $unit = $this->site->getUnitByID($item_unit);
      //
      //             $product = array(
      //                 'product_id' => $item_id,
      //                 'product_code' => $item_code,
      //                 'product_name' => $item_name,
      //                 'product_type' => $item_type,
      //                 'option_id' => $item_option,
      //                 'net_unit_price' => $item_net_price,
      //                 'unit_price' => $this->sma->formatDecimal($item_net_price + $item_tax),
      //                 'quantity' => $item_quantity,
      //                 'product_unit_id' => $unit ? $unit->id : NULL,
      //                 'product_unit_code' => $unit ? $unit->code : NULL,
      //                 'unit_quantity' => $item_unit_quantity,
      //                 'warehouse_id' => $warehouse_id,
      //                 'item_tax' => $pr_item_tax,
      //                 'tax_rate_id' => $item_tax_rate,
      //                 'tax' => $tax,
      //                 'discount' => $item_discount,
      //                 'item_discount' => $pr_item_discount,
      //                 'subtotal' => $this->sma->formatDecimal($subtotal),
      //                 'serial_no' => $item_serial,
      //                 'real_unit_price' => $real_unit_price,
      //             );
      //
      //             $products[] = ($product + $gst_data);
      //             $total += $this->sma->formatDecimal(($item_net_price * $item_unit_quantity), 4);
      //         }
      //     }
      //     if (empty($products)) {
      //         $this->form_validation->set_rules('product', lang("order_items"), 'required');
      //     } else {
      //         krsort($products);
      //     }
      //
      //     $order_discount = $this->site->calculateDiscount($this->input->post('order_discount'), ($total + $product_tax));
      //     $total_discount = $this->sma->formatDecimal(($order_discount + $product_discount), 4);
      //     $order_tax = $this->site->calculateOrderTax($this->input->post('order_tax'), ($total + $product_tax - $order_discount));
      //     $total_tax = $this->sma->formatDecimal(($product_tax + $order_tax), 4);
      //     $grand_total = $this->sma->formatDecimal(($total + $total_tax + $this->sma->formatDecimal($shipping) - $order_discount), 4);
      //     $data = array('date' => $date,
      //         'reference_no' => $reference,
      //         'customer_id' => $customer_id,
      //         'customer' => $customer,
      //         'biller_id' => $biller_id,
      //         'biller' => $biller,
      //         'warehouse_id' => $warehouse_id,
      //         'note' => $note,
      //         'staff_note' => $staff_note,
      //         'total' => $total,
      //         'product_discount' => $product_discount,
      //         'order_discount_id' => $this->input->post('order_discount'),
      //         'order_discount' => $order_discount,
      //         'total_discount' => $total_discount,
      //         'product_tax' => $product_tax,
      //         'order_tax_id' => $this->input->post('order_tax'),
      //         'order_tax' => $order_tax,
      //         'total_tax' => $total_tax,
      //         'shipping' => $this->sma->formatDecimal($shipping),
      //         'grand_total' => $grand_total,
      //         'total_items' => $total_items,
      //         'sale_status' => $sale_status,
      //         'payment_status' => $payment_status,
      //         'payment_term' => $payment_term,
      //         'due_date' => $due_date,
      //         'paid' => 0,
      //         'created_by' => $this->session->userdata('user_id'),
      //         'hash' => hash('sha256', microtime() . mt_rand()),
      //     );
      //     if ($this->Settings->indian_gst) {
      //         $data['cgst'] = $total_cgst;
      //         $data['sgst'] = $total_sgst;
      //         $data['igst'] = $total_igst;
      //     }
      //
      //     if ($payment_status == 'partial' || $payment_status == 'paid') {
      //         if ($this->input->post('paid_by') == 'deposit') {
      //             if ( ! $this->site->check_customer_deposit($customer_id, $this->input->post('amount-paid'))) {
      //                 $this->session->set_flashdata('error', lang("amount_greater_than_deposit"));
      //                 redirect($_SERVER["HTTP_REFERER"]);
      //             }
      //         }
      //         if ($this->input->post('paid_by') == 'gift_card') {
      //             $gc = $this->site->getGiftCardByNO($this->input->post('gift_card_no'));
      //             $amount_paying = $grand_total >= $gc->balance ? $gc->balance : $grand_total;
      //             $gc_balance = $gc->balance - $amount_paying;
      //             $payment = array(
      //                 'date' => $date,
      //                 'reference_no' => $this->input->post('payment_reference_no'),
      //                 'amount' => $this->sma->formatDecimal($amount_paying),
      //                 'paid_by' => $this->input->post('paid_by'),
      //                 'cheque_no' => $this->input->post('cheque_no'),
      //                 'cc_no' => $this->input->post('gift_card_no'),
      //                 'cc_holder' => $this->input->post('pcc_holder'),
      //                 'cc_month' => $this->input->post('pcc_month'),
      //                 'cc_year' => $this->input->post('pcc_year'),
      //                 'cc_type' => $this->input->post('pcc_type'),
      //                 'created_by' => $this->session->userdata('user_id'),
      //                 'note' => $this->input->post('payment_note'),
      //                 'type' => 'received',
      //                 'gc_balance' => $gc_balance,
      //             );
      //         } else {
      //             $payment = array(
      //                 'date' => $date,
      //                 'reference_no' => $this->input->post('payment_reference_no'),
      //                 'amount' => $this->sma->formatDecimal($this->input->post('amount-paid')),
      //                 'paid_by' => $this->input->post('paid_by'),
      //                 'cheque_no' => $this->input->post('cheque_no'),
      //                 'cc_no' => $this->input->post('pcc_no'),
      //                 'cc_holder' => $this->input->post('pcc_holder'),
      //                 'cc_month' => $this->input->post('pcc_month'),
      //                 'cc_year' => $this->input->post('pcc_year'),
      //                 'cc_type' => $this->input->post('pcc_type'),
      //                 'created_by' => $this->session->userdata('user_id'),
      //                 'note' => $this->input->post('payment_note'),
      //                 'type' => 'received',
      //             );
      //         }
      //     } else {
      //         $payment = array();
      //     }
      //
      //     if ($_FILES['document']['size'] > 0) {
      //         $this->load->library('upload');
      //         $config['upload_path'] = $this->digital_upload_path;
      //         $config['allowed_types'] = $this->digital_file_types;
      //         $config['max_size'] = $this->allowed_file_size;
      //         $config['overwrite'] = false;
      //         $config['encrypt_name'] = true;
      //         $this->upload->initialize($config);
      //         if (!$this->upload->do_upload('document')) {
      //             $error = $this->upload->display_errors();
      //             $this->session->set_flashdata('error', $error);
      //             redirect($_SERVER["HTTP_REFERER"]);
      //         }
      //         $photo = $this->upload->file_name;
      //         $data['attachment'] = $photo;
      //     }
      //
      //     // $this->sma->print_arrays($data, $products, $payment);
      // }
      //
      // if ($this->form_validation->run() == true && $this->sales_model->addSale($data, $products, $payment)) {
      //     $this->session->set_userdata('remove_slls', 1);
      //     if ($quote_id) {
      //         $this->db->update('quotes', array('status' => 'completed'), array('id' => $quote_id));
      //     }
      //     $this->session->set_flashdata('message', lang("sale_added"));
      //     admin_redirect("sales");
      // } else {
      //
      //     if ($quote_id || $sale_id) {
      //         if ($quote_id) {
      //             $this->data['quote'] = $this->sales_model->getQuoteByID($quote_id);
      //             $items = $this->sales_model->getAllQuoteItems($quote_id);
      //         } elseif ($sale_id) {
      //             $this->data['quote'] = $this->sales_model->getInvoiceByID($sale_id);
      //             $items = $this->sales_model->getAllInvoiceItems($sale_id);
      //         }
      //         krsort($items);
      //         $c = rand(100000, 9999999);
      //         foreach ($items as $item) {
      //             $row = $this->site->getProductByID($item->product_id);
      //             if (!$row) {
      //                 $row = json_decode('{}');
      //                 $row->tax_method = 0;
      //             } else {
      //                 unset($row->cost, $row->details, $row->product_details, $row->image, $row->barcode_symbology, $row->cf1, $row->cf2, $row->cf3, $row->cf4, $row->cf5, $row->cf6, $row->supplier1price, $row->supplier2price, $row->cfsupplier3price, $row->supplier4price, $row->supplier5price, $row->supplier1, $row->supplier2, $row->supplier3, $row->supplier4, $row->supplier5, $row->supplier1_part_no, $row->supplier2_part_no, $row->supplier3_part_no, $row->supplier4_part_no, $row->supplier5_part_no);
      //             }
      //             $row->quantity = 0;
      //             $pis = $this->site->getPurchasedItems($item->product_id, $item->warehouse_id, $item->option_id);
      //             if ($pis) {
      //                 foreach ($pis as $pi) {
      //                     $row->quantity += $pi->quantity_balance;
      //                 }
      //             }
      //             $row->id = $item->product_id;
      //             $row->code = $item->product_code;
      //             $row->name = $item->product_name;
      //             $row->type = $item->product_type;
      //             $row->qty = $item->quantity;
      //             $row->base_quantity = $item->quantity;
      //             $row->base_unit = $row->unit ? $row->unit : $item->product_unit_id;
      //             $row->base_unit_price = $row->price ? $row->price : $item->unit_price;
      //             $row->unit = $item->product_unit_id;
      //             $row->qty = $item->unit_quantity;
      //             $row->discount = $item->discount ? $item->discount : '0';
      //             $row->price = $this->sma->formatDecimal($item->net_unit_price + $this->sma->formatDecimal($item->item_discount / $item->quantity));
      //             $row->unit_price = $row->tax_method ? $item->unit_price + $this->sma->formatDecimal($item->item_discount / $item->quantity) + $this->sma->formatDecimal($item->item_tax / $item->quantity) : $item->unit_price + ($item->item_discount / $item->quantity);
      //             $row->real_unit_price = $item->real_unit_price;
      //             $row->tax_rate = $item->tax_rate_id;
      //             $row->serial = '';
      //             $row->option = $item->option_id;
      //             $options = $this->sales_model->getProductOptions($row->id, $item->warehouse_id);
      //             if ($options) {
      //                 $option_quantity = 0;
      //                 foreach ($options as $option) {
      //                     $pis = $this->site->getPurchasedItems($row->id, $item->warehouse_id, $item->option_id);
      //                     if ($pis) {
      //                         foreach ($pis as $pi) {
      //                             $option_quantity += $pi->quantity_balance;
      //                         }
      //                     }
      //                     if ($option->quantity > $option_quantity) {
      //                         $option->quantity = $option_quantity;
      //                     }
      //                 }
      //             }
      //             $combo_items = false;
      //             if ($row->type == 'combo') {
      //                 $combo_items = $this->sales_model->getProductComboItems($row->id, $item->warehouse_id);
      //             }
      //             $units = $this->site->getUnitsByBUID($row->base_unit);
      //             $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
      //             $ri = $this->Settings->item_addition ? $row->id : $c;
      //
      //             $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")",
      //                     'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'units' => $units, 'options' => $options);
      //             $c++;
      //         }
      //         $this->data['quote_items'] = json_encode($pr);
      //     }
      //
      //
      //
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

          $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('sales'), 'page' => "Supply Orders"), array('link' => '#', 'page' => "Add Supply Order"));
          $meta = array('page_title' => "Add Supply Order", 'bc' => $bc);

          $this->data['products'] = $this->site->getAllProducts();
          $this->page_construct('suppliers/add_supply_order', $meta, $this->data);
      // }

    }

    ////////////////////////////////////////////////////////////////////////////
    // add example

    function addExample()
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

    ////////////////////////////////////////////////////////////////////////////

    function addSupplyOrderLogic()
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
          admin_redirect('suppliers/addSupplyOrder');
      }

      // IF VALIDATION ERROR, SET ERROR FLASH MESSAGE AND REDIRECT TO FORM VIEW
      // IF VALIDATION SUCCESS, PROCEED TO WORK WITH THE DATABASE MODEL

      // ***********************************************************************
      // CUSTOM FORM INPUTS VALIDATION
      // ***********************************************************************

      // if (empty($_POST['supplier'])) {
      //   $this->session->set_flashdata('error', 'Please add a Supplier');
      //   admin_redirect('suppliers/addSupplyOrder');
      // }

      // ADD LATER
      // Use this to create an if statement that checks if 'product_id' is not empty
      // and if it is not empty check that size is > 0
      // isset($_POST['product_id']) ? sizeof($_POST['product_id']) : 0

      if (empty($_POST['product_id'])) {
        $this->session->set_flashdata('error', 'Please add at least 1 item to the order.');
        admin_redirect('suppliers/addSupplyOrder');
      }

      // ADD LATER
      // CUSTOM ARRAY FILLED WITH CUSTOM ERROR MESSAGES
      // ADD OR PUSH ERROR MESSAGES TO THE ARRAY
      // IN THE END, IF THIS ARRAY LENGTH > 0, SET FLASH DATA AND REDIRECT
      // $error_messages = array('error1' => 'Some error 1', 'error2' => 'Some error 2');
      // $this->session->set_flashdata('error', $error_messages);
      // admin_redirect('suppliers/addSupplyOrder');

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

      // echo "Hi from addSupplyOrderLogic()";
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
      // MODEL DATABASE OPERATION RESULTS
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
      // $new_supply_order_number = $starter_supply_order_number + $supply_orders_total_rows + 1;
      $new_supply_order_number = 1;

      if ($supply_orders_count_total_rows == 0) {
          $supply_orders_count_data = array(
              'starter_supply_order_number' => $default_starter_supply_order_number,
              'last_supply_order_number' => $default_starter_supply_order_number,
          );
          $this->db->insert('NEW_supply_orders_count', $supply_orders_count_data);
          $new_supply_order_number = $default_starter_supply_order_number;
      } else {
          // get last row on 'NEW_supply_orders_count'
          // pull off the 'last_supply_order_number'
          // set $new_supply_order_number = 'last_supply_order_number' + 1

          // $last_row = $this->db->last_row('NEW_supply_orders_count');
          // $last_supply_order_number = $last_row->last_supply_order_number;
          $last_supply_order_number = $this->db->select('last_supply_order_number')->from('NEW_supply_orders_count')->limit(1)->order_by('last_supply_order_number','DESC')->get()->row();
          $new_supply_order_number = $last_supply_order_number + 1;
      }

      // get table '$new_supply_orders_count'
      // if total rows === 0
      //    set initial number to the one on field 'starter_supply_order_number'
      //        if no rows or no 'starter_supply_order_number', then set 'starter_supply_order_number' to 1000
      //    set initial number 'starter_supply_order_number' + 1
      //    save this number in 'last_supply_order_number'
      // else
      // set initial number to the one on field 'last_supply_order_number' + 1

      $dataToInsert = array(
          'supplier_id' => $this->input->post('supplier'),
          'supply_order_number' => $new_supply_order_number,
          'message_to_supplier' => $this->input->post('msgToSupplier'),
          'message_to_receiving' => $this->input->post('msgToReceiving'),
          'created_at' => date('Y-m-d H:i:s'),
      );

      // $solution = $this->suppliers_model->addSupplyOrder($dataToInsert);
      //
      // echo $solution;
      // // $this->session->set_flashdata('message', 'New Supply Order Added Successfully');
      // admin_redirect('suppliers/getSupplyOrders');

      if ($this->suppliers_model->addSupplyOrder($dataToInsert) == true) {
        $this->session->set_flashdata('message', 'New Supply Order Added Successfully');
        admin_redirect('suppliers/getSupplyOrders');
      } else {
        // echo $this->suppliers_model->addSupplyOrder($dataToInsert);
        $this->session->set_flashdata('error', 'Something went wrong, please try again later.');
        admin_redirect('suppliers/addSupplyOrder');
      }

      // ***********************************************************************
      // Image
      // ***********************************************************************

      if ($_FILES['product_image']['size'] > 0) {

          $config['upload_path'] = $this->upload_path;
          $config['allowed_types'] = $this->image_types;
          $config['max_size'] = $this->allowed_file_size;
          $config['max_width'] = $this->Settings->iwidth;
          $config['max_height'] = $this->Settings->iheight;
          $config['overwrite'] = FALSE;
          $config['encrypt_name'] = TRUE;
          $config['max_filename'] = 25;
          $this->upload->initialize($config);
          if (!$this->upload->do_upload('product_image')) {
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
      // admin_redirect('suppliers/addSupplyOrder');
      // ***** IF SUCCESS ********************************************************
      // $this->session->set_flashdata('message', 'Supply Order Sent Successfully');
      // admin_redirect('suppliers/getSupplyOrders');

    }

    function getManifests()
    {
      $this->page_construct('suppliers/list_of_manifests', $meta, $this->data);
    }

    function addManifest()
    {
      $this->page_construct('suppliers/add_manifest', $meta, $this->data);
    }

    function getPallets()
    {
      $this->page_construct('suppliers/list_of_pallets', $meta, $this->data);
    }

    function addPallet()
    {
      $this->page_construct('suppliers/add_pallet', $meta, $this->data);
    }

    function printPalletBarcodeLabel()
    {
      $this->page_construct('suppliers/print_pallet_barcode_label', $meta, $this->data);
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

}
