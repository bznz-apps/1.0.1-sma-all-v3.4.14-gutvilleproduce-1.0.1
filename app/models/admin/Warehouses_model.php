<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Warehouses_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function updateProductQty($product_id, $quantity)
    {
        // GET PRODUCT QTY -----------------------------------------------------

        // get product_id and qty
        // sum the received qty and save as the new qty

        $q_product_qty = 0;
        $q_products = $this->db->get_where('products', array('id' => $product_id));
        if ($q_products->num_rows() > 0) {
            // return $q_products->row();
            $q_products_results = $q_products->row();
            $q_product_qty = $q_products_results->quantity;
        }
        // return FALSE;
        $new_product_qty = $q_product_qty + $quantity;

        // UPDATE WAREHOUSE TABLE

        $this->db->set('quantity', $new_product_qty);
        // $this->db->where('product_id', $product_id); // for single where condition, line below for multiple
        $this->db->where(array('id' => $product_id));
        $this->db->update('products');
        $result = $this->db->affected_rows();
    }
    public function updateWarehouseQty($warehouse_id, $product_id, $quantity)
    {
        // GET WAREHOUSE PRODUCT QTY -------------------------------------------

        // get warehouse_id and get product_id qty
        // sum the received qty and save as the new qty

        $q_warehouse_product_qty = 0;
        $q_warehouse_products = $this->db->get_where('warehouses_products', array('warehouse_id' => $warehouse_id, 'product_id' => $product_id));
        if ($q_warehouse_products->num_rows() > 0) {
            // return $q_warehouse_products->row();
            $q_warehouses_products_results = $q_warehouse_products->row();
            $q_warehouse_product_qty = $q_warehouses_products_results->quantity;
        }
        // return FALSE;
        $new_warehouse_product_qty = $q_warehouse_product_qty + $quantity;

        // UPDATE WAREHOUSE TABLE

        $this->db->set('quantity', $new_warehouse_product_qty);
        // $this->db->where('product_id', $product_id); // for single where condition, line below for multiple
        $this->db->where(array('warehouse_id' => $warehouse_id, 'product_id' => $product_id));
        $this->db->update('warehouses_products');
        $result =  $this->db->affected_rows();
    }

    // *************************************************************************
    // WAREHOUSES
    // *************************************************************************

    public function getAllWarehouses()
    {
        $q = $this->db->get('warehouses');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    // *************************************************************************
    // PALLETS
    // *************************************************************************

    public function addPallet($data)
    {
        if ($this->db->insert('NEW_pallets', $data)) {
            $insert_id = $this->db->insert_id();
            return  $insert_id;
        }
        return false;
    }

    public function addPalletItems($items)
    {
        if (!empty($items)) {
            foreach ($items as $item) {
              $this->db->insert('NEW_pallet_items', $item);
            }
            return true;
        }
        return false;
    }

    public function getPalletByID($id)
    {
        $q = $this->db->get_where('NEW_pallets', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllPallets()
    {
        $q = $this->db->get('NEW_pallets');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllPalletsByReceivingReportID($id)
    {
        $q = $this->db->get_where('NEW_pallets', array('receiving_report_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllPalletItemsByPalletID($id)
    {
        $q = $this->db->get_where('NEW_pallet_items', array('pallet_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function editPallet($id)
    {
    }

    public function deletePallet($id)
    {
        if ($this->db->delete('NEW_pallets', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    // *************************************************************************
    // RACKS
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
