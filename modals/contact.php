<template id="contactModal">
  <h2><?= $lang === 'ka' ? 'დაგვიკავშირდით' : 'Contact Us'; ?></h2>

  <form id="contactForm" class="contact-form" method="post" action="/siteUnderDevelopment/handler/contactHandler.php">
    <!-- Honeypot для защиты от ботов -->
    <input type="text" name="honeypot" id="honeypot" style="display:none;">
    
    <label for="name">
      <?= $lang === 'ka' ? 'სახელი' : 'Name'; ?>
      <input type="text" name="name" required>
    </label>

    <label for="email">
      <?= $lang === 'ka' ? 'ელფოსტა' : 'Email'; ?>
      <input type="email" name="email" required>
    </label>

    <label for="message">
      <?= $lang === 'ka' ? 'მესიჯი' : 'Message'; ?>
      <textarea name="message" required></textarea>
    </label>

    <button type="submit">
      <?= $lang === 'ka' ? 'გაგზავნა' : 'Send'; ?>
    </button>
  </form>
</template>
