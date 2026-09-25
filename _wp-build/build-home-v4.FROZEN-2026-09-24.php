<?php
/* CONGELADO em 2026-09-24 (v4 liquid glass, aprovada p/ revisao da Madu). NAO rodar: recriaria a pagina 30 e apagaria edicoes feitas no Elementor.
   Para descongelar: remova as 2 linhas abaixo. Restore point: elementor-data-30.v4.SNAPSHOT.json */
echo "build-home-v4 CONGELADO - veja o comentario no topo
"; exit(1);
?>
<?php
/**
 * Home da Franciele Falconi — v4: design "liquid glass" (Claude Design, handoff 2026-09-24).
 * Fonte: design_handoff_site_franciele_falconi / "Site Franciele Falconi Mobile.dc.html" (site responsivo unico).
 * 12 secoes, cada uma = 1 widget HTML fiel ao design (textos verbatim). Excecao: as 4 imagens pendentes
 * (3 depoimentos + foto da Fran no Sobre) sao widgets Imagem NATIVOS, para trocar pela Media Library.
 * CSS/JS: mu-plugin ff-site.php -> ff-assets/ff4-assets.css, ff4-garden.js, ff4-ui.js.
 * Rodar: wp eval-file build-home-v4.php   (recria a pagina "inicio" = front page, elementor_canvas / tela total)
 */
if (!defined('ABSPATH')) { echo "run via wp\n"; exit; }

$WA   = 'https://wa.me/5548991118771';
$MAIL = 'mailto:contato@francielefalconi.com.br';
$IG   = 'https://instagram.com/francielefalconi.terapeuta';

function ff4att($slug){ $p=get_page_by_path($slug,OBJECT,'attachment'); return $p?(int)$p->ID:0; }
function ff4url($slug){ $id=ff4att($slug); return $id?wp_get_attachment_url($id):''; }

function ff4uid(){ static $n=0; $n++; return substr(md5('ff4'.$n.'q'),0,7); }
function ff4c($s,$els=array(),$inner=true){
  $base=array('content_width'=>'full','flex_direction'=>'column','padding'=>array('unit'=>'px','top'=>0,'right'=>0,'bottom'=>0,'left'=>0,'isLinked'=>true),
    'flex_gap'=>array('unit'=>'px','size'=>0,'column'=>0,'row'=>0));
  return array('id'=>ff4uid(),'elType'=>'container','settings'=>array_merge($base,$s),'elements'=>$els,'isInner'=>$inner);
}
function ff4w($t,$s){ return array('id'=>ff4uid(),'elType'=>'widget','widgetType'=>$t,'settings'=>$s,'elements'=>array()); }
function ff4html($h){ return ff4w('html',array('html'=>$h)); }
function ff4img($slug,$class,$alt){
  $id=ff4att($slug);
  return ff4w('image',array('image'=>array('id'=>$id,'url'=>ff4url($slug),'alt'=>$alt),'image_size'=>'full','_css_classes'=>$class));
}
function e($s){ return htmlspecialchars($s,ENT_QUOTES,'UTF-8'); }

/* botao "moldura que fecha e reabre" (Botao.dc.html). $raw=true: sem wrapper .ff4-bw */
function btn($label,$href,$raw=false){
  $ext=(strpos($href,'http')===0)?' target="_blank" rel="noopener"':'';
  $l=e($label);
  $a='<a class="ff4-btn" href="'.e($href).'"'.$ext.'><span class="gh">'.$l.'</span><span class="fr"><i></i><i></i><i></i><i></i></span><span class="bx"><span class="sh"></span><span class="t1">'.$l.'</span><span class="t2" aria-hidden="true">'.$l.'</span></span></a>';
  return $raw?$a:'<div class="ff4-bw">'.$a.'</div>';
}
/* card de cantos dourados (Card.dc.html) */
function card($title,$desc,$big=''){
  return '<div class="ff4-card"><span class="c1"></span><span class="c2"></span>'.($big!==''?'<div class="big">'.e($big).'</div>':'').'<div class="ct">'.e($title).'</div><div class="cd">'.e($desc).'</div></div>';
}

