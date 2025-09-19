<?php
    session_start();
    unset($_SESSION["person"]);
    session_destroy();
    header("Location:../");
