function getQueryVariable(variable) {
  var query = location.search.substring(1)
  var vars = query.split('&')
  for (var i = 0; i < vars.length; i++) {
    var pair = vars[i].split('=')
    if (pair[0] == variable) {
      return pair[1];
    }
  }
  return '';
}
window.SiteConfig = {
  MQTT_CONFIG: {
    'servers': [
      {
        host: 'rmqtt-br.ssqlkjs.top',
        port: '/mqtt',
        protocol: 'wss'
      },
    ],
    'pre': 'w88',
    'username': 'yqmqtt',
    'password': '\x81_Ô}¹bÌuğñ¶\x85]+ĎÛÕ§ŏìŋÜĿÒhF'
  },
  XuanFuTG: {
    url: 'https://t.me/L77bet_games',
    img: '/h5/xuanfuTG.gif'
  },
  // 悬浮chat
  Chat: {
    url: 'https://chat.ichatlink.net/widget/standalone.html?eid=e679f25c0368bc3c1afc82e724341236&language=en',
    img: '/h5/chat.png'
  },
  sharePopup:true
};

(function updateThemColor() {
  var theme = localStorage.getItem('SiteColor') || 'black'
  document.documentElement.setAttribute('theme', theme)
  var themeFromUrl = getQueryVariable('theme')
  if (themeFromUrl) {
    document.documentElement.setAttribute('theme', themeFromUrl)
  }
  var tempH5 = localStorage.getItem('tempH5') || ''
  document.documentElement.setAttribute('tempH5', tempH5)
  var tempH5FromUrl = getQueryVariable('tempH5')
  if (tempH5FromUrl) {
    document.documentElement.setAttribute('tempH5', tempH5FromUrl)
  }
  var tempPC = localStorage.getItem('tempPC') || ''
  document.documentElement.setAttribute('tempPC', tempPC)
  var tempPCFromUrl = getQueryVariable('tempPC')
  if (tempPCFromUrl) {
    document.documentElement.setAttribute('tempPC', tempPCFromUrl)
  }
  
})();

(function updateWithPc() {
  var usePc = localStorage.getItem('pc') || '1'
  document.documentElement.setAttribute('pc', usePc)
  var usePcUrl = getQueryVariable('pc')
  if (usePcUrl) {
    document.documentElement.setAttribute('pc', usePcUrl)
  }
})();