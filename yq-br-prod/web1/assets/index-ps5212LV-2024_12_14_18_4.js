import { q as useUserInfoStore, S as useThreeLoginStore, a9 as useReactive, j as jsxRuntimeExports, z as trans, a as joinClass, I as Image, b_ as initGoogleSDK, b$ as renderGoogleBtn, c0 as initFacebookSDK, c1 as facebookLogin, F as slideBlockByGeetest4, H as Message, c2 as postThreeLogin } from "./comps-B8ShbmG--2024_12_14_18_4.js";
const svg_theme_fill_color = "_svg_theme_fill_color_wopgn_3";
const threeLoginBox = "_threeLoginBox_wopgn_55";
const topTips = "_topTips_wopgn_60";
const tipLIne = "_tipLIne_wopgn_68";
const tipSpan = "_tipSpan_wopgn_163";
const threeBtnsBox = "_threeBtnsBox_wopgn_258";
const googleBox = "_googleBox_wopgn_266";
const showLoaing = "_showLoaing_wopgn_281";
const moveBox = "_moveBox_wopgn_356";
const threeLoginMoverotation = "_threeLoginMoverotation_wopgn_1";
const loadingInBox = "_loadingInBox_wopgn_476";
const logo = "_logo_wopgn_599";
const emptyBox = "_emptyBox_wopgn_604";
const css = {
  svg_theme_fill_color,
  threeLoginBox,
  topTips,
  tipLIne,
  tipSpan,
  threeBtnsBox,
  googleBox,
  showLoaing,
  moveBox,
  threeLoginMoverotation,
  loadingInBox,
  logo,
  emptyBox
};
const ThreeLogin = ({ isLogin, isHook }) => {
  const { websetConfig } = useUserInfoStore();
  const { setThreeLoginInfo, setOpenForRegisterByThree } = useThreeLoginStore();
  const state = useReactive({
    waitingLoading: false
  });
  const { waitingLoading } = state;
  const postThreeLoginFun = async (params) => {
    const [...res] = await postThreeLogin(params);
    return [...res];
  };
  const handleThreeLogin = async (res, provider) => {
    const credential = res.credential || "";
    let rest = null;
    const postParams = { provider, id_token: credential };
    if (websetConfig.authLogReg !== "1") {
      const [ret1] = await postThreeLoginFun(postParams);
      rest = ret1;
    } else {
      const result = await slideBlockByGeetest4(websetConfig.authLogRegType);
      if (result) {
        const [ret2] = await postThreeLoginFun(Object.assign(postParams, {
          pass_token: result.pass_token,
          lot_number: result.lot_number,
          captcha_output: result.captcha_output,
          gen_time: result.gen_time
        }));
        rest = ret2;
      }
    }
    if (rest === "1000") {
      useUserInfoStore.setState({ openForRegister: false });
      useUserInfoStore.setState({ openForLogin: false });
      useUserInfoStore.setState({ alertAfterLogin: true });
      Message.success(trans("登录成功"));
      if (window.jsBridge && window.jsBridge.postMessage) {
        try {
          window.jsBridge.postMessage("login", "{}");
        } catch (e) {
          console.log(e);
        }
      }
    }
    if (rest && rest.need_register) {
      const params = {
        provider,
        id_token: credential,
        email: rest.email || "",
        password: rest.password,
        username: rest.username
      };
      setThreeLoginInfo(params);
      useUserInfoStore.setState({ openForLogin: false });
      useUserInfoStore.setState({ openForRegister: false });
      setOpenForRegisterByThree(true);
    }
  };
  const onClickGoogleLogin = async () => {
    state.waitingLoading = true;
    await initGoogleSDK();
    const res = await renderGoogleBtn(websetConfig.googleH5AppID);
    await handleThreeLogin(res, "google");
    state.waitingLoading = false;
  };
  const onClickFaceBookLogin = async () => {
    await initFacebookSDK();
    const res = await facebookLogin(websetConfig.facebookH5AppID);
    await handleThreeLogin({ credential: res.accessToken }, "facebook");
  };
  const isColseGoogleLogin = !websetConfig.googleQuickLogin || websetConfig.googleQuickLogin === "0";
  const isCloseFacebookLogin = !websetConfig.facebookQuickLogin || websetConfig.facebookQuickLogin === "0";
  if (isHook) return {
    google: [onClickGoogleLogin, isColseGoogleLogin],
    facebook: [onClickFaceBookLogin, isCloseFacebookLogin]
  };
  if (isColseGoogleLogin && isCloseFacebookLogin) return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.emptyBox });
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.threeLoginBox, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.topTips, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.tipLIne }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css.tipSpan, children: isLogin ? trans("快速登录") : trans("注册绑定") }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.tipLIne })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.threeBtnsBox, children: [
      !isColseGoogleLogin && /* @__PURE__ */ jsxRuntimeExports.jsxs(
        "div",
        {
          className: joinClass(css.googleBox, waitingLoading ? css.showLoaing : ""),
          onClick: async () => {
            await onClickGoogleLogin();
          },
          children: [
            waitingLoading ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.moveBox }) : null,
            /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.loadingInBox, children: /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { className: css.logo, src: "/home/loginreg/google_icon.webp" }) })
          ]
        }
      ),
      !isCloseFacebookLogin && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.googleBox, onClick: async () => {
        await onClickFaceBookLogin();
      }, children: /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { className: css.logo, src: "/home/loginreg/facebook_icon.webp" }) })
    ] })
  ] });
};
export {
  ThreeLogin as T
};
