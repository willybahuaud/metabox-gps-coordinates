add_shortcode( 'lieu', 'ma_google_map' );
function ma_google_map() {
  global $post;
  $coords = get_post_meta( $post->ID, '_event_coords', true );
  $adresse = get_post_meta( $post->ID, '_event_adresse', true );

  return '<iframe src="https://maps.google.fr/maps?q='.$adresse.'&num=1&t=v&ie=UTF8&z=14&ll='.$coords['lat'].','.$coords['long'].'&output=embed" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" width="425" height="350"></iframe>
  <small><a style="color: #0000ff; text-align: left;" href="https://maps.google.fr/maps?q='.$adresse.'&num=1&t=v&ie=UTF8&z=14&ll='.$coords['lat'].','.$coords['long'].'&source=embed">Agrandir le plan</a></small>'
}