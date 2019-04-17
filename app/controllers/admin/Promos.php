<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Promos extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            $this->sma->md('login');
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->lang->admin_load('promo', $this->Settings->user_language);
        $this->load->library('form_validation');
        $this->load->admin_model('promos_model');
    }

    function index($action = NULL)
    {
        $this->sma->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['action'] = $action;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('promos')));
        $meta = array('page_title' => lang('promos'), 'bc' => $bc);
        $this->page_construct('promos/index', $meta, $this->data);
    }

    function getPromos()
    {
        $this->sma->checkPermissions('index');

        $this->load->library('datatables');
        $this->datatables
            ->select("promos.id as id, promos.name, CONCAT(p2b.name, ' (', p2b.code, ')') as product2buy, CONCAT(p2g.name, ' (', p2g.code, ')') as product2get, promos.start_date, promos.end_date")
            ->from("promos")
            ->join("products as p2b", 'p2b.id=promos.product2buy', 'left')
            ->join("products as p2g", 'p2g.id=promos.product2get', 'left')
            ->add_column("Actions", "<div class=\"text-center\"><a class=\"tip\" title='" . $this->lang->line("edit_promo") . "' href='" . admin_url('promos/edit/$1') . "'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . $this->lang->line("delete_promo") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('promos/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
        //->unset_column('id');
        echo $this->datatables->generate();
    }

    function add()
    {
        $this->sma->checkPermissions(false, true);

        $this->form_validation->set_rules('name', $this->lang->line("name"), 'required');
        $this->form_validation->set_rules('product2buy', $this->lang->line("product2buy"), 'required');
        $this->form_validation->set_rules('product2get', $this->lang->line("product2get"), 'required');

        if ($this->form_validation->run() == true) {
            $data = array(
                'name' => $this->input->post('name'),
                'start_date' => $this->input->post('start_date') ? $this->sma->fsd(trim($this->input->post('start_date'))) : null,
                'end_date' => $this->input->post('end_date') ? $this->sma->fsd(trim($this->input->post('end_date'))) : null,
                'product2buy' => $this->input->post('product2buy'),
                'product2get' => $this->input->post('product2get'),
                'description' => $this->input->post('description'),
            );
        // $this->sma->print_arrays($data);
        } elseif ($this->input->post('add_promo')) {
            $this->session->set_flashdata('error', validation_errors());
            admin_redirect('promos/add');
        }


        if ($this->form_validation->run() == true && $this->promos_model->addPromo($data)) {
            $this->session->set_flashdata('message', $this->lang->line("promo_added"));
            admin_redirect("promos");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('add_promo')));
        $meta = array('page_title' => lang('add_promo'), 'bc' => $bc);
        $this->page_construct('promos/add', $meta, $this->data);
        }
    }

    function edit($id = NULL)
    {
        $this->sma->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $promo = $this->promos_model->getPromoByID($id);
        $this->form_validation->set_rules('name', $this->lang->line("name"), 'required');
        $this->form_validation->set_rules('product2buy', $this->lang->line("product2buy"), 'required');
        $this->form_validation->set_rules('product2get', $this->lang->line("product2get"), 'required');

        if ($this->form_validation->run() == true) {
            $data = array(
                'name' => $this->input->post('name'),
                'start_date' => $this->input->post('start_date') ? $this->sma->fsd(trim($this->input->post('start_date'))) : null,
                'end_date' => $this->input->post('end_date') ? $this->sma->fsd(trim($this->input->post('end_date'))) : null,
                'product2buy' => $this->input->post('product2buy'),
                'product2get' => $this->input->post('product2get'),
                'description' => $this->input->post('description'),
            );
        } elseif ($this->input->post('edit_promo')) {
            $this->session->set_flashdata('error', validation_errors());
            admin_redirect('promos/edit/'+$id);
        }

        if ($this->form_validation->run() == true && $this->promos_model->updatePromo($id, $data)) {
            $this->session->set_flashdata('message', $this->lang->line("promo_updated"));
            admin_redirect("promos");
        } else {
            $p2b = $this->site->getProductByID($promo->product2buy);
            $p2g = $this->site->getProductByID($promo->product2get);
            $promo->p2b = $p2b->name.'('.$p2b->code.')';
            $promo->p2g = $p2g->name.'('.$p2g->code.')';
            $this->data['promo'] = $promo;
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('edit_promo')));
        $meta = array('page_title' => lang('edit_promo'), 'bc' => $bc);
        $this->page_construct('promos/edit', $meta, $this->data);
        }
    }


    function delete($id = NULL)
    {
        $this->sma->checkPermissions(NULL, TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->promos_model->deletePromo($id)) {
            $this->sma->send_json(array('error' => 0, 'msg' => lang("promo_deleted")));
        }
    }

    function suggestions($term = NULL, $limit = NULL)
    {
        $this->sma->checkPermissions('index');

        if ($this->input->get('term')) {
            $term = $this->input->get('term', TRUE);
        }
        $limit = $this->input->get('limit', TRUE);
        $rows['results'] = $this->promos_model->getPromoSuggestions($term, $limit);
        $this->sma->send_json($rows);
    }

    // function promo_actions()
    // {
    //     if (!$this->Owner && !$this->GP['bulk_actions']) {
    //         $this->session->set_flashdata('warning', lang('access_denied'));
    //         redirect($_SERVER["HTTP_REFERER"]);
    //     }

    //     $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

    //     if ($this->form_validation->run() == true) {

    //         if (!empty($_POST['val'])) {
    //             if ($this->input->post('form_action') == 'delete') {
    //                 $this->sma->checkPermissions('delete');
    //                 $error = false;
    //                 foreach ($_POST['val'] as $id) {
    //                     if (!$this->promos_model->deletePromo($id)) {
    //                         $error = true;
    //                     }
    //                 }
    //                 if ($error) {
    //                     $this->session->set_flashdata('warning', lang('promo_x_deleted_have_sales'));
    //                 } else {
    //                     $this->session->set_flashdata('message', $this->lang->line("promo_deleted"));
    //                 }
    //                 redirect($_SERVER["HTTP_REFERER"]);
    //             }

    //             if ($this->input->post('form_action') == 'export_excel') {

    //                 $this->load->library('excel');
    //                 $this->excel->setActiveSheetIndex(0);
    //                 $this->excel->getActiveSheet()->setTitle(lang('promo'));
    //                 $this->excel->getActiveSheet()->SetCellValue('A1', lang('company'));
    //                 $this->excel->getActiveSheet()->SetCellValue('B1', lang('name'));
    //                 $this->excel->getActiveSheet()->SetCellValue('C1', lang('phone'));
    //                 $this->excel->getActiveSheet()->SetCellValue('D1', lang('email'));
    //                 $this->excel->getActiveSheet()->SetCellValue('E1', lang('city'));

    //                 $row = 2;
    //                 foreach ($_POST['val'] as $id) {
    //                     $customer = $this->site->getCompanyByID($id);
    //                     $this->excel->getActiveSheet()->SetCellValue('A' . $row, $customer->company);
    //                     $this->excel->getActiveSheet()->SetCellValue('B' . $row, $customer->name);
    //                     $this->excel->getActiveSheet()->SetCellValue('C' . $row, $customer->phone);
    //                     $this->excel->getActiveSheet()->SetCellValue('D' . $row, $customer->email);
    //                     $this->excel->getActiveSheet()->SetCellValue('E' . $row, $customer->city);
    //                     $row++;
    //                 }

    //                 $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    //                 $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    //                 $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    //                 $filename = 'promo_' . date('Y_m_d_H_i_s');
    //                 $this->load->helper('excel');
    //                 create_excel($this->excel, $filename);
    //             }
    //         } else {
    //             $this->session->set_flashdata('error', $this->lang->line("no_promo_selected"));
    //             redirect($_SERVER["HTTP_REFERER"]);
    //         }
    //     } else {
    //         $this->session->set_flashdata('error', validation_errors());
    //         redirect($_SERVER["HTTP_REFERER"]);
    //     }
    // }

}
