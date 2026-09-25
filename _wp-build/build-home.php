<?php
/**
 * Home da Franciele Falconi — v3: widgets NATIVOS do Elementor, fieis ao design v2.
 * Texto / imagem / botao / icone = widgets nativos (edita clicando no painel).
 * So ficam como widget HTML as decoracoes SEM texto: ornamentos de folha, os 2 selos
 * girando, as 2 ondas, o brilho do hero e o contorno organico do "Sobre".
 * CSS/JS estruturais no mu-plugin ff-assets.
 * Rodar: wp eval-file build-home.php   (recria a pagina ID 30 / slug "inicio")
 *
 * NOTA: quando a Madu aprovar, este arquivo tambem sera congelado.
 */
if (!defined('ABSPATH')) { echo "run via wp\n"; exit; }

$WA = 'https://wa.me/5548991118771';

$GLOBALS['ff_n'] = 0;
function ffuid(){ $GLOBALS['ff_n']++; return substr(md5('ff'.$GLOBALS['ff_n'].'z'),0,7); }
function c($s,$els=array(),$inner=false){ return array('id'=>ffuid(),'elType'=>'container','settings'=>$s,'elements'=>$els,'isInner'=>$inner); }
function wdg($t,$s){ return array('id'=>ffuid(),'elType'=>'widget','widgetType'=>$t,'settings'=>$s,'elements'=>array()); }
function htmlw($h){ return wdg('html',array('html'=>$h)); }

function ffatt($slug){ $p=get_page_by_path($slug,OBJECT,'attachment'); return $p?(int)$p->ID:0; }
function ffurl($id){ return $id?wp_get_attachment_url($id):''; }

function px($v){ return array('unit'=>'px','size'=>$v); }
function em($v){ return array('unit'=>'em','size'=>$v); }
function m($t,$r,$b,$l){ return array('unit'=>'px','top'=>$t,'right'=>$r,'bottom'=>$b,'left'=>$l,'isLinked'=>false); }
function pad($t,$r,$b,$l){ return array('unit'=>'px','top'=>$t,'right'=>$r,'bottom'=>$b,'left'=>$l,'isLinked'=>false); }

function heading($text,$tag,$color,$typo=array(),$align='left',$extra=array()){
  return wdg('heading', array_merge(array(
    'title'=>$text,'header_size'=>$tag,'align'=>$align,'title_color'=>$color,'typography_typography'=>'custom',
  ),$typo,$extra));
}
function txt($html,$color,$size=15,$lh=1.85,$weight='300',$align='left',$maxw=null,$extra=array()){
  $s=array(
    'editor'=>'<p>'.$html.'</p>','text_color'=>$color,'align'=>$align,
    'typography_typography'=>'custom','typography_font_family'=>'Montserrat','typography_font_weight'=>$weight,
    'typography_font_size'=>px($size),'typography_line_height'=>em($lh),
  );
  if($maxw){ $s['_element_width']='initial'; $s['_element_custom_width']=px($maxw); }
  return wdg('text-editor', array_merge($s,$extra));
}
function btn($label,$link,$class,$extra=array()){
  $ext = (strpos($link,'http')===0)?'true':'';
  return wdg('button', array_merge(array(
    'text'=>$label,'link'=>array('url'=>$link,'is_external'=>$ext,'nofollow'=>''),
    'size'=>'sm','_css_classes'=>$class,
    'typography_typography'=>'custom','typography_font_family'=>'Montserrat','typography_font_weight'=>'600',
    'typography_font_size'=>px(12),'typography_letter_spacing'=>em(0.12),
  ),$extra));
}
function underlineLink($label,$link,$align='left'){
  $ext=(strpos($link,'http')===0)?'true':'';
  return wdg('button',array(
    'text'=>$label,'link'=>array('url'=>$link,'is_external'=>$ext,'nofollow'=>''),
    'size'=>'xs','align'=>$align,'_css_classes'=>'ff-ulink',
    'typography_typography'=>'custom','typography_font_family'=>'Montserrat','typography_font_weight'=>'600',
    'typography_font_size'=>px(11.5),'typography_letter_spacing'=>em(0.16),
    'button_text_color'=>'#8a6a2f','button_background_color'=>'rgba(0,0,0,0)',
    '_margin'=>m(22,0,0,0),
  ));
}
function eyeRule(){
  return wdg('divider',array(
    'weight'=>px(1),'color'=>'#cb9d50','width'=>px(28),'gap'=>px(0),'align'=>'center',
    '_element_width'=>'initial','_element_custom_width'=>px(28),'_css_classes'=>'ff-eye-rule','_margin'=>array('unit'=>'px','top'=>0,'right'=>0,'bottom'=>0,'left'=>0,'isLinked'=>true),
  ));
}
function eyebrow($label,$align='left'){
  $lbl=heading($label,'div','#8a7a4a',array(
    'typography_font_family'=>'Cinzel','typography_font_weight'=>'600',
    'typography_font_size'=>px(11.5),'typography_letter_spacing'=>em(0.32),
  ),'center',array('_css_classes'=>'ff-eye-t'));
  $els=($align==='center')?array(eyeRule(),$lbl,eyeRule()):array($lbl);
  return c(array(
    'content_width'=>'full','flex_direction'=>'row','flex_align_items'=>'center',
    'flex_justify_content'=>($align==='center'?'center':'flex-start'),
    'flex_gap'=>array('unit'=>'px','size'=>($align==='center'?16:14),'column'=>($align==='center'?16:14),'row'=>10),
    'flex_wrap'=>'nowrap','_margin'=>m(0,0,($align==='center'?18:18),0),
  ),$els,true);
}
/* Icon Box nativo: circulo + icone FA + titulo + descricao. $mode: stacked|framed */
function iconBox($icon,$lib,$title,$desc,$mode,$circleColor,$class,$titleSize=16,$titleLS=0.05){
  return wdg('icon-box',array(
    'selected_icon'=>array('value'=>$icon,'library'=>$lib),
    'view'=>$mode,'shape'=>'circle','position'=>'left',
    'primary_color'=>$circleColor,
    'title_text'=>$title,'description_text'=>$desc,'title_size'=>'h3',
    'icon_space'=>px(18),
    'title_typography_typography'=>'custom','title_typography_font_family'=>($titleSize>=17?'"Playfair Display"':'Montserrat'),
    'title_typography_font_weight'=>($titleSize>=17?'500':'600'),
    'title_typography_font_size'=>px($titleSize),'title_typography_letter_spacing'=>em($titleLS),
    'title_color'=>'#3c4a34',
    'description_typography_typography'=>'custom','description_typography_font_family'=>'Montserrat',
    'description_typography_font_weight'=>'300','description_typography_font_size'=>px(14),
    'description_typography_line_height'=>em(1.75),'description_color'=>'#6b7059',
    '_css_classes'=>$class,
  ));
}
function pic($id,$class='',$extra=array()){
  return wdg('image', array_merge(array(
    'image'=>array('id'=>$id,'url'=>ffurl($id)),'image_size'=>'full','_css_classes'=>$class,
  ),$extra));
}

