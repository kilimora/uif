<?php
/**
 * ═══════════════════════════════════════════════════════════════════════════
 * AK CHILDREN INNOVATION · AGRIKONNEKT PRODUCT MICROSITE
 * Single front-controller entry point — every route below is served
 * from this one file, so there is nothing else for the server to 404 on.
 *
 * Routes:
 *   /                        → Home (Product, Impact, Stakeholders, Ecosystem,
 *                               The Journey, Africa & Policy, brief Initiative teasers)
 *   /financial-enablement    → Financial Enablement — Live Credit Score (full page)
 *   /child-aid-alignment     → Child Aid Alignment (full page)
 *   /sport-development       → Sport & Community Development (full page)
 *
 * Any other path falls back to Home rather than throwing a 404, so a typo'd
 * or stale link still lands somewhere useful. Pair with the accompanying
 * .htaccess so LiteSpeed routes all of the above straight into this file.
 *
 * Fonts: EB Garamond (editorial) + Ubuntu (interface) — the only two typefaces used site-wide
 *        (interface, subpages — local/system, falls back to Arial Narrow)
 *        + JetBrains Mono (data)
 * ═══════════════════════════════════════════════════════════════════════════
 */
$siteYear = date('Y');

$routes = ['financial-enablement', 'child-aid-alignment', 'sport-development', 'climate-resilience', 'child-resilience-index', 'terms-of-use', 'privacy-policy'];
$uri    = trim((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
$page   = in_array($uri, $routes, true) ? $uri : 'home';

// Hash-link prefix: on the homepage, in-page anchors are bare (#product);
// on every subpage they need to route back to the homepage first (/#product).
$hp = $page === 'home' ? '' : '/';

$meta = [
  'home' => [
    'title' => 'AK Children Initiative',
    'desc'  => 'AgriKonnekt is a climate resilient agricultural infrastructure platform engineered so that every gain a farm makes reaches the child waiting for it at home, across Kenya and Sub Saharan Africa.',
  ],
  'financial-enablement' => [
    'title' => 'Financial Enablement — AgriKonnekt Live Credit Score',
    'desc'  => 'The AgriKonnekt financial enablement engine: an explainable, alternative-data credit score for unbanked smallholder households, built from mobile money, airtime, cooperative savings, utility repayment and verified farm signals across eight scored factors.',
  ],
  'child-aid-alignment' => [
    'title' => 'Child Aid Alignment — AgriKonnekt',
    'desc'  => 'How AgriKonnekt maps onto the priorities, funding mechanisms, and evidence standards that any humanitarian, multilateral, or development partner working on child wellbeing already operates by, aligned with the Sustainable Development Goals.',
  ],
  'sport-development' => [
    'title' => 'Sport &amp; Community Development — AgriKonnekt',
    'desc'  => 'The evidence, the ecosystem, and the pathway: how structured sport, arts and vocational development close the gap in a child\'s afternoon, and how it aligns with the Sustainable Development Goals.',
  ],
  'climate-resilience' => [
    'title' => 'Climate &amp; Health Resilience — AgriKonnekt',
    'desc'  => 'How the same verified household record AgriKonnekt already collects becomes an early warning, forecasting and point-of-care system, so a climate shock reaches a family as a warning, not a surprise.',
  ],
  'child-resilience-index' => [
    'title' => 'Child Resilience Index — AgriKonnekt',
    'desc'  => 'An open, explainable composite score combining food and nutrition security, school-continuity and child-labour risk, and climate shock exposure, built from real household signals and a live weather feed, to route protection and support before a season goes wrong for a child, never to withhold aid from one.',
  ],
  'terms-of-use' => [
    'title' => 'Terms of Use — Kilimora / AgriKonnekt',
    'desc'  => 'Terms of Use governing access to the AgriKonnekt Expression of Interest microsite operated by Kilimora.',
  ],
  'privacy-policy' => [
    'title' => 'Privacy Policy — Kilimora / AgriKonnekt',
    'desc'  => 'How Kilimora collects, uses, and protects information across the AgriKonnekt Expression of Interest microsite and platform.',
  ],
];
$m = $meta[$page];

// The two legal pages are short, self-contained documents with their own
// header/footer and styling. Rather than shoehorning them into the large
// home/subpage template below, render them from a dedicated partial and
// stop — everything after this point is only relevant to the main site.
if ($page === 'terms-of-use' || $page === 'privacy-policy') {
    require __DIR__ . '/legal-pages.php';
    exit;
}
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $m['title'] ?></title>
<meta name="description" content="<?= $m['desc'] ?>">
<meta name="robots" content="index, follow">
<link rel="canonical" href="<?= 'https://uif.kilimora.africa/' . ($page === 'home' ? '' : $page) ?>">
<link rel="icon" id="klFavicon" type="image/png" href="https://kilimora.africa/wp-content/uploads/2026/06/AgriKonnect-6-No-Background-scaled.png?v=7">
<link rel="apple-touch-icon" id="klAppleIcon" href="https://kilimora.africa/wp-content/uploads/2026/06/AgriKonnect-6-No-Background-scaled.png?v=7">
<script>
/* Tints the tab icon with the same green (#5E8177) used on the header
   logo (see the #navyGreenTint SVG filter below): flood every opaque
   pixel with the tint color while keeping the original alpha mask —
   the raster equivalent of feFlood + feComposite(in). Falls back to
   the plain logo if the canvas cannot be read (e.g. no CORS headers
   from the image host). */
(function(){
  var TINT = '#5E8177';
  var SRC  = document.getElementById('klFavicon').href;
  var img  = new Image();
  img.crossOrigin = 'anonymous';
  img.onload = function(){
    try{
      var size   = 64;
      var canvas = document.createElement('canvas');
      canvas.width = canvas.height = size;
      var ctx = canvas.getContext('2d');
      ctx.drawImage(img, 0, 0, size, size);
      var data = ctx.getImageData(0, 0, size, size);
      var px = data.data;
      var r = parseInt(TINT.slice(1,3),16), g = parseInt(TINT.slice(3,5),16), b = parseInt(TINT.slice(5,7),16);
      for (var i = 0; i < px.length; i += 4){
        if (px[i+3] > 0){ px[i] = r; px[i+1] = g; px[i+2] = b; }
      }
      ctx.putImageData(data, 0, 0);
      var url = canvas.toDataURL('image/png');
      document.getElementById('klFavicon').href = url;
      var apple = document.getElementById('klAppleIcon');
      if (apple) apple.href = url;
    } catch(e){ /* tainted canvas (no CORS) — keep the untinted favicon */ }
  };
  img.src = SRC;
})();
</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,400&family=EB+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
  /* void + lime : the technical register */
  --void:#0A0C09;--void-2:#12150E;--void-3:#191D13;
  --lime:#C9F04D;--lime-soft:#DFF593;--lime-dim:#8FAE3A;
  /* cream + gold + forest : the household register */
  --cream:#FAF6E8;--cream-warm:#F2EAC9;
  --forest:#1B5C38;--forest-mid:#2E8B57;
  --gold:#C8922A;--gold-bright:#E6A832;
  --ink:#1B1912;--mid:#4A4738;--soft:#7C7863;
  --clay:#B5651D;--bloom:#C14E63;
  --rule:rgba(200,146,42,.28);--rule-void:rgba(201,240,77,.16);
  --nav:78px;--r-card:20px;--r-soft:12px;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth;-webkit-text-size-adjust:100%;background:#080906}
body{font-family:'Ubuntu',sans-serif;background:var(--cream);color:var(--ink);overflow-x:hidden;line-height:1.65}
a{color:inherit;text-decoration:none}
ul{list-style:none}
h1,h2,h3,h4{font-family:'EB Garamond',serif;color:var(--void);line-height:1.1;letter-spacing:-.01em}
img,video{max-width:100%;display:block}
code,.mono{font-family:'JetBrains Mono',monospace}

body{-webkit-user-select:none;-moz-user-select:none;user-select:none;-webkit-touch-callout:none}
img,video,.kl-logo img,.kl-nodrag{-webkit-user-drag:none;user-drag:none;-webkit-touch-callout:none}
img[src*="-scaled."]:not([src*="AgriKonnect"]):not([src*="emblem"]),.hero video,.papi-hero video{filter:brightness(1.55)}
video::-webkit-media-controls{display:none !important}
input,textarea,button{user-select:text}

.wrap{max-width:1240px;margin:0 auto;padding:0 32px}
.eyebrow{font-family:'EB Garamond',serif;font-style:italic;font-weight:500;font-size:22px;letter-spacing:.01em;color:var(--gold);margin-bottom:20px;display:flex;align-items:center;gap:10px}
.eyebrow::before{content:'';display:inline-block;width:22px;height:1px;background:var(--gold);opacity:.7;vertical-align:middle}
.section-void .eyebrow,.section-forest .eyebrow{color:var(--lime-soft)}
.section-void .eyebrow::before,.section-forest .eyebrow::before{background:var(--lime)}
.tag{font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;padding:5px 11px;border-radius:100px;display:inline-flex;align-items:center;gap:6px}
.tag.ok{background:rgba(201,240,77,.12);color:var(--lime-soft);border:1px solid rgba(201,240,77,.3)}
.tag.warn{background:rgba(200,146,42,.12);color:var(--gold-bright);border:1px solid rgba(200,146,42,.35)}
.tag.off{background:rgba(255,255,255,.05);color:rgba(255,255,255,.32);border:1px solid rgba(255,255,255,.12)}
.section{padding:120px 0}
.section-void{background:var(--void);color:var(--cream)}
.section-void h2,.section-void h3,.section-void h4{color:#fff}
.section-forest{background:var(--forest)}
@media(max-width:900px){.section{padding:76px 0}.wrap{padding:0 20px}}

/* ── HEADER ── */
/* Whole bar +55% brighter (same brightness(1.55) convention used for hero video/imagery above). */
.kl-h{position:fixed;top:0;left:0;right:0;z-index:9999;height:var(--nav);display:flex;align-items:center;justify-content:space-between;padding:0 40px;background:transparent;border-bottom:1px solid transparent;transition:.35s;filter:brightness(1.55)}
.kl-h.solid{background:rgba(10,12,9,.92);backdrop-filter:blur(16px);border-bottom-color:rgba(201,240,77,.14)}
.kl-logo{display:flex;align-items:center}
.kl-logo img{height:40px;width:auto}
/* Nav-links side occupies the right ~2/3 of the bar (logo takes the left third) — gets an
   extra lift stacked on top of the header's own filter (1.55 × 1.25 ≈ 1.94x total here). */
.kl-navwrap{display:flex;align-items:center;gap:34px;filter:brightness(1.25)}
.kl-nav-link{font-size:13px;font-weight:600;letter-spacing:.13em;text-transform:uppercase;color:rgba(255,255,255,.78);transition:.2s}
.kl-nav-link:hover{color:var(--lime)}
.kl-ham{display:none;flex-direction:column;gap:5px;background:none;border:0;cursor:pointer;padding:8px}
.scroll-progress{position:fixed;top:0;left:0;height:2px;background:var(--lime);z-index:10001;width:0%;transition:width .1s linear}
.reveal{opacity:0;transform:translateY(28px);transition:opacity .8s cubic-bezier(.16,.8,.3,1),transform .8s cubic-bezier(.16,.8,.3,1)}
.reveal.in{opacity:1;transform:translateY(0)}

/* ── RULE OF THREE: staggered child motion so hierarchy reads through motion, not just layout ── */
.reveal .defines-item,.reveal .sd-dom-card,.reveal .feat-card,.reveal .sol-card,.reveal .gate-tile,.reveal .elig-card,.reveal .voice-card,.reveal .mf-card,.reveal .hh-card,.reveal .sd-eco-card,.reveal .sd-path-step,.reveal .core-col,.reveal .traj-step,.reveal .policy-card{opacity:0;transform:translateY(18px);transition:opacity .6s cubic-bezier(.16,.8,.3,1),transform .6s cubic-bezier(.16,.8,.3,1)}
.reveal.in .defines-item,.reveal.in .sd-dom-card,.reveal.in .feat-card,.reveal.in .sol-card,.reveal.in .gate-tile,.reveal.in .elig-card,.reveal.in .voice-card,.reveal.in .mf-card,.reveal.in .hh-card,.reveal.in .sd-eco-card,.reveal.in .sd-path-step,.reveal.in .core-col,.reveal.in .traj-step,.reveal.in .policy-card{opacity:1;transform:translateY(0)}
.reveal.in .defines-item:nth-child(3n+1),.reveal.in .sd-dom-card:nth-child(3n+1),.reveal.in .feat-card:nth-child(3n+1),.reveal.in .gate-tile:nth-child(3n+1),.reveal.in .elig-card:nth-child(3n+1),.reveal.in .voice-card:nth-child(3n+1),.reveal.in .sd-eco-card:nth-child(3n+1),.reveal.in .sd-path-step:nth-child(3n+1),.reveal.in .core-col:nth-child(3n+1),.reveal.in .traj-step:nth-child(3n+1),.reveal.in .policy-card:nth-child(3n+1){transition-delay:0s}
.reveal.in .defines-item:nth-child(3n+2),.reveal.in .sd-dom-card:nth-child(3n+2),.reveal.in .feat-card:nth-child(3n+2),.reveal.in .gate-tile:nth-child(3n+2),.reveal.in .elig-card:nth-child(3n+2),.reveal.in .voice-card:nth-child(3n+2),.reveal.in .sd-eco-card:nth-child(3n+2),.reveal.in .sd-path-step:nth-child(3n+2),.reveal.in .core-col:nth-child(3n+2),.reveal.in .traj-step:nth-child(3n+2),.reveal.in .policy-card:nth-child(3n+2){transition-delay:.12s}
.reveal.in .defines-item:nth-child(3n+3),.reveal.in .sd-dom-card:nth-child(3n+3),.reveal.in .feat-card:nth-child(3n+3),.reveal.in .gate-tile:nth-child(3n+3),.reveal.in .elig-card:nth-child(3n+3),.reveal.in .voice-card:nth-child(3n+3),.reveal.in .sd-eco-card:nth-child(3n+3),.reveal.in .sd-path-step:nth-child(3n+3),.reveal.in .core-col:nth-child(3n+3),.reveal.in .traj-step:nth-child(3n+3),.reveal.in .policy-card:nth-child(3n+3){transition-delay:.24s}
@media(prefers-reduced-motion:reduce){.reveal .defines-item,.reveal .sd-dom-card,.reveal .feat-card,.reveal .sol-card,.reveal .gate-tile,.reveal .elig-card,.reveal .voice-card,.reveal .mf-card,.reveal .hh-card,.reveal .sd-eco-card,.reveal .sd-path-step,.reveal .core-col,.reveal .traj-step,.reveal .policy-card{opacity:1;transform:none;transition:none}}
.kl-ham span{width:22px;height:2px;background:#fff;border-radius:2px}
@media(max-width:960px){.kl-navwrap>ul{display:none}.kl-ham{display:flex}}

/* ── HEADER DROPDOWNS (Product / Initiative groupings) ── */
.kl-navwrap>ul{display:flex;gap:34px;align-items:center}
.kl-nav-item{position:relative}
.kl-nav-item>.kl-nav-link{display:inline-flex;align-items:center;gap:6px}
.kl-caret{width:7px;height:7px;border-right:1.4px solid currentColor;border-bottom:1.4px solid currentColor;transform:rotate(45deg);transition:transform .25s;opacity:.75;margin-top:-2px}
.kl-nav-item:hover .kl-caret,.kl-nav-item.open .kl-caret{transform:rotate(225deg);margin-top:3px}
.kl-dropdown{position:absolute;top:100%;left:50%;transform:translateX(-50%);padding-top:16px;opacity:0;visibility:hidden;transition:opacity .2s,visibility .2s;z-index:20}
.kl-nav-item:hover .kl-dropdown,.kl-nav-item.open .kl-dropdown{opacity:1;visibility:visible}
.kl-dropdown-inner{min-width:238px;background:rgba(10,12,9,.97);backdrop-filter:blur(16px);border:1px solid rgba(201,240,77,.16);border-radius:14px;padding:9px;box-shadow:0 24px 50px rgba(0,0,0,.45)}
.kl-dropdown-inner a{display:block;padding:11px 14px;border-radius:9px;font-size:13px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.8);transition:background .15s,color .15s}
.kl-dropdown-inner a:hover{background:rgba(201,240,77,.1);color:var(--lime)}
.kl-dropdown-inner a small{display:block;font-family:'Ubuntu',sans-serif;text-transform:none;letter-spacing:0;font-weight:400;font-size:13px;color:rgba(255,255,255,.4);margin-top:3px}

/* ── MOBILE DRAWER ACCORDION GROUPS ── */
.mob-group{border-bottom:1px solid rgba(255,255,255,.08)}
.mob-group .mob-group-head{width:100%;display:flex;align-items:center;justify-content:space-between;background:none;border:0;cursor:pointer;font-family:'Ubuntu',sans-serif;font-size:22px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:#fff;padding:16px 0}
.mob-group-head .kl-caret{width:7px;height:7px;border-right:1.4px solid currentColor;border-bottom:1.4px solid currentColor;transform:rotate(45deg);transition:transform .25s}
.mob-group.open .mob-group-head .kl-caret{transform:rotate(225deg);margin-top:4px}
.mob-group-body{max-height:0;overflow:hidden;transition:max-height .3s ease}
.mob-group.open .mob-group-body{max-height:260px}
.mob-group-body a{padding:12px 0 12px 18px!important;font-size:16px!important;color:rgba(255,255,255,.72)!important;border-bottom:0!important}
.mob-drawer a.mob-link{border-bottom:1px solid rgba(255,255,255,.08)}

/* ── GATEWAY TILES (homepage doors into sport & finance) ── */
.gate-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:22px;margin-top:40px}
.gate-tile{position:relative;overflow:hidden;display:flex;flex-direction:column;background:var(--void-2,#12150E);border-radius:22px;padding:30px 28px 26px;min-height:220px;transition:.3s}
.gate-tile::before{content:'';position:absolute;top:0;left:32px;right:32px;height:2px;border-radius:0 0 2px 2px}
.gate-tile--sport::before{background:var(--lime)}
.gate-tile--finance::before{background:var(--gold-bright)}
.gate-tile--child::before{background:#5FB4E0}
.gate-tile--climate::before{background:var(--forest-mid)}
.gate-tile--resilience::before{background:var(--bloom)}
.gate-tile:hover{transform:translateY(-4px);border-color:rgba(255,255,255,.22);box-shadow:0 24px 50px rgba(0,0,0,.4)}
.gate-kicker{font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:var(--lime-soft)}
.gate-tile--finance .gate-kicker{color:var(--gold-bright)}
.gate-tile--child .gate-kicker{color:#5FB4E0}
.gate-tile--climate .gate-kicker{color:var(--forest-mid)}
.gate-tile--resilience .gate-kicker{color:#E8A6B2}
.gate-tile h3{color:#fff;font-size:28px;margin:12px 0 10px}
.gate-tile p{color:rgba(255,255,255,.6);font-size:16px;line-height:1.65;flex:1}
.gate-cta{margin-top:16px;font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#fff;display:inline-flex;align-items:center;gap:6px}
.gate-tile--sport .gate-cta{color:var(--lime)}
.gate-tile--finance .gate-cta{color:var(--gold-bright)}
.gate-tile--child .gate-cta{color:#5FB4E0}
.gate-tile--climate .gate-cta{color:var(--forest-mid)}
.gate-tile--resilience .gate-cta{color:#E8A6B2}
@media(max-width:1180px){.gate-grid{grid-template-columns:1fr 1fr}}
@media(max-width:640px){.gate-grid{grid-template-columns:1fr}}

/* ── SYSTEMS ACCESS BOARD (homepage snapshot, commodity-board register) ── */
.sys-board{background:var(--void-2,#12150E);border-top:1px solid rgba(255,255,255,.1);border-bottom:1px solid rgba(255,255,255,.1)}
.sys-board-head{display:flex;align-items:baseline;justify-content:space-between;flex-wrap:wrap;gap:10px;padding:26px 0 14px}
.sys-board-tag{font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--lime-soft);display:inline-flex;align-items:center;gap:8px}
.sys-board-tag .dot{width:7px;height:7px;border-radius:50%;background:var(--lime);animation:fxpulse 1.8s infinite;display:inline-block}
.sys-board-note{font-family:'JetBrains Mono',monospace;font-size:13px;color:rgba(255,255,255,.4);letter-spacing:.03em}
.sys-board-note a{color:rgba(255,255,255,.65)}
.sys-row{display:grid;grid-template-columns:100px 1fr auto 140px;align-items:center;gap:18px;padding:16px 0;border-top:1px solid rgba(255,255,255,.08);transition:.2s}
.sys-row:hover{background:rgba(255,255,255,.03)}
.sys-status{font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;padding:5px 10px;border-radius:100px;text-align:center;white-space:nowrap}
.sys-status.s-live{color:var(--lime-soft);border:1px solid rgba(201,240,77,.35)}
.sys-status.s-proto{color:var(--gold-bright);border:1px solid rgba(230,168,50,.4)}
.sys-status.s-demo{color:rgba(255,255,255,.55);border:1px solid rgba(255,255,255,.2)}
.sys-main h4{color:#fff;font-size:22px;margin:0 0 4px;font-weight:700}
.sys-main p{color:rgba(255,255,255,.55);font-size:16px;line-height:1.5;margin:0;max-width:560px}
.sys-sdg{display:flex;gap:6px;flex-wrap:wrap}
.sys-access{font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:700;letter-spacing:.04em;color:var(--lime-soft);text-align:right;white-space:nowrap;transition:.2s}
.sys-row:hover .sys-access{color:var(--lime)}
@media(max-width:900px){
  .sys-row{grid-template-columns:76px 1fr;grid-template-areas:"status main" ". sdg" ". access";row-gap:8px}
  .sys-status{grid-area:status}
  .sys-main{grid-area:main}
  .sys-sdg{grid-area:sdg}
  .sys-access{grid-area:access;text-align:left}
}

/* ── SDG ALIGNMENT CHIPS (official UN SDG colours) ── */
.sdg-strip{display:flex;flex-wrap:wrap;gap:8px;margin-top:16px}
.sdg-chip{display:inline-flex;align-items:center;gap:8px;padding:5px 13px 5px 5px;border-radius:999px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.12);font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:600;letter-spacing:.03em;text-transform:uppercase;color:rgba(255,255,255,.7);white-space:nowrap}
.sdg-chip .sdg-num{width:20px;height:20px;border-radius:6px;display:flex;align-items:center;justify-content:center;font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:700;color:#fff;flex-shrink:0}

/* ── IN-PAGE SUBPAGE BAR (used by sections that behave like their own page) ── */
.subpage-bar{display:flex;align-items:center;gap:10px;margin-bottom:34px;font-family:'JetBrains Mono',monospace;font-size:13px;letter-spacing:.06em;text-transform:uppercase}
.subpage-crumb{color:rgba(255,255,255,.45);transition:.2s}
.subpage-crumb:hover{color:var(--lime)}
.subpage-crumb.is-current{color:var(--lime-soft)}
.subpage-sep{color:rgba(255,255,255,.25)}

/* ── HERO : PRODUCT LAUNCH ── */
.hero{position:relative;min-height:100vh;min-height:100svh;overflow:hidden;background:var(--void)}
.hero video{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.55;z-index:0}
.hero::after{content:'';position:absolute;inset:0;background:radial-gradient(ellipse at 30% 20%,rgba(201,240,77,.08),transparent 55%),linear-gradient(180deg,rgba(6,8,5,.35) 0%,rgba(6,8,5,.22) 35%,rgba(6,8,5,.3) 100%);z-index:1}
.hero-in{position:relative;z-index:2;width:100%;padding:calc(var(--nav) + 2vh) 0 2vh;box-sizing:border-box}
.hero-badge{display:inline-flex;align-items:center;gap:8px;font-family:'JetBrains Mono',monospace;font-size:clamp(8.5px,1.05vh,10.5px);font-weight:600;letter-spacing:.13em;text-transform:uppercase;color:var(--lime-soft);border:1px solid rgba(201,240,77,.3);padding:clamp(5px,.9vh,8px) 16px;border-radius:100px;margin-bottom:clamp(10px,1.5vh,20px);flex-shrink:0}
.hero-badge .dot{width:6px;height:6px;border-radius:50%;background:var(--lime);animation:pulse 2s infinite}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.25}}
.hero h1{font-size:clamp(24px,4.8vh,58px);color:#fff;max-width:1040px;margin-bottom:clamp(8px,1.2vh,16px);font-weight:600;letter-spacing:-.02em;line-height:1.08;flex-shrink:0}
.hero h1 em{font-style:italic;color:var(--lime-soft);font-weight:500}
.hero p.lead{font-family:'EB Garamond',serif;font-style:italic;font-size:clamp(12.5px,1.6vh,17.5px);color:rgba(255,255,255,.68);max-width:640px;margin-bottom:clamp(10px,1.7vh,24px);font-weight:400;line-height:1.5;flex-shrink:0}
.hero-cta{display:flex;flex-wrap:wrap;gap:12px;margin-bottom:clamp(12px,2vh,28px);flex-shrink:0}
.btn{display:inline-flex;align-items:center;gap:9px;font-size:clamp(8.5px,1vh,11px);font-weight:700;letter-spacing:.12em;text-transform:uppercase;padding:clamp(9px,1.4vh,15px) clamp(16px,2.4vw,30px);border-radius:100px;transition:transform .35s cubic-bezier(.34,1.56,.64,1),background .25s ease,box-shadow .35s ease;will-change:transform}
.btn:active{transform:scale(.96)}
.btn-solid{background:var(--lime);color:var(--void)!important}
.btn-solid:hover{background:#fff;transform:translateY(-2px) scale(1.02);box-shadow:0 10px 26px rgba(201,240,77,.28)}
.btn-line{border:1.4px solid rgba(255,255,255,.32);color:#fff!important}
.btn-line:hover{background:rgba(255,255,255,.1);transform:translateY(-2px) scale(1.02);box-shadow:0 10px 22px rgba(0,0,0,.28)}
.hero .hero-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:0;border-top:1px solid rgba(255,255,255,.14);flex-shrink:0}
.hero .hstat{padding:clamp(8px,1.3vh,16px) 20px 0;border-right:1px solid rgba(255,255,255,.1)}
.hero .hstat:last-child{border-right:0}
.hero .hstat b{display:block;font-family:'JetBrains Mono',monospace;font-size:clamp(15px,2vh,23px);color:var(--lime-soft);font-weight:700;line-height:1}
.hero .hstat span{font-size:clamp(8px,1vh,10.5px);color:rgba(255,255,255,.52);line-height:1.4;display:block;margin-top:clamp(4px,.8vh,8px)}
@media(max-width:800px){.hero .hero-stats{grid-template-columns:repeat(2,1fr);row-gap:clamp(8px,1.2vh,14px)}.hero .hstat{border-right:0}.hero .hstat:nth-child(odd){border-right:1px solid rgba(255,255,255,.1)}}
@media(max-height:640px){.hero .hero-stats{display:none}}



/* ── SECTION HEADS ── */
.stitle{font-size:clamp(26px,3.2vw,40px);font-weight:600;margin-bottom:20px;max-width:780px}
.slead{font-family:'EB Garamond',serif;font-style:italic;font-size:22px;color:var(--soft);max-width:660px;margin-bottom:36px;line-height:1.75}
.section-void .slead,.section-forest .slead{color:rgba(255,255,255,.6)}

/* ── FLOW DIVIDER : the connective thread between sections ── */
.flow-divider{position:relative;height:64px;overflow:hidden}
.flow-divider svg{position:absolute;left:50%;top:0;transform:translateX(-50%);width:1240px;max-width:none;height:100%}
@media(max-width:1300px){.flow-divider svg{width:100%}}

/* ── FILM GRAIN (cinematic texture on dark sections) ── */
.section-void,.section-forest,.hero{position:relative}
.section-void::before,.section-forest::before,.hero::before{content:'';position:absolute;inset:0;pointer-events:none;opacity:.05;mix-blend-mode:overlay;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");z-index:1}


/* ── MARQUEE TICKER ── */
.ticker{background:var(--void-2);border-bottom:1px solid rgba(201,240,77,.08);padding:15px 0;overflow:hidden;white-space:nowrap}
.ticker-track{display:inline-flex;gap:44px;animation:tickmove 34s linear infinite}
.ticker:hover .ticker-track{animation-play-state:paused}
@keyframes tickmove{from{transform:translateX(0)}to{transform:translateX(-50%)}}
.ticker-item{font-family:'JetBrains Mono',monospace;font-size:16px;font-weight:500;letter-spacing:.04em;color:rgba(255,255,255,.5);display:inline-flex;align-items:center;gap:10px}
.ticker-item b{color:var(--lime-soft);font-weight:700}
.ticker-item .sep{color:var(--gold);opacity:.5}

/* ── TRUSTED FRAMEWORKS MARQUEE ── */

/* ── SIGNAL SPLIT PANEL (the reveal) ── */
.signal-wrap{display:grid;grid-template-columns:1fr 1fr;border-radius:22px;overflow:hidden}
.signal-half{padding:40px 36px}
.signal-half.before{background:rgba(255,255,255,.02)}
.signal-half.after{background:rgba(201,240,77,.05);border-left:1px solid rgba(201,240,77,.14)}
.signal-h{font-family:'JetBrains Mono',monospace;font-size:13px;letter-spacing:.12em;text-transform:uppercase;margin-bottom:22px;display:block}
.signal-half.before .signal-h{color:rgba(255,255,255,.35)}
.signal-half.after .signal-h{color:var(--lime-soft)}
.signal-row{display:flex;align-items:center;justify-content:space-between;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.07);font-family:'JetBrains Mono',monospace;font-size:16px}
.signal-row:last-of-type{border-bottom:0}
.signal-half.before .signal-row{color:rgba(255,255,255,.28)}
.signal-half.after .signal-row{color:rgba(255,255,255,.75)}
.signal-caption{font-family:'EB Garamond',serif;font-style:italic;font-size:22px;margin-top:22px;line-height:1.6}
.signal-half.before .signal-caption{color:rgba(255,255,255,.4)}
.signal-half.after .signal-caption{color:var(--lime-soft)}
@media(max-width:820px){.signal-wrap{grid-template-columns:1fr}.signal-half.after{border-left:0;border-top:1px solid rgba(201,240,77,.14)}}

/* ── GIANT STAT INTERLUDE ── */
.stat-interlude{background:var(--void);padding:100px 0;text-align:center;overflow:hidden}
.stat-interlude .eyebrow{justify-content:center;display:inline-flex}
.stat-big{font-family:'EB Garamond',serif;font-weight:600;font-size:clamp(64px,14vw,196px);line-height:.92;color:#fff;letter-spacing:-.02em}
.stat-big em{color:var(--lime);font-style:normal}
.stat-interlude p{font-family:'JetBrains Mono',monospace;font-size:16px;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.4);margin-top:20px}

/* ── SDG RADIAL (UN emblem as core) ── */
/* ── SDG FLOWCHART (UN emblem as the source node) ── */
.sdg-flow{display:flex;flex-wrap:wrap;align-items:flex-start;justify-content:center;gap:0;margin:48px auto 0;max-width:1080px}
.sdg-node{display:flex;flex-direction:column;align-items:center;width:104px;flex-shrink:0}
.sdg-tile{width:92px;height:92px;border-radius:16px;overflow:hidden;box-shadow:0 6px 18px rgba(27,20,10,.12)}
.sdg-tile img{width:100%;height:100%;object-fit:cover;display:block}
.sdg-tile.un-tile{background:#fff;border:1px solid var(--rule);display:flex;align-items:center;justify-content:center;padding:10px;box-shadow:0 0 0 6px rgba(200,146,42,.1),0 6px 22px rgba(46,139,87,.22)}
.sdg-tile.un-tile img{object-fit:contain}
.sdg-node small{display:block;margin-top:8px;font-family:'JetBrains Mono',monospace;font-size:13px;letter-spacing:.06em;text-transform:uppercase;color:var(--soft);text-align:center;line-height:1.4}
.sdg-connector{width:30px;height:92px;flex-shrink:0;display:flex;align-items:center;justify-content:center}
.sdg-connector svg{width:100%;height:14px}
@media(max-width:900px){.sdg-connector{width:18px}.sdg-node{width:82px}.sdg-tile{width:74px;height:74px;border-radius:13px}}
@media(max-width:560px){.sdg-flow{gap:0 4px}.sdg-connector{display:none}.sdg-node{width:72px}.sdg-tile{width:66px;height:66px}}
.sdg-caption{text-align:center;font-family:'EB Garamond',serif;font-style:italic;color:var(--soft);font-size:19px;margin-top:36px}

/* ── SYSTEMIC CHANGE & STAKEHOLDER PRACTICE (Units.gr-pattern section) ── */
.stake-pills{display:flex;flex-wrap:wrap;justify-content:center;gap:10px;margin:34px 0 58px}
.stake-pill{font-family:'JetBrains Mono',monospace;font-size:13px;letter-spacing:.05em;text-transform:uppercase;padding:9px 17px;border:1px solid var(--rule);border-radius:999px;color:var(--mid);background:var(--cream);white-space:nowrap}
.stake-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:22px}
.stake-card{border-radius:var(--r-card);overflow:hidden;background:var(--cream);border:1px solid var(--rule);display:flex;flex-direction:column}
.stake-card .stake-img{aspect-ratio:4/3;overflow:hidden}
.stake-card .stake-img img{width:100%;height:100%;object-fit:cover;transition:transform .7s ease}
.stake-card:hover .stake-img img{transform:scale(1.07)}
.stake-card .stake-body{padding:22px 20px 26px;flex:1;display:flex;flex-direction:column}
.stake-card .stake-tag{font-family:'JetBrains Mono',monospace;font-size:13px;letter-spacing:.06em;text-transform:uppercase;color:var(--gold)}
.stake-card h4{font-size:28px;margin:7px 0 4px;color:var(--void)}
.stake-card .stake-sub{font-size:16px;color:var(--soft);font-style:italic;margin-bottom:14px;font-family:'EB Garamond',serif}
.stake-card ul{display:flex;flex-direction:column;gap:8px}
.stake-card li{font-size:16px;line-height:1.5;color:var(--mid);padding-left:15px;position:relative}
.stake-card li::before{content:'';position:absolute;left:0;top:7px;width:5px;height:5px;border-radius:50%;background:var(--gold)}
.stake-defines{display:grid;grid-template-columns:repeat(3,1fr);gap:44px;margin-top:64px;padding-top:50px;border-top:1px solid var(--rule)}
.stake-defines .sd-item{text-align:center}
.stake-defines .sd-icon{width:38px;height:38px;margin:0 auto 18px}
.stake-defines h5{font-family:'EB Garamond',serif;font-size:25px;letter-spacing:.02em;margin-bottom:10px;color:var(--void)}
.stake-defines p{font-size:19px;color:var(--mid);line-height:1.65;max-width:280px;margin:0 auto}
.stake-cta{display:inline-flex;align-items:center;gap:8px;margin-top:44px;font-family:'JetBrains Mono',monospace;font-size:16px;letter-spacing:.05em;text-transform:uppercase;color:var(--forest);border-bottom:1px solid var(--forest);padding-bottom:3px}
.stake-cta-wrap{text-align:center;margin-top:8px}
.mindmap-block{margin-top:8px}
.mindmap-wrap{max-width:760px;margin:8px auto 0;padding:20px}
@media(max-width:640px){.mindmap-wrap{padding:4px}.mindmap-block .slead{font-size:19px}}
@media(max-width:980px){.stake-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:760px){.stake-defines{grid-template-columns:1fr;gap:36px}}
@media(max-width:560px){.stake-grid{grid-template-columns:1fr}.stake-pills{gap:8px}.stake-pill{font-size:13px;padding:7px 13px}}

/* ── SDG GOALS : 2x4 grid, grey outline treatment ── */
.sdg-un-badge{display:flex;flex-direction:column;align-items:center;margin:44px auto 40px}
.sdg-un-badge .sdg-tile.un-tile{width:96px;height:96px}
.sdg-un-badge small{display:block;margin-top:8px;font-family:'JetBrains Mono',monospace;font-size:13px;letter-spacing:.06em;text-transform:uppercase;color:var(--soft)}
.sdg-goals-2x4{display:grid;grid-template-columns:repeat(4,1fr);grid-template-rows:repeat(2,1fr);gap:20px 20px;max-width:620px;margin:0 auto}
.sdg-goal{display:flex;flex-direction:column;align-items:center}
.sdg-goal .sdg-tile{width:100%;aspect-ratio:1;border-radius:14px;overflow:hidden;border:1.5px solid rgba(124,120,99,.4);background:#fff;box-shadow:none;padding:10px;display:flex;align-items:center;justify-content:center}
.sdg-goal .sdg-tile img{width:100%;height:100%;object-fit:contain;transition:filter .35s ease,opacity .35s ease}
.sdg-goal:hover .sdg-tile{border-color:var(--gold)}
.sdg-goal small{display:block;margin-top:9px;font-family:'JetBrains Mono',monospace;font-size:13px;letter-spacing:.05em;text-transform:uppercase;color:var(--soft);text-align:center;line-height:1.4}
@media(max-width:560px){.sdg-goals-2x4{max-width:360px;gap:14px}}

/* ── CALLIGRAPHY BLOCK (signature type moment) ── */
.calligraphy{font-family:'EB Garamond',serif;font-style:italic;font-weight:500;font-size:clamp(28px,3.2vw,42px);line-height:1.4;color:var(--forest);max-width:820px;margin:40px 0;letter-spacing:.002em}
.calligraphy.on-void{color:var(--lime-soft)}
.calligraphy .mark{color:var(--gold)}
.calligraphy.on-void .mark{color:var(--lime)}

.pull{position:relative;background:var(--cream-warm);border:1px solid var(--rule);border-radius:14px;padding:30px 30px 26px;margin:28px 0;font-family:'EB Garamond',serif;font-style:italic;font-size:25px;color:var(--ink)}
.pull::before{content:'"';position:absolute;top:-10px;left:26px;font-family:'EB Garamond',serif;font-style:italic;font-size:60px;color:var(--gold);opacity:.4;line-height:1}

/* ── CHALLENGE ── */
.challenge-grid{display:grid;grid-template-columns:1.1fr .9fr;gap:60px;align-items:center}
.challenge-grid p{margin-bottom:16px;color:var(--mid);font-size:22px}
.challenge-imgs{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.challenge-imgs img{border-radius:var(--r-card);object-fit:cover;height:100%;width:100%}
.challenge-imgs a:nth-child(2){margin-top:34px}
@media(max-width:860px){.challenge-grid{grid-template-columns:1fr}}

/* ── JOURNEY MAP (signature element) ── */
.journey{position:relative}
.jline{position:absolute;top:6px;bottom:6px;left:29px;width:2px;background:linear-gradient(180deg,var(--gold),var(--lime))}
.jitem{display:grid;grid-template-columns:60px 1fr;gap:30px;padding-bottom:64px;position:relative}
.jitem:last-child{padding-bottom:0}
.jdot{width:60px;height:60px;border-radius:50%;background:var(--void);border:2px solid var(--gold);color:var(--lime-soft);display:flex;align-items:center;justify-content:center;font-family:'JetBrains Mono',monospace;font-weight:700;font-size:16px;flex-shrink:0;z-index:1}
.jbody h3{font-size:22px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--gold);margin-bottom:12px;font-family:'JetBrains Mono',monospace}
.jbody .calligraphy{margin:6px 0 16px}
.jbody p.jnote{font-size:19px;color:var(--mid);max-width:640px}
@media(max-width:700px){.jitem{grid-template-columns:44px 1fr;gap:18px}.jdot{width:44px;height:44px;font-size:13px}.jline{left:21px}}

/* ── PRODUCT / SOLUTION CARDS ── */
.sol-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:20px}
.sol-card{background:rgba(201,240,77,.04);border-radius:var(--r-card);padding:32px 24px}
.sol-num{font-family:'JetBrains Mono',monospace;font-size:16px;color:var(--lime);margin-bottom:18px;letter-spacing:.04em}
.sol-card h4{font-size:28px;margin-bottom:10px;color:#fff}
.sol-card p{font-size:19px;color:rgba(255,255,255,.55);line-height:1.65}
@media(max-width:1100px){.sol-grid{grid-template-columns:repeat(3,1fr)}}
@media(max-width:920px){.sol-grid{grid-template-columns:1fr 1fr}}
@media(max-width:560px){.sol-grid{grid-template-columns:1fr}}
.credit-spotlight{position:relative;overflow:hidden;display:grid;grid-template-columns:1.05fr .95fr;gap:44px;margin-top:28px;padding:36px 40px;border-radius:var(--r-card);background:rgba(201,240,77,.05)}
.credit-spotlight::before{content:'';position:absolute;top:0;left:40px;right:40px;height:2px;background:var(--lime);border-radius:0 0 3px 3px}
.credit-spotlight::after{content:'✦';position:absolute;top:16px;right:26px;color:var(--lime);font-size:16px;opacity:.55}
.credit-spotlight h4{font-family:'EB Garamond',serif;font-size:34px;color:#fff;margin:8px 0 14px;line-height:1.25}
.credit-spotlight .cs-left p{font-size:19px;color:rgba(255,255,255,.62);line-height:1.75}
.cs-list{display:flex;flex-direction:column;gap:14px}
.cs-list li{font-size:16px;line-height:1.6;color:rgba(255,255,255,.62);padding-left:18px;position:relative}
.cs-list li::before{content:'';position:absolute;left:0;top:6px;width:6px;height:6px;border-radius:50%;background:var(--lime)}
@media(max-width:920px){.credit-spotlight{grid-template-columns:1fr;padding:28px 26px;gap:24px}}
.mf-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-top:34px}
.mf-card{background:rgba(255,255,255,.025);border-radius:var(--r-card);padding:24px 20px}
.mf-card.mf-highlight{background:rgba(201,240,77,.06);border-color:rgba(201,240,77,.28)}
.mf-kicker{font-family:'JetBrains Mono',monospace;font-size:13px;letter-spacing:.05em;text-transform:uppercase;color:var(--lime);margin-bottom:4px}
.mf-sub{font-family:'EB Garamond',serif;font-style:italic;font-size:16px;color:rgba(255,255,255,.45);margin-bottom:16px}
.mf-card ul{display:flex;flex-direction:column;gap:12px}
.mf-card li{font-size:16px;line-height:1.6;color:rgba(255,255,255,.6)}
.mf-card li strong{color:#fff;font-weight:600}
@media(max-width:1080px){.mf-grid{grid-template-columns:1fr 1fr}}
@media(max-width:560px){.mf-grid{grid-template-columns:1fr}}
.mf-grid-6{grid-template-columns:repeat(3,1fr)}
@media(max-width:1080px){.mf-grid-6{grid-template-columns:1fr 1fr}}
@media(max-width:560px){.mf-grid-6{grid-template-columns:1fr}}
.mf-grid-12{grid-template-columns:repeat(4,1fr)}
@media(max-width:1080px){.mf-grid-12{grid-template-columns:1fr 1fr}}
@media(max-width:560px){.mf-grid-12{grid-template-columns:1fr}}
.mf-pts{color:var(--lime)}
.scorecard-block{margin-top:36px;padding:30px 32px;border-radius:var(--r-card);background:rgba(255,255,255,.025)}
.sc-head{display:flex;align-items:baseline;gap:14px;flex-wrap:wrap}
.sc-note{font-family:'EB Garamond',serif;font-style:italic;font-size:16px;color:rgba(255,255,255,.45)}
.sc-caption{margin-top:16px;font-size:16px;color:rgba(255,255,255,.4);font-family:'JetBrains Mono',monospace;letter-spacing:.02em}
@media(max-width:640px){.scorecard-block{padding:22px 18px}}
.traj-row{display:flex;align-items:stretch;gap:0;margin-top:28px;flex-wrap:wrap}
.traj-step{flex:1;min-width:230px;background:rgba(255,255,255,.025);border-radius:var(--r-card);padding:26px 22px}
.traj-yr{font-family:'JetBrains Mono',monospace;font-size:13px;letter-spacing:.06em;text-transform:uppercase;color:var(--lime);margin-bottom:10px}
.traj-step h5{font-family:'EB Garamond',serif;font-size:25px;color:#fff;margin-bottom:10px;line-height:1.3}
.traj-step p{font-size:16px;line-height:1.65;color:rgba(255,255,255,.58)}
.traj-arrow{display:flex;align-items:center;justify-content:center;padding:0 14px;color:var(--lime);font-size:28px;opacity:.55}
.traj-close{margin-top:26px;font-family:'EB Garamond',serif;font-style:italic;font-size:22px;color:rgba(255,255,255,.68);max-width:640px;line-height:1.6}
@media(max-width:820px){.traj-row{flex-direction:column}.traj-arrow{transform:rotate(90deg);padding:8px 0}}

/* ── IMPACT CHART ── */
.impact-wrap{display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center}
.chart-card{background:var(--cream-warm);border-radius:var(--r-card);padding:36px 30px}
.chart-card h4{font-size:16px;letter-spacing:.06em;text-transform:uppercase;color:var(--soft);margin-bottom:24px;font-family:'JetBrains Mono',monospace;font-weight:600}
.bar-row{margin-bottom:20px}
.bar-row:last-child{margin-bottom:0}
.bar-label{display:flex;justify-content:space-between;font-size:16px;color:var(--mid);margin-bottom:7px;font-weight:500}
.bar-label b{color:var(--forest);font-family:'JetBrains Mono',monospace;font-size:19px}
.bar-track{height:10px;background:rgba(27,92,56,.1);border-radius:100px;overflow:hidden}
.bar-fill{height:100%;border-radius:100px;background:linear-gradient(90deg,var(--forest),var(--forest-mid))}
.chart-note{font-size:13px;color:var(--soft);margin-top:22px;line-height:1.6;font-style:italic;font-family:'EB Garamond',serif}
@media(max-width:860px){.impact-wrap{grid-template-columns:1fr}}

/* ── SDG STRIP (real icons) ── */
.sdg-grid{display:grid;grid-template-columns:repeat(8,1fr);gap:14px;margin-top:40px}
.sdg-tile{border-radius:14px;overflow:hidden;aspect-ratio:1;background:#fff;border:1px solid var(--rule)}
.sdg-tile img{width:100%;height:100%;object-fit:cover}
@media(max-width:860px){.sdg-grid{grid-template-columns:repeat(4,1fr)}}

/* ── UNICEF STRATEGIC ALIGNMENT ── */
.pillar-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:16px}
.pillar{background:rgba(255,255,255,.05);border-radius:16px;padding:24px 18px}
.pillar .pnum{font-family:'JetBrains Mono',monospace;font-size:13px;color:var(--lime);margin-bottom:14px}
.pillar h4{font-size:22px;color:#fff;margin-bottom:8px}
.pillar p{font-size:16px;color:rgba(255,255,255,.5);line-height:1.6}
@media(max-width:960px){.pillar-grid{grid-template-columns:1fr 1fr}}
@media(max-width:560px){.pillar-grid{grid-template-columns:1fr}}
.exec-list{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:20px}
.exec-item{position:relative;padding:6px 0 6px 22px}
.exec-item::before{content:'';position:absolute;left:0;top:13px;width:8px;height:8px;background:var(--lime);border-radius:2px;transform:rotate(45deg)}
.exec-item b{display:block;color:#fff;font-family:'EB Garamond',serif;font-size:22px;margin-bottom:4px}
.exec-item span{font-size:16px;color:rgba(255,255,255,.55)}
@media(max-width:760px){.exec-list{grid-template-columns:1fr}}

/* ── ECOSYSTEM / SYSTEMIC CHANGE : intertwined partner web ── */
.eco-tree{position:relative;width:100%;max-width:1080px;margin:56px auto 0;aspect-ratio:1000/760}
.eco-tree svg{position:absolute;inset:0;width:100%;height:100%}
.eco-node{position:absolute;transform:translate(-50%,-100%);text-align:center;width:148px;pointer-events:none}
.eco-node .dot{width:9px;height:9px;border-radius:50%;margin:0 auto 9px;background:var(--gold)}
.eco-node b{display:block;font-family:'EB Garamond',serif;font-weight:600;font-style:italic;font-size:19px;line-height:1.25;color:var(--void)}
.eco-node.below{transform:translate(-50%,14px)}
.eco-node.root{transform:translate(-50%,14px)}
.eco-node.root b{font-size:25px;color:var(--forest);font-style:normal;font-weight:700}
.eco-node.root .dot{width:16px;height:16px;background:var(--forest);margin-bottom:10px}
.dot-forest{background:var(--forest)!important}
.dot-gold{background:var(--gold)!important}
.dot-clay{background:var(--clay)!important}
.dot-lime{background:var(--lime-dim)!important}
.dot-bloom{background:var(--bloom)!important;width:13px!important;height:13px!important}
.eco-legend{display:flex;flex-wrap:wrap;gap:14px 26px;justify-content:center;margin:34px auto 0;max-width:920px}
.eco-legend-item{display:flex;align-items:center;gap:8px;font-family:'JetBrains Mono',monospace;font-size:13px;letter-spacing:.05em;text-transform:uppercase;color:var(--mid)}
.eco-legend-item i{width:10px;height:10px;border-radius:50%;display:inline-block;flex-shrink:0}
.eco-list{display:grid;grid-template-columns:1fr 1fr;gap:0 60px;margin-top:64px}
.eco-row{padding:20px 0;border-top:1px solid var(--rule);display:flex;gap:12px;align-items:flex-start}
.eco-row .tag{width:9px;height:9px;border-radius:50%;flex-shrink:0;margin-top:7px}
.eco-row h4{font-family:'EB Garamond',serif;font-size:22px;margin-bottom:6px;color:var(--forest)}
.eco-row p{font-size:16px;color:var(--soft);line-height:1.65}
@media(max-width:760px){.eco-list{grid-template-columns:1fr}.eco-node{width:104px}.eco-node b{font-size:16px}}

/* ── SPORT & CHILD DEVELOPMENT (merged section) ── */
.sd-ev-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:20px;margin-top:30px}
@media(max-width:820px){.sd-ev-grid{grid-template-columns:1fr}}
.sd-ev-card{position:relative;overflow:hidden;background:var(--void-2,#12150E);border-radius:20px;padding:28px 28px 26px}
.sd-ev-card::before{content:'';position:absolute;top:0;left:24px;right:24px;height:2px;background:var(--lime);border-radius:0 0 2px 2px}
.sd-ev-tag{font-family:'JetBrains Mono',monospace;font-size:13px;letter-spacing:.08em;text-transform:uppercase;color:var(--lime);margin-bottom:10px}
.sd-ev-card p{font-size:19px;line-height:1.75;color:rgba(255,255,255,.68)}
.sd-ev-src{margin-top:16px;font-size:13px;color:rgba(255,255,255,.35);font-family:'JetBrains Mono',monospace}
.sd-dom-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-top:20px}
@media(max-width:980px){.sd-dom-grid{grid-template-columns:1fr 1fr}}
@media(max-width:560px){.sd-dom-grid{grid-template-columns:1fr}}
.sd-dom-card{text-align:center;padding:30px 20px;background:var(--void-2,#12150E);border-radius:20px;transition:.3s}
.sd-dom-icon{width:38px;height:38px;margin:0 auto 16px}
.sd-dom-card h5{font-size:25px;margin-bottom:8px;color:#fff}
.sd-dom-card p{font-size:16px;color:rgba(255,255,255,.55);line-height:1.65}

/* ── UNICEF Innovation Fund eligibility checklist (cream/household register) ── */
.elig-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin:28px 0 38px}
@media(max-width:900px){.elig-grid{grid-template-columns:1fr 1fr}}
@media(max-width:560px){.elig-grid{grid-template-columns:1fr}}
.elig-card{background:#fff;border-radius:14px;padding:20px 22px;display:flex;gap:14px;align-items:flex-start;box-shadow:0 1px 3px rgba(27,25,18,.06)}
.elig-check{flex:none;width:26px;height:26px;border-radius:50%;background:var(--forest);color:#fff;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:700}
.elig-card h5{font-size:19px;color:var(--ink);margin:0 0 4px;font-weight:700}
.elig-card p{font-size:16px;color:var(--mid);line-height:1.5;margin:0}

/* ── Open Core comparison (open layer vs commercial platform) ── */
.core-wrap{display:grid;grid-template-columns:1fr 1fr;gap:22px;margin-top:28px}
@media(max-width:860px){.core-wrap{grid-template-columns:1fr}}
.core-col{border-radius:18px;padding:28px 26px}
.core-col.open{background:var(--void-2,#12150E)}
.core-col.commercial{background:rgba(255,255,255,.02)}
.core-tag{font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;display:inline-block;margin-bottom:10px}
.core-col.open .core-tag{color:var(--lime-soft)}
.core-col.commercial .core-tag{color:rgba(255,255,255,.45)}
.core-col h4{color:#fff;font-size:28px;margin-bottom:10px}
.core-col p.core-lead{font-size:16px;color:rgba(255,255,255,.55);line-height:1.6;margin-bottom:18px}
.core-col ul{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:11px}
.core-col li{font-size:16px;color:rgba(255,255,255,.75);line-height:1.55;padding-left:20px;position:relative}
.core-col.open li::before{content:"✓";position:absolute;left:0;color:var(--lime-soft);font-weight:700}
.core-col.commercial li::before{content:"$";position:absolute;left:0;color:rgba(255,255,255,.4);font-weight:700}

.sd-eco-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-top:24px}
@media(max-width:920px){.sd-eco-grid{grid-template-columns:1fr 1fr}}
@media(max-width:560px){.sd-eco-grid{grid-template-columns:1fr}}
.sd-eco-card{background:var(--void-2,#12150E);border-radius:12px;padding:20px 22px}
.sd-eco-kicker{font-family:'JetBrains Mono',monospace;font-size:13px;letter-spacing:.07em;text-transform:uppercase;color:var(--gold-bright);margin-bottom:8px}
.sd-eco-card h5{font-size:22px;margin-bottom:8px;color:#fff}
.sd-eco-card p{font-size:16px;color:rgba(255,255,255,.55);line-height:1.6}
.sd-mech-strip{display:flex;flex-wrap:wrap;gap:10px;margin-top:26px}
.sd-mech-pill{font-family:'JetBrains Mono',monospace;font-size:13px;letter-spacing:.05em;text-transform:uppercase;padding:9px 17px;border:1px solid rgba(255,255,255,.15);border-radius:999px;color:rgba(255,255,255,.62);white-space:nowrap}
.sd-path-row{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-top:26px}
@media(max-width:920px){.sd-path-row{grid-template-columns:1fr 1fr}}
@media(max-width:560px){.sd-path-row{grid-template-columns:1fr}}
.sd-path-step{background:var(--void-2,#12150E);border-radius:12px;padding:20px}
.sd-path-step .sd-pn{font-family:'JetBrains Mono',monospace;font-size:13px;color:var(--lime);font-weight:700;margin-bottom:8px}
.sd-path-step h5{font-size:22px;margin-bottom:7px;color:#fff}
.sd-path-step p{font-size:16px;color:rgba(255,255,255,.55);line-height:1.6}
.sd-gap-grid{display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-top:26px}
@media(max-width:820px){.sd-gap-grid{grid-template-columns:1fr}}
.sd-gap-col{position:relative;overflow:hidden;background:var(--void-2,#12150E);border-radius:20px;padding:30px 28px 28px}
.sd-gap-col::before{content:'';position:absolute;top:0;left:28px;right:28px;height:2px;border-radius:0 0 2px 2px}
.sd-gap-col.sd-done::before{background:var(--gold)}
.sd-gap-col.sd-open::before{background:var(--lime)}
.sd-gap-col h4{font-size:28px;margin-bottom:16px;color:#fff}
.sd-gap-col ul{display:flex;flex-direction:column;gap:12px}
.sd-gap-col li{font-size:16px;line-height:1.6;color:rgba(255,255,255,.65);padding-left:16px;position:relative;list-style:none}
.sd-gap-col li::before{content:'—';position:absolute;left:0;color:rgba(255,255,255,.3)}
.sd-gal-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-top:26px}
@media(max-width:920px){.sd-gal-grid{grid-template-columns:repeat(3,1fr)}}
@media(max-width:560px){.sd-gal-grid{grid-template-columns:repeat(2,1fr)}}
.sd-gal-item{aspect-ratio:1/1;border-radius:12px;overflow:hidden;background:var(--void-2,#12150E)}
.sd-gal-item img{width:100%;height:100%;object-fit:cover}

/* ── POLICY / AFRICA ── */
.policy-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-bottom:60px}
.policy-card{background:#fff;border:1px solid var(--rule);border-radius:var(--r-card);padding:26px 22px}
.policy-card h4{font-size:22px;margin-bottom:8px}
.policy-card p{font-size:16px;color:var(--soft);line-height:1.6}
@media(max-width:920px){.policy-grid{grid-template-columns:1fr 1fr}}
.roadmap{position:relative;padding-left:2px}
.rm-line{position:absolute;top:10px;bottom:10px;left:19px;width:2px;background:var(--rule)}
.rm-item{display:flex;gap:26px;padding-bottom:44px;position:relative}
.rm-item:last-child{padding-bottom:0}
.rm-dot{width:40px;height:40px;border-radius:50%;background:var(--forest);color:#fff;display:flex;align-items:center;justify-content:center;font-family:'JetBrains Mono',monospace;font-weight:700;font-size:13px;flex-shrink:0;z-index:1}
.rm-body h4{font-size:25px;margin-bottom:6px}
.rm-body p{font-size:19px;color:var(--mid)}

/* ── HOUSEHOLDS ── */
.hh-grid{display:grid;grid-template-columns:1fr 1fr;gap:50px;align-items:center}
.hh-imgwrap{border-radius:var(--r-card);overflow:hidden;position:relative}
.hh-imgwrap img{width:100%;height:420px;object-fit:cover}
.hh-cards{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.hh-card{background:rgba(255,255,255,.08);border-radius:14px;padding:22px 20px}
.hh-card b{display:block;font-family:'EB Garamond',serif;font-style:italic;font-weight:600;font-size:25px;color:var(--lime-soft);margin-bottom:8px;line-height:1.25}
.hh-card span{font-size:16px;color:rgba(255,255,255,.62);line-height:1.55}
@media(max-width:860px){.hh-grid{grid-template-columns:1fr}}

/* ── OPEN SOURCE ── */
.os-wrap{display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:start}
.os-badge{display:inline-flex;align-items:center;gap:16px;margin-bottom:30px}
.os-badge img{height:46px;width:auto;filter:url(#navyGreenTint);opacity:.85}
.os-badge b{display:block;font-size:16px;font-weight:700;color:var(--void)}
.os-badge span{font-size:13px;color:var(--soft)}
.license-table{width:100%;border-collapse:collapse;margin-top:8px}
.license-table td{padding:14px 0;border-bottom:1px solid var(--rule);font-size:19px;vertical-align:top}
.license-table td:first-child{width:34%;font-weight:700;color:var(--forest)}
.license-table td:last-child{color:var(--mid)}

/* ── CONTACT : green cylindrical capsule frame ── */
.contact-grid{display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center}
.contact-card{background:var(--forest);border-radius:100px;padding:14px 14px 14px 30px;display:flex;flex-direction:column;gap:0}
.contact-row{display:flex;align-items:center;gap:16px;padding:16px 10px;border-bottom:1px solid rgba(255,255,255,.14)}
.contact-row:last-child{border-bottom:0}
.contact-row .ic{width:42px;height:42px;border-radius:50%;background:var(--lime);color:var(--void);display:flex;align-items:center;justify-content:center;font-weight:700;flex-shrink:0}
.contact-row a{font-weight:700;font-size:22px;color:#fff}
.contact-row small{display:block;font-size:13px;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.55);margin-bottom:3px}
@media(max-width:860px){.contact-grid{grid-template-columns:1fr}.contact-card{border-radius:32px}}

/* ── FOOTER ── */
footer{background:#080906;color:rgba(255,255,255,.55);padding:48px 0 0}
/* Outer grid: brand block, then the nav columns as one unit — wider gap here
   pushes the nav block further from "AK Children Initiative". */
.f-top{display:grid;grid-template-columns:1.3fr 2.55fr;gap:56px;padding-bottom:32px;border-bottom:1px solid rgba(255,255,255,.08)}
/* Inner grid: Site / Initiative / Open Source+Contact — equal widths, tighter shared gap. */
.f-nav-group{display:grid;grid-template-columns:1fr 1fr 1fr;gap:24px}
.f-logo{display:flex;align-items:center;gap:10px;margin-bottom:0;opacity:.45}
.f-logo img{height:186px;width:auto;filter:grayscale(1) brightness(0) invert(1)}
.f-logo-txt{display:flex;flex-direction:column;font-family:'EB Garamond',serif;font-weight:700;font-size:44px;line-height:1.05;letter-spacing:-.01em;color:#fff}
.f-tag{font-family:'EB Garamond',serif;font-style:italic;font-size:19px;color:rgba(255,255,255,.35);line-height:1.75;max-width:320px}
.f-head{font-size:13px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:var(--lime-dim);margin-bottom:12px}
.f-col a,.f-col span{display:block;font-size:16px;color:rgba(255,255,255,.55);margin-bottom:12px;line-height:1.5}
.f-col a:hover{color:var(--lime)}
.f-bottom{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;padding:18px 0 22px;font-size:13px;color:rgba(255,255,255,.35)}
.f-bottom .fb-left{max-width:720px;line-height:1.8}
.f-bottom .fb-left a{color:rgba(255,255,255,.55);border-bottom:1px dotted rgba(255,255,255,.25)}
.f-bottom .fb-right{display:flex;gap:22px;flex-shrink:0}
.f-bottom .fb-right a{color:rgba(255,255,255,.6);font-weight:600;letter-spacing:.03em}
.f-bottom .fb-right a:hover{color:var(--lime)}
@media(max-width:1080px){.f-top{grid-template-columns:1fr;gap:32px}}
@media(max-width:720px){.f-nav-group{grid-template-columns:1fr 1fr}}
@media(max-width:480px){.f-nav-group{grid-template-columns:1fr}.f-bottom{flex-direction:column;align-items:flex-start}}

.mob-drawer{position:fixed;inset:0;background:var(--void);z-index:10000;display:flex;flex-direction:column;padding:100px 40px;transform:translateX(100%);transition:.35s}
.mob-drawer.open{transform:translateX(0)}
.mob-drawer a{display:block;font-size:22px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:#fff;padding:16px 0;border-bottom:1px solid rgba(255,255,255,.08)}
.mob-close{position:absolute;top:28px;right:28px;background:none;border:0;color:#fff;font-size:34px;cursor:pointer}

/* ═══════════════════════════════════════════════════════════════════════
   SUBPAGE COMPONENT LIBRARY
   Shared by /financial-enablement, /child-aid-alignment, /sport-development
   ═══════════════════════════════════════════════════════════════════════ */
:root{
  --navytint:#5E8177;
  --childblue:#5FB4E0;
}

/* ── HERO / SUBPAGE BREADCRUMB ── */
.papi-hero{padding:calc(var(--nav) + 90px) 0 20px;position:relative;overflow:hidden}
.papi-hero::before{content:'';position:absolute;top:-20%;right:-10%;width:600px;height:600px;background:radial-gradient(circle,rgba(201,240,77,.1),transparent 70%);pointer-events:none;z-index:0;animation:liquidDrift 18s ease-in-out infinite}
.papi-hero::after{content:'';position:absolute;inset:0;background:linear-gradient(180deg,transparent 0%,transparent 55%,rgba(0,0,0,.3) 100%);pointer-events:none;z-index:0}
@keyframes liquidDrift{0%,100%{transform:translate(0,0) scale(1)}33%{transform:translate(-4%,3%) scale(1.08)}66%{transform:translate(3%,-3%) scale(.96)}}
.papi-hero video{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.7;z-index:1;pointer-events:none}
.papi-hero::after{content:'';position:absolute;inset:0;z-index:2;pointer-events:none;background:radial-gradient(ellipse at 30% 20%,rgba(201,240,77,.06),transparent 55%),linear-gradient(180deg,rgba(10,12,9,.5) 0%,rgba(10,12,9,.45) 30%,rgba(10,12,9,.92) 100%)}
.subpage-bar{display:flex;align-items:center;gap:10px;margin-bottom:30px;font-family:'JetBrains Mono',monospace;font-size:13px;letter-spacing:.06em;text-transform:uppercase}
.subpage-crumb{color:rgba(255,255,255,.45);transition:.2s}
.subpage-crumb:hover{color:var(--lime)}
.subpage-crumb.is-current{color:var(--lime-soft)}
.subpage-sep{color:rgba(255,255,255,.25)}
.hero-badge{display:inline-flex;align-items:center;gap:8px;font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:600;letter-spacing:.13em;text-transform:uppercase;color:var(--lime-soft);border:1px solid rgba(201,240,77,.3);padding:8px 16px;border-radius:100px;margin-bottom:26px}
.hero-badge .dot{width:7px;height:7px;border-radius:50%;background:var(--lime);display:inline-block}
.papi-hero h1{font-size:clamp(30px,4.6vw,58px);max-width:820px;line-height:1.12;margin-bottom:20px}
.papi-hero .lead{font-family:'EB Garamond',serif;font-style:italic;font-size:22px;color:rgba(255,255,255,.62);max-width:640px;margin-bottom:32px;line-height:1.6}
.papi-hero .slead{font-family:'EB Garamond',serif;font-style:italic;font-size:22px;color:rgba(255,255,255,.62);line-height:1.7}
.hero-ctas{display:flex;gap:14px;flex-wrap:wrap;margin-bottom:50px}
.hero-stats{display:flex;gap:44px;flex-wrap:wrap;border-top:1px solid rgba(255,255,255,.12);padding-top:28px}
.hstat b{display:block;font-family:'JetBrains Mono',monospace;font-size:38px;color:var(--lime-soft);font-weight:700}
.hstat span{font-size:13px;letter-spacing:.04em;text-transform:uppercase;color:rgba(255,255,255,.45);font-family:'JetBrains Mono',monospace}

/* ── SDG ALIGNMENT CHIPS (official UN SDG colours) ── */
.sdg-chip.sdg-chip--sm{margin-top:16px}

/* ── LIVE FX TICKER ── */
.fx-bar{background:var(--void-2);border-top:1px solid rgba(201,240,77,.1);border-bottom:1px solid rgba(201,240,77,.1);padding:16px 0;overflow:hidden}
.fx-row{display:flex;align-items:center;gap:34px;flex-wrap:wrap;font-family:'JetBrains Mono',monospace;font-size:16px}
.fx-tag{display:inline-flex;align-items:center;gap:6px;color:var(--lime);font-weight:700;letter-spacing:.08em;text-transform:uppercase;font-size:13px}
.fx-dot{width:6px;height:6px;border-radius:50%;background:var(--lime);animation:fxpulse 1.8s infinite}
@keyframes fxpulse{0%,100%{opacity:1}50%{opacity:.25}}
.fx-pair{color:rgba(255,255,255,.75)}
.fx-pair b{color:#fff}
.fx-updated{color:rgba(255,255,255,.35);font-size:13px;margin-left:auto}
@media(max-width:760px){.fx-updated{margin-left:0;width:100%}}

/* ── WHAT DEFINES THIS API (3-col) ── */
.defines-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:44px;margin-top:20px}
.defines-item{text-align:center}
.defines-icon{width:42px;height:42px;margin:0 auto 18px}
.defines-item h5{font-size:28px;margin-bottom:10px}
.defines-item p{font-size:19px;color:rgba(255,255,255,.55);line-height:1.7;max-width:280px;margin:0 auto}
@media(max-width:760px){.defines-grid{grid-template-columns:1fr;gap:36px}}

/* ── FEATURE GRID (photo cards) ── */
.feat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-top:8px}
.feat-card{border-radius:var(--r-card);overflow:hidden;background:var(--void-2)}
.feat-img{aspect-ratio:4/3;overflow:hidden}
.feat-img img{width:100%;height:100%;object-fit:cover;transition:transform .7s ease}
.feat-card:hover .feat-img img{transform:scale(1.06)}
.feat-body{padding:20px 18px 24px}
.feat-tag{font-family:'JetBrains Mono',monospace;font-size:13px;letter-spacing:.06em;text-transform:uppercase;color:var(--lime)}
.feat-card h4{font-size:25px;margin:7px 0 8px}
.feat-card p{font-size:16px;line-height:1.6;color:rgba(255,255,255,.55)}
@media(max-width:980px){.feat-grid{grid-template-columns:1fr 1fr}}
@media(max-width:560px){.feat-grid{grid-template-columns:1fr}}

/* ── LIVE DEMO ── */
.demo-panel{background:var(--void-2);border-radius:var(--r-card);padding:40px;margin-top:16px}
@media(max-width:640px){.demo-panel{padding:24px 20px}}
.demo-grid{display:grid;grid-template-columns:1.1fr .9fr;gap:44px;margin-top:24px}
@media(max-width:920px){.demo-grid{grid-template-columns:1fr}}
.demo-factor{margin-bottom:22px}
.demo-factor-head{display:flex;justify-content:space-between;align-items:baseline;margin-bottom:8px}
.demo-factor-head span{font-family:'JetBrains Mono',monospace;font-size:16px;font-weight:700;color:#fff}
.demo-factor-head b{font-family:'JetBrains Mono',monospace;font-size:16px;color:var(--lime)}
input[type=range]{-webkit-appearance:none;width:100%;height:6px;border-radius:4px;background:rgba(255,255,255,.12);outline:none}
input[type=range]::-webkit-slider-thumb{-webkit-appearance:none;width:20px;height:20px;border-radius:50%;background:var(--lime);cursor:pointer;border:3px solid var(--void-2)}
input[type=range]::-moz-range-thumb{width:20px;height:20px;border-radius:50%;background:var(--lime);cursor:pointer;border:3px solid var(--void-2)}
.demo-total-wrap{position:sticky;top:calc(var(--nav) + 20px);background:var(--void-3);border-radius:var(--r-soft);padding:28px;text-align:center}
.demo-total-label{font-family:'JetBrains Mono',monospace;font-size:13px;letter-spacing:.06em;text-transform:uppercase;color:rgba(255,255,255,.5);margin-bottom:10px}
.demo-total-num{font-family:'EB Garamond',serif;font-weight:600;font-size:64px;color:var(--lime-soft);line-height:1}
.demo-total-num span{font-size:38px;color:rgba(255,255,255,.4)}
.demo-tier{margin-top:16px;font-family:'JetBrains Mono',monospace;font-size:16px;letter-spacing:.04em;text-transform:uppercase;padding:8px 16px;border-radius:100px;display:inline-block}
.demo-tier.t-emerging{background:rgba(200,146,42,.18);color:var(--gold-bright)}
.demo-tier.t-established{background:rgba(76,159,56,.18);color:#8FD976}
.demo-tier.t-compounding{background:rgba(201,240,77,.18);color:var(--lime)}
.demo-note{margin-top:16px;font-size:16px;color:rgba(255,255,255,.4);line-height:1.6}
.demo-disclaimer{margin-top:28px;font-family:'EB Garamond',serif;font-style:italic;font-size:16px;color:rgba(255,255,255,.35)}
.demo-banner{display:flex;align-items:flex-start;gap:13px;background:rgba(230,168,50,.07);border-radius:12px;padding:16px 20px;margin:22px 0 8px}
.demo-banner svg{flex:none;margin-top:2px}
.demo-banner-label{font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--gold-bright);margin-bottom:5px}
.demo-banner p{font-family:'JetBrains Mono',monospace;font-size:16px;line-height:1.6;color:rgba(255,255,255,.65);margin:0}

/* ── API DOCS ── */
.doc-panel{display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-top:8px}
@media(max-width:920px){.doc-panel{grid-template-columns:1fr}}
.code-block{background:var(--void-2);border-radius:var(--r-card);padding:22px 24px;overflow-x:auto}
.code-block .cb-label{font-family:'JetBrains Mono',monospace;font-size:13px;letter-spacing:.06em;text-transform:uppercase;color:rgba(255,255,255,.4);margin-bottom:14px}
.code-block pre{font-family:'JetBrains Mono',monospace;font-size:16px;line-height:1.7;color:#DFF593;white-space:pre}
.code-block .k{color:#8FAE3A}
.code-block .s{color:#E6A832}
.code-block .c{color:rgba(255,255,255,.35)}

/* ── PILLS ── */
.pill-strip{display:flex;flex-wrap:wrap;justify-content:center;gap:10px;margin-top:34px}
.pill{font-family:'JetBrains Mono',monospace;font-size:13px;letter-spacing:.05em;text-transform:uppercase;padding:9px 17px;border:1px solid rgba(255,255,255,.15);border-radius:999px;color:rgba(255,255,255,.6);white-space:nowrap}

/* ── PRIORITY PILLARS (Child Aid Alignment) ── */
.pillar-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:16px}
@media(max-width:980px){.pillar-grid{grid-template-columns:1fr 1fr}}
@media(max-width:560px){.pillar-grid{grid-template-columns:1fr}}
.pillar{background:rgba(255,255,255,.05);border-radius:16px;padding:24px 18px;display:flex;flex-direction:column}
.pillar .pnum{font-family:'JetBrains Mono',monospace;font-size:13px;color:var(--lime);margin-bottom:14px}
.pillar h4{font-size:22px;color:#fff;margin-bottom:8px}
.pillar p{font-size:16px;color:rgba(255,255,255,.5);line-height:1.6;flex:1}
.pillar .sdg-chip{margin-top:16px;align-self:flex-start}

/* ── HOW FUNDING WORKS ── */
.exec-list{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:20px}
@media(max-width:760px){.exec-list{grid-template-columns:1fr}}
.exec-item{position:relative;padding:6px 0 6px 22px}
.exec-item::before{content:'';position:absolute;left:0;top:13px;width:8px;height:8px;background:var(--lime);border-radius:2px;transform:rotate(45deg)}
.exec-item b{display:block;color:#fff;font-family:'EB Garamond',serif;font-size:22px;margin-bottom:4px}
.exec-item span{font-size:16px;color:rgba(255,255,255,.55)}

/* ── FUNDER / DOMAIN / EVIDENCE ECOSYSTEM (shared sd- namespace) ── */
.sd-eco-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-top:24px}
@media(max-width:920px){.sd-eco-grid{grid-template-columns:1fr 1fr}}
@media(max-width:560px){.sd-eco-grid{grid-template-columns:1fr}}
.sd-eco-card{background:var(--void-2,#12150E);border-radius:12px;padding:20px 22px}
.sd-eco-kicker{font-family:'JetBrains Mono',monospace;font-size:13px;letter-spacing:.07em;text-transform:uppercase;color:var(--lime);margin-bottom:8px}
.sd-eco-card h5{font-size:22px;margin-bottom:8px;color:#fff}
.sd-eco-card p{font-size:16px;color:rgba(255,255,255,.55);line-height:1.6}
.sd-mech-strip{display:flex;flex-wrap:wrap;gap:10px;margin-top:26px}
.sd-mech-pill{font-family:'JetBrains Mono',monospace;font-size:13px;letter-spacing:.05em;text-transform:uppercase;padding:9px 17px;border:1px solid rgba(255,255,255,.15);border-radius:999px;color:rgba(255,255,255,.62);white-space:nowrap}
.pull{position:relative;background:var(--void-2,#12150E);border-radius:14px;padding:30px 30px 26px;margin:28px 0;font-family:'EB Garamond',serif;font-style:italic;font-size:25px;color:rgba(255,255,255,.82)}

.sd-ev-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:20px;margin-top:30px}
@media(max-width:820px){.sd-ev-grid{grid-template-columns:1fr}}
.sd-ev-card{position:relative;overflow:hidden;background:var(--void-2,#12150E);border-radius:20px;padding:28px 28px 26px}
.sd-ev-card::before{content:'';position:absolute;top:0;left:24px;right:24px;height:2px;background:var(--lime);border-radius:0 0 2px 2px}
.sd-ev-tag{font-family:'JetBrains Mono',monospace;font-size:13px;letter-spacing:.08em;text-transform:uppercase;color:var(--lime);margin-bottom:10px}
.sd-ev-card p{font-size:19px;line-height:1.75;color:rgba(255,255,255,.68)}
.sd-ev-src{margin-top:16px;font-size:13px;color:rgba(255,255,255,.35);font-family:'JetBrains Mono',monospace}
.sd-dom-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-top:20px}
@media(max-width:980px){.sd-dom-grid{grid-template-columns:1fr 1fr}}
@media(max-width:560px){.sd-dom-grid{grid-template-columns:1fr}}
.sd-dom-card{text-align:center;padding:30px 20px;background:var(--void-2,#12150E);border-radius:20px;transition:.3s}
.sd-dom-icon{width:38px;height:38px;margin:0 auto 16px}
.sd-dom-card h5{font-size:25px;margin-bottom:8px;color:#fff}
.sd-dom-card p{font-size:16px;color:rgba(255,255,255,.55);line-height:1.65}
.sd-dom-card .sdg-chip{margin:14px auto 0}
.sd-path-row{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-top:26px}
@media(max-width:920px){.sd-path-row{grid-template-columns:1fr 1fr}}
@media(max-width:560px){.sd-path-row{grid-template-columns:1fr}}
.sd-path-step{background:var(--void-2,#12150E);border-radius:12px;padding:20px}
.sd-path-step .sd-pn{font-family:'JetBrains Mono',monospace;font-size:13px;color:var(--lime);font-weight:700;margin-bottom:8px}
.sd-path-step h5{font-size:22px;margin-bottom:7px;color:#fff}
.sd-path-step p{font-size:16px;color:rgba(255,255,255,.55);line-height:1.6}
.sd-gap-grid{display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-top:26px}
@media(max-width:820px){.sd-gap-grid{grid-template-columns:1fr}}
.sd-gap-col{position:relative;overflow:hidden;background:var(--void-2,#12150E);border-radius:20px;padding:30px 28px 28px}
.sd-gap-col::before{content:'';position:absolute;top:0;left:28px;right:28px;height:2px;border-radius:0 0 2px 2px}
.sd-gap-col.sd-done::before{background:var(--gold)}
.sd-gap-col.sd-open::before{background:var(--lime)}
.sd-gap-col h4{font-size:28px;margin-bottom:16px;color:#fff}
.sd-gap-col ul{display:flex;flex-direction:column;gap:12px}
.sd-gap-col li{font-size:16px;line-height:1.6;color:rgba(255,255,255,.65);padding-left:16px;position:relative;list-style:none}
.sd-gap-col li::before{content:'—';position:absolute;left:0;color:rgba(255,255,255,.3)}
.sd-gal-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-top:26px}
@media(max-width:920px){.sd-gal-grid{grid-template-columns:repeat(3,1fr)}}
@media(max-width:560px){.sd-gal-grid{grid-template-columns:repeat(2,1fr)}}
.sd-gal-item{aspect-ratio:1/1;border-radius:12px;overflow:hidden;background:var(--void-2,#12150E)}
.sd-gal-item img{width:100%;height:100%;object-fit:cover}

/* ═══════════════════════════════════════════════════════════════════════
   SUBPAGE THEME — dark "console" register for every route except home
   Scoped by [data-page] on <body> so the homepage's light/mixed theme,
   its hero .btn sizing, and its .kl-h scroll-solid transition are untouched.
   ═══════════════════════════════════════════════════════════════════════ */
body[data-page]:not([data-page="home"]){background:var(--void);color:rgba(255,255,255,.78)}
body[data-page]:not([data-page="home"]) h1,
body[data-page]:not([data-page="home"]) h2,
body[data-page]:not([data-page="home"]) h3,
body[data-page]:not([data-page="home"]) h4{color:#fff}
body[data-page]:not([data-page="home"]) .wrap{max-width:1180px}
body[data-page]:not([data-page="home"]) .section{padding:100px 0}
@media(max-width:760px){body[data-page]:not([data-page="home"]) .section{padding:64px 0}}
body[data-page]:not([data-page="home"]) .eyebrow{color:var(--gold-bright)}
body[data-page]:not([data-page="home"]) .eyebrow::before{background:var(--gold-bright)}
body[data-page]:not([data-page="home"]) .btn{padding:14px 26px;font-size:16px;gap:8px}
body[data-page]:not([data-page="home"]) .btn-solid{background:var(--lime);color:var(--void)!important}
body[data-page]:not([data-page="home"]) .btn-solid:hover{background:var(--lime-soft);transform:none}
body[data-page]:not([data-page="home"]) .btn-line{border:1px solid rgba(255,255,255,.3);color:#fff!important}
body[data-page]:not([data-page="home"]) .btn-line:hover{background:transparent;border-color:var(--lime)}

/* ── Child Aid Alignment — sky-blue accent register ── */
body[data-page="child-aid-alignment"] .btn-solid{background:var(--childblue)}
body[data-page="child-aid-alignment"] .btn-solid:hover{background:#7BC4E8}
body[data-page="child-aid-alignment"] .btn-line:hover{border-color:var(--childblue)}
body[data-page="child-aid-alignment"] .hero-badge{color:var(--childblue);border-color:rgba(95,180,224,.35)}
body[data-page="child-aid-alignment"] .hero-badge .dot{background:var(--childblue)}
body[data-page="child-aid-alignment"] .hstat b{color:var(--childblue)}
body[data-page="child-aid-alignment"] .subpage-crumb:hover,
body[data-page="child-aid-alignment"] .subpage-crumb.is-current{color:var(--childblue)}
body[data-page="child-aid-alignment"] .sd-eco-kicker{color:var(--childblue)}
body[data-page="child-aid-alignment"] .pillar .pnum{color:var(--childblue)}
body[data-page="child-aid-alignment"] .exec-item::before{background:var(--childblue)}
body[data-page="child-aid-alignment"] .papi-hero::before{background:radial-gradient(circle,rgba(95,180,224,.12),transparent 70%)}

/* ── Sport & Development — keep lime, gold ecosystem kicker ── */
body[data-page="sport-development"] .sd-eco-kicker{color:var(--gold-bright)}

/* ── Climate & Health Resilience — forest green accent register ── */
body[data-page="climate-resilience"] .btn-solid{background:var(--forest-mid)}
body[data-page="climate-resilience"] .btn-solid:hover{background:#3FA76B}
body[data-page="climate-resilience"] .btn-line:hover{border-color:var(--forest-mid)}
body[data-page="climate-resilience"] .hero-badge{color:var(--forest-mid);border-color:rgba(46,139,87,.35)}
body[data-page="climate-resilience"] .hero-badge .dot{background:var(--forest-mid)}
body[data-page="climate-resilience"] .hstat b{color:var(--forest-mid)}
body[data-page="climate-resilience"] .subpage-crumb:hover,
body[data-page="climate-resilience"] .subpage-crumb.is-current{color:var(--forest-mid)}
body[data-page="climate-resilience"] .sd-eco-kicker{color:var(--forest-mid)}
body[data-page="climate-resilience"] .exec-item::before{background:var(--forest-mid)}
body[data-page="climate-resilience"] .sd-ev-card::before,
body[data-page="climate-resilience"] .gate-tile::before{background:var(--forest-mid)}
body[data-page="climate-resilience"] .papi-hero::before{background:radial-gradient(circle,rgba(46,139,87,.16),transparent 70%)}

/* ── Child Resilience Index — rose/bloom accent register ── */
body[data-page="child-resilience-index"] .btn-solid{background:var(--bloom)}
body[data-page="child-resilience-index"] .btn-solid:hover{background:#D97088}
body[data-page="child-resilience-index"] .btn-line:hover{border-color:var(--bloom)}
body[data-page="child-resilience-index"] .hero-badge{color:#E8A6B2;border-color:rgba(193,78,99,.4)}
body[data-page="child-resilience-index"] .hero-badge .dot{background:var(--bloom)}
body[data-page="child-resilience-index"] .hstat b{color:#E8A6B2}
body[data-page="child-resilience-index"] .subpage-crumb:hover,
body[data-page="child-resilience-index"] .subpage-crumb.is-current{color:#E8A6B2}
body[data-page="child-resilience-index"] .sd-eco-kicker{color:#E8A6B2}
body[data-page="child-resilience-index"] .exec-item::before{background:var(--bloom)}
body[data-page="child-resilience-index"] .sd-ev-card::before,
body[data-page="child-resilience-index"] .gate-tile::before{background:var(--bloom)}
body[data-page="child-resilience-index"] .papi-hero::before{background:radial-gradient(circle,rgba(193,78,99,.18),transparent 70%)}

/* ── Footer: highlight whichever Initiative link matches the current route ── */
.f-col a.current{color:var(--lime)}

/* ── Financial Enablement — hero accent + testimonial voice cards ── */
.fe-accent{color:var(--lime-soft);font-style:italic;font-family:'EB Garamond',serif;font-weight:600}
.voice-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:26px}
@media(max-width:860px){.voice-grid{grid-template-columns:1fr}}
.voice-card{position:relative;overflow:hidden;background:var(--void-2,#12150E);border-radius:18px;padding:26px 26px 24px;display:flex;flex-direction:column}
.voice-card::before{content:'';position:absolute;top:0;left:22px;right:22px;height:2px;background:var(--lime);border-radius:0 0 2px 2px}
.voice-quote{font-family:'EB Garamond',serif;font-style:italic;font-size:22px;line-height:1.7;color:rgba(255,255,255,.82);flex:1}
.voice-attr{display:flex;align-items:center;gap:10px;margin-top:16px;padding-top:16px;border-top:1px solid rgba(255,255,255,.08)}
.voice-avatar{width:36px;height:36px;border-radius:50%;background:rgba(201,240,77,.14);color:var(--lime);display:flex;align-items:center;justify-content:center;font-family:'JetBrains Mono',monospace;font-size:16px;font-weight:700;flex-shrink:0}
.voice-name{font-size:16px;color:#fff;font-weight:600}
.voice-role{font-size:13px;color:rgba(255,255,255,.45);font-family:'JetBrains Mono',monospace;text-transform:uppercase;letter-spacing:.04em}

/* ── Child Aid Alignment — "north star" goal band ── */
.goal-band{text-align:center;padding:56px 40px;background:rgba(95,180,224,.08);border-radius:var(--r-card)}
.goal-num{font-family:'EB Garamond',serif;font-weight:600;font-size:clamp(56px,8vw,104px);color:var(--childblue);line-height:1}
.goal-label{font-family:'JetBrains Mono',monospace;font-size:16px;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.55);margin-top:16px}
.goal-sub{font-family:'EB Garamond',serif;font-style:italic;color:rgba(255,255,255,.6);max-width:560px;margin:14px auto 0;font-size:22px;line-height:1.6}

/* ═══════════════════════════════════════════════════════════════════════
   PAPI-HERO SIGNATURE CANVAS — a single hand-inked stroke drawn behind
   the hero copy on load, content-specific per page, in place of stock
   video footage. Purely CSS/SVG so it never needs an asset host.
   ═══════════════════════════════════════════════════════════════════════ */
.papi-hero{overflow:hidden}
.papi-canvas{position:absolute;inset:0;width:100%;height:100%;z-index:3;pointer-events:none}
.papi-hero .wrap{position:relative;z-index:4}
@media(prefers-reduced-motion:reduce){.papi-canvas *{animation:none!important;stroke-dashoffset:0!important}}

/* ── Child Aid Alignment — five priorities, one continuous line ──
   The stroke visits five nodes in the order the copy introduces them
   (health, nutrition, education, water, emergency response), the same
   five the SDG strip below repeats. The line is the argument. */
.ca-line{fill:none;stroke:var(--childblue);stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round;opacity:.5;stroke-dasharray:1600;stroke-dashoffset:1600;animation:ca-draw 3.4s .3s cubic-bezier(.6,.05,.28,1) forwards}
.ca-node{fill:var(--void);stroke:var(--childblue);stroke-width:1.4;opacity:0;animation:ca-node-in .5s ease forwards, pulse 2.8s ease-in-out infinite}
.ca-node-core{fill:var(--childblue);opacity:0;animation:ca-node-in .5s ease forwards}
@keyframes ca-draw{to{stroke-dashoffset:0}}
@keyframes ca-node-in{to{opacity:1}}

/* ── Sport & Development — a pitch chalked on, then a ball that finds it ──
   Traces an unmistakably hand-marked (not CAD-perfect) court outline,
   answering the hero copy's "empty afternoon becomes a football pitch"
   line with the visual instead of restating it. */
.sd-pitch{fill:none;stroke:var(--gold-bright);stroke-width:2;stroke-linecap:round;stroke-linejoin:round;opacity:.45;stroke-dasharray:2800;stroke-dashoffset:2800;animation:sd-draw 2.8s .2s cubic-bezier(.65,.05,.32,1) forwards}
.sd-mark{fill:none;stroke:var(--gold-bright);stroke-width:2;stroke-linecap:round;opacity:0;stroke-dasharray:340;stroke-dashoffset:340;animation:sd-mark-in .8s 2.4s ease forwards,sd-draw2 .8s 2.4s ease forwards}
@keyframes sd-draw{to{stroke-dashoffset:0}}
@keyframes sd-draw2{to{stroke-dashoffset:0}}
@keyframes sd-mark-in{to{opacity:.55}}
.sd-ball{fill:var(--lime)}

/* ═══════════════════════════════════════════════════════════════════════
   FIT-TO-VIEWPORT HERO — Child Aid Alignment & Sport Development
   Same technique the homepage .hero already relies on: the hero is
   locked to exactly one screen (100svh) and every size inside it is a
   vh-based clamp, so the whole block compresses proportionally instead
   of overflowing, at any browser zoom level or window/aspect ratio.
   ═══════════════════════════════════════════════════════════════════════ */
body[data-page="child-aid-alignment"] .papi-hero,
body[data-page="sport-development"] .papi-hero{
  min-height:100vh;min-height:100svh;
  padding:calc(var(--nav) + 5vh) 0 6vh;box-sizing:border-box;
}
body[data-page="child-aid-alignment"] .papi-hero > .wrap,
body[data-page="sport-development"] .papi-hero > .wrap{
  width:100%;box-sizing:border-box;
}
body[data-page="child-aid-alignment"] .papi-hero .subpage-bar,
body[data-page="sport-development"] .papi-hero .subpage-bar{
  font-size:clamp(8px,.95vh,10.5px);margin-bottom:clamp(8px,1.6vh,30px);flex-shrink:0;
}
body[data-page="child-aid-alignment"] .papi-hero .hero-badge,
body[data-page="sport-development"] .papi-hero .hero-badge{
  font-size:clamp(8px,.9vh,10.5px);padding:clamp(4px,.75vh,8px) 16px;
  margin-bottom:clamp(8px,1.5vh,26px)!important;flex-shrink:0;
}
body[data-page="child-aid-alignment"] .papi-hero h1,
body[data-page="sport-development"] .papi-hero h1{
  font-size:clamp(24px,4.8vh,58px)!important;margin-bottom:clamp(6px,1.1vh,14px)!important;
  line-height:1.12;flex-shrink:0;
}
body[data-page="child-aid-alignment"] .papi-hero .slead,
body[data-page="sport-development"] .papi-hero .slead{
  font-size:clamp(11px,1.3vh,16px);line-height:1.5;flex-shrink:0;
}
body[data-page="sport-development"] .papi-hero .slead + .slead{
  margin-top:clamp(4px,.8vh,14px)!important;
}
body[data-page="child-aid-alignment"] .papi-hero .sdg-strip,
body[data-page="sport-development"] .papi-hero .sdg-strip{
  margin-top:clamp(6px,1.3vh,24px)!important;gap:clamp(4px,.7vh,8px);flex-shrink:0;
}
body[data-page="child-aid-alignment"] .papi-hero .sdg-chip,
body[data-page="sport-development"] .papi-hero .sdg-chip{
  font-size:clamp(7.5px,.85vh,9.5px);padding:clamp(3px,.45vh,5px) clamp(9px,1.2vh,13px) clamp(3px,.45vh,5px) clamp(3px,.45vh,5px);
}
body[data-page="child-aid-alignment"] .papi-hero .sdg-chip .sdg-num,
body[data-page="sport-development"] .papi-hero .sdg-chip .sdg-num{
  width:clamp(15px,1.7vh,20px);height:clamp(15px,1.7vh,20px);font-size:clamp(8px,.95vh,10.5px);
}
body[data-page="child-aid-alignment"] .papi-hero .hero-stats,
body[data-page="sport-development"] .papi-hero .hero-stats{
  margin-top:clamp(8px,1.7vh,34px)!important;padding-top:clamp(8px,1.3vh,28px);
  gap:clamp(14px,3vw,44px);flex-shrink:0;
}
body[data-page="child-aid-alignment"] .papi-hero .hstat b,
body[data-page="sport-development"] .papi-hero .hstat b{font-size:clamp(15px,2vh,31px)}
body[data-page="child-aid-alignment"] .papi-hero .hstat span,
body[data-page="sport-development"] .papi-hero .hstat span{font-size:clamp(7px,.8vh,11px)}

/* ── Financial Enablement — a rising, hand-inked score line, 8 signals to a check ── */
.fin-line{fill:none;stroke:var(--lime);stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round;opacity:.5;stroke-dasharray:1500;stroke-dashoffset:1500;animation:ca-draw 3s .3s cubic-bezier(.6,.05,.28,1) forwards}
.fin-node{fill:var(--void);stroke:var(--lime);stroke-width:1.4;opacity:0;animation:ca-node-in .45s ease forwards,pulse 2.6s ease-in-out infinite}
.fin-node-core{fill:var(--lime);opacity:0;animation:ca-node-in .45s ease forwards}
.fin-check{fill:none;stroke:var(--lime);stroke-width:3;stroke-linecap:round;stroke-linejoin:round;opacity:0;stroke-dasharray:90;stroke-dashoffset:90;animation:fin-check-in .4s 2.6s ease forwards,fin-check-draw .4s 2.6s ease forwards}
@keyframes fin-check-draw{to{stroke-dashoffset:0}}
@keyframes fin-check-in{to{opacity:1}}

/* ── Fit-to-viewport hero — Financial Enablement ── */
body[data-page="financial-enablement"] .papi-hero{
  min-height:100vh;min-height:100svh;
  padding:calc(var(--nav) + 5vh) 0 6vh;box-sizing:border-box;
}
body[data-page="financial-enablement"] .papi-hero > .wrap{
  width:100%;box-sizing:border-box;
}
body[data-page="financial-enablement"] .papi-hero .subpage-bar{font-size:clamp(8px,.95vh,10.5px);margin-bottom:clamp(8px,1.6vh,30px);flex-shrink:0}
body[data-page="financial-enablement"] .papi-hero .hero-badge{font-size:clamp(8px,.9vh,10.5px);padding:clamp(4px,.75vh,8px) 16px;margin-bottom:clamp(8px,1.5vh,26px)!important;flex-shrink:0}
body[data-page="financial-enablement"] .papi-hero h1{font-size:clamp(24px,4.8vh,58px)!important;margin-bottom:clamp(6px,1.1vh,14px)!important;line-height:1.12;flex-shrink:0}
body[data-page="financial-enablement"] .papi-hero .lead{font-size:clamp(11px,1.3vh,16px)!important;line-height:1.5;margin-bottom:clamp(8px,1.5vh,20px)!important;flex-shrink:0}
body[data-page="financial-enablement"] .papi-hero .hero-ctas{margin-bottom:clamp(8px,1.5vh,20px)!important;gap:12px;flex-shrink:0}
body[data-page="financial-enablement"] .papi-hero .hero-stats{padding-top:clamp(8px,1.3vh,20px);gap:clamp(14px,3vw,44px);flex-shrink:0}
body[data-page="financial-enablement"] .papi-hero .sdg-strip{margin-top:clamp(6px,1.3vh,20px)!important;gap:clamp(4px,.7vh,8px);flex-shrink:0}
body[data-page="financial-enablement"] .papi-hero .sdg-chip{font-size:clamp(7.5px,.85vh,9.5px);padding:clamp(3px,.45vh,5px) clamp(9px,1.2vh,13px) clamp(3px,.45vh,5px) clamp(3px,.45vh,5px)}
body[data-page="financial-enablement"] .papi-hero .sdg-chip .sdg-num{width:clamp(15px,1.7vh,20px);height:clamp(15px,1.7vh,20px);font-size:clamp(8px,.95vh,10.5px)}

/* ── Fit-to-viewport hero — Climate & Health Resilience ──
   Same technique as the other initiative pages: the hero is locked to
   exactly one screen (100svh) and every size inside it is a vh-based
   clamp, so it compresses proportionally and never spills past the
   fold, at any browser zoom level or window/aspect ratio. */
body[data-page="climate-resilience"] .papi-hero{
  min-height:100vh;min-height:100svh;
  padding:calc(var(--nav) + 5vh) 0 6vh;box-sizing:border-box;
}
body[data-page="climate-resilience"] .papi-hero > .wrap{
  width:100%;box-sizing:border-box;
}
body[data-page="climate-resilience"] .papi-hero .subpage-bar{
  font-size:clamp(8px,.95vh,10.5px);margin-bottom:clamp(6px,1.4vh,30px);flex-shrink:0;
}
body[data-page="climate-resilience"] .papi-hero .hero-badge{
  font-size:clamp(8px,.9vh,10.5px);padding:clamp(4px,.7vh,8px) 16px;
  margin-bottom:clamp(6px,1.2vh,26px)!important;flex-shrink:0;
}
body[data-page="climate-resilience"] .papi-hero h1{
  font-size:clamp(24px,4.8vh,58px)!important;margin-bottom:clamp(5px,1vh,14px)!important;
  line-height:1.12;flex-shrink:0;max-width:900px;
}
body[data-page="climate-resilience"] .papi-hero .slead{
  font-size:clamp(11px,1.3vh,16px);line-height:1.5;flex-shrink:0;max-width:760px;
}
body[data-page="climate-resilience"] .papi-hero .sdg-strip{
  margin-top:clamp(5px,1.1vh,20px)!important;gap:clamp(4px,.6vh,8px);flex-shrink:0;
}
body[data-page="climate-resilience"] .papi-hero .sdg-chip{
  font-size:clamp(7.5px,.8vh,9.5px);padding:clamp(3px,.4vh,5px) clamp(9px,1.1vh,13px) clamp(3px,.4vh,5px) clamp(3px,.4vh,5px);
}
body[data-page="climate-resilience"] .papi-hero .sdg-chip .sdg-num{
  width:clamp(15px,1.6vh,20px);height:clamp(15px,1.6vh,20px);font-size:clamp(8px,.9vh,10.5px);
}
body[data-page="climate-resilience"] .papi-hero .hero-stats{
  margin-top:clamp(6px,1.4vh,28px)!important;padding-top:clamp(6px,1.1vh,20px);
  gap:clamp(12px,2.6vw,40px);flex-shrink:0;
}
body[data-page="climate-resilience"] .papi-hero .hstat b{font-size:clamp(14px,1.9vh,29px)}
body[data-page="climate-resilience"] .papi-hero .hstat span{font-size:clamp(6.5px,.75vh,10.5px)}

body[data-page="child-resilience-index"] .papi-hero{
  min-height:100vh;min-height:100svh;
  padding:calc(var(--nav) + 5vh) 0 6vh;box-sizing:border-box;
}
body[data-page="child-resilience-index"] .papi-hero > .wrap{
  width:100%;box-sizing:border-box;
}
body[data-page="child-resilience-index"] .papi-hero .subpage-bar{
  font-size:clamp(8px,.95vh,10.5px);margin-bottom:clamp(6px,1.4vh,30px);flex-shrink:0;
}
body[data-page="child-resilience-index"] .papi-hero .hero-badge{
  font-size:clamp(8px,.9vh,10.5px);padding:clamp(4px,.7vh,8px) 16px;
  margin-bottom:clamp(6px,1.2vh,26px)!important;flex-shrink:0;
}
body[data-page="child-resilience-index"] .papi-hero h1{
  font-size:clamp(24px,4.8vh,58px)!important;margin-bottom:clamp(5px,1vh,14px)!important;
  line-height:1.12;flex-shrink:0;max-width:820px;
}
body[data-page="child-resilience-index"] .papi-hero .slead{
  font-size:clamp(11px,1.3vh,16px);line-height:1.5;flex-shrink:0;max-width:760px;
}
body[data-page="child-resilience-index"] .papi-hero .hero-ctas{
  margin-bottom:clamp(6px,1.3vh,20px)!important;gap:12px;flex-shrink:0;
}
body[data-page="child-resilience-index"] .papi-hero .hero-stats{
  margin-top:clamp(6px,1.4vh,28px)!important;padding-top:clamp(6px,1.1vh,20px);
  gap:clamp(12px,2.6vw,40px);flex-shrink:0;
}
body[data-page="child-resilience-index"] .papi-hero .hstat b{font-size:clamp(14px,1.9vh,29px)}
body[data-page="child-resilience-index"] .papi-hero .hstat span{font-size:clamp(6.5px,.75vh,10.5px)}
body[data-page="child-resilience-index"] .papi-hero .sdg-strip{
  margin-top:clamp(5px,1.1vh,20px)!important;gap:clamp(4px,.6vh,8px);flex-shrink:0;
}
body[data-page="child-resilience-index"] .papi-hero .sdg-chip{
  font-size:clamp(7.5px,.8vh,9.5px);padding:clamp(3px,.4vh,5px) clamp(9px,1.1vh,13px) clamp(3px,.4vh,5px) clamp(3px,.4vh,5px);
}
body[data-page="child-resilience-index"] .papi-hero .sdg-chip .sdg-num{
  width:clamp(15px,1.6vh,20px);height:clamp(15px,1.6vh,20px);font-size:clamp(8px,.9vh,10.5px);
}
@media(max-height:640px){body[data-page="child-resilience-index"] .papi-hero .hero-stats{display:none}}

/* ═══════════════════════════════════════════════════════════════════════
   SCROLL FLOW ENGINE — the cinematic, scroll-driven rhythm (numbered
   section counter, "scroll to explore" cue, full-screen video theatre,
   and horizontal slideshow galleries), applied across every page so the
   whole site moves the way the reference experience does.
   ═══════════════════════════════════════════════════════════════════════ */
.reveal{opacity:0;transform:translateY(46px) scale(.975);transition:opacity 1s cubic-bezier(.16,.8,.3,1),transform 1s cubic-bezier(.16,.8,.3,1)}
.reveal.in{opacity:1;transform:translateY(0) scale(1)}

.flow-counter{position:fixed;right:26px;bottom:26px;z-index:70;font-family:'JetBrains Mono',monospace;font-size:13px;letter-spacing:.1em;color:rgba(255,255,255,.75);display:flex;align-items:center;gap:8px;pointer-events:none;mix-blend-mode:difference;transition:opacity .4s ease}
.flow-counter b{color:#fff;font-size:22px;font-weight:600}
.flow-counter i{font-style:normal;opacity:.5}
@media(max-width:640px){.flow-counter{display:none}}


.h-gallery-wrap{position:relative;margin-top:40px}
.h-gallery{display:flex;gap:20px;overflow-x:auto;scroll-snap-type:x mandatory;scrollbar-width:none;-ms-overflow-style:none;padding:4px 4px 6px}
.h-gallery::-webkit-scrollbar{display:none}
.h-gallery .h-slide{flex:0 0 74%;max-width:620px;scroll-snap-align:start;border-radius:var(--r-card);overflow:hidden;position:relative;aspect-ratio:16/10;background:var(--void-2)}
.h-gallery .h-slide img{width:100%;height:100%;object-fit:cover}
.h-gallery .h-slide-cap{position:absolute;left:0;right:0;bottom:0;padding:18px 20px;font-family:'JetBrains Mono',monospace;font-size:13px;letter-spacing:.06em;color:#fff;background:linear-gradient(0deg,rgba(0,0,0,.7),transparent)}
.h-gallery-nav{display:flex;justify-content:center;gap:10px;margin-top:16px}
.h-gallery-dot{width:7px;height:7px;border-radius:50%;background:rgba(0,0,0,.18);cursor:pointer;transition:.25s}
.h-gallery-dot.active{background:var(--gold);width:22px;border-radius:4px}
.h-gallery-arrow{position:absolute;top:50%;transform:translateY(-50%);width:44px;height:44px;border-radius:50%;background:rgba(10,12,9,.65);border:1px solid rgba(255,255,255,.2);color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;z-index:5;backdrop-filter:blur(4px);font-size:25px}
.h-gallery-arrow.prev{left:-6px}
.h-gallery-arrow.next{right:-6px}
@media(max-width:820px){.h-gallery-arrow{display:none}.h-gallery .h-slide{flex-basis:86%}}
/* dark sections (void background) need light dots */
.section-void .h-gallery-dot{background:rgba(255,255,255,.22)}
/* ── LIQUID CURSOR GLOW (desktop, fine-pointer only) ── */
#klCursorGlow{position:fixed;top:0;left:0;width:340px;height:340px;pointer-events:none;z-index:9999;border-radius:50%;background:radial-gradient(circle,rgba(201,240,77,.10),transparent 65%);transform:translate(-50%,-50%);will-change:transform;mix-blend-mode:screen}
body[data-page="home"] #klCursorGlow{background:radial-gradient(circle,rgba(200,146,42,.10),transparent 65%);mix-blend-mode:multiply}

/* ── Homepage-only: 3x vertical breathing room between sections ── */
body[data-page="home"] .section{padding:140px 0}
body[data-page="home"] .sys-board-head{padding:56px 0 32px}
body[data-page="home"] footer{padding-top:96px}
@media(max-width:900px){
  body[data-page="home"] .section{padding:90px 0}
  body[data-page="home"] footer{padding-top:64px}
}
@media(pointer:coarse),(prefers-reduced-motion:reduce){#klCursorGlow{display:none}}
</style>
</head>
<body data-page="<?= $page ?>" oncontextmenu="return false" onselectstart="return false" oncopy="return false" oncut="return false">
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
  <defs>
    <linearGradient id="klIconGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#E8F7A8"/>
      <stop offset="100%" stop-color="#8FAE3A"/>
    </linearGradient>
    <linearGradient id="klIconGradForest" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#3F9E63"/>
      <stop offset="100%" stop-color="#1B5C38"/>
    </linearGradient>
  </defs>
</svg>
<div id="klCursorGlow" aria-hidden="true"></div>
<svg width="0" height="0" style="position:absolute;overflow:hidden" aria-hidden="true">
  <filter id="navyGreenTint" color-interpolation-filters="sRGB">
    <feFlood flood-color="#5E8177" flood-opacity="1" result="tintColor"/>
    <feComposite in="tintColor" in2="SourceGraphic" operator="in"/>
  </filter>
</svg>
<div class="scroll-progress" id="scrollProgress"></div>
<header class="kl-h" id="klHeader">
  <a href="https://uif.kilimora.africa/" class="kl-logo">
    <img src="https://kilimora.africa/wp-content/uploads/2026/06/AgriKonnect-6-No-Background-scaled.png" alt="AgriKonnekt" draggable="false" class="kl-nodrag" style="filter:url(#navyGreenTint)">
  </a>
  <div class="kl-navwrap">
    <ul>
      <li class="kl-nav-item">
        <a class="kl-nav-link" href="<?= $hp ?>#product">Product <span class="kl-caret" aria-hidden="true"></span></a>
        <div class="kl-dropdown">
          <div class="kl-dropdown-inner">
            <a href="<?= $hp ?>#product">Overview<small>The platform, end to end</small></a>
            <a href="<?= $hp ?>#impact">Impact<small>Outcomes &amp; the SDGs</small></a>
            <a href="<?= $hp ?>#traceability">Traceability<small>Child labour protection</small></a>
            <a href="<?= $hp ?>#ecosystem">Ecosystem<small>How it all connects</small></a>
          </div>
        </div>
      </li>
      <li class="kl-nav-item">
        <a class="kl-nav-link" href="<?= $hp ?>#explore-more">Initiative <span class="kl-caret" aria-hidden="true"></span></a>
        <div class="kl-dropdown">
          <div class="kl-dropdown-inner">
            <a href="/financial-enablement"<?= $page==='financial-enablement' ? ' class="current"' : '' ?>>Financial Enablement<small>Live, explainable credit score</small></a>
            <a href="/child-aid-alignment"<?= $page==='child-aid-alignment' ? ' class="current"' : '' ?>>Child Aid Alignment<small>Fit for any child sector partner</small></a>
            <a href="/sport-development"<?= $page==='sport-development' ? ' class="current"' : '' ?>>Sport &amp; Development<small>The sister initiative</small></a>
            <a href="/climate-resilience"<?= $page==='climate-resilience' ? ' class="current"' : '' ?>>Climate &amp; Health Resilience<small>Warning before the shock lands</small></a>
            <a href="/child-resilience-index"<?= $page==='child-resilience-index' ? ' class="current"' : '' ?>>Child Resilience Index<small>Live nutrition, school &amp; climate risk</small></a>
          </div>
        </div>
      </li>
      <li><a class="kl-nav-link" href="<?= $hp ?>#journey">The Journey</a></li>
      <li><a class="kl-nav-link" href="<?= $hp ?>#africa">Africa &amp; Policy</a></li>
      <li><a class="kl-nav-link" href="<?= $hp ?>#contact">Contact</a></li>
    </ul>
    <button class="kl-ham" id="hamBtn" aria-label="Open menu"><span></span><span></span><span></span></button>
  </div>
</header>

<div class="mob-drawer" id="mobDrawer">
  <button class="mob-close" id="mobClose">✕</button>

  <div class="mob-group">
    <button class="mob-group-head" type="button" data-target="mobGroupProduct">Product <span class="kl-caret" aria-hidden="true"></span></button>
    <div class="mob-group-body" id="mobGroupProduct">
      <a href="<?= $hp ?>#product">Overview</a>
      <a href="<?= $hp ?>#impact">Impact</a>
      <a href="<?= $hp ?>#traceability">Traceability</a>
      <a href="<?= $hp ?>#ecosystem">Ecosystem</a>
    </div>
  </div>

  <div class="mob-group">
    <button class="mob-group-head" type="button" data-target="mobGroupInitiative">Initiative <span class="kl-caret" aria-hidden="true"></span></button>
    <div class="mob-group-body" id="mobGroupInitiative">
      <a href="/financial-enablement"<?= $page==='financial-enablement' ? ' class="current"' : '' ?>>Financial Enablement</a>
      <a href="/child-aid-alignment"<?= $page==='child-aid-alignment' ? ' class="current"' : '' ?>>Child Aid Alignment</a>
      <a href="/sport-development"<?= $page==='sport-development' ? ' class="current"' : '' ?>>Sport &amp; Development</a>
      <a href="/climate-resilience"<?= $page==='climate-resilience' ? ' class="current"' : '' ?>>Climate &amp; Health Resilience</a>
      <a href="/child-resilience-index"<?= $page==='child-resilience-index' ? ' class="current"' : '' ?>>Child Resilience Index</a>
    </div>
  </div>

  <a class="mob-link" href="<?= $hp ?>#journey">The Journey</a>
  <a class="mob-link" href="<?= $hp ?>#africa">Africa &amp; Policy</a>
  <a class="mob-link" href="<?= $hp ?>#contact">Contact</a>
</div>

<!-- ═══════════════════════════════════════════════════════════════════════
     PAGE BODY — one of four routes, switched server-side. Each branch is
     a complete page (its own hero through its own closing section); only
     the <head>, header/nav, footer and base script below are shared.
     ═══════════════════════════════════════════════════════════════════════ -->
<?php if ($page === 'home'): ?>

<section class="hero" id="home">
  <video autoplay muted loop playsinline preload="metadata" disablePictureInPicture controlsList="nodownload noremoteplayback noplaybackrate" oncontextmenu="return false" draggable="false" class="kl-nodrag" aria-hidden="true">
    <source src="https://kilimora.africa/wp-content/uploads/2026/07/1.Header-Section-Video.mp4" type="video/mp4">
  </video>
  <div class="hero-in wrap">
    <div class="hero-badge"><span class="dot"></span> Scaled Pilot, Kenya · Built To Scale Across Sub-Saharan Africa</div>
    <h1>Community technology. <em>Built so a child eats, learns and stays well because of it.</em></h1>
    <p class="lead">AgriKonnekt links climate sensors, verified farmer identity, and market access into one system, engineered with consumer-grade rigor and measured by one thing: what changes for the child in the household behind every harvest.</p>
    <div class="hero-cta">
      <a href="#access" class="btn btn-solid">Access The Systems ↓</a>
      <a href="#journey" class="btn btn-line">Read the Journey</a>
      <a href="#contact" class="btn btn-line">Talk to the Team</a>
    </div>
    <div class="hero-stats">
      <div class="hstat"><b>75%+</b><span>of Kenya's food comes from smallholder farms, the households most rural children grow up in</span></div>
      <div class="hstat"><b>30%+</b><span>of harvest value lost before market, value that could cover a school fee or a clinic visit</span></div>
      <div class="hstat"><b>KENYA</b><span>piloting today, built from day one to scale across Sub-Saharan Africa's smallholder economies</span></div>
      <div class="hstat"><b>OPEN</b><span>licence stack, so no other child's household has to pay just to be seen</span></div>
    </div>
  </div>
</section>

<!-- ═══ SYSTEMS ACCESS BOARD — snapshot of every live system + direct access ═══ -->
<div class="sys-board" id="access">
  <div class="wrap reveal">
    <div class="sys-board-head">
      <span class="sys-board-tag"><span class="dot"></span> Five Systems · One Household Record · Open Access</span>
      <span class="sys-board-note">BSD / CERN / CC BY licensed · query any system directly, no story required · <a href="#opensource">eligibility &amp; licence stack ↓</a></span>
    </div>

    <div class="sys-row">
      <span class="sys-status s-live">Live</span>
      <div class="sys-main"><h4>Financial Enablement</h4><p>Explainable 24-point credit score from 8 everyday signals. Query the model or try the live demo.</p></div>
      <div class="sys-sdg">
        <span class="sdg-chip sdg-chip--sm"><span class="sdg-num" style="background:#E5243B">1</span>Poverty</span>
        <span class="sdg-chip sdg-chip--sm"><span class="sdg-num" style="background:#FD6925">9</span>Innovation</span>
      </div>
      <a href="/financial-enablement" class="sys-access">Access API →</a>
    </div>

    <div class="sys-row">
      <span class="sys-status s-proto">Prototype</span>
      <div class="sys-main"><h4>Child Resilience Index</h4><p>Composite nutrition, school continuity and climate shock score, fed by a live weather feed. Try the explorer.</p></div>
      <div class="sys-sdg">
        <span class="sdg-chip sdg-chip--sm"><span class="sdg-num" style="background:#DDA63A">2</span>Hunger</span>
        <span class="sdg-chip sdg-chip--sm"><span class="sdg-num" style="background:#C5192D">4</span>Education</span>
      </div>
      <a href="/child-resilience-index" class="sys-access">Open Index →</a>
    </div>

    <div class="sys-row">
      <span class="sys-status s-live">Live</span>
      <div class="sys-main"><h4>Climate &amp; Health Resilience</h4><p>Hazard maps and early warning built off the same verified household record, before the shock lands.</p></div>
      <div class="sys-sdg">
        <span class="sdg-chip sdg-chip--sm"><span class="sdg-num" style="background:#4C9F38">3</span>Health</span>
        <span class="sdg-chip sdg-chip--sm"><span class="sdg-num" style="background:#3F7E44">13</span>Climate</span>
      </div>
      <a href="/climate-resilience" class="sys-access">See Forecasts →</a>
    </div>

    <div class="sys-row">
      <span class="sys-status s-demo">Reference</span>
      <div class="sys-main"><h4>Child Aid Alignment</h4><p>How the platform maps onto the five priorities every child focused funder is already scored against.</p></div>
      <div class="sys-sdg">
        <span class="sdg-chip sdg-chip--sm"><span class="sdg-num" style="background:#26BDE2">6</span>Water</span>
        <span class="sdg-chip sdg-chip--sm"><span class="sdg-num" style="background:#19486A">17</span>Partners</span>
      </div>
      <a href="/child-aid-alignment" class="sys-access">See The Fit →</a>
    </div>

    <div class="sys-row">
      <span class="sys-status s-demo">Reference</span>
      <div class="sys-main"><h4>Sport &amp; Community Development</h4><p>Courts, coaches, mentors and vocational pathways, the sister initiative for a child's life outside the farm.</p></div>
      <div class="sys-sdg">
        <span class="sdg-chip sdg-chip--sm"><span class="sdg-num" style="background:#FD9D24">11</span>Community</span>
        <span class="sdg-chip sdg-chip--sm"><span class="sdg-num" style="background:#00689D">16</span>Protection</span>
      </div>
      <a href="/sport-development" class="sys-access">Open Section →</a>
    </div>
  </div>
</div>

<div class="ticker">
  <div class="ticker-track">
    <span class="ticker-item"><b>SOIL_MOISTURE</b> STABLE <span class="sep">·</span></span>
    <span class="ticker-item"><b>RAINFALL_ANOMALY</b> DETECTED, MAKUENI <span class="sep">·</span></span>
    <span class="ticker-item"><b>LIVESTOCK_HEALTH</b> WITHIN RANGE <span class="sep">·</span></span>
    <span class="ticker-item"><b>VERIFIED_ID</b> ISSUED, 0000241 <span class="sep">·</span></span>
    <span class="ticker-item"><b>MARKET_LINK</b> ACTIVE, AGGREGATOR_003 <span class="sep">·</span></span>
    <span class="ticker-item"><b>CLIMATE_FINANCE</b> ELIGIBILITY CONFIRMED <span class="sep">·</span></span>
    <span class="ticker-item"><b>HARVEST_LOSS</b> RISK LOWERED <span class="sep">·</span></span>
    <span class="ticker-item"><b>DOCUMENTED AT</b> uif.kilimora.africa <span class="sep">·</span></span>
    <span class="ticker-item"><b>SOIL_MOISTURE</b> STABLE <span class="sep">·</span></span>
    <span class="ticker-item"><b>RAINFALL_ANOMALY</b> DETECTED, MAKUENI <span class="sep">·</span></span>
    <span class="ticker-item"><b>LIVESTOCK_HEALTH</b> WITHIN RANGE <span class="sep">·</span></span>
    <span class="ticker-item"><b>VERIFIED_ID</b> ISSUED, 0000241 <span class="sep">·</span></span>
    <span class="ticker-item"><b>MARKET_LINK</b> ACTIVE, AGGREGATOR_003 <span class="sep">·</span></span>
    <span class="ticker-item"><b>CLIMATE_FINANCE</b> ELIGIBILITY CONFIRMED <span class="sep">·</span></span>
    <span class="ticker-item"><b>HARVEST_LOSS</b> RISK LOWERED <span class="sep">·</span></span>
    <span class="ticker-item"><b>DOCUMENTED AT</b> uif.kilimora.africa <span class="sep">·</span></span>
  </div>
</div>

<!-- ═══ WHY WE BUILT THIS ═══ -->
<section class="section" id="challenge">
  <div class="wrap reveal">
    <div class="eyebrow">Why We Built This For Families</div>
    <h2 class="stitle">The children of Kenya's farms deserve infrastructure worthy of them</h2>
    <div class="challenge-grid">
      <div>
        <p>More than 75% of Kenya's food comes from smallholder farms. These families face droughts, irregular rainfall, livestock disease and soil degradation, then lose up to 30% of harvest value before it ever reaches a buyer. That lost value is a missed meal, a delayed school fee, a postponed clinic visit.</p>
        <p>Weather, financing, logistics and market access have always run as separate, disconnected tools. Farmers piece it together alone, and children absorb whatever falls through the gaps. Every harvest on this platform carries a traceable labour record, so a child's work is never mistaken for an adult's.</p>
        <div class="calligraphy">A farmer who is <span class="mark">verified</span> can be financed, insured and paid fairly. When that verification never happens, it is the child at home who quietly absorbs the risk first.</div>
        <p>AgriKonnekt closes that gap: one connected system for the families the formal economy overlooks, built around a single rule. A child's daily wellbeing sits at the centre of every decision the platform makes.</p>
      </div>
      <div class="challenge-imgs">
        <a href="#" draggable="false" onclick="return false" class="kl-nodrag"><img src="https://kilimora.africa/wp-content/uploads/2026/07/Children-Trad-Hut-scaled.jpg" alt="A rural household in Kenya" draggable="false"></a>
        <a href="#" draggable="false" onclick="return false" class="kl-nodrag"><img src="https://kilimora.africa/wp-content/uploads/2026/07/Children-Pause-scaled.jpg" alt="Children in a farming community" draggable="false"></a>
      </div>
    </div>
    <div class="h-gallery-wrap">
      <div class="h-gallery kl-nodrag">
        <div class="h-slide"><img src="https://kilimora.africa/wp-content/uploads/2026/07/Children-Trad-Hut-scaled.jpg" alt="A household in a rural farming community" draggable="false"><div class="h-slide-cap">The household the system is built around</div></div>
        <div class="h-slide"><img src="https://kilimora.africa/wp-content/uploads/2026/07/Children-Pause-scaled.jpg" alt="Children pausing between chores on a farm" draggable="false"><div class="h-slide-cap">A childhood shielded from the shock, rather than shaped by it</div></div>
        <div class="h-slide"><img src="https://kilimora.africa/wp-content/uploads/2026/07/Children-Tech-Build-scaled.jpg" alt="Children engaging with new technology" draggable="false"><div class="h-slide-cap">Technology built to reach the child at home</div></div>
        <div class="h-slide"><img src="https://kilimora.africa/wp-content/uploads/2026/07/Happy-Children-Suitcase-scaled.jpg" alt="Happy children carrying a suitcase" draggable="false"><div class="h-slide-cap">What every gain in the system is measured against</div></div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ TWO MORE DOORS ═══ -->
<section class="section section-void" id="explore-more" style="padding-top:70px;padding-bottom:70px">
  <div class="wrap reveal">
    <div class="eyebrow">A Child's World Extends Well Beyond The Harvest</div>
    <h2 style="font-size:clamp(28px,3.6vw,40px);max-width:760px;margin-bottom:12px;color:#fff">Five more doors into the same mission</h2>
    <p class="slead" style="max-width:660px">AgriKonnekt is the harvest. What happens behind the numbers, what a child does after school, and whether a household gets a warning before the next shock all matter just as much. One verified household record opens every door below, protecting a child from exploitative labour first, then carrying that household into finance, education, sport, and climate resilience.</p>
    <div class="gate-grid">
      <a href="/child-resilience-index" class="gate-tile gate-tile--resilience">
        <span class="gate-kicker">Newest System</span>
        <h3>Child Resilience Index</h3>
        <p>One live composite score built from food security, school versus labour risk, and climate shock exposure, fed by a live weather feed and open for any partner to query directly.</p>
        <div class="sdg-strip">
          <span class="sdg-chip"><span class="sdg-num" style="background:#DDA63A">2</span>Zero Hunger</span>
          <span class="sdg-chip"><span class="sdg-num" style="background:#C5192D">4</span>Education</span>
          <span class="sdg-chip"><span class="sdg-num" style="background:#3F7E44">13</span>Climate Action</span>
        </div>
        <span class="gate-cta">Open the Index →</span>
      </a>
      <a href="/climate-resilience" class="gate-tile gate-tile--climate">
        <span class="gate-kicker">Fourth Pathway</span>
        <h3>Climate &amp; Health Resilience</h3>
        <p>The same verified household record turned into hazard maps, early warnings, and health surge forecasts, so a family hears about the flood, heat wave, or outbreak while there is still time to prepare for it.</p>
        <div class="sdg-strip">
          <span class="sdg-chip"><span class="sdg-num" style="background:#4C9F38">3</span>Good Health</span>
          <span class="sdg-chip"><span class="sdg-num" style="background:#3F7E44">13</span>Climate Action</span>
          <span class="sdg-chip"><span class="sdg-num" style="background:#FD9D24">11</span>Community</span>
        </div>
        <span class="gate-cta">Open Climate Resilience →</span>
      </a>
      <a href="/financial-enablement" class="gate-tile gate-tile--finance">
        <span class="gate-kicker">Live System</span>
        <h3>Financial Enablement</h3>
        <p>An explainable credit score built from eight everyday signals, mobile money, airtime, cooperative savings, utility repayment and verified farm data among them, streaming in real time exactly as it lands in the field.</p>
        <div class="sdg-strip">
          <span class="sdg-chip"><span class="sdg-num" style="background:#E5243B">1</span>No Poverty</span>
          <span class="sdg-chip"><span class="sdg-num" style="background:#FD6925">9</span>Innovation</span>
          <span class="sdg-chip"><span class="sdg-num" style="background:#19486A">17</span>Partnerships</span>
        </div>
        <span class="gate-cta">Open Financial Enablement →</span>
      </a>
      <a href="/child-aid-alignment" class="gate-tile gate-tile--child">
        <span class="gate-kicker">Sector Fit</span>
        <h3>Child Aid Alignment</h3>
        <p>How this platform maps onto the five things every child focused funder or implementer already shows up for, health, nutrition, education, water and emergency response, and the funding mechanisms built to back exactly that.</p>
        <div class="sdg-strip">
          <span class="sdg-chip"><span class="sdg-num" style="background:#4C9F38">3</span>Health</span>
          <span class="sdg-chip"><span class="sdg-num" style="background:#C5192D">4</span>Education</span>
          <span class="sdg-chip"><span class="sdg-num" style="background:#26BDE2">6</span>Water</span>
        </div>
        <span class="gate-cta">See the alignment →</span>
      </a>
      <a href="/sport-development" class="gate-tile gate-tile--sport">
        <span class="gate-kicker">Sister Initiative</span>
        <h3>Sport &amp; Community Development</h3>
        <p>Courts, coaches, mentors, arts and vocational pathways, plus a new education hub with cooperative partners upskilling youth in agribusiness. See the evidence, the ecosystem, and the gaps we are closing.</p>
        <div class="sdg-strip">
          <span class="sdg-chip"><span class="sdg-num" style="background:#4C9F38">3</span>Health</span>
          <span class="sdg-chip"><span class="sdg-num" style="background:#FD9D24">11</span>Community</span>
          <span class="sdg-chip"><span class="sdg-num" style="background:#00689D">16</span>Protection</span>
        </div>
        <span class="gate-cta">Open the section →</span>
      </a>
    </div>
  </div>
</section>

<!-- ═══ THE JOURNEY (signature story map) ═══ -->
<section class="section" id="journey" style="background:var(--cream-warm)">
  <div class="wrap reveal">
    <div style="display:flex;justify-content:center;margin-bottom:8px">
      <svg width="150" height="140" viewBox="0 0 240 220" aria-hidden="true">
        <path d="M20,150 Q120,205 220,150" fill="none" stroke="var(--gold)" stroke-width="2.4" stroke-linecap="round"/>
        <path d="M120,150 C116,110 128,80 120,42" fill="none" stroke="url(#klIconGradForest)" stroke-width="2.4" stroke-linecap="round"/>
        <path d="M120,96 C96,90 84,70 88,50 C110,54 122,72 120,96 Z" fill="none" stroke="var(--forest-mid)" stroke-width="2"/>
        <path d="M120,80 C144,74 156,54 152,34 C130,38 118,56 120,80 Z" fill="none" stroke="var(--forest-mid)" stroke-width="2"/>
        <circle cx="120" cy="34" r="6" fill="var(--gold)"/>
        <circle cx="45" cy="120" r="3" fill="var(--gold)" opacity=".7"/>
        <circle cx="195" cy="120" r="3" fill="var(--gold)" opacity=".7"/>
        <circle cx="70" cy="180" r="2.4" fill="var(--forest)" opacity=".5"/>
        <circle cx="170" cy="180" r="2.4" fill="var(--forest)" opacity=".5"/>
      </svg>
    </div>
    <div class="eyebrow" style="justify-content:center">A Household Learning To Grow Something It Can Finally Trust</div>
    <h2 class="stitle" style="text-align:center;margin-left:auto;margin-right:auto">A journey map, told the way it happens inside a single household</h2>
    <p class="slead" style="text-align:center;margin-left:auto;margin-right:auto">Seven chapters trace the same journey every pilot family makes, from a child waking up to a farm nobody can vouch for, to a piece of technology the next county can simply inherit and put to work on its own terms.</p>

    <div class="journey">
      <div class="jline"></div>

      <div class="jitem">
        <div class="jdot">01</div>
        <div class="jbody">
          <h3>Before Dawn</h3>
          <div class="calligraphy">A child wakes to a house that is quietly calculating whether this season will hold.</div>
          <p class="jnote">No sensor, ledger or lender has yet recorded anything about this farm. Every decision this year rests on memory and guesswork, and the child's routine, school fees, meals, medical care, moves with whatever the season decides.</p>
        </div>
      </div>

      <div class="jitem">
        <div class="jdot">02</div>
        <div class="jbody">
          <h3>The Blind Spot</h3>
          <div class="calligraphy">Insurers, buyers and lenders price risk onto a family whenever the farm behind it holds no verifiable record.</div>
          <p class="jnote">This is exactly the gap AgriKonnekt was built to close. A track record anyone can verify is what lets a household secure insurance, borrow on fair terms, and prove to a buyer that what they are growing deserves to be paid for properly.</p>
        </div>
      </div>

      <div class="jitem">
        <div class="jdot">03</div>
        <div class="jbody">
          <h3>First Instrument</h3>
          <div class="calligraphy">A modest sensor array and a satellite feed give the farm its first objective account of itself.</div>
          <p class="jnote">Low cost IoT sensors, satellite imagery and drone diagnostics begin tracking soil, crop health, livestock risk and weather. Findings return through low bandwidth dashboards and SMS, built for the connectivity rural Kenya actually has.</p>
        </div>
      </div>

      <div class="jitem">
        <div class="jdot">04</div>
        <div class="jbody">
          <h3>A Name That Holds</h3>
          <div class="calligraphy">The farm receives a verified digital identity, portable across every buyer, insurer and lender it will ever meet.</div>
          <p class="jnote">Verification is aggregated at the cooperative level and cryptographically anchored, so the record cannot be quietly altered later by whichever party benefits most from doing so. For the first time, the farm exists as a legible economic actor.</p>
        </div>
      </div>

      <div class="jitem">
        <div class="jdot">05</div>
        <div class="jbody">
          <h3>The Market Opens</h3>
          <div class="calligraphy">Verified data becomes a bridge to aggregators, institutional buyers and climate finance that were previously closed to the household.</div>
          <p class="jnote">A market coordination engine connects producers directly to buyers, optimises logistics, and removes the layers of informal intermediation that historically ate the difference between farm gate price and market price.</p>
        </div>
      </div>

      <div class="jitem">
        <div class="jdot">06</div>
        <div class="jbody">
          <h3>What Returns Home</h3>
          <div class="calligraphy">Income stability, recovered losses and reclaimed time return to the household, and reach the children inside it first.</div>
          <p class="jnote">This is the chapter the entire platform is measured against. Fewer harvest emergencies, steadier school fee payments, and fewer days a child spends pulled into harvest stress labour rather than a classroom.</p>
        </div>
      </div>

      <div class="jitem">
        <div class="jdot">07</div>
        <div class="jbody">
          <h3>The System Holds</h3>
          <div class="calligraphy">The infrastructure becomes a reusable public good, released openly for the next cooperative, the next county, the next country.</div>
          <p class="jnote">Everything built along the way, the verification protocol, the sensor firmware, the coordination engine, ships under an open licence, so the journey does not have to be paid for twice by the next community that needs it.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="flow-divider" style="background:var(--void)">
  <svg viewBox="0 0 1240 64" preserveAspectRatio="none" aria-hidden="true"><path d="M0 30 C 240 58, 380 4, 600 32 S 980 58, 1240 30" fill="none" stroke="url(#klIconGrad)" stroke-width="1.2" stroke-dasharray="1 5" opacity=".5"/></svg>
</div>

<!-- ═══ PRODUCT ═══ -->
<section class="section section-void" id="product">
  <div class="wrap reveal">
    <div class="eyebrow">The Product</div>
    <h2 class="stitle">One platform, five interlocking systems, one child in mind</h2>
    <p class="slead">AgriKonnekt consolidates five systems built in isolation from one another, climate intelligence, farmer verification, AI powered credit scoring, market access, and impact tracking, into one platform, delivered over the SMS and low bandwidth channels that actually work in rural Kenya rather than the connectivity a lab assumes. Every system below exists for one reason: a child's next meal should depend on a single, reliable record, rather than on five separate tools happening to sync up.</p>

    <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;margin:22px 0 30px;padding:16px 20px;background:rgba(255,255,255,.03);border-radius:12px">
      <span style="font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--lime-soft);white-space:nowrap">Live Product</span>
      <span style="color:rgba(255,255,255,.6);font-size:16px;flex:1;min-width:220px">AgriKonnekt already runs as a live commercial platform, onboarding real households and cooperatives, at <strong style="color:#fff">agrikonnekt.kilimora.africa</strong>. This site documents the open, publicly fundable layer underneath that platform, itself a proven system already in operation.</span>
      <a href="https://agrikonnekt.kilimora.africa" target="_blank" rel="noopener" class="btn btn-line" style="white-space:nowrap">Visit The Live Platform ↗</a>
    </div>

    <div class="chart-card" style="background:var(--void-2);margin-bottom:34px">
      <h4 style="color:#fff">Why one connected platform beats a weather app, a credit app, and a separate NGO programme</h4>
      <p style="font-size:16px;color:rgba(255,255,255,.55);margin-bottom:16px">That fragmented stack is exactly what already exists, and it is exactly the gap the problem statement describes. Each tool below solves one slice well, in complete isolation from the other three.</p>
      <div class="signal-wrap" style="grid-template-columns:1fr 1fr;gap:0;border-radius:14px;overflow:hidden">
        <div class="signal-half before">
          <span class="signal-h">A Stack Of Separate Tools</span>
          <div class="signal-row"><span>WEATHER_APP</span><span class="tag off">Isolated from credit</span></div>
          <div class="signal-row"><span>MICROFINANCE_APP</span><span class="tag off">Isolated from the harvest</span></div>
          <div class="signal-row"><span>MARKET_APP</span><span class="tag off">Isolated from the child</span></div>
          <div class="signal-row"><span>NGO_CASE_FILE</span><span class="tag off">Finds out weeks later</span></div>
        </div>
        <div class="signal-half after">
          <span class="signal-h">AgriKonnekt, One Household Record</span>
          <div class="signal-row"><span>CLIMATE_SIGNAL</span><span class="tag on">Feeds the credit score</span></div>
          <div class="signal-row"><span>CREDIT_SIGNAL</span><span class="tag on">Feeds the resilience index</span></div>
          <div class="signal-row"><span>MARKET_SIGNAL</span><span class="tag on">Feeds the traceability record</span></div>
          <div class="signal-row"><span>CHILD_RISK_SIGNAL</span><span class="tag on">Visible the same day it shifts</span></div>
        </div>
      </div>
    </div>

    <div class="sol-grid">
      <div class="sol-card">
        <div class="sol-num">SYSTEM 01 · SENSE</div>
        <h4>Climate Intelligence</h4>
        <p>IoT sensors, satellite data and drone supported diagnostics track crop health, soil condition, livestock risk and weather exposure, continuously and in the field. It is an early warning system, so a bad season gets caught before it becomes a bad year for a child at home.</p>
      </div>
      <div class="sol-card">
        <div class="sol-num">SYSTEM 02 · VERIFY</div>
        <h4>Digital Farmer Identity</h4>
        <p>A verified production identity for every farmer and cooperative, enabling traceability, buyer trust, and access to climate finance and insurance on fair terms. For a household, that is the difference between being invisible and being able to plan.</p>
      </div>
      <div class="sol-card">
        <div class="sol-num">SYSTEM 03 · SCORE</div>
        <h4>AI Powered Credit Scoring API</h4>
        <p>An explainable credit model built on mobile money history and airtime patterns, the record this household has in place of a bank statement. Savings and repayment discipline weigh as heavily as credit signals, so lenders, SACCOs and insurers plug into it to reach farmers everyone else calls "unbanked," on terms sized to what a household can carry.</p>
      </div>
      <div class="sol-card">
        <div class="sol-num">SYSTEM 04 · CONNECT</div>
        <h4>Market Coordination</h4>
        <p>Producers reach aggregators and institutional buyers directly, with logistics optimised to reduce losses that occur after harvest and close market inefficiencies, so the value recovered flows straight back into the household itself.</p>
      </div>
      <div class="sol-card">
        <div class="sol-num">SYSTEM 05 · TRACK</div>
        <h4>Sustainability Data</h4>
        <p>Publicly exposed, continuously updated impact data, income, resilience and emissions proxies, measurable by funders, insurers and policymakers alike, so a claim that the platform is helping children can be independently verified rather than taken on trust.</p>
      </div>
    </div>

    <div class="credit-spotlight">
      <div class="cs-left">
        <div class="mf-kicker" style="margin-bottom:6px">AI &amp; Technology · Innovation Track</div>
        <div class="sol-num">SYSTEM 03 · FINANCIAL ENABLEMENT — WHY IT IS HERE</div>
        <h4>Financial exclusion is a child protection issue before it is a banking one</h4>
        <p>Smallholder households without documented income are the population every formal lender writes off, and that exclusion is exactly where risk concentrates around a child. When a shock hits, a rejected loan or a bad season with no cushion, an unbanked household turns to informal lenders at extortionate rates, a distress sale of livestock, or pulling a child out of school. A credit score is the mechanism standing between a bad season and a child's future paying for it.</p>
        <p style="margin-top:16px">Financial inclusion has earned some scepticism, and rightly so: broad reviews of credit access programmes show small, inconsistent effects, while savings consistently show the more reliable gains for a poor household. This system was built with that evidence in mind. Cooperative and group savings sit on equal footing with credit signals, and the score exists purely to widen what a household can already prove about itself.</p>
        <p style="margin-top:16px">The real innovation is the data source: deliberately spread across signals rather than resting on one. A rural household without a bank account almost always has a phone, often belongs to a savings group, and pays for utilities on some kind of schedule. <strong style="color:#fff">Mobile money history, airtime patterns, cooperative savings, and pay-as-you-go repayment</strong> exist for nearly every one of them, generated automatically and updating in real time, where a bank statement never existed. Eight independent signals keep the score fair when any one goes quiet for a season.</p>
      </div>
      <div class="cs-right">
        <ul class="cs-list">
          <li><strong>Mobile money transaction history</strong> — deposit and withdrawal frequency, transfer patterns, and balance stability read as a live proxy for income regularity and financial discipline</li>
          <li><strong>Airtime usage patterns</strong> — top up frequency, amount, and consistency signal disposable income and cash flow rhythm even for a household with zero formal credit history</li>
          <li><strong>Cooperative &amp; group savings</strong> — chama, VSLA and SACCO contribution consistency, a signal that exists for millions of households with no individual bank account at all</li>
          <li><strong>Utility &amp; asset repayment</strong> — pay as you go solar, water and input financing repayment discipline, read the same way a loan repayment history would be</li>
          <li>Layered with verified farm data, production history, transaction records, satellite verified yields and repayment behaviour, building a credit file for a population the formal bureau system has never reached</li>
          <li>Explainable by design, every score returns the top factors driving it, giving a rejected applicant a clear picture of exactly what to strengthen next season</li>
          <li>Retrained continuously against real repayment outcomes and audited quarterly for bias, with particular attention to women led and youth led households</li>
          <li>A human reviewer sits above every automated decision at the lending institution; the model recommends, and a person makes the final call</li>
          <li>Farmers opt in and can see exactly what data feeds their score, with every signal collected and scored under explicit consent</li>
          <li>Delivered as a documented API so banks, SACCOs, microfinance institutions and insurers can integrate it directly into their own underwriting</li>
        </ul>
        <a href="/financial-enablement" class="btn btn-line" style="margin-top:22px">Open Financial Enablement →</a>
      </div>
    </div>

    <div class="metrics-framework">
      <div class="eyebrow" style="margin-top:60px">Scoring Metrics — Deep Dive</div>
      <h3 style="font-family:'EB Garamond',serif;font-size:38px;color:#fff;margin:8px 0 12px;line-height:1.3;max-width:640px">Twelve metrics, each one an outcome of the household's credit review</h3>
      <p style="color:rgba(255,255,255,.6);max-width:680px;font-size:19px;line-height:1.75;margin-bottom:8px">Most credit models measure a single moment and freeze a household there; this one is built to measure direction instead. The score runs on a <strong style="color:#fff">120-point scale across twelve equally weighted metrics</strong>, scored 1 to 10 each, and every metric tied to a child's day to day life is structured so it can only ever add to the total. Retraining happens every quarter, aimed squarely at catching more families climbing.</p>

      <div class="mf-grid mf-grid-12">
        <div class="mf-card">
          <div class="mf-kicker">Metric 01 · <span class="mf-pts">Up to 10 pts</span></div>
          <div class="mf-sub">Mobile Money Transaction Consistency</div>
          <ul>
            <li>Deposit, withdrawal and transfer rhythm read straight from the household's own phone activity, a live, real time proxy for income regularity</li>
          </ul>
        </div>
        <div class="mf-card">
          <div class="mf-kicker">Metric 02 · <span class="mf-pts">Up to 10 pts</span></div>
          <div class="mf-sub">Multi Season Yield &amp; Income Diversification</div>
          <ul>
            <li>Consistency measured across seasons rather than one lucky harvest, layered with off farm income streams that cushion a single crop shock</li>
          </ul>
        </div>
        <div class="mf-card">
          <div class="mf-kicker">Metric 03 · <span class="mf-pts">Up to 10 pts</span></div>
          <div class="mf-sub">Savings Trajectory</div>
          <ul>
            <li>Month over month growth in savings, rewarding sustained progress over where a household started</li>
          </ul>
        </div>
        <div class="mf-card">
          <div class="mf-kicker">Metric 04 · <span class="mf-pts">Up to 10 pts</span></div>
          <div class="mf-sub">Repayment Record &amp; Asset Building</div>
          <ul>
            <li>On time repayment against any prior formal or informal credit, plus a responsible, steady pace of tools or livestock added year over year</li>
          </ul>
        </div>
        <div class="mf-card mf-highlight">
          <div class="mf-kicker">Metric 05 · <span class="mf-pts">Up to 10 pts</span></div>
          <div class="mf-sub">School Enrollment &amp; Continuity</div>
          <ul>
            <li>Terms completed without interruption for every child in the household, structured so it can only ever add points</li>
          </ul>
        </div>
        <div class="mf-card mf-highlight">
          <div class="mf-kicker">Metric 06 · <span class="mf-pts">Up to 10 pts</span></div>
          <div class="mf-sub">Legal Identity &amp; Documentation</div>
          <ul>
            <li>Every child in the household holding a birth certificate, plus verified household spend on books, uniforms and transport to school</li>
          </ul>
        </div>
        <div class="mf-card mf-highlight">
          <div class="mf-kicker">Metric 07 · <span class="mf-pts">Up to 10 pts</span></div>
          <div class="mf-sub">Clinic &amp; Immunisation Consistency</div>
          <ul>
            <li>An opt in health visit record kept through county partners, routine rather than crisis triggered monitoring</li>
          </ul>
        </div>
        <div class="mf-card mf-highlight">
          <div class="mf-kicker">Metric 08 · <span class="mf-pts">Up to 10 pts</span></div>
          <div class="mf-sub">Nutrition Investment Trend</div>
          <ul>
            <li>A rising share of household spend going toward food diversity, tracked over time rather than as a single snapshot</li>
          </ul>
        </div>
        <div class="mf-card">
          <div class="mf-kicker">Metric 09 · <span class="mf-pts">Up to 10 pts</span></div>
          <div class="mf-sub">Climate Adaptation Investment</div>
          <ul>
            <li>Earnings directed into drought resistant seed and water storage, evidence the household is building resilience ahead of the next shock</li>
          </ul>
        </div>
        <div class="mf-card">
          <div class="mf-kicker">Metric 10 · <span class="mf-pts">Up to 10 pts</span></div>
          <div class="mf-sub">Land Stewardship &amp; Shock Recovery</div>
          <ul>
            <li>Soil health practices logged through the Sense system, and how fast production returns to baseline after a bad season</li>
          </ul>
        </div>
        <div class="mf-card">
          <div class="mf-kicker">Metric 11 · <span class="mf-pts">Up to 10 pts</span></div>
          <div class="mf-sub">Check In &amp; Referral Responsiveness</div>
          <ul>
            <li>Engaging with a wellbeing check in or following through on a referral earns points; a household with no data on this metric is scored as neutral, never as a deduction</li>
          </ul>
        </div>
        <div class="mf-card">
          <div class="mf-kicker">Metric 12 · <span class="mf-pts">Up to 10 pts</span></div>
          <div class="mf-sub">Market Linkage &amp; Buyer Trust</div>
          <ul>
            <li>Confirmed aggregator or institutional buyer relationships through the Connect system, evidence of income the household can actually count on</li>
          </ul>
        </div>
      </div>

      <div class="scorecard-block">
        <div class="sc-head">
          <span class="mf-kicker">Illustrative Scorecard</span>
          <span class="sc-note">A real household, midway through Year Two of the trajectory below</span>
        </div>
        <svg viewBox="0 0 598 418" xmlns="http://www.w3.org/2000/svg" style="width:100%;height:auto;margin-top:16px">
<text x="0" y="18" font-family="JetBrains Mono, monospace" font-size="11.5" font-weight="600" fill="rgba(255,255,255,.82)">Mobile Money Transaction Consistency</text>
<rect x="300" y="9" width="254" height="10" rx="5" fill="rgba(255,255,255,.08)"/>
<rect x="300" y="9" width="203" height="10" rx="5" fill="var(--lime)"/>
<text x="598" y="18" text-anchor="end" font-family="JetBrains Mono, monospace" font-size="12" font-weight="700" fill="#fff">8<tspan fill="rgba(255,255,255,.4)" font-weight="500">/10</tspan></text>

<text x="0" y="48" font-family="JetBrains Mono, monospace" font-size="11.5" font-weight="600" fill="rgba(255,255,255,.82)">Multi Season Yield &amp; Income Diversification</text>
<rect x="300" y="39" width="254" height="10" rx="5" fill="rgba(255,255,255,.08)"/>
<rect x="300" y="39" width="229" height="10" rx="5" fill="var(--lime)"/>
<text x="598" y="48" text-anchor="end" font-family="JetBrains Mono, monospace" font-size="12" font-weight="700" fill="#fff">9<tspan fill="rgba(255,255,255,.4)" font-weight="500">/10</tspan></text>

<text x="0" y="78" font-family="JetBrains Mono, monospace" font-size="11.5" font-weight="600" fill="rgba(255,255,255,.82)">Savings Trajectory</text>
<rect x="300" y="69" width="254" height="10" rx="5" fill="rgba(255,255,255,.08)"/>
<rect x="300" y="69" width="178" height="10" rx="5" fill="var(--lime)"/>
<text x="598" y="78" text-anchor="end" font-family="JetBrains Mono, monospace" font-size="12" font-weight="700" fill="#fff">7<tspan fill="rgba(255,255,255,.4)" font-weight="500">/10</tspan></text>

<text x="0" y="108" font-family="JetBrains Mono, monospace" font-size="11.5" font-weight="600" fill="rgba(255,255,255,.82)">Repayment Record &amp; Asset Building</text>
<rect x="300" y="99" width="254" height="10" rx="5" fill="rgba(255,255,255,.08)"/>
<rect x="300" y="99" width="203" height="10" rx="5" fill="var(--lime)"/>
<text x="598" y="108" text-anchor="end" font-family="JetBrains Mono, monospace" font-size="12" font-weight="700" fill="#fff">8<tspan fill="rgba(255,255,255,.4)" font-weight="500">/10</tspan></text>

<text x="0" y="138" font-family="JetBrains Mono, monospace" font-size="11.5" font-weight="600" fill="rgba(255,255,255,.82)">School Enrollment &amp; Continuity</text>
<rect x="300" y="129" width="254" height="10" rx="5" fill="rgba(255,255,255,.08)"/>
<rect x="300" y="129" width="254" height="10" rx="5" fill="var(--lime)"/>
<text x="598" y="138" text-anchor="end" font-family="JetBrains Mono, monospace" font-size="12" font-weight="700" fill="#fff">10<tspan fill="rgba(255,255,255,.4)" font-weight="500">/10</tspan></text>

<text x="0" y="168" font-family="JetBrains Mono, monospace" font-size="11.5" font-weight="600" fill="rgba(255,255,255,.82)">Legal Identity &amp; Documentation</text>
<rect x="300" y="159" width="254" height="10" rx="5" fill="rgba(255,255,255,.08)"/>
<rect x="300" y="159" width="229" height="10" rx="5" fill="var(--lime)"/>
<text x="598" y="168" text-anchor="end" font-family="JetBrains Mono, monospace" font-size="12" font-weight="700" fill="#fff">9<tspan fill="rgba(255,255,255,.4)" font-weight="500">/10</tspan></text>

<text x="0" y="198" font-family="JetBrains Mono, monospace" font-size="11.5" font-weight="600" fill="rgba(255,255,255,.82)">Clinic &amp; Immunisation Consistency</text>
<rect x="300" y="189" width="254" height="10" rx="5" fill="rgba(255,255,255,.08)"/>
<rect x="300" y="189" width="203" height="10" rx="5" fill="var(--lime)"/>
<text x="598" y="198" text-anchor="end" font-family="JetBrains Mono, monospace" font-size="12" font-weight="700" fill="#fff">8<tspan fill="rgba(255,255,255,.4)" font-weight="500">/10</tspan></text>

<text x="0" y="228" font-family="JetBrains Mono, monospace" font-size="11.5" font-weight="600" fill="rgba(255,255,255,.82)">Nutrition Investment Trend</text>
<rect x="300" y="219" width="254" height="10" rx="5" fill="rgba(255,255,255,.08)"/>
<rect x="300" y="219" width="254" height="10" rx="5" fill="var(--lime)"/>
<text x="598" y="228" text-anchor="end" font-family="JetBrains Mono, monospace" font-size="12" font-weight="700" fill="#fff">10<tspan fill="rgba(255,255,255,.4)" font-weight="500">/10</tspan></text>

<text x="0" y="258" font-family="JetBrains Mono, monospace" font-size="11.5" font-weight="600" fill="rgba(255,255,255,.82)">Climate Adaptation Investment</text>
<rect x="300" y="249" width="254" height="10" rx="5" fill="rgba(255,255,255,.08)"/>
<rect x="300" y="249" width="178" height="10" rx="5" fill="var(--lime)"/>
<text x="598" y="258" text-anchor="end" font-family="JetBrains Mono, monospace" font-size="12" font-weight="700" fill="#fff">7<tspan fill="rgba(255,255,255,.4)" font-weight="500">/10</tspan></text>

<text x="0" y="288" font-family="JetBrains Mono, monospace" font-size="11.5" font-weight="600" fill="rgba(255,255,255,.82)">Land Stewardship &amp; Shock Recovery</text>
<rect x="300" y="279" width="254" height="10" rx="5" fill="rgba(255,255,255,.08)"/>
<rect x="300" y="279" width="203" height="10" rx="5" fill="var(--lime)"/>
<text x="598" y="288" text-anchor="end" font-family="JetBrains Mono, monospace" font-size="12" font-weight="700" fill="#fff">8<tspan fill="rgba(255,255,255,.4)" font-weight="500">/10</tspan></text>

<text x="0" y="318" font-family="JetBrains Mono, monospace" font-size="11.5" font-weight="600" fill="rgba(255,255,255,.82)">Check In &amp; Referral Responsiveness</text>
<rect x="300" y="309" width="254" height="10" rx="5" fill="rgba(255,255,255,.08)"/>
<rect x="300" y="309" width="229" height="10" rx="5" fill="var(--lime)"/>
<text x="598" y="318" text-anchor="end" font-family="JetBrains Mono, monospace" font-size="12" font-weight="700" fill="#fff">9<tspan fill="rgba(255,255,255,.4)" font-weight="500">/10</tspan></text>

<text x="0" y="348" font-family="JetBrains Mono, monospace" font-size="11.5" font-weight="600" fill="rgba(255,255,255,.82)">Market Linkage &amp; Buyer Trust</text>
<rect x="300" y="339" width="254" height="10" rx="5" fill="rgba(255,255,255,.08)"/>
<rect x="300" y="339" width="178" height="10" rx="5" fill="var(--lime)"/>
<text x="598" y="348" text-anchor="end" font-family="JetBrains Mono, monospace" font-size="12" font-weight="700" fill="#fff">7<tspan fill="rgba(255,255,255,.4)" font-weight="500">/10</tspan></text>

<line x1="0" y1="370" x2="598" y2="370" stroke="rgba(255,255,255,.15)" stroke-width="1"/>
<text x="0" y="404" font-family="EB Garamond, serif" font-size="17" font-weight="600" fill="#fff">Composite Household Score</text>
<text x="598" y="406" text-anchor="end" font-family="JetBrains Mono, monospace" font-size="22" font-weight="700" fill="var(--lime)">100<tspan fill="rgba(255,255,255,.45)" font-size="15" font-weight="500"> / 120</tspan></text>
</svg>
        <p class="sc-caption">Illustrative only. Every household's actual factor scores are computed directly from its own verified data.</p>
      </div>

      <div class="calligraphy on-void" style="margin-top:44px">A child's wellbeing can only ever <span class="mark">raise</span> this score, standing purely on the side of a family's approval.</div>


      <div class="traj-wrap">
        <div class="eyebrow" style="margin-top:56px">What This Looks Like, Compounding</div>
        <h3 style="font-family:'EB Garamond',serif;font-size:34px;color:#fff;margin:8px 0 6px;line-height:1.3;max-width:600px">One household, three seasons, the same score doing more each year</h3>
        <p style="color:rgba(255,255,255,.55);max-width:640px;font-size:19px;line-height:1.7">This unfolds continuously rather than on a single form filled out once, the same twelve metrics compounding as a household's record deepens and unlocking more each year.</p>

        <div class="traj-row">
          <div class="traj-step">
            <div class="traj-yr">Year One · Verified</div>
            <h5>Seen, for the first time</h5>
            <p>Production history and baseline child indicators, enrollment, legal identity, are captured for the first time. The household qualifies for its first fair rate microloan, small enough to be safe, large enough to matter. A missed clinic visit simply flags a health partner to follow up before the season ends, at no cost to the family.</p>
          </div>
          <div class="traj-arrow">→</div>
          <div class="traj-step">
            <div class="traj-yr">Year Two · Recognized</div>
            <h5>Credit that notices the climb</h5>
            <p>Savings trajectory and nutrition trend both start counting for the first time, alongside continued school continuity. The credit limit grows, the interest rate drops, and the household gains access to weather index insurance for the first time, cushioning next season's drought before it becomes this year's crisis.</p>
          </div>
          <div class="traj-arrow">→</div>
          <div class="traj-step">
            <div class="traj-yr">Year Three · Compounding</div>
            <h5>A score the household builds on</h5>
            <p>Multi season resilience and verified asset growth unlock financing for the input package that lifts yield past subsistence into surplus. Every child in the household is enrolled and documented, and the family's own record becomes something they build on year after year, compounding rather than resetting each season.</p>
          </div>
        </div>

        <p class="traj-close">The score is designed to notice a family getting better and keep opening doors as they do, carrying that progress forward rather than starting the same questions over again next year.</p>
      </div>
    </div>

    <h3 style="font-size:28px;color:#fff;margin:64px 0 8px;font-family:'JetBrains Mono',monospace;font-weight:600;letter-spacing:.02em">The same farm, seen two ways</h3>
    <p style="color:rgba(255,255,255,.5);max-width:620px;margin-bottom:28px;font-size:19px">Every institution downstream of a farmer, a lender, an insurer, a buyer, is really just asking one question. Can this be trusted. Before verification, the honest answer is silence.</p>
    <div class="signal-wrap">
      <div class="signal-half before">
        <span class="signal-h">Without AgriKonnekt</span>
        <div class="signal-row"><span>PRODUCTION_HISTORY</span><span class="tag off">NO RECORD</span></div>
        <div class="signal-row"><span>SOIL_AND_WEATHER_DATA</span><span class="tag off">NOT CAPTURED</span></div>
        <div class="signal-row"><span>BUYER_TRUST</span><span class="tag off">UNVERIFIABLE</span></div>
        <div class="signal-row"><span>INSURANCE_ELIGIBILITY</span><span class="tag off">PENDING</span></div>
        <div class="signal-row"><span>CREDIT_ACCESS</span><span class="tag off">DECLINED</span></div>
        <p class="signal-caption">A household making every decision on memory, and absorbing every shock alone.</p>
      </div>
      <div class="signal-half after">
        <span class="signal-h">With AgriKonnekt</span>
        <div class="signal-row"><span>PRODUCTION_HISTORY</span><span class="tag ok">VERIFIED · 14 MONTHS</span></div>
        <div class="signal-row"><span>SOIL_AND_WEATHER_DATA</span><span class="tag ok">LIVE FEED</span></div>
        <div class="signal-row"><span>BUYER_TRUST</span><span class="tag ok">CONFIRMED</span></div>
        <div class="signal-row"><span>INSURANCE_ELIGIBILITY</span><span class="tag ok">QUALIFIED</span></div>
        <div class="signal-row"><span>CREDIT_ACCESS</span><span class="tag ok">APPROVED</span></div>
        <p class="signal-caption">A household visible to the systems built to protect it, for the first time.</p>
      </div>
    </div>

    <div style="margin-top:48px;display:flex;justify-content:center">
      <a href="#" draggable="false" onclick="return false" class="kl-nodrag" style="border-radius:var(--r-card);overflow:hidden;max-width:640px">
        <img src="https://kilimora.africa/wp-content/uploads/2026/07/Children-Tech-Build-scaled.jpg" alt="A community building technology tools together" draggable="false" style="border-radius:var(--r-card)">
      </a>
    </div>
  </div>
</section>

<!-- ═══ TRACEABILITY & CHILD LABOUR PROTECTION ═══ -->
<section class="section section-void" id="traceability">
  <div class="wrap reveal">
    <div class="eyebrow">Traceability &amp; Child Labour Protection</div>
    <h2 class="stitle">A value chain traced from farm to buyer, keeping every child visible along the way</h2>
    <p class="slead">Every verified record inside AgriKonnekt carries its origin with it, from the household that produced it all the way through to the buyer who eventually pays for it. That traceability serves a purpose well beyond proving what was grown: it is the mechanism that keeps a child out of harvest labour meant for someone older, holding every link in a supply chain accountable for what happens along it.</p>

    <div class="exec-list">
      <div class="exec-item"><b>Verified At The Source</b><span>Every farm entering the system is registered against a digital identity, with labour conditions recorded alongside yield and harvest data as a single, continuous exercise from day one.</span></div>
      <div class="exec-item"><b>Followed Through Every Step</b><span>A harvest is tracked from the household that grew it, through the cooperative, the aggregator, and on to the buyer or market that eventually receives it, keeping every stage of the chain visible and accountable for where it came from.</span></div>
      <div class="exec-item"><b>Open To Independent Review</b><span>The same public data feed that reports impact figures also exposes sourcing and labour data, so an outside auditor, buyer, or funder can check the chain directly rather than take a self reported claim on faith.</span></div>
      <div class="exec-item"><b>Built Around What A Household Needs Next</b><span>A verified household is protected from exploitative labour first, and that same verified record becomes the gateway into organised development pathways in education, sporting activity, and household finance.</span></div>
    </div>

    <p style="margin-top:34px;color:rgba(255,255,255,.6);max-width:760px">Those pathways live on their own pages, one for household finance, one for education and child aid, one for sport and community development, and one for climate and health resilience, and every one of them starts from the same verified, traceable household record.</p>

    <div style="display:flex;flex-wrap:wrap;gap:28px;margin-top:20px">
      <a href="/financial-enablement" class="gate-cta" style="color:var(--gold-bright)">Household Finance →</a>
      <a href="/child-aid-alignment" class="gate-cta" style="color:#5FB4E0">Education &amp; Child Aid →</a>
      <a href="/sport-development" class="gate-cta" style="color:var(--lime)">Sport &amp; Community →</a>
      <a href="/climate-resilience" class="gate-cta" style="color:var(--forest-mid)">Climate &amp; Health Resilience →</a>
    </div>
  </div>
</section>

<!-- ═══ IMPACT + SDGs ═══ -->
<section class="section" id="impact">
  <div class="wrap reveal">
    <div class="eyebrow">Impact &amp; Alignment</div>
    <h2 class="stitle">Twelve month targets, measured in what a child gets to keep</h2>
    <p class="slead">These are the targets for the first year, backed by real data updated continuously through active fieldwork. Every number below stands in for what a child in that household gets to keep that year.</p>
    <div class="impact-wrap">
      <div class="chart-card">
        <h4>Projected Improvement, First Year</h4>
        <div class="bar-row"><div class="bar-label"><span>Reduction in losses that occur after harvest</span><b>−40%</b></div><div class="bar-track"><div class="bar-fill" style="width:80%"></div></div></div>
        <div class="bar-row"><div class="bar-label"><span>Verified farmers with finance or insurance access</span><b>+50%</b></div><div class="bar-track"><div class="bar-fill" style="width:90%"></div></div></div>
        <div class="bar-row"><div class="bar-label"><span>Household income stability</span><b>+35%</b></div><div class="bar-track"><div class="bar-fill" style="width:70%"></div></div></div>
        <div class="bar-row"><div class="bar-label"><span>Women led and youth led households reached</span><b>+45%</b></div><div class="bar-track"><div class="bar-fill" style="width:78%"></div></div></div>
        <div class="bar-row"><div class="bar-label"><span>Reduction in children drawn into harvest stress labour</span><b>+25%</b></div><div class="bar-track"><div class="bar-fill" style="width:55%"></div></div></div>
        <p class="chart-note">Targets will be finalised against baseline pilot data and updated every quarter once fieldwork begins.</p>
      </div>
      <div>
        <p style="margin-bottom:16px;color:var(--mid)">Every gain above compounds into what a child in that household needs to develop: stable nutrition, uninterrupted schooling, and a home standing well clear of the next drought's reach. Stronger farmer incomes are among the most direct paths a child protection agenda has into rural wellbeing.</p>
        <p style="color:var(--mid)">Because the data layer is public and continuously updated, it functions as a living measurement system rather than a survey conducted once and archived, one that UNICEF, other humanitarian agencies and partner institutions can query at any time.</p>
      </div>
    </div>

    <div class="eyebrow" style="margin-top:72px">Sustainable Development Goals</div>
    <h3 style="font-size:34px;margin-bottom:6px;text-align:center">Eight goals, held together by one shared mandate</h3>
    <div class="sdg-un-badge">
      <div class="sdg-tile un-tile"><img src="https://kilimora.africa/wp-content/uploads/2026/07/E_SDG_logo_UN_emblem_square_trans_PRINT-scaled.png" alt="United Nations Sustainable Development Goals emblem" draggable="false" class="kl-nodrag"></div>
      <small>Global Goals</small>
    </div>
    <div class="sdg-goals-2x4">
      <div class="sdg-goal">
        <div class="sdg-tile" style="background:#E5243B"><img src="https://kilimora.africa/wp-content/uploads/2026/04/E_SDG_Icons-01.jpg" alt="SDG 1, No Poverty" draggable="false" class="kl-nodrag"></div>
        <small>01 · No Poverty</small>
      </div>
      <div class="sdg-goal">
        <div class="sdg-tile" style="background:#DDA63A"><img src="https://kilimora.africa/wp-content/uploads/2026/04/E_SDG_Icons-02.jpg" alt="SDG 2, Zero Hunger" draggable="false" class="kl-nodrag"></div>
        <small>02 · Zero Hunger</small>
      </div>
      <div class="sdg-goal">
        <div class="sdg-tile" style="background:#4C9F38"><img src="https://kilimora.africa/wp-content/uploads/2026/07/E-WEB-Goal-03.png" alt="SDG 3, Good Health and Wellbeing" draggable="false" class="kl-nodrag"></div>
        <small>03 · Good Health</small>
      </div>
      <div class="sdg-goal">
        <div class="sdg-tile" style="background:#FF3A21"><img src="https://kilimora.africa/wp-content/uploads/2026/04/E_SDG_Icons-05.jpg" alt="SDG 5, Gender Equality" draggable="false" class="kl-nodrag"></div>
        <small>05 · Gender Equality</small>
      </div>
      <div class="sdg-goal">
        <div class="sdg-tile" style="background:#A21942"><img src="https://kilimora.africa/wp-content/uploads/2026/04/E_SDG_Icons-08.jpg" alt="SDG 8, Decent Work and Economic Growth" draggable="false" class="kl-nodrag"></div>
        <small>08 · Decent Work</small>
      </div>
      <div class="sdg-goal">
        <div class="sdg-tile" style="background:#FD6925"><img src="https://kilimora.africa/wp-content/uploads/2026/07/E-WEB-Goal-09.png" alt="SDG 9, Industry, Innovation and Infrastructure" draggable="false" class="kl-nodrag"></div>
        <small>09 · Innovation</small>
      </div>
      <div class="sdg-goal">
        <div class="sdg-tile" style="background:#3F7E44"><img src="https://kilimora.africa/wp-content/uploads/2026/07/E-WEB-Goal-13.png" alt="SDG 13, Climate Action" draggable="false" class="kl-nodrag"></div>
        <small>13 · Climate Action</small>
      </div>
      <div class="sdg-goal">
        <div class="sdg-tile" style="background:#19486A"><img src="https://kilimora.africa/wp-content/uploads/2026/04/E_SDG_Icons-17.jpg" alt="SDG 17, Partnerships for the Goals" draggable="false" class="kl-nodrag"></div>
        <small>17 · Partnerships</small>
      </div>
    </div>
    <p class="sdg-caption">Every goal in this grid leads back to the same place, a child, growing up somewhere the system finally reaches.</p>
  </div>
</section>

<div class="stat-interlude">
  <div class="wrap reveal">
    <div class="eyebrow">The Number This Product Answers To</div>
    <div class="stat-big">30<em>%</em></div>
    <p>of harvested value, recovered back into the household, one verified farm at a time</p>
  </div>
</div>

<!-- ═══ UNICEF STRATEGIC ALIGNMENT ═══ -->
<!-- Child Aid Alignment now lives at /child-aid-alignment — brief teaser only lives above in #explore-more -->

<!-- ═══ ECOSYSTEM / SYSTEMIC CHANGE ═══ -->
<section class="section" id="ecosystem">
  <div class="wrap reveal">
    <div class="eyebrow">Systemic Change &amp; Stakeholder Practice</div>
    <h2 class="stitle">Infrastructure only holds if the relationships around it do</h2>
    <p class="slead">This platform functions as a dense, living web rather than a tidy org chart, nine categories of partner pulling in from every direction, tied to each other as much as to the platform at the centre, all of it converging on the same household.</p>

    <div class="eco-tree">
      <svg viewBox="0 0 1000 760" preserveAspectRatio="xMidYMid meet" aria-hidden="true">
        <!-- spokes: centre to every partner -->
        <g fill="none" stroke-width="1.4" opacity=".55">
          <path stroke="url(#klIconGradForest)" d="M500,380 L500,90"/>
          <path stroke="url(#klIconGradForest)" d="M500,380 L776.4,157.9"/>
          <path stroke="var(--gold)" d="M500,380 L923.5,329.7"/>
          <path stroke="var(--gold)" d="M500,380 L872.4,525"/>
          <path stroke="var(--clay)" d="M500,380 L647.1,652.5"/>
          <path stroke="var(--clay)" d="M500,380 L127.6,525"/>
          <path stroke="var(--lime-dim)" d="M500,380 L76.5,329.7"/>
          <path stroke="var(--lime-dim)" d="M500,380 L223.6,157.9"/>
        </g>
        <path fill="none" stroke="var(--bloom)" stroke-width="2.2" opacity=".75" d="M500,380 L342.7,671.3"/>

        <!-- mesh: partners tied directly to their nearest counterpart -->
        <g fill="none" stroke-width="1.2" opacity=".4">
          <path stroke="url(#klIconGradForest)" d="M500,90 L776.4,157.9"/>
          <path stroke="var(--gold)" d="M923.5,329.7 L872.4,525"/>
          <path stroke="var(--lime-dim)" d="M76.5,329.7 L223.6,157.9"/>
        </g>
        <g fill="none" stroke="var(--bloom)" stroke-width="1.6" opacity=".6">
          <path d="M342.7,671.3 L647.1,652.5"/>
          <path d="M342.7,671.3 L127.6,525"/>
        </g>

        <!-- cross-ties: the less obvious relationships that make it a web, not a hub -->
        <g fill="none" stroke="var(--mid)" stroke-width="1" stroke-dasharray="2 5" opacity=".38">
          <path d="M500,90 L647.1,652.5"/>
          <path d="M776.4,157.9 L923.5,329.7"/>
          <path d="M76.5,329.7 L872.4,525"/>
          <path d="M223.6,157.9 L127.6,525"/>
          <path d="M342.7,671.3 L776.4,157.9"/>
        </g>

        <circle cx="500" cy="380" r="17" fill="var(--forest)"/>
        <circle cx="500" cy="90" r="8" fill="var(--forest)"/>
        <circle cx="776.4" cy="157.9" r="8" fill="var(--forest)"/>
        <circle cx="923.5" cy="329.7" r="8" fill="var(--gold)"/>
        <circle cx="872.4" cy="525" r="8" fill="var(--gold)"/>
        <circle cx="647.1" cy="652.5" r="8" fill="var(--clay)"/>
        <circle cx="127.6" cy="525" r="8" fill="var(--clay)"/>
        <circle cx="76.5" cy="329.7" r="8" fill="var(--lime-dim)"/>
        <circle cx="223.6" cy="157.9" r="8" fill="var(--lime-dim)"/>
        <circle cx="342.7" cy="671.3" r="12" fill="var(--bloom)"/>
      </svg>

      <div class="eco-node" style="top:11.84%;left:50%"><span class="dot dot-forest"></span><b>Farmer Cooperatives</b></div>
      <div class="eco-node" style="top:20.78%;left:77.64%"><span class="dot dot-forest"></span><b>Community Verification Networks</b></div>
      <div class="eco-node" style="top:43.38%;left:92.35%"><span class="dot dot-gold"></span><b>County Extension Services</b></div>
      <div class="eco-node below" style="top:69.08%;left:87.24%"><span class="dot dot-gold"></span><b>Continental Policy Frameworks</b></div>
      <div class="eco-node below" style="top:85.86%;left:64.71%"><span class="dot dot-clay"></span><b>Microfinance &amp; Insurance Providers</b></div>
      <div class="eco-node below" style="top:88.33%;left:34.27%"><span class="dot dot-bloom"></span><b>Households &amp; Children</b></div>
      <div class="eco-node below" style="top:69.08%;left:12.76%"><span class="dot dot-clay"></span><b>Commodity Buyers &amp; Aggregators</b></div>
      <div class="eco-node" style="top:43.38%;left:7.65%"><span class="dot dot-lime"></span><b>Open Source Engineering Community</b></div>
      <div class="eco-node" style="top:20.78%;left:22.36%"><span class="dot dot-lime"></span><b>Independent Data Auditors</b></div>
      <div class="eco-node root" style="top:50%;left:50%"><span class="dot"></span><b>AgriKonnekt</b></div>
    </div>

    <div class="eco-legend">
      <span class="eco-legend-item"><i style="background:var(--forest)"></i>Grassroots &amp; Delivery</span>
      <span class="eco-legend-item"><i style="background:var(--gold)"></i>Government &amp; Policy</span>
      <span class="eco-legend-item"><i style="background:var(--clay)"></i>Finance &amp; Market</span>
      <span class="eco-legend-item"><i style="background:var(--lime-dim)"></i>Technology &amp; Evidence</span>
      <span class="eco-legend-item"><i style="background:var(--bloom)"></i>Households &amp; Children</span>
      <span class="eco-legend-item"><i style="background:var(--forest);width:13px;height:13px"></i>AgriKonnekt, the connective layer</span>
    </div>

    <div class="eco-list">
      <div class="eco-row"><span class="tag" style="background:var(--forest)"></span><div><h4>Farmer Cooperatives</h4><p>The source of truth for every record in the system, and the group whose trust determines whether verification is adopted rather than resisted. Murang'a Farmers Cooperative Union is the planned site of the platform's first Education Centre of Excellence, training the youth who will run these systems next.</p></div></div>
      <div class="eco-row"><span class="tag" style="background:var(--forest)"></span><div><h4>Community Verification Networks</h4><p>The local layer of trusted peers who cross check a record before it ever reaches a funder or a lender, catching details a form alone would miss.</p></div></div>
      <div class="eco-row"><span class="tag" style="background:var(--gold)"></span><div><h4>County Extension Services</h4><p>The existing government channel through which agronomic advice already reaches communities, and the natural home for long term platform stewardship.</p></div></div>
      <div class="eco-row"><span class="tag" style="background:var(--gold)"></span><div><h4>Continental Policy Frameworks</h4><p>The regional agricultural and child wellbeing agendas this platform was built to plug into directly, rather than operate alongside as an afterthought.</p></div></div>
      <div class="eco-row"><span class="tag" style="background:var(--clay)"></span><div><h4>Microfinance &amp; Insurance Providers</h4><p>The institutions that convert a verified record into an actual loan, premium or payout, turning data into a household's working capital.</p></div></div>
      <div class="eco-row"><span class="tag" style="background:var(--clay)"></span><div><h4>Commodity Buyers &amp; Aggregators</h4><p>The market side of the same relationship, paying a fairer price once a harvest and its origin can actually be verified.</p></div></div>
      <div class="eco-row"><span class="tag" style="background:var(--lime-dim)"></span><div><h4>Open Source Engineering Community</h4><p>The distributed group of engineers who audit, harden and extend the codebase once it is released publicly under an open licence.</p></div></div>
      <div class="eco-row"><span class="tag" style="background:var(--lime-dim)"></span><div><h4>Independent Data Auditors</h4><p>The outside reviewers who keep the public data feed honest, so impact figures are verified rather than merely self reported.</p></div></div>
      <div class="eco-row"><span class="tag" style="background:var(--bloom)"></span><div><h4>Households &amp; Children</h4><p>The reason the other eight relationships exist at all, and the measure every quarterly update is ultimately written for.</p></div></div>
    </div>
  </div>
</section>

<div class="flow-divider" style="background:var(--forest)">
  <svg viewBox="0 0 1240 64" preserveAspectRatio="none" aria-hidden="true"><path d="M0 30 C 200 2, 420 58, 640 28 S 1020 2, 1240 30" fill="none" stroke="url(#klIconGrad)" stroke-width="1.2" stroke-dasharray="1 5" opacity=".55"/></svg>
</div>

<!-- ═══ AFRICA & POLICY ═══ -->
<section class="section section-forest" id="africa">
  <div class="wrap reveal">
    <div class="eyebrow" style="color:var(--lime-soft)">Africa &amp; Sub Saharan Focus</div>
    <h2 class="stitle" style="color:#fff">Built to plug into the continent's own promise to its children</h2>
    <p class="slead" style="color:rgba(255,255,255,.68)">Sub Saharan Africa holds some of the largest reserves of uncultivated arable land in the world, and Kenya sits inside a continental agenda that already names agricultural transformation and child wellbeing as core promises. AgriKonnekt was built to deliver directly on that promise.</p>
    <div class="policy-grid">
      <div class="policy-card">
        <h4>AU Agenda 2063</h4>
        <p>Aspiration 1 calls for prosperity built on inclusive growth and sustainable development, and Aspiration 6 calls for people driven development that relies on the potential of African people, especially women and youth, and cares for children. Verification gives both a measurable data backbone.</p>
      </div>
      <div class="policy-card">
        <h4>Malabo Declaration &amp; CAADP</h4>
        <p>Supports the continental commitment to end hunger and halve losses that occur after harvest, by building the traceability and market linkage infrastructure that CAADP targets already assume exists.</p>
      </div>
      <div class="policy-card">
        <h4>Kenya Vision 2030 &amp; ASTGS</h4>
        <p>Directly supports the Agricultural Sector Transformation and Growth Strategy's push for climate smart, technology enabled, market linked farming under the Bottom Up Economic Transformation Agenda.</p>
      </div>
      <div class="policy-card">
        <h4>EAC Vision 2050</h4>
        <p>Contributes to regional food security and industrialisation goals by building interoperable data infrastructure that can scale across East African Community member states.</p>
      </div>
    </div>

    <h3 style="font-size:34px;margin-bottom:34px;color:#fff">Roadmap for the first year</h3>
    <div class="roadmap">
      <div class="rm-line"></div>
      <div class="rm-item"><div class="rm-dot">Q1</div><div class="rm-body"><h4 style="color:#fff">Verification &amp; baseline</h4><p style="color:rgba(255,255,255,.65)">Onboard priority cooperatives, issue digital farmer identities, and capture baseline household and climate risk data across target counties.</p></div></div>
      <div class="rm-item"><div class="rm-dot">Q2</div><div class="rm-body"><h4 style="color:#fff">Climate intelligence live</h4><p style="color:rgba(255,255,255,.65)">Deploy IoT and satellite monitoring with SMS advisory across pilot sites, and open the real time public data feed for external review.</p></div></div>
      <div class="rm-item"><div class="rm-dot">Q3</div><div class="rm-body"><h4 style="color:#fff">Market &amp; finance linkage</h4><p style="color:rgba(255,255,255,.65)">Connect verified farmers to aggregators, buyers and finance or insurance partners, and publish the first quarterly impact update.</p></div></div>
      <div class="rm-item"><div class="rm-dot">Q4</div><div class="rm-body"><h4 style="color:#fff">Scale &amp; open release</h4><p style="color:rgba(255,255,255,.65)">Publish the full BSD, CERN and CC BY licensed release, document lessons for replication, and prepare a scale up plan across additional counties and East African Community states.</p></div></div>
      <div class="rm-item"><div class="rm-dot" style="background:var(--gold)">Y2</div><div class="rm-body"><h4 style="color:#fff">Education Hub, Murang'a Farmers Cooperative Union</h4><p style="color:rgba(255,255,255,.65)">Building on Year One's verified household data, an education hub at Murang'a Farmers Cooperative Union upskills high schoolers and post secondary youth in agribusiness, with sport, nutrition and meals as the incentive that keeps them enrolled. AgriKonnekt leads as tech partner, building the human capital, entrepreneurs and future corporate leaders, that the whole agri value chain needs, from smallholder farmers and cooperatives to supply chain players and exporters.</p></div></div>
    </div>
  </div>
</section>

<!-- ═══ HOUSEHOLDS ═══ -->
<section class="section section-void" id="households">
  <div class="wrap reveal">
    <div class="eyebrow">Who We Reach</div>
    <h2 class="stitle" style="color:#fff">The households where a child has the most to lose</h2>
    <p class="slead" style="color:rgba(255,255,255,.6)">Targeting is deliberate. The households least visible to formal systems are the ones most exposed to climate shocks, and the ones where a child pays first when a harvest fails.</p>
    <div class="hh-grid">
      <div class="hh-imgwrap">
        <a href="#" draggable="false" onclick="return false" class="kl-nodrag"><img src="https://kilimora.africa/wp-content/uploads/2026/07/Happy-Children-Suitcase-scaled.jpg" alt="Children from a farming household" draggable="false"></a>
      </div>
      <div class="hh-cards">
        <div class="hh-card"><b>First At The Table</b><span>Low income smallholder households in counties with the highest climate exposure, where a lost harvest means a missed meal fastest</span></div>
        <div class="hh-card"><b>First Kept In School</b><span>Women led households, given lower access to land, finance and productivity tools, where school fees are usually the first cost a crisis cuts</span></div>
        <div class="hh-card"><b>First Into The Market</b><span>Youth led farming enterprises building first time formal market access, opening a future a younger sibling can inherit</span></div>
        <div class="hh-card"><b>First To Be Seen</b><span>Cooperative linked households that need a verified digital identity, so a child's home stops being invisible to the systems meant to protect it</span></div>
      </div>
    </div>
  </div>
</section>

<div class="flow-divider" style="background:var(--void)">
  <svg viewBox="0 0 1240 64" preserveAspectRatio="none" aria-hidden="true"><path d="M0 32 C 260 60, 400 6, 620 30 S 960 60, 1240 32" fill="none" stroke="url(#klIconGrad)" stroke-width="1.2" stroke-dasharray="1 5" opacity=".5"/></svg>
</div>

<!-- ═══ OPEN SOURCE ═══ -->
<section class="section" id="opensource">
  <div class="wrap reveal">
    <div class="eyebrow">Eligibility, Stated Plainly</div>
    <h2 class="stitle">Built directly against the fund's own criteria</h2>
    <p class="slead">Six requirements decide whether an Expression of Interest gets reviewed at all. Here is exactly how Kilimora and AgriKonnekt meet each one, answered plainly for the reviewer.</p>

    <div class="elig-grid">
      <div class="elig-card">
        <span class="elig-check">✓</span>
        <div><h5>Registered Private Company</h5><p>Kilimora is registered as a private company, the fund's first entry point.</p></div>
      </div>
      <div class="elig-card">
        <span class="elig-check">✓</span>
        <div><h5>Registered In A Programme Country</h5><p>Incorporated in Kenya, a confirmed UNICEF programme country with an already established track record.</p></div>
      </div>
      <div class="elig-card">
        <span class="elig-check">✓</span>
        <div><h5>Open Source By Licence</h5><p>Software under BSD 3-Clause, hardware under CERN Open Hardware Licence, design and content under CC BY 4.0, live in the release today.</p></div>
      </div>
      <div class="elig-card">
        <span class="elig-check">✓</span>
        <div><h5>Scaled Pilot, Real Households</h5><p>AgriKonnekt runs live in Kenya today, with households, farm records and cooperative pilots on the platform, and an architecture built to scale straight across Sub-Saharan Africa.</p></div>
      </div>
      <div class="elig-card">
        <span class="elig-check">✓</span>
        <div><h5>Built For The Most Vulnerable Children</h5><p>Every system on the platform, credit, climate, school continuity, is scored and routed toward the households a child's daily wellbeing depends on most directly.</p></div>
      </div>
      <div class="elig-card">
        <span class="elig-check">✓</span>
        <div><h5>Public, Measurable, Real Time Data</h5><p>Live feeds and an open API expose the same data a reviewer can independently verify in real time.</p></div>
      </div>
    </div>

    <div class="os-wrap">
      <div>
        <div class="os-badge">
          <img src="https://kilimora.africa/wp-content/uploads/2026/07/OSI-Logo.png" alt="Open Source Initiative" class="kl-nodrag" draggable="false">
          <div><b>OSI Principles Designed</b><span>Read the founding principles at <a href="https://opensource.org/licenses" target="_blank" rel="noopener" style="color:var(--forest);user-select:text">opensource.org/licenses</a></span></div>
        </div>
        <p style="color:var(--mid);margin-bottom:18px">Copyright in the underlying code exists automatically from the moment it is written, with registration entirely optional. The licences below stand as an explicit, standing grant of permission for open, public reuse.</p>
        <table class="license-table">
          <tr><td>Software</td><td>BSD 3 Clause License, open source and OSI principles designed, with the full text included in the project's LICENSE file.</td></tr>
          <tr><td>Hardware</td><td>CERN Open Hardware Licence, applied to the IoT sensor and diagnostic device designs.</td></tr>
          <tr><td>Design &amp; content</td><td>Creative Commons Attribution 4.0 International, applied to documentation, design assets and written content.</td></tr>
        </table>
      </div>
      <div>
        <div class="chart-card" style="background:var(--cream-warm)">
          <h4>What that looks like in practice</h4>
          <div style="display:flex;flex-direction:column;gap:16px">
            <div><b style="color:var(--forest);font-family:'EB Garamond',serif;font-size:25px">✓ Live and working</b><p style="font-size:16px;color:var(--mid);margin-top:4px">AgriKonnekt is a working platform already in active use today.</p></div>
            <div><b style="color:var(--forest);font-family:'EB Garamond',serif;font-size:25px">✓ Open by default</b><p style="font-size:16px;color:var(--mid);margin-top:4px">Every layer, code, hardware designs and content, is OSI principles designed or released under a CC BY licence.</p></div>
            <div><b style="color:var(--forest);font-family:'EB Garamond',serif;font-size:25px">✓ Transparent, verifiable data</b><p style="font-size:16px;color:var(--mid);margin-top:4px">Impact data is publicly exposed in real time and stands up to independent verification.</p></div>
            <div><b style="color:var(--forest);font-family:'EB Garamond',serif;font-size:25px">✓ Built for reuse</b><p style="font-size:16px;color:var(--mid);margin-top:4px">Anyone, anywhere, can inspect, adapt, and build freely on the platform.</p></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ OPEN CORE: WHAT IS OPEN, WHAT STAYS COMMERCIAL ═══ -->
<section class="section section-void">
  <div class="wrap reveal">
    <div class="eyebrow">Beyond The Commercial Platform</div>
    <h2 class="stitle" style="color:#fff">A genuine open core, licensed as such from day one</h2>
    <p class="slead" style="color:rgba(255,255,255,.6)">agrikonnekt.kilimora.africa is Kilimora's commercial enterprise platform, the product that pays for the team and keeps this work funded past any single grant cycle. What is proposed to the Innovation Fund is a distinct, separable layer: the specific components a child's safety and a household's climate exposure depend on, released under open licence so they stand independently of Kilimora's future as a company.</p>
    <div class="core-wrap">
      <div class="core-col open">
        <span class="core-tag">Open · BSD / CERN / CC BY</span>
        <h4>The Public Good Layer</h4>
        <p class="core-lead">Forkable and independently inspectable by any ministry, NGO, or competitor, regardless of where Kilimora stands as a company five years from now.</p>
        <ul>
          <li>Child Resilience Index scoring methodology, weights and tier logic</li>
          <li>Climate hazard and early warning data schema and API contract</li>
          <li>IoT sensor and diagnostic device hardware designs (CERN OHL)</li>
          <li>Verified household identity schema and consent protocol</li>
          <li>Documentation, training materials and a reference implementation (CC BY 4.0)</li>
          <li>A hosted reference API any accredited partner can query directly, free of charge</li>
        </ul>
      </div>
      <div class="core-col commercial">
        <span class="core-tag">Commercial · agrikonnekt.kilimora.africa</span>
        <h4>The Enterprise Platform</h4>
        <p class="core-lead">What a paying cooperative, lender, or aggregator actually needs day to day, and the revenue that funds continued maintenance of the open layer beside it.</p>
        <ul>
          <li>Full platform hosting, uptime guarantees and multi tenant infrastructure</li>
          <li>Buyer, lender and cooperative account management and dashboards</li>
          <li>Premium integrations with SACCOs, insurers and aggregator ERPs</li>
          <li>Advanced analytics, forecasting and business intelligence tooling</li>
          <li>Dedicated onboarding, training and account support</li>
          <li>Revenue that sustains the open layer independently of ongoing grant funding</li>
        </ul>
      </div>
    </div>
    <p style="font-family:'EB Garamond',serif;font-style:italic;color:rgba(255,255,255,.4);max-width:720px;margin-top:24px;font-size:16px">This is the standard open core structure: the components a public funder should be able to trust regardless of Kilimora's commercial fortunes are licensed openly and forever, while the infrastructure a paying enterprise customer needs stays commercial, fully separate from anyone's ability to inspect, self host, or build on the open layer.</p>
  </div>
</section>

<!-- Sport & Community Development now lives at /sport-development — brief teaser only lives above in #explore-more -->

<!-- ═══ CONTACT ═══ -->
<!-- ═══ FOOTER ═══ -->

<?php elseif ($page === 'financial-enablement'): ?>

<section class="papi-hero">
  <video autoplay muted loop playsinline preload="metadata" disablePictureInPicture controlsList="nodownload noremoteplayback noplaybackrate" oncontextmenu="return false" draggable="false" class="kl-nodrag" aria-hidden="true">
    <source src="https://kilimora.africa/wp-content/uploads/2026/07/Header-Financial.mp4" type="video/mp4">
  </video>
  <svg class="papi-canvas" viewBox="0 0 1200 520" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
    <path class="fin-line" d="M740,400 C 765,380 775,395 790,370 C 810,345 815,400 830,410 C 855,390 865,355 880,340 C 900,320 905,315 920,300 C 940,285 950,335 960,320 C 985,280 995,250 1010,230 C 1030,200 1045,175 1060,150" />
    <g>
      <circle class="fin-node" cx="740" cy="400" r="8" style="animation-delay:.3s,3s"/><circle class="fin-node-core" cx="740" cy="400" r="2.6" style="animation-delay:.3s"/>
      <circle class="fin-node" cx="790" cy="370" r="8" style="animation-delay:.75s,3.5s"/><circle class="fin-node-core" cx="790" cy="370" r="2.6" style="animation-delay:.75s"/>
      <circle class="fin-node" cx="830" cy="410" r="8" style="animation-delay:1.2s,4s"/><circle class="fin-node-core" cx="830" cy="410" r="2.6" style="animation-delay:1.2s"/>
      <circle class="fin-node" cx="880" cy="340" r="8" style="animation-delay:1.5s,4.5s"/><circle class="fin-node-core" cx="880" cy="340" r="2.6" style="animation-delay:1.5s"/>
      <circle class="fin-node" cx="920" cy="300" r="8" style="animation-delay:1.8s,5s"/><circle class="fin-node-core" cx="920" cy="300" r="2.6" style="animation-delay:1.8s"/>
      <circle class="fin-node" cx="960" cy="320" r="8" style="animation-delay:2.1s,5.5s"/><circle class="fin-node-core" cx="960" cy="320" r="2.6" style="animation-delay:2.1s"/>
      <circle class="fin-node" cx="1010" cy="230" r="8" style="animation-delay:2.35s,6s"/><circle class="fin-node-core" cx="1010" cy="230" r="2.6" style="animation-delay:2.35s"/>
      <circle class="fin-node" cx="1060" cy="150" r="8" style="animation-delay:2.6s,6.5s"/><circle class="fin-node-core" cx="1060" cy="150" r="2.6" style="animation-delay:2.6s"/>
    </g>
    <path class="fin-check" d="M1050,148 L1065,163 L1093,117" />
  </svg>
  <div class="wrap">
    <div class="subpage-bar"><a href="/" class="subpage-crumb">Home</a><span class="subpage-sep">/</span><a href="/#explore-more" class="subpage-crumb">Initiative</a><span class="subpage-sep">/</span><span class="subpage-crumb is-current">Financial Enablement</span></div>
    <div class="hero-badge"><span class="dot"></span>System 03 · Financial Enablement — Live</div>
    <h1>Get <span class="fe-accent">boosted</span> access to credit, built from a phone a family already has</h1>
    <p class="lead">An explainable score built from eight everyday signals, with savings discipline weighed as heavily as mobile money and airtime, designed to widen fair access to credit rather than push instant debt onto a shock. Try the model below, or query the engine directly, every number comes with a full breakdown of what moved it.</p>
    <div class="hero-ctas">
      <a href="#demo" class="btn btn-solid">Try the Live Demo</a>
      <a href="#how" class="btn btn-line">See How It Works</a>
      <a href="#docs" class="btn btn-line">API Documentation →</a>
    </div>
    <div class="hero-stats">
      <div class="hstat"><b>24</b><span>Point Scale</span></div>
      <div class="hstat"><b>8</b><span>Scored Signals</span></div>
      <div class="hstat"><b>&lt;400ms</b><span>Median Response</span></div>
      <div class="hstat"><b>0</b><span>Points Lost To Child Data</span></div>
    </div>
    <div class="sdg-strip">
      <span class="sdg-chip"><span class="sdg-num" style="background:#E5243B">1</span>No Poverty</span>
      <span class="sdg-chip"><span class="sdg-num" style="background:#A21942">8</span>Decent Work</span>
      <span class="sdg-chip"><span class="sdg-num" style="background:#FD6925">9</span>Innovation</span>
      <span class="sdg-chip"><span class="sdg-num" style="background:#19486A">17</span>Partnerships</span>
    </div>
  </div>
</section>

<!-- ═══ LIVE FX TICKER (real-time) ═══ -->
<div class="fx-bar">
  <div class="wrap reveal">
    <div class="fx-row" id="fxRow">
      <span class="fx-tag"><span class="fx-dot"></span> Live FX Reference — Cross Border Funder Disbursement</span>
      <span class="fx-pair">USD → KES <b id="fxUsdKes">···</b></span>
      <span class="fx-pair">EUR → KES <b id="fxEurKes">···</b></span>
      <span class="fx-pair">GBP → KES <b id="fxGbpKes">···</b></span>
      <span class="fx-updated" id="fxUpdated">Fetching live rate…</span>
    </div>
  </div>
</div>

<!-- ═══ WHAT DEFINES THIS API ═══ -->
<section class="section">
  <div class="wrap reveal">
    <div class="eyebrow" style="justify-content:center">What Defines It</div>
    <div class="defines-grid">
      <div class="defines-item">
        <svg class="defines-icon" viewBox="0 0 44 44" fill="none"><path d="M6 34h32M13 34V19M22 34V10M31 34V23" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <h5>Explainable</h5>
        <p>Every score returns the factors behind it, in plain language, so every number arrives with a clear reason attached.</p>
      </div>
      <div class="defines-item">
        <svg class="defines-icon" viewBox="0 0 44 44" fill="none"><circle cx="22" cy="15" r="6" stroke="url(#klIconGrad)" stroke-width="1.6"/><path d="M8 36c0-8 6-13 14-13s14 5 14 13" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linecap="round"/></svg>
        <h5>Inclusive By Design</h5>
        <p>Built on eight everyday signals, phone, savings group, utility repayment and farm data among them, the record any household already generates simply by living its daily life. Every household that exists can be scored fairly, and savings behaviour carries the same weight as credit behaviour, keeping the score honest about how much debt a household can actually carry.</p>
      </div>
      <div class="defines-item">
        <svg class="defines-icon" viewBox="0 0 44 44" fill="none"><path d="M22 6l14 5v9c0 9-6 15-14 18-8-3-14-9-14-18v-9l14-5z" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linejoin="round"/><path d="M16 22l4 4 8-8" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <h5>Consent Secured</h5>
        <p>Every score requires explicit opt in, and a human reviewer sits above every automated lending decision.</p>
      </div>
    </div>
  </div>
</section>

<!-- ═══ HOW IT WORKS (3-step) ═══ -->
<section class="section" id="how" style="padding-top:0">
  <div class="wrap reveal">
    <div class="eyebrow" style="justify-content:center">How It Works</div>
    <h2 style="font-size:clamp(26px,3.2vw,40px);max-width:640px;margin:0 auto 8px;text-align:center">From a phone in someone's pocket to a decision a lender can stand behind</h2>
    <p class="slead" style="text-align:center;max-width:560px;margin:0 auto">Three steps, start to finish, using only the paperwork a household already has and returning a fully explained decision at the other end.</p>
    <div class="sd-path-row" style="grid-template-columns:repeat(3,1fr);margin-top:36px">
      <div class="sd-path-step">
        <div class="sd-pn">01</div>
        <h5>Connect the signals</h5>
        <p>The household opts in. Mobile money, airtime, group savings, utility repayment and farm records link up, only what they agree to share.</p>
      </div>
      <div class="sd-path-step">
        <div class="sd-pn">02</div>
        <h5>The score explains itself</h5>
        <p>Eight factors combine into a 24-point score in under 400ms, with every factor returned individually alongside the total.</p>
      </div>
      <div class="sd-path-step">
        <div class="sd-pn">03</div>
        <h5>A lender acts on it</h5>
        <p>A SACCO, bank, MFI or insurer pulls the score and its breakdown straight into their own underwriting. The model recommends, and a human always makes the final approval, keeping a household's exposure matched precisely to what its own signals can support.</p>
      </div>
    </div>
  </div>
</section>

<!-- ═══ FEATURE GRID ═══ -->
<section class="section" style="padding-top:0">
  <div class="wrap reveal">
    <div class="eyebrow">The API, Feature by Feature</div>
    <h2 style="font-size:clamp(26px,3.2vw,40px);max-width:640px;margin-bottom:40px">Everything a lender needs to underwrite a household the formal system has overlooked</h2>
    <div class="feat-grid">
      <div class="feat-card">
        <div class="feat-img"><img src="https://kilimora.africa/wp-content/uploads/2026/07/Children-Trad-Hut-scaled.jpg" alt="Alternative data engine reaching an unbanked household"></div>
        <div class="feat-body">
          <div class="feat-tag">Data Engine</div>
          <h4>Alternative Data, Verified</h4>
          <p>Mobile money, airtime, cooperative savings, utility repayment, production history and market transactions, cross checked against each other before they ever reach the model.</p>
        </div>
      </div>
      <div class="feat-card">
        <div class="feat-img"><img src="https://kilimora.africa/wp-content/uploads/2026/07/Children-Tech-Build-scaled.jpg" alt="Explainability layer showing score breakdown"></div>
        <div class="feat-body">
          <div class="feat-tag">Explainability</div>
          <h4>Factor Level Breakdown</h4>
          <p>Every response includes all eight factor scores individually alongside the composite, keeping the full calculation visible on both ends.</p>
        </div>
      </div>
      <div class="feat-card">
        <div class="feat-img"><img src="https://kilimora.africa/wp-content/uploads/2026/07/Children-Pause-scaled.jpg" alt="Bias and fraud monitoring for fair lending"></div>
        <div class="feat-body">
          <div class="feat-tag">Fairness Audit</div>
          <h4>Bias &amp; Fraud Monitoring</h4>
          <p>Retrained quarterly against real repayment outcomes, with dedicated audits for women led and youth led households.</p>
        </div>
      </div>
      <div class="feat-card">
        <div class="feat-img"><img src="https://kilimora.africa/wp-content/uploads/2026/07/Happy-Children-Suitcase-scaled.jpg" alt="A household benefiting from fair access to credit"></div>
        <div class="feat-body">
          <div class="feat-tag">Integration</div>
          <h4>Webhooks &amp; Batch Scoring</h4>
          <p>Score on demand, subscribe to score changes, or submit a portfolio for batch underwriting overnight.</p>
        </div>
      </div>
    </div>
    <div class="pill-strip">
      <span class="pill">REST &amp; Webhook API</span>
      <span class="pill">Sandbox Environment</span>
      <span class="pill">99.9% Uptime SLA</span>
      <span class="pill">Human Reviewed Decisions</span>
      <span class="pill">Opt In Consent Layer</span>
      <span class="pill">Quarterly Bias Audit</span>
    </div>
  </div>
</section>

<!-- ═══ TRUST & DATA PROTECTION ═══ -->
<section class="section" style="padding-top:0">
  <div class="wrap reveal">
    <div class="eyebrow" style="justify-content:center">In This System We Trust</div>
    <h2 style="font-size:clamp(26px,3.2vw,40px);max-width:640px;margin:0 auto 40px;text-align:center">Your data moves only where you can see it going</h2>
    <div class="sd-dom-grid">
      <div class="sd-dom-card">
        <svg class="sd-dom-icon" viewBox="0 0 44 44" fill="none"><rect x="10" y="19" width="24" height="17" rx="3" stroke="url(#klIconGrad)" stroke-width="1.6"/><path d="M15 19v-5a7 7 0 0114 0v5" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linecap="round"/></svg>
        <h5>Bank Grade Encryption</h5>
        <p>Every signal is encrypted in transit and at rest, the same standard formal lenders already trust with their own core systems.</p>
      </div>
      <div class="sd-dom-card">
        <svg class="sd-dom-icon" viewBox="0 0 44 44" fill="none"><path d="M22 6l14 5v9c0 9-6 15-14 18-8-3-14-9-14-18v-9l14-5z" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linejoin="round"/><path d="M16 22l4 4 8-8" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <h5>Consent Based Linking</h5>
        <p>Every connection requires an explicit opt in, and a household can see exactly which signals feed their score at any time.</p>
      </div>
      <div class="sd-dom-card">
        <svg class="sd-dom-icon" viewBox="0 0 44 44" fill="none"><circle cx="22" cy="22" r="15" stroke="url(#klIconGrad)" stroke-width="1.6"/><path d="M14 22h16M22 14v16" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linecap="round"/><path d="M14 30l16-16" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linecap="round"/></svg>
        <h5>We Never Sell Data</h5>
        <p>Signals are scored, kept strictly to that purpose, generating and improving a household's own score alone.</p>
      </div>
      <div class="sd-dom-card">
        <svg class="sd-dom-icon" viewBox="0 0 44 44" fill="none"><path d="M10 22a12 12 0 1024 0 12 12 0 00-24 0z" stroke="url(#klIconGrad)" stroke-width="1.6"/><path d="M22 16v6l5 3" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <h5>Opt Out Anytime</h5>
        <p>A household can disconnect any signal, pause scoring, or leave the system entirely, with a single request and no penalty.</p>
      </div>
    </div>
  </div>
</section>

<!-- ═══ LIVE DEMO ═══ -->
<section class="section" id="demo" style="padding-top:0">
  <div class="wrap reveal">
    <div class="eyebrow">Try It</div>
    <h2 style="font-size:clamp(26px,3.2vw,40px);max-width:640px">Move the sliders, watch the score respond</h2>
    <p style="font-family:'EB Garamond',serif;font-style:italic;color:rgba(255,255,255,.55);max-width:600px;margin-top:8px">A simplified, illustrative version of the scoring engine. The live API scores directly from verified data, this just lets you feel how all eight factors add up.</p>

    <div class="demo-banner">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M12 3l9 16H3l9-16z" stroke="var(--gold-bright)" stroke-width="1.6" stroke-linejoin="round"/><path d="M12 10v4M12 17h.01" stroke="var(--gold-bright)" stroke-width="1.8" stroke-linecap="round"/></svg>
      <div>
        <div class="demo-banner-label">Demo Mode — Not Production Validated</div>
        <p>The sliders and tier thresholds (Emerging / Established / Compounding) are simplified, illustrative logic, not calibrated against real repayment outcomes. The production model is trained and validated separately, on verified household data.</p>
      </div>
    </div>

    <div class="demo-panel">
      <div class="demo-grid">
        <div>
          <div class="demo-factor">
            <div class="demo-factor-head"><span>01 · Production &amp; Income Stability</span><b id="v0">2</b></div>
            <input type="range" min="0" max="3" value="2" step="1" oninput="updateScore()" id="f0">
          </div>
          <div class="demo-factor">
            <div class="demo-factor-head"><span>02 · Mobile Money &amp; Airtime Behavior</span><b id="v1">2</b></div>
            <input type="range" min="0" max="3" value="2" step="1" oninput="updateScore()" id="f1">
          </div>
          <div class="demo-factor">
            <div class="demo-factor-head"><span>03 · Cooperative &amp; Group Savings</span><b id="v2">2</b></div>
            <input type="range" min="0" max="3" value="2" step="1" oninput="updateScore()" id="f2">
          </div>
          <div class="demo-factor">
            <div class="demo-factor-head"><span>04 · Utility &amp; Asset Repayment</span><b id="v3">2</b></div>
            <input type="range" min="0" max="3" value="2" step="1" oninput="updateScore()" id="f3">
          </div>
          <div class="demo-factor">
            <div class="demo-factor-head"><span>05 · Child Education Continuity</span><b id="v4">2</b></div>
            <input type="range" min="0" max="3" value="2" step="1" oninput="updateScore()" id="f4">
          </div>
          <div class="demo-factor">
            <div class="demo-factor-head"><span>06 · Child Health &amp; Nutrition</span><b id="v5">2</b></div>
            <input type="range" min="0" max="3" value="2" step="1" oninput="updateScore()" id="f5">
          </div>
          <div class="demo-factor">
            <div class="demo-factor-head"><span>07 · Household Climate Resilience</span><b id="v6">2</b></div>
            <input type="range" min="0" max="3" value="2" step="1" oninput="updateScore()" id="f6">
          </div>
          <div class="demo-factor" style="margin-bottom:0">
            <div class="demo-factor-head"><span>08 · Protective Engagement</span><b id="v7">2</b></div>
            <input type="range" min="0" max="3" value="2" step="1" oninput="updateScore()" id="f7">
          </div>
        </div>

        <div>
          <div class="demo-total-wrap">
            <div class="demo-total-label">Composite Score</div>
            <div class="demo-total-num"><span id="scoreTotal">16</span><span>/24</span></div>
            <div class="demo-tier t-established" id="scoreTier">Established</div>
            <p class="demo-note" id="scoreNote">A household this consistent typically qualifies for a standard rate microloan and weather index insurance.</p>
          </div>
        </div>
      </div>
      <p class="demo-disclaimer">Illustrative only. Factor weighting shown here is simplified for demonstration; the production model scores against verified data directly, and child linked factors never subtract from the total.</p>
    </div>
  </div>
</section>

<!-- ═══ VOICES FROM THE FIELD ═══ -->
<section class="section" style="padding-top:0">
  <div class="wrap reveal">
    <div class="eyebrow" style="justify-content:center">Voices From The Field</div>
    <h2 style="font-size:clamp(26px,3.2vw,40px);max-width:640px;margin:0 auto 40px;text-align:center">Good for the household, good for the lender behind it</h2>
    <div class="voice-grid">
      <div class="voice-card">
        <p class="voice-quote">"I never had a bank statement to show anyone. Turns out my phone had been keeping the record the whole time. First loan I've ever qualified for."</p>
        <div class="voice-attr">
          <div class="voice-avatar">JW</div>
          <div><div class="voice-name">Joyce W.</div><div class="voice-role">Smallholder, Kajiado County</div></div>
        </div>
      </div>
      <div class="voice-card">
        <p class="voice-quote">"We used to reject applications we simply could not assess. Now the score explains itself, so our loan officers spend their time on judgment calls, not guesswork."</p>
        <div class="voice-attr">
          <div class="voice-avatar">DM</div>
          <div><div class="voice-name">Daniel M.</div><div class="voice-role">Credit Officer, Partner SACCO</div></div>
        </div>
      </div>
      <div class="voice-card">
        <p class="voice-quote">"Our chama's savings record finally counts for something outside the group. That is the part nobody had built for us before."</p>
        <div class="voice-attr">
          <div class="voice-avatar">GA</div>
          <div><div class="voice-name">Grace A.</div><div class="voice-role">Savings Group Treasurer</div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ SIGNAL ROADMAP ═══ -->
<section class="section" style="padding-top:0">
  <div class="wrap reveal">
    <div class="eyebrow" style="justify-content:center">Diversifying The Signal Set</div>
    <h2 style="font-size:clamp(26px,3.2vw,40px);max-width:640px;margin:0 auto 8px;text-align:center">No single data point decides a household's future</h2>
    <p class="slead" style="text-align:center;max-width:600px;margin:0 auto">Eight signals today, spread across income, savings, protection and resilience so the score stays fair even when any one signal goes quiet for a season. More are already in the pipeline.</p>
    <div class="sd-gap-grid" style="margin-top:30px">
      <div class="sd-gap-col sd-done">
        <h4>Live Today</h4>
        <ul>
          <li>Production &amp; income stability</li>
          <li>Mobile money &amp; airtime behavior</li>
          <li>Cooperative &amp; group savings (chama, VSLA, SACCO)</li>
          <li>Utility &amp; pay as you go asset repayment</li>
          <li>Child education continuity</li>
          <li>Child health &amp; nutrition</li>
          <li>Household climate resilience</li>
          <li>Protective engagement</li>
        </ul>
      </div>
      <div class="sd-gap-col sd-open">
        <h4>Coming Next</h4>
        <ul>
          <li>Satellite verified crop yield, layered on top of self reported production</li>
          <li>Remittance flow patterns, for households supported from outside the county</li>
          <li>Direct cooperative ledger integration, replacing manually reported savings</li>
          <li>Harvest linked insurance claims history, once index insurance uptake scales</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- ═══ API DOCUMENTATION ═══ -->
<section class="section" id="docs" style="padding-top:0">
  <div class="wrap reveal">
    <div class="eyebrow">Documentation</div>
    <h2 style="font-size:clamp(26px,3.2vw,40px);max-width:640px;margin-bottom:30px">One call in, a full breakdown out</h2>
    <div class="doc-panel">
      <div class="code-block">
        <div class="cb-label">Request</div>
        <pre><span class="k">POST</span> /v1/score
<span class="c">Authorization:</span> Bearer sk_live_••••••••
<span class="c">Content Type:</span> application/json

{
  <span class="s">"farmer_id"</span>: <span class="s">"AK KE-88213"</span>,
  <span class="s">"consent_token"</span>: <span class="s">"ctk_9f2..."</span>,
  <span class="s">"data_sources"</span>: [
    <span class="s">"mobile_money"</span>,
    <span class="s">"airtime"</span>,
    <span class="s">"group_savings"</span>,
    <span class="s">"utility_repayment"</span>,
    <span class="s">"production_history"</span>,
    <span class="s">"market_transactions"</span>
  ]
}</pre>
      </div>
      <div class="code-block">
        <div class="cb-label">Response</div>
        <pre>{
  <span class="s">"score"</span>: <span class="k">19</span>,
  <span class="s">"scale"</span>: <span class="k">24</span>,
  <span class="s">"tier"</span>: <span class="s">"established"</span>,
  <span class="s">"factors"</span>: {
    <span class="s">"production_income"</span>: <span class="k">2</span>,
    <span class="s">"mobile_airtime"</span>: <span class="k">3</span>,
    <span class="s">"group_savings"</span>: <span class="k">2</span>,
    <span class="s">"utility_repayment"</span>: <span class="k">3</span>,
    <span class="s">"child_education"</span>: <span class="k">2</span>,
    <span class="s">"child_health"</span>: <span class="k">3</span>,
    <span class="s">"climate_resilience"</span>: <span class="k">2</span>,
    <span class="s">"protective_engagement"</span>: <span class="k">2</span>
  },
  <span class="s">"child_factors_floor"</span>: <span class="s">"never_negative"</span>,
  <span class="s">"human_review_required"</span>: <span class="k">false</span>
}</pre>
      </div>
    </div>
  </div>
</section>


<?php elseif ($page === 'child-aid-alignment'): ?>

<section class="papi-hero">
  <video autoplay muted loop playsinline preload="metadata" disablePictureInPicture controlsList="nodownload noremoteplayback noplaybackrate" oncontextmenu="return false" draggable="false" class="kl-nodrag" aria-hidden="true">
    <source src="https://kilimora.africa/wp-content/uploads/2026/07/Header-Child-Aid.mp4" type="video/mp4">
  </video>
  <svg class="papi-canvas" viewBox="0 0 1200 520" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
    <path class="ca-line" d="M860,90 C 940,130 990,150 1010,190 C 1035,238 995,255 950,270 C 900,287 905,320 940,345 C 975,370 950,405 900,410 C 845,416 830,375 790,360 C 745,343 720,290 748,250" />
    <g>
      <circle class="ca-node" cx="860" cy="90" r="9" style="animation-delay:.4s,3.1s"/><circle class="ca-node-core" cx="860" cy="90" r="3" style="animation-delay:.4s"/>
      <circle class="ca-node" cx="1010" cy="190" r="9" style="animation-delay:1s,3.6s"/><circle class="ca-node-core" cx="1010" cy="190" r="3" style="animation-delay:1s"/>
      <circle class="ca-node" cx="950" cy="270" r="9" style="animation-delay:1.6s,4.1s"/><circle class="ca-node-core" cx="950" cy="270" r="3" style="animation-delay:1.6s"/>
      <circle class="ca-node" cx="900" cy="410" r="9" style="animation-delay:2.2s,4.6s"/><circle class="ca-node-core" cx="900" cy="410" r="3" style="animation-delay:2.2s"/>
      <circle class="ca-node" cx="748" cy="250" r="9" style="animation-delay:2.8s,5.1s"/><circle class="ca-node-core" cx="748" cy="250" r="3" style="animation-delay:2.8s"/>
    </g>
  </svg>
  <div class="wrap">
    <div class="subpage-bar"><a href="/" class="subpage-crumb">Home</a><span class="subpage-sep">/</span><a href="/#explore-more" class="subpage-crumb">Initiative</a><span class="subpage-sep">/</span><span class="subpage-crumb is-current">Child Aid Alignment</span></div>
    <div class="hero-badge"><span class="dot"></span> Sector Fit · Built For Any Child Focused Partner</div>
    <h1 style="max-width:820px;margin-bottom:14px">One platform, mapped onto the five things every child focused funder already shows up for</h1>
    <p class="slead" style="max-width:700px">AgriKonnekt is built around five priorities: health, nutrition, education, clean water and emergency response, the same five that anchor most child wellbeing mandates. Every mechanism here is held to a verifiable standard, outcomes a funder can check independently, not enrollment numbers taken on trust.</p>

    <div class="sdg-strip" style="margin-top:24px">
      <span class="sdg-chip"><span class="sdg-num" style="background:#E5243B">1</span>No Poverty</span>
      <span class="sdg-chip"><span class="sdg-num" style="background:#DDA63A">2</span>Zero Hunger</span>
      <span class="sdg-chip"><span class="sdg-num" style="background:#4C9F38">3</span>Good Health</span>
      <span class="sdg-chip"><span class="sdg-num" style="background:#C5192D">4</span>Quality Education</span>
      <span class="sdg-chip"><span class="sdg-num" style="background:#26BDE2">6</span>Clean Water</span>
      <span class="sdg-chip"><span class="sdg-num" style="background:#3F7E44">13</span>Climate Action</span>
      <span class="sdg-chip"><span class="sdg-num" style="background:#19486A">17</span>Partnerships</span>
    </div>

    <div class="hero-stats" style="margin-top:34px">
      <div class="hstat"><b>05</b><span>Core Priorities Addressed</span></div>
      <div class="hstat"><b>07</b><span>SDGs Directly Advanced</span></div>
      <div class="hstat"><b>04</b><span>Funding Mechanism Types Fit</span></div>
      <div class="hstat"><b>100%</b><span>Open Source, By Design</span></div>
    </div>
  </div>
</section>

<!-- ═══ FIVE PRIORITIES ═══ -->
<section class="section">
  <div class="wrap reveal">
    <div class="eyebrow">The Five Priorities</div>
    <h2 style="font-family:'EB Garamond',serif;font-size:clamp(26px,3.2vw,40px);max-width:760px;margin-bottom:14px">Built around the same five things every family already worries about</h2>
    <p class="slead" style="max-width:700px;margin-bottom:36px">Whether the reviewer sits inside a UN agency, a bilateral aid ministry, a private foundation, or a national government department, these are the five outcomes almost every child focused mandate is ultimately scored against. AgriKonnekt was engineered around them directly, not retrofitted to claim them afterward.</p>

    <div class="pillar-grid">
      <div class="pillar">
        <div class="pnum">PRIORITY 01</div>
        <h4>Health</h4>
        <p>Steadier income keeps clinic visits affordable and preventable illness rarer, so a child's health is not the first thing a bad season takes away.</p>
        <span class="sdg-chip"><span class="sdg-num" style="background:#4C9F38">3</span>Health</span>
      </div>
      <div class="pillar">
        <div class="pnum">PRIORITY 02</div>
        <h4>Nutrition</h4>
        <p>Fewer losses after harvest mean more food stays on the table through the lean months between planting and the next harvest.</p>
        <span class="sdg-chip"><span class="sdg-num" style="background:#DDA63A">2</span>Nutrition</span>
      </div>
      <div class="pillar">
        <div class="pnum">PRIORITY 03</div>
        <h4>Education</h4>
        <p>Reliable income keeps school fees paid on time and keeps a child in the classroom rather than pulled into emergency farm labour.</p>
        <span class="sdg-chip"><span class="sdg-num" style="background:#C5192D">4</span>Education</span>
      </div>
      <div class="pillar">
        <div class="pnum">PRIORITY 04</div>
        <h4>Clean Water</h4>
        <p>The same sensor network monitoring soil and climate extends naturally to water point and catchment health, a signal families depend on daily.</p>
        <span class="sdg-chip"><span class="sdg-num" style="background:#26BDE2">6</span>Water</span>
      </div>
      <div class="pillar">
        <div class="pnum">PRIORITY 05</div>
        <h4>Emergency Response</h4>
        <p>Early warning on drought, flood and disease outbreak gives responders lead time measured in weeks, before a shock becomes a crisis a child cannot recover from.</p>
        <span class="sdg-chip"><span class="sdg-num" style="background:#3F7E44">13</span>Climate Action</span>
      </div>
    </div>
  </div>
</section>

<!-- ═══ IMPACT AT A GLANCE ═══ -->
<section class="section" style="padding-top:0">
  <div class="wrap reveal">
    <div class="goal-band">
      <div class="goal-num">50,000</div>
      <div class="goal-label">Households Within Reach By 2028</div>
      <p class="goal-sub">Our strategic horizon, not a vanity metric. Every partner integration on this page compounds toward that one number, and every child behind it.</p>
    </div>
  </div>
</section>

<!-- ═══ VOICES ═══ -->
<section class="section" style="padding-top:0">
  <div class="wrap reveal">
    <div class="eyebrow" style="justify-content:center">From The Households And The Partners Alongside Them</div>
    <div class="voice-grid" style="grid-template-columns:repeat(2,1fr);max-width:820px;margin-left:auto;margin-right:auto">
      <div class="voice-card">
        <p class="voice-quote">"The season a hailstorm took our maize, the loan came through in two days, not two months. My daughter did not miss a single term."</p>
        <div class="voice-attr">
          <div class="voice-avatar">FM</div>
          <div><div class="voice-name">Faith M.</div><div class="voice-role">Mother, Programme Household</div></div>
        </div>
      </div>
      <div class="voice-card">
        <p class="voice-quote">"We do not fund pilots anymore, we fund evidence. This is the first platform we've reviewed where the outcome data was already public before we asked for it."</p>
        <div class="voice-attr">
          <div class="voice-avatar">PO</div>
          <div><div class="voice-name">Programme Officer</div><div class="voice-role">Multilateral Innovation Fund</div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ WAYS TO PARTNER ═══ -->
<section class="section" style="padding-top:0">
  <div class="wrap reveal">
    <div class="eyebrow" style="justify-content:center">Ways To Partner</div>
    <h2 style="font-size:clamp(26px,3.2vw,40px);max-width:640px;margin:0 auto 8px;text-align:center">Three ways in, whatever your mandate allows</h2>
    <p class="slead" style="text-align:center;max-width:560px;margin:0 auto">No single mechanism fits every institution. Pick the door that matches how your organisation already moves capital.</p>
    <div class="sd-path-row" style="grid-template-columns:repeat(3,1fr);margin-top:36px">
      <div class="sd-path-step">
        <div class="sd-pn">FUND</div>
        <h5>Milestone Linked Capital</h5>
        <p>Grant or catalytic capital tied to milestones already tracked in public, verifiable data, no separate reporting layer required on your side.</p>
      </div>
      <div class="sd-path-step">
        <div class="sd-pn">INTEGRATE</div>
        <h5>Technical Alignment</h5>
        <p>Pull our open data feeds and score explainability directly into your own MEL systems or results based financing dashboards.</p>
      </div>
      <div class="sd-path-step">
        <div class="sd-pn">REFER</div>
        <h5>Sector Referral</h5>
        <p>Point a peer institution, a community of practice, or a co funder our way. Most of the funders on this page arrived through exactly that.</p>
      </div>
    </div>
  </div>
</section>

<!-- ═══ HOW THIS KIND OF FUNDING WORKS ═══ -->
<section class="section" style="background:var(--void-2);padding-top:80px;padding-bottom:80px">
  <div class="wrap reveal">
    <div class="eyebrow">How This Kind Of Funding Actually Works</div>
    <h2 style="font-family:'EB Garamond',serif;font-size:clamp(26px,3.2vw,40px);color:#fff;margin-bottom:10px;max-width:700px">Built to satisfy the process, not retrofitted to survive it</h2>
    <p style="color:rgba(255,255,255,.55);max-width:700px;margin-bottom:8px">Humanitarian venture funding, results based financing, and catalytic philanthropy alike do not simply grant capital. Each runs a structured process of its own, and this platform has been engineered to satisfy that process at every stage, whichever type of partner is reviewing it.</p>
    <div class="exec-list">
      <div class="exec-item"><b>Milestone linked capital</b><span>Early, often unrestricted funding tied to milestone based technical assistance rather than a single disbursement, the pattern used by innovation funds, development innovation grants and venture philanthropy alike.</span></div>
      <div class="exec-item"><b>Open source by requirement</b><span>Every layer, software, hardware and content, ships OSI principles designed, or under a Creative Commons licence, from day one, satisfying the public good requirement most institutional funders now expect.</span></div>
      <div class="exec-item"><b>Portfolio, not a single bet</b><span>Funders treat each grantee as one node in a wider portfolio of frontier solutions for children, which is why replicability across contexts matters as much as performance in one.</span></div>
      <div class="exec-item"><b>Public, verifiable evidence</b><span>Real time, publicly exposed data replaces the self reported metrics that most seed stage ventures rely on, the same standard results based financing and multilateral audit teams require.</span></div>
    </div>
  </div>
</section>

<!-- ═══ WHO THIS FITS ═══ -->
<section class="section" style="background:var(--void-2);padding-top:80px;padding-bottom:80px">
  <div class="wrap reveal">
    <div class="eyebrow">Who This Fits</div>
    <h2 style="font-family:'EB Garamond',serif;font-size:clamp(26px,3.2vw,40px);color:#fff;margin-bottom:10px;max-width:700px">Written for the whole sector, not one funder in it</h2>
    <p style="font-family:'EB Garamond',serif;font-style:italic;color:rgba(255,255,255,.55);max-width:660px;font-size:19px">No single mechanism finances a child's whole path out of vulnerability. The categories below are illustrative of the kind of partner this platform is built to satisfy, drawn from across the sector rather than naming any one funder as the intended audience.</p>
    <div class="sd-eco-grid">
      <div class="sd-eco-card">
        <div class="sd-eco-kicker">Multilateral Innovation Funds</div>
        <h5>UN &amp; Multilateral Agency Vehicles</h5>
        <p>Structured innovation funds inside UN agencies and multilateral bodies, of which the UNICEF Innovation Fund is one well known example, that back early stage, open source technology for children at scale.</p>
      </div>
      <div class="sd-eco-card">
        <div class="sd-eco-kicker">Bilateral Development Agencies</div>
        <h5>Government Aid &amp; Innovation Windows</h5>
        <p>Bilateral mechanisms such as USAID's Development Innovation Ventures, the UK's FCDO, and Germany's GIZ, each running competitive, evidence first calls for frontier development technology.</p>
      </div>
      <div class="sd-eco-card">
        <div class="sd-eco-kicker">Catalytic &amp; Venture Philanthropy</div>
        <h5>Risk Tolerant Private Capital</h5>
        <p>Venture philanthropy and catalytic funders willing to take on the early risk that traditional grant makers cannot, in exchange for measurable, scalable social return.</p>
      </div>
      <div class="sd-eco-card">
        <div class="sd-eco-kicker">Corporate &amp; Private Foundations</div>
        <h5>Multi Year Foundation Funding</h5>
        <p>Corporate and family foundations running multi year, thematic funding rounds for child health, education and resilience, typically alongside a peer learning community of practice.</p>
      </div>
      <div class="sd-eco-card">
        <div class="sd-eco-kicker">Results Based Financing</div>
        <h5>Outcome &amp; Impact Linked Capital</h5>
        <p>Financing structures that disburse against verified, publicly auditable outcomes rather than activity reports, the standard this platform's live data was built to meet from day one.</p>
      </div>
      <div class="sd-eco-card">
        <div class="sd-eco-kicker">Sector Networks &amp; Accelerators</div>
        <h5>Shared Evidence &amp; Practice Bodies</h5>
        <p>Cross sector research libraries, accelerators and communities of practice that surface open calls, peer reviewed evidence, and practitioner networks for the whole child development field.</p>
      </div>
    </div>

    <div class="eyebrow" style="margin-top:56px">Mechanisms Available</div>
    <h3 style="font-family:'EB Garamond',serif;font-size:28px;color:#fff;max-width:640px">The actual levers a partner can pull today, on a child's behalf</h3>
    <div class="sd-mech-strip">
      <span class="sd-mech-pill">Milestone Based Innovation Grants</span>
      <span class="sd-mech-pill">Development Innovation Ventures</span>
      <span class="sd-mech-pill">Catalytic &amp; Venture Philanthropy</span>
      <span class="sd-mech-pill">Corporate Foundation Multi Year Funding</span>
      <span class="sd-mech-pill">Results Based Financing</span>
      <span class="sd-mech-pill">Open Source Public Goods Requirements</span>
      <span class="sd-mech-pill">Community of Practice Peer Learning</span>
      <span class="sd-mech-pill">Safeguarding &amp; Data Governance Accreditation</span>
    </div>

    <div class="pull">A funder's mandate rarely changes from one institution to the next, health, nutrition, education, water, protection from shocks. What changes is the paperwork. This platform was built once, against the underlying mandate, so it fits the paperwork of whichever partner is reading it.</div>
  </div>
</section>

<!-- ═══ CLOSING CTA ═══ -->
<section class="section" style="background:var(--void-2);padding-top:80px;padding-bottom:90px;text-align:center">
  <div class="wrap reveal">
    <div class="eyebrow" style="justify-content:center">Where This Fits</div>
    <h2 style="font-family:'EB Garamond',serif;font-size:clamp(28px,3.6vw,38px);color:#fff;max-width:720px;margin:0 auto 14px">Built to align with exactly the kind of call any serious child development funder puts out every year</h2>
    <p style="font-family:'EB Garamond',serif;font-style:italic;color:rgba(255,255,255,.55);max-width:660px;margin:0 auto;font-size:19px">Health, nutrition, education, water, and protection from the next shock, the same five pillars this initiative is built around, and the same priorities a programme officer somewhere is currently reviewing applications against. Somewhere, a child is waiting to find out which way that decision goes.</p>
    <a href="/#contact" class="btn btn-solid" style="margin-top:26px">Talk To Us About Alignment →</a>
  </div>
</section>


<?php elseif ($page === 'sport-development'): ?>

<section class="papi-hero">
  <video autoplay muted loop playsinline preload="metadata" disablePictureInPicture controlsList="nodownload noremoteplayback noplaybackrate" oncontextmenu="return false" draggable="false" class="kl-nodrag" aria-hidden="true">
    <source src="https://kilimora.africa/wp-content/uploads/2026/07/Header-Sports.mp4" type="video/mp4">
  </video>
  <svg class="papi-canvas" viewBox="0 0 1200 520" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
    <path class="sd-pitch" d="M760,70 C 900,65 1060,72 1108,95 C 1128,150 1126,300 1112,395 C 1080,428 920,436 780,432 C 742,418 738,390 736,340 C 734,260 738,150 760,70 Z" />
    <path class="sd-pitch" d="M924,72 C 924,180 924,320 924,430" style="animation-delay:.15s"/>
    <circle class="sd-pitch" cx="924" cy="251" r="58" style="animation-delay:.3s"/>
    <path class="sd-mark" d="M760,180 C 745,220 745,280 760,320" />
    <path class="sd-mark" d="M1108,180 C 1123,220 1123,280 1108,320" style="animation-delay:2.55s"/>
    <circle class="sd-ball" cx="924" cy="251" r="6" opacity="0">
      <animate attributeName="opacity" begin="2.6s" dur=".4s" fill="freeze" to="1"/>
      <animateMotion begin="2.9s" dur="5.5s" repeatCount="indefinite" path="M0,0 C 40,-34 70,10 20,44 C -30,76 -55,20 -10,-10 C 15,-28 5,-6 0,0 Z" />
    </circle>
  </svg>
  <div class="wrap">
    <div class="subpage-bar"><a href="/" class="subpage-crumb">Home</a><span class="subpage-sep">/</span><a href="/#explore-more" class="subpage-crumb">Initiative</a><span class="subpage-sep">/</span><span class="subpage-crumb is-current">Sport &amp; Community</span></div>
    <div class="hero-badge"><span class="dot"></span> Sister Initiative · Sport, Play &amp; Community Development</div>
    <h1 style="max-width:820px;margin-bottom:14px">Somewhere right now, a child is deciding whether an empty afternoon becomes a football pitch, or a doorway to trouble</h1>
    <p class="slead" style="max-width:700px">Somewhere in Nairobi right now, a child is deciding whether an empty afternoon becomes a football pitch or a doorway to trouble, and for most, there is no pitch waiting. That gap, not talent, is what this initiative closes. Sport anchors it as the best evidenced, most scalable starting point, backed by the studies below.</p>

    <div class="sdg-strip" style="margin-top:24px">
      <span class="sdg-chip"><span class="sdg-num" style="background:#4C9F38">3</span>Good Health</span>
      <span class="sdg-chip"><span class="sdg-num" style="background:#C5192D">4</span>Quality Education</span>
      <span class="sdg-chip"><span class="sdg-num" style="background:#FF3A21">5</span>Gender Equality</span>
      <span class="sdg-chip"><span class="sdg-num" style="background:#A21942">8</span>Decent Work</span>
      <span class="sdg-chip"><span class="sdg-num" style="background:#DD1367">10</span>Reduced Inequalities</span>
      <span class="sdg-chip"><span class="sdg-num" style="background:#FD9D24">11</span>Sustainable Communities</span>
      <span class="sdg-chip"><span class="sdg-num" style="background:#00689D">16</span>Peace &amp; Protection</span>
      <span class="sdg-chip"><span class="sdg-num" style="background:#19486A">17</span>Partnerships</span>
    </div>

    <div class="hero-stats" style="margin-top:34px">
      <div class="hstat"><b>46</b><span>Longitudinal Studies, 2025 Meta Analysis</span></div>
      <div class="hstat"><b>4</b><span>Development Domains Reached At Once</span></div>
      <div class="hstat"><b>10</b><span>FIFA Talent Academies Already In Africa</span></div>
      <div class="hstat"><b>1</b><span>Weakest Factor: Athlete Holistic Wellbeing</span></div>
    </div>
  </div>
</section>

<!-- ═══ MARQUEE STRIP ═══ -->
<div class="marquee-strip">
  <div class="marquee-track">
    <span>FOOTBALL</span><span>•</span><span>NETBALL</span><span>•</span><span>ATHLETICS</span><span>•</span><span>VOLLEYBALL</span><span>•</span><span>MUSIC</span><span>•</span><span>ROBOTICS</span><span>•</span><span>THEATRE</span><span>•</span><span>VOCATIONAL TRAINING</span><span>•</span>
    <span>FOOTBALL</span><span>•</span><span>NETBALL</span><span>•</span><span>ATHLETICS</span><span>•</span><span>VOLLEYBALL</span><span>•</span><span>MUSIC</span><span>•</span><span>ROBOTICS</span><span>•</span><span>THEATRE</span><span>•</span><span>VOCATIONAL TRAINING</span><span>•</span>
  </div>
</div>

<!-- ═══ THE CASE, PEER-REVIEWED ═══ -->
<section class="section">
  <div class="wrap reveal">
    <div class="eyebrow">The Case, Peer Reviewed</div>
    <h2 style="font-family:'EB Garamond',serif;font-size:clamp(26px,3.2vw,40px);color:#fff;margin-bottom:10px;max-width:680px">Sport is not a side activity to a child's development. In the research, it is a delivery mechanism for most of it.</h2>
    <div class="sd-ev-grid">
      <div class="sd-ev-card">
        <div class="sd-ev-tag">Psychological &amp; Social</div>
        <p>A 2026 systematic review across five databases, screening studies on 5 to 17 year olds, found structured team sport builds psychological wellbeing through three consistent mechanisms: how children see themselves, whether they feel they belong among peers, and the social support the team structure provides. For a child who has never once been picked first, that belonging is not a small thing.</p>
        <div class="sd-ev-src">Wade et al., Mental Health through Sport conceptual model, 2026</div>
        <span class="sdg-chip sdg-chip--sm"><span class="sdg-num" style="background:#4C9F38">3</span>Good Health</span>
      </div>
      <div class="sd-ev-card">
        <div class="sd-ev-tag">Long Term Health</div>
        <p>A 2025 meta analysis pooled 46 longitudinal studies, screened from over 4,500 candidates, with sample sizes up to 50,000 and follow ups as long as 54 years. It found statistically significant positive effects on physical activity and wellbeing, and reduced mental ill being, carrying from childhood into adulthood, decades after the last whistle blew.</p>
        <div class="sd-ev-src">Int'l Journal of Behavioral Nutrition &amp; Physical Activity, 2025</div>
        <span class="sdg-chip sdg-chip--sm"><span class="sdg-num" style="background:#4C9F38">3</span>Good Health</span>
      </div>
      <div class="sd-ev-card">
        <div class="sd-ev-tag">Whole Child Design</div>
        <p>Reviews of sport based youth development programmes describe a deliberate shift beyond physical fitness alone, toward interventions built to reach physical, cognitive, social and lifestyle domains together, on the premise that being merely "problem free" is not the same as being fully prepared for the life ahead of them.</p>
        <div class="sd-ev-src">Sport based youth development interventions review, PMC</div>
        <span class="sdg-chip sdg-chip--sm"><span class="sdg-num" style="background:#C5192D">4</span>Quality Education</span>
      </div>
      <div class="sd-ev-card">
        <div class="sd-ev-tag">Closing Gaps</div>
        <p>A meta analysis of sport based positive youth development interventions found programmes that explicitly build values and life skills instruction into coaching, not just competition, produced stronger outcomes, including closing motor skill gaps between girls and boys who came in behind, gaps that started as a lack of a field, not a lack of ability.</p>
        <div class="sd-ev-src">Sport based interventions &amp; positive youth development, meta analysis</div>
        <span class="sdg-chip sdg-chip--sm"><span class="sdg-num" style="background:#FF3A21">5</span>Gender Equality</span>
      </div>
    </div>
  </div>
</section>

<!-- ═══ WHERE IT LANDS ═══ -->
<section class="section" style="background:var(--void-2);padding-top:80px;padding-bottom:80px">
  <div class="wrap reveal">
    <div class="eyebrow" style="justify-content:center">Where It Lands</div>
    <h2 style="font-family:'EB Garamond',serif;font-size:34px;color:#fff;text-align:center;max-width:640px;margin:0 auto 8px">One activity, four development domains, at the same time</h2>
    <div class="sd-dom-grid">
      <div class="sd-dom-card">
        <svg class="sd-dom-icon" viewBox="0 0 44 44" fill="none"><path d="M22 6v32M8 22h28" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linecap="round"/><circle cx="22" cy="22" r="15" stroke="url(#klIconGrad)" stroke-width="1.6"/></svg>
        <h5>Physical</h5>
        <p>Activity levels, motor skill development, and lower risk of the unhealthy body composition patterns linked to inactivity.</p>
        <span class="sdg-chip"><span class="sdg-num" style="background:#4C9F38">3</span>Health</span>
      </div>
      <div class="sd-dom-card">
        <svg class="sd-dom-icon" viewBox="0 0 44 44" fill="none"><circle cx="22" cy="15" r="6" stroke="url(#klIconGrad)" stroke-width="1.6"/><path d="M8 36c0-8 6-13 14-13s14 5 14 13" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linecap="round"/></svg>
        <h5>Psychological</h5>
        <p>Self perception, reduced depressive symptoms, and a durable sense of belonging built through team structure.</p>
        <span class="sdg-chip"><span class="sdg-num" style="background:#4C9F38">3</span>Health</span>
      </div>
      <div class="sd-dom-card">
        <svg class="sd-dom-icon" viewBox="0 0 44 44" fill="none"><path d="M6 34h32M13 34V19M22 34V10M31 34V23" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <h5>Cognitive &amp; Academic</h5>
        <p>Discipline, goal setting and focus that carry over into the classroom, when a programme pairs sport with schooling rather than competing with it.</p>
        <span class="sdg-chip"><span class="sdg-num" style="background:#C5192D">4</span>Education</span>
      </div>
      <div class="sd-dom-card">
        <svg class="sd-dom-icon" viewBox="0 0 44 44" fill="none"><path d="M22 6l14 5v9c0 9-6 15-14 18-8-3-14-9-14-18v-9l14-5z" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linejoin="round"/><path d="M16 22l4 4 8-8" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <h5>Protective</h5>
        <p>Structured, supervised time that displaces the idleness associated with risk behaviours, and a coach or mentor positioned to notice when something's wrong before it becomes irreversible.</p>
        <span class="sdg-chip"><span class="sdg-num" style="background:#00689D">16</span>Protection</span>
      </div>
    </div>
  </div>
</section>

<!-- ═══ BEYOND THE PITCH ═══ -->
<section class="section">
  <div class="wrap reveal">
    <div class="eyebrow">Beyond The Pitch</div>
    <h2 style="font-family:'EB Garamond',serif;font-size:clamp(26px,3.2vw,40px);color:#fff;margin-bottom:10px;max-width:700px">Why we call it Sport &amp; Community Development, and mean the second half of that name just as much</h2>
    <p style="font-family:'EB Garamond',serif;font-style:italic;color:rgba(255,255,255,.55);max-width:680px;font-size:19px;margin-bottom:24px">A basketball court in Jericho and a music room in Komarock are solving the same problem: a child with structured hours, a caring adult, and somewhere to belong. We build for both, and everything in between.</p>
    <div class="sd-dom-grid">
      <div class="sd-dom-card">
        <svg class="sd-dom-icon" viewBox="0 0 44 44" fill="none"><circle cx="22" cy="22" r="16" stroke="url(#klIconGrad)" stroke-width="1.6"/><path d="M14 26c2-8 6-12 8-12s6 4 8 12" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linecap="round"/></svg>
        <h5>Local Sports Hubs</h5>
        <p>Basketball, football, netball and athletics infrastructure built with counties and community partners, on the model of Nairobi's Jericho and Komarock courts.</p>
        <span class="sdg-chip"><span class="sdg-num" style="background:#FD9D24">11</span>Community</span>
      </div>
      <div class="sd-dom-card">
        <svg class="sd-dom-icon" viewBox="0 0 44 44" fill="none"><path d="M10 30V14l12-6 12 6v16" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linejoin="round"/><path d="M10 30h24M17 30v-8h10v8" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linejoin="round"/></svg>
        <h5>Arts, Music &amp; Craft</h5>
        <p>Studio and workshop space run on the same coaching plus mentorship model, for the children whose talent is not on a scoreboard.</p>
        <span class="sdg-chip"><span class="sdg-num" style="background:#C5192D">4</span>Education</span>
      </div>
      <div class="sd-dom-card">
        <svg class="sd-dom-icon" viewBox="0 0 44 44" fill="none"><path d="M8 34l8-16 6 10 6-14 8 20" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <h5>Vocational &amp; Digital Skills</h5>
        <p>Platforms like Yakazi already link trained youth to employers. We build toward that same verified, portable record of what a young person can do.</p>
        <span class="sdg-chip"><span class="sdg-num" style="background:#A21942">8</span>Decent Work</span>
      </div>
      <div class="sd-dom-card">
        <svg class="sd-dom-icon" viewBox="0 0 44 44" fill="none"><circle cx="15" cy="16" r="5" stroke="url(#klIconGrad)" stroke-width="1.6"/><circle cx="29" cy="16" r="5" stroke="url(#klIconGrad)" stroke-width="1.6"/><path d="M6 36c0-7 4-11 9-11s9 4 9 11M20 36c0-7 4-11 9-11s9 4 9 11" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linecap="round"/></svg>
        <h5>Mentorship &amp; Local Leadership</h5>
        <p>Coaches, teachers and elder youth from the same community, the adult presence every one of these formats depends on to actually work.</p>
        <span class="sdg-chip"><span class="sdg-num" style="background:#DD1367">10</span>Inclusion</span>
      </div>
    </div>

    <div class="pull" style="background:var(--void-2,#12150E);color:rgba(255,255,255,.82);border-radius:14px;padding:30px 30px 26px;margin-top:44px;font-family:'EB Garamond',serif;font-style:italic;font-size:25px">This is already happening in Nairobi. In Makadara and Komarock, county government and outside partners built FIBA standard courts where there'd been none, then went further, moving the strongest grassroots teams into a paid Street League and covering their entry fees. A generation of kids with real basketball ability finally had somewhere to put it, and a modest income for showing up. It is proof of concept for everything above: infrastructure plus a funded pathway changes what a neighbourhood's afternoons look like.</div>
  </div>
</section>

<!-- ═══ EDUCATION HUB WITH COOPERATIVE PARTNERS ═══ -->
<section class="section" style="background:var(--void-2)">
  <div class="wrap reveal">
    <div class="eyebrow">New · Education &amp; Agribusiness Pathway</div>
    <h2 style="font-family:'EB Garamond',serif;font-size:clamp(26px,3.2vw,40px);color:#fff;margin-bottom:10px;max-width:720px">An education hub built with cooperatives, where sport is part of what keeps a student showing up</h2>
    <p class="slead" style="max-width:700px">Working with cooperative partners including Murang'a Farmers Cooperative Union, AgriKonnekt is building education initiatives that upskill high schoolers and post-secondary students in agribusiness, using sport, nutrition and meals together as the incentive that keeps a young person coming back week after week.</p>
    <div class="sd-dom-grid">
      <div class="sd-dom-card">
        <svg class="sd-dom-icon" viewBox="0 0 44 44" fill="none"><path d="M22 6l14 5v9c0 9-6 15-14 18-8-3-14-9-14-18v-9l14-5z" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linejoin="round"/></svg>
        <h5>Cooperative Partnership</h5>
        <p>Murang'a Farmers Cooperative Union is the planned home of the first hub, with further cooperatives to follow as the model proves out.</p>
      </div>
      <div class="sd-dom-card">
        <svg class="sd-dom-icon" viewBox="0 0 44 44" fill="none"><path d="M8 34l8-16 6 10 6-14 8 20" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <h5>Agribusiness Skills</h5>
        <p>A curriculum for high schoolers and post-secondary students built around the practical, entrepreneurial side of agribusiness, not classroom theory alone.</p>
      </div>
      <div class="sd-dom-card">
        <svg class="sd-dom-icon" viewBox="0 0 44 44" fill="none"><circle cx="22" cy="22" r="16" stroke="url(#klIconGrad)" stroke-width="1.6"/><path d="M14 26c2-8 6-12 8-12s6 4 8 12" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linecap="round"/></svg>
        <h5>Sport, Nutrition &amp; Meals</h5>
        <p>The same incentive structure proven on the courts above, sport paired with nutrition and meals, applied here to keep students enrolled and attending.</p>
      </div>
      <div class="sd-dom-card">
        <svg class="sd-dom-icon" viewBox="0 0 44 44" fill="none"><rect x="10" y="19" width="24" height="17" rx="3" stroke="url(#klIconGrad)" stroke-width="1.6"/><path d="M15 19v-5a7 7 0 0114 0v5" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linecap="round"/></svg>
        <h5>AgriKonnekt As Tech Partner</h5>
        <p>Leading the human capital build behind the hub: the technology and data infrastructure that turns training into a verified, portable record of skill.</p>
      </div>
    </div>
    <div class="pull" style="background:var(--void-2,#12150E);color:rgba(255,255,255,.82);border-radius:14px;padding:30px 30px 26px;margin-top:44px;font-family:'EB Garamond',serif;font-style:italic;font-size:25px">The hub is built to grow talent the whole agri value chain needs, from smallholder farmers and cooperatives through to supply chain players and exporters, developing the entrepreneurs and future corporate leaders who will go on to improve livelihoods across that chain.</div>
  </div>
</section>

<!-- ═══ SYSTEMIC CHANGE / ECOSYSTEM ═══ -->
<section class="section" style="background:var(--void-2);padding-top:80px;padding-bottom:80px">
  <div class="wrap reveal">
    <div class="eyebrow">Systemic Change</div>
    <h2 style="font-family:'EB Garamond',serif;font-size:clamp(26px,3.2vw,40px);color:#fff;margin-bottom:10px;max-width:680px">The organisations already doing this, and where each one sits in the system</h2>
    <p style="font-family:'EB Garamond',serif;font-style:italic;color:rgba(255,255,255,.55);max-width:640px;font-size:19px">No single organisation covers a child's whole journey through sport. Real systemic change means these pieces working as one system instead of six separate ones that each only see a fraction of the child in front of them.</p>
    <div class="sd-eco-grid">
      <div class="sd-eco-card">
        <div class="sd-eco-kicker">Funding Mechanism</div>
        <h5>UEFA Foundation for Children</h5>
        <p>An annual, open call for projects across health, education, access to sport, personal development, minority integration and child protection, assessed by an independent Board of Trustees.</p>
      </div>
      <div class="sd-eco-card">
        <div class="sd-eco-kicker">Displacement &amp; Refuge</div>
        <h5>Olympic Refuge Foundation</h5>
        <p>Runs a Collaborative Action Grant through the Sport for Refugees Coalition, prioritising Latin America and the Horn of Africa, Sahel and Great Lakes regions, funding joint initiatives between member organisations.</p>
      </div>
      <div class="sd-eco-card">
        <div class="sd-eco-kicker">Corporate Foundation</div>
        <h5>adidas Foundation</h5>
        <p>Moving for Change funds INGOs partnering with local Sport for Development organisations across Ethiopia, Kenya, Rwanda, Tanzania and Uganda, with multi year funding and a peer learning Community of Practice.</p>
      </div>
      <div class="sd-eco-card">
        <div class="sd-eco-kicker">Sector Network</div>
        <h5>sportanddev.org / ISCA</h5>
        <p>The field's shared research library and job board, surfacing open calls, peer reviewed evidence and practitioner networks across the whole sector.</p>
      </div>
      <div class="sd-eco-card">
        <div class="sd-eco-kicker">Global Governing Body</div>
        <h5>FIFA Talent Development Scheme</h5>
        <p>Operates 42 Talent Academies worldwide, 10 already in Africa, pairing football training with academic education, aiming for 75 academies globally by 2027.</p>
      </div>
      <div class="sd-eco-card">
        <div class="sd-eco-kicker">Local Government</div>
        <h5>County Government Partnerships</h5>
        <p>Nairobi City County's partnership with Hennessy to build FIBA standard basketball courts, and its Street League subsidy for grassroots teams, shows what county level investment can look like.</p>
      </div>
    </div>

    <div class="eyebrow" style="margin-top:56px">Mechanisms Available</div>
    <h3 style="font-family:'EB Garamond',serif;font-size:28px;color:#fff;max-width:640px">The actual levers an organisation can pull today, on that child's behalf</h3>
    <div class="sd-mech-strip">
      <span class="sd-mech-pill">Collaborative Action Grants</span>
      <span class="sd-mech-pill">Erasmus+ Sport Cooperation Partnerships</span>
      <span class="sd-mech-pill">Corporate Foundation Multi Year Funding</span>
      <span class="sd-mech-pill">County Infrastructure Partnerships</span>
      <span class="sd-mech-pill">FIFA Talent Academy Placement</span>
      <span class="sd-mech-pill">League Subsidy &amp; Subscription Support</span>
      <span class="sd-mech-pill">Community of Practice Peer Learning</span>
      <span class="sd-mech-pill">Safeguarding &amp; Governance Accreditation</span>
    </div>
  </div>
</section>

<!-- ═══ FOOTBALL PATHWAY ═══ -->
<section class="section">
  <div class="wrap reveal">
    <div class="eyebrow">Football, Specifically</div>
    <h2 style="font-family:'EB Garamond',serif;font-size:clamp(26px,3.2vw,40px);color:#fff;margin-bottom:10px;max-width:680px">Football carries a career pathway most other sports do not, and that cuts both ways</h2>
    <p style="font-family:'EB Garamond',serif;font-style:italic;color:rgba(255,255,255,.55);max-width:640px;font-size:19px">Millions of unregistered youth players across Africa may have real ability. Whether that turns into a scholarship or a scam depends entirely on the quality of the system around them, and right now, too often, there is no system at all.</p>
    <div class="sd-path-row">
      <div class="sd-path-step">
        <div class="sd-pn">01</div>
        <h5>Grassroots Play</h5>
        <p>Unstructured or school level play, the stage where talent is present but almost entirely undocumented, and where a scout's word is the only record that exists.</p>
      </div>
      <div class="sd-path-step">
        <div class="sd-pn">02</div>
        <h5>Talent Identification</h5>
        <p>Licensed academy scouting and regional development centres, the stage where illegal agents most often intercept a hopeful teenager with false promises and a plane ticket that never comes.</p>
      </div>
      <div class="sd-path-step">
        <div class="sd-pn">03</div>
        <h5>Academy &amp; Education</h5>
        <p>Academies that pair technical training with schooling, safeguarding and life skills, following models like Right to Dream and FASI, so a bad injury does not end the whole future at once.</p>
      </div>
      <div class="sd-path-step">
        <div class="sd-pn">04</div>
        <h5>Verified Pathway Out</h5>
        <p>Documented placement into semi professional, professional, or scholarship routes, with a paper trail that protects the player, not just the club that signed him.</p>
      </div>
    </div>
  </div>
</section>

<!-- ═══ HONEST ACCOUNTING ═══ -->
<section class="section" style="background:var(--void-2);padding-top:80px;padding-bottom:80px">
  <div class="wrap reveal">
    <div class="eyebrow">Honest Accounting</div>
    <h2 style="font-family:'EB Garamond',serif;font-size:clamp(26px,3.2vw,40px);color:#fff;margin-bottom:16px;max-width:680px">What is already been done, and where it still falls short</h2>
    <div class="sd-gap-grid">
      <div class="sd-gap-col sd-done">
        <h4>Effort So Far</h4>
        <ul>
          <li>County level infrastructure investment, like Nairobi's Hennessy funded basketball courts and Street League subsidy, giving grassroots teams a real venue and a modest income instead of an empty lot.</li>
          <li>FIFA's Talent Development Scheme has stood up 10 academies across Africa, pairing training with formal education so a career in sport does not have to mean dropping out of school.</li>
          <li>A small number of academies, Right to Dream and FASI among them, have proven that pairing football with schooling and safeguarding produces both better players and better protected children.</li>
          <li>International funders, UEFA Foundation for Children, the Olympic Refuge Foundation and the adidas Foundation among them, run recurring, well structured grant calls specifically for this space.</li>
        </ul>
      </div>
      <div class="sd-gap-col sd-open">
        <h4>Gaps Still Open</h4>
        <ul>
          <li>Academic comparison of African football academies found athlete holistic wellbeing consistently scored as the weakest factor, behind technical and long term development focus, across every country studied. Talent is being polished while the child underneath it is quietly overlooked.</li>
          <li>Many grassroots academies still operate with no verified data on who is training there, what they are achieving, or whether they are safe, the same visibility gap AgriKonnekt was built to close in agriculture.</li>
          <li>Unlicensed scouts and agents continue to intercept talented but undocumented players with false promises, a problem a verified, portable player record could substantially reduce.</li>
          <li>Non football sports, netball, athletics, volleyball, remain comparatively under resourced next to football's funding and pathway density, despite carrying the same developmental evidence base and the same children needing it.</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- ═══ FROM THE FIELD ═══ -->
<section class="section">
  <div class="wrap reveal">
    <div class="eyebrow">From The Field</div>
    <h2 style="font-family:'EB Garamond',serif;font-size:34px;color:#fff;margin-bottom:6px;max-width:640px">Faces from the communities every page above was written for</h2>
    <div class="sd-gal-grid">
      <div class="sd-gal-item"><img src="https://kilimora.africa/wp-content/uploads/2026/07/aduratomi-scaled.jpg" alt="Community sport programme participant" draggable="false" class="kl-nodrag"></div>
      <div class="sd-gal-item"><img src="https://kilimora.africa/wp-content/uploads/2026/07/dago-scaled.jpg" alt="Community sport programme participant" draggable="false" class="kl-nodrag"></div>
      <div class="sd-gal-item"><img src="https://kilimora.africa/wp-content/uploads/2026/07/isaac-naph-scaled.jpg" alt="Community sport programme participant" draggable="false" class="kl-nodrag"></div>
      <div class="sd-gal-item"><img src="https://kilimora.africa/wp-content/uploads/2026/07/josh-scaled.jpg" alt="Community sport programme participant" draggable="false" class="kl-nodrag"></div>
      <div class="sd-gal-item"><img src="https://kilimora.africa/wp-content/uploads/2026/07/josh-dago-scaled.jpg" alt="Community sport programme participants" draggable="false" class="kl-nodrag"></div>
      <div class="sd-gal-item"><img src="https://kilimora.africa/wp-content/uploads/2026/07/kenechukwu-scaled.jpg" alt="Community sport programme participant" draggable="false" class="kl-nodrag"></div>
      <div class="sd-gal-item"><img src="https://kilimora.africa/wp-content/uploads/2026/07/mo-liban-scaled.jpg" alt="Community sport programme participant" draggable="false" class="kl-nodrag"></div>
      <div class="sd-gal-item"><img src="https://kilimora.africa/wp-content/uploads/2026/07/muhammad-scaled.jpg" alt="Community sport programme participant" draggable="false" class="kl-nodrag"></div>
      <div class="sd-gal-item"><img src="https://kilimora.africa/wp-content/uploads/2026/07/ntezicimpa-scaled.jpg" alt="Community sport programme participant" draggable="false" class="kl-nodrag"></div>
      <div class="sd-gal-item"><img src="https://kilimora.africa/wp-content/uploads/2026/07/obibini-scaled.jpg" alt="Community sport programme participant" draggable="false" class="kl-nodrag"></div>
      <div class="sd-gal-item"><img src="https://kilimora.africa/wp-content/uploads/2026/07/rowlandzy-scaled.jpg" alt="Community sport programme participant" draggable="false" class="kl-nodrag"></div>
      <div class="sd-gal-item"><img src="https://kilimora.africa/wp-content/uploads/2026/07/shichenyi-scaled.jpg" alt="Community sport programme participant" draggable="false" class="kl-nodrag"></div>
      <div class="sd-gal-item"><img src="https://kilimora.africa/wp-content/uploads/2026/07/tifeclicks-scaled.jpg" alt="Community sport programme participant" draggable="false" class="kl-nodrag"></div>
      <div class="sd-gal-item"><img src="https://kilimora.africa/wp-content/uploads/2026/07/tsion-scaled.jpg" alt="Community sport programme participant" draggable="false" class="kl-nodrag"></div>
      <div class="sd-gal-item"><img src="https://kilimora.africa/wp-content/uploads/2026/07/workman-scaled.jpg" alt="Community sport programme participant" draggable="false" class="kl-nodrag"></div>
    </div>

    <div style="text-align:center;margin-top:64px">
      <div class="eyebrow" style="justify-content:center">Where This Fits</div>
      <h3 style="font-family:'EB Garamond',serif;font-size:34px;color:#fff;max-width:680px;margin:0 auto 14px">Built to align with exactly the kind of call the UEFA Foundation for Children puts out every year</h3>
      <p style="font-family:'EB Garamond',serif;font-style:italic;color:rgba(255,255,255,.55);max-width:640px;margin:0 auto;font-size:19px">Health, education, access to sport, personal development, integration, and protection of children's rights, the same six pillars this initiative is built around, and the same six a Board of Trustees somewhere is currently reviewing applications against. Somewhere, a child is waiting to find out which way that decision goes.</p>
      <a href="/#contact" class="btn btn-solid" style="margin-top:26px">Talk To Us About This Initiative →</a>
    </div>
  </div>
</section>

<?php elseif ($page === 'climate-resilience'): ?>

<section class="papi-hero">
  <video autoplay muted loop playsinline preload="metadata" disablePictureInPicture controlsList="nodownload noremoteplayback noplaybackrate" oncontextmenu="return false" draggable="false" class="kl-nodrag" aria-hidden="true">
    <source src="https://kilimora.africa/wp-content/uploads/2026/07/Header-Climate-and-Health-comss.mp4" type="video/mp4">
  </video>
  <svg class="papi-canvas" viewBox="0 0 1200 520" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
    <path class="ca-line" style="stroke:var(--forest-mid)" d="M120,420 C 220,300 260,180 340,150 C 420,120 470,220 560,210 C 650,200 690,90 800,80 C 900,72 960,160 1060,140" />
    <circle class="ca-node" style="stroke:var(--forest-mid)" cx="340" cy="150" r="7"/><circle class="ca-node-core" style="fill:var(--forest-mid)" cx="340" cy="150" r="3"/>
    <circle class="ca-node" style="stroke:var(--forest-mid);animation-delay:.6s" cx="560" cy="210" r="7"/><circle class="ca-node-core" style="fill:var(--forest-mid);animation-delay:.6s" cx="560" cy="210" r="3"/>
    <circle class="ca-node" style="stroke:var(--forest-mid);animation-delay:1.2s" cx="800" cy="80" r="7"/><circle class="ca-node-core" style="fill:var(--forest-mid);animation-delay:1.2s" cx="800" cy="80" r="3"/>
    <circle class="ca-node" style="stroke:var(--forest-mid);animation-delay:1.8s" cx="1060" cy="140" r="7"/><circle class="ca-node-core" style="fill:var(--forest-mid);animation-delay:1.8s" cx="1060" cy="140" r="3"/>
  </svg>
  <div class="wrap">
    <div class="subpage-bar"><a href="/" class="subpage-crumb">Home</a><span class="subpage-sep">/</span><a href="/#explore-more" class="subpage-crumb">Initiative</a><span class="subpage-sep">/</span><span class="subpage-crumb is-current">Climate &amp; Health Resilience</span></div>
    <div class="hero-badge"><span class="dot"></span> Fourth Pathway · Climate &amp; Health Resilience</div>
    <h1>The same verified household that keeps a child fed can also warn that household before the flood, the heat wave, or the outbreak arrives</h1>
    <p class="slead">About a billion children already live in countries facing high climate risk, some now enduring twice the extreme heat days of the 1960s. Most response technology sits far from the household actually carrying that risk. AgriKonnekt already holds a verified record for every household it serves; this pathway turns that record into a hazard map, an early warning, and a health forecast, so a shock arrives as a warning, not a surprise.</p>

    <div class="sdg-strip">
      <span class="sdg-chip"><span class="sdg-num" style="background:#4C9F38">3</span>Good Health</span>
      <span class="sdg-chip"><span class="sdg-num" style="background:#26BDE2">6</span>Clean Water</span>
      <span class="sdg-chip"><span class="sdg-num" style="background:#FD9D24">11</span>Sustainable Communities</span>
      <span class="sdg-chip"><span class="sdg-num" style="background:#3F7E44">13</span>Climate Action</span>
      <span class="sdg-chip"><span class="sdg-num" style="background:#19486A">17</span>Partnerships</span>
    </div>

    <div class="hero-stats">
      <div class="hstat"><b>1B+</b><span>Children In High Climate Risk Countries</span></div>
      <div class="hstat"><b>1 in 5</b><span>Now Living Through Double The Extreme Heat Days</span></div>
      <div class="hstat"><b>4</b><span>Modules, Built On One Shared Record</span></div>
      <div class="hstat"><b>0</b><span>New Household Registration Needed To Start</span></div>
    </div>
  </div>
</section>

<div class="marquee-strip">
  <div class="marquee-track">
    <span>HEAT</span><span>•</span><span>FLOOD</span><span>•</span><span>DROUGHT</span><span>•</span><span>AIR QUALITY</span><span>•</span><span>MALARIA</span><span>•</span><span>DENGUE</span><span>•</span><span>EARLY WARNING</span><span>•</span><span>SURGE FORECASTING</span><span>•</span>
    <span>HEAT</span><span>•</span><span>FLOOD</span><span>•</span><span>DROUGHT</span><span>•</span><span>AIR QUALITY</span><span>•</span><span>MALARIA</span><span>•</span><span>DENGUE</span><span>•</span><span>EARLY WARNING</span><span>•</span><span>SURGE FORECASTING</span><span>•</span>
  </div>
</div>

<!-- ═══ THE CASE ═══ -->
<section class="section">
  <div class="wrap reveal">
    <div class="eyebrow">Why This Pathway Exists</div>
    <h2 class="stitle" style="font-size:clamp(26px,3.2vw,40px);max-width:700px">Children carry the heaviest cost of a changing climate, with the least say in the response</h2>
    <div class="sd-ev-grid">
      <div class="sd-ev-card">
        <div class="sd-ev-tag">Exposure</div>
        <p>Roughly a billion children live in countries already facing high climate and environmental risk, and that number is set to grow, not shrink, over the life of this platform.</p>
        <div class="sd-ev-src">Global child climate risk analysis</div>
        <span class="sdg-chip sdg-chip--sm"><span class="sdg-num" style="background:#3F7E44">13</span>Climate Action</span>
      </div>
      <div class="sd-ev-card">
        <div class="sd-ev-tag">Heat</div>
        <p>About one in five children worldwide now live through at least twice as many days above 35 degrees Celsius as children did in the 1960s, a shift most local health systems were never built to plan around.</p>
        <div class="sd-ev-src">Extreme heat exposure trend analysis</div>
        <span class="sdg-chip sdg-chip--sm"><span class="sdg-num" style="background:#4C9F38">3</span>Good Health</span>
      </div>
      <div class="sd-ev-card">
        <div class="sd-ev-tag">The Gap</div>
        <p>Climate technology investment is growing quickly, but most of it is not built around a child's specific risk, a health outcome, or the realities of the household actually facing the hazard first hand.</p>
        <div class="sd-ev-src">Climate innovation landscape review</div>
        <span class="sdg-chip sdg-chip--sm"><span class="sdg-num" style="background:#FD9D24">11</span>Community</span>
      </div>
      <div class="sd-ev-card">
        <div class="sd-ev-tag">The Opening</div>
        <p>A verified household record, already collected for finance and traceability, is exactly the missing link between a hazard map drawn at national level and a warning that actually reaches one home in time.</p>
        <div class="sd-ev-src">AgriKonnekt household data layer</div>
        <span class="sdg-chip sdg-chip--sm"><span class="sdg-num" style="background:#26BDE2">6</span>Clean Water</span>
      </div>
    </div>
    <div class="h-gallery-wrap">
      <div class="h-gallery kl-nodrag">
        <div class="h-slide"><img src="https://kilimora.africa/wp-content/uploads/2026/07/Children-Trad-Hut-scaled.jpg" alt="A rural household exposed to climate risk" draggable="false"><div class="h-slide-cap">The household a hazard map has to reach in time</div></div>
        <div class="h-slide"><img src="https://kilimora.africa/wp-content/uploads/2026/07/Children-Pause-scaled.jpg" alt="Children resting during extreme heat" draggable="false"><div class="h-slide-cap">One in five now live through double the extreme heat days</div></div>
        <div class="h-slide"><img src="https://kilimora.africa/wp-content/uploads/2026/07/Children-Tech-Build-scaled.jpg" alt="Community technology reaching a household" draggable="false"><div class="h-slide-cap">The same record, turned into an early warning</div></div>
        <div class="h-slide"><img src="https://kilimora.africa/wp-content/uploads/2026/07/Happy-Children-Suitcase-scaled.jpg" alt="A family prepared ahead of a climate shock" draggable="false"><div class="h-slide-cap">A warning before the shock, not a surprise after it</div></div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ WHERE IT LANDS: THE FOUR MODULES ═══ -->
<section class="section" style="background:var(--void-2);padding-top:80px;padding-bottom:80px">
  <div class="wrap reveal">
    <div class="eyebrow" style="justify-content:center">Where It Lands</div>
    <h2 style="font-family:'EB Garamond',serif;font-size:34px;color:#fff;text-align:center;max-width:640px;margin:0 auto 8px">One household record, four modules, turned on as needed</h2>
    <div class="sd-dom-grid">
      <div class="sd-dom-card">
        <svg class="sd-dom-icon" viewBox="0 0 44 44" fill="none"><path d="M8 12l10-4 10 4 8-3v26l-8 3-10-4-10 4-8-3V9l8 3Z" stroke="var(--forest-mid)" stroke-width="1.6" stroke-linejoin="round"/><path d="M18 8v26M28 12v26" stroke="var(--forest-mid)" stroke-width="1.6"/></svg>
        <h5>Risk Map</h5>
        <p>Lays flood, heat, and air quality hazards over the schools, clinics, and homes already in the household record, and scores each one for exposure.</p>
        <span class="sdg-chip"><span class="sdg-num" style="background:#3F7E44">13</span>Climate</span>
      </div>
      <div class="sd-dom-card">
        <svg class="sd-dom-icon" viewBox="0 0 44 44" fill="none"><path d="M13 22a9 9 0 0 1 18 0c0 6 2.2 8 3 9.5H10c.8-1.5 3-3.5 3-9.5Z" stroke="var(--forest-mid)" stroke-width="1.6"/><path d="M17 32a5 5 0 0 0 10 0" stroke="var(--forest-mid)" stroke-width="1.6" stroke-linecap="round"/></svg>
        <h5>Early Alert</h5>
        <p>Sends plain language warnings for heat, flood, storm, and outbreak risk to a household's phone or nearest community post, in the language it already uses.</p>
        <span class="sdg-chip"><span class="sdg-num" style="background:#4C9F38">3</span>Health</span>
      </div>
      <div class="sd-dom-card">
        <svg class="sd-dom-icon" viewBox="0 0 44 44" fill="none"><path d="M8 34V22M18 34V14M28 34V20M38 34V10" stroke="var(--forest-mid)" stroke-width="1.6" stroke-linecap="round"/></svg>
        <h5>Forecast</h5>
        <p>Reads climate and health signals together to flag a likely rise in malaria, dengue, or heat illness weeks ahead, so a clinic can staff and stock for it.</p>
        <span class="sdg-chip"><span class="sdg-num" style="background:#4C9F38">3</span>Health</span>
      </div>
      <div class="sd-dom-card">
        <svg class="sd-dom-icon" viewBox="0 0 44 44" fill="none"><path d="M8 12h28v18H18l-6 5v-5H8V12Z" stroke="var(--forest-mid)" stroke-width="1.6" stroke-linejoin="round"/><path d="M15 20h14M15 25h9" stroke="var(--forest-mid)" stroke-width="1.6" stroke-linecap="round"/></svg>
        <h5>Care Assist</h5>
        <p>Puts a simple, offline capable assistant in a community health worker's hands, turning climate and health data into a clear next step at the point of care.</p>
        <span class="sdg-chip"><span class="sdg-num" style="background:#26BDE2">6</span>Water &amp; Care</span>
      </div>
    </div>
  </div>
</section>

<!-- ═══ BUILT ON WHAT ALREADY EXISTS ═══ -->
<section class="section">
  <div class="wrap reveal">
    <div class="eyebrow">Built On What Already Exists</div>
    <h2 style="font-family:'EB Garamond',serif;font-size:clamp(26px,3.2vw,40px);color:#fff;margin-bottom:10px;max-width:700px">No new registration, no new device, no new agency required to start</h2>
    <p style="font-family:'EB Garamond',serif;font-style:italic;color:rgba(255,255,255,.55);max-width:680px;font-size:19px;margin-bottom:24px">This is not a separate platform bolted on next to AgriKonnekt. It reads from the same verified household layer that already powers financial enablement and traceability, and it is built open source, so any ministry, hospital, school system, or community partner can run it under its own control.</p>

    <div class="exec-list">
      <div class="exec-item"><b>One Shared Record</b><span>Hazard data, facility locations, and health signals are layered onto the household and farm data AgriKonnekt already verifies, not collected again from scratch.</span></div>
      <div class="exec-item"><b>Offline First</b><span>Every module keeps working with no connection and syncs when one returns, built for the same low bandwidth conditions AgriKonnekt already runs in.</span></div>
      <div class="exec-item"><b>Open Source, Any Partner</b><span>Released under the same open licence as the rest of the platform, so a government, hospital, or NGO can adopt one module, or all four, at no licence cost.</span></div>
      <div class="exec-item"><b>Interoperable</b><span>Built to connect with systems already in place, including DHIS2 and national facility registries, rather than asking an institution to replace what it already runs.</span></div>
    </div>

    <div class="pull">The strongest early warning system is the one that already knows who it is warning. AgriKonnekt's household record means this pathway starts from a running start, not an empty database.</div>
  </div>
</section>

<!-- ═══ HONEST ACCOUNTING ═══ -->
<section class="section" style="background:var(--void-2);padding-top:80px;padding-bottom:80px">
  <div class="wrap reveal">
    <div class="eyebrow">Honest Accounting</div>
    <h2 style="font-family:'EB Garamond',serif;font-size:clamp(26px,3.2vw,40px);color:#fff;margin-bottom:16px;max-width:680px">What is already been done, and where it still falls short</h2>
    <div class="sd-gap-grid">
      <div class="sd-gap-col sd-done">
        <h4>Effort So Far</h4>
        <ul>
          <li>National meteorological services across the region already publish flood, drought, and storm forecasts, and mobile network coverage now reaches most of the households this pathway serves.</li>
          <li>DHIS2 and similar health information systems are already in use across many ministries of health, giving this pathway a system to plug into rather than replace.</li>
          <li>Community health worker networks already exist at scale, trained and trusted, and are the natural front line for a point of care tool built for climate linked illness.</li>
          <li>Parametric insurance and anticipatory action financing mechanisms are maturing quickly, giving early warning data somewhere concrete to trigger a response.</li>
        </ul>
      </div>
      <div class="sd-gap-col sd-open">
        <h4>Gaps Still Open</h4>
        <ul>
          <li>National forecasts rarely translate into a plain warning that reaches one specific household, school, or clinic in time, and even more rarely in the language that household speaks.</li>
          <li>Health facility registries and climate hazard layers are often built and held separately, so nobody can easily see which clinics sit inside a flood plain or a heat corridor.</li>
          <li>Most climate technology investment still does not centre a child's specific risk, the same visibility gap AgriKonnekt was built to close in agriculture and household finance.</li>
          <li>Community health workers are rarely given a tool that turns a weather forecast into a specific next step for the patient in front of them.</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- ═══ WHERE THIS FITS ═══ -->
<section class="section">
  <div class="wrap reveal">
    <div style="text-align:center">
      <div class="eyebrow" style="justify-content:center">Where This Fits</div>
      <h3 style="font-family:'EB Garamond',serif;font-size:34px;color:#fff;max-width:680px;margin:0 auto 14px">Built for the kind of call any climate or child health funder already runs</h3>
      <p style="font-family:'EB Garamond',serif;font-style:italic;color:rgba(255,255,255,.55);max-width:640px;margin:0 auto;font-size:19px">Open source licensing, a functional prototype, and a for profit or non profit team building at the intersection of climate resilience and child health, the same shape almost every frontier climate funding call already asks for. This pathway is built to stand on its own evidence, under any institution's name, not only AgriKonnekt's.</p>
      <a href="/#contact" class="btn btn-solid" style="margin-top:26px">Talk To Us About This Pathway →</a>
    </div>
  </div>
</section>

<?php elseif ($page === 'child-resilience-index'): ?>

<section class="papi-hero">
  <video autoplay muted loop playsinline preload="metadata" disablePictureInPicture controlsList="nodownload noremoteplayback noplaybackrate" oncontextmenu="return false" draggable="false" class="kl-nodrag" aria-hidden="true">
    <source src="https://kilimora.africa/wp-content/uploads/2026/08/Child-Resilience-Index.mp4" type="video/mp4">
  </video>
  <svg class="papi-canvas" viewBox="0 0 1200 520" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
    <path class="ca-line" style="stroke:var(--bloom)" d="M130,360 C 230,300 260,180 350,190 C 440,200 460,320 560,300 C 660,280 680,140 790,120 C 900,100 950,220 1060,190" />
    <circle class="ca-node" style="stroke:var(--bloom)" cx="350" cy="190" r="7"/><circle class="ca-node-core" style="fill:var(--bloom)" cx="350" cy="190" r="3"/>
    <circle class="ca-node" style="stroke:var(--bloom);animation-delay:.6s" cx="560" cy="300" r="7"/><circle class="ca-node-core" style="fill:var(--bloom);animation-delay:.6s" cx="560" cy="300" r="3"/>
    <circle class="ca-node" style="stroke:var(--bloom);animation-delay:1.2s" cx="790" cy="120" r="7"/><circle class="ca-node-core" style="fill:var(--bloom);animation-delay:1.2s" cx="790" cy="120" r="3"/>
    <circle class="ca-node" style="stroke:var(--bloom);animation-delay:1.8s" cx="1060" cy="190" r="7"/><circle class="ca-node-core" style="fill:var(--bloom);animation-delay:1.8s" cx="1060" cy="190" r="3"/>
  </svg>
  <div class="wrap">
    <div class="subpage-bar"><a href="/" class="subpage-crumb">Home</a><span class="subpage-sep">/</span><a href="/#explore-more" class="subpage-crumb">Initiative</a><span class="subpage-sep">/</span><span class="subpage-crumb is-current">Child Resilience Index</span></div>
    <div class="hero-badge"><span class="dot"></span> System 05 · Child Resilience Index — Live Weather Feed, Prototype Scoring</div>
    <h1 style="max-width:820px;margin-bottom:14px">One composite score for the whole situation a child is actually growing up in</h1>
    <p class="slead" style="max-width:700px">Food security, whether school loses out to the harvest, and whether a shock arrives as a warning or a surprise: three questions that usually live in three different systems. This one combines them into a single explainable score, grounded in real household signals and a live weather feed, built to route support toward a household.</p>
    <div class="hero-ctas">
      <a href="#explorer" class="btn btn-solid">Try the Live Explorer</a>
      <a href="#how" class="btn btn-line">See How It Works</a>
      <a href="#docs" class="btn btn-line">API Documentation →</a>
    </div>
    <div class="hero-stats">
      <div class="hstat"><b>3</b><span>Composite Modules</span></div>
      <div class="hstat"><b>12</b><span>Scored Signals</span></div>
      <div class="hstat"><b>LIVE</b><span>Weather Fed Climate Module</span></div>
      <div class="hstat"><b>0</b><span>Aid Decisions Made By The Score Alone</span></div>
    </div>
    <div class="sdg-strip">
      <span class="sdg-chip"><span class="sdg-num" style="background:#DDA63A">2</span>Zero Hunger</span>
      <span class="sdg-chip"><span class="sdg-num" style="background:#C5192D">4</span>Quality Education</span>
      <span class="sdg-chip"><span class="sdg-num" style="background:#4C9F38">3</span>Good Health</span>
      <span class="sdg-chip"><span class="sdg-num" style="background:#3F7E44">13</span>Climate Action</span>
      <span class="sdg-chip"><span class="sdg-num" style="background:#19486A">17</span>Partnerships</span>
    </div>
  </div>
</section>

<!-- ═══ LIVE CLIMATE FEED (real, public, no key required) ═══ -->
<div class="fx-bar">
  <div class="wrap reveal">
    <div class="fx-row" id="climateRow">
      <span class="fx-tag"><span class="fx-dot"></span> Live Weather Feed — Open Meteo, Three Reference Counties</span>
      <span class="fx-pair">Kajiado <b id="cf-kajiado">···</b></span>
      <span class="fx-pair">Machakos <b id="cf-machakos">···</b></span>
      <span class="fx-pair">Kitui <b id="cf-kitui">···</b></span>
      <span class="fx-updated" id="climateUpdated">Fetching live feed…</span>
    </div>
  </div>
</div>

<!-- ═══ WHAT DEFINES IT ═══ -->
<section class="section">
  <div class="wrap reveal">
    <div class="eyebrow" style="justify-content:center">What Defines It</div>
    <div class="defines-grid">
      <div class="defines-item">
        <svg class="defines-icon" viewBox="0 0 44 44" fill="none"><circle cx="14" cy="30" r="6" stroke="url(#klIconGrad)" stroke-width="1.6"/><circle cx="30" cy="30" r="6" stroke="url(#klIconGrad)" stroke-width="1.6"/><circle cx="22" cy="14" r="6" stroke="url(#klIconGrad)" stroke-width="1.6"/></svg>
        <h5>Composite, Not Siloed</h5>
        <p>Food security, school continuity and climate exposure are usually scored by three different programmes that never talk to each other. Here they are one record, so nobody falls through the gap between them.</p>
      </div>
      <div class="defines-item">
        <svg class="defines-icon" viewBox="0 0 44 44" fill="none"><path d="M22 6l14 5v9c0 9-6 15-14 18-8-3-14-9-14-18v-9l14-5z" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linejoin="round"/><path d="M16 22l4 4 8-8" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <h5>Never Punitive</h5>
        <p>The score routes outreach toward a household; it never removes a household from a programme, flags a family to any authority, or substitutes for a caseworker's judgment. A low score means "check on this household sooner," nothing else.</p>
      </div>
      <div class="defines-item">
        <svg class="defines-icon" viewBox="0 0 44 44" fill="none"><path d="M6 34h32M13 34V19M22 34V10M31 34V23" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <h5>Grounded, Not Guessed</h5>
        <p>Thresholds are set with local county and school level partners, not imported wholesale from another context, and the climate module is anchored to a live public feed rather than a static assumption.</p>
      </div>
    </div>
  </div>
</section>

<!-- ═══ HOW IT WORKS ═══ -->
<section class="section" id="how" style="padding-top:0">
  <div class="wrap reveal">
    <div class="eyebrow" style="justify-content:center">How It Works</div>
    <h2 style="font-size:clamp(26px,3.2vw,40px);max-width:640px;margin:0 auto 8px;text-align:center">Three modules, one number a partner can act on the same day</h2>
    <p class="slead" style="text-align:center;max-width:560px;margin:0 auto">Every household record already inside AgriKonnekt carries the signals this index needs. Nothing new to collect, just a different lens on data the system already has consent to see.</p>
    <div class="sd-path-row" style="grid-template-columns:repeat(3,1fr);margin-top:36px">
      <div class="sd-path-step">
        <div class="sd-pn">01</div>
        <h5>Three modules score independently</h5>
        <p>Food &amp; nutrition security, school continuity &amp; child labour risk, and climate shock exposure each produce their own sub score, four signals apiece, so no single weak signal can quietly sink the whole picture.</p>
      </div>
      <div class="sd-path-step">
        <div class="sd-pn">02</div>
        <h5>The climate module reads live weather</h5>
        <p>Rainfall and temperature for the household's county are pulled from a public weather feed in real time, not a seasonal average, so the score moves with the actual sky overhead.</p>
      </div>
      <div class="sd-path-step">
        <div class="sd-pn">03</div>
        <h5>A partner routes support, not a verdict</h5>
        <p>A school, county office, or NGO partner sees the composite and all three sub scores, and decides where a home visit, a bursary application, or an early alert is most useful this week.</p>
      </div>
    </div>
  </div>
</section>

<!-- ═══ CHILD-SAFEGUARDING TRUST GRID ═══ -->
<section class="section" style="padding-top:0">
  <div class="wrap reveal">
    <div class="eyebrow" style="justify-content:center">In This System We Trust</div>
    <h2 style="font-size:clamp(26px,3.2vw,40px);max-width:640px;margin:0 auto 40px;text-align:center">A score about a child carries a higher bar, not a lower one</h2>
    <div class="sd-dom-grid">
      <div class="sd-dom-card">
        <svg class="sd-dom-icon" viewBox="0 0 44 44" fill="none"><path d="M22 6l14 5v9c0 9-6 15-14 18-8-3-14-9-14-18v-9l14-5z" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linejoin="round"/><path d="M16 22l4 4 8-8" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <h5>Never A Denial Mechanism</h5>
        <p>The index cannot be used to remove a household from aid, insurance, or any programme. It exists only to prioritise attention toward a household, never to withdraw it.</p>
      </div>
      <div class="sd-dom-card">
        <svg class="sd-dom-icon" viewBox="0 0 44 44" fill="none"><rect x="10" y="19" width="24" height="17" rx="3" stroke="url(#klIconGrad)" stroke-width="1.6"/><path d="M15 19v-5a7 7 0 0114 0v5" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linecap="round"/></svg>
        <h5>Minimised Child Data</h5>
        <p>No child is named, tracked individually, or profiled. Every signal is scored at the household level, encrypted in transit and at rest.</p>
      </div>
      <div class="sd-dom-card">
        <svg class="sd-dom-icon" viewBox="0 0 44 44" fill="none"><circle cx="22" cy="15" r="6" stroke="url(#klIconGrad)" stroke-width="1.6"/><path d="M8 36c0-8 6-13 14-13s14 5 14 13" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linecap="round"/></svg>
        <h5>Community Set Thresholds</h5>
        <p>Tier boundaries are reviewed with local school and county partners who understand the season on the ground, not fixed once by an engineer far from it.</p>
      </div>
      <div class="sd-dom-card">
        <svg class="sd-dom-icon" viewBox="0 0 44 44" fill="none"><path d="M10 22a12 12 0 1024 0 12 12 0 00-24 0z" stroke="url(#klIconGrad)" stroke-width="1.6"/><path d="M22 16v6l5 3" stroke="url(#klIconGrad)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <h5>Opt Out Anytime</h5>
        <p>A household can pause or leave scoring entirely with a single request. Nothing about their access to any AgriKonnekt system depends on staying scored.</p>
      </div>
    </div>
  </div>
</section>

<!-- ═══ LIVE EXPLORER ═══ -->
<section class="section" id="explorer" style="padding-top:0">
  <div class="wrap reveal">
    <div class="eyebrow">Try It</div>
    <h2 style="font-size:clamp(26px,3.2vw,40px);max-width:640px">Move the sliders, watch all three modules respond</h2>
    <p style="font-family:'EB Garamond',serif;font-style:italic;color:rgba(255,255,255,.55);max-width:620px;margin-top:8px">A simplified, illustrative version of the scoring engine. The climate module's first slider starts from the live weather feed above, real numbers, edit it and it stops following the feed.</p>

    <div class="demo-banner">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M12 3l9 16H3l9-16z" stroke="var(--gold-bright)" stroke-width="1.6" stroke-linejoin="round"/><path d="M12 10v4M12 17h.01" stroke="var(--gold-bright)" stroke-width="1.8" stroke-linecap="round"/></svg>
      <div>
        <div class="demo-banner-label">Demo Mode — Not Production Validated</div>
        <p>The sliders, weights and tier thresholds (Fragile / Stabilizing / Resilient) below are simplified, illustrative logic built to show how a composite child resilience score could work. They have not been validated against real nutrition, school attendance or shock recovery outcomes on the ground, and the county tier boundaries have not yet been reviewed with local school or county partners. Only the weather readings feeding the climate module are genuinely live. This is a working prototype presented as exactly that, not a scored or deployed system.</p>
      </div>
    </div>

    <div class="demo-panel" style="margin-top:26px">
      <div class="eyebrow" style="justify-content:flex-start;margin-bottom:6px">Module 01 · Food &amp; Nutrition Security</div>
      <div class="demo-grid">
        <div>
          <div class="demo-factor">
            <div class="demo-factor-head"><span>Harvest Yield Stability</span><b id="v0">2</b></div>
            <input type="range" min="0" max="3" value="2" step="1" oninput="updateResilience()" id="f0">
          </div>
          <div class="demo-factor">
            <div class="demo-factor-head"><span>Dietary Diversity At Home</span><b id="v1">2</b></div>
            <input type="range" min="0" max="3" value="2" step="1" oninput="updateResilience()" id="f1">
          </div>
          <div class="demo-factor">
            <div class="demo-factor-head"><span>Post Harvest Loss Control</span><b id="v2">2</b></div>
            <input type="range" min="0" max="3" value="2" step="1" oninput="updateResilience()" id="f2">
          </div>
          <div class="demo-factor" style="margin-bottom:0">
            <div class="demo-factor-head"><span>Market Access, Distance Adjusted</span><b id="v3">2</b></div>
            <input type="range" min="0" max="3" value="2" step="1" oninput="updateResilience()" id="f3">
          </div>
        </div>
        <div>
          <div class="demo-total-wrap">
            <div class="demo-total-label">Module Score</div>
            <div class="demo-total-num"><span id="modATotal">8</span><span>/12</span></div>
            <div class="demo-tier t-established" id="modATier">Stabilizing</div>
            <p class="demo-note" id="modANote">Basic food security is holding but shocks would still bite; keep monitoring through the lean season.</p>
          </div>
        </div>
      </div>
    </div>

    <div class="demo-panel" style="margin-top:20px">
      <div class="eyebrow" style="justify-content:flex-start;margin-bottom:6px">Module 02 · School Continuity &amp; Child Labour Risk</div>
      <div class="demo-grid">
        <div>
          <div class="demo-factor">
            <div class="demo-factor-head"><span>Protection From Harvest Season Labour Demand</span><b id="v4">2</b></div>
            <input type="range" min="0" max="3" value="2" step="1" oninput="updateResilience()" id="f4">
          </div>
          <div class="demo-factor">
            <div class="demo-factor-head"><span>School Fees Currently Covered</span><b id="v5">2</b></div>
            <input type="range" min="0" max="3" value="2" step="1" oninput="updateResilience()" id="f5">
          </div>
          <div class="demo-factor">
            <div class="demo-factor-head"><span>Distance &amp; Safety Of The Route To School</span><b id="v6">2</b></div>
            <input type="range" min="0" max="3" value="2" step="1" oninput="updateResilience()" id="f6">
          </div>
          <div class="demo-factor" style="margin-bottom:0">
            <div class="demo-factor-head"><span>Household Income Shock Buffer</span><b id="v7">2</b></div>
            <input type="range" min="0" max="3" value="2" step="1" oninput="updateResilience()" id="f7">
          </div>
        </div>
        <div>
          <div class="demo-total-wrap">
            <div class="demo-total-label">Module Score</div>
            <div class="demo-total-num"><span id="modBTotal">8</span><span>/12</span></div>
            <div class="demo-tier t-established" id="modBTier">Watching</div>
            <p class="demo-note" id="modBNote">Some protective factors in place, but harvest season pressure could still interrupt schooling.</p>
          </div>
        </div>
      </div>
    </div>

    <div class="demo-panel" style="margin-top:20px">
      <div class="eyebrow" style="justify-content:flex-start;margin-bottom:6px">Module 03 · Climate Shock Exposure</div>
      <div class="demo-grid">
        <div>
          <div class="demo-factor">
            <div class="demo-factor-head"><span>Live Rainfall Reading <span style="color:rgba(255,255,255,.4);font-style:italic">(from feed above)</span></span><b id="v8">2</b></div>
            <input type="range" min="0" max="3" value="2" step="1" oninput="this.dataset.touched='1';updateResilience()" id="f8">
          </div>
          <div class="demo-factor">
            <div class="demo-factor-head"><span>Historical Drought / Flood Exposure</span><b id="v9">2</b></div>
            <input type="range" min="0" max="3" value="2" step="1" oninput="updateResilience()" id="f9">
          </div>
          <div class="demo-factor">
            <div class="demo-factor-head"><span>Early Warning Access (SMS / Radio)</span><b id="v10">2</b></div>
            <input type="range" min="0" max="3" value="2" step="1" oninput="updateResilience()" id="f10">
          </div>
          <div class="demo-factor" style="margin-bottom:0">
            <div class="demo-factor-head"><span>Post Shock Recovery Capacity</span><b id="v11">2</b></div>
            <input type="range" min="0" max="3" value="2" step="1" oninput="updateResilience()" id="f11">
          </div>
        </div>
        <div>
          <div class="demo-total-wrap">
            <div class="demo-total-label">Module Score</div>
            <div class="demo-total-num"><span id="modCTotal">8</span><span>/12</span></div>
            <div class="demo-tier t-established" id="modCTier">Aware</div>
            <p class="demo-note" id="modCNote">Some warning and buffer capacity exists; a shock would still be hard, but not blind.</p>
          </div>
        </div>
      </div>
    </div>

    <div class="demo-panel" style="margin-top:20px;text-align:center">
      <div class="demo-total-label">Composite Child Resilience Score</div>
      <div class="demo-total-num" style="justify-content:center"><span id="criTotal">24</span><span>/36</span></div>
      <div class="demo-tier t-established" id="criTier" style="margin:0 auto">Stabilizing — building protective factors</div>
      <p class="demo-disclaimer" style="margin-top:16px">Illustrative only. The production model scores against verified household data and the live weather feed directly; this panel lets you feel how the three modules combine.</p>
    </div>
  </div>
</section>

<!-- ═══ VOICES FROM THE FIELD ═══ -->
<section class="section" style="padding-top:0">
  <div class="wrap reveal">
    <div class="eyebrow" style="justify-content:center">Voices From The Field</div>
    <h2 style="font-size:clamp(26px,3.2vw,40px);max-width:640px;margin:0 auto 40px;text-align:center">A number that tells a partner where to look first</h2>
    <div class="voice-grid">
      <div class="voice-card">
        <p class="voice-quote">"We used to find out a child had stopped coming weeks after it happened. Now the school continuity score gives us a reason to check in before that gap opens."</p>
        <div class="voice-attr">
          <div class="voice-avatar">FM</div>
          <div><div class="voice-name">Faith M.</div><div class="voice-role">Head Teacher, Kajiado County</div></div>
        </div>
      </div>
      <div class="voice-card">
        <p class="voice-quote">"The rainfall number is not a guess anymore. When the feed turns red for our ward, we already know which households to call first."</p>
        <div class="voice-attr">
          <div class="voice-avatar">PK</div>
          <div><div class="voice-name">Peter K.</div><div class="voice-role">County Disaster Risk Officer</div></div>
        </div>
      </div>
      <div class="voice-card">
        <p class="voice-quote">"Nobody's ever taken my child off a programme because of a number. It is only ever brought someone to our door sooner."</p>
        <div class="voice-attr">
          <div class="voice-avatar">RN</div>
          <div><div class="voice-name">Ruth N.</div><div class="voice-role">Smallholder, Machakos County</div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ SIGNAL ROADMAP ═══ -->
<section class="section" style="padding-top:0">
  <div class="wrap reveal">
    <div class="eyebrow" style="justify-content:center">Building Out The Index</div>
    <h2 style="font-size:clamp(26px,3.2vw,40px);max-width:640px;margin:0 auto 8px;text-align:center">Started with three modules, on purpose</h2>
    <p class="slead" style="text-align:center;max-width:600px;margin:0 auto">Better to launch three well validated modules than ten untested ones. More arrive once local partners confirm the thresholds hold up across a full season.</p>
    <div class="sd-gap-grid" style="margin-top:30px">
      <div class="sd-gap-col sd-done">
        <h4>Live Today</h4>
        <ul>
          <li>Food &amp; nutrition security module</li>
          <li>School continuity &amp; child labour risk module</li>
          <li>Climate shock exposure module, fed by live public weather data</li>
          <li>Composite score with a full factor level breakdown</li>
        </ul>
      </div>
      <div class="sd-gap-col sd-open">
        <h4>Coming Next</h4>
        <ul>
          <li>Health &amp; immunisation continuity module</li>
          <li>Direct integration with school attendance registers, where partners consent to share them</li>
          <li>County level aggregate dashboards, so a partner can see a ward, not just one household</li>
          <li>Satellite verified rainfall anomaly, layered on top of the live station feed</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- ═══ API DOCUMENTATION ═══ -->
<section class="section" id="docs" style="padding-top:0">
  <div class="wrap reveal">
    <div class="eyebrow">Documentation</div>
    <h2 style="font-size:clamp(26px,3.2vw,40px);max-width:640px;margin-bottom:30px">One call in, three sub scores and a composite out</h2>
    <div class="doc-panel">
      <div class="code-block">
        <div class="cb-label">Request</div>
        <pre><span class="k">POST</span> /v1/child-resilience-index
<span class="c">Authorization:</span> Bearer sk_live_••••••••
<span class="c">Content Type:</span> application/json

{
  <span class="s">"household_id"</span>: <span class="s">"AK KE-88213"</span>,
  <span class="s">"consent_token"</span>: <span class="s">"ctk_9f2..."</span>,
  <span class="s">"county"</span>: <span class="s">"kajiado"</span>
}</pre>
      </div>
      <div class="code-block">
        <div class="cb-label">Response</div>
        <pre>{
  <span class="s">"composite_score"</span>: <span class="k">24</span>,
  <span class="s">"scale"</span>: <span class="k">36</span>,
  <span class="s">"tier"</span>: <span class="s">"stabilizing"</span>,
  <span class="s">"modules"</span>: {
    <span class="s">"food_nutrition_security"</span>: <span class="k">8</span>,
    <span class="s">"school_continuity_labour_risk"</span>: <span class="k">8</span>,
    <span class="s">"climate_shock_exposure"</span>: <span class="k">8</span>
  },
  <span class="s">"climate_source"</span>: <span class="s">"open meteo, live"</span>,
  <span class="s">"aid_decision_authority"</span>: <span class="s">"none, routing_only"</span>,
  <span class="s">"human_review_required"</span>: <span class="k">true</span>
}</pre>
      </div>
    </div>
  </div>
</section>

<!-- ═══ WHERE THIS FITS ═══ -->
<section class="section">
  <div class="wrap reveal">
    <div style="text-align:center">
      <div class="eyebrow" style="justify-content:center">Where This Fits</div>
      <h3 style="font-family:'EB Garamond',serif;font-size:34px;color:#fff;max-width:680px;margin:0 auto 14px">Built for the kind of evidence a child innovation fund already asks for</h3>
      <p style="font-family:'EB Garamond',serif;font-style:italic;color:rgba(255,255,255,.55);max-width:640px;margin:0 auto;font-size:19px">A working prototype, open licensing, and publicly exposed, measurable real time data, built at the exact intersection of food security, education continuity and climate resilience that a fund reviewing this space is already scoring applications against.</p>
      <a href="/#contact" class="btn btn-solid" style="margin-top:26px">Talk To Us About This System →</a>
    </div>
  </div>
</section>

<?php endif; ?>

<footer>
  <div class="wrap reveal" id="contact" style="padding-bottom:32px;margin-bottom:32px;border-bottom:1px solid rgba(255,255,255,.14);text-align:center;display:flex;align-items:center;justify-content:center;gap:24px;flex-wrap:wrap">
    <p style="margin:0;font-family:'EB Garamond',serif;font-style:italic;font-size:25px;color:#fff">Want to see what this can do for one more child's household? Get in touch with the team.</p>
    <a href="mailto:hello@kilimora.africa" class="btn btn-solid" style="flex-shrink:0">Email the Team</a>
  </div>
  <div class="wrap reveal">
    <div class="f-top">
      <div style="display:flex;align-items:center">
        <div class="f-logo">
          <a href="https://uif.kilimora.africa/" style="display:inline-flex;align-items:center;gap:10px;text-decoration:none">
          <img src="https://kilimora.africa/wp-content/uploads/2026/06/AgriKonnect-6-No-Background-scaled.png" alt="AgriKonnekt" draggable="false" class="kl-nodrag">
          <span class="f-logo-txt"><span>AK</span><span>Children</span><span>Initiative</span></span>
          </a>
        </div>
      </div>
      <div class="f-nav-group">
      <div class="f-col">
        <div class="f-head">Site</div>
        <a href="<?= $hp ?>#product">Product</a>
        <a href="<?= $hp ?>#explore-more">Initiative</a>
        <a href="<?= $hp ?>#journey">The Journey</a>
        <a href="<?= $hp ?>#africa">Africa &amp; Policy</a>
      </div>
      <div class="f-col">
        <div class="f-head">Initiative</div>
        <a href="/financial-enablement"<?= $page==='financial-enablement' ? ' class="current"' : '' ?>>Financial Enablement</a>
        <a href="/child-aid-alignment"<?= $page==='child-aid-alignment' ? ' class="current"' : '' ?>>Child Aid Alignment</a>
        <a href="/sport-development"<?= $page==='sport-development' ? ' class="current"' : '' ?>>Sport &amp; Development</a>
        <a href="/climate-resilience"<?= $page==='climate-resilience' ? ' class="current"' : '' ?>>Climate &amp; Health Resilience</a>
        <a href="/child-resilience-index"<?= $page==='child-resilience-index' ? ' class="current"' : '' ?>>Child Resilience Index</a>
      </div>
      <div class="f-col">
        <div class="f-head">Open Source</div>
        <a href="<?= $hp ?>#opensource">Our Licence Stack</a>
        <div class="f-head" style="margin-top:20px">Contact</div>
        <a href="mailto:hello@kilimora.africa">hello@kilimora.africa</a>
      </div>
      </div>
    </div>
    <div class="f-bottom">
      <div class="fb-left">
        © <?php echo $siteYear; ?> Managed by <a href="https://kilimora.africa" target="_blank" rel="noopener">Kilimora</a>. The content on this website is licensed under a <a href="https://web.archive.org/web/20230202010104/https://creativecommons.org/licenses/by/4.0/" target="_blank" rel="noopener">Creative Commons Attribution 4.0 International License</a>. Software components are released under the BSD 3 Clause License, <a href="https://opensource.org/licenses" target="_blank" rel="noopener">OSI principles designed</a> open source software.
      </div>
      <div class="fb-right">
        <a href="/privacy-policy">Privacy Policy</a>
        <a href="/terms-of-use">Terms of Use</a>
      </div>
    </div>
  </div>
</footer>

<script>
(function(){
  'use strict';
  var h = document.getElementById('klHeader');
  var prog = document.getElementById('scrollProgress');
  window.addEventListener('scroll', function(){
    h.classList.toggle('solid', window.scrollY > 40);
    var doc = document.documentElement;
    var pct = (window.scrollY / (doc.scrollHeight - doc.clientHeight)) * 100;
    if (prog) prog.style.width = pct + '%';
  });
  var ham = document.getElementById('hamBtn'), drawer = document.getElementById('mobDrawer'), close = document.getElementById('mobClose');
  ham && ham.addEventListener('click', function(){ drawer.classList.add('open'); });
  close && close.addEventListener('click', function(){ drawer.classList.remove('open'); });
  drawer && drawer.querySelectorAll('a').forEach(function(a){ a.addEventListener('click', function(){ drawer.classList.remove('open'); }); });

  // Mobile drawer accordion groups (Product / Initiative)
  drawer && drawer.querySelectorAll('.mob-group-head').forEach(function(btn){
    btn.addEventListener('click', function(){
      var grp = btn.closest('.mob-group');
      var wasOpen = grp.classList.contains('open');
      drawer.querySelectorAll('.mob-group.open').forEach(function(g){ g.classList.remove('open'); });
      if (!wasOpen) grp.classList.add('open');
    });
  });

  // Desktop header dropdowns: tap/click to open on touch devices, hover already handled by CSS
  document.querySelectorAll('.kl-nav-item').forEach(function(item){
    var trigger = item.querySelector(':scope > .kl-nav-link');
    trigger && trigger.addEventListener('click', function(e){
      if (window.matchMedia('(hover: none)').matches){
        e.preventDefault();
        var wasOpen = item.classList.contains('open');
        document.querySelectorAll('.kl-nav-item.open').forEach(function(i){ i.classList.remove('open'); });
        if (!wasOpen) item.classList.add('open');
      }
    });
  });
  document.addEventListener('click', function(e){
    if (!e.target.closest('.kl-nav-item')){
      document.querySelectorAll('.kl-nav-item.open').forEach(function(i){ i.classList.remove('open'); });
    }
  });

  var revealEls = document.querySelectorAll('.reveal');

  // Liquid cursor glow: smooth-follows the pointer on fine-pointer, motion-OK devices only.
  (function(){
    var glow = document.getElementById('klCursorGlow');
    if (!glow) return;
    var fine = window.matchMedia('(pointer:fine)').matches;
    var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (!fine || reduced) return;
    var tx = window.innerWidth/2, ty = window.innerHeight/2, cx = tx, cy = ty;
    document.addEventListener('mousemove', function(e){ tx = e.clientX; ty = e.clientY; });
    function tick(){
      cx += (tx - cx) * 0.09;
      cy += (ty - cy) * 0.09;
      glow.style.transform = 'translate(' + cx + 'px,' + cy + 'px) translate(-50%,-50%)';
      requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
  })();

  if ('IntersectionObserver' in window){
    var io = new IntersectionObserver(function(entries){
      entries.forEach(function(en){ if (en.isIntersecting){ en.target.classList.add('in'); io.unobserve(en.target); } });
    }, { threshold: 0, rootMargin: '0px 0px -10px 0px' });
    revealEls.forEach(function(el){ io.observe(el); });
  } else {
    revealEls.forEach(function(el){ el.classList.add('in'); });
  }
  // Safety net: if the page loads directly on a #hash (e.g. a shared link to
  // #sport-development), reveal that section immediately instead of waiting
  // on a scroll/intersection event that may never fire from a cold load.
  if (window.location.hash){
    var target = document.querySelector(window.location.hash);
    if (target){
      var scoped = target.matches('.reveal') ? [target] : target.querySelectorAll('.reveal');
      (scoped.length ? scoped : [target]).forEach(function(el){ el.classList.add('in'); });
      target.querySelectorAll('.reveal').forEach(function(el){ el.classList.add('in'); });
    }
  }

  document.addEventListener('dragstart', function(e){ e.preventDefault(); }, false);
  document.addEventListener('contextmenu', function(e){ e.preventDefault(); }, false);
  document.addEventListener('selectstart', function(e){ e.preventDefault(); }, false);
  document.addEventListener('keydown', function(e){
    var k = e.key ? e.key.toLowerCase() : '';
    if ((e.ctrlKey || e.metaKey) && ['s','u','c','x','p'].indexOf(k) > -1) { e.preventDefault(); }
  }, false);

  /* ═══ SCROLL FLOW ENGINE — numbered counter, scroll cue, video theatre, galleries ═══ */

  // Floating "01 / 09"-style counter + "scroll to explore" cue, tracking the
  // main full-bleed sections the way the reference site's chapter marker does.
  var flowPanels = Array.prototype.slice.call(document.querySelectorAll('.hero, .papi-hero, .section'));
  if (flowPanels.length){
    var fc = document.createElement('div');
    fc.className = 'flow-counter';
    fc.innerHTML = '<b id="flowCur">01</b><i>/ ' + String(flowPanels.length).padStart(2,'0') + '</i>';
    document.body.appendChild(fc);
    var flowCurEl = document.getElementById('flowCur');
    var flowTicking = false;
    function flowUpdate(){
      flowTicking = false;
      var idx = 0;
      for (var i = 0; i < flowPanels.length; i++){
        if (flowPanels[i].getBoundingClientRect().top <= window.innerHeight * 0.5) idx = i;
      }
      if (flowCurEl) flowCurEl.textContent = String(idx + 1).padStart(2, '0');
    }
    window.addEventListener('scroll', function(){
      if (!flowTicking){ flowTicking = true; requestAnimationFrame(flowUpdate); }
    }, { passive: true });
    flowUpdate();
  }

  // Horizontal slideshow galleries: dot indicators + prev/next arrows,
  // synced to native scroll-snap so it also works with touch swipe.
  document.querySelectorAll('.h-gallery').forEach(function(g){
    var wrap = g.closest('.h-gallery-wrap');
    if (!wrap) return;
    var slides = g.querySelectorAll('.h-slide');
    if (!slides.length) return;
    var nav = document.createElement('div');
    nav.className = 'h-gallery-nav';
    slides.forEach(function(s, i){
      var d = document.createElement('span');
      d.className = 'h-gallery-dot' + (i === 0 ? ' active' : '');
      d.addEventListener('click', function(){ s.scrollIntoView({ behavior: 'smooth', inline: 'start', block: 'nearest' }); });
      nav.appendChild(d);
    });
    wrap.appendChild(nav);
    var prevBtn = document.createElement('div'); prevBtn.className = 'h-gallery-arrow prev'; prevBtn.innerHTML = '&#8249;';
    var nextBtn = document.createElement('div'); nextBtn.className = 'h-gallery-arrow next'; nextBtn.innerHTML = '&#8250;';
    wrap.appendChild(prevBtn); wrap.appendChild(nextBtn);
    prevBtn.addEventListener('click', function(){ g.scrollBy({ left: -g.clientWidth * 0.82, behavior: 'smooth' }); });
    nextBtn.addEventListener('click', function(){ g.scrollBy({ left: g.clientWidth * 0.82, behavior: 'smooth' }); });
    var galTicking = false;
    g.addEventListener('scroll', function(){
      if (galTicking) return;
      galTicking = true;
      requestAnimationFrame(function(){
        galTicking = false;
        var idx = Math.round(g.scrollLeft / g.clientWidth);
        nav.querySelectorAll('.h-gallery-dot').forEach(function(d, i){ d.classList.toggle('active', i === idx); });
      });
    }, { passive: true });
  });
})();
</script>
</body>

<?php if ($page === 'financial-enablement'): ?>
<script>
async function loadFX(){
  try{
    const res = await fetch('https://open.er-api.com/v6/latest/USD');
    const data = await res.json();
    if(!data || !data.rates) throw new Error('no rates');
    const kes = data.rates.KES, eur = data.rates.EUR, gbp = data.rates.GBP;
    document.getElementById('fxUsdKes').textContent = kes.toFixed(2);
    document.getElementById('fxEurKes').textContent = (kes/eur).toFixed(2);
    document.getElementById('fxGbpKes').textContent = (kes/gbp).toFixed(2);
    document.getElementById('fxUpdated').textContent = 'Updated ' + (data.time_last_update_utc || 'just now');
  }catch(e){
    document.getElementById('fxUpdated').textContent = 'Live feed unavailable right now';
  }
}
loadFX();
setInterval(loadFX, 90000);

function updateScore(){
  let total = 0;
  for(let i=0;i<8;i++){
    const val = parseInt(document.getElementById('f'+i).value,10);
    document.getElementById('v'+i).textContent = val;
    total += val;
  }
  document.getElementById('scoreTotal').textContent = total;
  const tierEl = document.getElementById('scoreTier');
  const noteEl = document.getElementById('scoreNote');
  tierEl.className = 'demo-tier';
  if(total <= 11){
    tierEl.classList.add('t-emerging');
    tierEl.textContent = 'Emerging';
    noteEl.textContent = 'A household just entering the system. Qualifies for a small, safe starter loan while more data accumulates.';
  } else if(total <= 18){
    tierEl.classList.add('t-established');
    tierEl.textContent = 'Established';
    noteEl.textContent = 'A household this consistent typically qualifies for a standard-rate microloan and weather-index insurance.';
  } else {
    tierEl.classList.add('t-compounding');
    tierEl.textContent = 'Compounding';
    noteEl.textContent = 'Strong across every factor. Qualifies for larger, lower-rate financing and input packages that push past subsistence.';
  }
}
updateScore();
</script>
<?php elseif ($page === 'child-resilience-index'): ?>
<script>
async function loadClimateFeed(){
  const sites = [
    { id:'kajiado', lat:-1.85, lon:36.78 },
    { id:'machakos', lat:-1.52, lon:37.27 },
    { id:'kitui', lat:-1.37, lon:38.01 }
  ];
  try{
    const results = await Promise.all(sites.map(async function(s){
      const res = await fetch('https://api.open-meteo.com/v1/forecast?latitude=' + s.lat + '&longitude=' + s.lon + '&current=temperature_2m,precipitation&daily=precipitation_sum&timezone=Africa%2FNairobi&forecast_days=1');
      const data = await res.json();
      return { id:s.id, temp: data.current.temperature_2m, rainToday: data.daily.precipitation_sum[0] };
    }));
    results.forEach(function(r){
      const el = document.getElementById('cf-' + r.id);
      if (el) el.textContent = r.temp.toFixed(1) + '°C · ' + r.rainToday.toFixed(1) + 'mm today';
    });
    document.getElementById('climateUpdated').textContent = 'Updated ' + new Date().toLocaleTimeString('en-GB',{hour:'2-digit',minute:'2-digit'}) + ' EAT';

    const avgRain = results.reduce(function(a,r){ return a + r.rainToday; }, 0) / results.length;
    const liveSlider = document.getElementById('f8');
    if (liveSlider && !liveSlider.dataset.touched){
      let val = 3;
      if (avgRain < 1) val = 1;
      else if (avgRain > 15) val = 1;
      else if (avgRain > 5) val = 2;
      liveSlider.value = val;
      updateResilience();
    }
  }catch(e){
    const u = document.getElementById('climateUpdated');
    if (u) u.textContent = 'Live feed unavailable right now';
  }
}
loadClimateFeed();
setInterval(loadClimateFeed, 5 * 60000);

function updateResilience(){
  const groups = [
    { ids:['f0','f1','f2','f3'], totalEl:'modATotal', tierEl:'modATier', noteEl:'modANote',
      tiers:[
        [0,4,'emerging','Fragile','Food access is inconsistent; prioritise this household for nutrition support and market linkage first.'],
        [5,8,'established','Stabilizing','Basic food security is holding but shocks would still bite; keep monitoring through the lean season.'],
        [9,12,'compounding','Resilient','Diverse, buffered food access; this household can likely absorb an ordinary bad season.']
      ] },
    { ids:['f4','f5','f6','f7'], totalEl:'modBTotal', tierEl:'modBTier', noteEl:'modBNote',
      tiers:[
        [0,4,'emerging','At Risk','High chance a child is pulled toward labour or misses school this term; flag for a school-linked follow-up.'],
        [5,8,'established','Watching','Some protective factors in place, but harvest season pressure could still interrupt schooling.'],
        [9,12,'compounding','Protected','School continuity looks well protected against the usual seasonal pressures.']
      ] },
    { ids:['f8','f9','f10','f11'], totalEl:'modCTotal', tierEl:'modCTier', noteEl:'modCNote',
      tiers:[
        [0,4,'emerging','Exposed','Limited warning and recovery capacity; this household should be first on the list for an early alert.'],
        [5,8,'established','Aware','Some warning and buffer capacity exists; a shock would still be hard, but not blind.'],
        [9,12,'compounding','Buffered','Strong warning access and recovery capacity; this household is positioned to absorb a climate shock.']
      ] }
  ];
  let grand = 0;
  groups.forEach(function(g){
    let sub = 0;
    g.ids.forEach(function(id){
      const el = document.getElementById(id);
      const val = parseInt(el.value,10);
      const vEl = document.getElementById('v' + id.slice(1));
      if (vEl) vEl.textContent = val;
      sub += val;
    });
    grand += sub;
    document.getElementById(g.totalEl).textContent = sub;
    let tier = g.tiers[0];
    for(let i=0;i<g.tiers.length;i++){ if (sub >= g.tiers[i][0] && sub <= g.tiers[i][1]) tier = g.tiers[i]; }
    const tEl = document.getElementById(g.tierEl);
    tEl.className = 'demo-tier t-' + tier[2];
    tEl.textContent = tier[3];
    document.getElementById(g.noteEl).textContent = tier[4];
  });
  document.getElementById('criTotal').textContent = grand;
  const criTierEl = document.getElementById('criTier');
  if (grand <= 14){
    criTierEl.className = 'demo-tier t-emerging';
    criTierEl.textContent = 'Fragile — needs immediate support';
  } else if (grand <= 24){
    criTierEl.className = 'demo-tier t-established';
    criTierEl.textContent = 'Stabilizing — building protective factors';
  } else {
    criTierEl.className = 'demo-tier t-compounding';
    criTierEl.textContent = 'Resilient — protective factors compounding';
  }
}
updateResilience();
</script>
<?php endif; ?>
</body>
</html>
