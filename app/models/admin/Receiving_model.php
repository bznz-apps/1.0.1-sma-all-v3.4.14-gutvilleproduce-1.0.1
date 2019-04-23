<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Receiving_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    // *************************************************************************
    // MANIFESTS
    // *************************************************************************

    public function addManifest($data)
    {
        if ($this->db->insert('NEW_supply_order_manifests', $data)) {  // 'NEW_receiving_manifests'
            $insert_id = $this->db->insert_id();
            return  $insert_id;
        }
        return false;
    }

    public function getManifestByID($id)
    {
        $q = $this->db->get_where('NEW_supply_order_manifests', array('id' => $id), 1);  // 'NEW_receiving_manifests'
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllManifests()
    {
        $q = $this->db->get('NEW_supply_order_manifests'); // 'NEW_receiving_manifests'
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function editManifest($id)
    {
    }

    public function deleteManifest($id)
    {
        if ($this->db->delete('NEW_supply_order_manifests', array('id' => $id))) {  // 'NEW_receiving_manifests'
            return true;
        }
        return FALSE;
    }

    // *************************************************************************
    // RECEIVING REPORTS
    // *************************************************************************

    public function addReceivingReport($data)
    {
        if ($this->db->insert('NEW_receiving_reports', $data)) {
            $insert_id = $this->db->insert_id();
            return  $insert_id;
        }
        return false;
    }

    public function getReceivingReportByID($id)
    {
        $q = $this->db->get_where('NEW_receiving_reports', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllReceivingReports()
    {
        $q = $this->db->get('NEW_receiving_reports');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function editReceivingReport($id)
    {
    }

    public function deleteReceivingReport($id)
    {
        if ($this->db->delete('NEW_receiving_reports', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

}