$IMG = array();
foreach(array('logo-icon','logo-lockup','hero-desktop','hero-tablet','hero-mobile','caminho-individual','caminho-empresa','sobre-principal','sobre-palco','conversar') as $s){ $IMG[$s]=ffatt($s); }

$data = array();

/* ============================================================ 1. NAV — pill flutuante (modelo supaste.com) ============================================================ */
/* #ffnav = wrapper fixo transparente (pointer-events:none). #ff-navpill = a pill visivel. */
$navBrand = c(array(
  'content_width'=>'full','flex_direction'=>'row','flex_align_items'=>'center','flex_wrap'=>'nowrap',
  'flex_gap'=>array('unit'=>'px','size'=>10,'column'=>10,'row'=>10),'_element_id'=>'ff-navbrand',
), array(
  pic($IMG['logo-icon'],'ff-nav-logo',array('link_to'=>'custom','link'=>array('url'=>'#inicio'),'width'=>px(32))),
  heading('Franciele Falconi','div','#ece5d3',array(
    'typography_font_family'=>'"Playfair Display"','typography_font_weight'=>'500','typography_font_size'=>px(15),
    'typography_letter_spacing'=>em(0.02),
  ),'left',array('_css_classes'=>'ff-nav-wordmark')),
), true);
$navLinks = wdg('icon-list',array(
  'icon_list'=>array(
    array('text'=>'CAMINHOS','link'=>array('url'=>'#caminhos'),'selected_icon'=>array('value'=>'','library'=>'')),
    array('text'=>'SERVIÇOS','link'=>array('url'=>'#servico'),'selected_icon'=>array('value'=>'','library'=>'')),
    array('text'=>'MÉTODO','link'=>array('url'=>'#metodo'),'selected_icon'=>array('value'=>'','library'=>'')),
    array('text'=>'SOBRE','link'=>array('url'=>'#sobre'),'selected_icon'=>array('value'=>'','library'=>'')),
  ),
  'view'=>'inline','_css_classes'=>'ff-navlinks',
  'typography_typography'=>'custom','typography_font_family'=>'Cinzel','typography_font_weight'=>'500',
  'typography_font_size'=>px(11.5),'typography_letter_spacing'=>em(0.14),'text_color'=>'#d8d2bd',
));
$navCta = btn('AGENDAR',$WA,'ff-nav-cta',array(
  'typography_font_size'=>px(11),'typography_letter_spacing'=>em(0.12),'button_text_color'=>'#3c4a34',
));
$navPill = c(array(
  'content_width'=>'full','flex_direction'=>'row','flex_align_items'=>'center','flex_wrap'=>'nowrap',
  '_element_id'=>'ff-navpill','padding'=>pad(10,10,10,18),
), array($navBrand,$navLinks,$navCta), true);
$data[] = c(array(
  'content_width'=>'full','flex_direction'=>'row','flex_align_items'=>'center','flex_justify_content'=>'center',
  'flex_wrap'=>'nowrap','_element_id'=>'ffnav',
  'padding'=>pad(18,24,18,24),'padding_mobile'=>pad(12,14,12,14),
), array( $navPill ));

/* ============================================================ 2. HERO — modelo supaste.com adaptado aos tons da marca ============================================================ */
/* #inicio (165vh + gradiente da marca) > .ff-hero-sticky (sticky 100svh) > [deco, #hero-inner, mockup].
   O efeito de subida do mockup e o fade do conteudo dependem de --hero-progress (setado no ff-assets.js). */
$heroDeco = <<<'HTML'
<div id="ffprogress" aria-hidden="true"></div>
<div class="ff-hero-backdrop" aria-hidden="true"></div>
<svg class="ff-hero-hills" viewBox="0 0 1440 320" preserveAspectRatio="none" aria-hidden="true">
  <path d="M0,214 C240,132 430,150 660,204 C900,260 1130,250 1440,176 L1440,320 L0,320 Z" fill="var(--ff-green)" opacity=".85"></path>
  <path d="M0,268 C280,206 520,232 780,262 C1040,292 1240,284 1440,244 L1440,320 L0,320 Z" fill="var(--ff-green-dark)"></path>
