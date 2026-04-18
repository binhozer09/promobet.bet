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
        host: 'brmq.yq666.com',
        port: '/mqtt',
        protocol: 'wss'
      },
    ],
    'pre': 'w88',
    'username': 'ª\x88ŭôŅÔŚê\x83\x11¿PĒ®&\x04',
    'password': '\x81_Ô}¹bÌuğñ¶\x85]+ĎÛÕ§ŏìŋÜĿÒhF'
  },
};


(function updateThemColor() {
  var theme = localStorage.getItem('SiteColor') || 'black'
  document.documentElement.setAttribute('theme', theme)
 
  
  var themeFromUrl = getQueryVariable('theme')
  console.log(themeFromUrl,'地址传过来的主题色地址传过来的主题色地址传过来的主题色地址传过来的主题色');
  if (themeFromUrl) {
    document.documentElement.setAttribute('theme', themeFromUrl)
  }

  var pc = localStorage.getItem('pc') || ''
  document.documentElement.setAttribute('pc', pc)
  var pcFromUrl = getQueryVariable('pc')
  if (pcFromUrl) {
    document.documentElement.setAttribute('pc', pcFromUrl)
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