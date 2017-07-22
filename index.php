<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="index.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/css/materialize.min.css">
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/js/materialize.min.js"></script>
  <script>
    $(document).ready(function() {
      $('.modal-trigger').click(function() {
        $('#modal1').modal('open');
      })

      $('.btn-click').click(function(e) {
        var id = e.currentTarget.id;
        console.log('todo-content' + id);
        $.ajax({
          url: 'register.php',
          type: 'POST',
          data: {
            'id': String(id)
          }
        }).done(function() {
          console.log("success");
        }).fail(function(err) {
          console.log(err);
        })

        $('.todo-content' + id).fadeOut('slow');

      })
    });
  </script>
</head>
<body>
  <div class="container col lg12">
    <nav>
      <div class="nav-wrapper">
        <a href="#" class="brand-logo center title">TodoList</a>
        <ul id="nav-mobile" class="left hide-on-med-and-down">
          <li><a href="#modal1" class="a-tag modal-trigger">todo追加</a></li>
        </ul>
      </div>
    </nav>

    <div class="content">

    <?php
      $dsn = 'mysql:dbname=j5076;host=localhost';
      $user = 'root';
      $password='';
      try {
        $PDO = new PDO($dsn,$user,$password);
      }catch(PDOException $e){
        die("接続失敗".$e->getMessage());
      }

      if(!empty($_POST["id"])) {
        $id = intval($_POST["id"]);
        $stmt =$PDO->prepare("delete from todo where id = ?");
        $stmt -> bindValue(1,$id, PDO::PARAM_INT);
      }

      if(isset($_POST["submit"]) && !empty($_POST["title"]) && !empty($_POST["text"])) {
        $title = $_POST["title"];
        $text = $_POST["text"];
        $stmt =$PDO->prepare("insert into todo values (?, ?, null, null)");
        $stmt -> bindValue(1, $title, PDO::PARAM_STR);
        $stmt -> bindValue(2, $text, PDO::PARAM_STR);
        $stmt -> execute();
      }

      $stmt = $PDO -> prepare("select * from todo");
      $stmt -> execute();
      $rows = $stmt->fetchAll();
      foreach($rows as $row) {
        $title = $row["title"];
        $text = $row["text"];
        $id = $row["id"];
        echo "<div class='row todo-content$id'>";
        echo "<div class='col s12 m12 lg12'>";
        echo "<div class='card'>";
        echo "<div class='card-image'>";
        echo "<span class=''><h2 class='title'>$title</h2></span>";
        echo "<a id='$id' class='btn-floating halfway-fab waves-effect waves-light red btn-click'><i class='material-icons'>done</i></a>";
        echo "</div>";
        echo "<div class='card-content'>";
        echo "<p class='content-text'>$text</p>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
      }
    ?>

    <div id="modal1" class="modal bottom-sheet">
      <div class="modal-content">
        <form class="col lg12" action="index.php" method="POST">
          <div class="input-field col s6">
            <input id="last_name" type="text" name="title" class="validate">
            <label for="last_name">Add title</label>
          </div>
          <div class="input-field col s6">
            <input id="last_name" type="text" name="text" class="validate">
            <label for="last_name">Add text</label>
          </div>
          <input class="btn" type="submit" name="submit" value="送信">
        </form>
      </div>
    </div>

    </div>
  </div>
  <div class="footer">
    <a class="waves-effect waves-light btn modal-trigger" href="#modal1">Add Todo</a>
  </div>

</body>
</html>
