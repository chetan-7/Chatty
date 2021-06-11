<?php
  $name=$_POST['name'];
  $group=$_POST['group'];
?>

<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="style2.css">
<script src="https://www.gstatic.com/firebasejs/8.6.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.6.1/firebase-database.js"></script>
<script type="text/javascript" src="firebase.js"></script>
<script type="text/javascript">
    var myName="<?php echo $name ?>";
    var myGroup="<?php echo $group ?>";

    function sendMessage(){
      var message=document.getElementById("message").value;
      firebase.database().ref("message/"+myGroup).push().set({
        "group":myGroup,
        "sender":myName,
        "message":message
      });
      document.getElementById("message").value="";
      return false;
    }

    firebase.database().ref("message/"+myGroup).on("child_added",function(snapshot){
        var html="";
        html+="<li id='message-"+snapshot.key+"'>";
        html += "<span id='name'>"+snapshot.val().sender+"</span>$: "+snapshot.val().message;
        //show delete button if message is sent by me.
        if(snapshot.val().sender==myName){
          html+="    <button class='btn' data-id='"+snapshot.key+"'onclick='deleteMessage(this);'>";
          html += "&#10006;"
          html+="</button>";
        }
         html+="</li>";
        document.getElementById("messages").innerHTML+=html;
      });

    function deleteMessage(self){
        //get message Id.
        var messageId=self.getAttribute("data-id");

        //delete message
        firebase.database().ref("message/"+myGroup).child(messageId).remove();
    }
    firebase.database().ref("message/"+myGroup).on("child_removed",function(snapshot){
          document.getElementById("message-"+snapshot.key).innerHTML="This message has been deleted.";
        });


</script>
<div id="header1">
  <button onClick="document.location.href='index.php'" style="padding-left:280px" class="btn">&#10006;</button>
  <p><span id="rinfo">Room info$: <?php echo $group ?></span></p>
</div>

<form onsubmit="return sendMessage()">
  <label>Type Message ~ </label>
  <input type="text" class="input" id="message">
  <!--<input type="submit">-->
</form>
<ul style="list-style-type:none;" id="messages"></ul>


