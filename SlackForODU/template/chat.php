    <?php
    include 'includes/db_connection.php';
    include 'includes/functions.php';
    ?>
    <?php
    //if($cname!=''){
    //    $cname=$_SESSION['sess_user'];
    //}
    $chats = array();
    $channelObject = array();
    if($_SESSION['sess_user']){
        if($channelSelected != ''){

        $query="SELECT * FROM channel WHERE channel_name='".$channelSelected."'";
        $result= $connection->query($query);
        //echo $numrows;
        if($result-> num_rows>0)
        {
        while($row=$result->fetch_assoc())
        {
        $channel_idSelected=$row['channel_id'];
    //	$msg=$row['msg_body'];
    ////    $cdate=new DateTime($row['create_date']);
    ////    $displayDate=date_format($cdate, 'h:i');
    //    array_push($chats, $row);
        }

        } else {
    //	echo "No message yet.";
       // header("Location:wklogin.php");
        }    

        $query="SELECT * FROM message WHERE channel_id='".$channel_idSelected."'";
        $result= $connection->query($query);
        $chats = array();   
        if($result-> num_rows>0)
        {
        while($row=$result->fetch_assoc())
        {
    //	$currentThread=$row['thread_id'];
    //	$msg=$row['msg_body'];
    //    $cdate=new DateTime($row['create_date']);
    //    $displayDate=date_format($cdate, 'h:i');
        array_push($chats, $row);
        }   

        } else {
    //	echo "No message yet.";
       // header("Location:wklogin.php");
        }
        }
        else{

        $query="SELECT * FROM message WHERE creator_id='".$cname."' and channel_id='' and recipient_id='".$_SESSION['sess_user']."'";
        $result= $connection->query($query);
        //echo $numrows;
        if($result-> num_rows>0)
        {
        while($row=$result->fetch_assoc())
        {
    //	$currentThread=$row['thread_id'];
    //	$msg=$row['msg_body'];
    //    $cdate=new DateTime($row['create_date']);
    //    $displayDate=date_format($cdate, 'h:i');
        array_push($chats, $row);
        }
        $query="SELECT * FROM message WHERE creator_id='".$_SESSION['sess_user']."' and channel_id='' and recipient_id='".$cname."'";
        $result= $connection->query($query);
        //echo $numrows;
        if($result-> num_rows>0)
        {
        while($row=$result->fetch_assoc())
        {
    //	$currentThread=$row['thread_id'];
    //	$msg=$row['msg_body'];
    //    $cdate=new DateTime($row['create_date']);
    //    $displayDate=date_format($cdate, 'h:i');
        array_push($chats, $row);
        }

        }
        } else {

        }

        }

      }
    ?>

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Droid+Sans:400,700">

        <div id="live-chat">

            <header class="clearfix">

                <a href="#" class="chat-close">x</a>

                <h4><?php 
                    if($channelSelected){
                        echo "#".$channelSelected;
                    }else{
                        echo ucwords($cname);
                    }
                     ?></h4>

                <span class="chat-message-counter">3</span>

            </header>

            <div class="chat">

                <div class="chat-history">

                     <?php
                    $prevDate='';
                    usort($chats, function($a, $b) {
                        return strtotime($a['create_date']) - strtotime($b['create_date']);
                    });
                    foreach ($chats as $value) {
                        $crfdate=date_format(new DateTime($value['create_date']),'l, F j, Y');
                        $crdate=date_format(new DateTime($value['create_date']),'g:i a');
                        ?>
                    <center><?php 
                        if(strcmp($crfdate, $prevDate)>0){
                        echo $crfdate;
                        $prevDate=$crfdate;
                        }
                        ?></center>
                    <div class="chat-message clearfix">

                        <img src="../images/<?php echo $value['profile_pic'] ?>" alt="profile pic" width="32" height="32">

                        <div class="chat-message-content clearfix">

                            <span class="chat-time"><?php echo $crdate ?></span>

                            <h5><?php echo ucwords($value['creator_id']) ?></h5>

                            <p><?php echo $value['msg_body'] ?></p>

                        </div> <!-- end chat-message-content -->

                    </div> <!-- end chat-message -->

                    <hr>
     <?php
                    }
                 ?>
                </div> <!-- end chat-history -->

    <!--			<p class="chat-feedback">Your partner is typing…</p>-->

                <form action="#" method="post">

                    <fieldset>
                        <div class="row">
                            <div class="col-sm-11 col-md-11 col-lg-11 col-xs-11">
                        <input  type="text" placeholder="Type your message…" name="message" autofocus>
                                  </div>
                                   <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1">
    					<input  type="submit" value="Send" class="btn" name="submit" />
<!--                               style="position: absolute; left: -9999px"-->
                            </div>
                            </div>
                    </fieldset>

                </form>

            </div> <!-- end chat -->

        </div> <!-- end live-chat -->

      <?php 
        if($_SESSION['sess_user']){
        if (isset($_POST['message'])){ 
        if($_POST['message']!=''){
        $message=verify_input($_POST['message']);
        $subject=$channelSelected;
        $creator_id=$_SESSION['sess_user'];
        //$thread_id='p'+$cname;
        if($cname){
         $channel_id='';
         $recipient_id=$cname;
        }else
        {
           $channel_id=$channel_idSelected;
           $recipient_id='';
        }
        $group_id='';
        $profile_pic=$_SESSION['sess_user_profile_pic'];

        $connection->query("insert into message (subject,creator_id,msg_body,create_date,channel_id,group_id,recipient_id,profile_pic)
        values('$subject','$creator_id','$message',NOW(),'$channel_id','$group_id','$recipient_id','$profile_pic')
        ")or die(mysql_error());
     $_POST['message']='';
    unset($_POST['message']);
    exit;
    } 
    }
    }else {
        echo "Something went wrong!";
    }
    mysqli_close($connection);
    ?>
