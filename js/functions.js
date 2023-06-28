/**
 * Functions
 *
 * @author Shahzaib
 */

"use strict";

/**
 * Make Ready the Summernote
 *
 * @return  void
 * @version 1.8
 */
function readySummernote()
{
  if ( $.isFunction( $.fn.summernote ) )
  {
    $( '.textarea' ).summernote(
    {
      height: 245,
      dialogsInBody: true,
      callbacks: {
        // https://github.com/summernote/summernote/issues/303
        onPaste: function ( event )
        {
          const bufferText = ( ( event.originalEvent || event ).clipboardData || window.clipboardData ).getData( 'Text' );

          event.preventDefault();

          setTimeout( function ()
          {
            document.execCommand( 'insertText', false, bufferText );
          }, 10 );
        },
        onImageUpload: function ( image )
        {
          sendFile( image[0], this );
        },
        onMediaDelete: function ( image )
        {
          deleteFile( image[0].src );
        }
      },
      toolbar: [
        [
          'style',
          [
            'style'
          ]
        ],
        [
          'font',
          [
            'bold',
            'underline'
          ]
        ],
        [
          'fontsize',
          [
            'fontsize'
          ]
        ],
        [
          'para',
          [
            'paragraph',
            'ul',
            'ol'
          ]
        ],
        [
          'table',
          [
            'table'
          ]
        ],
        ['insert',
          [
            'link',
            'picture',
            'video'
          ]
        ],
        [
          'view', 
          [
            'codeview',
            'fullscreen'
          ]
        ]
      ]
    });
  }
}

/**
 * Chat Scroll Down
 *
 * @string  selector
 * @return  void
 * @version 1.4
 */
function chatScrollDown( selector )
{
  if ( $( selector ).length )
  {
    $( selector ).scrollTop( $( selector )[0].scrollHeight );
  }
}

/**
 * Send File ( Summernote )
 *
 * @param   object file
 * @param   object sender
 * @global  string snImageUpload
 * @global  string csrfToken
 * @return  void
 * @version 1.3
 */
function sendFile( file, sender )
{
  if ( typeof snImageUpload !== 'undefined' )
  {
    var data = new FormData();
    data.append( 'file', file );
    data.append( 'z_csrf', csrfToken );
    
    $.ajax(
    {
      data: data,
      type: 'POST',
      url: snImageUpload,
      cache: false,
      contentType: false,
      processData: false,
      success: function ( url )
      {
        var target = $( sender ).attr( 'id' );
        var image = $( '<img>' ).attr( 'src', url );
        $( '#' + target ).summernote( 'insertNode', image[0] );
      }
    });
  }
}

/**
 * Delete File ( Summernote )
 *
 * @param   file   object
 * @global  string snDeleteUpload
 * @global  string csrfToken
 * @return  void
 * @version 1.3
 */
function deleteFile( file )
{
  if ( typeof snDeleteUpload !== 'undefined' )
  {
    $.ajax(
    {
      data: {file: file, z_csrf: csrfToken},
      type: 'POST',
      url: snDeleteUpload,
      cache: false
    });
  }
}

/**
 * Show Response Message ( Ajax ).
 *
 * Use to display a success or failure message with the help of
 * Bootstrap alert classes.
 *
 * To display the response message outside of the form element, add a parent
 * class ".not-in-form" for the ".response-message" element.
 *
 * @param  object|null     $form Pass "null" if outside of the "<form>" element.
 * @param  string          message
 * @param  boolean|integer success
 * @return void
 */
function showResponseMessage( $form, message, success )
{
  var $response;
  var alertType  = 'alert-danger';
  var $alertBody = '';
  
  if ( success == true )
  {
    alertType = 'alert-success';
  }
  
  $alertBody += '<div class="alert ' + alertType + '">';
  $alertBody += message;
  $alertBody += '</div>';
  
  if ( $form === null )
  {
    $response = $( '.not-in-form .response-message' );
  }
  else
  {
    $response = $form.find( '.response-message' );
  }
  
  $response.html( $alertBody );
}

