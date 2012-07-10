<?php session_start();?>
<html>
<head>
      
        <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

        <link href="../../../css/style.css" rel="stylesheet" type="text/css"/>
        <link href="../../../css/jquery-ui.css" rel="stylesheet" type="text/css"/>  
        <link rel="stylesheet" type="text/css" href="../../../css/jquery.validate.css" />
        <link rel="stylesheet" type="text/css" href="../../../css/style1.css" />

        

        <script src="../../../js/jquery.validate.js" type="text/javascript"></script>
        <script src="../../../js/formValidation.js" type="text/javascript"></script>
        
           
<style type="text/css" title="currentStyle">
        @import "../../../css/demo_page.css";
        @import "../../../css/jquery.datatables.messages.css";
</style>
<script type="text/javascript" language="javascript" src="../../../js/jquery.datatables.messages.js"></script>

        <style type="text/css">
            #unreadmsgs{
    padding: 4px;
    margin: 0px;
    margin-right: 200px;
    margin-left: 220px;
    padding-top: 40px;
            }
#readmsgs{
    padding: 4px;
    margin: 0px;
    margin-right: 200px;
    margin-left: 220px;
    padding-top: 40px;
}

        </style>
        <script type="text/javascript">

        
            window.onload= function(){
                
               usern= '<?php echo($username) ?>'; }
            
            
function fnFormatDetails ( oTable, nTr )

{ 
   
   //alert(array2);
    var aData = oTable.fnGetData( nTr );
    var sOut = '<table cellspacing="0" border="0" style="padding-left:50px; background:lightgray">';
    sOut += '<tr><td>From:</td><td>'+aData[1]+'</td></tr>';
    sOut += '<tr><td style= "border-top: 1px solid black; ">Message:</td><td style= "border-top: 1px solid black; "><pre><strong>'+aData[5]+'<strong></pre></td></tr>';
    
    sOut += '</table>';
   
    return sOut;
}
 
