<?php
// Only add SMTP Mailer (SendGrid) if on live site
if(Director::isLive()) Email::set_mailer(new SmtpMailer());