/**
 * Reset Form
 *
 * Use to reset/clean the form values. It might not work for
 * the fields that are applied the third party libraries.
 *
 * @param  object $form
 * @return void
 */
function resetForm( $form )
{
  $form.trigger( 'reset' );
  
  // Forcefully reset the select2:
  $form.find( '.select2' ).each( function ()
  {
    $( this ).change();
  });

  $form.blur();
}

/**
 * Reset Response Message
 *
 * Use to reset/clear the recent response message.
 *
 * @return void
 */
function resetResponseMessages()
{
  $( '.response-message' ).html( '' );
}

/**
 * JSON Response
 *
 * Use to verify the JSON validity.
 *
 * @param  string response
 * @return mixed
 */
function jsonResponse( response )
{
  var status;
  
  try
  {
    status = $.parseJSON( response );
  }
  catch ( e )
  {
    status = false;
  }
  
  return status;
}

/**
 * Manage Success Response
 *
 * Use to handle the ajax requests responses.
 *
 * @global string msgSeemsDeleted
 * @param  object $form
 * @param  string response
 * @return void
 */
function manageSuccessResponse( $form, response )
{
  var response = jsonResponse( response );
  var $markup;
  
  if ( typeof msgSeemsDeleted !== 'undefined' )
  {
    $markup += '<tr id="record-0">';
    $markup += '<td colspan="' + $( '.records-thead th' ).length + '">';
    $markup += msgSeemsDeleted;
    $markup += '</td>';
    $markup += '</tr>';
  }
  
  // For the "replace", "remove", and "add" statuses, The data must be inside
  // the "<table>" element. The "<tbody>" element must have a ".records-tbody"
  // class. Each row under this element, must have a record ID with the prefix
  // of "record-*".
  
  // The modal for the "add" status, must have a class called "close-after"
  // e.g. "modal close-after"
  
  if ( response != false )
  {
    var rs = response.status;
    
    if ( rs === 'replace' || rs === 'remove' || rs === 'add' )
    {
      showResponseMessage( null, response.message, 1 );
    }
    
    if ( rs === 'true_cm' )
    {
      showResponseMessage( null, response.value, 1 );
      $( '.close-after' ).modal( 'hide' );
    }
    else if ( rs === 'jump' )
    {
      window.location = response.value;
      return false;
    }
    else if ( rs === 'true' || rs === 'true_gr' )
    {
      showResponseMessage( $form, response.value, 1 );
      resetForm( $form );
    }
    else if ( rs === 'true_dr' )
    {
      showResponseMessage( $form, response.value, 1 );
    }
    else if ( rs === 'false' || rs === 'false_gr' )
    {
      showResponseMessage( $form, response.value, 0 );
    }
    else if ( rs === 'replace' )
    {
      $( '.records-tbody #record-' + response.id ).html( response.value );
      $( '#read' ).modal( 'hide' );
    }
    else if ( rs === 'remove' )
    {
      $( '.records-tbody #record-' + response.value ).remove();
      $( '#delete' ).modal( 'hide' );
      $( '#read' ).modal( 'hide' );
      
      if ( $( '.records-tbody tr' ).length === 0 )
      {
        $( '.records-tbody' ).html( $markup );
      }
      
    }
    
    // If the table records are displayed as ASC order, add a class called
    // ".z-records-asc" with ".records-tbody" class.
    
    else if ( rs === 'add' )
    {
      if ( ! $( '.records-tbody' ).hasClass( 'z-records-asc' ) )
      {
        $( '.records-tbody' ).prepend( response.value );
      }
      else
      {
        $( '.records-tbody' ).append( response.value );
      }
      
      $( '.close-after' ).modal( 'hide' );
      
      if ( $( '#record-0' ).length )
      {
        $( '#record-0' ).remove();
      }
      
      resetForm( $form );
    }
    
    else if ( rs === 'refresh' )
    {
        location.reload();
    }
    
    if ( rs === 'true_gr' || rs === 'false_gr' )
    {
      if ( typeof grecaptcha !== 'undefined' )
      {
        grecaptcha.reset();
      }
    }
    
    else if ( rs === 'user_chat_starting' )
    {
      $( '#chat-box-tools' ).html( response.value.chat_header );
      $( '#chat-box-body' ).html( response.value.chat_body );
    }
    
    else if ( rs === 'user_chat_ending' )
    {
      $( '#chat-box-tools' ).html( '' );
      $( '#chat-box-body' ).html( response.value );
      $( '#end-chat' ).modal( 'hide' );
      readySelect2();
    }
    
    else if ( rs === 'reset_form' )
    {
      resetForm( $form );
    }
    
    else if ( rs === 'close_modal' )
    {
      $( response.value ).modal( 'hide' );
    }
  }
}

