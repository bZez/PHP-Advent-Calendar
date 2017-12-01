<link rel="stylesheet" type="text/css" href="css/main.css">
<link href="https://fonts.googleapis.com/css?family=Mountains+of+Christmas" rel="stylesheet">
<div class="container">
  <?php
  //SET TIMEDATE's
  $now = new DateTime('Today');
  $year = date('Y');
  $xmas = new Datetime($year.'-12-25'); //X-MAS DATE
  $waitingDays = $now->diff($xmas)->days-25; //DAYS UNTIL CALENDAR ACTIVATION
  $daysUntilXmas = $now->diff($xmas)->days; //DAYS UNTIL X-MAS

  //IF 25 DAYS LEFT UNTIL X-MAS, ACTIVATING CALENDAR
  if ($waitingDays < 0) {
    session_start();
    //RESET
    echo '<a href=index.php?reset class="reset">Reset</a><br>';
    if(isset($_GET['reset'])) {
      session_destroy();
      header('Location:index.php');
    }

    //GET DAY
    if (isset($_GET['day'])) {
      $_SESSION['days'][$_GET['day']] = 'opened';
      if (isset($_GET['gift'])) {
        $giftsList = file_get_contents('gifts.json');
        $gifts = json_decode($giftsList);
        $gift= $gifts->list;
        $giftslist = array();
        foreach ($gift as $g) {
        $giftslist[] = $g->gift;
        }
        ?>

        <div class="gift">
          <div class="giftImg"></div>
          <a href=index.php>Back</a>
          <?php if($_GET['day'] === 25){ echo 'XMASSSSSSSS'; } ?>
          <p>You win:</p><br>
          <?php
          echo '<div class="giftText">'.$giftslist[rand(0,24)].'</div>';
           ?>
          <span><?php echo $_GET['day'] ?></span>

        </div>

        <?php
      }
    }

    //CHECK AND SET CONTENT
    function setContent($day) {
      $today = new DateTime('Today');
      $today = $today->diff(new Datetime(date('Y').'-11-30'))->days;
      for ($i=1;$i < $today;$i++){
        $_SESSION['days'][$i] = 'opened';
      }
      //IF DAY PASSED (OPENED)
      if (isset($_SESSION['days'][$day])) {
        echo '<div class="day opened">'.$day.'</div>';
      }

      //IF X-MAS DAY (25) & IF TODAY IS X-MAS DAY
      else if ($day === 25){
        if ($day === 25 && $day === $today){
          echo '<a href=index.php?day='.$day.'&gift><div class="day xmas active">YEAH !</div></a>';
        } else {
          echo '<div class="day xmas">x-mas</div>';
        }
      }

      //ELSE IF TODAY (SET ACTIVE)
      else if($day === $today) {
        echo '<a href=index.php?day='.$day.'&gift><div class="day closed active">'.$day.'</div></a>';
      }

      //ELSE
      else {
        echo '<div class="day closed">'.$day.'</div>';
      }
    };

    //DISPLAY DAYS IN ARRAY
    function generate() {
      $days = array();
      for($i=1;$i<26;$i++){
        $days[] = setContent($i);
      } return $days;
    }

    //DO EVERYTHING
    generate();

  }
  //ELSE DISPLAY WAITING MESSAGE
  else {
    echo '<div class="sorry">Sorry you must wait <span style="color:white;">'.$waitingDays.' day(s)</span> to see the advent calendar dude ! </div>';
  }

  ?>
  <div class="dayleft">
    <?php
  echo $daysUntilXmas.' day(s) left until X-MAS !';
    ?>
  </div>
</div><br>
