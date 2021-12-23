<?php 

// Control core classes for avoid errors
if( class_exists( 'CSF' ) ) {

  //
  // Set a unique slug-like ID
  $prefix = 'custom_profile_options';

  //
  // Create profile options
  CSF::createProfileOptions( $prefix, array(
    'data_type' => 'serialize', // The type of the database save options. `serialize` or `unserialize`
  ) );

  //
  // Create a section
  CSF::createSection( $prefix, array(
    'fields' => array(

      array(
        'id'    => 'latitude',
        'type'  => 'text',
        'title' => 'Latitude',
      ),

      array(
        'id'    => 'longitude',
        'type'  => 'text',
        'title' => 'Longitude',
      ),

    )
  ) );

}

