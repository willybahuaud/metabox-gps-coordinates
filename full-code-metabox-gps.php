// Initialisons la Metabox
add_action( 'add_meta_boxes', 'mes_metaboxes' );
function mes_metaboxes() {
  add_meta_box( 'metabox_adresse', 'Adresse', 'metabox_adresse', 'post', 'side', 'core' );
}

// Construisons la Metabox
function metabox_adresse( $post ) {
  $adresse = get_post_meta( $post->ID, '_event_adresse', true );
  $coords = get_post_meta( $post->ID, '_event_coords', true );
  $coordonnes_definies = get_post_meta( $post->ID, '_defined_coords', true );
  ?>
  <em>Renseignez une adresse postale précise</em>
  <textarea id="" style="width: 250px;" name="event_adress"><?php echo $adresse; ?></textarea>
  <input id="gps_coords" style="width: 250px;" type="text" name="event_coords" value="<?php echo $coords['lat'].' , '.$coords['long']; ?>" disabled="disabled" />

  <input type="checkbox" name="defined_coords" checked="checked" /> value="1" id="defined_coords"> <label for="defined_coords">Coordonnées définies manuellement</label>
  <script type="text/javascript">// <![CDATA[
    jQuery(document).ready(function($){
      var $gps_man = $('#defined_coords');
      function test_manual_coords(){
        if($gps_man.prop("checked")==true){
          $('#gps_coords').prop("disabled",false);
        }else{
          $('#gps_coords').prop("disabled",true);
        }
      }
      $gps_man.on('click',test_manual_coords);
      test_manual_coords();
    });

  // ]]></script>
  <?php
}

// sauvegarde de la metabox
add_action( 'save_post', 'save_adresse' );
function save_adresse( $post_id ) {
  if( isset( $_POST[ 'event_adress' ] ) ) {
    $adresse = wp_strip_all_tags( $_POST[ 'event_adress' ] );
    update_post_meta( $post_id, '_event_adresse', $adresse );
    function get_coords( $a ) {
      $map_url = 'http://maps.google.com/maps/api/geocode/json?address='.urlencode($a).'&sensor=false';
      $request = wp_remote_get( $map_url );
      $json = wp_remote_retrieve_body( $request );

      if( empty( $json ) )
        return false;

      $json = json_decode( $json );
      $lat = $json->results[ 0 ]->geometry->location->lat;
      $long = $json->results[ 0 ]->geometry->location->lng;
      return compact( 'lat', 'long' );
    }

    //je récupère ma checkbox
    $coordonnes_definies = $_POST[ 'defined_coords' ];
    if( $coordonnes_definies == 1 ) { //si checkbox cochée...
      // je sauvegarde sa valeur
      update_post_meta( $post_id, '_defined_coords', 1 );
      //je construis un tableu à partir des coordonnées de l'utilisateur
      $user_coords = explode( ',', trim( $_POST[ 'event_coords' ] ) );
      $coords = array( 'lat' => $user_coords[ 0 ], 'long' => $user_coords[ 1 ] );
      // j'update les coordonnées définies par l'utilisateur
      update_post_meta( $post_id, '_event_coords', $coords );
    }else{ // sinon...
      //j'update sa valeur
      update_post_meta( $post_id, '_defined_coords', 0 );
      // je fais le taf' normal
      $coords = get_coords( $adresse );
      if( $coords != '' )
        update_post_meta( $post_id, '_event_coords', $coords );
    }
  }
}