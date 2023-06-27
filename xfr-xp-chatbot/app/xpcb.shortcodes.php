<?php
/** XPCHATBOT para chatBot */
add_shortcode('xp_chatbot', function ($atts, $content) {
  global $chatbot;
  $html = get_view_xp_chatbot($chatbot);
  return $html;
});


/** XPCHATBOT para chatBot */
add_shortcode('xp_chatbot_control_panel', function ($atts, $content) {
  global $chatbot;
  $html = get_view_xp_control_panel($chatbot);
  return $html;
});



/** PARA GEOLOCALIZA */
// add_shortcode('xpm_geolocaliza', function ($atts, $content) {
//   global $geolocaliza;
//   $html = get_view_xpm_geolocaliza($geolocaliza);
//   return $html;
// });

// add_shortcode('xpm_geolocaliza_new', function ($atts, $content) {
//   global $geolocaliza;
//   $html = get_view_xpm_geolocaliza_clon($geolocaliza);
//   return $html;
// });