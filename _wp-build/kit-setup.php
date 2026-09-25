<?php
$kit_id = (int) get_option('elementor_active_kit');
if (!$kit_id) { echo "no kit\n"; exit; }

$settings = get_post_meta($kit_id, '_elementor_page_settings', true);
if (!is_array($settings)) $settings = [];

$settings['site_name'] = 'Franciele Falconi';
$settings['site_description'] = 'Terapeuta Psicanalista Sistêmica';

// System colors
$settings['system_colors'] = [
  ['_id'=>'primary','title'=>'Primary','color'=>'#29311E'],
  ['_id'=>'secondary','title'=>'Secondary','color'=>'#CB9D50'],
  ['_id'=>'text','title'=>'Text','color'=>'#3C4A34'],
  ['_id'=>'accent','title'=>'Accent','color'=>'#D9B877'],
];
// Custom colors
$settings['custom_colors'] = [
  ['_id'=>'ff_ground','title'=>'Ground','color'=>'#F1ECE1'],
  ['_id'=>'ff_green_deep','title'=>'Green Deep','color'=>'#29311E'],
  ['_id'=>'ff_green_mid','title'=>'Green Mid','color'=>'#2B3524'],
  ['_id'=>'ff_green_soft','title'=>'Green Soft','color'=>'#3C4A34'],
  ['_id'=>'ff_green_light','title'=>'Green Light','color'=>'#C8E4B8'],
  ['_id'=>'ff_gold','title'=>'Gold','color'=>'#CB9D50'],
  ['_id'=>'ff_gold_light','title'=>'Gold Light','color'=>'#D9B877'],
  ['_id'=>'ff_cream','title'=>'Cream Text','color'=>'#ECE5D3'],
];

// System typography
$mk = function($id,$title,$family,$weight,$size=null,$lh=null,$ls=null,$transform=null){
  $t = ['_id'=>$id,'title'=>$title,'typography_typography'=>'custom',
        'typography_font_family'=>$family,'typography_font_weight'=>$weight];
  if($size!==null){ $t['typography_font_size']=['unit'=>'px','size'=>$size]; }
  if($lh!==null){ $t['typography_line_height']=['unit'=>'em','size'=>$lh]; }
  if($ls!==null){ $t['typography_letter_spacing']=['unit'=>'px','size'=>$ls]; }
  if($transform!==null){ $t['typography_text_transform']=$transform; }
  return $t;
};
$settings['system_typography'] = [
  $mk('primary','Primary','Cinzel','600',null,1.15,1,'uppercase'),
  $mk('secondary','Secondary','"Playfair Display"','500',null,1.2,0),
  $mk('text','Text','Montserrat','400',null,1.7,0),
  $mk('accent','Accent','"Petit Formal Script"','400',null,1.3,0),
];
$settings['custom_typography'] = [
  $mk('ff_label','Label','Montserrat','600',12,1.4,2,'uppercase'),
  $mk('ff_display','Display','Cinzel','600',54,1.1,1,'uppercase'),
  $mk('ff_serif','Serif','"Playfair Display"','500',34,1.2,0),
];

$settings['container_width'] = ['unit'=>'px','size'=>1200];
$settings['space_between_widgets'] = ['unit'=>'px','size'=>0,'column'=>0,'row'=>0];
$settings['body_color'] = '#3C4A34';
$settings['body_typography_typography'] = 'custom';
$settings['body_typography_font_family'] = 'Montserrat';
$settings['body_typography_font_weight'] = '400';
$settings['body_typography_font_size'] = ['unit'=>'px','size'=>16];
$settings['body_typography_line_height'] = ['unit'=>'em','size'=>1.7];
$settings['page_title_selector'] = 'h1.entry-title';
$settings['activeItemIndex'] = 1;

update_post_meta($kit_id, '_elementor_page_settings', $settings);
echo "kit $kit_id updated\n";