</svg>
HTML;
/* Mockup da janela do app — bloco 100% placeholder (a Madu edita os textos/cores depois). */
$heroMockup = <<<'HTML'
<div class="ff-hero-mockup" aria-hidden="true">
  <div class="ff-mockup-frame">
    <div class="ff-mockup-window">
      <aside class="ff-mockup-sidebar">
        <div class="ff-mockup-brand"><span></span>Franciele</div>
      </aside>
      <div class="ff-mockup-body">
        <div class="ff-mockup-topbar">
          <div class="ff-mockup-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="#8a9478" stroke-width="1.4" stroke-linecap="round"><circle cx="10.6" cy="10.6" r="6.2"></circle><path d="M15.4 15.4 20 20"></path></svg>
            <span>Buscar…</span>
          </div>
          <div class="ff-mockup-tools"><i></i><i></i><i></i></div>
        </div>
        <div class="ff-mockup-tabs">
          <span class="is-active">Histórico 24</span>
          <span>Categoria 24</span>
          <span>Categoria 24</span>
          <span>Categoria 24</span>
          <span>Categoria 24</span>
          <span class="ff-mockup-tab-add">+</span>
        </div>
        <div class="ff-mockup-cards">
          <article class="ff-mockup-card"><div class="ff-mockup-card-media m-image"></div><div class="ff-mockup-card-foot"><i></i><span>há 5 min</span></div></article>
          <article class="ff-mockup-card"><div class="ff-mockup-card-media m-note"><span>Nota de texto</span></div><div class="ff-mockup-card-foot"><i></i><span>há 19 min</span></div></article>
          <article class="ff-mockup-card"><div class="ff-mockup-card-media m-shot"><span>Captura</span></div><div class="ff-mockup-card-foot"><i></i><span>há 23 min</span></div></article>
          <article class="ff-mockup-card"><div class="ff-mockup-card-media m-color"><span>#3C4A34</span></div><div class="ff-mockup-card-foot"><i></i><span>há 35 min</span></div></article>
          <article class="ff-mockup-card"><div class="ff-mockup-card-media m-icon"><span>✦</span></div><div class="ff-mockup-card-foot"><i></i><span>há 37 min</span></div></article>
        </div>
      </div>
    </div>
  </div>
</div>
HTML;
$heroDecoWrap   = c(array('content_width'=>'full','_css_classes'=>'ff-hero-deco','width'=>array('unit'=>'%','size'=>100)), array( htmlw($heroDeco) ), true);
$heroMockupWrap = c(array('content_width'=>'full','_css_classes'=>'ff-hero-mockupwrap','width'=>array('unit'=>'%','size'=>100)), array( htmlw($heroMockup) ), true);
$heroInner = c(array(
  'content_width'=>'full','_element_id'=>'hero-inner','width'=>array('unit'=>'%','size'=>100),
  'flex_direction'=>'column','flex_align_items'=>'center','max_width'=>px(820),'z_index'=>3,
  'padding'=>pad(0,20,0,20),
), array(
  c(array('content_width'=>'full','flex_direction'=>'row','flex_align_items'=>'center','flex_wrap'=>'nowrap',
    'flex_justify_content'=>'center','flex_gap'=>array('unit'=>'px','size'=>8,'column'=>8,'row'=>8),
    '_css_classes'=>'ff-hero-eyebrow','_margin'=>m(0,0,22,0)), array(
    wdg('icon',array('selected_icon'=>array('value'=>'fas fa-leaf','library'=>'fa-solid'),
      'primary_color'=>'#ece5d3','size'=>px(16),'align'=>'center','_css_classes'=>'ff-hero-eyebrow-i')),
    heading('TERAPIA &amp; DESENVOLVIMENTO HUMANO','div','#ece5d3',array(
      'typography_font_family'=>'Cinzel','typography_font_weight'=>'600','typography_font_size'=>px(12),
      'typography_letter_spacing'=>em(0.28),
    ),'center',array('_css_classes'=>'ff-hero-eyebrow-t')),
  ), true),
  heading('Cuide uma vez.','h1','#ece5d3',array(
    'typography_font_family'=>'Montserrat','typography_font_weight'=>'600','typography_font_size'=>px(64),
    'typography_font_size_mobile'=>px(40),'typography_line_height'=>em(1),'typography_letter_spacing'=>em(-0.02),
  ),'center',array('_css_classes'=>'ff-hero-h1 ff-hero-h1--strong')),
  heading('Floresça sempre.','div','#d9b877',array(
    'typography_font_family'=>'"Playfair Display"','typography_font_weight'=>'400','typography_font_style'=>'italic',
    'typography_font_size'=>px(64),'typography_font_size_mobile'=>px(40),
    'typography_line_height'=>em(1),'typography_letter_spacing'=>em(-0.02),
  ),'center',array('_css_classes'=>'ff-hero-h1 ff-hero-h1--script')),
  txt('Texto de apoio (placeholder) — descreva aqui, em duas ou três linhas, a proposta do Método Falconi para acompanhar o título.',
    'rgba(246,241,228,.82)',16,1.6,'400','center',null,array('_css_classes'=>'ff-hero-lead','_margin'=>m(26,0,0,0))),
  c(array('content_width'=>'full','flex_direction'=>'row','flex_align_items'=>'center','flex_justify_content'=>'center',
    'flex_wrap'=>'wrap','_margin'=>m(30,0,0,0)), array(
    btn('AGENDAR UMA CONVERSA',$WA,'ff-btn-hero',array(
      'selected_icon'=>array('value'=>'far fa-comment-dots','library'=>'fa-regular'),
      'icon_align'=>'left','icon_indent'=>px(10),
    )),
  ), true),
  c(array('content_width'=>'full','flex_direction'=>'row','flex_align_items'=>'center','flex_justify_content'=>'center',
    'flex_wrap'=>'wrap','flex_gap'=>array('unit'=>'px','size'=>20,'column'=>20,'row'=>8),
    '_css_classes'=>'ff-hero-badges','_margin'=>m(20,0,0,0)), array(
    heading('Atendimento humano','div','rgba(246,241,228,.6)',array('typography_font_family'=>'Montserrat','typography_font_weight'=>'400','typography_font_size'=>px(11.5),'typography_letter_spacing'=>em(0.08)),'center',array('_css_classes'=>'ff-hero-badge')),
    heading('Online e presencial','div','rgba(246,241,228,.6)',array('typography_font_family'=>'Montserrat','typography_font_weight'=>'400','typography_font_size'=>px(11.5),'typography_letter_spacing'=>em(0.08)),'center',array('_css_classes'=>'ff-hero-badge')),
    heading('Florianópolis · SC','div','rgba(246,241,228,.6)',array('typography_font_family'=>'Montserrat','typography_font_weight'=>'400','typography_font_size'=>px(11.5),'typography_letter_spacing'=>em(0.08)),'center',array('_css_classes'=>'ff-hero-badge')),
  ), true),
), true);
$heroSticky = c(array(
  'content_width'=>'full','_css_classes'=>'ff-hero-sticky','width'=>array('unit'=>'%','size'=>100),
  'flex_direction'=>'column','flex_align_items'=>'center','flex_justify_content'=>'center','overflow'=>'hidden',
), array( $heroDecoWrap, $heroInner, $heroMockupWrap ), true);
$data[] = c(array(
  'content_width'=>'full','background_background'=>'classic','background_color'=>'#3c4a34',
  '_element_id'=>'inicio','padding'=>pad(0,0,0,0),
), array( $heroSticky ));

