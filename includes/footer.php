<footer>
  <div class="footer-wrapper">

    <!-- Левая часть -->
    <div class="footer-left">
      <p><span>© </span>ყველა უფლება დაცულია | All Rights Reserved</p>
      <p>© ქართულის, როგორც უცხო ენის, სწავლების პროგრამა, 2014</p>
      <p>© ქართულის, როგორც უცხო ენის, სწავლების პროგრამა „ირბახი“, 2016</p>
      <p>© ქართულის, როგორც უცხო ენის, სწავლების პროგრამა „ირბახი“, 2025</p>
    </div>

    <!-- Средняя часть -->
    <div class="footer-middle">
      <ul class="footer-links">
        <li><a href="#" data-modal="programModal"><?= $labels['about_program'] ?></a></li>
        <li><a href="#" data-modal="aboutModal"><?= $labels['about_us'] ?></a></li>
      </ul>
    </div>

    <!-- Правая часть -->
    <div class="footer-right">
      <div class="contact-grid">
        <!-- 1. Контакт -->
        <div class="contact-span"><?= $labels['contact'] ?></div>

        <!-- 2. Адрес -->
        <div class="contact-span">
          <?php if ($lang === 'ka'): ?>
            <p>მისამართი:</p>
            <p>საქართველო, 0102 თბილისი, დიმიტრი&nbsp;უზნაძის&nbsp;ქ.&nbsp;N52</p>
            <p>ტელეფონი: (+995 32) 220 02 20</p>
          <?php else: ?>
            <p>Address:</p>
            <p>Georgia, 0102 Tbilisi, Dimitry Uznadze st. N52</p>
            <p>Telephone: (+995 32) 220 02 20</p>
          <?php endif; ?>
        </div>

        <!-- 3. Email -->
        <img class="icon" src="assets/img/gmail.svg" alt="Email" data-modal="contactModal">
        <a class="contact-link" data-modal="contactModal" href="#">geolang@mes.gov.ge</a>

        <!-- 4. Facebook -->
        <img class="icon" src="assets/img/fb.svg" alt="Facebook">
        <a class="contact-link" href="https://www.facebook.com/groups/582203081933137/" target="_blank">
          GEORGIAN AS A FOREIGN LANGUAGE
        </a>
      </div>
    </div>

  </div>
</footer>
