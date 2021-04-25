
<?php
  $uri = $_SERVER['REQUEST_URI']; //substr($_SERVER['REQUEST_URI'], 1, strlen($_SERVER['REQUEST_URI']));
  $current_url = "https://{$_SERVER['HTTP_HOST']}{$uri}";
  $social_text = "Monitorización de un huerto en tiempo real (IES Arroyo de la  Miel)";
  $social_url = $current_url;

  /*
  echo "URI: " . $current_url . "<br/>";
  echo "_SERVER[HTTP_HOST]: " . $_SERVER['HTTP_HOST'] . "<br/>";
  echo "CURRENT URL: " . $current_url . "<br/>";
  echo "SOCIAL TEXT: " . $social_text . "<br/>";
  echo "SOCIAL: " . $social_url . "<br/>";
  */

  // whastapp
//  $whatsapp_href="https://wa.me/?text=" . urlencode("{$social_url}"); //{$social_text}
  $whatsapp_href = "whatsapp://send?text=" . urlencode("$social_text \n$social_url");

  // telegram
  $telegram_href = "tg://msg?text=" . urlencode("$social_text\n$social_url");

  // twitter
  // http://twitter.com/share?text=text goes here&url=http://url goes here&hashtags=hashtag1,hashtag2,hashtag3
  $twitter_href = "https://twitter.com/share?text="  . urlencode($social_text) . "&url=" . urlencode($social_url) . "&hashtags=monitorizacion,huerto,arduino,raspberrypi";

  // facebook
  // <a href="https://www.facebook.com/sharer/sharer.php?u=#url" target="_blank">Share</a>
  $facebook_href = "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($social_url);
?>
<footer class="footer">
  <div class="container footer-class">
    <div class="row">
      <div class="col-md-12 text-center">
          <ul class="footer-social ul-footer-social">
            <li><a href="<?php echo $whatsapp_href; ?>"><i class="fab fa-whatsapp"></i></a></li>
            <li><a href="<?php echo $telegram_href; ?>"><i class="fab fa-telegram"></i></a></li>
            <li><a href="<?php echo $twitter_href; ?>"><i class="fab fa-twitter"></i></a></li>
            <li><a href="<?php echo $facebook_href; ?>"><i class="fab fa-facebook"></i></a></li>
          </ul>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 copyright text-center">
        <a class="inherit-color" href="http://www.iesarroyodelamiel.es/"><p>© <?php echo date("Y"); ?> - IES Arroyo de la Miel</p></a>
      </div>
    </div>
  </div>
</footer>
