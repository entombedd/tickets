<!DOCTYPE html>
<html>
  <head>
    <title>Бронирование билетов</title>
    <meta charset="utf-8">
    <script src="j.js"></script>
    <link rel="stylesheet" href="main.css">
  </head>
  <body>
    <?php
    $tripid = 1;
    $userid = 1;
    require "aaa.php";
    $trip = $_BUS->get($tripid);
    ?>
    <div class="info">
      <div class="iLeft">Дата</div>
      <div class="iRight"><?=$trip["trip_date"]?></div>
    </div>
    <div class="info">
      <div class="iLeft">Откуда</div>
      <div class="iRight"><?=$trip["trip_from"]?></div>
    </div>
    <div class="info">
      <div class="iLeft">Куда</div>
      <div class="iRight"><?=$trip["trip_to"]?></div>
    </div>
    <div id="layout"><?php foreach ($trip["seats"] as $s) {
      $taken = is_numeric($s["user_id"]);
      printf("<div class='seat%s'%s>%s</div>",
        $taken ? " taken" : "",
        $taken ? "" : " onclick='reserve.toggle(this)'",
        $s["seat_id"]
      );
    } ?></div>
    <div class="legend">
      <div class="box"></div> Свободные места
    </div>
    <div class="legend">
      <div class="box taken"></div> Занятые места
    </div>
    <div class="legend">
      <div class="box selected"></div> Выбранные вами места
    </div>

    <form id="form" method="post" action="save.php" onsubmit="return reserve.save();">
      <input type="hidden" name="tid" value="<?=$tripid?>">
      <input type="hidden" name="uid" value="<?=$userid?>">
      <label>Имя</label>
      <input type="text" name="name">
      <label>Почта</label>
      <input type="email" name="email">
      <input type="submit" value="Забронировать места">
    </form>
  </body>
</html>