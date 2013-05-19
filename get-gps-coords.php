add_action('save_post','save_adresse');
function save_adresse($post_id){
  // Code déjà présent ↓
  if( isset( $_POST[ 'event_adress' ] ) ) {
    $adresse = wp_strip_all_tags( $_POST[ 'event_adress' ] );
    update_post_meta( $post_id, '_event_adresse', $adresse );

    // Ajout ↓
    // 1 . je construit une fonction pour récup' les coords
    function get_coords($a){
      // je construit une URL avec l'adresse
      $map_url = 'http://maps.google.com/maps/api/geocode/json?address=' . urlencode( $a ) . '&sensor=false';
      // je récupère ça
      $request = wp_remote_get( $map_url );
      $json = wp_remote_retrieve_body( $request );

      // si c'est vide, je kill...
      if( empty( $json ) )
      return false;

      // je parse et je choppe la latitude et la longitude
      $json = json_decode( $json );
      $lat = $json->results[ 0 ]->geometry->location->lat;
      $long = $json->results[ 0 ]->geometry->location->lng;
      // je retourne les valeurs sous forme de tableau
      return compact( 'lat', 'long' );
    }

    // 2. je lance ma fonction, l'adresse en parametre
    $coords = get_coords( $adresse );
    // 3. si j'ai récupéré des coordonnées, je sauvegarde
    if( $coords != '' )
      update_post_meta( $post_id, '_event_coords', $coords );
  }
}