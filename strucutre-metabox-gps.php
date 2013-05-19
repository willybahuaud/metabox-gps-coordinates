// Initialisons la Metabox
add_action( 'add_meta_boxes', 'mes_metaboxes' );
function mes_metaboxes() {
  add_meta_box( 'metabox_adresse', 'Adresse', 'metabox_adresse', 'post', 'side', 'core' );
}

// Construisons la Metabox
function metabox_adresse( $post ){
  $adresse = get_post_meta( $post->ID, '_event_adresse', true );
  ?>
  <em>Renseignez une adresse postale précise</em>
  <textarea id="" style="width: 250px;" name="event_adress"><?php echo $adresse; ?></textarea>
  <input id="gps_coords" style="width: 250px;" type="text" name="event_coords" value="" disabled="disabled" />
  <?php
}

// sauvegarde de la metabox
add_action( 'save_post', 'save_adresse' );
function save_adresse( $post_id ) {
  if( isset( $_POST['event_adress'] ) ) {
    $adresse = wp_strip_all_tags( $_POST[ 'event_adress' ] );
    update_post_meta( $post_id, '_event_adresse', $adresse );
  }
}