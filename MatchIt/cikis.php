<?php

session_start();

session_destroy();

header("Location: kayit-giris.html");
exit;