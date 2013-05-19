function metabox_adresse( $post ){
  $adresse = get_post_meta( $post->ID, '_event_adresse', true );
  $coords = get_post_meta( $post->ID, '_event_coords', true );
  ?>
  <em>Renseignez une adresse postale précise</em>
  <textarea id="" style="width: 250px;" name="event_adress"><?php echo $adresse; ?></textarea>
  <input id="gps_coords" style="width: 250px;" type="text" name="event_coords" value="<?php echo $coords['lat'].' , '.$coords['long']; ?>" disabled="disabled" />
  <?php
}