/* ============================================================ 3. CAMINHOS ============================================================ */
function pathCard($h3,$sub,$imgId,$blob,$h4,$p,$cta,$WA){
  return c(array('content_width'=>'full','flex_direction'=>'column','flex_align_items'=>'center','_css_classes'=>'ff-pathcard'), array(
    heading($h3,'h3','#3c4a34',array('typography_font_family'=>'"Playfair Display"','typography_font_weight'=>'500',
      'typography_font_size'=>px(24),'typography_letter_spacing'=>em(0.08)),'center'),
    txt($sub,'#8b8f76',12.5,1.4,'500','center',null,array('_margin'=>m(9,0,24,0))),
    pic($imgId,'ff-path-figure '.$blob),
    heading($h4,'h4','#3c4a34',array('typography_font_family'=>'"Playfair Display"','typography_font_weight'=>'400',
      'typography_font_size'=>px(27),'typography_line_height'=>em(1.28)),'center',
      array('_element_width'=>'initial','_element_custom_width'=>px(420),'_margin'=>m(28,0,0,0))),
    txt($p,'#6b7059',14.5,1.85,'300','center',400,array('_margin'=>m(14,0,0,0))),
    underlineLink($cta.'   →',$WA,'center'),
  ), true);
}
$data[] = c(array('content_width'=>'boxed','background_background'=>'classic','background_color'=>'#f1ece1',
  'max_width'=>px(1220),'_element_id'=>'caminhos','flex_direction'=>'column',
  'padding'=>pad(30,32,84,32),'padding_mobile'=>pad(24,20,64,20)), array(
  c(array('content_width'=>'full','flex_direction'=>'column','flex_align_items'=>'center','_margin'=>m(0,0,44,0)), array(
    eyebrow('UM TRABALHO, DOIS CAMINHOS','center'),
    heading('Qual caminho <em style="color:#cb9d50;font-style:italic">é o seu?</em>','h2','#3c4a34',array(
      'typography_font_family'=>'"Playfair Display"','typography_font_weight'=>'400','typography_font_size'=>px(56),
      'typography_font_size_mobile'=>px(32),'typography_line_height'=>em(1.04)),'center'),
  ), true),
  c(array('content_width'=>'full','flex_direction'=>'row','flex_wrap'=>'wrap','flex_align_items'=>'flex-start',
    'flex_gap'=>array('unit'=>'px','size'=>48,'column'=>48,'row'=>48),'_element_id'=>'ff-paths'), array(
    pathCard('PARA VOCÊ','Processo individual',$IMG['caminho-individual'],'ff-blob-a',
      'Você sente que perdeu a conexão consigo mesma?',
      'Sobrecarga emocional, relações que cansam mais do que nutrem. Talvez seja hora de olhar para dentro — com apoio.',
      'QUERO CUIDAR DE MIM',$WA),
    pathCard('PARA SUA EMPRESA','Equipes e lideranças',$IMG['caminho-empresa'],'ff-blob-b',
      'Sua equipe está esgotada, e a liderança não sabe por quê?',
      'Conflitos e lideranças despreparadas para o lado humano da gestão custam caro. Existe um caminho estruturado para mudar isso.',
      'FALAR SOBRE MINHA EMPRESA',$WA),
  ), true),
));

/* ============================================================ 4. DEPOIMENTOS ============================================================ */
$depoLeaf = <<<'HTML'
<svg class="ff-depo-leaf" viewBox="0 0 200 200" aria-hidden="true">
  <path d="M12 100 C70 100 130 100 190 100" fill="none" stroke="#3c4a34" stroke-width="2.4"></path>
  <ellipse cx="52" cy="78" rx="24" ry="11" fill="#3c4a34" transform="rotate(-24 52 78)"></ellipse>
  <ellipse cx="86" cy="122" rx="24" ry="11" fill="#3c4a34" transform="rotate(24 86 122)"></ellipse>
  <ellipse cx="118" cy="78" rx="22" ry="10" fill="#3c4a34" transform="rotate(-24 118 78)"></ellipse>
  <ellipse cx="150" cy="120" rx="20" ry="9" fill="#3c4a34" transform="rotate(24 150 120)"></ellipse>