$LOGO   = ff4url('ff4-logo');
$BANNER = ff4url('ff4-banner-hero');
$CAPA_V = ff4url('ff4-capa-voce');
$CAPA_E = ff4url('ff4-capa-empresa');
$JARDIM = ff4url('ff4-jardim');

/* ================= 01 NAV + FUNDO ANIMADO ================= */
$nav  = '<div class="ff4-bg" aria-hidden="true"><img src="'.e($JARDIM).'" alt="" decoding="async"><div class="vg"></div>'
      . '<canvas id="ff4-cv-back"></canvas><canvas id="ff4-cv-mid"></canvas><canvas id="ff4-cv-hero"></canvas><canvas id="ff4-cv-front"></canvas></div>';
$nav .= '<div class="ff4-navspace"></div><div class="ff4-navfix"><header class="ff4-nav" data-screen-label="01 Nav"><div class="ff4-nav-row">'
      . '<a class="ff4-nav-logo" href="#home"><img src="'.e($LOGO).'" alt="Fran Falconi" width="224" height="44"></a>'
      . '<nav class="ff4-nav-links" aria-label="Principal"><a href="#caminhos">CAMINHOS</a><a href="#servicos">SERVIÇOS</a><a href="#metodo">MÉTODO</a><a href="#sobre">SOBRE MIM</a></nav>'
      . '<span class="ff4-nav-cta">'.btn('AGENDAR CONVERSA',$WA,true).'</span>'
      . '<button class="ff4-burger" type="button" aria-label="Abrir menu" aria-expanded="false">☰</button></div>'
      . '<nav class="ff4-menu" aria-label="Menu"><a href="#caminhos">CAMINHOS</a><a href="#servicos">SERVIÇOS</a><a href="#metodo">MÉTODO</a><a href="#sobre">SOBRE MIM</a>'.btn('AGENDAR CONVERSA',$WA).'</nav>'
      . '</header></div>';

/* ================= 02 HERO ================= */
$hero  = '<section id="home" class="ff4-hero" data-screen-label="02 Hero"><img src="'.e($BANNER).'" alt="Franciele Falconi, terapeuta psicanalista" width="1600" height="900" fetchpriority="high">'
       . '<div class="ff4-hero-body"><div class="ff4-col" style="gap:14px">'
       . '<div class="ff4-eyebrow">TERAPEUTA PSICANALISTA SISTÊMICA</div>'
       . '<h1 class="ff4-sr">Presença, escuta ativa e cuidado genuíno em cada encontro.</h1>'
       . '<p class="ff4-p">Ansiedade, exaustão e conflitos não surgem por acaso. Eu ajudo pessoas e equipes a compreender as raízes emocionais do que sentem e a construir relações mais saudáveis, com clareza e responsabilidade.</p></div>'
       . '<div class="ff4-col ff4-hero-actions" style="gap:18px;align-items:flex-start"><div class="ff4-hero-cta">'.btn('Agendar uma conversa',$WA)
       . '<a class="ff4-link" href="#caminhos">Conhecer o trabalho</a></div>'
       . '<div class="ff4-sign">Método Falconi · Florianópolis, SC · presencial e online</div></div></div></section>';

