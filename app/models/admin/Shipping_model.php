<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Shipping_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    // *************************************************************************
    // PICK UP ORDERS
    // *************************************************************************

    public function addPickUpOrder($data)
    {
        if ($this->db->insert('NEW_pickup_orders', $data)) {
            $insert_id = $this->db->insert_id();
            return  $insert_id;
        }
        return false;
    }

    public function addPickUpOrderItems($items)
    {
        if (!empty($items)) {
            foreach ($items as $item) {
              $this->db->insert('NEW_pickup_order_item', $item);
            }
            return true;
        }
        return false;
    }

    public function getPickUpOrderByID($id)
    {
        $q = $this->db->get_where('NEW_pickup_orders', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllPickUpOrders()
    {
        $q = $this->db->get('NEW_pickup_orders');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function editPickUpOrder($id)
    {
    }

    public function deletePickUpOrder($id)
    {
        if ($this->db->delete('NEW_pickup_orders', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    // *************************************************************************
    // BILLS OF LADING
    // *************************************************************************

    public function addRack($data)
    {
        if ($this->db->insert('NEW_racks', $data)) {
            $insert_id = $this->db->insert_id();
            return  $insert_id;
        }
        return false;
    }

    public function getRackByID($id)
    {
        $q = $this->db->get_where('NEW_racks', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllRacks()
    {
        $q = $this->db->get('NEW_racks');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function editRack($id)
    {
    }

    public function deleteRack($id)
    {
        if ($this->db->delete('NEW_racks', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

}