</svg>
HTML;
function depoCard($ini,$role){
  return c(array('content_width'=>'full','flex_direction'=>'row','flex_align_items'=>'flex-start',
    'flex_gap'=>array('unit'=>'px','size'=>18,'column'=>18,'row'=>18),'padding'=>pad(8,26,8,26),'_css_classes'=>'ff-depo-cell'), array(
    heading($ini,'div','#d9b877',array('typography_font_family'=>'"Playfair Display"','typography_font_weight'=>'400',
      'typography_font_size'=>px(20)),'center',array('_css_classes'=>'ff-depo-ini')),
    c(array('content_width'=>'full','flex_direction'=>'column','flex_gap'=>array('unit'=>'px','size'=>0,'column'=>0,'row'=>0)), array(
      txt('Texto do depoimento — envie os depoimentos reais e eu substituo.','#e4e6d8',14.5,1.8,'300',null,null,array('_margin'=>m(0,0,16,0))),
      heading('— NOME DO CLIENTE','div','#d9b877',array('typography_font_family'=>'Montserrat','typography_font_weight'=>'600',
        'typography_font_size'=>px(11),'typography_letter_spacing'=>em(0.14)),'left',array('_css_classes'=>'ff-depo-name')),
      heading($role,'div','#9aa38c',array('typography_font_family'=>'Montserrat','typography_font_weight'=>'400',
        'typography_font_size'=>px(10.5),'typography_letter_spacing'=>em(0.14)),'left',array('_css_classes'=>'ff-depo-role','_margin'=>m(5,0,0,0))),
    ), true),
  ), true);
}
$data[] = c(array('content_width'=>'boxed','background_background'=>'classic','background_color'=>'#f1ece1',
  'max_width'=>px(1180),'_element_id'=>'depoimentos','overflow'=>'visible',
  'padding'=>pad(0,24,84,24)), array(
  htmlw($depoLeaf),
  c(array('content_width'=>'full','background_background'=>'classic','background_color'=>'#3c4a34','_element_id'=>'ff-depopanel',
    'border_radius'=>array('unit'=>'px','top'=>90,'right'=>90,'bottom'=>90,'left'=>90,'isLinked'=>false),
    'padding'=>pad(72,60,72,60),'padding_mobile'=>pad(50,26,50,26),'flex_direction'=>'column'), array(
    heading('QUEM JÁ PERCORREU ESSE CAMINHO','div','#d9b877',array('typography_font_family'=>'Montserrat',
      'typography_font_weight'=>'600','typography_font_size'=>px(11),'typography_letter_spacing'=>em(0.3)),'center',
      array('_css_classes'=>'ff-depo-kicker','_margin'=>m(0,0,40,0))),
    c(array('content_width'=>'full','flex_direction'=>'row','flex_wrap'=>'wrap','_element_id'=>'ff-depogrid'), array(
      depoCard('A','PROCESSO INDIVIDUAL'), depoCard('M','WORKSHOP'), depoCard('R','PROGRAMA IN COMPANY'),
    ), true),
  )),
));

