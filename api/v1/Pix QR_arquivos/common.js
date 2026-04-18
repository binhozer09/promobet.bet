function loading(interval) {
  if (!$('.progress-wrap')[0]) {
    $('body').append('<div class="progress-wrap"><div class="reverse-spinner"></div></div>');
  }
  $('.progress-wrap').show();

  if (interval) {
    setTimeout(() => clearLoading(), interval);
  }
}

function clearLoading() {
  $('.progress-wrap').hide();
}

function parseUrlParams() {
  var regex = /[?&]([^=#&]+)=([^&#]*)/g,
    url = window.location.href,
    params = {},
    match;
  while ((match = regex.exec(url))) {
    params[decodeURIComponent(match[1])] = decodeURIComponent(match[2]);
  }
  return params;
}

function toast(message, hide = 3000) {
  toastr.success(message);
}

function error(message) {
  toastr.error(message);
}

function back() {
  setTimeout(function () {
    window.history.back();
  }, 3000);
}

function copy(text, tips) {
  var input = document.createElement('input');
  input.value = text;
  document.body.appendChild(input);
  input.select();
  document.execCommand('Copy');

  document.body.removeChild(input);
  toast(tips || 'Copied: ' + text, 1500);
}

function toThousandFormat(num) {
  return (+num || 0).toString().replace(/^-?\d+/g, (m) => m.replace(/(?=(?!\b)(\d{3})+$)/g, ','));
}

function toThousandBrlFormat(num) {
  var src = (+num || 0).toString();
  src = src.indexOf('.') > -1 ? src : src + '.00';
  var r = src.replace('.', ',').replace(/^-?\d+/g, (m) => m.replace(/(?=(?!\b)(\d{3})+$)/g, '.'));
  return r;
}

function startCheckTrade(tradeNo) {
  var checkPaidIntv = setInterval(function () {
    fetch('/api/cashier/trade?tradeNo=' + tradeNo, {
      // mode: 'no-cors',
      // redirect: 'follow'
    })
      .then((response) => response.json())
      .then((json) => {
        // console.log(json)
        var trade = json.data;

        if (trade.status === 'PAY_FAILED') {
          clearInterval(checkPaidIntv);
          toast('Something went wrong, Retry later')(
            window.jsInterface && window.jsInterface.onNavBack()
          ) || window.history.back();
        }

        if (trade.status === 'PAID') {
          clearInterval(checkPaidIntv);
          if (trade.redirectUrl != null && trade.redirectUrl != '') {
            location.href = trade.redirectUrl;
          }
          (window.jsInterface && window.jsInterface.onNavBack()) || window.history.back();
        }
      })
      .catch((err) => console.error(err));
  }, 15000);
}

function isMobile() {
  var userAgentInfo = navigator.userAgent;
  var mobileAgents = ['Android', 'iPhone', 'SymbianOS', 'Windows Phone', 'iPad', 'iPod'];
  var mobile_flag = false;

  //根据userAgent判断是否是手机
  for (var v = 0; v < mobileAgents.length; v++) {
    if (userAgentInfo.indexOf(mobileAgents[v]) > 0) {
      mobile_flag = true;
      break;
    }
  }

  var screen_width = window.screen.width;
  var screen_height = window.screen.height;

  //根据屏幕分辨率判断是否是手机
  if (screen_width < 500 && screen_height < 800) {
    mobile_flag = true;
  }

  return mobile_flag;
}
