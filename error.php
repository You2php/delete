<?php
header("HTTP/1.0 404 Not Found");
$headtitle='Fehler!';
include("./header.php");?>

<div class="container-fluid" style="height: 480px;
    background-color: #dbdbdb;">
    <div class="container" style="height: 100%">
        <div class="row" style="height: 100%">
 <div class="col-12 justify-content-center align-self-center text-center">
     <img src="//wx3.sinaimg.cn/large/b0738b0agy1fm04l0cw4ej203w02s0sl.jpg" class="p-2" >
      <h2>Der angeforderte Inhalt existiert nicht!</h2>
      <p>Entschuldigung, der von dir angeforderte Inhalt wurde nicht gerendert!</p>
	  <p>Mögliche Ursachen: </p>
      <p>1. Die von Ihnen eingegebene Linkadresse ist falsch! </p>
      <p>2. Video ist urheberrechtlich geschützter Inhalt (Diese Seite kann urheberrechtlich geschützten Inhalt nicht auflösen!) </p>
      <p>3. Dieses Video existiert nicht. </p>
      <p>4. Webserverfehler. </p>
  </div>

  </div>
    </div>
  
</div>


<?php
include("./footer.php"); 
?>