/* ================= 03 DOIS CAMINHOS ================= */
function book($eyebrow,$sub,$title,$text,$btnHtml,$img,$alt,$pos){
  return '<div class="ff4-book"><div class="ff4-book-face"><div class="ff4-eyebrow">'.e($eyebrow).'</div><div class="ff4-book-sub">'.e($sub).'</div>'
    . '<div class="ff4-book-t">'.e($title).'</div><p class="ff4-p sm">'.e($text).'</p><div class="ff4-book-cta">'.$btnHtml.'</div></div>'
    . '<div class="ff4-cover"><img src="'.e($img).'" alt="'.e($alt).'" loading="lazy" style="object-position:'.$pos.'"><div class="cap"><div class="ff4-eyebrow">'.e($eyebrow).'</div><div class="s">'.e($sub).'</div></div></div></div>';
}
$cam  = '<section id="caminhos" class="ff4-glass" data-screen-label="03 Dois caminhos"><div class="ff4-col" style="gap:12px"><div class="ff4-eyebrow">UM TRABALHO, DOIS CAMINHOS</div><h2 class="ff4-h2">Qual caminho é o seu?</h2></div><div class="ff4-grid2">'
      . book('PARA VOCÊ','Processo individual','Você sente que perdeu a conexão consigo mesma?','Sobrecarga emocional, dificuldade em dizer não, relações que cansam mais do que nutrem. Talvez seja hora de olhar para dentro, com apoio.',btn('QUERO CUIDAR DE MIM',$WA),$CAPA_V,'Grupo de mulheres sorrindo em uma vivência terapêutica','center 40%')
      . book('PARA SUA EMPRESA','Equipes e lideranças','Sua equipe está esgotada, e a liderança não sabe por quê?','Conflitos, queda de engajamento e lideranças despreparadas para o lado humano da gestão custam caro. Existe um caminho estruturado para mudar isso.',btn('FALAR SOBRE MINHA EMPRESA',$WA),$CAPA_E,'Franciele Falconi palestrando para uma plateia','52% center')
      . '</div></section>';

/* ================= 04 VOCÊ SE RECONHECE ================= */
function lis($arr){ $o=''; foreach($arr as $i){ $o.='<div class="ff4-li"><span>'.e($i).'</span></div>'; } return $o; }
$rec  = '<section class="ff4-glass" data-screen-label="04 Voce se reconhece"><div class="ff4-col" style="gap:16px"><h2 class="ff4-h2">Você não está “sendo dramática”.</h2>'
      . '<p class="ff4-p">Exaustão emocional, culpa constante, medo de decepcionar, relações desgastadas. Isso tem origem, e tem caminho. Não é fraqueza: é raiz.</p></div><div class="ff4-grid2">'
      . '<div class="ff4-panel"><div class="ff4-panel-t">Talvez você esteja vivendo:</div>'.lis(array('Exaustão emocional e sobrecarga mental','Dificuldade em estabelecer limites, em casa e no trabalho','A sensação de estar perdida ou desconectada de si mesma','Relações familiares ou profissionais desgastadas','Vazio, culpa ou insatisfação constante com a própria vida')).'</div>'
      . '<div class="ff4-panel gold"><div class="ff4-panel-t">O que pode mudar com o processo:</div>'.lis(array('Consciência emocional e compreensão dos seus próprios padrões','Uma vida emocional mais organizada, com clareza e responsabilidade','Limites mais saudáveis nas relações','Menos ansiedade, exaustão e conflitos internos','Mais presença, autonomia e estabilidade emocional')).'</div>'
      . '</div></section>';

/* ================= 06 SERVIÇO ================= */
$svc  = '<section id="servicos" class="ff4-glass" data-screen-label="06 Servico em destaque"><div class="ff4-col" style="gap:14px"><h2 class="ff4-h2">Processo Terapêutico Individual</h2>'
      . '<p class="ff4-p">O Método Falconi em profundidade: um espaço seguro para compreender a raiz do que se repete, com foco em consciência emocional, limites mais saudáveis e transformação real, e não só alívio do sintoma.</p></div>'
      . '<div class="ff4-svc-grid">'
      . card('ANÁLISE INTEGRATIVA','Leitura da sua história emocional e dos padrões que se repetem.')
      . card('HIPNOTERAPIA','Acesso ao material inconsciente que sustenta o sofrimento.')
      . card('CONSTELAÇÃO FAMILIAR','Individual ou em grupo, para compreender heranças e vínculos.')
      . card('REIKI E BARRAS DE ACCESS','Apoio energético para aliviar tensão e reorganizar o corpo.')
      . '</div>'.btn('Quero começar',$WA).'</section>';