/* ============================================================ 5. SERVICO + METODO + EMPRESAS ============================================================ */
function stepCol($n,$icon,$name,$p,$gold=false){
  return c(array('content_width'=>'full','flex_direction'=>'column','flex_align_items'=>'center','_css_classes'=>'ff-step',
    'flex_gap'=>array('unit'=>'px','size'=>0,'column'=>0,'row'=>0)), array(
    wdg('icon',array('selected_icon'=>array('value'=>$icon,'library'=>'fa-solid'),
      'primary_color'=>($gold?'#f6f1e4':'#8a7a4a'),'size'=>px(24),'align'=>'center',
      '_css_classes'=>'ff-step-orb'.($gold?' ff-step-orb--gold':''))),
    heading($n,'div','#a79c7c',array('typography_font_family'=>'"Playfair Display"','typography_font_weight'=>'400',
      'typography_font_size'=>px(15)),'center',array('_css_classes'=>'ff-step-num','_margin'=>m(0,0,8,0))),
    heading($name,'h3','#3c4a34',array('typography_font_family'=>'Cinzel','typography_font_weight'=>'600',
      'typography_font_size'=>px(11.5),'typography_letter_spacing'=>em(0.16)),'center',array('_css_classes'=>'ff-step-name')),
    txt($p,'#6b7059',13,1.75,'300','center',null,array('_margin'=>m(8,0,0,0))),
  ), true);
}
$leftCol = c(array('content_width'=>'full','flex_direction'=>'column',
  'flex_gap'=>array('unit'=>'px','size'=>0,'column'=>0,'row'=>0)), array(
  eyebrow('SERVIÇO EM DESTAQUE','left'),
  heading('Processo Terapêutico Individual','h2','#3c4a34',array('typography_font_family'=>'"Playfair Display"',
    'typography_font_weight'=>'400','typography_font_size'=>px(37),'typography_line_height'=>em(1.12)),'left',array('_css_classes'=>'ff-h2','_margin'=>m(0,0,18,0))),
  txt('O Método Falconi em profundidade: um espaço para compreender a raiz do que se repete, com foco em consciência emocional, limites mais saudáveis e transformação real — não apenas alívio do sintoma.','#5f6344',15,1.85,'300',null,null,array('_margin'=>m(0,0,34,0))),
  c(array('content_width'=>'full','flex_direction'=>'column','flex_gap'=>array('unit'=>'px','size'=>26,'column'=>26,'row'=>26)), array(
    iconBox('fas fa-plus-circle','fa-solid','ANÁLISE INTEGRATIVA','Leitura da história emocional e dos padrões que se repetem.','stacked','#cb9d50','ff-svc',12.5,0.13),
    iconBox('fas fa-eye','fa-solid','HIPNOTERAPIA','Acesso ao material inconsciente que sustenta o sofrimento.','stacked','#cb9d50','ff-svc',12.5,0.13),
    iconBox('fas fa-sitemap','fa-solid','CONSTELAÇÃO FAMILIAR','Individual ou em grupo, para compreender heranças e vínculos.','stacked','#cb9d50','ff-svc',12.5,0.13),
    iconBox('fas fa-heart','fa-solid','REIKI E BARRAS DE ACCESS','Apoio energético para aliviar tensão e reorganizar o corpo.','stacked','#cb9d50','ff-svc',12.5,0.13),
  ), true),
  btn('QUERO COMEÇAR',$WA,'ff-btn-primary',array('_margin'=>m(38,0,0,0))),
), true);
$rightCol = c(array('content_width'=>'full','flex_direction'=>'column','_element_id'=>'metodo',
  'flex_gap'=>array('unit'=>'px','size'=>0,'column'=>0,'row'=>0)), array(
  c(array('content_width'=>'full','flex_direction'=>'column','flex_align_items'=>'center','_margin'=>m(0,0,40,0),
    'flex_gap'=>array('unit'=>'px','size'=>0,'column'=>0,'row'=>0)), array(
    eyebrow('MÉTODO FALCONI','center'),
    heading('Cinco movimentos, <em style="color:#cb9d50;font-style:italic">uma transformação</em>','h2','#3c4a34',array(
      'typography_font_family'=>'"Playfair Display"','typography_font_weight'=>'400','typography_font_size'=>px(40),
      'typography_font_size_mobile'=>px(27),'typography_line_height'=>em(1.08)),'center',array('_css_classes'=>'ff-h2')),
  ), true),
  c(array('content_width'=>'full','flex_direction'=>'row','flex_align_items'=>'flex-start','_element_id'=>'ff-stepsrow'), array(
    stepCol('01','fas fa-search','CONSCIÊNCIA','Entender onde você está e por quê.'),
    htmlw('<div class="ff-arrow" aria-hidden="true">→</div>'),
    stepCol('02','fas fa-seedling','RAIZ','A origem dos padrões que se repetem.'),
    htmlw('<div class="ff-arrow" aria-hidden="true">→</div>'),
    stepCol('03','fas fa-leaf','RESSIGNIFICAÇÃO','Liberar o peso emocional que os sustenta.'),
    htmlw('<div class="ff-arrow" aria-hidden="true">→</div>'),
    stepCol('04','fas fa-home','RECONSTRUÇÃO','Novos comportamentos, limites e identidade.'),
    htmlw('<div class="ff-arrow" aria-hidden="true">→</div>'),
    stepCol('05','fas fa-check-circle','INTEGRAÇÃO','Consolidar a mudança no dia a dia.',true),
  ), true),
  txt('Todo processo começa com um mapeamento emocional que identifica os padrões que mais pesam na sua vida hoje. A partir dele, construímos juntas um plano genuinamente seu — não um roteiro pronto.','#6b7059',14.5,1.85,'300','left',620,array('_margin'=>m(36,0,0,0))),
  heading('Não é uma fórmula genérica,<br>é um caminho','div','#8a7a4a',array('typography_font_family'=>'"Petit Formal Script"',
    'typography_font_weight'=>'400','typography_font_size'=>px(26),'typography_line_height'=>em(1.4)),'right',
    array('_css_classes'=>'ff-script','_margin'=>m(30,0,0,0))),
), true);
$empresas = array(
  array('fas fa-users','Palestras','Encontros inspiradores sobre bem-estar emocional, saúde mental e qualidade das relações no ambiente de trabalho.'),
  array('fas fa-chalkboard-teacher','Workshops','Práticas vivenciais e reflexivas que convidam à ação, à consciência e ao desenvolvimento humano dentro das equipes.'),
  array('fas fa-comments','Rodas de Conversa','Espaços seguros de escuta ativa e troca entre colaboradores, fortalecendo vínculos e a cultura de cuidado mútuo.'),
  array('fas fa-heartbeat','Vivências para Equipes','Experiências terapêuticas coletivas focadas em reconexão, presença e fortalecimento das relações profissionais.'),
  array('fas fa-hand-holding-heart','Saúde Emocional no Trabalho','Programas de cuidado emocional que contribuem para um clima organizacional mais saudável e produtivo.'),
  array('far fa-comment-dots','Comunicação e Relações Saudáveis','Práticas que desenvolvem escuta, empatia e qualidade nas trocas interpessoais dentro das organizações.'),
);
$empEls = array();
foreach($empresas as $e){
  $lib = (strpos($e[0],'far ')===0)?'fa-regular':'fa-solid';
  $empEls[] = iconBox($e[0],$lib,$e[1],$e[2],'framed','#cb9d50','ff-card',18,0);
}
$data[] = c(array('content_width'=>'boxed','background_background'=>'classic','background_color'=>'#f1ece1',
  'max_width'=>px(1220),'_element_id'=>'servico','flex_direction'=>'column',
  'padding'=>pad(36,32,84,32),'padding_mobile'=>pad(30,20,64,20)), array(
  c(array('content_width'=>'full','flex_direction'=>'row','flex_wrap'=>'wrap','flex_align_items'=>'flex-start',
    'flex_gap'=>array('unit'=>'px','size'=>64,'column'=>64,'row'=>48),'_element_id'=>'ff-svcgrid'), array($leftCol,$rightCol), true),
  c(array('content_width'=>'full','flex_direction'=>'column','flex_align_items'=>'center','_margin'=>m(80,0,0,0)), array(
    eyebrow('PARA EMPRESAS','center'),
    heading('Saúde emocional como cultura','h2','#3c4a34',array('typography_font_family'=>'"Playfair Display"',
      'typography_font_weight'=>'400','typography_font_size'=>px(38),'typography_line_height'=>em(1.12)),'center',array('_margin'=>m(0,0,44,0))),
  ), true),
  c(array('content_width'=>'full','flex_direction'=>'row','flex_wrap'=>'wrap','_element_id'=>'ff-cardsgrid'), $empEls, true),
));

