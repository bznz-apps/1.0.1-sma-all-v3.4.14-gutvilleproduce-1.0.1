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

    public function addBOL($data)
    {
        if ($this->db->insert('NEW_bills_of_lading', $data)) {
            $insert_id = $this->db->insert_id();
            return  $insert_id;
        }
        return false;
    }

    public function addBOLItems($items)
    {
        if (!empty($items)) {
            foreach ($items as $item) {
              $this->db->insert('NEW_bill_of_lading_item', $item);
            }
            return true;
        }
        return false;
    }

    public function getBOLByID($id)
    {
        $q = $this->db->get_where('NEW_bills_of_lading', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllBOL()
    {
        $q = $this->db->get('NEW_bills_of_lading');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function editBOL($id)
    {
    }

    public function deleteBOL($id)
    {
        if ($this->db->delete('NEW_bills_of_lading', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

}
