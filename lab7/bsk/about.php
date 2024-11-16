<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once './utils/templates.php';
include_once './utils/env.php';

renderHeader("About us");

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$siteAddress = $protocol . $_SERVER['HTTP_HOST'];


echo <<<HTML
<h2>About us</h2>
<p>We are the best! Lorem ipsum dolor, sit amet consectetur adipisicing elit. Repudiandae iste nihil quibusdam voluptatum quo voluptates dolorum ducimus non obcaecati molestias! Lorem ipsum, dolor sit amet consectetur adipisicing elit. Sit ratione eligendi harum id nobis illo eveniet exercitationem, provident libero maxime.</p>
<form id='contactForm'>
    <p>Contact us</p>
    <input type='email' name='email' id='email' placeholder='example@hotmail.com' required>
    <textarea id='message' name='message' required></textarea>
    <button type='submit'>Send</button>
</form>

<script>
  document.getElementById('contactForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    grecaptcha.ready(() => {
        grecaptcha.execute("$captchaKey", {
                action: "contact"
            }).then(function(token) {
                // Gather form data
                const email = document.getElementById('email').value;
                const message = document.getElementById('message').value;
          
                // Send data to PHP server
                fetch('$siteAddress/utils/contact.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ email, message, token })
                }).then(response => {
                    if (response.ok) {
                      alert('Message sent successfully!');
                    } else {
                      alert('Failed to send message.');
                    }
                })
            })

    });
  });
</script>
HTML;


renderFooter();

?>