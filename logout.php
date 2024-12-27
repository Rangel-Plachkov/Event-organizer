<?php
    session_start();
    session_destroy();

    header("Location: http://localhost:63342/Event-organizer/index.php");

    exit;