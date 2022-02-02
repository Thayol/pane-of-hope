<?php
if (isset($_SESSION["paneofhope"])) unset($_SESSION["paneofhope"]);

header('Location: ' . $absolute_prefix . '/login/');