<?php
include 'includes/db_connection.php';
include 'includes/htmlheader.php';
if (!isset($_SESSION)) {
    session_start();
}
?>
<div class="login-container" style="width:400px">
<h4>Channel Details:</h4>
<br><br>
<div class="row">
	<div class="col-sm-6 col-md-6 col-lg-6 col-xs-6 ">
	<p>Channel Name :</p>
	</div>
	<div class="col-sm-6 col-md-6 col-lg-6 col-xs-6 channel-name">	
	</div>
</div>

<div class="row">
	<div class="col-sm-6 col-md-6 col-lg-6 col-xs-6">
		<p>Channel Type :</p>
	</div>
	<div class="col-sm-6 col-md-6 col-lg-6 col-xs-6 channel-type">	
	</div>
</div>
<br><br>
<?php if($_SESSION['sess_user']== 'admin'){
	echo "<div class='row'><button class='change-membership'>Change Membership</button></div>";
}
?>
<br><br>
<div class="row">
<div class="ui-widget">
  <label for="tags">Invite people to join this channel: </label>
  <input id="tags" size="50" style="border: 1px solid;"><br><br>
  <input id="invite" value="Invite" type="submit"/><br><br>
  <input id="add" value="Add user directly" type="submit"/>
</div>

</div>
<br><br>
<div class="row">
<div class="ui-widget">
  <label for="tagsR">Remove people from this channel: </label>
  <input id="tagsR" size="50" style="border: 1px solid;"><br><br>
   <input id="remove" value="Remove" type="submit">
</div>

