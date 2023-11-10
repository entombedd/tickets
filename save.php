<?php
require "aaa.php";
echo $_BUS->reserve(
  $_POST["tid"], $_POST["uid"], $_POST["email"], $_POST["name"], $_POST["seats"]
) ? "OK" : "ERROR" ;