/* ================= 07 MÉTODO ================= */
$steps = array(array('01','CONSCIÊNCIA','Entender onde você está e por quê.'),array('02','RAIZ','Chegar à origem dos padrões que se repetem.'),array('03','RESSIGNIFICAÇÃO','Liberar o peso emocional que os sustenta.'),array('04','RECONSTRUÇÃO','Criar novos comportamentos, limites e identidade.'),array('05','INTEGRAÇÃO','Consolidar a mudança no dia a dia.'));
$track=''; foreach($steps as $s){ $track.='<div>'.card($s[1],$s[2],$s[0]).'</div>'; }
foreach($steps as $s){ $track.='<div aria-hidden="true">'.card($s[1],$s[2],$s[0]).'</div>'; }
$met  = '<section id="metodo" class="ff4-glass" data-screen-label="07 Metodo Falconi"><div class="ff4-col" style="gap:14px"><div class="ff4-eyebrow">MÉTODO FALCONI</div><h2 class="ff4-h2">Cinco movimentos, uma transformação</h2>'
      . '<p class="ff4-p">Um processo estruturado e próprio, criado para conduzir cada pessoa da consciência à transformação real.</p></div>'
      . '<div class="ff4-mq"><div class="ff4-track" id="ff4-track">'.$track.'</div></div>'
      . '<div class="ff4-grid2" style="gap:28px;align-items:center"><div class="ff4-col" style="gap:14px"><p class="ff4-p">Todo processo começa com um mapeamento emocional, que identifica os padrões que mais pesam na sua vida hoje. A partir dele, construímos juntas um plano genuinamente seu, não um roteiro pronto.</p>'
      . '<div class="ff4-script ff4-script-l">Não é uma fórmula genérica, é um caminho.</div></div>'
      . '<blockquote class="ff4-panel ff4-quote">“Toda transformação verdadeira começa quando compreendemos nossas raízes e assumimos a responsabilidade pelo nosso desenvolvimento.”</blockquote></div></section>';

/* ================= 08 PARA EMPRESAS ================= */
$emp = array(
 array('Palestras','Encontros inspiradores sobre bem-estar emocional, saúde mental e qualidade das relações no trabalho.','Ideal para abrir um programa ou marcar uma data. Tema alinhado à realidade do seu time.'),
 array('Workshops','Práticas vivenciais e reflexivas que convidam à ação, à consciência e ao desenvolvimento humano dentro das equipes.','Encontros práticos, com dinâmicas que a equipe leva para o dia a dia.'),
 array('Rodas de Conversa','Espaços seguros de escuta ativa e troca entre colaboradores, fortalecendo vínculos e a cultura de cuidado mútuo.','Fortalecem vínculos e a cultura de cuidado mútuo.'),
 array('Vivências para Equipes','Experiências coletivas focadas em reconexão, presença e fortalecimento das relações profissionais.','Para equipes que precisam se reencontrar depois de conflito, mudança ou sobrecarga.'),
 array('Saúde Emocional no Trabalho','Programas de cuidado emocional que contribuem para um clima organizacional mais saudável e produtivo.','Ações contínuas de prevenção, com acompanhamento.'),
 array('Comunicação e Relações Saudáveis','Práticas que desenvolvem escuta, empatia e qualidade nas trocas interpessoais dentro das organizações.','Voltado a lideranças e equipes que precisam alinhar conversas difíceis.'),
);
$empH=''; foreach($emp as $x){ $empH.='<div class="ff4-panel ff4-emp"><div class="h"><div class="t">'.e($x[0]).'</div><span class="ff4-plus">+</span></div><div class="r">'.e($x[1]).'</div><div class="d">'.e($x[2]).'</div></div>'; }
$emps = '<section id="empresas" class="ff4-glass" data-screen-label="08 Para empresas"><div class="ff4-col" style="gap:14px"><div class="ff4-eyebrow">PARA EMPRESAS</div><h2 class="ff4-h2">Saúde emocional como cultura</h2>'
      . '<p class="ff4-p">Diagnóstico organizacional e desenvolvimento de pessoas para empresas de educação e saúde que querem prevenir o adoecimento emocional antes que ele vire crise. Menos conflito, mais comunicação, lideranças preparadas.</p></div>'
      . '<div class="ff4-panel ff4-feature"><div class="ff4-eyebrow">DESTAQUE</div><div style="font-family:\'Cormorant Garamond\',serif;font-weight:600;font-size:30px">Consultoria Organizacional</div>'
      . '<p class="ff4-p sm">Começa com um diagnóstico dos principais desafios da sua organização. A partir dele identificamos oportunidades de desenvolvimento, fortalecemos equipes, melhoramos a comunicação, desenvolvemos inteligência emocional e fortalecemos a cultura, com pertencimento e colaboração.</p>'
      . '<p class="ff4-p sm it">Treinamento de Desenvolvimento Organizacional: programa sob medida para o momento da sua equipe.</p></div>'
      . '<div class="ff4-emp-grid">'.$empH.'</div>'.btn('Falar sobre minha empresa',$WA).'</section>';

