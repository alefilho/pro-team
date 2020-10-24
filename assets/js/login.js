$(function() {
  const BASE = $('link[rel="base"]').attr('href');
  var timeMessage = 3000;

  // TRIGGER

  function ToastError(ErrMsg, ErrNo = null){
    var CssClass = (ErrNo == 'E_USER_NOTICE' ? 'trigger_info' : (ErrNo == 'E_USER_WARNING' ? 'trigger_alert' : (ErrNo == 'E_USER_ERROR' ? 'trigger_error' : 'trigger_success')));
    return "<div class='trigger trigger_ajax "+CssClass+"'>"+ErrMsg+"<span class='ajax_close'></span><div class='trigger_progress'></div></div>";
  }

  function Trigger(Message) {
    $('.trigger_ajax').fadeOut('fast', function () {
      $(this).remove();
    });
    $('body').before("<div class='trigger_modal'>" + Message + "</div>");
    $('.trigger_ajax').fadeIn();

    $('.trigger_progress').animate({"width": "100%"}, timeMessage, "linear", function () {
      $('.trigger_modal').fadeOut();
    });
  }

  function TriggerClose(thistrigger) {
    thistrigger.fadeOut('fast', function () {
      $(this).remove();
    });
  }

  $(document).on('click', '.trigger_modal', function(){
    TriggerClose($(this));
  });

  // LOADING

  function Loading(bool) {
    if (bool) {
      $('body').append('<div class="load_alpha" style="position: fixed; width: 100%; height: 100vh; z-index: 99999; background: url(assets/img/bg.png) rgba(0,0,0,0.5); top: 0; left: 0;">'+
      '   <div class="ajax_load" style="z-index: 1101; width: 100%; height: 100vh; background: url(assets/img/load_w.gif); background-repeat: no-repeat; background-position: center;"></div>'+
      '</div>');
    }else {
      $('.load_alpha').remove();
    }
  }

  $('#Login').submit(function(e){
    e.preventDefault();

    var AjaxData = 'AjaxFile=Login&AjaxAction=ExeLogin&' + $(this).serialize();

    $.ajax({
      method: 'POST',
      url: BASE + '/src/ajax/Login.ajax.php',
      data: AjaxData,
      dataType: 'json',
      beforeSend: function () {
        Loading(true);
      },
      success: function (data) {
        Loading(false);

        if (data.trigger) {
          Trigger(data.trigger);
        }

        if (data.location) {
          if (data.trigger) {
            setTimeout(function(){ location.href = data.location; }, timeMessage);
          }else{
            location.href = data.location;
          }
        }
      },
      error: function(){
        Loading(false);
        Trigger(ToastError("Ocorreu algum erro", "E_USER_ERROR"));
      }
    });
  });

  $('#SignUp').submit(function(e){
    e.preventDefault();

    var AjaxData = 'AjaxFile=Users&AjaxAction=save&' + $(this).serialize();

    $.ajax({
      method: 'POST',
      url: BASE + '/src/ajax/Users.ajax.php',
      data: AjaxData,
      dataType: 'json',
      beforeSend: function () {
        Loading(true);
      },
      success: function (data) {
        Loading(false);

        if (data.trigger) {
          Trigger(data.trigger);
        }

        if (data.location) {
          if (data.trigger) {
            setTimeout(function(){ location.href = data.location; }, timeMessage);
          }else{
            location.href = data.location;
          }
        }

        if (data.reset) {
          $.each(data.reset, function (key, value) {
            $(key)[0].reset();
            $(key).find('input[name="id"]').val("");
          });
        }
      },
      error: function(){
        Loading(false);
        Trigger(ToastError("Ocorreu algum erro", "E_USER_ERROR"));
      }
    });
  });

  $('#Recover').submit(function(e){
    e.preventDefault();

    var AjaxData = 'AjaxFile=Login&AjaxAction=recover&' + $(this).serialize();

    $.ajax({
      method: 'POST',
      url: BASE + '/src/ajax/Login.ajax.php',
      data: AjaxData,
      dataType: 'json',
      beforeSend: function () {
        Loading(true);
      },
      success: function (data) {
        Loading(false);

        if (data.trigger) {
          Trigger(data.trigger);
        }

        if (data.location) {
          if (data.trigger) {
            setTimeout(function(){ location.href = data.location; }, timeMessage);
          }else{
            location.href = data.location;
          }
        }

        if (data.reset) {
          $.each(data.reset, function (key, value) {
            $(key)[0].reset();
            $(key).find('input[name="id"]').val("");
          });
        }
      },
      error: function(){
        Loading(false);
        Trigger(ToastError("Ocorreu algum erro", "E_USER_ERROR"));
      }
    });
  });

  $('#RecoverToken').submit(function(e){
    e.preventDefault();

    var AjaxData = 'AjaxFile=Login&AjaxAction=recoverToken&' + $(this).serialize();

    $.ajax({
      method: 'POST',
      url: BASE + '/src/ajax/Login.ajax.php',
      data: AjaxData,
      dataType: 'json',
      beforeSend: function () {
        Loading(true);
      },
      success: function (data) {
        Loading(false);

        if (data.trigger) {
          Trigger(data.trigger);
        }

        if (data.location) {
          if (data.trigger) {
            setTimeout(function(){ location.href = data.location; }, timeMessage);
          }else{
            location.href = data.location;
          }
        }

        if (data.reset) {
          $.each(data.reset, function (key, value) {
            $(key)[0].reset();
            $(key).find('input[name="id"]').val("");
          });
        }
      },
      error: function(){
        Loading(false);
        Trigger(ToastError("Ocorreu algum erro", "E_USER_ERROR"));
      }
    });
  });
});
