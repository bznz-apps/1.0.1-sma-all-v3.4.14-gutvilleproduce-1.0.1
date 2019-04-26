<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update317 extends CI_Migration {

    public function up() {

      // -----------------------------------------------------------------------
      /*

      Reference Links:
      https://www.codeigniter.com/user_guide/database/forge.html?highlight=dbforge#adding-fields

      // Add New Table Sample
      // Add Table With All Type Of Fields, Restrictions, Functions, etc.
      // Add Update Existing Table By Adding New Fields Sample
      //  - Use add_column rather than add_field

      // ADD SAMPLE1 TABLE
      // ADD SAMPLE2 TABLE
      // ADD SAMPLE3 TABLE
      // ADD SAMPLE4 TABLE
      // ADD SAMPLE5 TABLE

      $this->dbforge->add_field(array(
        'blog_id' => array(
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
        ),
        'blog_title' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
        ),
        'blog_description' => array(
                'type' => 'TEXT',
                'null' => TRUE,
        ),

        // Not Working
        // 'created_at' => array(
        //   'type' => 'TIMESTAMP',
        //   'default' => 'CURRENT_TIMESTAMP'
        // ),
        // 'modified_at' => array(
        //   'type' => 'TIMESTAMP',
        //   'default' => 'CURRENT_TIMESTAMP'
        // ),

        // Test This
        // 'created_at' => array(
        //   'type' => 'TIMESTAMP',
        //   'default' => 'CURRENT_TIMESTAMP'
        // ),

        // Test it, supposedly working
        'created_at' => array(
          'type' => 'varchar',
          'constraint' => 250,
          'null' => true,
          'on update' => 'NOW()'
        ),
        // Working
        'test_enum' => array(
          'type' => 'ENUM("a","b","c")',
          'default' => 'a',
          'null' => TRUE,
        ),

        // MOST COMMON

        'id' => array(
          'type' => 'INT',
          'constraint' => 11,
          'unsigned' => TRUE,
          'auto_increment' => TRUE
        ),

        'other_id_not_required' => array(
          'type' => 'INT',
          'constraint' => 11,
          'null' => TRUE,
        ),

        'other_id_required' => array(
          'type' => 'INT',
          'constraint' => 11,
          'null' => TRUE,
        ),

        'created_at' => array(
          'type' => 'varchar',
          'constraint' => 250,
          'null' => true,
          'on update' => 'NOW()'
        ),

        'status_required' => array(
          'type' => 'ENUM("on_hold","incoming","received","rejected")',
          'default' => 'on_hold',
          'null' => TRUE,
        ),

        'comments_not_required' => array(
          'type' => 'TEXT',
          'null' => TRUE
        ),

        'image_not_required' => array(
          'type' => 'VARCHAR(255)',
          'collation' => 'utf8_general_ci',
          'null' => TRUE,
          // 'default' => null
        ),

        'attachment_not_required' => array(
          'type' => 'VARCHAR(55)',
          'collation' => 'utf8_general_ci',
          'null' => TRUE,
          // 'default' => null
        ),

      ));
      $this->dbforge->add_key('blog_id', TRUE);
      $this->dbforge->create_table('blog_table');

      */
      // // -----------------------------------------------------------------------
      // // NEW SAMPLE 1
      //
      // $this->dbforge->add_field(array(
      //     'id' => array(
      //       'type' => 'INT',
      //       'constraint' => 11,
      //       'unsigned' => TRUE,
      //       'auto_increment' => TRUE
      //     ),
      //
      //     // 'date' => array(
      //     //   'type' => 'TIMESTAMP',
      //     // ),
      //     // 'due_date' => array(
      //     //   'type' => 'DATE',
      //     // ),
      //     // 'start_date' => array(
      //     //   'type' => 'DATE',
      //     // ),
      //     // 'end_date' => array(
      //     //   'type' => 'DATE',
      //     // ),
      //
      //     'created_at' => array(
      //       'type' => 'TIMESTAMP',
      //       'constraint' => 250,
      //       'null' => true,
      //       'on update' => 'NOW()'
      //     ),
      //     'updated_at' => array(
      //       'type' => 'TIMESTAMP',
      //       'constraint' => 250,
      //       'null' => true,
      //       'on update' => 'NOW()'
      //     ),
      //
      //     'created_by' => array(
      //       'type' => 'INT',
      //       'constraint' => 11,
      //       'null' => TRUE,
      //     ),
      //     'updated_by' => array(
      //       'type' => 'INT',
      //       'constraint' => 11,
      //       'null' => TRUE,
      //     ),
      //
      //     'name' => array(
      //       'type' => 'VARCHAR',
      //       'constraint' => 100,
      //       'null' => TRUE,
      //     ),
      //     'email' => array(
      //       'type' => 'TEXT',
      //       'null' => TRUE,
      //     ),
      //
      //     'ref_id_1' => array(
      //       'type' => 'INT',
      //       'constraint' => 11,
      //       'null' => TRUE,
      //     ),
      //     'ref_id_2' => array(
      //       'type' => 'INT',
      //       'constraint' => 11,
      //       'null' => TRUE,
      //     ),
      //     'ref_id_3' => array(
      //       'type' => 'INT',
      //       'constraint' => 11,
      //       'null' => TRUE,
      //     ),
      //
      //     'internal_code_1' => array(
      //       'type' => 'VARCHAR',
      //       'constraint' => 11,
      //       'null' => TRUE,
      //     ),
      //     'internal_code_2' => array(
      //       'type' => 'VARCHAR',
      //       'constraint' => 11,
      //       'null' => TRUE,
      //     ),
      //     'internal_code_3' => array(
      //       'type' => 'VARCHAR',
      //       'constraint' => 11,
      //       'null' => TRUE,
      //     ),
      //
      //     'reference_number_1' => array(
      //       'type' => 'VARCHAR',
      //       'constraint' => 11,
      //       'null' => TRUE,
      //     ),
      //     'reference_number_2' => array(
      //       'type' => 'VARCHAR',
      //       'constraint' => 11,
      //       'null' => TRUE,
      //     ),
      //     'reference_number_3' => array(
      //       'type' => 'VARCHAR',
      //       'constraint' => 11,
      //       'null' => TRUE,
      //     ),
      //
      //     'status' => array(
      //       'type' => 'ENUM("status1","status2","status3","status4","status5")',
      //       'default' => 'status1',
      //       'null' => TRUE,
      //     ),
      //     'selectable1' => array(
      //       'type' => 'ENUM("selectable11","selectable12","selectable13","selectable14","selectable15")',
      //       'default' => 'selectable11',
      //       'null' => TRUE,
      //     ),
      //     'selectable2' => array(
      //       'type' => 'ENUM("selectable21","selectable22","selectable23","selectable24","selectable25")',
      //       'default' => 'selectable21',
      //       'null' => TRUE,
      //     ),
      //     'selectable3' => array(
      //       'type' => 'ENUM("selectable31","selectable32","selectable33","selectable34","selectable35")',
      //       'default' => 'selectable31',
      //       'null' => TRUE,
      //     ),
      //
      //     /*
      //       DESCRIPTION1
      //       DESCRIPTION2
      //       DESCRIPTION3
      //       IMAGE
      //       IMAGES
      //       FILE
      //       ATTACHMENT
      //       BOOL1
      //       BOOL2
      //       BOOL3
      //       SLUG
      //     */
      //
      //     'description' => array(
      //       'type' => 'TEXT',
      //       'null' => TRUE
      //     ),
      //
      // ));
      // $this->dbforge->add_key('id', TRUE);
      // $this->dbforge->create_table('NEW_sample1');

      //////////////////////////////////////////////////////////////////////////
      // NEW TABLES AND FIELDS
      //////////////////////////////////////////////////////////////////////////

      // -----------------------------------------------------------------------
      // SUPPLY ORDERS

      /*
          'sample_field' => array(
              'type' => 'INT',
              'constraint' => 11,
              'unsigned' => TRUE,
              'auto_increment' => TRUE,
              'null' => TRUE,
              'unique' => FALSE,
              'default' => 'Default Value Here',
              'first' => TRUE,
              'after' => 'another_field'
          ),
      */

      $this->dbforge->add_field(array(
          'id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
          ),
          'supply_order_number' => array(
            'type' => 'INT',
            'constraint' => 11,
            // 'unsigned' => TRUE,
            // 'default' => 1,
            // 'auto_increment' => TRUE,
            // 'null' => FALSE,
            // 'unique' => TRUE,
          ),
          'description' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'sender_status' => array(
            'type' => 'ENUM("draft","sent")',
            'default' => 'draft',
            'null' => TRUE,
          ),
          'receiver_status' => array(
            'type' => 'ENUM("received","processing","sent")',
            'default' => 'received',
            'null' => TRUE,
          ),
          'created_at' => array(
            'type' => 'varchar', // timestam?
            'constraint' => 250,
            'null' => true,
            'on update' => 'NOW()'
            // default 'CURRENT_TIMESTAMP'
          ),

          'supplier_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => TRUE,
          ),

          'message_to_supplier' => array(
            'type' => 'VARCHAR(1000)',
            'collation' => 'utf8_general_ci',
            'null' => TRUE,
            'default' => null
          ),
          'message_to_receiving' => array(
            'type' => 'VARCHAR(1000)',
            'collation' => 'utf8_general_ci',
            'null' => TRUE,
            'default' => null
          ),

          'image' => array(
            'type' => 'VARCHAR(255)',
            'collation' => 'utf8_general_ci',
            'null' => TRUE,
            'default' => 'no_image.png'
          ),
          'attachment' => array(
            'type' => 'VARCHAR(55)',
            'collation' => 'utf8_general_ci',
            'null' => TRUE,
            'default' => null
          ),

      ));
      $this->dbforge->add_key('id', TRUE);
      $this->dbforge->create_table('NEW_supply_orders');

      // -----------------------------------------------------------------------
      // SUPPLY ORDER COUNT

      $this->dbforge->add_field(array(
          'starter_supply_order_number' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE,
          ),
          'last_supply_order_number' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE,
          ),
      ));
      $this->dbforge->add_key('id', TRUE);
      $this->dbforge->create_table('NEW_supply_orders_count');

      // -----------------------------------------------------------------------
      // SUPPLY ORDER PHOTOS

      $this->dbforge->add_field(array(
          'id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE,
            'null' => FALSE,
          ),
          'supply_order_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE,
          ),
          'photo' => array(
            'type' => 'VARCHAR(100)',
            // 'constraint' => 11,
            'collation' => 'utf8_general_ci',
            'null' => FALSE
          ),
      ));
      $this->dbforge->add_key('id', TRUE);
      $this->dbforge->create_table('NEW_supply_order_photos');

      // -----------------------------------------------------------------------
      // SUPPLY ORDER ITEMS

      $this->dbforge->add_field(array(
          'id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
          ),
          'supply_order_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => TRUE,
          ),
          'product_id' => array(
            'type' => 'INT',
            'constraint' => 11,
          ),
          'quantity' => array(
            'type' => 'INT',
            'constraint' => 11,
          ),
      ));
      $this->dbforge->add_key('id', TRUE);
      $this->dbforge->create_table('NEW_supply_order_items');

      // -----------------------------------------------------------------------
      // SUPPLY ORDER MANIFESTS

      $this->dbforge->add_field(array(
          'id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
          ),
          'manifest_ref_no' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'supply_order_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => TRUE,
          ),
          'description' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'created_at' => array(
            'type' => 'varchar',
            'constraint' => 250,
            'null' => true,
            'on update' => 'NOW()'
          ),
          'image' => array(
            'type' => 'VARCHAR(255)',
            'collation' => 'utf8_general_ci',
            'null' => TRUE,
            'default' => 'no_image.png'
          ),
          'attachment' => array(
            'type' => 'VARCHAR(55)',
            'collation' => 'utf8_general_ci',
            'null' => TRUE,
            'default' => null
          ),
      ));
      $this->dbforge->add_key('id', TRUE);
      $this->dbforge->create_table('NEW_supply_order_manifests');

      // -----------------------------------------------------------------------
      // PALLET

      $this->dbforge->add_field(array(
          'id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
          ),
          'code' => array(
            'type' => 'VARCHAR',
            'constraint' => 50,
            'collation' => 'utf8_general_ci',
            'null' => FALSE,
          ),
          'barcode_symbology' => array(
            'type' => 'VARCHAR',
            'constraint' => 55,
            'collation' => 'utf8_general_ci',
            'null' => FALSE,
            'default' => 'code128'
          ),
          'supply_order_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => TRUE,
          ),
          'manifest_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => TRUE,
          ),
          'receiving_report_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => TRUE,
          ),
          'warehouse_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => TRUE,
          ),
          'description' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'created_at' => array(
            'type' => 'varchar',
            'constraint' => 250,
            'null' => true,
            'on update' => 'NOW()'
          ),
          'rack_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => TRUE,
          ),
          'image' => array(
            'type' => 'VARCHAR(255)',
            'collation' => 'utf8_general_ci',
            'null' => TRUE,
            'default' => 'no_image.png'
          ),
          'attachment' => array(
            'type' => 'VARCHAR(55)',
            'collation' => 'utf8_general_ci',
            'null' => TRUE,
            'default' => null
          ),
      ));
      $this->dbforge->add_key('id', TRUE);
      $this->dbforge->create_table('NEW_pallets'); // add pallet_items

      // -----------------------------------------------------------------------
      // PALLET ITEMS

      $this->dbforge->add_field(array(
          'id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
          ),
          'product_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => TRUE,
          ),
          'pallet_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => TRUE,
          ),
          'description' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'quantity' => array(
            'type' => 'INT',
            'constraint' => 11,
          ),
          // 'quantity' => array(
          //   'name' => 'quantity',
          //   'type' => 'DECIMAL',
          //   'constraint' => '15,4'
          // ),
          // 'created_at' => array(
          //   'type' => 'varchar',
          //   'constraint' => 250,
          //   'null' => true,
          //   'on update' => 'NOW()'
          // ),
          'rack_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => TRUE,
          ),
      ));
      $this->dbforge->add_key('id', TRUE);
      $this->dbforge->create_table('NEW_pallet_items'); // add pallet_items

      // -----------------------------------------------------------------------
      // REPORTE DE RECIBO - RECEIPT REPORT

      $this->dbforge->add_field(array(
          'id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
          ),
          'receiving_report_number' => array(
            'type' => 'INT',
            'constraint' => 11,
          ),
          'warehouse_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => TRUE,
          ),
          'supply_order_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => TRUE,
          ),
          'manifest_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => TRUE,
          ),
          'manifest_ref_no' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'created_at' => array(
            'type' => 'varchar',
            'constraint' => 250,
            'null' => true,
            'on update' => 'NOW()'
          ),
          'status' => array(
            'type' => 'ENUM("on_hold","incoming","received","rejected")',
            'default' => 'on_hold',
            'null' => TRUE,
          ),
          'comments' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'image' => array(
            'type' => 'VARCHAR(255)',
            'collation' => 'utf8_general_ci',
            'null' => TRUE,
            'default' => 'no_image.png'
          ),
          'attachment' => array(
            'type' => 'VARCHAR(55)',
            'collation' => 'utf8_general_ci',
            'null' => TRUE,
            'default' => null
          ),
      ));
      $this->dbforge->add_key('id', TRUE);
      $this->dbforge->create_table('NEW_receiving_reports');

      // -----------------------------------------------------------------------
      // RECEIVINGS COUNT

      $this->dbforge->add_field(array(
          'starter_receiving_report_number' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE,
          ),
          'last_receiving_report_number' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE,
          ),
      ));
      $this->dbforge->add_key('id', TRUE);
      $this->dbforge->create_table('NEW_receiving_reports_count');

      // -----------------------------------------------------------------------
      // RECEIVING REPORT PHOTOS

      $this->dbforge->add_field(array(
          'id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE,
            'null' => FALSE,
          ),
          'receiving_report_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE,
          ),
          'photo' => array(
            'type' => 'VARCHAR(100)',
            // 'constraint' => 11,
            'collation' => 'utf8_general_ci',
            'null' => FALSE
          ),
      ));
      $this->dbforge->add_key('id', TRUE);
      $this->dbforge->create_table('NEW_receiving_reports_photos');

      // -----------------------------------------------------------------------
      // Quality Control - Inspection Report

      $this->dbforge->add_field(array(
          'id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
          ),

          'inspection_no' => array(
            'type' => 'INT',
            'constraint' => 11,
            // 'unsigned' => TRUE,
            // 'default' => 1,
            // 'auto_increment' => TRUE,
            // 'null' => FALSE,
            // 'unique' => TRUE,
          ),
          'receiving_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => TRUE,
          ),
          'created_at' => array(
            'type' => 'varchar', // timestam?
            'constraint' => 250,
            'null' => true,
            'on update' => 'NOW()'
            // default 'CURRENT_TIMESTAMP'
          ),

          // Fields according to Gutville's Report

          'lot_n' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => TRUE,
          ),
          'product' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'total_qty_sampled' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'grower_shipper' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),

          'inspection_address' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'inspection_date' => array(
            'type' => 'varchar',
            'constraint' => 250,
            'null' => true,
            'on update' => 'NOW()'
          ),
          'inspection_name' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'product_origin' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),

          // Strictly Required - field to add image or pdf
          'image' => array(
            'type' => 'VARCHAR(255)',
            'collation' => 'utf8_general_ci',
            'null' => TRUE,
            // 'default' => null
          ),
          'attachment' => array(
            'type' => 'VARCHAR(55)',
            'collation' => 'utf8_general_ci',
            'null' => TRUE,
            // 'default' => null
          ),

          // Added Fields

          'warehouse_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => TRUE,
          ),
          'status' => array(
            'type' => 'ENUM("issued","processing","done")',
            'default' => 'issued',
            'null' => TRUE,
          ),

          // issued_at vs created_at
          'issued_at' => array(
            'type' => 'varchar',
            'constraint' => 250,
            'null' => true,
            'on update' => 'NOW()'
          ),
          'completed_at' => array(
            'type' => 'varchar',
            'constraint' => 250,
            'null' => true,
            'on update' => 'NOW()'
          ),

          'additional_issues' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'comments' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
      ));
      $this->dbforge->add_key('id', TRUE);
      $this->dbforge->create_table('NEW_quality_control_reports');

      // -----------------------------------------------------------------------
      // INSPECTION REPORT COUNT

      $this->dbforge->add_field(array(
          'starter_no' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE,
          ),
          'last_no' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE,
          ),
      ));
      $this->dbforge->add_key('id', TRUE);
      $this->dbforge->create_table('NEW_quality_control_reports_count');

      // -----------------------------------------------------------------------
      // INSPECTION REPORT PHOTOS

      $this->dbforge->add_field(array(
          'id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE,
            'null' => FALSE,
          ),
          'inspection_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE,
          ),
          'photo' => array(
            'type' => 'VARCHAR(100)',
            // 'constraint' => 11,
            'collation' => 'utf8_general_ci',
            'null' => FALSE
          ),
      ));
      $this->dbforge->add_key('id', TRUE);
      $this->dbforge->create_table('NEW_quality_control_report_photos');

      // -----------------------------------------------------------------------
      // Quality Control - Inspection Item

      $this->dbforge->add_field(array(
          'id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
          ),

          'inspection_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => TRUE,
          ),
          'pallet_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => TRUE,
          ),
          'product_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => TRUE,
          ),

          // Fields according to Gutville's Report

          'sise' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'sample' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'temp' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'presion' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'ripe' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'mold' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'clean' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'color' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'firm' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'mechanical_damage' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'weight' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'scars_russet_bruset' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'over_ripe' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'total' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
      ));
      $this->dbforge->add_key('id', TRUE);
      $this->dbforge->create_table('NEW_quality_control_report_item');

      // -----------------------------------------------------------------------
      // PICK UP ORDER

      $this->dbforge->add_field(array(
          'id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
          ),

          'pickup_order_no' => array(
            'type' => 'INT',
            'constraint' => 11,
            // 'unsigned' => TRUE,
            // 'default' => 1,
            // 'auto_increment' => TRUE,
            // 'null' => FALSE,
            // 'unique' => TRUE,
          ),
          'created_at' => array(
            'type' => 'varchar', // timestam?
            'constraint' => 250,
            'null' => true,
            'on update' => 'NOW()'
            // default 'CURRENT_TIMESTAMP'
          ),

          'sale_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => TRUE,
          ),
          'sold_to' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'ship_to' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'order_no' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'sales_load' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'order_date' => array(
            'type' => 'varchar',
            'constraint' => 250,
            'null' => true,
            'on update' => 'NOW()'
          ),
          'ship_date' => array(
            'type' => 'varchar',
            'constraint' => 250,
            'null' => true,
            'on update' => 'NOW()'
          ),
          'pay_terms' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'sale_terms' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'customer_PO' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'delivery' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'via' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'salesperson' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'carrier' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'trailer_lic' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'broker' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'state' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'qty' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'pallets' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'weight' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'legend1' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'legend2' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'legend3' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
      ));
      $this->dbforge->add_key('id', TRUE);
      $this->dbforge->create_table('NEW_pickup_orders');

      // -----------------------------------------------------------------------
      // PICK UP ORDER COUNT

      $this->dbforge->add_field(array(
          'starter_no' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE,
          ),
          'last_no' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE,
          ),
      ));
      $this->dbforge->add_key('id', TRUE);
      $this->dbforge->create_table('NEW_pickup_orders_count');

      // -----------------------------------------------------------------------
      // PICK UP ORDER PHOTOS

      $this->dbforge->add_field(array(
          'id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE,
            'null' => FALSE,
          ),
          'pickup_order_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE,
          ),
          'photo' => array(
            'type' => 'VARCHAR(100)',
            // 'constraint' => 11,
            'collation' => 'utf8_general_ci',
            'null' => FALSE
          ),
      ));
      $this->dbforge->add_key('id', TRUE);
      $this->dbforge->create_table('NEW_pickup_order_photos');

      // -----------------------------------------------------------------------
      // PICK UP ORDER ITEM

      $this->dbforge->add_field(array(
          'id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
          ),
          'pickup_order_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => TRUE,
          ),
          'product_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => TRUE,
          ),
          'qty' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'UOM' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'price' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'brkg' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          // Manually add here an extra cost or take product name from product id
          'item_description' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),

      ));
      $this->dbforge->add_key('id', TRUE);
      $this->dbforge->create_table('NEW_pickup_order_item');

      // -----------------------------------------------------------------------
      // BILL OF LADING

      $this->dbforge->add_field(array(
          'id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
          ),

          'bol_no' => array(
            'type' => 'INT',
            'constraint' => 11,
          ),
          'created_at' => array(
            'type' => 'varchar',
            'constraint' => 250,
            'null' => true,
            'on update' => 'NOW()'
          ),

          // Data below is taken from the sale id

          'sale_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => TRUE,
          ),
          'warehouse_id' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'pickup_address' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'pu' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'sale_terms' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'payment_terms' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'shipper' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'ship_to' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'bill_to' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'po' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),

          // data below is the real data collected at bill of LADING

          'carrier_name' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'truck_broker' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'driver_name' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'driver_license' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'driver_phone' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'truck_trailer' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'time_out' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'temperature' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'driver_signature' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),

          'customer_instructions_temperature' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'customer_instructions_temperature_recorder_num' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'customer_instructions_seal_num' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'customer_more_instructions_1' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'customer_more_instructions_2' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'customer_more_instructions_3' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),

          // attachments

          'image_of_signature' => array(
            'type' => 'VARCHAR(255)',
            'collation' => 'utf8_general_ci',
            'null' => TRUE,
            // 'default' => null
          ),
          'attachment_of_signature' => array(
            'type' => 'VARCHAR(55)',
            'collation' => 'utf8_general_ci',
            'null' => TRUE,
            // 'default' => null
          ),

          'image_of_drivers_license' => array(
            'type' => 'VARCHAR(255)',
            'collation' => 'utf8_general_ci',
            'null' => TRUE,
            // 'default' => null
          ),
          'attachment_of_drivers_license' => array(
            'type' => 'VARCHAR(55)',
            'collation' => 'utf8_general_ci',
            'null' => TRUE,
            // 'default' => null
          ),

          'image_of_other_doc_1' => array(
            'type' => 'VARCHAR(255)',
            'collation' => 'utf8_general_ci',
            'null' => TRUE,
            // 'default' => null
          ),
          'attachment_of_other_doc_1' => array(
            'type' => 'VARCHAR(55)',
            'collation' => 'utf8_general_ci',
            'null' => TRUE,
            // 'default' => null
          ),

      ));
      $this->dbforge->add_key('id', TRUE);
      $this->dbforge->create_table('NEW_bills_of_lading');

      // -----------------------------------------------------------------------
      // BILLS OF LADING COUNT

      $this->dbforge->add_field(array(
          'starter_no' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE,
          ),
          'last_no' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE,
          ),
      ));
      $this->dbforge->add_key('id', TRUE);
      $this->dbforge->create_table('NEW_bills_of_lading_count');

      // -----------------------------------------------------------------------
      // BILL OF LADING PHOTOS

      $this->dbforge->add_field(array(
          'id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE,
            'null' => FALSE,
          ),
          'bol_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => FALSE,
          ),
          'photo' => array(
            'type' => 'VARCHAR(100)',
            // 'constraint' => 11,
            'collation' => 'utf8_general_ci',
            'null' => FALSE
          ),
      ));
      $this->dbforge->add_key('id', TRUE);
      $this->dbforge->create_table('NEW_bill_of_lading_photos');

      // -----------------------------------------------------------------------
      // RACKS

      $this->dbforge->add_field(array(
          'id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
          ),
          'warehouse_id' => array(
            'type' => 'INT',
            'constraint' => 11,
            'null' => TRUE,
          ),

          'column' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'row' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'z_index' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'floor_level' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'comments' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'pallet_id' => array(
            'type' => 'TEXT',
            'null' => TRUE
          ),
          'status' => array(
            'type' => 'ENUM("available","busy","other")',
            'default' => 'available',
            'null' => TRUE,
          ),
          'rack_usage' => array(
            'type' => 'ENUM("regular_storage","newest_items","clearance_items","damaged_items","returned_items","refurbished_items","expired_items","layaway_items")',
            'default' => 'regular_storage',
            'null' => TRUE,
          ),

      ));
      $this->dbforge->add_key('id', TRUE);
      $this->dbforge->create_table('NEW_racks');

      // -----------------------------------------------------------------------
      // XXXXXXX

      // -----------------------------------------------------------------------
      // XXXXXXX

      // -----------------------------------------------------------------------

      //////////////////////////////////////////////////////////////////////////
      // TABLE FIELDS UPDATES
      //////////////////////////////////////////////////////////////////////////

      // -----------------------------------------------------------------------

    }

    public function down() {
      // $this->dbforge->drop_table('NEW_sample1');
      // $this->dbforge->drop_table('sample2');
      // $this->dbforge->drop_table('sample3');
      // $this->dbforge->drop_table('sample4');
      // $this->dbforge->drop_table('sample5');

      $this->dbforge->create_table('NEW_supply_orders');
      $this->dbforge->create_table('NEW_supply_orders_count');
      $this->dbforge->create_table('NEW_supply_order_photos');
      $this->dbforge->create_table('NEW_supply_order_items');
      $this->dbforge->create_table('NEW_supply_order_manifests');
      $this->dbforge->create_table('NEW_pallets');
      $this->dbforge->create_table('NEW_pallet_items');
      $this->dbforge->create_table('NEW_receiving_reports');
      $this->dbforge->create_table('NEW_receiving_reports_count');
      $this->dbforge->create_table('NEW_receiving_reports_photos');
      $this->dbforge->create_table('NEW_quality_control_reports');
      $this->dbforge->create_table('NEW_quality_control_reports_count');
      $this->dbforge->create_table('NEW_quality_control_report_photos');
      $this->dbforge->create_table('NEW_quality_control_report_item');
      $this->dbforge->create_table('NEW_pickup_orders');
      $this->dbforge->create_table('NEW_pickup_orders_count');
      $this->dbforge->create_table('NEW_pickup_order_photos');
      $this->dbforge->create_table('NEW_pickup_order_item');
      $this->dbforge->create_table('NEW_bills_of_lading');
      $this->dbforge->create_table('NEW_bills_of_lading_count');
      $this->dbforge->create_table('NEW_bill_of_lading_photos');
      $this->dbforge->create_table('NEW_racks');
    }

}