/* ================= 10 FAQ ================= */
$faq = array(
 array('É terapia ou coaching?','É um processo terapêutico, conduzido por uma psicanalista sistêmica. Une escuta clínica, investigação da origem emocional dos padrões e ferramentas práticas de mudança. Vai além de metas e produtividade.'),
 array('Presencial ou online?','Presencial em Florianópolis (SC) e online para todo o Brasil.'),
 array('Quanto tempo dura?','O Método Falconi tem 12 sessões, em 5 fases. O ritmo é ajustado a você.'),
 array('Minhas informações são sigilosas?','Sim. O sigilo profissional é um compromisso ético do início ao fim.'),
 array('Quanto custa?','Depende do formato. Conversamos na primeira conversa, sem compromisso.'),
 array('Como sei se é para mim?','Conversando. A conversa inicial serve para entendermos juntas o caminho mais adequado.'),
);
$faqH=''; foreach($faq as $q){ $faqH.='<div class="ff4-faq-i"><div class="h"><div class="q">'.e($q[0]).'</div><span class="ff4-plus">+</span></div><p class="ff4-p sm a">'.e($q[1]).'</p></div>'; }
$faqS = '<section class="ff4-glass" data-screen-label="10 Perguntas frequentes"><h2 class="ff4-h2">Perguntas frequentes</h2><div class="ff4-faq">'.$faqH.'</div></section>';

/* ================= 11 CTA FINAL + 12 RODAPÉ ================= */
$cta  = '<section id="contato" class="ff4-glass ff4-cta-sec" data-screen-label="11 CTA final"><div class="ff4-cta-grid"><div class="ff4-col" style="gap:16px;align-items:flex-start">'
      . '<div class="ff4-eyebrow">SEM COMPROMISSO, COM ACOLHIMENTO</div><h2 class="ff4-h2">Vamos começar essa conversa?</h2>'
      . '<p class="ff4-p">O primeiro passo é entender onde você está e o que faz sentido a partir daqui. Escolha o caminho mais confortável para você.</p>'.btn('Agendar pelo WhatsApp',$WA).'</div>'
      . '<div class="ff4-panel ff4-contact"><a href="'.e($WA).'" target="_blank" rel="noopener">+55 48 99111-8771</a><a href="'.e($MAIL).'">contato@francielefalconi.com.br</a>'
      . '<a href="'.e($IG).'" target="_blank" rel="noopener">@francielefalconi.terapeuta</a><div class="loc">Florianópolis, SC · atendimento presencial e online</div></div></div></section>';
$foot = '<footer class="ff4-glass ff4-foot" data-screen-label="12 Rodape"><div class="ff4-foot-row"><img src="'.e($LOGO).'" alt="Fran Falconi" width="203" height="40">'
      . '<div class="ff4-foot-brand">FRANCIELE FALCONI · DESENVOLVIMENTO HUMANO &amp; SAÚDE EMOCIONAL</div></div>'
      . '<p class="ff4-foot-note">Este site é informativo e não substitui avaliação profissional individual. Em caso de emergência ou risco, procure ajuda imediata: <strong>CVV 188</strong> (24h, gratuito) ou <strong>SAMU 192</strong>.</p></footer>';

