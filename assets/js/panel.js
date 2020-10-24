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

  $('.select2').select2();

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

  tinymce.init({
    selector: 'textarea#tiny'
  });


  $(document).on('keyup', '.j_cep', function(){
    //Nova variável "cep" somente com dígitos.
    var cep = $(this).val().replace(/\D/g, '');
    //Verifica se campo cep possui valor informado.
    if (cep != "") {
      //Expressão regular para validar o CEP.
      var validacep = /^[0-9]{8}$/;
      //Valida o formato do CEP.
      if(validacep.test(cep)) {
        //Consulta o webservice viacep.com.br/
        $.ajax({
          method: 'GET',
          url: "https://viacep.com.br/ws/"+ cep +"/json/?callback=?",
          dataType: 'json',
          beforeSend: function () {
            Loading(true);
          },
          success: function (dados) {
            Loading(false);
            if (!("erro" in dados)) {
              //Atualiza os campos com os valores da consulta.
              $(".j_cep_logradouro").val(dados.logradouro);
              $(".j_cep_bairro").val(dados.bairro);
              $(".j_cep_cidade").val(dados.localidade);
              $(".j_cep_uf").val(dados.uf);
              $(".j_cep_ibge").val(dados.ibge);
            }else {
              //CEP pesquisado não foi encontrado.
              Trigger(ToastError("CEP não foi encontrado", "E_USER_WARNING"));
            }
          },
          error: function(){
            Loading(false);
          }
        });
      }else {
        $(".j_cep_logradouro").val("");
        $(".j_cep_bairro").val("");
        $(".j_cep_cidade").val("");
        $(".j_cep_uf").val("");
        $(".j_cep_ibge").val("");
      }
    }else {
      $(".j_cep_logradouro").val("");
      $(".j_cep_bairro").val("");
      $(".j_cep_cidade").val("");
      $(".j_cep_uf").val("");
      $(".j_cep_ibge").val("");
    }
  });

  function validaCpfCnpj(val) {
    val = val.replace(/[^\d]+/g,'');

    if (val.length == 11) {
      var cpf = val.trim()

      cpf = cpf.replace(/\./g, '');
      cpf = cpf.replace('-', '');
      cpf = cpf.split('');

      var v1 = 0;
      var v2 = 0;
      var aux = false;

      for (var i = 1; cpf.length > i; i++) {
        if (cpf[i - 1] != cpf[i]) {
          aux = true;
        }
      }

      if (aux == false) {
        return false;
      }

      for (var i = 0, p = 10; (cpf.length - 2) > i; i++, p--) {
        v1 += cpf[i] * p;
      }

      v1 = ((v1 * 10) % 11);

      if (v1 == 10) {
        v1 = 0;
      }

      if (v1 != cpf[9]) {
        return false;
      }

      for (var i = 0, p = 11; (cpf.length - 1) > i; i++, p--) {
        v2 += cpf[i] * p;
      }

      v2 = ((v2 * 10) % 11);

      if (v2 == 10) {
        v2 = 0;
      }

      if (v2 != cpf[10]) {
        return false;
      } else {
        return true;
      }
    } else if (val.length == 14) {
      var cnpj = val.trim();

      cnpj = cnpj.replace(/\./g, '');
      cnpj = cnpj.replace('-', '');
      cnpj = cnpj.replace('/', '');
      cnpj = cnpj.split('');

      var v1 = 0;
      var v2 = 0;
      var aux = false;

      for (var i = 1; cnpj.length > i; i++) {
        if (cnpj[i - 1] != cnpj[i]) {
          aux = true;
        }
      }

      if (aux == false) {
        return false;
      }

      for (var i = 0, p1 = 5, p2 = 13; (cnpj.length - 2) > i; i++, p1--, p2--) {
        if (p1 >= 2) {
          v1 += cnpj[i] * p1;
        } else {
          v1 += cnpj[i] * p2;
        }
      }

      v1 = (v1 % 11);

      if (v1 < 2) {
        v1 = 0;
      } else {
        v1 = (11 - v1);
      }

      if (v1 != cnpj[12]) {
        return false;
      }

      for (var i = 0, p1 = 6, p2 = 14; (cnpj.length - 1) > i; i++, p1--, p2--) {
        if (p1 >= 2) {
          v2 += cnpj[i] * p1;
        } else {
          v2 += cnpj[i] * p2;
        }
      }

      v2 = (v2 % 11);

      if (v2 < 2) {
        v2 = 0;
      } else {
        v2 = (11 - v2);
      }

      if (v2 != cnpj[13]) {
        return false;
      } else {
        return true;
      }
    } else {
      return false;
    }
  }

  function Ajax(AjaxFile, AjaxData, Filter = null) {
    var contentType = 'application/x-www-form-urlencoded';
    if (typeof(AjaxData) == 'object') {
      contentType = false;
    }

    $.ajax({
      method: 'POST',
      url: BASE + '/src/ajax/' + AjaxFile + '.ajax.php' + (Filter ? '?' + Filter : ''),
      data: AjaxData,
      dataType: 'json',
      processData: false,
      contentType: contentType,
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

        if (data.reload) {
          if (data.trigger) {
            setTimeout(function(){ location.reload(); }, timeMessage);
          }else{
            location.reload();
          }
        }

        if (data.content) {
          $.each(data.content, function (key, value) {
            $(key).fadeTo('300', '0.5', function () {
              $(this).html(value).fadeTo('300', '1');
            });
          });
        }

        if (data.reset) {
          $.each(data.reset, function (key, value) {
            $(key)[0].reset();
            $(key).find('input[name="id"]').val("");
          });
        }

        if (data.fadeIn) {
          $.each(data.fadeIn, function (key, value) {
            $(key).fadeIn();
          });
        }

        if (data.fadeOut) {
          $.each(data.fadeOut, function (key, value) {
            $(key).fadeOut();
          });
        }

        if (data.open) {
          $.each(data.open, function (key, value) {
            var win = window.open(key, value);
            if (win) {
              //Browser has allowed it to be opened
              win.focus();
            } else {
              //Browser has blocked it
              alert('Please allow popups for this website');
            }
          });
        }

        if (data.modal) {
          $.each(data.modal, function (key, value) {
            $(key).modal(value);
          });
        }

        if (data.replaceWith) {
          $.each(data.replaceWith, function (key, value) {
            $(key).replaceWith(value);
          });
        }

        if (data.form) {
          $.each(data.form, function (key, value) {
            $.each(value, function(key2, value2){
              if ($(key).find("input[name='"+key2+"']").attr('type') == 'checkbox') {
                if (value2 == 1) {
                  $(key).find("input[name='"+key2+"']").prop('checked', true);
                }else {
                  $(key).find("input[name='"+key2+"']").prop('checked', false);
                }
              }else {
                $(key).find("input[name='"+key2+"']").val(value2);
                $(key).find("textarea[name='"+key2+"']").val(value2);
                $(key).find("select[name='"+key2+"']").val(value2);
              }
            });
          });
        }

        if (data.tiny) {
          $.each(data.tiny, function (key, value) {
            tinymce.get(key).execCommand('mceCleanup');
            tinymce.get(key).execCommand('mceInsertContent', false, value);
          });
        }

        if (data.click) {
          $.each(data.click, function (key, value) {
            $(key).click();
          });
        }

        if (data.ajax) {
          $.each(data.ajax, function (key, value) {
            Ajax(value['AjaxFile'], value['AjaxData']);
          });
        }

        $('.select2').select2();
      },
      error: function(){
        Loading(false);
        Trigger(ToastError("Erro desconhecido, contate o desenvolvedor", "E_USER_ERROR"));
      }
    });
  }

  $(document).on("submit", ".j_form", function (e) {
    e.preventDefault();

    var AjaxFile = $(this).find('input[name="AjaxFile"]').val();
    var AjaxData = new FormData(this);

    Ajax(AjaxFile, AjaxData);
  });

  $(document).on("click", '.j_ajax_generic', function(){
    var AjaxFile = $(this).attr("ajaxfile");
    var AjaxAction = $(this).attr("ajaxaction");
    var AjaxData = $(this).attr("ajaxdata") + '&AjaxFile=' + AjaxFile + '&AjaxAction=' + AjaxAction;

    if ($(this).attr("confirm") && $(this).attr("confirm").toLowerCase() == "true") {
      $.confirm({
        title: 'Continuar',
        content: 'Deseja realmente continuar com essa ação?',
        theme: 'supervan',
        buttons: {
          Sim: {
            text: '',
            btnClass: '',
            keys: ['enter', 's', 'y'],
            action: function(){
              Ajax(AjaxFile, AjaxData);
            }
          },
          Não: {
            text: '',
            btnClass: '',
            keys: ['n', 'esc'],
            action: function(){

            }
          },
        }
      });
    }else {
      Ajax(AjaxFile, AjaxData);
    }
  });

  $('.j_checkall').change(function () {
    if ($(this).prop('checked')) {
      $('input[type="checkbox"]').prop( "checked", true );
    }else {
      $('input[type="checkbox"]').prop( "checked", false );
    }
  });

  $('.j_form_filter').submit(function (e) {
    e.preventDefault();

    $('.j_form_filter').find("input[name='pag']").val("1");

    const f = $('.j_form_filter').serialize();
    const splitFilter = f.split("&");

    let AjaxFile = "";
    let AjaxAction = "";

    let filterArray = [];

    $.each(splitFilter, function (i, e) {
      const s = e.split("=");
      if (s[0] == "AjaxFile") {
        AjaxFile = s[1];
      }else if (s[0] == "AjaxAction") {
        AjaxAction = s[1];
      }else if (s[0] == "filter") {
      }else if (s[0] == "pag") {
      }else {
        if (String(s[1]).length > 0) {
          filterArray.push(e)
        }
      }
    });

    const filter = filterArray.join("&");

    $('.j_form_filter').find("input[name='filter']").val(filter);

    sendFormFilter();
  });

  if ($('.j_form_filter').length) {
    const f = $('.j_form_filter').serialize();
    const splitFilter = f.split("&");

    let AjaxFile = "";
    let AjaxAction = "";

    let filterArray = [];

    $.each(splitFilter, function (i, e) {
      const s = e.split("=");
      if (s[0] == "AjaxFile") {
        AjaxFile = s[1];
      }else if (s[0] == "AjaxAction") {
        AjaxAction = s[1];
      }else if (s[0] == "filter") {
      }else if (s[0] == "pag") {
      }else {
        if (String(s[1]).length > 0) {
          filterArray.push(e)
        }
      }
    });

    const filter = filterArray.join("&");

    $('.j_form_filter').find("input[name='filter']").val(filter);
    sendFormFilter();
  }

  function sendFormFilter () {
    let AjaxData = new FormData();
    const AjaxFile = $('.j_form_filter').find("input[name='AjaxFile']").val();

    AjaxData.append("AjaxFile", $('.j_form_filter').find("input[name='AjaxFile']").val());
    AjaxData.append("AjaxAction", $('.j_form_filter').find("input[name='AjaxAction']").val());

    Ajax(AjaxFile, AjaxData, "pag=" + $('.j_form_filter').find("input[name='pag']").val() + '&' + $('.j_form_filter').find("input[name='filter']").val());
  };

  $(document).on("click", '.j_btn_pag', function (e) {
    e.preventDefault();
    $('.j_form_filter').find("input[name='pag']").val($(this).attr('pag'));
    sendFormFilter();
  });

  $(document).on('click', '.j_copy_clipboard', function () {
    copyTextToClipboard($(this).attr("text"));
    $(this).html("Copiado");
  });

  function copyTextToClipboard(text) {
    var textArea = document.createElement("textarea");

    textArea.style.position = 'fixed';
    textArea.style.top = 0;
    textArea.style.left = 0;

    // Ensure it has a small width and height. Setting to 1px / 1em
    // doesn't work as this gives a negative w/h on some browsers.
    textArea.style.width = '2em';
    textArea.style.height = '2em';

    // We don't need padding, reducing the size if it does flash render.
    textArea.style.padding = 0;

    // Clean up any borders.
    textArea.style.border = 'none';
    textArea.style.outline = 'none';
    textArea.style.boxShadow = 'none';

    // Avoid flash of white box if rendered for any reason.
    textArea.style.background = 'transparent';


    textArea.value = text;

    document.getElementById("ModalBody").appendChild(textArea);
    // document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();

    try {
      var successful = document.execCommand('copy');
      var msg = successful ? 'successful' : 'unsuccessful';
      console.log('Copying text command was ' + msg);
    } catch (err) {
      console.log('Oops, unable to copy');
    }

    document.getElementById("ModalBody").removeChild(textArea);
    // document.body.removeChild(textArea);
  }

  $('.j_open_modal').click(function () {
    $($(this).attr("ref")).modal('show');
    $($(this).attr("reset"))[0].reset();
    $($(this).attr("reset")).find('input[name="id"]').val("");

    if ($('#tiny').length > 0) {
      tinymce.get("tiny").execCommand('mceCleanup');
    }
  });
})

function showThumbnail(filess) {
  var url = filess.value;
  var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
  if (filess.files && filess.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
    var reader = new FileReader();
    reader.onload = function (e) {
      document.getElementById('j_show_img').setAttribute('src', e.target.result);
    }
    reader.readAsDataURL(filess.files[0]);
  }
}
