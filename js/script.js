/**
 * Script
 *
 * @author Shahzaib
 */

"use strict";

$( function ()
{
  // Display the attached image ( of ticket ) under
  // the bootstrap modal and popup it:
  // @version 1.7
  $( '.popup-img-attachment' ).on( 'click', function ()
  {
    var attachmentSource = $( this ).attr( 'src' );
    
    $( '#popup-attachment-img-download' ).attr( 'href', attachmentSource );
    $( '#for-popup-attachment-img' ).attr( 'src', attachmentSource );
    $( '#view-ticket-attachment' ).modal( 'show' );
  });
  
  
  // Ajax requests handling:
  $( document ).on( 'submit', '.z-form', function ( event )
  {
    event.preventDefault();
    formAjaxRequest( $( this ) );
  });
  
  
  // Bootstrap 5 Tooltip:
  var tooltipTriggerList = [].slice.call( document.querySelectorAll( '[data-bs-toggle="tooltip"]' ) );
  var tooltipList = tooltipTriggerList.map( function ( tooltipTriggerEl )
  {
    return new bootstrap.Tooltip( tooltipTriggerEl );
  });
  
  
  // Send email to user modal management:
  $( '.seu-tool' ).on( 'click', function()
  {
    $( '#seu-email' ).val( $( this ).attr( 'data-email' ) );
  });
  
  $( '#send-email-user' ).on( 'hidden.bs.modal', function ()
  {
    $( '.textarea' ).summernote( 'reset' );
    resetForm( $( this ).find( 'form' ) );
  })
  
  
  // Manage requestor modals without sending the ajax request:
  $( '.z-table, .z-card' ).on( 'click', function ( event )
  {
    var $element = $( event.target );
    var isFine   = true;
    
    /**
     * The element you want to use to set the record ID for the modal form,
     * must have a class "tool". If the setter element is the child of "tool"
     * class, add the "tool-c" class also to the child element.
     *
     * The element that is having "tool" class, must have these attributes:
     * "data-target" OR "data-bs-target" Modal ID e.g. delete
     * "data-id" Record ID
     */
    
    if ( $element.hasClass( 'tool-c' ) )
    {
      $element = $element.parent( '.tool' );
    }
    else
    {
      if ( ! $element.hasClass( 'tool' ) )
      {
        isFine = false;
      }
    }
    
    if ( isFine === true )
    {
      var dataTarget = $element.attr( 'data-target' );
      var dataBsTarget = $element.attr( 'data-bs-target' );
      var $modal;
      
      if ( typeof dataTarget !== typeof undefined && dataTarget !== false )
      {
          var $modal = $( $element.attr( 'data-target' ) );
      }
      else if ( typeof dataBsTarget !== typeof undefined && dataBsTarget !== false )
      {
          var $modal = $( $element.attr( 'data-bs-target' ) );
      }
      
      var dataID = $element.attr( 'data-id' );
      
      $modal.find( '[name="id"]' ).val( dataID );
    }
  });
  
  
  // Google Analytics:
  if ( typeof googleAnalyticsID !== 'undefined' )
  {
    window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push( arguments ); }
    gtag( 'js', new Date() );

    gtag( 'config', googleAnalyticsID );
  }
  
  
  // Cookie Popup:
  if ( $.isFunction( $.cookie ) )
  {
    $( '.accept-btn' ).on( 'click', function()
    {
      $( '.cookie-popup' ).css( 'display', 'none' );
      $.cookie( 'z_accepted', true, { expires: 365, path: '/' } );
    });
    
    if ( $.cookie( 'z_accepted' ) == null )
    {
      $( '.cookie-popup' ).css( 'display', 'block' );
    }
  }
  
  
  /**
   * Scroll to specific box management.
   *
   * @global string  moveToBoxId
   * @global integer subtractBoxMove
   */
  if ( typeof moveToBoxId !== 'undefined' )
  {
    var toSubtract = 0;
    
    if ( typeof subtractBoxMove != 'undefined' )
    {
      toSubtract = subtractBoxMove;
    }
    
    if ( $( '#section-' + moveToBoxId ).length )
    {
      $( 'html, body' ).animate(
      {
        scrollTop: $( '#section-' + moveToBoxId ).offset().top - toSubtract
      } );
    }
  }
  
  
  /**
   * Articles Voting
   *
   * @global string csrfToken
   */
  $( document ).on( 'click', '.article-vote', function ()
  {
    $( '.article-vote' ).attr( 'disabled', 'disabled' );
    
    $.ajax(
    {
      url: $( this ).attr( 'data-action' ),
      data: {z_csrf: csrfToken},
      method: 'POST',
      success: function( response )
      {
        response = jsonResponse( response );
        
        if ( response.status === 'false' )
        {
          showResponseMessage( null, response.value, 0 );
        }
        else if ( response.status === 'voted' )
        {
          $( '#article-votes' ).text( response.value );
        }
      }
    });
  });
  
  
  // Select 2:
  readySelect2();
  
  
  // On modal shown, clear extra:
  $( window ).on( 'shown.bs.modal', function ()
  {
    resetResponseMessages();
  });
});


$( window ).on( 'load', function ()
{
  // Make pay modal button activated on the page is fully loaded:
  $( '.btn.pay-modal' ).removeAttr( 'disabled' );
});