</div>
</div>

   <script type="text/javascript">
    var availableTags = [];
    var uninvitedlist = '';
    var availableTagsR = [];
    var joinedlist = '';
    var addList='';
    var removeList='';
    var chDetails=location.search.substring(11,location.search.length);
    function setData(chDetails) {
        $.ajax({
            type: 'GET',
            url: 'viewChannelDetailData.php?chDetails=' + chDetails,
            data: '',
            success: function(response) {
                console.log(response);
                $.each( response['channelDetail'], function( key, channel ) {
                         console.log(channel);
                         $('.channel-name').html(channel['channel_name']);
                         $('.channel-type').html(channel['channel_type']);
                         if(channel['channel_type']=='public'){
                         	$('.change-membership').html('');
                         }
                         uninvitedlist=channel['uninvited'];
                         joinedlist=channel['joined'];
                         console.log(joinedlist);
                         if(channel['channel_name'] == 'general' || channel['channel_name'] == 'random'){
                         	$('.ui-widget').html('');
                         	$('.ui-widget').append('<p>No invite need for this channel.</p>');
                         }
                
            })
                	function stringToArray(uninvitedlist){
                	if(uninvitedlist.indexOf(',') != -1){
                			if(uninvitedlist.indexOf(',')==0){
                				uninvitedlist = uninvitedlist.slice(uninvitedlist.indexOf(',')+1,uninvitedlist.length);
                			}
                			else{
                				availableTags.push(uninvitedlist.substring(0,uninvitedlist.indexOf(',')));
                				uninvitedlist = uninvitedlist.slice(uninvitedlist.indexOf(',')+1,uninvitedlist.length);
                			}
                			stringToArray(uninvitedlist);
                	}else if(uninvitedlist.length > 1){
                			availableTags.push(uninvitedlist.substring(0,uninvitedlist.length));
                	}
                }
                	stringToArray(uninvitedlist);
                	console.log(availableTags);
                    $( function() {
					    

					    function split( val ) {
					      return val.split( /,\s*/ );
					    }
					    function extractLast( term ) {
					      return split( term ).pop();
					    }
					 
					    $( "#tags" )
					      // don't navigate away from the field on tab when selecting an item
					      .on( "keydown", function( event ) {
					        if ( event.keyCode === $.ui.keyCode.TAB &&
					            $( this ).autocomplete( "instance" ).menu.active ) {
					          event.preventDefault();
					        }
					      })
					      .autocomplete({
					        minLength: 0,
					        source: function( request, response ) {
					          // delegate back to autocomplete, but extract the last term
					          response( $.ui.autocomplete.filter(
					            availableTags, extractLast( request.term ) ) );
					        },
					        focus: function() {
					          // prevent value inserted on focus
					          return false;
					        },
					        select: function( event, ui ) {
					          var terms = split( this.value );
					          // remove the current input
					          terms.pop();
					          // add the selected item
					          terms.push( ui.item.value );
					          // add placeholder to get the comma-and-space at the end
					          terms.push( "" );
					          this.value = terms.join( ", " );
					          addList=this.value;
					          return false;
					        }
					      });
					  } );


                    function stringToArrayR(joinedlist){
                	if(joinedlist.indexOf(',') != -1){
                			if(joinedlist.indexOf(',')==0){
                				joinedlist = joinedlist.slice(joinedlist.indexOf(',')+1,joinedlist.length);
                			}
                			else{
                				availableTagsR.push(joinedlist.substring(0,joinedlist.indexOf(',')));
                				joinedlist = joinedlist.slice(joinedlist.indexOf(',')+1,joinedlist.length);
                			}
                			stringToArrayR(joinedlist);
                	}else if(joinedlist.length > 1){
                			availableTagsR.push(joinedlist.substring(0,joinedlist.length));
                	}
                }
                	stringToArrayR(joinedlist);
                	console.log(availableTagsR);
                    $( function() {
					    

					    function split( val ) {
					      return val.split( /,\s*/ );
					    }
					    function extractLast( term ) {
					      return split( term ).pop();
					    }
					 
					    $( "#tagsR" )
					      // don't navigate away from the field on tab when selecting an item
					      .on( "keydown", function( event ) {
					        if ( event.keyCode === $.ui.keyCode.TAB &&
					            $( this ).autocomplete( "instance" ).menu.active ) {
					          event.preventDefault();
					        }
					      })
					      .autocomplete({
					        minLength: 0,
					        source: function( request, response ) {
					          // delegate back to autocomplete, but extract the last term
					          response( $.ui.autocomplete.filter(
					            availableTagsR, extractLast( request.term ) ) );
					        },
					        focus: function() {
					          // prevent value inserted on focus
					          return false;
					        },
					        select: function( event, ui ) {
					          var terms = split( this.value );
					          // remove the current input
					          terms.pop();
					          // add the selected item
					          terms.push( ui.item.value );
					          // add placeholder to get the comma-and-space at the end
					          terms.push( "" );
					          this.value = terms.join( ", " );
					          removeList=this.value;
					          return false;
					        }
					      });
					  } );

    }
    });
   }
   $('#add').click(function() {
   		console.log(addList);
   		$.ajax({
                type: 'GET',
                url: 'viewChannelDetailData.php?chDetails=' + chDetails,
                data: { addList: addList },
                success: function(response) {
                    console.log({ addList: addList });
                    return { addList: addList };
                }
            });
   	});
   $('#invite').click(function() {
   		console.log(addList);
   		$.ajax({
                type: 'GET',
                url: 'viewChannelDetailData.php?chDetails=' + chDetails,
                data: { inviteList: addList },
                success: function(response) {
                    console.log({ inviteList: addList });
                    return { inviteList: addList };
                }
            });
   	});
   $('#remove').click(function() {
   		console.log(removeList);
   		$.ajax({
                type: 'GET',
                url: 'viewChannelDetailData.php?chDetails=' + chDetails,
                data: { removeList: removeList },
                success: function(response) {
                    console.log({ removeList: removeList });
                    return { removeList: removeList };
                }
            });
   	});
   
   console.log(location.search.substring(11,location.search.length));
   setData(location.search.substring(11,location.search.length));


 

    </script>