$(document).ready(function() {
    /*
     * Insert a 'details' column to the table
     */
    $('#urm').show();
    $('#rm').show();
    
    var nCloneTh = document.createElement( 'th' );
    var nCloneTd = document.createElement( 'td' );
    nCloneTd.innerHTML = '<a style="cursor:pointer;" class="view"><u>View</u></a>';
    nCloneTd.className = "center";
     
//    $('#unreadmsgs_table thead tr').each( function () {
//        this.insertBefore( nCloneTh, this.childNodes[0] );
//    } );
     
    $('#unreadmsgs_table tbody tr').each( function () {
        this.insertBefore(  nCloneTd.cloneNode( true ), this.childNodes[0] );
    } );
    $('#readmsgs_table tbody tr').each( function () {
        this.insertBefore(  nCloneTd.cloneNode( true ), this.childNodes[0] );
    } );
     
    /*
     * Initialse DataTables, with no sorting on the 'details' column
     */
    var view_unread_row = null;
    var view_read_row = null;
    var oTable = $('#unreadmsgs_table').dataTable( {
       
        "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ 0 ] },{ "bSortable": false, "aTargets": [ 4 ] },{ "bSortable": false, "aTargets": [ 7 ] },{ "bVisible": false, "aTargets": [5] },{ "bVisible": false, "aTargets": [6] }
        ],
        "aaSorting": [[6, 'desc']],
         "bScrollCollapse": true,
        "bJQueryUI": true,
        "tableTitle":'Unread Messages',

        "bAutoWidth": false, "aoColumns" : [
        {
            sWidth: '30px'
        },
        {
            sWidth: '30px'
        },
        {
            sWidth: '70px'
        },
           
        {
            sWidth: '100px'
        },
        {
            sWidth: '30px'
        },
        {
            sWidth: '30px'
        },{
            sWidth: '30px'
        },{
            sWidth: '30px'
        }
        ]     
    });
    
    var oTable1 = $('#readmsgs_table').dataTable( {
        "aoColumnDefs": [
             { "bSortable": false, "aTargets": [ 0 ] },{ "bSortable": false, "aTargets": [ 4 ] },{ "bSortable": false, "aTargets": [ 7 ] },{ "bVisible": false, "aTargets": [5] },{ "bVisible": false, "aTargets": [6] }
        ],
        "aaSorting": [[6, 'desc']],
         "bScrollCollapse": true,
        "bJQueryUI": true,
       
       "bAutoWidth": false, "aoColumns" : [
     {
            sWidth: '30px'
        },
        {
            sWidth: '30px'
        },
        {
            sWidth: '70px'
        },
           
        {
            sWidth: '100px'
        },
        {
            sWidth: '30px'
        },
        {
            sWidth: '30px'
        },{
            sWidth: '30px'
        },{
            sWidth: '30px'
        }
        ]    
    });
     
    /* Add event listener for opening and closing details
     * Note that the indicator for showing which row is open is not controlled by DataTables,
     * rather it is done here
     */
    $('#unreadmsgs_table tbody td a.view').live('click', function () {
        var nTr = $(this).parents('tr')[0];
        if ( view_unread_row !== null && view_unread_row != nTr ) {
            var aData = oTable.fnGetData( nTr );
            /* A different row is being edited - the edit should be cancelled and this row edited */
            restoreRow( oTable, view_unread_row );
             this.innerHTML= '<a style="cursor:pointer;"><u>Close</u></a>';
            oTable.fnOpen( nTr, fnFormatDetails(oTable, nTr), 'details' );
            oTable.fnClose( view_unread_row );
            view_unread_row = nTr;
            $.ajax({
            type:"POST",
            url:"http://localhost/gs/php/index.php/message/updatenotif",
            data:{name:usern, timestamp:aData[6]},
            success: function(response)
            {
                location.reload(true);
            }
        });
            
            
        }
        else if ( oTable.fnIsOpen(nTr) )
        {
            /* This row is already open - close it */
            view_unread_row=null;
            this.innerHTML= '<a style="cursor:pointer;"><u>View</u></a>';
            var aData = oTable.fnGetData( nTr );
            
        
      
        $.ajax({
            type:"POST",
            url:"http://localhost/gs/php/index.php/message/updatenotif",
            data:{name:usern, timestamp:aData[6]},
            success: function(response)
            {
                     
            
            location.reload(true);
    
            }
        });
            
            
        }
        else
        {
          view_unread_row=nTr;
            /* Open this row */
           this.innerHTML= '<a style="cursor:pointer;"><u>Close</u></a>';
            oTable.fnOpen( nTr, fnFormatDetails(oTable, nTr), 'details' );
        }
    } );
    $('#unreadmsgs_table tbody td a.delete').live('click', function () {
    var nTr = $(this).parents('tr')[0];
   // alert(nTr);
      
      var aData = oTable.fnGetData( nTr );
      //alert(aData[6]);
      
        $.ajax({
            type:"POST",
            url:"http://localhost/gs/php/index.php/message/deletenotif",
            data:{name:usern, timestamp:aData[6]},
            success: function(response)
            {
                     
            
    
            }
         
        });

         oTable.fnDeleteRow( nTr );
         location.reload(true); 
    });
        $('#unreadmsgs_table a.reply').live('click', function (e) {
        e.preventDefault();
     
          var nTr = $(this).parents('tr')[0];
   // alert(nTr);
      
      var aData = oTable.fnGetData( nTr );
      
        $('#element_1').val(aData[1]);
        
        var x = aData[5];
        var when = aData[3];
        var who = aData[1];
       x="\n\n---------------------------------------------------------"+"\n On "+when+", " + who + " wrote: "+ "\n\n"  +x;
      
        $('#element_3').val(x);
        $("#reply").dialog('open');
        

    });
            $('#readmsgs_table a.reply').live('click', function (e) {
        e.preventDefault();
     
          var nTr = $(this).parents('tr')[0];
   // alert(nTr);
      
      var aData = oTable1.fnGetData( nTr );
      
        $('#element_1').val(aData[1]);
        
        var x = aData[5];
        var when = aData[3];
        var who = aData[1];
       x="\n\n---------------------------------------------------------"+"\n On "+when+", " + who + " wrote: "+ "\n\n"  +x;
      
        $('#element_3').val(x);
        $("#reply").dialog('open');
        

    });
        $( "#reply" ).dialog({
        
        open: function(event, ui) { $('#element_3').focus();},

        autoOpen: false,
        height: 620,
        width: 620,
        modal: true,
                        
        buttons: {
            "Send": function() {
                var bValid = true;
                 var from = document.getElementById("element_1").value;
                 var text = document.getElementById("element_3").value;
                //write validations
                                         
                                                                                 
                if ( bValid ) {
                    //code to send a reply
                        $.ajax({
                          type:"POST",
                          url:"http://localhost/gs/php/index.php/message/sendreply",
                           data:{username:from, type:"reply" , from:usern ,text:text},
                           success: function(response)
                           {
                            location.reload(true);
    
                             }
                        });
                                             
                                         
                }
                                     
                                         
                                         
            },
            Cancel: function() {
                
                 $('#element_3').val("");
                  $('#element_1').val("");
                $( this ).dialog( "close" );
                                        
            }
        },
        close: function() {
            $('#element_3').val("");
                  $('#element_1').val("");
            $( this ).dialog( "close" );
        }
    }); 
    
    $('#readmsgs_table tbody td a.view').live('click', function () {
        var nTr = $(this).parents('tr')[0];
        
         if ( view_read_row !== null && view_read_row != nTr ) {
             
            var aData = oTable1.fnGetData( nTr );
            /* A different row is being edited - the edit should be cancelled and this row edited */
            restoreRow( oTable1, view_read_row );
             this.innerHTML= '<a style="cursor:pointer;"><u>Close</u></a>';
            oTable1.fnOpen( nTr, fnFormatDetails(oTable1, nTr), 'details' );
            oTable1.fnClose( view_read_row );
            view_read_row = nTr;
         
            
        }
        else if ( oTable1.fnIsOpen(nTr) )
        {
            /* This row is already open - close it */
           
           
            this.innerHTML= '<a style="cursor:pointer;"><u>View</u></a>';
            oTable1.fnClose( nTr );
             view_read_row=null;
            
            
        }
        else
        {
            /* Open this row */
            
            view_read_row=nTr;
           this.innerHTML= '<a style="cursor:pointer;"><u>Close</u></a>';
            oTable1.fnOpen( nTr, fnFormatDetails(oTable1, nTr), 'details' );
        }
    } );
        $('#readmsgs_table tbody td a.delete').live('click', function () {
    var nTr = $(this).parents('tr')[0];
    
      var aData = oTable1.fnGetData( nTr );
   
      
        $.ajax({
            type:"POST",
            url:"http://localhost/gs/php/index.php/message/deletenotif_read",
            data:{name:usern, timestamp:aData[6]},
            success: function(response)
            {
                     
            
    
            }
         
        });
//      var updateQuery = 'https://dev-gardenshift.rhcloud.com/Gardenshift/delete_notification_unread/'+usern+'/'+aData[6];
//      alert(updateQuery);
//       $.get(updateQuery);
         oTable1.fnDeleteRow( nTr );
         location.reload(true); 
    });
    

} );
function restoreRow ( oTable, nRow )
{
    var aData = oTable.fnGetData(nRow);
    var jqTds = $('>td', nRow);
	
    for ( var i=0, iLen=jqTds.length ; i<iLen ; i++ ) {
        oTable.fnUpdate( aData[i], nRow, i, false );
    }
	
    oTable.fnDraw();
}     
    </script>
