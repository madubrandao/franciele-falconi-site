<?php
/* Troca as 4 imagens placeholder (3 depoimentos + foto do Sobre) da Home v4 SEM recriar a pagina. */
if (!defined('ABSPATH')) { echo "run via wp\n"; exit; }
$pid = 30;
$map = array(
  'ff4-testi' => array(
    array('ff4-depoimento-1', 'Mensagem de cliente no WhatsApp: teu atendimento é demais, você me dá clareza e faz eu olhar por outro ângulo as situações.'),
    array('ff4-depoimento-2', 'Mensagem de cliente no WhatsApp agradecendo à Fran pelas palavras e refletindo sobre o processo.'),
    array('ff4-depoimento-3', 'Mensagem de cliente no WhatsApp: ao longo das sessões fui aprendendo a me ouvir, a entender o que estava sentindo.'),
  ),
  'ff4-photo' => array(array('ff4-foto-fran-sobre-palco', 'Franciele Falconi palestrando, com microfone na mão')),
);
$idx = array('ff4-testi'=>0, 'ff4-photo'=>0);
$data = json_decode(get_post_meta($pid, '_elementor_data', true), true);
function ff4walk(&$els, &$idx, $map) {
  foreach ($els as &$el) {
    if (($el['widgetType'] ?? '') === 'image') {
      $cls = $el['settings']['_css_classes'] ?? '';
      if (isset($map[$cls])) {
        $n = $idx[$cls]++;
        if (isset($map[$cls][$n])) {
          list($slug, $alt) = $map[$cls][$n];
          $p = get_page_by_path($slug, OBJECT, 'attachment');
          if (!$p) { echo "FALTA anexo $slug\n"; continue; }
          $el['settings']['image'] = array('id'=>(int)$p->ID, 'url'=>wp_get_attachment_url($p->ID), 'alt'=>$alt);
          echo "OK $cls #$n -> $slug (ID {$p->ID})\n";
        }
      }
    }
    if (!empty($el['elements'])) ff4walk($el['elements'], $idx, $map);
  }
}
ff4walk($data, $idx, $map);
update_post_meta($pid, '_elementor_data', wp_slash(wp_json_encode($data)));
if (class_exists('\Elementor\Plugin')) { \Elementor\Plugin::instance()->files_manager->clear_cache(); }
echo "feito\n";
