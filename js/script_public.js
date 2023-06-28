/**
 * Script ( Public Area )
 *
 * @author  Shahzaib
 * @version 1.4
 */

"use strict";

$( function ()
{
  // On the page load, move the scroll down of that box
  // where the chat messages are displayed:
  chatScrollDown( '#chat-messages' );
  
  
  // Chat box hide and show handling:
  $( '#chat-toggle' ).on( 'click', function()
  {
    $( this ).removeClass( 'bg-danger' );
    
    $( '#chat-box' ).fadeToggle( 'fast' );
    
    chatScrollDown( '#chat-messages' );
  });
  
  
  // Check for the active chat, if exists, then send the ajax
  // request(s) to the server for the real time experience:
  if ( $.isFunction( $.cookie ) )
  {
    if ( typeof chatCookie !== 'undefined' && typeof liveChattingStatus !== 'undefined' && typeof proceedChat !== 'undefined' )
    {
      setInterval( function ()
      {
        if ( $.cookie( chatCookie ) != null && liveChattingStatus == 1 && proceedChat == 1 )
        {
          var source = $( '#chat-messages' ).attr( 'data-chat-action' );
          var lastReplyId = $( '.chat-message:last' ).attr( 'data-reply-id' );
          
          lastReplyId = ( typeof lastReplyId !== 'undefined' ) ? lastReplyId : 0;
          
          if ( typeof source === 'undefined' ) return false;
          
          $.ajax(
          {
            url: source,
            data: { last_reply_id: lastReplyId, z_csrf: csrfToken },
            method: 'POST',
            success: function( response )
            {
              response = jsonResponse( response );
              
              if ( response.status === 'user_chat_replies' )
              {
                if ( typeof response.value.logged_in !== 'undefined' )
                {
                    if ( response.value.logged_in === 'false' )
                    {
                        proceedChat = 0;
                        
                        showResponseMessage( $( '#chat-box-body form' ), response.value.message, false );
                    }
                }
                
                if ( response.value.chat_ended === 'true' )
                {
                  $( '#chat-box-tools' ).html( '' );
                  $( '#chat-box-body' ).html( response.value.chat_body );
                  
                  readySelect2();
                }
                else
                {
                  if ( response.value.having_replies === 'true' )
                  {
                    $( '#chat-messages' ).append( response.value.chat_body );
                    
                    if ( $( '#chat-box' ).css( 'display' ) === 'none' )
                    {
                      $( '#chat-toggle' ).addClass( 'bg-danger' );
                    }
                    
                    chatScrollDown( '#chat-messages' );
                  }
                }
              }
            }
          });
        }
      }, 3000 );
    }
  }
  
  
  // Submit the form to store a chat reply when the
  // user hit the enter key:
  $( document ).on( 'keypress', '#chat-reply', function ( event )
  {
    if ( event.which === 13 && ! event.shiftKey )
    {
      event.preventDefault();
      
      if ( $( this ).val() !== '' )
      {
        $( this ).closest( 'form' ).submit();
      }
    }
  });
});