</head>
<body>
        

     
     <div id="unreadmsgs">
                <div id ="urm"><strong>Unread Messages</strong></div>
                <table id="unreadmsgs_table" class="display" style="width: 1200px; background-color:lightgray;">
                    <thead>
                        <tr><th style="width: 20px; ">view</th>
                            <th style="width: 20px; ">From</th>
                            <th style="width: 20px; ">Message</th>
                            <th >Date</th>
                            
                            <th>Delete</th>
                            <th style="width: 20px; visibility:hidden">data</th>
                            <th style="width: 20px; visibility:hidden">data1</th>
                            <th>Reply</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
//                        global $stack;
//                        $stack = array();
                        ?>
                        <?php
                        date_default_timezone_set('America/New_York');
                        for ($c = 0; $c < count($unreadmsgs); $c++) {
                            echo '<tr>';

                         
                           
                            echo '<td align ="center">' . $unreadmsgs[$c]->{'from'} . '</td>';
                            echo '<td align ="center">' . substr($unreadmsgs[$c]->{'text'} , 0, 10).'... '.'</td>';
                            $date = date("D, d M Y", $unreadmsgs[$c]->{'timestamp'}/1000 );
                            $time = date("H:i:s", $unreadmsgs[$c]->{'timestamp'}/1000 );
                            echo '<td align ="center">' . $date .' at '.$time . '</td>';
                           echo '<td align ="center" ><a class="delete" href="">Delete</a></td>';
                           echo '<td align ="center">' . $unreadmsgs[$c]->{'text'}.'</td>';
                           echo '<td align ="center">' . $unreadmsgs[$c]->{'timestamp'}.'</td>';
                        echo '<td align ="center" ><a class="reply" href="">Reply</a></td>';
                           

                            echo '</tr>';
                        }
                        ?>
                    </tbody>

                </table>                

            </div>
    
         <div id="readmsgs">
                <div id ="rm"><strong>Read Messages</strong></div>
                <table id="readmsgs_table" class="display" style="width: 1200px; background-color:lightgray;">
                    <thead>
                        <tr>
                            <th style="width: 20px; ">view</th>
                            <th style="width: 20px; ">From</th>
                            <th style="width: 20px; ">Message</th>
                            <th style="width: 20px; ">Date</th>

                            <th>Delete</th>
                            <th style="width: 20px; visibility:hidden">data</th>
                            <th style="width: 20px; visibility:hidden">data1</th>
                            <th>Reply</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        //global $stack;
                        //$stack = array();
                        ?>
                        <?php
                        date_default_timezone_set('America/New_York');
                        for ($c = 0; $c < count($readmsgs); $c++) {
                            echo '<tr>';

//                            array_push($stack, $usercrops[$c]->{'crop_name'});

                            echo '<td align ="center">' . $readmsgs[$c]->{'from'} . '</td>';
                           echo '<td align ="center">' . substr($readmsgs[$c]->{'text'} , 0, 10).'... '.'</td>';
                            $date = date("D, d M Y", $readmsgs[$c]->{'timestamp'}/1000 );
                            $time = date("H:i:s", $readmsgs[$c]->{'timestamp'}/1000 );
                            echo '<td align ="center">' . $date .' at '.$time . '</td>';

                            echo '<td align ="center"><a class="delete" href="">Delete</a></td>';
                             echo '<td align ="center">' . $readmsgs[$c]->{'text'}.'</td>';
                           echo '<td align ="center">' . $readmsgs[$c]->{'timestamp'}.'</td>';
                           echo '<td align ="center"><a class="reply" href="">Reply</a></td>';
                           

                            echo '</tr>';
                        }
                        ?>
                    </tbody>

                </table>                

            </div>
    <div id="reply">
                    <form id="replyform" class="appnitro"  method="post" action="">
                <div class="form_description">
                    <h2>Reply</h2>
                    <p>Please type in your message and click send.</p>
                </div>
                <table>

                    <tr><td>
                            <label class="description" for="element_1">To:</label>
                        </td><td>
                            <input id="element_1" name="element_1" class="element text medium" type="text" maxlength="255" value="" readonly="readonly"/> 

                        </td></tr>		
                    <tr><td>
                            <label class="description" for="element_3" value="">Message: </label>
                        </td><td>

                            <TEXTAREA NAME="comments" COLS=50 ROWS=12 id="element_3" name="element_3" class="element text medium" value=""></TEXTAREA> 
                        </td></tr></table>
            </form>
    </div>
</body>
</html>