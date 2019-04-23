<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Quality_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    // *************************************************************************
    // QUALITY REPORTS
    // *************************************************************************

    public function addQualityReport($data)
    {
        if ($this->db->insert('NEW_quality_control_reports', $data)) {
            $insert_id = $this->db->insert_id();
            return  $insert_id;
        }
        return false;
    }

    public function addQualityReportItems($items)
    {
        if (!empty($items)) {
            foreach ($items as $item) {
              $this->db->insert('NEW_quality_control_report_item', $item);
            }
            return true;
        }
        return false;
    }

    public function getQualityReportByID($id)
    {
        $q = $this->db->get_where('NEW_quality_control_reports', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllQualityReports()
    {
        $q = $this->db->get('NEW_quality_control_reports');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function editQualityReport($id)
    {
    }

    public function deleteQualityReport($id)
    {
        if ($this->db->delete('NEW_quality_control_reports', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

}
