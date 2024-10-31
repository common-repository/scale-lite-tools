<?php
/**
* @author : Ionut Morariu moraryou@gmail.com
* @description : Adds column in admin dashboard for various post types
* @since 0.1
*/

namespace Scale_Lite;

class AddColumns {

  /**
   * All columns
   */
  public $columns = array();
  public function __construct( $columns ) {
    $this->columns = $columns;
  }
  public function add_columns() {
    foreach ( $this->columns as $column ) {
      $column->attach();
    }
  }
}
