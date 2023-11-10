<?php
class Bus {
  private $pdo = null;
  private $stmt = null;
  public $error = null;
  function __construct () {
    $this->pdo = new PDO(
      'mysql:host=localhost;dbname=55', 'root', '', 
      [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
  }
  function __destruct () {
    if ($this->stmt !== null) { $this->stmt = null; }
    if ($this->pdo !== null) { $this->pdo = null; }
  }
  function query ($sql, $data=null) : void {
    $this->stmt = $this->pdo->prepare($sql);
    $this->stmt->execute($data);
  }
  function trip ($bus, $date, $from, $to, $tid=null) {
    if ($tid==null) {
      $sql = "INSERT INTO trips 
        (bus_id, trip_date, trip_from, trip_to)
        VALUES (?,?,?,?)";
      $data = [$bus, $date, $from, $to];
    } else {
      $sql = "UPDATE trips SET
        bus_id=?, trip_date=?, trip_from=?, trip_to=?
        WHERE trip_id=?";
      $data = [$bus, $date, $from, $to, $tid];
    }
    $this->query($sql, $data);
    return true;
  }
  function get ($tid) {
    $this->query("SELECT * FROM trips WHERE trip_id=?", [$tid]);
    $trip = $this->stmt->fetch();
    if (!is_array($trip)) { return false; }
    $this->query(
      "SELECT s.seat_id, r.user_id
       FROM seats s
       LEFT JOIN trips t USING (bus_id)
       LEFT JOIN reserve_seats r USING(seat_id)
       WHERE t.trip_id=?
       ORDER BY s.seat_id",
      [$tid]
    );
    $trip["seats"] = $this->stmt->fetchAll();
    return $trip;
  }
  function reserve ($tid, $uid, $email, $name, $seats) {
    $this->query(
      "INSERT INTO reservations (trip_id, user_id, email, name) VALUES (?,?,?,?)",
      [$tid, $uid, $email, $name]
    );
    $sql = "INSERT INTO reserve_seats (trip_id, user_id, seat_id) VALUES ";
    foreach ($seats as $seat) {
      $sql .= "(?,?,?),";
      $data[] = $tid;
      $data[] = $uid;
      $data[] = $seat;
    }
    $sql = substr($sql, 0, -1);
    $this->query($sql, $data);
    return true;
  }
}
$_BUS = new Bus();