/**
 * Get Spinner Markup
 *
 * @return string HTML
 */
function getSpinnerMarkup()
{
  var $markup;
  
  $markup  = '<div class="loadingio-spinner-rolling-icon">';
  $markup += '<div class="ldio-icon-inner">';
  $markup += '<div></div>';
  $markup += '</div>';
  $markup += '</div>';
  
  return $markup;
}

/**
 * Is CSRF Token ( Variable ) Exists.
 *
 * @global string csrfToken
 * @return boolean
 */
function isCsrfTokenExists()
{
  return typeof csrfToken !== 'undefined';
}

/**
 * Handle Technical Errors
 *
 * @param  object $form
 * @param  object $response
 * @global object errors
 * @return void
 */
function handleTechnicalErrors( $form, response )
{
  var message;
  
  if ( typeof errors !== 'undefined' )
  {
    if ( errors.hasOwnProperty( response.status ) )
    {
      showResponseMessage( $form, errors[response.status], 0 );
    }
    else
    {
      showResponseMessage( $form, errors['wentWrong'], 0 );
    }
  }
  else
  {
    message = response.status + ' - ' + response.statusText;
    
    showResponseMessage( $form, message, 0 );
  }
}

/**
 * Form Ajax Request ( with the Support of File Uploading ).
 *
 * Single and multiple files uploading are both supported.
 *
 * Use to send the form ajax request. This function will be automatically
 * called on the form submit event, if that form is having the "z-form" class
 * and the related script file is included.
 *
 * In the CI configuration, the "csrf_token_name" must be set as "z_csrf".
 *
 * @global string csrfToken
 * @param  object $form
 * @return void
 */