/* ============================================================ 6. SOBRE ============================================================ */
$sobreOutline = '<div class="ff-sobre-outline" data-plx-scroll="16"></div>';
$data[] = c(array('content_width'=>'boxed','background_background'=>'classic','background_color'=>'#e7e0cf',
  'max_width'=>px(1220),'_element_id'=>'sobre','overflow'=>'visible',
  'padding'=>pad(84,32,84,32),'padding_mobile'=>pad(56,20,56,20)), array(
  c(array('content_width'=>'full','flex_direction'=>'row','flex_wrap'=>'wrap','flex_align_items'=>'center',
    'flex_gap'=>array('unit'=>'px','size'=>72,'column'=>72,'row'=>48),'_element_id'=>'ff-sobregrid'), array(
    c(array('content_width'=>'full','flex_direction'=>'column','_element_id'=>'ff-sobre-media','overflow'=>'visible',
      'flex_gap'=>array('unit'=>'px','size'=>0,'column'=>0,'row'=>0)), array(
      htmlw($sobreOutline),
      pic($IMG['sobre-principal'],'ff-blob-c'),
      pic($IMG['sobre-palco'],'ff-sobre-inset'),
    ), true),
    c(array('content_width'=>'full','flex_direction'=>'column','flex_gap'=>array('unit'=>'px','size'=>0,'column'=>0,'row'=>0)), array(
      eyebrow('SOBRE','left'),
      heading('Presença, escuta ativa e cuidado genuíno em cada encontro.','h2','#3c4a34',array(
        'typography_font_family'=>'"Playfair Display"','typography_font_weight'=>'400','typography_font_size'=>px(44),
        'typography_font_size_mobile'=>px(28),'typography_line_height'=>em(1.12)),'left',array('_margin'=>m(0,0,14,0))),
      heading('antes da técnica, a pessoa','div','#8a7a4a',array('typography_font_family'=>'"Petit Formal Script"',
        'typography_font_weight'=>'400','typography_font_size'=>px(26)),'left',array('_css_classes'=>'ff-script','_margin'=>m(0,0,28,0))),
      txt('Minha atuação une neurociência, psicanálise e abordagem sistêmica a ferramentas de desenvolvimento emocional. Acredito que ansiedade, exaustão e conflitos nas relações não surgem por acaso — são reflexos de histórias, padrões inconscientes e dinâmicas relacionais que precisam ser compreendidos para que a mudança aconteça de forma verdadeira.','#4d5138',15.5,1.9,'300',null,null,array('_margin'=>m(0,0,20,0))),
      txt('Cada processo começa com presença e escuta ativa: antes de qualquer técnica, é preciso compreender a pessoa (ou a equipe) que está à minha frente, com cuidado genuíno pelo caminho que ela está percorrendo.','#4d5138',15.5,1.9,'300',null,null,array('_margin'=>m(0,0,30,0))),
      c(array('content_width'=>'full','flex_direction'=>'row','flex_wrap'=>'wrap',
        'flex_gap'=>array('unit'=>'px','size'=>9,'column'=>9,'row'=>9),'_element_id'=>'ff-chips','_margin'=>m(0,0,32,0)), array(
        heading('NEUROCIÊNCIA','div','#5f6344',array('typography_font_family'=>'Montserrat','typography_font_weight'=>'500','typography_font_size'=>px(10.5),'typography_letter_spacing'=>em(0.14)),'center',array('_css_classes'=>'ff-chip')),
        heading('PSICANÁLISE','div','#5f6344',array('typography_font_family'=>'Montserrat','typography_font_weight'=>'500','typography_font_size'=>px(10.5),'typography_letter_spacing'=>em(0.14)),'center',array('_css_classes'=>'ff-chip')),
        heading('ABORDAGEM SISTÊMICA','div','#5f6344',array('typography_font_family'=>'Montserrat','typography_font_weight'=>'500','typography_font_size'=>px(10.5),'typography_letter_spacing'=>em(0.14)),'center',array('_css_classes'=>'ff-chip')),
        heading('DESENVOLVIMENTO EMOCIONAL','div','#5f6344',array('typography_font_family'=>'Montserrat','typography_font_weight'=>'500','typography_font_size'=>px(10.5),'typography_letter_spacing'=>em(0.14)),'center',array('_css_classes'=>'ff-chip')),
      ), true),
      underlineLink('AGENDAR UMA CONVERSA   →',$WA,'left'),
    ), true),
  ), true),
));

/* ============================================================ 7. CONVERSAR ============================================================ */
$convDeco = <<<'HTML'
<svg class="ff-conv-wave" viewBox="0 0 1440 120" preserveAspectRatio="none" aria-hidden="true"><path d="M0,0 L0,58 C250,124 520,116 760,68 C990,22 1210,16 1440,60 L1440,0 Z" fill="#e7e0cf"></path></svg>
<div class="ff-conv-glow" data-plx-scroll="18"></div>
HTML;
$convSeal = <<<'HTML'
<div class="ff-seal2" data-plx-scroll="-14">
  <svg viewBox="0 0 120 120" aria-hidden="true" class="ff-seal-ring"><defs><path id="ffseal2" d="M60,60 m-45,0 a45,45 0 1,1 90,0 a45,45 0 1,1 -90,0"></path></defs><text><textPath href="#ffseal2" startOffset="0">RAÍZES · CONSCIÊNCIA · EQUILÍBRIO · </textPath></text></svg>
  <img src="LOGOICON" alt="" aria-hidden="true" class="ff-seal2-logo">
