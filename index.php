<!DOCTYPE html>
<html>
<head>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Remote Keyboard</title>
    <style>
        .keyboard {
            display: grid;
            grid-template-columns: repeat(5, 50px);
            grid-template-rows: repeat(2, 50px);
        }

        .key {
            width: 50px;
            height: 50px;
            background-color: white;
            border: 1px solid black;
        }
    </style>
</head>
<body>
<?php
        $user = isset($_GET['user']) ? intval($_GET['user']) : 0;
        $color = isset($_GET['color']) ? $_GET['color'] : 'white';
        $hasControl = hasControl($user);
    ?>
    <div class="keyboard">
        <div class="key" data-key="1">1</div>
        <div class="key" data-key="2">2</div>
        <div class="key" data-key="3">3</div>
        <div class="key" data-key="4">4</div>
        <div class="key" data-key="5">5</div>
        <div class="key" data-key="6">6</div>
        <div class="key" data-key="7">7</div>
        <div class="key" data-key="8">8</div>
        <div class="key" data-key="9">9</div>
        <div class="key" data-key="10">10</div>
    </div>
    </div>
    <div class="keyboard_users"></div>
    <?php
          if ($hasControl && $hasControl === null) { ?>
        <button id="controlButton" data-user="<?php echo $user; ?>">Take Control</button>
    <?php } else { ?>
        <div>
            <label for="userId">Enter Your User ID:</label>
            <!-- <input type="text" id="userId" name="userId"/> -->
            <button id="controlButton">Take Control</button>
        </div>
    <?php }
    
    function hasControl($user) {
      $hasControl = null;
      $controlHolder = null;
    }
    
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="style.js"></script>
    <script>
        var currentUser = <?php echo isset($_GET['user']) ? intval($_GET['user']) : 0; ?>;
        var controlTimeout = null;
            // alert(currentUser);
        $(document).ready(function () {
            $('.key').click(function () {
                var keyNumber = $(this).data('key');
                toggleKey(keyNumber);
            });

            $('#controlButton').click(function () {

                // if (hasControl || controlHolder === null) {
                //     acquireControl();
                // } else {
                    var userId = prompt("Enter Your User ID:");
                    if (userId !== null && userId.trim() !== '') {
                        currentUser = parseInt(userId);
                        acquireControl();
                        // console.log(currentUser);
                    }
                // }
            });

            setInterval(updateKeyboard, 1000);
        });

        function toggleKey(keyNumber) {
          var $keyElement = $('[data-key="' + keyNumber + '"]');
          var currentColor = $keyElement.css('background-color');
          
          if (currentColor === 'rgb(255, 255, 255)') {
              var color = (currentUser === 1) ? 'red' : 'yellow';
              $keyElement.css('background-color', color);
              insertrecord(color,keyNumber);
          } else {
              $keyElement.css('background-color', 'white');
          }
        }
        var controlHolder = null;
        var controlTimeout = null;

        function acquireControl() {
            $.ajax({
                url: 'control.php',
                method: 'POST',
                data: { user: currentUser },
                success: function (response) {
                  // console.log(response);
                    clearTimeout(controlTimeout); // Clear the previous timeout
                    controlTimeout = setTimeout(releaseControl, 120000); // Set a new timeout
                },
                error: function (xhr, status, error) {
                    console.error("Error acquiring control:", xhr.responseText);
                }
            });
        }
        function insertrecord(color,keyNumber){
          var userids = <?php echo $_GET['user']; ?>;

          $.ajax({
                url: 'control.php',
                method: 'POST',
                data: { 'user': userids, 'color' : color, 'keyNumber' : keyNumber, 'type' : 'insert'},
                success: function (response) {
                    controlTimeout = null;
                    // $(".keyboard").hide();
                    $(".keyboard_users").show();
                    // console.log(response);
                },
                error: function (xhr, status, error) {
                    console.error("Error releasing control:", xhr.responseText);
                }
            });
        }
        function releaseControl() {
            $.ajax({
                url: 'control.php',
                method: 'GET',
                data: { user: currentUser },
                success: function (response) {
                    controlTimeout = null;
                },
                error: function (xhr, status, error) {
                    console.error("Error releasing control:", xhr.responseText);
                }
            });
        }

        function updateKeyboard() {

        }
    </script>
    
</body>
</html>