function formAjaxRequest( $form )
{
  resetResponseMessages();
  
  // It's important that the "<textarea>" element that's used
  // for the summernote text editor, must have a unique ID:
  if ( $form.find( '.textarea' ).length !== 0 )
  { 
    $( $form.find( '.textarea' ) ).each( function( $index, $element )
    {
      var selector = '#' + $( $element ).attr( 'id' );
      
      var content = $( selector ).summernote( 'code' );
      
      if ( content !== '<p><br></p>' ) $( selector ).val( content );
    });
  }
  
  var formInput    = $form.serializeArray();
  var submitButton = $form.find( '[type="submit"]' );
  var enctype      = $form.attr( 'enctype' );
  var action       = $form.attr( 'action' );
  var method       = $form.attr( 'method' );
  var addCsrf      = true;
  var btnPreText   = $( submitButton ).html();
  var formData     = formInput;
  var typeFile;
  var JSON;
  
  if ( $form.attr( 'data-csrf' ) === 'manual' )
  {
    addCsrf = false;
  }
  
  if ( enctype === 'multipart/form-data' )
  {
    formData = new FormData();
    typeFile = $form.find( 'input[type="file"]' );
    
    $.each( typeFile, function ( key, input )
    {
      if ( $( input ).val() != '' )
      {
        if ( typeof typeFile.attr( 'multiple' ) !== 'undefined' )
        {
          $( $( input ).get( 0 ).files ).each( function( index, file )
          {
            formData.append( $( input ).attr( 'name' ) + '-' + index, file );
          });
        }
        else
        {
          formData.append( $( input ).attr( 'name' ), $( input ).get( 0 ).files[0] );
        }
      }
    });
    
    $.each( formInput, function( key, input ) {
      formData.append( input.name, input.value );
    });
    
    if ( isCsrfTokenExists() === true && addCsrf )
    {
      formData.append( 'z_csrf', csrfToken );
    }
  }
  else
  {
    if ( isCsrfTokenExists() === true && addCsrf )
    {
      formData.push({
        name: 'z_csrf',
        value: csrfToken
      });
    }
  }
  
  JSON = {
    url: action,
    method: method,
    data: formData,
    beforeSend: function ()
    {
      submitButton.attr( 'disabled', 'disabled' );
      submitButton.html( getSpinnerMarkup() );
    },
    error: function( response )
    {
      handleTechnicalErrors( $form, response );
    },
    success: function( response )
    {
      manageSuccessResponse( $form, response );
    }
  };
  
  if ( enctype === 'multipart/form-data' )
  {
    JSON.contentType = false;
    JSON.processData = false;
    JSON.cache = false;
  }
  
  $.ajax( JSON ).done( function () {
    submitButton.removeAttr( 'disabled' );
    $( submitButton ).html( btnPreText );
  });
}

/**
 * Make Ready the Select2
 *
 * @return void
 */
function readySelect2()
{
  $( '.select2' ).select2();
  $( '.select2' ).css( 'width', '100%' );
  
  $( '.select2.disabled' ).select2(
  {
    disabled: true
  });
  
  $( '.select2.search-disabled' ).select2(
  {
    minimumResultsForSearch: -1
  });
}

/**
 * Get Record.
 *
 * Use to get a record from the database using ajax request. There
 * should be a HTML modal with the ID called "read" and that modal
 * must have a class "ajax-response" to display the response.
 *
 * @global string  csrfToken
 * @global string  processing
 * @param  integer id
 * @param  string  source   URL
 * @param  object  $element Requester
 * @return void
 */
function getRecord( id, source, $element )
{
  var elementText     = $element.html();
  var elementSiblings = $element.siblings();
  var dataToSend      = { id: id, z_csrf: csrfToken };
  var spinner         = getSpinnerMarkup();
  
  $.ajax({
    url: source,
    data: dataToSend,
    method: 'POST',
    
    beforeSend: function ()
    {
      if ( $element.is( 'span' ) && typeof processing !== 'undefined' )
      {
        $element.html( processing );
      }
      else
      {
        $element.html( spinner );
      }
      
      elementSiblings.add( '.get-data-tool' )
      .add( '.get-data-tool-c' )
      .add( '.tool' )
      .on( 'click', function ()
      {
        return false;
      });
    },
    error: function( response )
    {
      handleTechnicalErrors( null, response );
      $element.html( elementText );
    },
    success: function( serverResponse )
    {
      var response = jsonResponse( serverResponse );
      var value    = '';
      
      if ( response != false )
      {
        if ( response.status === 'true' )
        {
          value = response.value;
        }
        else if ( response.status === 'false' )
        {
          showResponseMessage( null, response.value, 0 );
        }
        else if ( response.status === 'jump' )
        {
          window.location = response.value;
          return false;
        }
      }
      else
      {
        if ( serverResponse != '' )
        {
          value = serverResponse;
        }
      }
      
      if ( value != '' )
      {
        $( '.ajax-response' ).html( value );
        $( '#read' ).modal( 'show' );
        $( '[data-toggle="tooltip"]' ).tooltip();
        readySelect2();
        readySummernote();
      }
    }
  }).done( function () {
    $element.html( elementText );
    elementSiblings.add( '.get-data-tool' )
    .add( '.get-data-tool-c' )
    .add( '.tool' )
    .unbind( 'click' );
  });
}