</div>
HTML;
$convSeal = str_replace('LOGOICON', ffurl($IMG['logo-icon']), $convSeal);
$convContact = wdg('icon-list',array(
  'icon_list'=>array(
    array('text'=>'+55 48 99111-8771','link'=>array('url'=>$WA,'is_external'=>'true'),'selected_icon'=>array('value'=>'fab fa-whatsapp','library'=>'fa-brands')),
    array('text'=>'contato@francielefalconi.com.br','link'=>array('url'=>'mailto:contato@francielefalconi.com.br'),'selected_icon'=>array('value'=>'fas fa-envelope','library'=>'fa-solid')),
    array('text'=>'@francielefalconi.terapeuta','link'=>array('url'=>'https://instagram.com/francielefalconi.terapeuta','is_external'=>'true'),'selected_icon'=>array('value'=>'fab fa-instagram','library'=>'fa-brands')),
    array('text'=>'Atendimento online e presencial','selected_icon'=>array('value'=>'fas fa-map-marker-alt','library'=>'fa-solid')),
  ),
  'icon_color'=>'#d9b877','icon_size'=>px(15),'text_color'=>'#ece5d3',
  'typography_typography'=>'custom','typography_font_family'=>'Montserrat','typography_font_size'=>px(13.5),
  '_css_classes'=>'ff-contact','_margin'=>m(0,0,0,0),
));
$data[] = c(array('content_width'=>'boxed','background_background'=>'classic','background_color'=>'#3c4a34',
  'max_width'=>px(1180),'_element_id'=>'conversar','overflow'=>'hidden',
  'padding'=>pad(120,32,110,32),'padding_mobile'=>pad(96,20,80,20)), array(
  htmlw($convDeco),
  c(array('content_width'=>'full','flex_direction'=>'row','flex_wrap'=>'wrap','flex_align_items'=>'center',
    'flex_gap'=>array('unit'=>'px','size'=>60,'column'=>60,'row'=>48),'_element_id'=>'ff-convgrid'), array(
    c(array('content_width'=>'full','flex_direction'=>'column','flex_gap'=>array('unit'=>'px','size'=>0,'column'=>0,'row'=>0)), array(
      heading('Vamos começar <em style="color:#d9b877;font-style:italic">essa conversa?</em>','h2','#ece5d3',array(
        'typography_font_family'=>'"Playfair Display"','typography_font_weight'=>'400','typography_font_size'=>px(50),
        'typography_font_size_mobile'=>px(30),'typography_line_height'=>em(1.06)),'left',array('_css_classes'=>'ff-h2','_margin'=>m(0,0,16,0))),
      heading('sem compromisso, com acolhimento','div','#d9b877',array('typography_font_family'=>'"Petit Formal Script"',
        'typography_font_weight'=>'400','typography_font_size'=>px(26)),'left',array('_css_classes'=>'ff-script','_margin'=>m(0,0,24,0))),
      txt('O primeiro passo é entender onde você está e o que faz sentido a partir daqui. Escolha o caminho mais confortável para você.','#c6c9b6',15.5,1.9,'300','left',460,array('_margin'=>m(0,0,34,0))),
      btn('AGENDAR PELO WHATSAPP',$WA,'ff-btn-primary',array('_margin'=>m(0,0,44,0))),
      $convContact,
    ), true),
    c(array('content_width'=>'full','flex_direction'=>'column','flex_align_items'=>'center','overflow'=>'visible','_element_id'=>'ff-conv-media'), array(
      pic($IMG['conversar'],'ff-blob-d'),
      htmlw($convSeal),
    ), true),
  ), true),
));

/* ============================================================ 8. FOOTER ============================================================ */
$data[] = c(array('content_width'=>'full','background_background'=>'classic','background_color'=>'#2b3524','_element_id'=>'ff-footer',
  'flex_direction'=>'row','flex_justify_content'=>'space-between','flex_align_items'=>'center','flex_wrap'=>'wrap',
  'flex_gap'=>array('unit'=>'px','size'=>24,'column'=>24,'row'=>18),'padding'=>pad(40,32,40,32)), array(
  c(array('content_width'=>'full','flex_direction'=>'row','flex_align_items'=>'center',
    'flex_gap'=>array('unit'=>'px','size'=>14,'column'=>14,'row'=>14),'_element_id'=>'ff-foot-brand'), array(
    pic($IMG['logo-icon'],'ff-foot-logo',array('width'=>px(38))),
    c(array('content_width'=>'full','flex_direction'=>'column','flex_gap'=>array('unit'=>'px','size'=>3,'column'=>3,'row'=>3)), array(
      heading('FRANCIELE FALCONI','div','#ece5d3',array('typography_font_family'=>'Cinzel','typography_font_weight'=>'600','typography_font_size'=>px(13),'typography_letter_spacing'=>em(0.14)),'left'),
      heading('DESENVOLVIMENTO HUMANO &amp; SAÚDE EMOCIONAL','div','#8d8a6e',array('typography_font_family'=>'Montserrat','typography_font_weight'=>'400','typography_font_size'=>px(10.5),'typography_letter_spacing'=>em(0.14)),'left'),
    ), true),
  ), true),
  wdg('icon-list',array(
    'icon_list'=>array(
      array('text'=>'@francielefalconi.terapeuta','link'=>array('url'=>'https://instagram.com/francielefalconi.terapeuta','is_external'=>'true'),'selected_icon'=>array('value'=>'','library'=>'')),
      array('text'=>'contato@francielefalconi.com.br','link'=>array('url'=>'mailto:contato@francielefalconi.com.br'),'selected_icon'=>array('value'=>'','library'=>'')),
      array('text'=>'WhatsApp','link'=>array('url'=>$WA,'is_external'=>'true'),'selected_icon'=>array('value'=>'','library'=>'')),
    ),
    'view'=>'inline','space_between'=>px(26),'text_color'=>'#c6c0a8',
    'typography_typography'=>'custom','typography_font_family'=>'Montserrat','typography_font_size'=>px(12.5),
    '_css_classes'=>'ff-foot-links',
  )),
));

/* ---- salvar ---- */
$existing = get_page_by_path('inicio', OBJECT, 'page');
$pid = $existing ? $existing->ID : wp_insert_post(array('post_title'=>'Inicio','post_name'=>'inicio','post_status'=>'publish','post_type'=>'page'));
update_post_meta($pid,'_elementor_data', wp_slash(wp_json_encode($data)));
update_post_meta($pid,'_elementor_edit_mode','builder');
update_post_meta($pid,'_elementor_template_type','wp-page');
update_post_meta($pid,'_wp_page_template','elementor_canvas');
update_post_meta($pid,'_elementor_version','3.32.0');
$ps = get_post_meta($pid,'_elementor_page_settings',true); if(!is_array($ps)) $ps=array();
$ps['hide_title']='yes'; $ps['page_layout']='elementor_canvas';
update_post_meta($pid,'_elementor_page_settings',$ps);
update_option('show_on_front','page'); update_option('page_on_front',$pid); update_option('blog_public',0);
if (class_exists('\Elementor\Plugin')) { \Elementor\Plugin::instance()->files_manager->clear_cache(); }
echo "Home OK (nativo v3): page ID $pid ; sections=".count($data)."\n";
