<?php
/**
 * Shared partial for the two legal routes: /terms-of-use and /privacy-policy.
 * Included from index.php once $page has been resolved to one of these two
 * values. Expects $page and $siteYear to already be set by index.php.
 */
$isTerms = $page === 'terms-of-use';
$title   = $isTerms ? 'Terms of Use: Kilimora / AgriKonnekt' : 'Privacy Policy: Kilimora / AgriKonnekt';
$other   = $isTerms ? ['label' => 'Privacy Policy', 'href' => '/privacy-policy'] : ['label' => 'Terms of Use', 'href' => '/terms-of-use'];
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $title ?></title>
<meta name="robots" content="index, follow">
<link rel="canonical" href="https://uif.kilimora.africa/<?= $isTerms ? 'terms-of-use' : 'privacy-policy' ?>">
<link rel="icon" type="image/png" href="https://kilimora.africa/wp-content/uploads/2026/06/AgriKonnect-6-No-Background-scaled.png?v=7">
<link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700&family=EB+Garamond:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
<style>
:root{--kl-cream:#FAF6E8;--kl-green:#1A5C38;--kl-gold:#C8922A;--kl-gold-bright:#E6A832;--kl-dark:#1A1814;--kl-ink:#2C2820;--kl-mid:#4A4540;--kl-soft:#7A7268;--kl-rule:rgba(216,207,160,.4)}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Ubuntu',sans-serif;background:var(--kl-cream);color:var(--kl-ink);line-height:1.75}
a{color:var(--kl-green);text-decoration:none;border-bottom:1px dotted rgba(26,92,56,.4)}
.wrap{max-width:840px;margin:0 auto;padding:0 28px}
header{background:var(--kl-dark);padding:22px 0;position:sticky;top:0;z-index:100;box-shadow:0 0 0 rgba(0,0,0,0);transition:padding .25s ease, box-shadow .25s ease}
header.is-scrolled{padding:10px 0;box-shadow:0 4px 18px rgba(0,0,0,.28)}
header .wrap{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px 20px}
.logo{display:flex;align-items:center;gap:10px;min-width:0}
.logo img{height:32px;width:32px;flex-shrink:0;border-radius:50%;-webkit-user-drag:none;user-drag:none;transition:height .25s ease,width .25s ease}
header.is-scrolled .logo img{height:22px;width:22px}
.logo span{font-family:'EB Garamond',serif;font-weight:700;color:#fff;font-size:17px;line-height:1.25;white-space:normal;transition:font-size .25s ease}
header.is-scrolled .logo span{font-size:14px}
header a.back{flex-shrink:0;color:rgba(255,255,255,.6);font-size:11px;letter-spacing:.1em;text-transform:uppercase;border:0;white-space:nowrap;padding:6px 2px;transition:font-size .25s ease}
header.is-scrolled a.back{font-size:9.5px}
@media (max-width:480px){
  .logo span{font-size:14px}
  header a.back{font-size:10px}
  header.is-scrolled .logo span{font-size:12px}
}
main{padding:70px 0 90px}
h1{font-family:'EB Garamond',serif;font-size:clamp(30px,4.4vw,46px);font-weight:700;color:var(--kl-dark);margin-bottom:8px}
.updated{font-size:12px;color:var(--kl-soft);text-transform:uppercase;letter-spacing:.1em;margin-bottom:44px}
h2{font-family:'EB Garamond',serif;font-size:22px;color:var(--kl-green);margin:38px 0 12px}
p,li{font-size:15px;color:var(--kl-mid);margin-bottom:14px}
ul{padding-left:22px;margin-bottom:14px}
.callout{background:#fff;border-left:3px solid var(--kl-gold);border-radius:0 12px 12px 0;padding:20px 24px;margin:26px 0;font-style:italic;font-family:'EB Garamond',serif;color:var(--kl-ink)}
table{width:100%;border-collapse:collapse;margin:18px 0}
td{padding:12px 0;border-bottom:1px solid var(--kl-rule);font-size:14px;vertical-align:top}
td:first-child{width:32%;font-weight:700;color:var(--kl-green)}
footer{background:#100e0a;color:rgba(255,255,255,.4);padding:30px 0;font-size:11px}
footer .wrap{display:flex;justify-content:space-between;flex-wrap:wrap;gap:10px}
footer a{color:rgba(255,255,255,.6);border-color:rgba(255,255,255,.2)}
</style>
</head>
<body>
<header>
  <div class="wrap">
    <a href="/" class="logo">
      <img src="https://kilimora.africa/wp-content/uploads/2026/06/AgriKonnect-FAVICON-scaled.png" alt="AK Children Initiative" draggable="false">
      <span>AK Children Initiative</span>
    </a>
    <a class="back" href="/">← Back to Homepage</a>
  </div>
</header>

<main class="wrap">
<?php if ($isTerms): ?>

  <h1>Terms of Use</h1>
  <div class="updated">Last updated: <?php echo date('F j, Y'); ?> · Applies to uif.kilimora.africa and kilimora.africa</div>

  <p>These Terms of Use ("Terms") govern your access to and use of this Expression of Interest microsite for AgriKonnekt, operated by Kilimora ("Kilimora," "we," "us"). By accessing this site, you agree to these Terms.</p>

  <h2>1. Purpose of This Site</h2>
  <p>This site publishes Kilimora's Expression of Interest to the UNICEF Innovation Fund and describes the AgriKonnekt platform, its open source licensing, target impact, and roadmap, including the Climate &amp; Health Resilience pathway built on the same verified household record. It is informational and does not itself process farmer transactions.</p>

  <h2>2. Two Different Kinds of Content: Please Read Carefully</h2>
  <p>It is important to distinguish between two categories of material on this site, because they are licensed differently:</p>
  <table>
    <tr><td>Site design, copy, photography &amp; video</td><td>© Kilimora, all editorial and visual content on this microsite (layout, written copy, photographs, and video) is protected and may not be copied, downloaded, redistributed, or reused without written permission, except where explicitly released under the Creative Commons licence referenced in Section 4 below.</td></tr>
    <tr><td>The AgriKonnekt open-source platform</td><td>The underlying AgriKonnekt software, hardware designs, and documentation are separately released as open-source under the BSD 3-Clause License (software), CERN Open Hardware Licence (hardware), and CC BY 4.0 (design/content), as described on the "Open Source" section of this site. Use of the open-source project itself is governed by those license texts, available in the project repository.</td></tr>
  </table>

  <h2>3. Content Protection &amp; Restrictions</h2>
  <p>To protect the integrity of our brand, our photography, and our video content, this site applies technical measures that disable right-click saving, dragging, and text selection on images, video, and the site logo. You agree not to attempt to circumvent these protections, and not to scrape, mirror, republish, or redistribute site content (other than the open-source AgriKonnekt project components explicitly licensed for reuse) without our prior written consent.</p>

  <div class="callout">If you need a copy of an image, video, or document for legitimate press, funding-partner, or due-diligence purposes, simply email us, we are glad to share files directly rather than have them scraped from the page.</div>

  <h2>4. Creative Commons Licensing (Site Content)</h2>
  <p>Where explicitly stated, the content on this website, managed by <a href="https://kilimora.africa" target="_blank" rel="noopener">kilimora.africa</a>, is licensed under a <a href="https://web.archive.org/web/20230202010104/https://creativecommons.org/licenses/by/4.0/" target="_blank" rel="noopener">Creative Commons Attribution 4.0 International License</a>. Any reuse under that license must credit Kilimora and link back to kilimora.africa.</p>

  <h2>5. Open-Source Licensing (AgriKonnekt Platform)</h2>
  <p>AgriKonnekt's software components are released under the BSD 3-Clause License, an OSI-approved open-source license listed at <a href="https://opensource.org/licenses" target="_blank" rel="noopener">opensource.org/licenses</a>. Under this license, you may use, copy, modify, and redistribute the covered source code, with or without modification, provided the license's copyright notice, list of conditions, and disclaimer are retained, and Kilimora's name is not used to endorse derived products without prior written permission. The full license text is included in the project's <code>LICENSE</code> file. No warranty is provided; use is at your own risk, as set out in that license text.</p>

  <h2>6. No Warranty; Informational Purpose</h2>
  <p>Impact figures, milestones, and targets presented on this site are projections tied to Kilimora's Expression of Interest and are subject to change as pilot data is finalised. This site does not constitute financial, legal, or investment advice, and nothing on it guarantees Innovation Fund selection or funding.</p>

  <h2>7. Third-Party Links</h2>
  <p>This site links to third-party resources (for example, the Open Source Initiative's license list and the Wayback Machine's archived copy of the CC BY 4.0 license text). Kilimora is not responsible for the content or availability of external sites.</p>

  <h2>8. Limitation of Liability</h2>
  <p>To the fullest extent permitted by Kenyan law, Kilimora is not liable for any indirect, incidental, or consequential damages arising from your use of, or inability to use, this site.</p>

  <h2>9. Governing Law</h2>
  <p>These Terms are governed by the laws of the Republic of Kenya. Any dispute arising from these Terms will be subject to the exclusive jurisdiction of the courts of Kenya.</p>

  <h2>10. Changes to These Terms</h2>
  <p>We may revise these Terms from time to time; the "Last updated" date above will reflect the most recent revision. Continued use of the site after changes take effect constitutes acceptance of the revised Terms.</p>

  <h2>11. Contact</h2>
  <p>Questions about these Terms can be directed to <a href="mailto:hello@kilimora.africa">hello@kilimora.africa</a> or <a href="tel:+254103900367">+254 103 900 367</a>.</p>

<?php else: ?>

  <h1>Privacy Policy</h1>
  <div class="updated">Last updated: <?php echo date('F j, Y'); ?> · Applies to uif.kilimora.africa and kilimora.africa</div>

  <p>This Privacy Policy explains how Kilimora ("Kilimora," "we," "us") collects, uses, and protects information when you visit this Expression of Interest microsite for the AgriKonnekt platform, submitted to the UNICEF Innovation Fund, or otherwise interact with our related services.</p>

  <h2>1. Who We Are</h2>
  <p>Kilimora is a Kenya-registered private company operating the AgriKonnekt agribusiness infrastructure platform. You can reach us at <a href="mailto:hello@kilimora.africa">hello@kilimora.africa</a> or <a href="tel:+254103900367">+254 103 900 367</a> (call or WhatsApp).</p>

  <h2>2. Information We Collect</h2>
  <ul>
    <li><strong>Information you provide directly:</strong> your name, email address, and message contents when you contact us, or details submitted as part of funding, partnership, or pilot applications.</li>
    <li><strong>Farmer and household data (AgriKonnekt platform):</strong> verified digital farmer identity data, farm location, crop and livestock information, and climate/sensor readings collected through the AgriKonnekt platform where you are an onboarded farmer, cooperative, or partner.</li>
    <li><strong>Climate and health signal data (Climate &amp; Health Resilience module):</strong> hazard exposure scores tied to a registered facility or household location, facility level identifiers for schools and clinics taking part, and aggregated health surge indicators used for early warning and forecasting. This module does not collect an individual patient's medical records.</li>
    <li><strong>Automatically collected information:</strong> standard technical data such as browser type, device type, approximate location, and pages visited, used only for basic analytics and security.</li>
  </ul>

  <h2>3. How We Use Information</h2>
  <ul>
    <li>To respond to enquiries about this Expression of Interest and the AgriKonnekt platform;</li>
    <li>To operate, secure, and improve the AgriKonnekt platform for onboarded farmers and cooperatives;</li>
    <li>To generate hazard maps, send early warning alerts, and produce health surge forecasts for registered schools, clinics, and households taking part in the Climate &amp; Health Resilience module;</li>
    <li>To produce the aggregated, publicly exposed impact data required for Innovation Fund reporting and transparency, this aggregated data does not identify individual farmers or children;</li>
    <li>To comply with legal obligations under the Kenya Data Protection Act, 2019, and any applicable UNICEF data-sharing requirements.</li>
  </ul>

  <div class="callout">We never sell personal data. Farmer verification data exists to unlock finance, insurance, and fair market access for the farmer it belongs to, not to be traded to third parties.</div>

  <h2>4. Children's Information</h2>
  <p>This site and the AgriKonnekt platform are directed at adult farmers, cooperatives, company representatives, and institutional partners. We do not knowingly collect personal data directly from children. Where household-level data referenced in impact reporting relates to children (e.g. aggregate household composition for targeting low-income or women-led households), it is used only in de-identified, aggregate form consistent with UNICEF's child-safeguarding expectations. The Climate &amp; Health Resilience module follows the same standard: hazard, early warning, and health surge data is tied to a facility or household, never to an individual child, and any partner receiving it agrees to the same safeguarding terms.</p>

  <h2>5. Data Sharing</h2>
  <p>We may share information with: (a) UNICEF and its designated reviewers, strictly for Innovation Fund evaluation and monitoring purposes; (b) verified financial, insurance, and market partners connected through AgriKonnekt, and only with the farmer's consent as part of onboarding; (c) ministries of health, disaster response agencies, and school or clinic administrators receiving early warning or surge forecast data through the Climate &amp; Health Resilience module, limited to the facility level data needed for that warning; (d) service providers who help us operate our infrastructure (e.g. hosting), under confidentiality obligations; and (e) authorities where required by Kenyan law.</p>

  <h2>6. Data Security</h2>
  <p>We apply reasonable technical and organisational safeguards, including access controls, encryption in transit, and rate-limiting on our systems, to protect information from unauthorised access, alteration, or disclosure. No system is completely secure, and we encourage you to contact us if you believe your data has been compromised.</p>

  <h2>7. Your Rights</h2>
  <p>Under the Kenya Data Protection Act, 2019, you have the right to access, correct, or request deletion of your personal data, and to object to certain processing. To exercise these rights, contact <a href="mailto:hello@kilimora.africa">hello@kilimora.africa</a>.</p>

  <h2>8. Content Protection Notice</h2>
  <p>Images, video, and other media on this site are protected from copying, downloading, and dragging as part of our intellectual property protections described in our <a href="/terms-of-use">Terms of Use</a>. This does not affect your data-privacy rights described above.</p>

  <h2>9. Changes to This Policy</h2>
  <p>We may update this Privacy Policy from time to time. Material changes will be reflected by updating the "Last updated" date above. Continued use of this site after changes take effect constitutes acceptance of the revised policy.</p>

  <h2>10. Contact</h2>
  <p>Questions about this Privacy Policy can be directed to <a href="mailto:hello@kilimora.africa">hello@kilimora.africa</a> or <a href="tel:+254103900367">+254 103 900 367</a>.</p>

<?php endif; ?>
</main>

<footer>
  <div class="wrap">
    <span>© <?php echo $siteYear; ?> Kilimora. Content licensed <a href="https://web.archive.org/web/20230202010104/https://creativecommons.org/licenses/by/4.0/" target="_blank" rel="noopener">CC BY 4.0</a>.</span>
    <span><a href="<?= $other['href'] ?>"><?= $other['label'] ?></a></span>
  </div>
</footer>

<script>
(function(){
  var header = document.querySelector('header');
  if (!header) return;
  var toggle = function(){
    header.classList.toggle('is-scrolled', window.scrollY > 40);
  };
  toggle();
  window.addEventListener('scroll', toggle, {passive: true});
})();
</script>
</body>
</html>
