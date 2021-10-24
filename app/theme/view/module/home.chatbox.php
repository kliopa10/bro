<?php
session_start();

$_SESSION['name'] = $AuthUser['name'];
$_SESSION['type'] = $AuthUser['account_type'];
$_SESSION['username'] = $AuthUser['username'];

?>
<div class="app-section">
    <div class="app-heading">
        <div class="text">
            <?php echo $HomeModule['name'];?>
        </div>
    </div>
<!-- Chatbox Wrapper -->
<div id="wrapper" class="chatbox">
  <div id="menu">
    <?php if ($AuthUser['id']) { ?>
	<?php if($AuthUser['chatboxban'] == 1) { ?>
  	<p class="welcome"><?php echo __('You have been banned from using the chatbox for');?> <b><?php echo get($AuthUser,'data.chatboxbanreason'); ?></b>  
	<?php } else { ?>
    <p class="welcome">Welcome to the <?php echo $HomeModule['name'];?>, <b <?php if($_SESSION['type'] == 'admin') { ?>class="admin-name"<?php } ?>><?php echo $_SESSION['name']; ?></b>
    <?php } ?>
	<?php
      if (isset($_GET['delete'])) {
        unlink($_GET['delete']);
      }
    ?>
	<?php if($AuthUser['chatboxban'] == 1) { } else { ?>
    <?php if($AuthUser['account_type'] == 'admin') { ?>
      <a class="float-right" href="?delete=app/chatlogs/chatlog.html">(<?php echo __('Clear Chat');?>)</a></p>
    <?php } ?>    
    <?php } ?>
  </div>
  <?php if($AuthUser['chatboxban'] == 1) { } else { ?>
  <form name="message" action="">
    <input name="usermsg" type="text" id="usermsg" style="width:79%;padding:5px;border-radius:5px;border: 1px solid lightgrey" aria-label="<?php echo __('Enter Message');?>" />
    <input name="submitmsg" type="submit" id="submitmsg" value="Send" style="width:20%;padding:5px;border-radius:5px;border: 1px solid var(--theme-color);background-color: var(--theme-color);font-weight:bold;float:right" />
  </form>
  <?php } ?>   
  <?php } else { ?>    
    <p class="welcome"><?php echo __('Please');?> <a href="<?php echo APP; ?>/login" style="color:(--theme-color)"><?php echo __('Login');?></a> <?php echo __('or');?> <a href="<?php echo APP; ?>/register" style="color:(--theme-color)"><?php echo __('Register');?></a> <?php echo __('to use the chatbox');?></p>
  </div>      
  <?php } ?>
    <br>
  <div id="chatbox" style="overflow-y: scroll;height: 200px;padding-right:10px;margin-left:-1px;">
    <?php
      if(file_exists(APP."/app/chatlogs/chatlog.html") && filesize(APP."/app/chatlogs/chatlog.html") > 0){
        $contents = file_get_contents(APP."/app/chatlogs/chatlog.html");          
          echo $contents;
      }
    ?>
  </div>
</div>
</div>
<!-- Chatbox AJAX -->
<script type="text/javascript">
  // jQuery Document
  $(document).ready(function() {
      $("#submitmsg").click(function() {
          var clientmsg = $("#usermsg").val();
          $.post("app/controller/Ajax.Chat.php", {
              text: clientmsg
          });
          $("#usermsg").val("");
          return false;
      });

      function loadLog() {
          var oldscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Scroll height before the request
          $.ajax({
              url: "<?php echo APP; ?>/app/chatlogs/chatlog.html",
              cache: false,
              success: function(html) {
                  $("#chatbox").html(html); //Insert chat log into the #chatbox div
                  //Auto-scroll			
                  var newscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Scroll height after the request
                  if (newscrollHeight > oldscrollHeight) {
                      $("#chatbox").animate({
                          scrollTop: newscrollHeight
                      }, 'normal'); //Autoscroll to bottom of div
                  }
              }
          });
      }
      setInterval(loadLog, 2500);
  });
</script>
