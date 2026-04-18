const __vite__mapDeps=(i,m=__vite__mapDeps,d=(m.f||(m.f=["assets/App-DNWaJ6B8-2024_12_14_18_4.js","assets/comps-B8ShbmG--2024_12_14_18_4.js","assets/icons-Cdaou_E3-2024_12_14_18_4.js","assets/icons-CxwRDrrn-2024_12_14_18_4.css","assets/comps-DLgBMKMA-2024_12_14_18_4.css","assets/index-DgUEEG5U-2024_12_14_18_4.js","assets/index-DyG_R4zg-2024_12_14_18_4.js","assets/index-Bok_l04h-2024_12_14_18_4.css","assets/index-4Dep-BeX-2024_12_14_18_4.js","assets/index-Bt-OyMEQ-2024_12_14_18_4.css","assets/index-CGmeRIXa-2024_12_14_18_4.js","assets/index-DF6p0DMW-2024_12_14_18_4.css","assets/index-BcpFmBv5-2024_12_14_18_4.js","assets/index-C7CpiKuL-2024_12_14_18_4.css","assets/index-CsbwU_08-2024_12_14_18_4.js","assets/index-DsKRbARX-2024_12_14_18_4.css","assets/index-Bq-a07OY-2024_12_14_18_4.js","assets/index-D8LKgtTC-2024_12_14_18_4.css","assets/config-B4NCyDU0-2024_12_14_18_4.js","assets/index-Dzw5inTt-2024_12_14_18_4.css","assets/index-ps5212LV-2024_12_14_18_4.js","assets/index-jLdODqpN-2024_12_14_18_4.css","assets/line.module-BFLXfPKU-2024_12_14_18_4.js","assets/line-D5VAH0cn-2024_12_14_18_4.css","assets/index-D_urebYV-2024_12_14_18_4.js","assets/index-DPGPb2-h-2024_12_14_18_4.css","assets/index-CwqwqHfm-2024_12_14_18_4.js","assets/index-CezjVG8f-2024_12_14_18_4.js","assets/index-oTqPuerr-2024_12_14_18_4.js","assets/index-DPssoWs5-2024_12_14_18_4.css","assets/index-p7nE_l8p-2024_12_14_18_4.css","assets/index-DIlf81kZ-2024_12_14_18_4.css","assets/index-DLcyu0vi-2024_12_14_18_4.js","assets/index-B5atNveR-2024_12_14_18_4.css","assets/App-BUIvY06G-2024_12_14_18_4.css","assets/App-CvhD5z2--2024_12_14_18_4.js","assets/index-BBf4puSv-2024_12_14_18_4.js","assets/index-CTv-AT9z-2024_12_14_18_4.js","assets/index-D4Cw-pvd-2024_12_14_18_4.css","assets/index-CTaUv14e-2024_12_14_18_4.css","assets/index-rpZTIjpC-2024_12_14_18_4.js","assets/context-C55T3XVF-2024_12_14_18_4.js","assets/index-DttmqmCb-2024_12_14_18_4.js","assets/index-DSE3kXAc-2024_12_14_18_4.css","assets/index-Df-UDyxE-2024_12_14_18_4.css","assets/index-Ba2huCJI-2024_12_14_18_4.js","assets/index-98jhfXf5-2024_12_14_18_4.css","assets/App-Dl0zLnOe-2024_12_14_18_4.css","assets/App-olCRdXRx-2024_12_14_18_4.js","assets/index-BYMVQi1V-2024_12_14_18_4.js","assets/index-Dc1gNDM5-2024_12_14_18_4.css","assets/index-DAC3Xkn--2024_12_14_18_4.js","assets/index-X_1m5ZRH-2024_12_14_18_4.css","assets/App-Bg0ZTbpG-2024_12_14_18_4.css","assets/App-B2yHIxbX-2024_12_14_18_4.js","assets/index-RKZnyznx-2024_12_14_18_4.js","assets/index-B6iKbgGk-2024_12_14_18_4.css","assets/App-DgIV-cBM-2024_12_14_18_4.css"])))=>i.map(i=>d[i]);
function __vite_legacy_guard() {
  import.meta.url;
  import("_").catch(() => 1);
  (async function* () {
  })().next();
}
;
import { c as commonjsGlobal, g as getDefaultExportFromCjs, j as jsxRuntimeExports, a as joinClass, b as adaptNotchScreen, d as addPagePV, e as addPageUV, f as dayjs, h as getWebType, C as Cache, s as saveUrlParams, i as getQueryVariable, k as getPageHtmlFontSize, r as reactExports, l as isWithPc, _ as __vitePreload, m as languageLoaded, n as memberWebsetList, o as useGameStore, p as useFloatPopShareStore, q as useUserInfoStore, t as initPixi, v as client, B as BrowserRouter, w as tempH5, x as tempPC } from "./comps-B8ShbmG--2024_12_14_18_4.js";
import "./icons-Cdaou_E3-2024_12_14_18_4.js";
(function polyfill() {
  const relList = document.createElement("link").relList;
  if (relList && relList.supports && relList.supports("modulepreload")) {
    return;
  }
  for (const link of document.querySelectorAll('link[rel="modulepreload"]')) {
    processPreload(link);
  }
  new MutationObserver((mutations) => {
    for (const mutation of mutations) {
      if (mutation.type !== "childList") {
        continue;
      }
      for (const node of mutation.addedNodes) {
        if (node.tagName === "LINK" && node.rel === "modulepreload")
          processPreload(node);
      }
    }
  }).observe(document, { childList: true, subtree: true });
  function getFetchOpts(link) {
    const fetchOpts = {};
    if (link.integrity) fetchOpts.integrity = link.integrity;
    if (link.referrerPolicy) fetchOpts.referrerPolicy = link.referrerPolicy;
    if (link.crossOrigin === "use-credentials")
      fetchOpts.credentials = "include";
    else if (link.crossOrigin === "anonymous") fetchOpts.credentials = "omit";
    else fetchOpts.credentials = "same-origin";
    return fetchOpts;
  }
  function processPreload(link) {
    if (link.ep)
      return;
    link.ep = true;
    const fetchOpts = getFetchOpts(link);
    fetch(link.href, fetchOpts);
  }
})();
const __variableDynamicImportRuntimeHelper = (glob, path, segs) => {
  const v = glob[path];
  if (v) {
    return typeof v === "function" ? v() : Promise.resolve(v);
  }
  return new Promise((_, reject) => {
    (typeof queueMicrotask === "function" ? queueMicrotask : setTimeout)(
      reject.bind(
        null,
        new Error(
          "Unknown variable dynamic import: " + path + (path.split("/").length !== segs ? ". Note that variables only represent file names one level deep." : "")
        )
      )
    );
  });
};
(function(window2) {
  if (typeof window2 === "undefined") {
    throw new Error("Geetest requires browser environment");
  }
  var document2 = window2.document;
  var Math2 = window2.Math;
  var head = document2.getElementsByTagName("head")[0];
  var TIMEOUT = 1e4;
  function _Object(obj) {
    this._obj = obj;
  }
  _Object.prototype = {
    _each: function(process) {
      var _obj = this._obj;
      for (var k in _obj) {
        if (_obj.hasOwnProperty(k)) {
          process(k, _obj[k]);
        }
      }
      return this;
    },
    _extend: function(obj) {
      var self = this;
      new _Object(obj)._each(function(key, value) {
        self._obj[key] = value;
      });
    }
  };
  var uuid = function() {
    return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g, function(c) {
      var r = Math2.random() * 16 | 0;
      var v = c === "x" ? r : r & 3 | 8;
      return v.toString(16);
    });
  };
  function Config(config) {
    var self = this;
    new _Object(config)._each(function(key, value) {
      self[key] = value;
    });
  }
  Config.prototype = {
    apiServers: ["gcaptcha4.geetest.com", "gcaptcha4.geevisit.com", "gcaptcha4.gsensebot.com"],
    staticServers: ["static.geetest.com", "static.geevisit.com"],
    protocol: "http://",
    typePath: "/load",
    fallback_config: {
      bypass: {
        staticServers: ["static.geetest.com", "static.geevisit.com"],
        type: "bypass",
        bypass: "/v4/bypass.js"
      }
    },
    _get_fallback_config: function() {
      var self = this;
      if (isString(self.type)) {
        return self.fallback_config[self.type];
      } else {
        return self.fallback_config.bypass;
      }
    },
    _extend: function(obj) {
      var self = this;
      new _Object(obj)._each(function(key, value) {
        self[key] = value;
      });
    }
  };
  var isNumber = function(value) {
    return typeof value === "number";
  };
  var isString = function(value) {
    return typeof value === "string";
  };
  var isBoolean = function(value) {
    return typeof value === "boolean";
  };
  var isObject = function(value) {
    return typeof value === "object" && value !== null;
  };
  var isFunction = function(value) {
    return typeof value === "function";
  };
  var MOBILE = /Mobi/i.test(navigator.userAgent);
  var callbacks = {};
  var status = {};
  var random = function() {
    return parseInt(Math2.random() * 1e4) + (/* @__PURE__ */ new Date()).valueOf();
  };
  var bind = function(target, context) {
    if (typeof target !== "function") {
      return;
    }
    var args = Array.prototype.slice.call(arguments, 2);
    if (Function.prototype.bind) {
      return target.bind(context, args);
    } else {
      return function() {
        var _args = Array.prototype.slice.call(arguments);
        return target.apply(context, args.concat(_args));
      };
    }
  };
  var toString = Object.prototype.toString;
  var _isFunction = function(obj) {
    return typeof obj === "function";
  };
  var _isObject = function(obj) {
    return obj === Object(obj);
  };
  var _isArray = function(obj) {
    return toString.call(obj) == "[object Array]";
  };
  var _isDate = function(obj) {
    return toString.call(obj) == "[object Date]";
  };
  var _isRegExp = function(obj) {
    return toString.call(obj) == "[object RegExp]";
  };
  var _isBoolean = function(obj) {
    return toString.call(obj) == "[object Boolean]";
  };
  function resolveKey(input) {
    return input.replace(/(\S)(_([a-zA-Z]))/g, function(match, $1, $2, $3) {
      return $1 + $3.toUpperCase() || "";
    });
  }
  function camelizeKeys(input, convert) {
    if (!_isObject(input) || _isDate(input) || _isRegExp(input) || _isBoolean(input) || _isFunction(input)) {
      return convert ? resolveKey(input) : input;
    }
    if (_isArray(input)) {
      var temp = [];
      for (var i = 0; i < input.length; i++) {
        temp.push(camelizeKeys(input[i]));
      }
    } else {
      var temp = {};
      for (var prop in input) {
        if (input.hasOwnProperty(prop)) {
          temp[camelizeKeys(prop, true)] = camelizeKeys(input[prop]);
        }
      }
    }
    return temp;
  }
  var loadScript = function(url, cb, timeout) {
    var script = document2.createElement("script");
    script.charset = "UTF-8";
    script.async = true;
    if (/static\.geetest\.com/g.test(url)) {
      script.crossOrigin = "anonymous";
    }
    script.onerror = function() {
      cb(true);
      loaded = true;
    };
    var loaded = false;
    script.onload = script.onreadystatechange = function() {
      if (!loaded && (!script.readyState || "loaded" === script.readyState || "complete" === script.readyState)) {
        loaded = true;
        setTimeout(function() {
          cb(false);
        }, 0);
      }
    };
    script.src = url;
    head.appendChild(script);
    setTimeout(function() {
      if (!loaded) {
        script.onerror = script.onload = null;
        script.remove && script.remove();
        cb(true);
      }
    }, timeout || TIMEOUT);
  };
  var normalizeDomain = function(domain) {
    return domain.replace(/^https?:\/\/|\/$/g, "");
  };
  var normalizePath = function(path) {
    path = path && path.replace(/\/+/g, "/");
    if (path.indexOf("/") !== 0) {
      path = "/" + path;
    }
    return path;
  };
  var normalizeQuery = function(query) {
    if (!query) {
      return "";
    }
    var q = "?";
    new _Object(query)._each(function(key, value) {
      if (isString(value) || isNumber(value) || isBoolean(value)) {
        q = q + encodeURIComponent(key) + "=" + encodeURIComponent(value) + "&";
      }
    });
    if (q === "?") {
      q = "";
    }
    return q.replace(/&$/, "");
  };
  var makeURL = function(protocol, domain, path, query) {
    domain = normalizeDomain(domain);
    var url = normalizePath(path) + normalizeQuery(query);
    if (domain) {
      url = protocol + domain + url;
    }
    return url;
  };
  var load = function(config, protocol, domains, path, query, cb, handleCb) {
    var tryRequest = function(at) {
      if (handleCb) {
        var cbName = "geetest_" + random();
        window2[cbName] = bind(handleCb, null, cbName);
        query.callback = cbName;
      }
      var url = makeURL(protocol, domains[at], path, query);
      loadScript(url, function(err) {
        if (err) {
          if (cbName) {
            try {
              window2[cbName] = function() {
                window2[cbName] = null;
              };
            } catch (e) {
            }
          }
          if (at >= domains.length - 1) {
            cb(true);
          } else {
            tryRequest(at + 1);
          }
        } else {
          cb(false);
        }
      }, config.timeout);
    };
    tryRequest(0);
  };
  var jsonp = function(domains, path, config, callback) {
    var handleCb = function(cbName, data) {
      if (data.status == "success") {
        callback(data.data);
      } else if (!data.status) {
        callback(data);
      } else {
        callback(data);
      }
      window2[cbName] = void 0;
      try {
        delete window2[cbName];
      } catch (e) {
      }
    };
    load(config, config.protocol, domains, path, {
      callback: "",
      captcha_id: config.captchaId,
      challenge: config.challenge || uuid(),
      client_type: MOBILE ? "h5" : "web",
      risk_type: config.riskType,
      user_info: config.userInfo,
      call_type: config.callType,
      lang: config.language ? config.language : navigator.appName === "Netscape" ? navigator.language.toLowerCase() : navigator.userLanguage.toLowerCase()
    }, function(err) {
      if (err && typeof config.offlineCb === "function") {
        config.offlineCb();
        return;
      }
      if (err) {
        callback(config._get_fallback_config());
      }
    }, handleCb);
  };
  var throwError = function(errorType, config, errObj) {
    var errors = {
      networkError: "缃戠粶閿欒",
      gtTypeError: "gt瀛楁涓嶆槸瀛楃涓茬被鍨�"
    };
    if (typeof config.onError === "function") {
      config.onError({
        desc: errObj.desc,
        msg: errObj.msg,
        code: errObj.code
      });
    } else {
      throw new Error(errors[errorType]);
    }
  };
  var detect = function() {
    return window2.Geetest || document2.getElementById("gt_lib");
  };
  if (detect()) {
    status.slide = "loaded";
  }
  var GeetestIsLoad = function(fname) {
    var GeetestIsLoad2 = false;
    var tags = { js: "script", css: "link" };
    var tagname = fname && tags[fname.split(".").pop()];
    if (tagname !== void 0) {
      var elts = document2.getElementsByTagName(tagname);
      for (var i in elts) {
        if (elts[i].href && elts[i].href.toString().indexOf(fname) > 0 || elts[i].src && elts[i].src.toString().indexOf(fname) > 0) {
          GeetestIsLoad2 = true;
        }
      }
    }
    return GeetestIsLoad2;
  };
  window2.initGeetest4 = function(userConfig, callback) {
    var config = new Config(userConfig);
    if (userConfig.https) {
      config.protocol = "https://";
    } else if (!userConfig.protocol) {
      config.protocol = window2.location.protocol + "//";
    }
    if (isObject(userConfig.getType)) {
      config._extend(userConfig.getType);
    }
    jsonp(config.apiServers, config.typePath, config, function(newConfig) {
      var newConfig = camelizeKeys(newConfig);
      if (newConfig.status === "error") {
        return throwError("networkError", config, newConfig);
      }
      var type = newConfig.type;
      if (config.debug) {
        new _Object(newConfig)._extend(config.debug);
      }
      var init = function() {
        config._extend(newConfig);
        callback(new window2.Geetest4(config));
      };
      callbacks[type] = callbacks[type] || [];
      var s = status[type] || "init";
      if (s === "init") {
        status[type] = "loading";
        callbacks[type].push(init);
        if (newConfig.gctPath) {
          load(config, config.protocol, Object.hasOwnProperty.call(config, "staticServers") ? config.staticServers : newConfig.staticServers || config.staticServers, newConfig.gctPath, null, function(err) {
            if (err) {
              throwError("networkError", config, {
                code: "60205",
                msg: "Network failure",
                desc: {
                  detail: "gct resource load timeout"
                }
              });
            }
          });
        }
        load(config, config.protocol, Object.hasOwnProperty.call(config, "staticServers") ? config.staticServers : newConfig.staticServers || config.staticServers, newConfig.bypass || newConfig.staticPath + newConfig.js, null, function(err) {
          if (err) {
            status[type] = "fail";
            throwError("networkError", config, {
              code: "60204",
              msg: "Network failure",
              desc: {
                detail: "js resource load timeout"
              }
            });
          } else {
            status[type] = "loaded";
            var cbs = callbacks[type];
            for (var i = 0, len = cbs.length; i < len; i = i + 1) {
              var cb = cbs[i];
              if (isFunction(cb)) {
                cb();
              }
            }
            callbacks[type] = [];
            status[type] = "init";
          }
        });
      } else if (s === "loaded") {
        if (newConfig.gctPath && !GeetestIsLoad(newConfig.gctPath)) {
          load(config, config.protocol, Object.hasOwnProperty.call(config, "staticServers") ? config.staticServers : newConfig.staticServers || config.staticServers, newConfig.gctPath, null, function(err) {
            if (err) {
              throwError("networkError", config, {
                code: "60205",
                msg: "Network failure",
                desc: {
                  detail: "gct resource load timeout"
                }
              });
            }
          });
        }
        return init();
      } else if (s === "fail") {
        throwError("networkError", config, {
          code: "60204",
          msg: "Network failure",
          desc: {
            detail: "js resource load timeout"
          }
        });
      } else if (s === "loading") {
        callbacks[type].push(init);
      }
    });
  };
})(window);
var utc$1 = { exports: {} };
(function(module, exports) {
  !function(t, i) {
    module.exports = i();
  }(commonjsGlobal, function() {
    var t = "minute", i = /[+-]\d\d(?::?\d\d)?/g, e = /([+-]|\d\d)/g;
    return function(s, f, n) {
      var u = f.prototype;
      n.utc = function(t2) {
        var i2 = { date: t2, utc: true, args: arguments };
        return new f(i2);
      }, u.utc = function(i2) {
        var e2 = n(this.toDate(), { locale: this.$L, utc: true });
        return i2 ? e2.add(this.utcOffset(), t) : e2;
      }, u.local = function() {
        return n(this.toDate(), { locale: this.$L, utc: false });
      };
      var o = u.parse;
      u.parse = function(t2) {
        t2.utc && (this.$u = true), this.$utils().u(t2.$offset) || (this.$offset = t2.$offset), o.call(this, t2);
      };
      var r = u.init;
      u.init = function() {
        if (this.$u) {
          var t2 = this.$d;
          this.$y = t2.getUTCFullYear(), this.$M = t2.getUTCMonth(), this.$D = t2.getUTCDate(), this.$W = t2.getUTCDay(), this.$H = t2.getUTCHours(), this.$m = t2.getUTCMinutes(), this.$s = t2.getUTCSeconds(), this.$ms = t2.getUTCMilliseconds();
        } else r.call(this);
      };
      var a = u.utcOffset;
      u.utcOffset = function(s2, f2) {
        var n2 = this.$utils().u;
        if (n2(s2)) return this.$u ? 0 : n2(this.$offset) ? a.call(this) : this.$offset;
        if ("string" == typeof s2 && (s2 = function(t2) {
          void 0 === t2 && (t2 = "");
          var s3 = t2.match(i);
          if (!s3) return null;
          var f3 = ("" + s3[0]).match(e) || ["-", 0, 0], n3 = f3[0], u3 = 60 * +f3[1] + +f3[2];
          return 0 === u3 ? 0 : "+" === n3 ? u3 : -u3;
        }(s2), null === s2)) return this;
        var u2 = Math.abs(s2) <= 16 ? 60 * s2 : s2, o2 = this;
        if (f2) return o2.$offset = u2, o2.$u = 0 === s2, o2;
        if (0 !== s2) {
          var r2 = this.$u ? this.toDate().getTimezoneOffset() : -1 * this.utcOffset();
          (o2 = this.local().add(u2 + r2, t)).$offset = u2, o2.$x.$localOffset = r2;
        } else o2 = this.utc();
        return o2;
      };
      var h = u.format;
      u.format = function(t2) {
        var i2 = t2 || (this.$u ? "YYYY-MM-DDTHH:mm:ss[Z]" : "");
        return h.call(this, i2);
      }, u.valueOf = function() {
        var t2 = this.$utils().u(this.$offset) ? 0 : this.$offset + (this.$x.$localOffset || this.$d.getTimezoneOffset());
        return this.$d.valueOf() - 6e4 * t2;
      }, u.isUTC = function() {
        return !!this.$u;
      }, u.toISOString = function() {
        return this.toDate().toISOString();
      }, u.toString = function() {
        return this.toDate().toUTCString();
      };
      var l = u.toDate;
      u.toDate = function(t2) {
        return "s" === t2 && this.$offset ? n(this.format("YYYY-MM-DD HH:mm:ss:SSS")).toDate() : l.call(this);
      };
      var c = u.diff;
      u.diff = function(t2, i2, e2) {
        if (t2 && this.$u === t2.$u) return c.call(this, t2, i2, e2);
        var s2 = this.local(), f2 = n(t2).local();
        return c.call(s2, f2, i2, e2);
      };
    };
  });
})(utc$1);
var utcExports = utc$1.exports;
const utc = /* @__PURE__ */ getDefaultExportFromCjs(utcExports);
var timezone$1 = { exports: {} };
(function(module, exports) {
  !function(t, e) {
    module.exports = e();
  }(commonjsGlobal, function() {
    var t = { year: 0, month: 1, day: 2, hour: 3, minute: 4, second: 5 }, e = {};
    return function(n, i, o) {
      var r, a = function(t2, n2, i2) {
        void 0 === i2 && (i2 = {});
        var o2 = new Date(t2), r2 = function(t3, n3) {
          void 0 === n3 && (n3 = {});
          var i3 = n3.timeZoneName || "short", o3 = t3 + "|" + i3, r3 = e[o3];
          return r3 || (r3 = new Intl.DateTimeFormat("en-US", { hour12: false, timeZone: t3, year: "numeric", month: "2-digit", day: "2-digit", hour: "2-digit", minute: "2-digit", second: "2-digit", timeZoneName: i3 }), e[o3] = r3), r3;
        }(n2, i2);
        return r2.formatToParts(o2);
      }, u = function(e2, n2) {
        for (var i2 = a(e2, n2), r2 = [], u2 = 0; u2 < i2.length; u2 += 1) {
          var f2 = i2[u2], s2 = f2.type, m = f2.value, c = t[s2];
          c >= 0 && (r2[c] = parseInt(m, 10));
        }
        var d = r2[3], l = 24 === d ? 0 : d, h = r2[0] + "-" + r2[1] + "-" + r2[2] + " " + l + ":" + r2[4] + ":" + r2[5] + ":000", v = +e2;
        return (o.utc(h).valueOf() - (v -= v % 1e3)) / 6e4;
      }, f = i.prototype;
      f.tz = function(t2, e2) {
        void 0 === t2 && (t2 = r);
        var n2, i2 = this.utcOffset(), a2 = this.toDate(), u2 = a2.toLocaleString("en-US", { timeZone: t2 }), f2 = Math.round((a2 - new Date(u2)) / 1e3 / 60), s2 = 15 * -Math.round(a2.getTimezoneOffset() / 15) - f2;
        if (!Number(s2)) n2 = this.utcOffset(0, e2);
        else if (n2 = o(u2, { locale: this.$L }).$set("millisecond", this.$ms).utcOffset(s2, true), e2) {
          var m = n2.utcOffset();
          n2 = n2.add(i2 - m, "minute");
        }
        return n2.$x.$timezone = t2, n2;
      }, f.offsetName = function(t2) {
        var e2 = this.$x.$timezone || o.tz.guess(), n2 = a(this.valueOf(), e2, { timeZoneName: t2 }).find(function(t3) {
          return "timezonename" === t3.type.toLowerCase();
        });
        return n2 && n2.value;
      };
      var s = f.startOf;
      f.startOf = function(t2, e2) {
        if (!this.$x || !this.$x.$timezone) return s.call(this, t2, e2);
        var n2 = o(this.format("YYYY-MM-DD HH:mm:ss:SSS"), { locale: this.$L });
        return s.call(n2, t2, e2).tz(this.$x.$timezone, true);
      }, o.tz = function(t2, e2, n2) {
        var i2 = n2 && e2, a2 = n2 || e2 || r, f2 = u(+o(), a2);
        if ("string" != typeof t2) return o(t2).tz(a2);
        var s2 = function(t3, e3, n3) {
          var i3 = t3 - 60 * e3 * 1e3, o2 = u(i3, n3);
          if (e3 === o2) return [i3, e3];
          var r2 = u(i3 -= 60 * (o2 - e3) * 1e3, n3);
          return o2 === r2 ? [i3, o2] : [t3 - 60 * Math.min(o2, r2) * 1e3, Math.max(o2, r2)];
        }(o.utc(t2, i2).valueOf(), f2, a2), m = s2[0], c = s2[1], d = o(m).utcOffset(c);
        return d.$x.$timezone = a2, d;
      }, o.tz.guess = function() {
        return Intl.DateTimeFormat().resolvedOptions().timeZone;
      }, o.tz.setDefault = function(t2) {
        r = t2;
      };
    };
  });
})(timezone$1);
var timezoneExports = timezone$1.exports;
const timezone = /* @__PURE__ */ getDefaultExportFromCjs(timezoneExports);
const svg_theme_fill_color$1 = "_svg_theme_fill_color_t5y6x_3";
const h5_skeleton = "_h5_skeleton_t5y6x_55";
const headerBox$1 = "_headerBox_t5y6x_59";
const leftIcon = "_leftIcon_t5y6x_71";
const rightBox$1 = "_rightBox_t5y6x_77";
const rightItemBox1 = "_rightItemBox1_t5y6x_81";
const rightItemBox2 = "_rightItemBox2_t5y6x_87";
const carouselBox$1 = "_carouselBox_t5y6x_93";
const noticeBox$1 = "_noticeBox_t5y6x_101";
const noticeItemBox1$1 = "_noticeItemBox1_t5y6x_111";
const noticeItemBox2$1 = "_noticeItemBox2_t5y6x_116";
const typeSelectBox$1 = "_typeSelectBox_t5y6x_121";
const typeSelectItemBox$1 = "_typeSelectItemBox_t5y6x_131";
const gameLogoBox = "_gameLogoBox_t5y6x_136";
const gameLogoItem1 = "_gameLogoItem1_t5y6x_146";
const gameLogoItem2 = "_gameLogoItem2_t5y6x_151";
const gameListBox$1 = "_gameListBox_t5y6x_157";
const gameListItemBox = "_gameListItemBox_t5y6x_168";
const css$1 = {
  svg_theme_fill_color: svg_theme_fill_color$1,
  h5_skeleton,
  headerBox: headerBox$1,
  leftIcon,
  rightBox: rightBox$1,
  rightItemBox1,
  rightItemBox2,
  carouselBox: carouselBox$1,
  noticeBox: noticeBox$1,
  noticeItemBox1: noticeItemBox1$1,
  noticeItemBox2: noticeItemBox2$1,
  typeSelectBox: typeSelectBox$1,
  typeSelectItemBox: typeSelectItemBox$1,
  gameLogoBox,
  gameLogoItem1,
  gameLogoItem2,
  gameListBox: gameListBox$1,
  gameListItemBox
};
const H5Skeleton = () => {
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css$1.h5_skeleton, "skeletonBoxLightBg2"), children: [
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css$1.headerBox, "skeletonBoxLightBg"), children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.leftIcon, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.rightBox, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.rightItemBox1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.rightItemBox1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.rightItemBox1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.rightItemBox2, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) })
      ] })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.carouselBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.noticeBox, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.noticeItemBox1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.noticeItemBox2, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.typeSelectBox, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.typeSelectItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.typeSelectItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.typeSelectItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.typeSelectItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.typeSelectItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.carouselBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.gameLogoBox, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameLogoItem1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameLogoItem2, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.gameListBox, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameListItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameListItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameListItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameListItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameListItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameListItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.gameLogoBox, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameLogoItem1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameLogoItem2, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.gameListBox, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameListItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameListItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameListItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameListItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameListItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameListItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.gameLogoBox, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameLogoItem1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameLogoItem2, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.gameListBox, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameListItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameListItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameListItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameListItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameListItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameListItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.gameLogoBox, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameLogoItem1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameLogoItem2, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.gameListBox, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameListItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameListItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameListItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameListItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameListItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$1.gameListItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) })
    ] })
  ] });
};
const svg_theme_fill_color = "_svg_theme_fill_color_ovjwc_3";
const pc_skeleton = "_pc_skeleton_ovjwc_55";
const headerBox = "_headerBox_ovjwc_61";
const headerBoxInBox = "_headerBoxInBox_ovjwc_65";
const logoItem = "_logoItem_ovjwc_71";
const contentBox = "_contentBox_ovjwc_76";
const blanceBox = "_blanceBox_ovjwc_85";
const headerRightItemBox1 = "_headerRightItemBox1_ovjwc_91";
const headerRightItemBox2 = "_headerRightItemBox2_ovjwc_97";
const headerRightItemBox3 = "_headerRightItemBox3_ovjwc_103";
const item3_1 = "_item3_1_ovjwc_111";
const item3_2 = "_item3_2_ovjwc_116";
const leftBox = "_leftBox_ovjwc_121";
const rightBox = "_rightBox_ovjwc_128";
const btmContentBox = "_btmContentBox_ovjwc_133";
const btmContentBoxIn = "_btmContentBoxIn_ovjwc_136";
const sliderBox = "_sliderBox_ovjwc_142";
const sliderItemBox1 = "_sliderItemBox1_ovjwc_151";
const sliderItemBox2 = "_sliderItemBox2_ovjwc_159";
const sliderItemBox3 = "_sliderItemBox3_ovjwc_165";
const sliderItemBox3_1 = "_sliderItemBox3_1_ovjwc_174";
const sliderItemBox3_2 = "_sliderItemBox3_2_ovjwc_180";
const contentBox2 = "_contentBox2_ovjwc_185";
const carouselBox = "_carouselBox_ovjwc_193";
const underBox = "_underBox_ovjwc_198";
const upBox = "_upBox_ovjwc_205";
const noticeBox = "_noticeBox_ovjwc_213";
const noticeItemBox1 = "_noticeItemBox1_ovjwc_222";
const noticeItemBox2 = "_noticeItemBox2_ovjwc_229";
const typeSelectBox = "_typeSelectBox_ovjwc_234";
const typeSelectItemBox = "_typeSelectItemBox_ovjwc_244";
const gameListBox = "_gameListBox_ovjwc_250";
const jockpotBox = "_jockpotBox_ovjwc_256";
const gameItemBox = "_gameItemBox_ovjwc_264";
const gameItemTitleBox = "_gameItemTitleBox_ovjwc_274";
const titleBox1 = "_titleBox1_ovjwc_281";
const titleBox2 = "_titleBox2_ovjwc_287";
const listItemBox = "_listItemBox_ovjwc_292";
const gameItem = "_gameItem_ovjwc_264";
const css = {
  svg_theme_fill_color,
  pc_skeleton,
  headerBox,
  headerBoxInBox,
  logoItem,
  contentBox,
  blanceBox,
  headerRightItemBox1,
  headerRightItemBox2,
  headerRightItemBox3,
  item3_1,
  item3_2,
  leftBox,
  rightBox,
  btmContentBox,
  btmContentBoxIn,
  sliderBox,
  sliderItemBox1,
  sliderItemBox2,
  sliderItemBox3,
  sliderItemBox3_1,
  sliderItemBox3_2,
  contentBox2,
  carouselBox,
  underBox,
  upBox,
  noticeBox,
  noticeItemBox1,
  noticeItemBox2,
  typeSelectBox,
  typeSelectItemBox,
  gameListBox,
  jockpotBox,
  gameItemBox,
  gameItemTitleBox,
  titleBox1,
  titleBox2,
  listItemBox,
  gameItem
};
const PcSkeleton = () => {
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css.pc_skeleton, "skeletonPageBg"), children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.headerBox, "skeletonBoxLightBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.headerBoxInBox, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.leftBox, children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.logoItem, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.rightBox, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.contentBox, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.blanceBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.headerRightItemBox1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.headerRightItemBox1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.headerRightItemBox1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.headerRightItemBox2, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css.headerRightItemBox3), children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.item3_1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.item3_2, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) })
        ] })
      ] }) })
    ] }) }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.btmContentBox, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.btmContentBoxIn, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css.sliderBox, "skeletonBoxLightBg2"), children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox2, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox2, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.sliderItemBox3, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox3_1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox3_2, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) })
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.sliderItemBox3, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox3_1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox3_2, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) })
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.sliderItemBox3, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox3_1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox3_2, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) })
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.sliderItemBox3, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox3_1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox3_2, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) })
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.sliderItemBox3, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox3_1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox3_2, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) })
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.sliderItemBox3, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox3_1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox3_2, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) })
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.sliderItemBox3, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox3_1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.sliderItemBox3_2, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) })
        ] })
      ] }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.rightBox, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.contentBox2, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css.noticeBox, "skeletonBoxLightBg2"), children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.noticeItemBox1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.noticeItemBox2, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) })
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css.typeSelectBox, "skeletonBoxLightBg2"), children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.typeSelectItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.typeSelectItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.typeSelectItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.typeSelectItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.typeSelectItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.typeSelectItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.typeSelectItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.typeSelectItemBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) })
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css.gameListBox, "skeletonBoxLightBg2"), children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.jockpotBox, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
          (() => new Array(4).fill(1))().map((game, _idx) => {
            return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.gameItemBox, children: [
              /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.gameItemTitleBox, children: [
                /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.titleBox1, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }),
                /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.titleBox2, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) })
              ] }),
              /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.listItemBox, children: (() => new Array(12).fill(1))().map((item, idx) => {
                return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.gameItem, "skeletonBg"), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "skeleton-paragraph" }) }, idx);
              }) })
            ] }, _idx);
          })
        ] })
      ] }) })
    ] }) })
  ] });
};
adaptNotchScreen();
addPagePV();
addPageUV();
const startPage = document.getElementById("start_page");
if (startPage) {
  document.body.removeChild(startPage);
}
const timerOBJ = {
  BR: "America/Sao_Paulo",
  PH: "Asia/Manila",
  VN: "Asia/Ho_Chi_Minh",
  PK: "Asia/Karachi"
};
dayjs.extend(utc);
dayjs.extend(timezone);
dayjs.tz.setDefault(timerOBJ["BR"]);
let isH5 = getWebType();
Cache.set("isH5", isH5);
saveUrlParams();
const linkId = getQueryVariable("id") || "";
if (linkId) {
  Cache.set("link_id", linkId);
}
(() => {
  const fbc = getQueryVariable("fbc") || "";
  if (fbc) {
    Cache.set("fbc", fbc);
  }
  const fbp = getQueryVariable("fbp") || "";
  if (fbp) {
    Cache.set("fbp", fbp);
  }
  const fbclid = getQueryVariable("fbclid") || "";
  if (fbclid) {
    Cache.set("fbclid", fbclid);
  }
})();
(() => {
  const ttclid = getQueryVariable("ttclid") || "";
  if (ttclid) {
    Cache.set("ttclid", ttclid);
  }
})();
getPageHtmlFontSize();
window.addEventListener("resize", () => {
  getPageHtmlFontSize();
});
const viewPath = (() => {
  const temp = +isWithPc === 1 ? tempH5 : tempPC;
  if (temp.startsWith("1")) {
    return "views";
  }
  return "views_u8";
})();
const App = reactExports.lazy(() => new Promise((resolve) => {
  console.log("__SiteCountry__ :", "BR");
  Promise.all([__variableDynamicImportRuntimeHelper(/* @__PURE__ */ Object.assign({ "./views/h5/App.jsx": () => __vitePreload(() => import("./App-DNWaJ6B8-2024_12_14_18_4.js"), true ? __vite__mapDeps([0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34]) : void 0), "./views/pc/App.jsx": () => __vitePreload(() => import("./App-CvhD5z2--2024_12_14_18_4.js"), true ? __vite__mapDeps([35,1,2,3,4,36,12,13,37,38,39,22,23,40,41,42,43,44,45,46,47]) : void 0), "./views_u8/h5/App.jsx": () => __vitePreload(() => import("./App-olCRdXRx-2024_12_14_18_4.js").then((n) => n.A), true ? __vite__mapDeps([48,1,2,3,4,49,18,12,13,50,5,6,7,8,9,10,11,14,15,16,17,19,51,52,53]) : void 0), "./views_u8/pc/App.jsx": () => __vitePreload(() => import("./App-B2yHIxbX-2024_12_14_18_4.js").then((n) => n.A), true ? __vite__mapDeps([54,1,2,3,4,14,12,13,15,10,11,36,37,38,39,55,56,57]) : void 0) }), "./".concat(viewPath, "/").concat(+isWithPc === 1 ? "h5" : "pc", "/App.jsx"), 4), languageLoaded()]).then(async ([comp]) => {
    const [res, err] = await memberWebsetList();
    console.log("websetConfig :>> ", res);
    if (res) {
      res.site_uphold = res.site_uphold ? JSON.parse(res.site_uphold) : {};
      res.nav_bottom_h5 = res.nav_bottom_h5 ? JSON.parse(res.nav_bottom_h5) : [];
      const img_shape = res.img_shape || "0";
      if (img_shape === "0") {
        useGameStore.setState({ renderType: "" });
      }
      if (img_shape === "1") {
        useGameStore.setState({ renderType: "rect" });
      }
      const float = res.float || [];
      float.sort((a, b) => a.sort - b.sort);
      const share = res.share || [];
      share.sort((a, b) => a.sort - b.sort);
      useFloatPopShareStore.setState({ float, share });
      const pop = res.pop || [];
      pop.sort((a, b) => a.sort - b.sort);
      useFloatPopShareStore.setState({ pop });
      const holidayIsOpen = {};
      if (res.holidayTheme) {
        const holidayTheme = JSON.parse(res.holidayTheme);
        const christmas = holidayTheme.find((item) => item.value === "christmas");
        if (christmas && christmas.theme_switch == "1" && (dayjs(christmas.start_time).valueOf() < dayjs().valueOf() && dayjs().valueOf() < dayjs(christmas.end_time).valueOf())) {
          holidayIsOpen.festival_christmas = true;
          document.documentElement.setAttribute("festival", "christmas");
          console.log("圣诞主题已开启 :>> ");
        }
      }
      console.log("holidayIsOpen :>> ", holidayIsOpen);
      useUserInfoStore.setState({ websetConfig: {
        ...res,
        ...holidayIsOpen
      }, prefix: res.prefix || "", logRegisterStyle: res.logRegisterStyle || 1 });
    }
    resolve(comp);
  });
}));
window.onload = function() {
  if (location.href.includes("gameStart")) {
    location.href = "/";
  }
};
document.documentElement.className = "h5";
document.documentElement.dir = localStorage.getItem("i18nextLng") == "ur" ? "rtl" : "ltr";
new initPixi();
client.createRoot(document.getElementById("root")).render(
  /* @__PURE__ */ jsxRuntimeExports.jsx(BrowserRouter, { children: viewPath == "views_u8" ? /* @__PURE__ */ jsxRuntimeExports.jsx(App, {}) : /* @__PURE__ */ jsxRuntimeExports.jsx(reactExports.Suspense, { fallback: +isWithPc === 1 ? /* @__PURE__ */ jsxRuntimeExports.jsx(H5Skeleton, {}) : /* @__PURE__ */ jsxRuntimeExports.jsx(PcSkeleton, {}), children: /* @__PURE__ */ jsxRuntimeExports.jsx(App, {}) }) })
);
export {
  H5Skeleton as H,
  __variableDynamicImportRuntimeHelper as _,
  __vite_legacy_guard
};