/* ================= 05 DEPOIMENTOS (container glass + 3 imagens nativas) ================= */
$depoHead = '<div class="ff4-eyebrow">QUEM JÁ PERCORREU ESSE CAMINHO</div>'
          . '<blockquote class="ff4-blockq" style="margin-top:32px">Cada processo é único, e documentado. Ao longo do Método Falconi você recebe devolutivas que registram a sua evolução, sessão a sessão.</blockquote>';
$depo = ff4c(array('_element_id'=>'ff4-depo'),array(
  ff4html('<div class="ff4-sec ff4-wrap" data-screen-label="05 Depoimentos">'.$depoHead.'</div>'),
  ff4c(array('_element_id'=>'ff4-depogrid','flex_direction'=>'row'),array(
    ff4img('ff4-placeholder-45','ff4-testi','Depoimento 1 (imagem a ser enviada)'),
    ff4img('ff4-placeholder-45','ff4-testi','Depoimento 2 (imagem a ser enviada)'),
    ff4img('ff4-placeholder-45','ff4-testi','Depoimento 3 (imagem a ser enviada)'),
  )),
));

/* ================= 09 SOBRE (container glass grid + foto nativa) ================= */
$sobreL = '<div class="ff4-col" style="gap:14px"><div class="ff4-eyebrow">SOBRE MIM</div><h2 class="ff4-h2">Presença, escuta ativa e cuidado genuíno.</h2><div class="ff4-script ff4-script-s">antes da técnica, a pessoa</div></div>';
$chips=''; foreach(array('NEUROCIÊNCIA','PSICANÁLISE','ABORDAGEM SISTÊMICA','DESENVOLVIMENTO EMOCIONAL') as $c){ $chips.='<span class="ff4-chip">'.e($c).'</span>'; }
$sobreR = '<div class="ff4-col" style="gap:18px;align-items:flex-start"><p class="ff4-p">Minha atuação une neurociência, psicanálise e abordagem sistêmica a ferramentas de desenvolvimento emocional. Acredito que ansiedade, exaustão e conflitos nas relações não surgem por acaso: são reflexos de histórias, padrões inconscientes e dinâmicas relacionais que precisam ser compreendidos para que a mudança aconteça de verdade.</p>'
        . '<p class="ff4-p">Cada processo começa com presença e escuta ativa. Antes de qualquer técnica, é preciso compreender a pessoa (ou a equipe) que está à minha frente, com cuidado genuíno pelo caminho que ela percorre.</p>'
        . '<div class="ff4-chips">'.$chips.'</div>'.btn('Agendar uma conversa',$WA).'</div>';
$sobre = ff4c(array('_element_id'=>'sobre','flex_direction'=>'row'),array(
  ff4c(array('_element_id'=>'ff4-sobre-l'),array(
    ff4html('<div class="ff4-sec ff4-wrap" data-screen-label="09 Sobre mim">'.$sobreL.'</div>'),
    ff4img('ff4-placeholder-45','ff4-photo','Foto da Franciele Falconi (imagem a ser enviada)'),
  )),
  ff4c(array('_element_id'=>'ff4-sobre-r'),array(ff4html('<div class="ff4-sec ff4-wrap">'.$sobreR.'</div>'))),
));

/* ================= montagem ================= */
function sec($html){ return ff4c(array(),array(ff4html('<div class="ff4-sec ff4-wrap">'.$html.'</div>'))); }
$data = array( ff4c(array('_element_id'=>'ff4-root'),array(
  sec($nav), sec($hero), sec($cam), sec($rec), $depo, sec($svc), sec($met), sec($emps), $sobre, sec($faqS), sec($cta), sec($foot),
), false) );

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
echo "Home OK (v4 liquid glass): page ID $pid ; top-level=".count($data)." ; secoes=".count($data[0]['elements'])."\n";
