<?php
$sent = mail("tatasab@yahoo.com", "Test", "This is a test message", "From: test@geofl.ge");
echo $sent ? "OK" : "FAILED";
