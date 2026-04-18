import { q as useUserInfoStore, o as useGameStore, r as reactExports, C as Cache$1, y as useNavigate, j as jsxRuntimeExports, M as Modal, z as trans, A as checkInputUserName, a as joinClass, D as checkInputUserPwd, E as CheckBox, F as slideBlockByGeetest4, G as login, H as Message, I as Image, J as useSearchParams, K as checkPasswordStrength, L as checkInputPhoneNumber, N as getCountryFlag, O as countryCode, P as curCountryNameReg, Q as getMoneyUnit, R as register, S as useThreeLoginStore, T as copyText, U as saveImgByElement, V as useTranslation, W as useClickAway, X as getLineClass, Y as getRandomInt, Z as useLocation, $ as useMusicStore, a0 as useWebsetConfig, a1 as useGuideStore, a2 as useNavigateToActivity, a3 as sortGameTabs, a4 as instance, a5 as scrollToPlatromItem, a6 as desktopOpen, h as getWebType, a7 as getBrowser, a8 as useAlertStore, a9 as useReactive, p as useFloatPopShareStore, aa as minPxChip, f as dayjs, ab as getPromoDetail, ac as useSetState, u as useAsyncEffect, n as memberWebsetList, ad as getNoticeList, ae as preloadImage, af as getMemberCustomerList, ag as reactDomExports, ah as useGetState, ai as useEventListener, aj as browser, ak as getH5Type, al as postRecallbalance, am as Container, an as ChangeTheme, ao as ShowAllSVG } from "./comps-B8ShbmG--2024_12_14_18_4.js";
import { r as routesMaps, L as LoginWheelDialog, R as RouterApp } from "./index-DgUEEG5U-2024_12_14_18_4.js";
import { a as CloseIconInLogin, A as ArrowLeftInMineIcon, U as UserFullIcon, L as LockIcon, D as DownIcon, P as PasswordFullIcon, b as PhoneIcon, c as PixAccountIcon, d as Login$4, e as Register$3, f as CloseIconInMineIcon, E as EditString, R as RectCopyIcon, g as CloseModal, h as LinhaIcon, i as LinhaSelectIcon, j as AudioPreIcon, k as AudioPlayerIcon, l as AudioPausedIcon, m as AudioNextIcon, n as AudioLoopIcon, o as AudioRandomIcon, p as AudioOneceIcon, q as AudioMenuIcon, I as IosIcon, r as AndroidIcon, s as AudioVolumIcon, t as AudioNoVolumIcon, u as AudioMusicIcon, v as AudioStarIcon, w as AudioDeleteIcon } from "./icons-Cdaou_E3-2024_12_14_18_4.js";
import { F as Form } from "./index-CsbwU_08-2024_12_14_18_4.js";
import { I as Input, F as FormContext } from "./index-BcpFmBv5-2024_12_14_18_4.js";
import { B as Button } from "./index-CGmeRIXa-2024_12_14_18_4.js";
import { T as ThreeLogin } from "./index-ps5212LV-2024_12_14_18_4.js";
import { l as lineCss } from "./line.module-BFLXfPKU-2024_12_14_18_4.js";
import { G as GameTabs, T as ToolTabs, g as gameTypeNames, I as InfoTabs } from "./config-B4NCyDU0-2024_12_14_18_4.js";
import { L as Language } from "./index-D_urebYV-2024_12_14_18_4.js";
import { D as DiaDmWheel } from "./index-CwqwqHfm-2024_12_14_18_4.js";
import { G as Guide } from "./index-DLcyu0vi-2024_12_14_18_4.js";
import "./index-CXpt7Cav-2024_12_14_18_4.js";
import "./index-DyG_R4zg-2024_12_14_18_4.js";
import "./index-4Dep-BeX-2024_12_14_18_4.js";
import "./index-Bq-a07OY-2024_12_14_18_4.js";
import "./index-CezjVG8f-2024_12_14_18_4.js";
import "./index-oTqPuerr-2024_12_14_18_4.js";
const svg_theme_fill_color$u = "_svg_theme_fill_color_m6u5o_3";
const h5PageContentBox = "_h5PageContentBox_m6u5o_55";
const googleTipBnt = "_googleTipBnt_m6u5o_67";
const css$t = {
  svg_theme_fill_color: svg_theme_fill_color$u,
  h5PageContentBox,
  googleTipBnt
};
const svg_theme_fill_color$t = "_svg_theme_fill_color_k6pzx_3";
const loginBox$a = "_loginBox_k6pzx_55";
const submitButton$5 = "_submitButton_k6pzx_68";
const loginInput$7 = "_loginInput_k6pzx_72";
const inputIcon$5 = "_inputIcon_k6pzx_79";
const clsoeBtn$b = "_clsoeBtn_k6pzx_85";
const titleBox$7 = "_titleBox_k6pzx_105";
const titleContent$3 = "_titleContent_k6pzx_117";
const titleIcon$1 = "_titleIcon_k6pzx_128";
const icon$a = "_icon_k6pzx_140";
const icon2$9 = "_icon2_k6pzx_147";
const rolesBox$6 = "_rolesBox_k6pzx_162";
const tipsSpan$3 = "_tipsSpan_k6pzx_174";
const btmTextBox$6 = "_btmTextBox_k6pzx_184";
const noMarLeft$6 = "_noMarLeft_k6pzx_200";
const img1$7 = "_img1_k6pzx_203";
const wg3Back$3 = "_wg3Back_k6pzx_210";
const css$s = {
  svg_theme_fill_color: svg_theme_fill_color$t,
  loginBox: loginBox$a,
  submitButton: submitButton$5,
  loginInput: loginInput$7,
  inputIcon: inputIcon$5,
  clsoeBtn: clsoeBtn$b,
  titleBox: titleBox$7,
  titleContent: titleContent$3,
  titleIcon: titleIcon$1,
  icon: icon$a,
  icon2: icon2$9,
  rolesBox: rolesBox$6,
  tipsSpan: tipsSpan$3,
  btmTextBox: btmTextBox$6,
  noMarLeft: noMarLeft$6,
  img1: img1$7,
  wg3Back: wg3Back$3
};
const Login$3 = () => {
  const { openForLogin, token, theme, websetConfig } = useUserInfoStore();
  const { games } = useGameStore();
  const [form] = Form.useForm();
  const [loading2, setLoading] = reactExports.useState(false);
  const [isSave, setIsSave] = reactExports.useState(Cache$1.get("isSave") || false);
  const navigate = useNavigate();
  const postLogin = async (params) => {
    const [res, error] = await login(params, { useLoading: true });
    if (!error) {
      Message.success(trans("登录成功"));
      if (window.jsBridge && window.jsBridge.postMessage) {
        try {
          window.jsBridge.postMessage("login", "{}");
        } catch (e) {
          console.log(e);
        }
      }
      useUserInfoStore.setState({ openForLogin: false });
      useUserInfoStore.setState({ alertAfterLogin: true });
      if (isSave) {
        Cache$1.set("UserName", form.getFieldValue("username"));
        Cache$1.set("UserPwd", form.getFieldValue("password"));
      }
    }
  };
  const submit = async (params) => {
    setLoading(true);
    if (websetConfig.authLogReg !== "1") {
      await postLogin(params);
    } else {
      const result = await slideBlockByGeetest4(websetConfig.authLogRegType);
      if (result) {
        await postLogin(Object.assign(params, {
          pass_token: result.pass_token,
          lot_number: result.lot_number,
          captcha_output: result.captcha_output,
          gen_time: result.gen_time
        }));
      }
    }
    setLoading(false);
  };
  return /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, { children: /* @__PURE__ */ jsxRuntimeExports.jsx(
    Modal,
    {
      isOpen: openForLogin,
      closeByClickOut: false,
      showType: document.documentElement.getAttribute("temph5") === "12" ? "right" : "",
      onClose: (e) => {
        e && e.stopPropagation();
        useUserInfoStore.setState({ openForLogin: false });
      },
      children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$s.loginBox, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          "div",
          {
            className: css$s.clsoeBtn,
            onClick: () => {
              useUserInfoStore.setState({ openForLogin: false });
              const googleDom = document.getElementById("g_id_signIn");
              if (googleDom) googleDom.innerHTML = "";
            },
            children: /* @__PURE__ */ jsxRuntimeExports.jsx(CloseIconInLogin, {})
          }
        ),
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          "div",
          {
            className: css$s.wg3Back,
            onClick: () => {
              useUserInfoStore.setState({ openForLogin: false });
              const googleDom = document.getElementById("g_id_signIn");
              if (googleDom) googleDom.innerHTML = "";
            },
            children: /* @__PURE__ */ jsxRuntimeExports.jsx(ArrowLeftInMineIcon, {})
          }
        ),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$s.titleBox, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$s.titleContent, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx(UserFullIcon, { className: css$s.titleIcon }),
          trans("登入")
        ] }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs(
          "div",
          {
            onClick: (e) => {
              e.stopPropagation();
            },
            children: [
              /* @__PURE__ */ jsxRuntimeExports.jsxs(
                Form,
                {
                  form,
                  initialValues: {
                    username: isSave ? Cache$1.get("UserName") : "",
                    password: isSave ? Cache$1.get("UserPwd") : ""
                  },
                  onFinish: (values) => submit(values),
                  onFinishFailed: ({ values, errorFields, outOfDate }) => {
                    console.log("Failed:", values, errorFields, outOfDate);
                  },
                  onValuesChange: (changedValues, allValues) => {
                    if (changedValues.username) {
                      form.setFieldsValue({
                        username: changedValues.username.toLowerCase().trim()
                      });
                    }
                  },
                  children: [
                    /* @__PURE__ */ jsxRuntimeExports.jsx(
                      Form.Field,
                      {
                        name: "username",
                        defaultShowError: true,
                        rules: [
                          { required: true, message: trans("请输入用户名") },
                          {
                            validator: (rule, value) => {
                              const error = checkInputUserName(value);
                              if (!error) {
                                return Promise.resolve();
                              }
                              return Promise.reject(error);
                            }
                          }
                        ],
                        children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                          Input,
                          {
                            placeholder: trans("用户名"),
                            clearable: true,
                            focusBorder: true,
                            required: true,
                            className: css$s.loginInput,
                            prefix: /* @__PURE__ */ jsxRuntimeExports.jsx(UserFullIcon, { className: joinClass(css$s.img1, css$s.inputIcon) })
                          }
                        )
                      }
                    ),
                    /* @__PURE__ */ jsxRuntimeExports.jsx(
                      Form.Field,
                      {
                        name: "password",
                        defaultShowError: true,
                        rules: [
                          { required: true, message: trans("请输入密码") },
                          {
                            validator: (rule, value) => {
                              const error = checkInputUserPwd(value);
                              if (!error) {
                                return Promise.resolve();
                              }
                              return Promise.reject(error);
                            }
                          }
                        ],
                        children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                          Input,
                          {
                            type: "password",
                            placeholder: trans("密码"),
                            clearable: true,
                            focusBorder: true,
                            required: true,
                            className: css$s.loginInput,
                            prefix: /* @__PURE__ */ jsxRuntimeExports.jsx(LockIcon, { className: joinClass(css$s.img1, css$s.inputIcon) })
                          }
                        )
                      }
                    ),
                    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$s.rolesBox, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
                      /* @__PURE__ */ jsxRuntimeExports.jsx(CheckBox, { checked: isSave, onChange: (isChecked) => {
                        setIsSave(isChecked);
                        Cache$1.set("isSave", isChecked);
                      } }),
                      /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$s.tipsSpan, children: trans("记住密码") })
                    ] }) }),
                    /* @__PURE__ */ jsxRuntimeExports.jsx(Form.Field, { children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                      Button,
                      {
                        className: css$s.submitButton,
                        block: true,
                        loading: loading2,
                        onClick: () => {
                          form.submit();
                        },
                        children: trans("登录")
                      }
                    ) })
                  ]
                }
              ),
              /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$s.btmTextBox, children: [
                /* @__PURE__ */ jsxRuntimeExports.jsx(
                  "div",
                  {
                    onClick: () => {
                      useUserInfoStore.setState({ openForLogin: false });
                      navigate("/message?service=1");
                    },
                    children: trans("联系支持人员")
                  }
                ),
                games.some((item2) => item2.name === "试玩") ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { onClick: () => {
                  navigate("/demo-game?id=".concat("10", "&pid=").concat("0"));
                  useUserInfoStore.setState({ openForLogin: false });
                }, children: trans("试玩") }) : /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: " " }),
                /* @__PURE__ */ jsxRuntimeExports.jsx(
                  "div",
                  {
                    onClick: () => {
                      useUserInfoStore.setState({ openForLogin: false });
                      useUserInfoStore.setState({ openForRegister: true });
                    },
                    children: trans("注册账户")
                  }
                )
              ] })
            ]
          }
        ),
        /* @__PURE__ */ jsxRuntimeExports.jsx(ThreeLogin, { isLogin: true })
      ] })
    }
  ) });
};
const svg_theme_fill_color$s = "_svg_theme_fill_color_12w2x_3";
const loginBox$9 = "_loginBox_12w2x_55";
const clsoeBtn$a = "_clsoeBtn_12w2x_68";
const iconsvg$1 = "_iconsvg_12w2x_77";
const logo$2 = "_logo_12w2x_88";
const titleBox$6 = "_titleBox_12w2x_92";
const item$3 = "_item_12w2x_99";
const titleContentActive$1 = "_titleContentActive_12w2x_114";
const regIcon = "_regIcon_12w2x_122";
const margin$1 = "_margin_12w2x_128";
const marginTop$1 = "_marginTop_12w2x_131";
const inSetBox$1 = "_inSetBox_12w2x_136";
const leftAnimate$1 = "_leftAnimate_12w2x_143";
const reg$1 = "_reg_12w2x_122";
const loginType$1 = "_loginType_12w2x_158";
const loginType2$1 = "_loginType2_12w2x_167";
const wg3Back$2 = "_wg3Back_12w2x_175";
const css$r = {
  svg_theme_fill_color: svg_theme_fill_color$s,
  loginBox: loginBox$9,
  clsoeBtn: clsoeBtn$a,
  iconsvg: iconsvg$1,
  logo: logo$2,
  titleBox: titleBox$6,
  item: item$3,
  titleContentActive: titleContentActive$1,
  regIcon,
  margin: margin$1,
  marginTop: marginTop$1,
  inSetBox: inSetBox$1,
  leftAnimate: leftAnimate$1,
  reg: reg$1,
  loginType: loginType$1,
  loginType2: loginType2$1,
  wg3Back: wg3Back$2
};
const svg_theme_fill_color$r = "_svg_theme_fill_color_uknll_3";
const icon$9 = "_icon_uknll_55";
const icon2$8 = "_icon2_uknll_63";
const currencyTips$1 = "_currencyTips_uknll_81";
const rolesBox$5 = "_rolesBox_uknll_85";
const text1$4 = "_text1_uknll_98";
const p$3 = "_p_uknll_113";
const btmTextBox$5 = "_btmTextBox_uknll_129";
const btmTextBox2$3 = "_btmTextBox2_uknll_145";
const noMarLeft$5 = "_noMarLeft_uknll_161";
const img1$6 = "_img1_uknll_165";
const loginInput$6 = "_loginInput_uknll_172";
const inputIcon$4 = "_inputIcon_uknll_179";
const nationIsShow$2 = "_nationIsShow_uknll_186";
const nation$2 = "_nation_uknll_186";
const strength$3 = "_strength_uknll_195";
const thLabel$3 = "_thLabel_uknll_204";
const strengthItem$3 = "_strengthItem_uknll_209";
const selectedLow$3 = "_selectedLow_uknll_215";
const selectedMiddle1$3 = "_selectedMiddle1_uknll_218";
const selectedMiddle2$3 = "_selectedMiddle2_uknll_221";
const selectedHigh$3 = "_selectedHigh_uknll_224";
const phoneContainer$3 = "_phoneContainer_uknll_228";
const country$3 = "_country_uknll_234";
const area$3 = "_area_uknll_241";
const css$q = {
  svg_theme_fill_color: svg_theme_fill_color$r,
  icon: icon$9,
  icon2: icon2$8,
  currencyTips: currencyTips$1,
  rolesBox: rolesBox$5,
  text1: text1$4,
  p: p$3,
  btmTextBox: btmTextBox$5,
  btmTextBox2: btmTextBox2$3,
  noMarLeft: noMarLeft$5,
  img1: img1$6,
  loginInput: loginInput$6,
  inputIcon: inputIcon$4,
  nationIsShow: nationIsShow$2,
  nation: nation$2,
  strength: strength$3,
  thLabel: thLabel$3,
  strengthItem: strengthItem$3,
  selectedLow: selectedLow$3,
  selectedMiddle1: selectedMiddle1$3,
  selectedMiddle2: selectedMiddle2$3,
  selectedHigh: selectedHigh$3,
  phoneContainer: phoneContainer$3,
  country: country$3,
  area: area$3
};
const svg_theme_fill_color$q = "_svg_theme_fill_color_kb4s6_3";
const selectContainer = "_selectContainer_kb4s6_55";
const noBg = "_noBg_kb4s6_213";
const small = "_small_kb4s6_219";
const select$1 = "_select_kb4s6_55";
const icon2$7 = "_icon2_kb4s6_234";
const content$3 = "_content_kb4s6_240";
const prefix = "_prefix_kb4s6_331";
const suffix = "_suffix_kb4s6_343";
const up = "_up_kb4s6_459";
const down = "_down_kb4s6_463";
const options = "_options_kb4s6_467";
const option = "_option_kb4s6_467";
const active$6 = "_active_kb4s6_640";
const selectActive = "_selectActive_kb4s6_755";
const css$p = {
  svg_theme_fill_color: svg_theme_fill_color$q,
  selectContainer,
  noBg,
  "default": "_default_kb4s6_216",
  small,
  select: select$1,
  icon2: icon2$7,
  content: content$3,
  prefix,
  suffix,
  up,
  down,
  options,
  option,
  active: active$6,
  selectActive
};
const SelectTow = (props) => {
  const [value, setValue] = reactExports.useState(props.value);
  const [showOptions, setShowOptions] = reactExports.useState(false);
  const formContext = reactExports.useContext(FormContext);
  const renderValue = reactExports.useMemo(() => {
    if (props.items === void 0) return "";
    const item2 = props.items.find((_) => _.value === value);
    return item2 ? item2.label : "";
  }, [value, props.items]);
  const renderIconValue = reactExports.useMemo(() => {
    if (props.items === void 0) return "";
    const item2 = props.items.find((_) => _.value === value);
    return item2 ? item2.icon : "";
  }, [value, props.items]);
  const size2 = reactExports.useMemo(() => {
    var _a;
    return (_a = formContext.size) != null ? _a : "default";
  }, [formContext.size]);
  reactExports.useEffect(() => {
    setValue(props.value);
  }, [props.value]);
  const clickHandler = (_value) => {
    setValue(_value);
    setShowOptions(false);
    props.onChange && props.onChange(_value);
  };
  return /* @__PURE__ */ jsxRuntimeExports.jsxs(
    "div",
    {
      className: joinClass(
        css$p.selectContainer,
        css$p[size2],
        props.noBg ? css$p.noBg : "",
        showOptions ? css$p.selectActive : ""
      ),
      onClick: () => setShowOptions(!showOptions),
      children: [
        props.prefix && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$p.prefix, children: props.prefix }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$p.select, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$p.content, children: [
          " ",
          renderIconValue && /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { className: css$p.icon2, src: renderIconValue }),
          " ",
          renderValue
        ] }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$p.suffix, children: /* @__PURE__ */ jsxRuntimeExports.jsx(DownIcon, { className: joinClass(showOptions ? css$p.up : css$p.down) }) }),
        showOptions && props.items && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$p.options, css$p[size2]), children: props.items.map((_, index) => {
          return /* @__PURE__ */ jsxRuntimeExports.jsxs(
            "div",
            {
              className: joinClass(
                css$p.option,
                value === _.value ? css$p.active : ""
              ),
              onClick: (e) => {
                e.stopPropagation();
                clickHandler(_.value);
              },
              children: [
                _.icon && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { className: css$p.icon2, src: _.icon }) }),
                /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
                  " ",
                  _.label
                ] })
              ]
            },
            index
          );
        }) })
      ]
    }
  );
};
const CPFMaps$2 = {
  purple: "/home/CPF.webp",
  bvGreen: "/home/CPF_bv_green.webp",
  hermesOrange: "/home/CPF_hermes_orange.webp",
  embraerBlue: "/home/CPF_embraer.webp",
  lightBrown: "/home/CPF_patek_philippe.webp",
  greenGold: "/home/CPF_green_gold.webp",
  venetaGrey: "/home/CPF_veneta_Grey.webp",
  versaceYellow: "/home/CPF_versace_yellow.webp",
  blue: "/home/CPF_blue.webp",
  black: "/home/CPF_black.webp",
  oilyGreen: "/home/CPF_oilyGreen.webp",
  elsaPink: "/home/CPF_elsaPink.webp",
  sk2: "/home/CPF_sk2.webp",
  furlaBlue: "/home/CPF_furlaBlue.webp",
  AnnaSuiPurple: "/home/CPF_AnnaSuiPurple.webp",
  burgundyRed: "/home/CPF_burgundyRed.webp",
  bvlgariBrown: "/home/CPF_bvlgariBrown.webp",
  usdtGreen: "/home/CPF_usdtGreen.webp",
  microsoftRed: "/home/CPF_microsoftRed.webp",
  ferrariBlack: "/home/CPF_ferrariBlack.webp",
  whiteGreen: "/home/CPF_whiteGreen.webp",
  whiteBlue: "/home/CPF_whiteBlue.webp",
  whiteRed: "/home/CPF_whiteRed.webp",
  whitePurple: "/home/CPF_whitePurple.webp",
  whiteDarkGreen: "/home/CPF_whiteDarkGreen.webp",
  whiteRedGucci: "/home/CPF_whiteRedGucci.webp",
  whiteBlack: "/home/CPF_whiteBlack.webp",
  whitePink: "/home/CPF_whitePink.webp",
  whiteYellow: "/home/CPF_whiteYellow.webp",
  whiteOrange: "/home/CPF_whiteOrange.webp",
  whiteBrownLauren: "/home/CPF_whiteBrownLauren.webp",
  whiteBrown: "/home/CPF_whiteBrown.webp",
  martinPurple: "/home/CPF_martinPurple.webp",
  whiteBlueFendi: "/home/CPF_whiteBlueFendi.webp",
  whiteGreenCindy: "/home/CPF_whiteGreenCindy.webp",
  celineBrownWhite: "/home/CPF_celineBrownWhite.webp",
  burberryBlueWhite: "/home/CPF_burberryBlueWhite.webp"
};
const Register$2 = () => {
  const { openForRegister, theme, websetConfig } = useUserInfoStore();
  const [form] = Form.useForm();
  const [loading2, setLoading] = reactExports.useState(false);
  const [isCheck, setIsCheck] = reactExports.useState(true);
  const [query] = useSearchParams();
  const navigate = useNavigate();
  const { games } = useGameStore();
  const reg_need_phone = websetConfig.reg_need_phone || "0";
  const register_need_name_switch = websetConfig.register_need_name_switch || "0";
  const realNameRequired = websetConfig.realNameRequired || "0";
  const phoneRequired = websetConfig.phoneRequired || "0";
  const [curPsdLevel, setCurPsdLevel] = reactExports.useState(0);
  const curLevelClass = reactExports.useMemo(() => {
    if (curPsdLevel === 1) {
      return css$q.selectedLow;
    } else if (curPsdLevel === 2) {
      return css$q.selectedMiddle1;
    } else if (curPsdLevel === 3) {
      return css$q.selectedMiddle2;
    } else if (curPsdLevel === 4) {
      return css$q.selectedHigh;
    }
    return "";
  }, [curPsdLevel]);
  const postReg = async (params) => {
    const [res, error] = await register(params);
    if (!error) {
      Message.success(trans("注册成功"));
      useUserInfoStore.setState({
        openForRegister: false,
        openForLogin: false
      });
      useUserInfoStore.setState({ alertAfterLogin: true });
      Cache$1.set("isAlreadyRegister", true);
    }
  };
  const submit = async (_params) => {
    console.error("submit", _params);
    if (!isCheck) {
      Message.error(trans("请勾选同意《用户协议》"));
      return;
    }
    const params = {
      ..._params,
      verify_code: "",
      code: "",
      link_id: query.get("id") || Cache$1.get("link_id") || "",
      reg_url: location.href
    };
    if (!params.realname) {
      delete params.realname;
    }
    setLoading(true);
    if (websetConfig.authLogReg !== "1") {
      await postReg(params);
    } else {
      const result = await slideBlockByGeetest4(websetConfig.authLogRegType);
      if (result) {
        await postReg(
          Object.assign(params, {
            pass_token: result.pass_token,
            lot_number: result.lot_number,
            captcha_output: result.captcha_output,
            gen_time: result.gen_time
          })
        );
      }
    }
    setLoading(false);
  };
  reactExports.useEffect(() => {
    if (openForRegister) {
      form.resetFields();
    }
  }, [openForRegister]);
  return (
    // <Modal
    //   isOpen={openForRegister}
    //   closeByClickOut={false}
    //   onClose={(e) => {
    //     e && e.stopPropagation();
    //     useUserInfoStore.setState({ openForRegister: false });
    //   }}
    // >
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx(
        "div",
        {
          onClick: (e) => {
            e.stopPropagation();
          },
          children: /* @__PURE__ */ jsxRuntimeExports.jsxs(
            Form,
            {
              form,
              size: "small",
              initialValues: {
                username: "",
                password: "",
                repassword: "",
                phone: "",
                realname: "",
                currency: "1",
                cpf: ""
              },
              onFinish: (values) => submit(values),
              onFinishFailed: ({ values, errorFields, outOfDate }) => {
                console.log("Failed:", values, errorFields, outOfDate);
              },
              onValuesChange: (changedValues, allValues) => {
                if (changedValues.username) {
                  form.setFieldsValue({
                    username: changedValues.username.toLowerCase().trim()
                  });
                }
                if (changedValues.phone) {
                  form.setFieldsValue({
                    phone: changedValues.phone.replace(/\D/g, "") + " "
                  });
                  setTimeout(() => {
                    form.setFieldsValue({
                      phone: changedValues.phone.replace(/\D/g, "")
                    });
                  }, 0);
                }
                setCurPsdLevel(checkPasswordStrength(allValues.password));
              },
              children: [
                /* @__PURE__ */ jsxRuntimeExports.jsx(
                  Form.Field,
                  {
                    name: "username",
                    defaultShowError: true,
                    rules: [
                      { required: true, message: trans("请输入用户名") },
                      {
                        validator: (rule, value) => {
                          const error = checkInputUserName(value);
                          if (!error) {
                            return Promise.resolve();
                          }
                          return Promise.reject(error);
                        }
                      }
                    ],
                    children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                      Input,
                      {
                        placeholder: trans("用户名"),
                        clearable: true,
                        focusBorder: true,
                        required: true,
                        className: css$q.loginInput,
                        prefix: /* @__PURE__ */ jsxRuntimeExports.jsx(UserFullIcon, { className: joinClass(css$q.img1, css$q.inputIcon) })
                      }
                    )
                  }
                ),
                /* @__PURE__ */ jsxRuntimeExports.jsx(
                  Form.Field,
                  {
                    name: "password",
                    defaultShowError: true,
                    rules: [
                      { required: true, message: trans("请输入密码") },
                      {
                        validator: (rule, value) => {
                          const error = checkInputUserPwd(value);
                          if (!error) {
                            return Promise.resolve();
                          }
                          return Promise.reject(error);
                        }
                      }
                    ],
                    children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                      Input,
                      {
                        type: "password",
                        placeholder: trans("密码"),
                        clearable: true,
                        focusBorder: true,
                        required: true,
                        className: css$q.loginInput,
                        prefix: /* @__PURE__ */ jsxRuntimeExports.jsx(PasswordFullIcon, { className: joinClass(css$q.img1, css$q.inputIcon) })
                      }
                    )
                  }
                ),
                /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$q.strength, children: [
                  /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$q.thLabel, children: trans("密码强度") }),
                  /* @__PURE__ */ jsxRuntimeExports.jsx(
                    "div",
                    {
                      className: joinClass(
                        css$q.strengthItem,
                        curPsdLevel >= 1 ? curLevelClass : ""
                      )
                    }
                  ),
                  /* @__PURE__ */ jsxRuntimeExports.jsx(
                    "div",
                    {
                      className: joinClass(
                        css$q.strengthItem,
                        curPsdLevel >= 2 ? curLevelClass : ""
                      )
                    }
                  ),
                  /* @__PURE__ */ jsxRuntimeExports.jsx(
                    "div",
                    {
                      className: joinClass(
                        css$q.strengthItem,
                        curPsdLevel >= 4 ? curLevelClass : ""
                      )
                    }
                  ),
                  /* @__PURE__ */ jsxRuntimeExports.jsx(
                    "div",
                    {
                      className: joinClass(
                        css$q.strengthItem,
                        curPsdLevel >= 4 ? curLevelClass : ""
                      )
                    }
                  )
                ] }),
                /* @__PURE__ */ jsxRuntimeExports.jsx(
                  Form.Field,
                  {
                    name: "repassword",
                    defaultShowError: true,
                    rules: [
                      { required: true, message: trans("请输入密码") },
                      {
                        validator: (rule, value) => {
                          const error = checkInputUserPwd(value);
                          if (!error) {
                            if (value === form.getFieldValue("password")) {
                              return Promise.resolve();
                            } else {
                              return Promise.reject(trans("两次输入的密码不一样"));
                            }
                          }
                          return Promise.reject(error);
                        }
                      }
                    ],
                    children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                      Input,
                      {
                        type: "password",
                        placeholder: trans("确认密码"),
                        clearable: true,
                        focusBorder: true,
                        required: true,
                        className: css$q.loginInput,
                        prefix: /* @__PURE__ */ jsxRuntimeExports.jsx(PasswordFullIcon, { className: joinClass(css$q.img1, css$q.inputIcon) })
                      }
                    )
                  }
                ),
                reg_need_phone === "1" ? /* @__PURE__ */ jsxRuntimeExports.jsx(
                  Form.Field,
                  {
                    name: "phone",
                    defaultShowError: true,
                    rules: [
                      {
                        required: phoneRequired === "1",
                        message: trans("请输入手机号")
                      },
                      {
                        validator: (rule, value) => {
                          const isOkey = checkInputPhoneNumber(value);
                          if (isOkey) {
                            return Promise.resolve();
                          }
                          return Promise.reject(trans("手机号格式不正确"));
                        }
                      }
                    ],
                    children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                      Input,
                      {
                        placeholder: trans("输入手机号码"),
                        clearable: true,
                        focusBorder: true,
                        required: phoneRequired === "1",
                        className: css$q.loginInput,
                        prefix: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$q.phoneContainer, children: [
                          /* @__PURE__ */ jsxRuntimeExports.jsx(PhoneIcon, { className: css$q.img1 }),
                          /* @__PURE__ */ jsxRuntimeExports.jsx(
                            Image,
                            {
                              className: css$q.country,
                              src: getCountryFlag()
                            }
                          ),
                          /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$q.area, children: countryCode })
                        ] })
                      }
                    )
                  }
                ) : null,
                (websetConfig == null ? void 0 : websetConfig["isShowCPF"]) === "1" && /* @__PURE__ */ jsxRuntimeExports.jsx(
                  Form.Field,
                  {
                    defaultShowError: true,
                    name: "cpf",
                    rules: [
                      { required: (websetConfig == null ? void 0 : websetConfig["requiredRegisterCPF"]) + "" === "1", message: trans("EnterCPFTag") },
                      // {
                      //   validator: (rule, value) => {
                      //     if(value) {
                      //       const val = validarCPF(value);
                      //       if (val) {
                      //         return Promise.resolve();
                      //       }
                      //       return Promise.reject(trans('EnterCPFTag'));
                      //     } else {
                      //       return Promise.resolve();
                      //     }
                      //   }
                      // }
                      {
                        pattern: /^\d{11}$/,
                        message: trans("EnterCPFTag")
                      }
                    ],
                    children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                      Input,
                      {
                        placeholder: trans("输入11位CPF号码"),
                        clearable: true,
                        noBg: true,
                        required: (websetConfig == null ? void 0 : websetConfig["requiredRegisterCPF"]) + "" === "1",
                        maxLength: 11,
                        prefix: /* @__PURE__ */ jsxRuntimeExports.jsx(
                          Image,
                          {
                            className: css$q.img1,
                            src: CPFMaps$2[theme] || "/home/CPF.webp"
                          }
                        )
                      }
                    )
                  }
                ),
                register_need_name_switch === "1" ? /* @__PURE__ */ jsxRuntimeExports.jsx(
                  Form.Field,
                  {
                    name: "realname",
                    defaultShowError: true,
                    rules: [
                      {
                        required: realNameRequired === "1",
                        message: trans("输入您的真实姓名")
                      },
                      {
                        pattern: curCountryNameReg,
                        message: trans("真实姓名格式错误")
                      }
                    ],
                    children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                      Input,
                      {
                        placeholder: trans("全名"),
                        clearable: true,
                        focusBorder: true,
                        required: realNameRequired === "1",
                        className: css$q.loginInput,
                        prefix: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$q.phoneContainer, children: /* @__PURE__ */ jsxRuntimeExports.jsx(PixAccountIcon, { className: css$q.img1 }) }),
                        onBlur: (e) => {
                        }
                      }
                    )
                  }
                ) : null,
                (websetConfig == null ? void 0 : websetConfig["currencyRequired"]) === "1" && /* @__PURE__ */ jsxRuntimeExports.jsx(Form.Field, { name: "currency", children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                  SelectTow,
                  {
                    noBg: true,
                    items: [
                      {
                        label: "".concat(getMoneyUnit(), " (").concat(getMoneyUnit(), ")"),
                        value: "1",
                        icon: getCountryFlag(true)
                      }
                    ]
                  }
                ) }),
                // /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$q.currencyTips, children: trans("币种决定第三方游戏和支付方式，无法修改") }),
                /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$q.rolesBox, children: [
                  /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: /* @__PURE__ */ jsxRuntimeExports.jsx(CheckBox, { checked: isCheck, onChange: (c) => {
                    setIsCheck(c);
                  } }) }),
                  /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$q.text1), children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$q.p, children: [
                    trans("我已年满18岁，我已阅读并同意"),
                    /* @__PURE__ */ jsxRuntimeExports.jsx(
                      "span",
                      {
                        onClick: () => {
                          useUserInfoStore.setState({ openForXieYi: true });
                        },
                        children: "《".concat(trans("用户协议"), "》")
                      }
                    )
                  ] }) })
                ] }),
                /* @__PURE__ */ jsxRuntimeExports.jsx(Form.Field, { children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                  Button,
                  {
                    block: true,
                    className: css$q.submitButton,
                    loading: loading2,
                    onClick: () => {
                      form.submit();
                    },
                    children: trans("注册")
                  }
                ) })
              ]
            }
          )
        }
      ),
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: games.some((item2) => item2.name === "试玩") ? css$q.btmTextBox : css$q.btmTextBox2, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          "div",
          {
            onClick: () => {
              useUserInfoStore.setState({ openForRegister: false });
              navigate("/message?service=1");
            },
            children: trans("联系支持人员")
          }
        ),
        games.some((item2) => item2.name === "试玩") ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { onClick: () => {
          navigate("/demo-game?id=".concat("10", "&pid=").concat("0"));
          useUserInfoStore.setState({ openForLogin: false });
          useUserInfoStore.setState({ openForRegister: false });
        }, children: trans("试玩") }) : /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: " " })
      ] }),
      /* @__PURE__ */ jsxRuntimeExports.jsx(ThreeLogin, {})
    ] })
  );
};
const svg_theme_fill_color$p = "_svg_theme_fill_color_jsafj_3";
const loginBox$8 = "_loginBox_jsafj_55";
const submitButton$4 = "_submitButton_jsafj_61";
const loginInput$5 = "_loginInput_jsafj_65";
const inputIcon$3 = "_inputIcon_jsafj_72";
const clsoeBtn$9 = "_clsoeBtn_jsafj_78";
const icon$8 = "_icon_jsafj_95";
const icon2$6 = "_icon2_jsafj_102";
const rolesBox$4 = "_rolesBox_jsafj_117";
const tipsSpan$2 = "_tipsSpan_jsafj_129";
const btmTextBox$4 = "_btmTextBox_jsafj_139";
const btmTextBox2$2 = "_btmTextBox2_jsafj_155";
const noMarLeft$4 = "_noMarLeft_jsafj_171";
const img1$5 = "_img1_jsafj_174";
const css$o = {
  svg_theme_fill_color: svg_theme_fill_color$p,
  loginBox: loginBox$8,
  submitButton: submitButton$4,
  loginInput: loginInput$5,
  inputIcon: inputIcon$3,
  clsoeBtn: clsoeBtn$9,
  icon: icon$8,
  icon2: icon2$6,
  rolesBox: rolesBox$4,
  tipsSpan: tipsSpan$2,
  btmTextBox: btmTextBox$4,
  btmTextBox2: btmTextBox2$2,
  noMarLeft: noMarLeft$4,
  img1: img1$5
};
const Login$2 = () => {
  const { openForLogin, token, theme, websetConfig } = useUserInfoStore();
  const [form] = Form.useForm();
  const [loading2, setLoading] = reactExports.useState(false);
  const [isSave, setIsSave] = reactExports.useState(Cache$1.get("isSave") || false);
  const navigate = useNavigate();
  const { games } = useGameStore();
  const postLogin = async (params) => {
    const [res, error] = await login(params, { useLoading: true });
    if (!error) {
      Message.success(trans("登录成功"));
      if (window.jsBridge && window.jsBridge.postMessage) {
        try {
          window.jsBridge.postMessage("login", "{}");
        } catch (e) {
          console.log(e);
        }
      }
      useUserInfoStore.setState({ openForLogin: false, openForRegister: false });
      useUserInfoStore.setState({ alertAfterLogin: true });
      if (isSave) {
        Cache$1.set("UserName", form.getFieldValue("username"));
        Cache$1.set("UserPwd", form.getFieldValue("password"));
      }
    }
  };
  const submit = async (params) => {
    setLoading(true);
    if (websetConfig.authLogReg !== "1") {
      await postLogin(params);
    } else {
      const result = await slideBlockByGeetest4(websetConfig.authLogRegType);
      if (result) {
        await postLogin(Object.assign(params, {
          pass_token: result.pass_token,
          lot_number: result.lot_number,
          captcha_output: result.captcha_output,
          gen_time: result.gen_time
        }));
      }
    }
    setLoading(false);
  };
  return /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, { children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$o.loginBox, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsxs(
      "div",
      {
        onClick: (e) => {
          e.stopPropagation();
        },
        children: [
          /* @__PURE__ */ jsxRuntimeExports.jsxs(
            Form,
            {
              form,
              size: "small",
              initialValues: {
                username: isSave ? Cache$1.get("UserName") : "",
                password: isSave ? Cache$1.get("UserPwd") : ""
              },
              onFinish: (values) => submit(values),
              onFinishFailed: ({ values, errorFields, outOfDate }) => {
                console.log("Failed:", values, errorFields, outOfDate);
              },
              onValuesChange: (changedValues, allValues) => {
                if (changedValues.username) {
                  form.setFieldsValue({
                    username: changedValues.username.toLowerCase().trim()
                  });
                }
              },
              children: [
                /* @__PURE__ */ jsxRuntimeExports.jsx(
                  Form.Field,
                  {
                    name: "username",
                    defaultShowError: true,
                    rules: [
                      { required: true, message: trans("请输入用户名") },
                      {
                        validator: (rule, value) => {
                          const error = checkInputUserName(value);
                          if (!error) {
                            return Promise.resolve();
                          }
                          return Promise.reject(error);
                        }
                      }
                    ],
                    children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                      Input,
                      {
                        placeholder: trans("用户名"),
                        clearable: true,
                        focusBorder: true,
                        required: true,
                        className: css$o.loginInput,
                        prefix: /* @__PURE__ */ jsxRuntimeExports.jsx(UserFullIcon, { className: joinClass(css$o.img1, css$o.inputIcon) })
                      }
                    )
                  }
                ),
                /* @__PURE__ */ jsxRuntimeExports.jsx(
                  Form.Field,
                  {
                    name: "password",
                    defaultShowError: true,
                    rules: [
                      { required: true, message: trans("请输入密码") },
                      {
                        validator: (rule, value) => {
                          const error = checkInputUserPwd(value);
                          if (!error) {
                            return Promise.resolve();
                          }
                          return Promise.reject(error);
                        }
                      }
                    ],
                    children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                      Input,
                      {
                        type: "password",
                        placeholder: trans("密码"),
                        clearable: true,
                        focusBorder: true,
                        required: true,
                        className: css$o.loginInput,
                        prefix: /* @__PURE__ */ jsxRuntimeExports.jsx(PasswordFullIcon, { className: joinClass(css$o.img1, css$o.inputIcon) })
                      }
                    )
                  }
                ),
                /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$o.rolesBox, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
                  /* @__PURE__ */ jsxRuntimeExports.jsx(CheckBox, { checked: isSave, onChange: (isChecked) => {
                    setIsSave(isChecked);
                    Cache$1.set("isSave", isChecked);
                  } }),
                  /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$o.tipsSpan, children: trans("记住密码") })
                ] }) }),
                /* @__PURE__ */ jsxRuntimeExports.jsx(Form.Field, { children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                  Button,
                  {
                    className: css$o.submitButton,
                    block: true,
                    loading: loading2,
                    onClick: () => {
                      form.submit();
                    },
                    children: trans("登录")
                  }
                ) })
              ]
            }
          ),
          /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: games.some((item2) => item2.name === "试玩") ? css$o.btmTextBox : css$o.btmTextBox2, children: [
            /* @__PURE__ */ jsxRuntimeExports.jsx(
              "div",
              {
                onClick: () => {
                  useUserInfoStore.setState({ openForLogin: false });
                  navigate("/message?service=1");
                },
                children: trans("联系支持人员")
              }
            ),
            games.some((item2) => item2.name === "试玩") ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { onClick: () => {
              navigate("/demo-game?id=".concat("10", "&pid=").concat("0"));
              useUserInfoStore.setState({ openForLogin: false });
              useUserInfoStore.setState({ openForRegister: false });
            }, children: trans("试玩") }) : /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: " " })
          ] })
        ]
      }
    ),
    /* @__PURE__ */ jsxRuntimeExports.jsx(ThreeLogin, { isLogin: true })
  ] }) });
};
const LogInRegister2$1 = () => {
  const { openForLogin, token, theme, websetConfig, openForRegister } = useUserInfoStore();
  const [btnClose, setBtnClose] = reactExports.useState(true);
  reactExports.useEffect(() => {
    if (btnClose) {
      if (openForLogin) {
        setLoginType(true);
      } else {
        setLoginType(false);
      }
    } else {
      setBtnClose(true);
    }
  }, [openForLogin, openForRegister]);
  const [loginType3, setLoginType] = reactExports.useState(true);
  return /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, { children: /* @__PURE__ */ jsxRuntimeExports.jsx(
    Modal,
    {
      isOpen: openForLogin || openForRegister,
      closeByClickOut: false,
      showType: document.documentElement.getAttribute("temph5") === "12" ? "right" : "",
      onClose: (e) => {
        e && e.stopPropagation();
        setBtnClose(false);
        useUserInfoStore.setState({
          openForLogin: false,
          openForRegister: false
        });
      },
      children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$r.loginBox, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          "div",
          {
            className: css$r.clsoeBtn,
            onClick: () => {
              setBtnClose(false);
              useUserInfoStore.setState({
                openForLogin: false,
                openForRegister: false
              });
              const googleDom = document.getElementById("g_id_signIn");
              if (googleDom) googleDom.innerHTML = "";
            },
            children: /* @__PURE__ */ jsxRuntimeExports.jsx(CloseIconInLogin, { className: css$r.iconsvg })
          }
        ),
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          "div",
          {
            className: css$r.wg3Back,
            onClick: () => {
              setBtnClose(false);
              useUserInfoStore.setState({
                openForLogin: false,
                openForRegister: false
              });
              const googleDom = document.getElementById("g_id_signIn");
              if (googleDom) googleDom.innerHTML = "";
            },
            children: /* @__PURE__ */ jsxRuntimeExports.jsx(ArrowLeftInMineIcon, {})
          }
        ),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$r.titleBox, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsxs(
            "div",
            {
              className: joinClass(
                css$r.item,
                !loginType3 && css$r.titleContentActive
              ),
              onClick: () => {
                setLoginType(false);
              },
              children: [
                /* @__PURE__ */ jsxRuntimeExports.jsx(Login$4, { className: css$r.regIcon }),
                /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: trans("注册") })
              ]
            }
          ),
          /* @__PURE__ */ jsxRuntimeExports.jsxs(
            "div",
            {
              className: joinClass(
                css$r.item,
                loginType3 && css$r.titleContentActive
              ),
              onClick: () => {
                setLoginType(true);
              },
              children: [
                /* @__PURE__ */ jsxRuntimeExports.jsx(Register$3, { className: css$r.regIcon }),
                /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
                  " ",
                  trans("会员登录")
                ] })
              ]
            }
          )
        ] }),
        loginType3 ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$r.marginTop, children: trans("仅支持账户登录") }) : /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$r.marginTop, children: trans("仅支持账户注册") }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { style: { width: "100%", overflow: "hidden" }, children: /* @__PURE__ */ jsxRuntimeExports.jsxs(
          "div",
          {
            className: joinClass(
              css$r.inSetBox,
              loginType3 ? "" : css$r.leftAnimate
            ),
            children: [
              /* @__PURE__ */ jsxRuntimeExports.jsx(Login$2, {}),
              !loginType3 ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$r.loginType, children: /* @__PURE__ */ jsxRuntimeExports.jsx(Register$2, {}) }) : /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$r.loginType2, children: " " })
            ]
          }
        ) })
      ] }) })
    }
  ) });
};
const svg_theme_fill_color$o = "_svg_theme_fill_color_13u7f_3";
const modalContent = "_modalContent_13u7f_55";
const loginBox$7 = "_loginBox_13u7f_59";
const clsoeBtn$8 = "_clsoeBtn_13u7f_72";
const iconsvg = "_iconsvg_13u7f_81";
const logo$1 = "_logo_13u7f_92";
const titleBox$5 = "_titleBox_13u7f_96";
const item$2 = "_item_13u7f_103";
const titleContentActive = "_titleContentActive_13u7f_111";
const margin = "_margin_13u7f_117";
const marginTop = "_marginTop_13u7f_120";
const inSetBox = "_inSetBox_13u7f_124";
const leftAnimate = "_leftAnimate_13u7f_131";
const reg = "_reg_13u7f_137";
const loginType = "_loginType_13u7f_146";
const loginType2 = "_loginType2_13u7f_155";
const wg3Back$1 = "_wg3Back_13u7f_163";
const css$n = {
  svg_theme_fill_color: svg_theme_fill_color$o,
  modalContent,
  loginBox: loginBox$7,
  clsoeBtn: clsoeBtn$8,
  iconsvg,
  logo: logo$1,
  titleBox: titleBox$5,
  item: item$2,
  titleContentActive,
  margin,
  marginTop,
  inSetBox,
  leftAnimate,
  reg,
  loginType,
  loginType2,
  wg3Back: wg3Back$1
};
const svg_theme_fill_color$n = "_svg_theme_fill_color_pwwcd_3";
const icon$7 = "_icon_pwwcd_55";
const icon2$5 = "_icon2_pwwcd_63";
const currencyTips = "_currencyTips_pwwcd_81";
const rolesBox$3 = "_rolesBox_pwwcd_85";
const text1$3 = "_text1_pwwcd_98";
const p$2 = "_p_pwwcd_113";
const btmTextBox$3 = "_btmTextBox_pwwcd_129";
const btmTextBox2$1 = "_btmTextBox2_pwwcd_145";
const noMarLeft$3 = "_noMarLeft_pwwcd_161";
const img1$4 = "_img1_pwwcd_165";
const loginInput$4 = "_loginInput_pwwcd_172";
const inputIcon$2 = "_inputIcon_pwwcd_179";
const flex$2 = "_flex_pwwcd_186";
const strength$2 = "_strength_pwwcd_192";
const thLabel$2 = "_thLabel_pwwcd_201";
const strengthItem$2 = "_strengthItem_pwwcd_206";
const selectedLow$2 = "_selectedLow_pwwcd_212";
const selectedMiddle1$2 = "_selectedMiddle1_pwwcd_215";
const selectedMiddle2$2 = "_selectedMiddle2_pwwcd_218";
const selectedHigh$2 = "_selectedHigh_pwwcd_221";
const phoneContainer$2 = "_phoneContainer_pwwcd_225";
const country$2 = "_country_pwwcd_231";
const area$2 = "_area_pwwcd_238";
const css$m = {
  svg_theme_fill_color: svg_theme_fill_color$n,
  icon: icon$7,
  icon2: icon2$5,
  currencyTips,
  rolesBox: rolesBox$3,
  text1: text1$3,
  p: p$2,
  btmTextBox: btmTextBox$3,
  btmTextBox2: btmTextBox2$1,
  noMarLeft: noMarLeft$3,
  img1: img1$4,
  loginInput: loginInput$4,
  inputIcon: inputIcon$2,
  flex: flex$2,
  strength: strength$2,
  thLabel: thLabel$2,
  strengthItem: strengthItem$2,
  selectedLow: selectedLow$2,
  selectedMiddle1: selectedMiddle1$2,
  selectedMiddle2: selectedMiddle2$2,
  selectedHigh: selectedHigh$2,
  phoneContainer: phoneContainer$2,
  country: country$2,
  area: area$2
};
const CPFMaps$1 = {
  purple: "/home/CPF.webp",
  bvGreen: "/home/CPF_bv_green.webp",
  hermesOrange: "/home/CPF_hermes_orange.webp",
  embraerBlue: "/home/CPF_embraer.webp",
  lightBrown: "/home/CPF_patek_philippe.webp",
  greenGold: "/home/CPF_green_gold.webp",
  venetaGrey: "/home/CPF_veneta_Grey.webp",
  versaceYellow: "/home/CPF_versace_yellow.webp",
  blue: "/home/CPF_blue.webp",
  black: "/home/CPF_black.webp",
  oilyGreen: "/home/CPF_oilyGreen.webp",
  elsaPink: "/home/CPF_elsaPink.webp",
  sk2: "/home/CPF_sk2.webp",
  furlaBlue: "/home/CPF_furlaBlue.webp",
  AnnaSuiPurple: "/home/CPF_AnnaSuiPurple.webp",
  burgundyRed: "/home/CPF_burgundyRed.webp",
  bvlgariBrown: "/home/CPF_bvlgariBrown.webp",
  usdtGreen: "/home/CPF_usdtGreen.webp",
  microsoftRed: "/home/CPF_microsoftRed.webp",
  ferrariBlack: "/home/CPF_ferrariBlack.webp",
  whiteGreen: "/home/CPF_whiteGreen.webp",
  whiteBlue: "/home/CPF_whiteBlue.webp",
  whiteRed: "/home/CPF_whiteRed.webp",
  whitePurple: "/home/CPF_whitePurple.webp",
  whiteDarkGreen: "/home/CPF_whiteDarkGreen.webp",
  whiteRedGucci: "/home/CPF_whiteRedGucci.webp",
  whiteBlack: "/home/CPF_whiteBlack.webp",
  whitePink: "/home/CPF_whitePink.webp",
  whiteYellow: "/home/CPF_whiteYellow.webp",
  whiteOrange: "/home/CPF_whiteOrange.webp",
  whiteBrownLauren: "/home/CPF_whiteBrownLauren.webp",
  whiteBrown: "/home/CPF_whiteBrown.webp",
  martinPurple: "/home/CPF_martinPurple.webp",
  whiteBlueFendi: "/home/CPF_whiteBlueFendi.webp",
  whiteGreenCindy: "/home/CPF_whiteGreenCindy.webp",
  celineBrownWhite: "/home/CPF_celineBrownWhite.webp",
  burberryBlueWhite: "/home/CPF_burberryBlueWhite.webp"
};
const Register$1 = () => {
  const { openForRegister, theme, websetConfig } = useUserInfoStore();
  const [form] = Form.useForm();
  const [loading2, setLoading] = reactExports.useState(false);
  const [isCheck, setIsCheck] = reactExports.useState(true);
  const [query] = useSearchParams();
  const navigate = useNavigate();
  const { games } = useGameStore();
  const reg_need_phone = websetConfig.reg_need_phone || "0";
  const register_need_name_switch = websetConfig.register_need_name_switch || "0";
  const realNameRequired = websetConfig.realNameRequired || "0";
  const phoneRequired = websetConfig.phoneRequired || "0";
  const [curPsdLevel, setCurPsdLevel] = reactExports.useState(0);
  const curLevelClass = reactExports.useMemo(() => {
    if (curPsdLevel === 1) {
      return css$m.selectedLow;
    } else if (curPsdLevel === 2) {
      return css$m.selectedMiddle1;
    } else if (curPsdLevel === 3) {
      return css$m.selectedMiddle2;
    } else if (curPsdLevel === 4) {
      return css$m.selectedHigh;
    }
    return "";
  }, [curPsdLevel]);
  const postReg = async (params) => {
    const [res, error] = await register(params);
    if (!error) {
      Message.success(trans("注册成功"));
      useUserInfoStore.setState({
        openForRegister: false,
        openForLogin: false
      });
      useUserInfoStore.setState({ alertAfterLogin: true });
      Cache$1.set("isAlreadyRegister", true);
    }
  };
  const submit = async (_params) => {
    console.error("submit", _params);
    if (!isCheck) {
      Message.error(trans("请勾选同意《用户协议》"));
      return;
    }
    const params = {
      ..._params,
      verify_code: "",
      code: "",
      link_id: query.get("id") || Cache$1.get("link_id") || "",
      reg_url: location.href
    };
    if (!params.realname) {
      delete params.realname;
    }
    setLoading(true);
    if (websetConfig.authLogReg !== "1") {
      await postReg(params);
    } else {
      const result = await slideBlockByGeetest4(websetConfig.authLogRegType);
      if (result) {
        await postReg(
          Object.assign(params, {
            pass_token: result.pass_token,
            lot_number: result.lot_number,
            captcha_output: result.captcha_output,
            gen_time: result.gen_time
          })
        );
      }
    }
    setLoading(false);
  };
  reactExports.useEffect(() => {
    if (openForRegister) {
      form.resetFields();
    }
  }, [openForRegister]);
  return (
    // <Modal
    //   isOpen={openForRegister}
    //   closeByClickOut={false}
    //   onClose={(e) => {
    //     e && e.stopPropagation();
    //     useUserInfoStore.setState({ openForRegister: false });
    //   }}
    // >
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx(
        "div",
        {
          onClick: (e) => {
            e.stopPropagation();
          },
          children: /* @__PURE__ */ jsxRuntimeExports.jsxs(
            Form,
            {
              form,
              size: "small",
              initialValues: {
                username: "",
                password: "",
                repassword: "",
                phone: "",
                realname: "",
                currency: "1",
                cpf: ""
              },
              onFinish: (values) => submit(values),
              onFinishFailed: ({ values, errorFields, outOfDate }) => {
                console.log("Failed:", values, errorFields, outOfDate);
              },
              onValuesChange: (changedValues, allValues) => {
                if (changedValues.username) {
                  form.setFieldsValue({
                    username: changedValues.username.toLowerCase().trim()
                  });
                }
                if (changedValues.phone) {
                  form.setFieldsValue({
                    phone: changedValues.phone.replace(/\D/g, "") + " "
                  });
                  setTimeout(() => {
                    form.setFieldsValue({
                      phone: changedValues.phone.replace(/\D/g, "")
                    });
                  }, 0);
                }
                setCurPsdLevel(checkPasswordStrength(allValues.password));
              },
              children: [
                /* @__PURE__ */ jsxRuntimeExports.jsx(
                  Form.Field,
                  {
                    name: "username",
                    defaultShowError: true,
                    rules: [
                      { required: true, message: trans("请输入用户名") },
                      {
                        validator: (rule, value) => {
                          const error = checkInputUserName(value);
                          if (!error) {
                            return Promise.resolve();
                          }
                          return Promise.reject(error);
                        }
                      }
                    ],
                    children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                      Input,
                      {
                        placeholder: trans("用户名"),
                        clearable: true,
                        focusBorder: true,
                        required: true,
                        className: css$m.loginInput,
                        prefix: /* @__PURE__ */ jsxRuntimeExports.jsx(UserFullIcon, { className: joinClass(css$m.img1, css$m.inputIcon) })
                      }
                    )
                  }
                ),
                /* @__PURE__ */ jsxRuntimeExports.jsx(
                  Form.Field,
                  {
                    name: "password",
                    defaultShowError: true,
                    rules: [
                      { required: true, message: trans("请输入密码") },
                      {
                        validator: (rule, value) => {
                          const error = checkInputUserPwd(value);
                          if (!error) {
                            return Promise.resolve();
                          }
                          return Promise.reject(error);
                        }
                      }
                    ],
                    children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                      Input,
                      {
                        type: "password",
                        placeholder: trans("密码"),
                        clearable: true,
                        focusBorder: true,
                        required: true,
                        className: css$m.loginInput,
                        prefix: /* @__PURE__ */ jsxRuntimeExports.jsx(PasswordFullIcon, { className: joinClass(css$m.img1, css$m.inputIcon) })
                      }
                    )
                  }
                ),
                /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$m.strength, children: [
                  /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$m.thLabel, children: trans("密码强度") }),
                  /* @__PURE__ */ jsxRuntimeExports.jsx(
                    "div",
                    {
                      className: joinClass(
                        css$m.strengthItem,
                        curPsdLevel >= 1 ? curLevelClass : ""
                      )
                    }
                  ),
                  /* @__PURE__ */ jsxRuntimeExports.jsx(
                    "div",
                    {
                      className: joinClass(
                        css$m.strengthItem,
                        curPsdLevel >= 2 ? curLevelClass : ""
                      )
                    }
                  ),
                  /* @__PURE__ */ jsxRuntimeExports.jsx(
                    "div",
                    {
                      className: joinClass(
                        css$m.strengthItem,
                        curPsdLevel >= 4 ? curLevelClass : ""
                      )
                    }
                  ),
                  /* @__PURE__ */ jsxRuntimeExports.jsx(
                    "div",
                    {
                      className: joinClass(
                        css$m.strengthItem,
                        curPsdLevel >= 4 ? curLevelClass : ""
                      )
                    }
                  )
                ] }),
                /* @__PURE__ */ jsxRuntimeExports.jsx(
                  Form.Field,
                  {
                    name: "repassword",
                    defaultShowError: true,
                    rules: [
                      { required: true, message: trans("请输入密码") },
                      {
                        validator: (rule, value) => {
                          const error = checkInputUserPwd(value);
                          if (!error) {
                            console.log(form.getFieldValue("password"), value);
                            if (value === form.getFieldValue("password")) {
                              return Promise.resolve();
                            } else {
                              return Promise.reject(trans("两次输入的密码不一样"));
                            }
                          }
                          return Promise.reject(error);
                        }
                      }
                    ],
                    children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                      Input,
                      {
                        type: "password",
                        placeholder: trans("确认密码"),
                        clearable: true,
                        focusBorder: true,
                        required: true,
                        className: css$m.loginInput,
                        prefix: /* @__PURE__ */ jsxRuntimeExports.jsx(PasswordFullIcon, { className: joinClass(css$m.img1, css$m.inputIcon) })
                      }
                    )
                  }
                ),
                reg_need_phone === "1" ? /* @__PURE__ */ jsxRuntimeExports.jsx(
                  Form.Field,
                  {
                    name: "phone",
                    defaultShowError: true,
                    rules: [
                      {
                        required: phoneRequired === "1",
                        message: trans("请输入手机号")
                      },
                      {
                        validator: (rule, value) => {
                          const isOkey = checkInputPhoneNumber(value);
                          if (isOkey) {
                            return Promise.resolve();
                          }
                          return Promise.reject(trans("手机号格式不正确"));
                        }
                      }
                    ],
                    children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                      Input,
                      {
                        placeholder: trans("输入手机号码"),
                        clearable: true,
                        focusBorder: true,
                        required: phoneRequired === "1",
                        className: css$m.loginInput,
                        prefix: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$m.phoneContainer, children: [
                          /* @__PURE__ */ jsxRuntimeExports.jsx(PhoneIcon, { className: css$m.img1 }),
                          /* @__PURE__ */ jsxRuntimeExports.jsx(
                            Image,
                            {
                              className: css$m.country,
                              src: getCountryFlag()
                            }
                          ),
                          /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$m.area, children: countryCode })
                        ] })
                      }
                    )
                  }
                ) : null,
                (websetConfig == null ? void 0 : websetConfig["isShowCPF"]) === "1" && /* @__PURE__ */ jsxRuntimeExports.jsx(
                  Form.Field,
                  {
                    defaultShowError: true,
                    name: "cpf",
                    rules: [
                      { required: (websetConfig == null ? void 0 : websetConfig["requiredRegisterCPF"]) + "" === "1", message: trans("EnterCPFTag") },
                      // {
                      //   validator: (rule, value) => {
                      //     if(value) {
                      //       const val = validarCPF(value);
                      //       if (val) {
                      //         return Promise.resolve();
                      //       }
                      //       return Promise.reject(trans('EnterCPFTag'));
                      //     } else {
                      //       return Promise.resolve();
                      //     }
                      //   }
                      // }
                      {
                        pattern: /^\d{11}$/,
                        message: trans("EnterCPFTag")
                      }
                    ],
                    children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                      Input,
                      {
                        placeholder: trans("输入11位CPF号码"),
                        clearable: true,
                        noBg: true,
                        required: (websetConfig == null ? void 0 : websetConfig["requiredRegisterCPF"]) + "" === "1",
                        maxLength: 11,
                        prefix: /* @__PURE__ */ jsxRuntimeExports.jsx(
                          Image,
                          {
                            className: css$m.img1,
                            src: CPFMaps$1[theme] || "/home/CPF.webp"
                          }
                        )
                      }
                    )
                  }
                ),
                register_need_name_switch === "1" ? /* @__PURE__ */ jsxRuntimeExports.jsx(
                  Form.Field,
                  {
                    name: "realname",
                    defaultShowError: true,
                    rules: [
                      {
                        required: realNameRequired === "1",
                        message: trans("输入您的真实姓名")
                      },
                      {
                        pattern: curCountryNameReg,
                        message: trans("真实姓名格式错误")
                      }
                    ],
                    children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                      Input,
                      {
                        placeholder: trans("全名"),
                        clearable: true,
                        focusBorder: true,
                        required: realNameRequired === "1",
                        className: css$m.loginInput,
                        onBlur: (e) => {
                        },
                        prefix: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$m.phoneContainer, children: /* @__PURE__ */ jsxRuntimeExports.jsx(PixAccountIcon, { className: css$m.img1 }) })
                      }
                    )
                  }
                ) : null,
                (websetConfig == null ? void 0 : websetConfig["currencyRequired"]) === "1" && /* @__PURE__ */ jsxRuntimeExports.jsx(Form.Field, { name: "currency", children: /* @__PURE__ */ jsxRuntimeExports.jsx(SelectTow, { noBg: true, items: [
                  {
                    label: "".concat(getMoneyUnit(), " (").concat(getMoneyUnit(), ")"),
                    value: "1",
                    icon: getCountryFlag(true)
                  }
                ] }) }),
                /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$m.currencyTips, children: trans("币种决定第三方游戏和支付方式，无法修改") }),
                /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$m.rolesBox, children: [
                  /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: /* @__PURE__ */ jsxRuntimeExports.jsx(CheckBox, { checked: isCheck, onChange: (c) => {
                    setIsCheck(c);
                  } }) }),
                  /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$m.text1), children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$m.p, children: [
                    trans("我已年满18岁，我已阅读并同意"),
                    /* @__PURE__ */ jsxRuntimeExports.jsx(
                      "span",
                      {
                        onClick: () => {
                          useUserInfoStore.setState({ openForXieYi: true });
                        },
                        children: "《".concat(trans("用户协议"), "》")
                      }
                    )
                  ] }) })
                ] }),
                /* @__PURE__ */ jsxRuntimeExports.jsx(Form.Field, { children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                  Button,
                  {
                    block: true,
                    className: css$m.submitButton,
                    loading: loading2,
                    onClick: () => {
                      form.submit();
                    },
                    children: trans("注册")
                  }
                ) })
              ]
            }
          )
        }
      ),
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: games.some((item2) => item2.name === "试玩") ? css$m.btmTextBox : css$m.btmTextBox2, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          "div",
          {
            onClick: () => {
              useUserInfoStore.setState({ openForRegister: false });
              navigate("/message?service=1");
            },
            children: trans("联系支持人员")
          }
        ),
        games.some((item2) => item2.name === "试玩") ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { onClick: () => {
          navigate("/demo-game?id=".concat("10", "&pid=").concat("0"));
          useUserInfoStore.setState({ openForLogin: false });
          useUserInfoStore.setState({ openForRegister: false });
        }, children: trans("试玩") }) : /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: " " })
      ] }),
      /* @__PURE__ */ jsxRuntimeExports.jsx(ThreeLogin, {})
    ] })
  );
};
const svg_theme_fill_color$m = "_svg_theme_fill_color_1qsfi_3";
const loginBox$6 = "_loginBox_1qsfi_55";
const submitButton$3 = "_submitButton_1qsfi_61";
const loginInput$3 = "_loginInput_1qsfi_65";
const inputIcon$1 = "_inputIcon_1qsfi_72";
const clsoeBtn$7 = "_clsoeBtn_1qsfi_78";
const icon$6 = "_icon_1qsfi_95";
const icon2$4 = "_icon2_1qsfi_102";
const rolesBox$2 = "_rolesBox_1qsfi_117";
const tipsSpan$1 = "_tipsSpan_1qsfi_132";
const btmTextBox$2 = "_btmTextBox_1qsfi_142";
const btmTextBox2 = "_btmTextBox2_1qsfi_158";
const noMarLeft$2 = "_noMarLeft_1qsfi_174";
const img1$3 = "_img1_1qsfi_177";
const css$l = {
  svg_theme_fill_color: svg_theme_fill_color$m,
  loginBox: loginBox$6,
  submitButton: submitButton$3,
  loginInput: loginInput$3,
  inputIcon: inputIcon$1,
  clsoeBtn: clsoeBtn$7,
  icon: icon$6,
  icon2: icon2$4,
  rolesBox: rolesBox$2,
  tipsSpan: tipsSpan$1,
  btmTextBox: btmTextBox$2,
  btmTextBox2,
  noMarLeft: noMarLeft$2,
  img1: img1$3
};
const Login$1 = () => {
  const { openForLogin, token, theme, websetConfig } = useUserInfoStore();
  const [form] = Form.useForm();
  const [loading2, setLoading] = reactExports.useState(false);
  const [isSave, setIsSave] = reactExports.useState(Cache$1.get("isSave") || false);
  const navigate = useNavigate();
  const { games } = useGameStore();
  const postLogin = async (params) => {
    const [res, error] = await login(params, { useLoading: true });
    if (!error) {
      Message.success(trans("登录成功"));
      if (window.jsBridge && window.jsBridge.postMessage) {
        try {
          window.jsBridge.postMessage("login", "{}");
        } catch (e) {
          console.log(e);
        }
      }
      useUserInfoStore.setState({ openForLogin: false, openForRegister: false });
      useUserInfoStore.setState({ alertAfterLogin: true });
      if (isSave) {
        Cache$1.set("UserName", form.getFieldValue("username"));
        Cache$1.set("UserPwd", form.getFieldValue("password"));
      }
    }
  };
  const submit = async (params) => {
    setLoading(true);
    if (websetConfig.authLogReg !== "1") {
      await postLogin(params);
    } else {
      const result = await slideBlockByGeetest4(websetConfig.authLogRegType);
      if (result) {
        await postLogin(Object.assign(params, {
          pass_token: result.pass_token,
          lot_number: result.lot_number,
          captcha_output: result.captcha_output,
          gen_time: result.gen_time
        }));
      }
    }
    setLoading(false);
  };
  return /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, { children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$l.loginBox, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsxs(
      "div",
      {
        onClick: (e) => {
          e.stopPropagation();
        },
        children: [
          /* @__PURE__ */ jsxRuntimeExports.jsxs(
            Form,
            {
              form,
              size: "small",
              initialValues: {
                username: isSave ? Cache$1.get("UserName") : "",
                password: isSave ? Cache$1.get("UserPwd") : ""
              },
              onFinish: (values) => submit(values),
              onFinishFailed: ({ values, errorFields, outOfDate }) => {
                console.log("Failed:", values, errorFields, outOfDate);
              },
              onValuesChange: (changedValues, allValues) => {
                if (changedValues.username) {
                  form.setFieldsValue({
                    username: changedValues.username.toLowerCase().trim()
                  });
                }
              },
              children: [
                /* @__PURE__ */ jsxRuntimeExports.jsx(
                  Form.Field,
                  {
                    name: "username",
                    defaultShowError: true,
                    rules: [
                      { required: true, message: trans("请输入用户名") },
                      {
                        validator: (rule, value) => {
                          const error = checkInputUserName(value);
                          if (!error) {
                            return Promise.resolve();
                          }
                          return Promise.reject(error);
                        }
                      }
                    ],
                    children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                      Input,
                      {
                        placeholder: trans("用户名"),
                        clearable: true,
                        focusBorder: true,
                        required: true,
                        className: css$l.loginInput,
                        prefix: /* @__PURE__ */ jsxRuntimeExports.jsx(UserFullIcon, { className: joinClass(css$l.img1, css$l.inputIcon) })
                      }
                    )
                  }
                ),
                /* @__PURE__ */ jsxRuntimeExports.jsx(
                  Form.Field,
                  {
                    name: "password",
                    defaultShowError: true,
                    rules: [
                      { required: true, message: trans("请输入密码") },
                      {
                        validator: (rule, value) => {
                          const error = checkInputUserPwd(value);
                          if (!error) {
                            return Promise.resolve();
                          }
                          return Promise.reject(error);
                        }
                      }
                    ],
                    children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                      Input,
                      {
                        type: "password",
                        placeholder: trans("密码"),
                        clearable: true,
                        focusBorder: true,
                        required: true,
                        className: css$l.loginInput,
                        prefix: /* @__PURE__ */ jsxRuntimeExports.jsx(PasswordFullIcon, { className: joinClass(css$l.img1, css$l.inputIcon) })
                      }
                    )
                  }
                ),
                /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$l.rolesBox, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
                  /* @__PURE__ */ jsxRuntimeExports.jsx(CheckBox, { checked: isSave, onChange: (isChecked) => {
                    setIsSave(isChecked);
                    Cache$1.set("isSave", isChecked);
                  } }),
                  /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$l.tipsSpan, children: trans("记住密码") })
                ] }) }),
                /* @__PURE__ */ jsxRuntimeExports.jsx(Form.Field, { children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                  Button,
                  {
                    className: css$l.submitButton,
                    block: true,
                    loading: loading2,
                    onClick: () => {
                      form.submit();
                    },
                    children: trans("登录")
                  }
                ) })
              ]
            }
          ),
          /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: games.some((item2) => item2.name === "试玩") ? css$l.btmTextBox : css$l.btmTextBox2, children: [
            /* @__PURE__ */ jsxRuntimeExports.jsx(
              "div",
              {
                onClick: () => {
                  useUserInfoStore.setState({ openForLogin: false });
                  navigate("/message?service=1");
                },
                children: trans("联系支持人员")
              }
            ),
            games.some((item2) => item2.name === "试玩") ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { onClick: () => {
              navigate("/demo-game?id=".concat("10", "&pid=").concat("0"));
              useUserInfoStore.setState({ openForLogin: false });
              useUserInfoStore.setState({ openForRegister: false });
            }, children: trans("试玩") }) : /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: " " })
          ] })
        ]
      }
    ),
    /* @__PURE__ */ jsxRuntimeExports.jsx(ThreeLogin, { isLogin: true })
  ] }) });
};
const LogInRegister2 = () => {
  const { openForLogin, token, theme, websetConfig, openForRegister } = useUserInfoStore();
  const [btnClose, setBtnClose] = reactExports.useState(true);
  reactExports.useEffect(() => {
    if (btnClose) {
      if (openForLogin) {
        setLoginType(true);
      } else {
        setLoginType(false);
      }
    } else {
      setBtnClose(true);
    }
  }, [openForLogin, openForRegister]);
  const [loginType3, setLoginType] = reactExports.useState(true);
  return /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, { children: /* @__PURE__ */ jsxRuntimeExports.jsx(
    Modal,
    {
      isOpen: openForLogin || openForRegister,
      closeByClickOut: false,
      showType: document.documentElement.getAttribute("temph5") === "12" ? "right" : "",
      onClose: (e) => {
        e && e.stopPropagation();
        setBtnClose(false);
        useUserInfoStore.setState({
          openForLogin: false,
          openForRegister: false
        });
      },
      children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$n.modalContent, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$n.loginBox, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          "div",
          {
            className: css$n.clsoeBtn,
            onClick: () => {
              setLoginType(true);
              setBtnClose(false);
              useUserInfoStore.setState({
                openForLogin: false,
                openForRegister: false
              });
              const googleDom = document.getElementById("g_id_signIn");
              if (googleDom) googleDom.innerHTML = "";
            },
            children: /* @__PURE__ */ jsxRuntimeExports.jsx(CloseIconInLogin, { className: css$n.iconsvg })
          }
        ),
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          "div",
          {
            className: css$n.wg3Back,
            onClick: () => {
              setLoginType(true);
              setBtnClose(false);
              useUserInfoStore.setState({
                openForLogin: false,
                openForRegister: false
              });
              const googleDom = document.getElementById("g_id_signIn");
              if (googleDom) googleDom.innerHTML = "";
            },
            children: /* @__PURE__ */ jsxRuntimeExports.jsx(ArrowLeftInMineIcon, {})
          }
        ),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$n.logo, children: websetConfig.logo_img ? /* @__PURE__ */ jsxRuntimeExports.jsx(
          Image,
          {
            src: websetConfig.logo_img,
            style: {
              maxWidth: "300rem"
            },
            remote: true,
            isGame: true
          }
        ) : null }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$n.titleBox, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx(
            "div",
            {
              className: joinClass(
                css$n.item,
                !loginType3 && css$n.titleContentActive
              ),
              onClick: () => {
                setLoginType(false);
              },
              children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: trans("注册") })
            }
          ),
          /* @__PURE__ */ jsxRuntimeExports.jsx(
            "div",
            {
              className: joinClass(
                css$n.item,
                loginType3 && css$n.titleContentActive
              ),
              onClick: () => {
                setLoginType(true);
              },
              children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
                " ",
                trans("会员登录")
              ] })
            }
          )
        ] }),
        loginType3 ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$n.marginTop, children: trans("仅支持账户登录") }) : /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$n.marginTop, children: trans("仅支持账户注册") }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { style: { width: "100%", overflow: "hidden" }, children: /* @__PURE__ */ jsxRuntimeExports.jsxs(
          "div",
          {
            className: joinClass(
              css$n.inSetBox,
              loginType3 ? "" : css$n.leftAnimate
            ),
            children: [
              /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: /* @__PURE__ */ jsxRuntimeExports.jsx(Login$1, {}) }),
              !loginType3 ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$n.loginType, children: /* @__PURE__ */ jsxRuntimeExports.jsx(Register$1, {}) }) : /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$n.loginType2, children: " " })
            ]
          }
        ) })
      ] }) })
    }
  ) });
};
const svg_theme_fill_color$l = "_svg_theme_fill_color_317jo_3";
const loginBox$5 = "_loginBox_317jo_55";
const submitButton$2 = "_submitButton_317jo_70";
const clsoeBtn$6 = "_clsoeBtn_317jo_82";
const titleBox$4 = "_titleBox_317jo_102";
const titleContent$2 = "_titleContent_317jo_123";
const titleIcon = "_titleIcon_317jo_135";
const titleIconSelf = "_titleIconSelf_317jo_148";
const loginBtn$6 = "_loginBtn_317jo_161";
const userNameBox$2 = "_userNameBox_317jo_182";
const userPwdBox$2 = "_userPwdBox_317jo_196";
const waringBox$2 = "_waringBox_317jo_210";
const icon$5 = "_icon_317jo_228";
const icon2$3 = "_icon2_317jo_236";
const rolesBox$1 = "_rolesBox_317jo_254";
const text1$2 = "_text1_317jo_267";
const p$1 = "_p_317jo_285";
const btmTextBox$1 = "_btmTextBox_317jo_303";
const noMarLeft$1 = "_noMarLeft_317jo_321";
const img1$2 = "_img1_317jo_325";
const loginInput$2 = "_loginInput_317jo_332";
const inputIcon = "_inputIcon_317jo_362";
const inputPrefix$1 = "_inputPrefix_317jo_369";
const active$5 = "_active_317jo_372";
const flex$1 = "_flex_317jo_387";
const nationIsShow$1 = "_nationIsShow_317jo_393";
const nation$1 = "_nation_317jo_393";
const strength$1 = "_strength_317jo_402";
const thLabel$1 = "_thLabel_317jo_411";
const strengthItem$1 = "_strengthItem_317jo_417";
const selectedLow$1 = "_selectedLow_317jo_424";
const selectedMiddle1$1 = "_selectedMiddle1_317jo_427";
const selectedMiddle2$1 = "_selectedMiddle2_317jo_430";
const selectedHigh$1 = "_selectedHigh_317jo_433";
const phoneContainer$1 = "_phoneContainer_317jo_437";
const country$1 = "_country_317jo_443";
const area$1 = "_area_317jo_450";
const wg3Back = "_wg3Back_317jo_460";
const css$k = {
  svg_theme_fill_color: svg_theme_fill_color$l,
  loginBox: loginBox$5,
  submitButton: submitButton$2,
  clsoeBtn: clsoeBtn$6,
  titleBox: titleBox$4,
  titleContent: titleContent$2,
  titleIcon,
  titleIconSelf,
  loginBtn: loginBtn$6,
  userNameBox: userNameBox$2,
  userPwdBox: userPwdBox$2,
  waringBox: waringBox$2,
  icon: icon$5,
  icon2: icon2$3,
  rolesBox: rolesBox$1,
  text1: text1$2,
  p: p$1,
  btmTextBox: btmTextBox$1,
  noMarLeft: noMarLeft$1,
  img1: img1$2,
  loginInput: loginInput$2,
  inputIcon,
  inputPrefix: inputPrefix$1,
  active: active$5,
  flex: flex$1,
  nationIsShow: nationIsShow$1,
  nation: nation$1,
  strength: strength$1,
  thLabel: thLabel$1,
  strengthItem: strengthItem$1,
  selectedLow: selectedLow$1,
  selectedMiddle1: selectedMiddle1$1,
  selectedMiddle2: selectedMiddle2$1,
  selectedHigh: selectedHigh$1,
  phoneContainer: phoneContainer$1,
  country: country$1,
  area: area$1,
  wg3Back
};
const CPFMaps = {
  purple: "/home/CPF.webp",
  bvGreen: "/home/CPF_bv_green.webp",
  hermesOrange: "/home/CPF_hermes_orange.webp",
  embraerBlue: "/home/CPF_embraer.webp",
  lightBrown: "/home/CPF_patek_philippe.webp",
  greenGold: "/home/CPF_green_gold.webp",
  venetaGrey: "/home/CPF_veneta_Grey.webp",
  versaceYellow: "/home/CPF_versace_yellow.webp",
  blue: "/home/CPF_blue.webp",
  black: "/home/CPF_black.webp",
  oilyGreen: "/home/CPF_oilyGreen.webp",
  elsaPink: "/home/CPF_elsaPink.webp",
  sk2: "/home/CPF_sk2.webp",
  furlaBlue: "/home/CPF_furlaBlue.webp",
  AnnaSuiPurple: "/home/CPF_AnnaSuiPurple.webp",
  burgundyRed: "/home/CPF_burgundyRed.webp",
  bvlgariBrown: "/home/CPF_bvlgariBrown.webp",
  usdtGreen: "/home/CPF_usdtGreen.webp",
  microsoftRed: "/home/CPF_microsoftRed.webp",
  ferrariBlack: "/home/CPF_ferrariBlack.webp",
  whiteGreen: "/home/CPF_whiteGreen.webp",
  whiteBlue: "/home/CPF_whiteBlue.webp",
  whiteRed: "/home/CPF_whiteRed.webp",
  whitePurple: "/home/CPF_whitePurple.webp",
  whiteDarkGreen: "/home/CPF_whiteDarkGreen.webp",
  whiteRedGucci: "/home/CPF_whiteRedGucci.webp",
  whiteBlack: "/home/CPF_whiteBlack.webp",
  whitePink: "/home/CPF_whitePink.webp",
  whiteYellow: "/home/CPF_whiteYellow.webp",
  whiteOrange: "/home/CPF_whiteOrange.webp",
  whiteBrownLauren: "/home/CPF_whiteBrownLauren.webp",
  whiteBrown: "/home/CPF_whiteBrown.webp",
  martinPurple: "/home/CPF_martinPurple.webp",
  whiteBlueFendi: "/home/CPF_whiteBlueFendi.webp",
  whiteGreenCindy: "/home/CPF_whiteGreenCindy.webp",
  celineBrownWhite: "/home/CPF_celineBrownWhite.webp",
  burberryBlueWhite: "/home/CPF_burberryBlueWhite.webp"
};
const Register = () => {
  const { openForRegister, theme, websetConfig } = useUserInfoStore();
  const { games } = useGameStore();
  const [form] = Form.useForm();
  const [loading2, setLoading] = reactExports.useState(false);
  const [isCheck, setIsCheck] = reactExports.useState(true);
  const [query] = useSearchParams();
  const navigate = useNavigate();
  const reg_need_phone = websetConfig.reg_need_phone || "0";
  const register_need_name_switch = websetConfig.register_need_name_switch || "0";
  const realNameRequired = websetConfig.realNameRequired || "0";
  const phoneRequired = websetConfig.phoneRequired || "0";
  const [curPsdLevel, setCurPsdLevel] = reactExports.useState(0);
  const curLevelClass = reactExports.useMemo(() => {
    if (curPsdLevel === 1) {
      return css$k.selectedLow;
    } else if (curPsdLevel === 2) {
      return css$k.selectedMiddle1;
    } else if (curPsdLevel === 3) {
      return css$k.selectedMiddle2;
    } else if (curPsdLevel === 4) {
      return css$k.selectedHigh;
    }
    return "";
  }, [curPsdLevel]);
  const postReg = async (params) => {
    const [res, error] = await register(params);
    if (!error) {
      Message.success(trans("注册成功"));
      useUserInfoStore.setState({ openForRegister: false });
      useUserInfoStore.setState({ alertAfterLogin: true });
      Cache$1.set("isAlreadyRegister", true);
    }
  };
  const submit = async (_params) => {
    console.error("submit", _params);
    if (!isCheck) {
      Message.error(trans("请勾选同意《用户协议》"));
      return;
    }
    const params = {
      ..._params,
      verify_code: "",
      code: "",
      link_id: query.get("id") || Cache$1.get("link_id") || "",
      reg_url: location.href
    };
    if (!params.realname) {
      delete params.realname;
    }
    setLoading(true);
    if (websetConfig.authLogReg !== "1") {
      await postReg(params);
    } else {
      const result = await slideBlockByGeetest4(websetConfig.authLogRegType);
      if (result) {
        await postReg(
          Object.assign(params, {
            pass_token: result.pass_token,
            lot_number: result.lot_number,
            captcha_output: result.captcha_output,
            gen_time: result.gen_time
          })
        );
      }
    }
    setLoading(false);
  };
  reactExports.useEffect(() => {
    if (openForRegister) {
      form.resetFields();
    }
  }, [openForRegister]);
  return /* @__PURE__ */ jsxRuntimeExports.jsx(
    Modal,
    {
      isOpen: openForRegister,
      closeByClickOut: false,
      showType: document.documentElement.getAttribute("temph5") === "12" ? "right" : "",
      onClose: (e) => {
        e && e.stopPropagation();
        useUserInfoStore.setState({ openForRegister: false });
      },
      children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$k.loginBox, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          "div",
          {
            className: css$k.clsoeBtn,
            onClick: () => {
              useUserInfoStore.setState({ openForRegister: false });
              const googleDom = document.getElementById("g_id_signIn");
              if (googleDom) googleDom.innerHTML = "";
            },
            children: /* @__PURE__ */ jsxRuntimeExports.jsx(CloseIconInLogin, {})
          }
        ),
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          "div",
          {
            className: css$k.wg3Back,
            onClick: () => {
              useUserInfoStore.setState({ openForRegister: false });
              const googleDom = document.getElementById("g_id_signIn");
              if (googleDom) googleDom.innerHTML = "";
            },
            children: /* @__PURE__ */ jsxRuntimeExports.jsx(ArrowLeftInMineIcon, {})
          }
        ),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$k.titleBox, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$k.titleContent, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx(UserFullIcon, { className: joinClass(css$k.titleIcon, css$k.titleIconSelf) }),
          trans("注册您的账户")
        ] }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          "div",
          {
            onClick: (e) => {
              e.stopPropagation();
            },
            children: /* @__PURE__ */ jsxRuntimeExports.jsxs(
              Form,
              {
                form,
                size: "small",
                initialValues: {
                  username: "",
                  password: "",
                  repassword: "",
                  phone: "",
                  realname: "",
                  currency: "1",
                  cpf: ""
                },
                onFinish: (values) => submit(values),
                onFinishFailed: ({ values, errorFields, outOfDate }) => {
                  console.log("Failed:", values, errorFields, outOfDate);
                },
                onValuesChange: (changedValues, allValues) => {
                  if (changedValues.username) {
                    form.setFieldsValue({
                      username: changedValues.username.toLowerCase().trim()
                    });
                  }
                  if (changedValues.phone) {
                    form.setFieldsValue({
                      phone: changedValues.phone.replace(/\D/g, "") + " "
                    });
                    setTimeout(() => {
                      form.setFieldsValue({
                        phone: changedValues.phone.replace(/\D/g, "")
                      });
                    }, 0);
                  }
                  setCurPsdLevel(checkPasswordStrength(allValues.password));
                },
                children: [
                  /* @__PURE__ */ jsxRuntimeExports.jsx(
                    Form.Field,
                    {
                      name: "username",
                      defaultShowError: true,
                      rules: [
                        { required: true, message: trans("请输入用户名") },
                        {
                          validator: (rule, value) => {
                            const error = checkInputUserName(value);
                            if (!error) {
                              return Promise.resolve();
                            }
                            return Promise.reject(error);
                          }
                        }
                      ],
                      children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                        Input,
                        {
                          placeholder: trans("用户名"),
                          clearable: true,
                          focusBorder: true,
                          required: true,
                          className: css$k.loginInput,
                          prefix: /* @__PURE__ */ jsxRuntimeExports.jsx(UserFullIcon, { className: joinClass(css$k.img1, css$k.inputIcon) })
                        }
                      )
                    }
                  ),
                  /* @__PURE__ */ jsxRuntimeExports.jsx(
                    Form.Field,
                    {
                      name: "password",
                      defaultShowError: true,
                      rules: [
                        { required: true, message: trans("请输入密码") },
                        {
                          validator: (rule, value) => {
                            const error = checkInputUserPwd(value);
                            if (!error) {
                              return Promise.resolve();
                            }
                            return Promise.reject(error);
                          }
                        }
                      ],
                      children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                        Input,
                        {
                          type: "password",
                          placeholder: trans("密码"),
                          clearable: true,
                          focusBorder: true,
                          required: true,
                          className: css$k.loginInput,
                          prefix: /* @__PURE__ */ jsxRuntimeExports.jsx(LockIcon, { className: joinClass(css$k.img1, css$k.inputIcon) })
                        }
                      )
                    }
                  ),
                  /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$k.strength, children: [
                    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$k.thLabel, children: trans("密码强度") }),
                    /* @__PURE__ */ jsxRuntimeExports.jsx(
                      "div",
                      {
                        className: joinClass(
                          css$k.strengthItem,
                          curPsdLevel >= 1 ? curLevelClass : ""
                        )
                      }
                    ),
                    /* @__PURE__ */ jsxRuntimeExports.jsx(
                      "div",
                      {
                        className: joinClass(
                          css$k.strengthItem,
                          curPsdLevel >= 2 ? curLevelClass : ""
                        )
                      }
                    ),
                    /* @__PURE__ */ jsxRuntimeExports.jsx(
                      "div",
                      {
                        className: joinClass(
                          css$k.strengthItem,
                          curPsdLevel >= 3 ? curLevelClass : ""
                        )
                      }
                    ),
                    /* @__PURE__ */ jsxRuntimeExports.jsx(
                      "div",
                      {
                        className: joinClass(
                          css$k.strengthItem,
                          curPsdLevel >= 4 ? curLevelClass : ""
                        )
                      }
                    )
                  ] }),
                  /* @__PURE__ */ jsxRuntimeExports.jsx(
                    Form.Field,
                    {
                      name: "repassword",
                      defaultShowError: true,
                      rules: [
                        { required: true, message: trans("请输入密码") },
                        {
                          validator: (rule, value) => {
                            const error = checkInputUserPwd(value);
                            if (!error) {
                              console.log(form.getFieldValue("password"), value);
                              if (value === form.getFieldValue("password")) {
                                return Promise.resolve();
                              } else {
                                return Promise.reject(trans("两次输入的密码不一样"));
                              }
                            }
                            return Promise.reject(error);
                          }
                        }
                      ],
                      children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                        Input,
                        {
                          type: "password",
                          placeholder: trans("确认密码"),
                          clearable: true,
                          focusBorder: true,
                          required: true,
                          className: css$k.loginInput,
                          prefix: /* @__PURE__ */ jsxRuntimeExports.jsx(LockIcon, { className: joinClass(css$k.img1, css$k.inputIcon) })
                        }
                      )
                    }
                  ),
                  reg_need_phone === "1" ? /* @__PURE__ */ jsxRuntimeExports.jsx(
                    Form.Field,
                    {
                      name: "phone",
                      defaultShowError: true,
                      rules: [
                        {
                          required: phoneRequired === "1",
                          message: trans("请输入手机号")
                        },
                        {
                          validator: (rule, value) => {
                            const isOkey = checkInputPhoneNumber(value);
                            if (isOkey) {
                              return Promise.resolve();
                            }
                            return Promise.reject(trans("手机号格式不正确"));
                          }
                        }
                      ],
                      children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                        Input,
                        {
                          placeholder: trans("输入手机号码"),
                          clearable: true,
                          focusBorder: true,
                          required: phoneRequired === "1",
                          className: css$k.loginInput,
                          prefix: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$k.phoneContainer, children: [
                            /* @__PURE__ */ jsxRuntimeExports.jsx(PhoneIcon, { className: css$k.img1 }),
                            /* @__PURE__ */ jsxRuntimeExports.jsx(
                              Image,
                              {
                                className: css$k.country,
                                src: getCountryFlag()
                              }
                            ),
                            /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$k.area, children: countryCode })
                          ] })
                        }
                      )
                    }
                  ) : null,
                  (websetConfig == null ? void 0 : websetConfig["isShowCPF"]) === "1" && /* @__PURE__ */ jsxRuntimeExports.jsx(
                    Form.Field,
                    {
                      defaultShowError: true,
                      name: "cpf",
                      rules: [
                        { required: (websetConfig == null ? void 0 : websetConfig["requiredRegisterCPF"]) + "" === "1", message: trans("EnterCPFTag") },
                        // {
                        //   validator: (rule, value) => {
                        //     if(value) {
                        //       const val = validarCPF(value);
                        //       if (val) {
                        //         return Promise.resolve();
                        //       }
                        //       return Promise.reject(trans('EnterCPFTag'));
                        //     } else {
                        //       return Promise.resolve();
                        //     }
                        //   }
                        // }
                        {
                          pattern: /^\d{11}$/,
                          message: trans("EnterCPFTag")
                        }
                      ],
                      children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                        Input,
                        {
                          placeholder: trans("输入11位CPF号码"),
                          clearable: true,
                          noBg: true,
                          required: (websetConfig == null ? void 0 : websetConfig["requiredRegisterCPF"]) + "" === "1",
                          maxLength: 11,
                          prefix: /* @__PURE__ */ jsxRuntimeExports.jsx(
                            Image,
                            {
                              className: css$k.img1,
                              src: CPFMaps[theme] || "/home/CPF.webp"
                            }
                          )
                        }
                      )
                    }
                  ),
                  register_need_name_switch === "1" ? /* @__PURE__ */ jsxRuntimeExports.jsx(
                    Form.Field,
                    {
                      name: "realname",
                      defaultShowError: true,
                      rules: [
                        {
                          required: realNameRequired === "1",
                          message: trans("输入您的真实姓名")
                        },
                        {
                          pattern: curCountryNameReg,
                          message: trans("真实姓名格式错误")
                        }
                      ],
                      children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                        Input,
                        {
                          placeholder: trans("全名"),
                          clearable: true,
                          focusBorder: true,
                          required: realNameRequired == "1",
                          className: css$k.loginInput,
                          prefix: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$k.phoneContainer, children: /* @__PURE__ */ jsxRuntimeExports.jsx(PixAccountIcon, { className: css$k.img1 }) }),
                          onBlur: (e) => {
                          }
                        }
                      )
                    }
                  ) : null,
                  (websetConfig == null ? void 0 : websetConfig["currencyRequired"]) === "1" && /* @__PURE__ */ jsxRuntimeExports.jsx(Form.Field, { name: "currency", children: /* @__PURE__ */ jsxRuntimeExports.jsx(SelectTow, { noBg: true, items: [
                    {
                      label: "".concat(getMoneyUnit(), " (").concat(getMoneyUnit(), ")"),
                      value: "1",
                      icon: getCountryFlag(true)
                    }
                  ] }) }),
                  /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$k.rolesBox, children: [
                    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: /* @__PURE__ */ jsxRuntimeExports.jsx(CheckBox, { checked: isCheck, onChange: (c) => {
                      setIsCheck(c);
                    } }) }),
                    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$k.text1), children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$k.p, children: [
                      trans("我已年满18岁，我已阅读并同意"),
                      /* @__PURE__ */ jsxRuntimeExports.jsx(
                        "span",
                        {
                          onClick: () => {
                            useUserInfoStore.setState({ openForXieYi: true });
                          },
                          children: "《".concat(trans("用户协议"), "》")
                        }
                      )
                    ] }) })
                  ] }),
                  /* @__PURE__ */ jsxRuntimeExports.jsx(Form.Field, { children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                    Button,
                    {
                      block: true,
                      className: css$k.submitButton,
                      loading: loading2,
                      onClick: () => {
                        form.submit();
                      },
                      children: trans("注册")
                    }
                  ) })
                ]
              }
            )
          }
        ),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$k.btmTextBox, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx(
            "div",
            {
              onClick: () => {
                useUserInfoStore.setState({ openForRegister: false });
                navigate("/message?service=1");
              },
              children: trans("联系支持人员")
            }
          ),
          games.some((item2) => item2.name === "试玩") ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { onClick: () => {
            navigate("/demo-game?id=".concat("10", "&pid=").concat("0"));
            useUserInfoStore.setState({ openForRegister: false });
          }, children: trans("试玩") }) : /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: " " }),
          /* @__PURE__ */ jsxRuntimeExports.jsx(
            "div",
            {
              onClick: () => {
                useUserInfoStore.setState({ openForRegister: false });
                useUserInfoStore.setState({ openForLogin: true });
              },
              children: trans("登录")
            }
          )
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsx(ThreeLogin, {})
      ] })
    }
  );
};
const LoginBox = () => {
  const { logRegisterStyle } = useUserInfoStore();
  return /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
    logRegisterStyle == 1 && /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx(Login$3, {}),
      /* @__PURE__ */ jsxRuntimeExports.jsx(Register, {})
    ] }),
    logRegisterStyle == 2 && /* @__PURE__ */ jsxRuntimeExports.jsx(LogInRegister2$1, {}),
    logRegisterStyle == 3 && /* @__PURE__ */ jsxRuntimeExports.jsx(LogInRegister2, {})
  ] });
};
const svg_theme_fill_color$k = "_svg_theme_fill_color_xn8tc_3";
const loginBox$4 = "_loginBox_xn8tc_55";
const noPaddingBottomBox$1 = "_noPaddingBottomBox_xn8tc_62";
const submitButton$1 = "_submitButton_xn8tc_65";
const clsoeBtn$5 = "_clsoeBtn_xn8tc_73";
const titleBox$3 = "_titleBox_xn8tc_96";
const titleContent$1 = "_titleContent_xn8tc_117";
const googleAccount = "_googleAccount_xn8tc_126";
const loginBtn$5 = "_loginBtn_xn8tc_132";
const userNameBox$1 = "_userNameBox_xn8tc_152";
const userPwdBox$1 = "_userPwdBox_xn8tc_165";
const waringBox$1 = "_waringBox_xn8tc_178";
const rolesBox = "_rolesBox_xn8tc_198";
const text1$1 = "_text1_xn8tc_210";
const p = "_p_xn8tc_228";
const btmTextBox = "_btmTextBox_xn8tc_323";
const noMarLeft = "_noMarLeft_xn8tc_403";
const img1$1 = "_img1_xn8tc_406";
const loginInput$1 = "_loginInput_xn8tc_412";
const brlSelectBox = "_brlSelectBox_xn8tc_417";
const downArrowIcon = "_downArrowIcon_xn8tc_423";
const brlDisInput = "_brlDisInput_xn8tc_426";
const inputPrefix = "_inputPrefix_xn8tc_457";
const active$4 = "_active_xn8tc_460";
const flex = "_flex_xn8tc_475";
const nationIsShow = "_nationIsShow_xn8tc_481";
const nation = "_nation_xn8tc_481";
const phoneContainer = "_phoneContainer_xn8tc_490";
const country = "_country_xn8tc_496";
const area = "_area_xn8tc_503";
const css$j = {
  svg_theme_fill_color: svg_theme_fill_color$k,
  loginBox: loginBox$4,
  noPaddingBottomBox: noPaddingBottomBox$1,
  submitButton: submitButton$1,
  clsoeBtn: clsoeBtn$5,
  titleBox: titleBox$3,
  titleContent: titleContent$1,
  googleAccount,
  loginBtn: loginBtn$5,
  userNameBox: userNameBox$1,
  userPwdBox: userPwdBox$1,
  waringBox: waringBox$1,
  rolesBox,
  text1: text1$1,
  p,
  btmTextBox,
  noMarLeft,
  img1: img1$1,
  loginInput: loginInput$1,
  brlSelectBox,
  downArrowIcon,
  brlDisInput,
  inputPrefix,
  active: active$4,
  flex,
  nationIsShow,
  nation,
  phoneContainer,
  country,
  area
};
const RegisterByThree = () => {
  const { theme, websetConfig } = useUserInfoStore();
  const {
    openForRegisterByThree,
    setOpenForRegisterByThree,
    threeLoginInfo,
    setThreeLoginInfo,
    setOpenForInfoPreviewByThree
  } = useThreeLoginStore();
  const [form] = Form.useForm();
  const [query] = useSearchParams();
  const reg_need_phone = websetConfig.reg_need_phone || "0";
  const register_need_name_switch = websetConfig.register_need_name_switch || "0";
  const realNameRequired = websetConfig.realNameRequired || "0";
  const phoneRequired = websetConfig.phoneRequired || "0";
  const submit = async (_params) => {
    const params = {
      ...threeLoginInfo,
      ..._params,
      verify_code: "",
      code: "",
      link_id: query.get("id") || Cache$1.get("link_id") || "",
      reg_url: location.href
    };
    if (!params.realname) {
      delete params.realname;
    }
    setThreeLoginInfo(params);
    setOpenForRegisterByThree(false);
    setOpenForInfoPreviewByThree(true);
  };
  const email = ((threeLoginInfo == null ? void 0 : threeLoginInfo.email) || "").slice(0, 3);
  reactExports.useEffect(() => {
    if (openForRegisterByThree) {
      form.resetFields();
    }
  }, [openForRegisterByThree]);
  return /* @__PURE__ */ jsxRuntimeExports.jsx(
    Modal,
    {
      zIndex: 1e4,
      isOpen: openForRegisterByThree,
      closeByClickOut: false,
      onClose: (e) => {
        e && e.stopPropagation();
        useThreeLoginStore.setState({ openForRegisterByThree: false });
      },
      children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$j.loginBox, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          "div",
          {
            className: css$j.clsoeBtn,
            onClick: () => {
              useThreeLoginStore.setState({ openForRegisterByThree: false });
            },
            children: /* @__PURE__ */ jsxRuntimeExports.jsx(CloseIconInMineIcon, {})
          }
        ),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$j.titleBox, children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$j.titleContent, children: trans("设置账号") }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs(
          "div",
          {
            onClick: (e) => {
              e.stopPropagation();
            },
            children: [
              (threeLoginInfo == null ? void 0 : threeLoginInfo.provider) == "google" && /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$j.googleAccount, children: [
                trans("google account"),
                " ",
                email,
                "******@gmail.com"
              ] }),
              (threeLoginInfo == null ? void 0 : threeLoginInfo.provider) == "facebook" && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$j.googleAccount, children: trans("facebook account") }),
              /* @__PURE__ */ jsxRuntimeExports.jsx(
                Input,
                {
                  placeholder: "",
                  focusBorder: true,
                  value: "".concat(getMoneyUnit(), "(").concat(getMoneyUnit(), ")"),
                  required: true,
                  readOnly: true,
                  className: joinClass(css$j.loginInput, css$j.brlSelectBox),
                  prefix: /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { className: css$j.country, src: getCountryFlag() }),
                  suffix: /* @__PURE__ */ jsxRuntimeExports.jsx(DownIcon, { className: css$j.downArrowIcon })
                }
              ),
              /* @__PURE__ */ jsxRuntimeExports.jsxs(
                Form,
                {
                  form,
                  initialValues: {
                    username: "",
                    password: "",
                    repassword: "",
                    phone: "",
                    realname: ""
                  },
                  onFinish: (values) => submit(values),
                  onFinishFailed: ({ values, errorFields, outOfDate }) => {
                    console.log("Failed:", values, errorFields, outOfDate);
                  },
                  onValuesChange: (changedValues, allValues) => {
                    if (changedValues.phone) {
                      form.setFieldsValue({
                        phone: changedValues.phone.replace(/\D/g, "") + " "
                      });
                      setTimeout(() => {
                        form.setFieldsValue({
                          phone: changedValues.phone.replace(/\D/g, "")
                        });
                      }, 0);
                    }
                  },
                  children: [
                    register_need_name_switch === "1" ? /* @__PURE__ */ jsxRuntimeExports.jsx(
                      Form.Field,
                      {
                        name: "realname",
                        className: css$j.noPaddingBottomBox,
                        defaultShowError: true,
                        rules: [
                          {
                            required: realNameRequired === "1",
                            message: trans("全名")
                          }
                        ],
                        children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                          Input,
                          {
                            placeholder: trans("全名"),
                            clearable: true,
                            focusBorder: true,
                            required: realNameRequired === "1",
                            className: css$j.loginInput,
                            prefix: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$j.phoneContainer, children: /* @__PURE__ */ jsxRuntimeExports.jsx(PixAccountIcon, { className: css$j.img1 }) }),
                            onBlur: (e) => {
                            }
                          }
                        )
                      }
                    ) : null,
                    reg_need_phone === "1" ? /* @__PURE__ */ jsxRuntimeExports.jsx(
                      Form.Field,
                      {
                        name: "phone",
                        defaultShowError: true,
                        className: css$j.noPaddingBottomBox,
                        rules: [
                          {
                            required: phoneRequired === "1",
                            message: trans("请输入手机号")
                          },
                          {
                            validator: (rule, value) => {
                              const isOkey = checkInputPhoneNumber(value);
                              if (isOkey) {
                                return Promise.resolve();
                              }
                              return Promise.reject(trans("手机号格式不正确"));
                            }
                          }
                        ],
                        children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                          Input,
                          {
                            placeholder: trans("输入手机号码"),
                            clearable: true,
                            focusBorder: true,
                            required: phoneRequired === "1",
                            className: css$j.loginInput,
                            prefix: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$j.phoneContainer, children: [
                              /* @__PURE__ */ jsxRuntimeExports.jsx(PhoneIcon, { className: css$j.img1 }),
                              /* @__PURE__ */ jsxRuntimeExports.jsx(
                                Image,
                                {
                                  className: css$j.country,
                                  src: getCountryFlag()
                                }
                              ),
                              /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$j.area, children: countryCode })
                            ] })
                          }
                        )
                      }
                    ) : null,
                    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$j.rolesBox, children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$j.text1), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$j.p, children: trans("A moeda determina os jogos de terceiros e os métodos de pagamento e não pode ser modificada.") }) }) }),
                    /* @__PURE__ */ jsxRuntimeExports.jsx(Form.Field, { children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                      Button,
                      {
                        block: true,
                        className: css$j.submitButton,
                        onClick: () => {
                          form.submit();
                        },
                        children: trans("Complete")
                      }
                    ) })
                  ]
                }
              )
            ]
          }
        )
      ] })
    }
  );
};
const svg_theme_fill_color$j = "_svg_theme_fill_color_1di51_3";
const infoPreviewByThree = "_infoPreviewByThree_1di51_55";
const inBox$1 = "_inBox_1di51_173";
const save_box = "_save_box_1di51_176";
const other_box = "_other_box_1di51_292";
const titleBox$2 = "_titleBox_1di51_296";
const infoBox = "_infoBox_1di51_312";
const itemBox$1 = "_itemBox_1di51_312";
const text_box = "_text_box_1di51_315";
const str = "_str_1di51_411";
const icon$4 = "_icon_1di51_489";
const iconBox = "_iconBox_1di51_521";
const icon2$2 = "_icon2_1di51_528";
const copyBox = "_copyBox_1di51_640";
const fitSpan = "_fitSpan_1di51_739";
const copyIcon = "_copyIcon_1di51_857";
const clodeModal = "_clodeModal_1di51_971";
const loading = "_loading_1di51_992";
const css$i = {
  svg_theme_fill_color: svg_theme_fill_color$j,
  infoPreviewByThree,
  inBox: inBox$1,
  save_box,
  other_box,
  titleBox: titleBox$2,
  infoBox,
  itemBox: itemBox$1,
  text_box,
  str,
  icon: icon$4,
  iconBox,
  icon2: icon2$2,
  copyBox,
  fitSpan,
  copyIcon,
  clodeModal,
  loading
};
const svg_theme_fill_color$i = "_svg_theme_fill_color_1wf0t_3";
const tip1 = "_tip1_1wf0t_55";
const tip2 = "_tip2_1wf0t_63";
const iptTip = "_iptTip_1wf0t_70";
const btnsBox = "_btnsBox_1wf0t_77";
const btnItem1 = "_btnItem1_1wf0t_85";
const btnItem2 = "_btnItem2_1wf0t_101";
const conSpan = "_conSpan_1wf0t_118";
const comCss = {
  svg_theme_fill_color: svg_theme_fill_color$i,
  tip1,
  tip2,
  iptTip,
  btnsBox,
  btnItem1,
  btnItem2,
  conSpan
};
const InfoPreviewByThree = () => {
  var _a;
  const {
    setOpenForInfoPreviewByThree,
    openForInfoPreviewByThree,
    setOpenForEditInfoByThree,
    threeLoginInfo
  } = useThreeLoginStore();
  const showEditInfoModal = () => {
    setOpenForInfoPreviewByThree(false);
    setOpenForEditInfoByThree(true);
  };
  const { websetConfig } = useUserInfoStore();
  const [saveLoading, setSaveLoading] = reactExports.useState(false);
  const saveImgRef = reactExports.useRef();
  const postReg = async (params) => {
    if (!saveLoading) {
      setSaveLoading(true);
      const [res, error] = await register(params);
      if (!error) {
        await saveImgByElement(saveImgRef.current);
        Message.success(trans("注册成功"));
        setOpenForInfoPreviewByThree(false);
        Cache.set("isAlreadyRegister", true);
        useUserInfoStore.setState({ alertAfterLogin: true });
      }
      setSaveLoading(false);
    }
  };
  const submit = async () => {
    var _a2;
    const params = {
      ...threeLoginInfo,
      username: (_a2 = threeLoginInfo == null ? void 0 : threeLoginInfo.username) == null ? void 0 : _a2.toLowerCase()
    };
    if (websetConfig.authLogReg !== "1") {
      await postReg(params);
    } else {
      const result = await slideBlockByGeetest4(websetConfig.authLogRegType);
      if (result) {
        await postReg(Object.assign(params, {
          pass_token: result.pass_token,
          lot_number: result.lot_number,
          captcha_output: result.captcha_output,
          gen_time: result.gen_time
        }));
      }
    }
  };
  return /* @__PURE__ */ jsxRuntimeExports.jsx(Modal, { isOpen: openForInfoPreviewByThree, zIndex: 1e4, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$i.infoPreviewByThree, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$i.inBox, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { ref: saveImgRef, className: css$i.save_box, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$i.titleBox, children: trans("Account and password prompts") }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: comCss.tip1, children: trans("Congratulations on successful registration!") }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: comCss.tip2, children: [
          trans(
            "The system has automatically generated the following account number and password for you. You can copy and save the text or pictures."
          ),
          /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: comCss.iptTip, children: trans("And do not disclose it to others to avoid causing losses!") })
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$i.infoBox, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$i.itemBox, onClick: () => showEditInfoModal(), children: [
            /* @__PURE__ */ jsxRuntimeExports.jsx(UserFullIcon, { className: css$i.icon }),
            /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$i.text_box, children: [
              /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: trans("Account：") }),
              /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$i.str, children: (_a = threeLoginInfo == null ? void 0 : threeLoginInfo.username) == null ? void 0 : _a.toLowerCase() })
            ] }),
            /* @__PURE__ */ jsxRuntimeExports.jsx(
              "div",
              {
                className: css$i.iconBox,
                onClick: () => showEditInfoModal(),
                children: /* @__PURE__ */ jsxRuntimeExports.jsx(EditString, { className: css$i.icon2 })
              }
            )
          ] }),
          /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$i.itemBox, children: [
            /* @__PURE__ */ jsxRuntimeExports.jsx(PasswordFullIcon, { className: css$i.icon }),
            /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$i.text_box, children: [
              trans("Password："),
              /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$i.str, children: threeLoginInfo == null ? void 0 : threeLoginInfo.password })
            ] }),
            /* @__PURE__ */ jsxRuntimeExports.jsx(
              "div",
              {
                className: css$i.iconBox,
                onClick: () => showEditInfoModal(),
                children: /* @__PURE__ */ jsxRuntimeExports.jsx(EditString, { className: css$i.icon2 })
              }
            )
          ] })
        ] })
      ] }),
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$i.other_box, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$i.copyBox, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$i.fitSpan, onClick: () => {
            copyText("".concat(trans("Account：")).concat((threeLoginInfo == null ? void 0 : threeLoginInfo.username) || "", " ").concat(trans("Password：")).concat((threeLoginInfo == null ? void 0 : threeLoginInfo.password) || ""));
            Message.success(trans("复制成功"));
          }, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { children: [
            trans("One-click copy of account password"),
            /* @__PURE__ */ jsxRuntimeExports.jsx(RectCopyIcon, { className: css$i.copyIcon })
          ] }) }),
          trans("Remember to save it!")
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: comCss.btnsBox, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(comCss.btnItem1), onClick: () => showEditInfoModal(), children: /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: trans("Modify account") }) }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "".concat(comCss.btnItem2, " ").concat(saveLoading && css$i.loading), onClick: async () => {
            await submit();
          }, children: /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: trans("Save the picture and register") }) })
        ] })
      ] })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$i.clodeModal, onClick: () => {
      setOpenForInfoPreviewByThree(false);
    }, children: /* @__PURE__ */ jsxRuntimeExports.jsx(CloseModal, { className: css$i.icon }) })
  ] }) });
};
const svg_theme_fill_color$h = "_svg_theme_fill_color_1vqdw_3";
const editInfoByThree = "_editInfoByThree_1vqdw_55";
const noPaddingBottomBox = "_noPaddingBottomBox_1vqdw_62";
const loginInput = "_loginInput_1vqdw_65";
const titleBox$1 = "_titleBox_1vqdw_83";
const titleContent = "_titleContent_1vqdw_106";
const loginBtn$4 = "_loginBtn_1vqdw_115";
const userNameBox = "_userNameBox_1vqdw_135";
const userPwdBox = "_userPwdBox_1vqdw_148";
const waringBox = "_waringBox_1vqdw_161";
const icon$3 = "_icon_1vqdw_175";
const icon2$1 = "_icon2_1vqdw_182";
const img1 = "_img1_1vqdw_198";
const findPsd = "_findPsd_1vqdw_208";
const strength = "_strength_1vqdw_215";
const thLabel = "_thLabel_1vqdw_224";
const strengthItem = "_strengthItem_1vqdw_229";
const selectedLow = "_selectedLow_1vqdw_235";
const selectedMiddle1 = "_selectedMiddle1_1vqdw_238";
const selectedMiddle2 = "_selectedMiddle2_1vqdw_241";
const selectedHigh = "_selectedHigh_1vqdw_244";
const css$h = {
  svg_theme_fill_color: svg_theme_fill_color$h,
  editInfoByThree,
  noPaddingBottomBox,
  loginInput,
  titleBox: titleBox$1,
  titleContent,
  loginBtn: loginBtn$4,
  userNameBox,
  userPwdBox,
  waringBox,
  icon: icon$3,
  icon2: icon2$1,
  img1,
  findPsd,
  strength,
  thLabel,
  strengthItem,
  selectedLow,
  selectedMiddle1,
  selectedMiddle2,
  selectedHigh
};
const EditInfoByThree = () => {
  const {
    openForEditInfoByThree,
    setOpenForEditInfoByThree,
    setOpenForInfoPreviewByThree,
    threeLoginInfo,
    setThreeLoginInfo
  } = useThreeLoginStore();
  useUserInfoStore();
  const [form] = Form.useForm();
  reactExports.useState(Cache$1.get("isSave") || false);
  useNavigate();
  const [curPsdLevel, setCurPsdLevel] = reactExports.useState(0);
  const curLevelClass = (() => {
    if (curPsdLevel === 1) {
      return css$h.selectedLow;
    } else if (curPsdLevel === 2) {
      return css$h.selectedMiddle1;
    } else if (curPsdLevel === 3) {
      return css$h.selectedMiddle2;
    } else if (curPsdLevel === 4) {
      return css$h.selectedHigh;
    }
    return "";
  })();
  reactExports.useEffect(() => {
    var _a;
    if (openForEditInfoByThree) {
      form.setFieldsValue({
        username: ((_a = threeLoginInfo == null ? void 0 : threeLoginInfo.username) == null ? void 0 : _a.toLowerCase()) || "",
        password: (threeLoginInfo == null ? void 0 : threeLoginInfo.password) || ""
      });
      setCurPsdLevel(checkPasswordStrength((threeLoginInfo == null ? void 0 : threeLoginInfo.password) || ""));
    }
  }, [openForEditInfoByThree, threeLoginInfo]);
  const submit = async (values) => {
    const params = {
      ...threeLoginInfo,
      ...values
    };
    setThreeLoginInfo(params);
    setOpenForEditInfoByThree(false);
    setOpenForInfoPreviewByThree(true);
  };
  return /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, { children: /* @__PURE__ */ jsxRuntimeExports.jsx(
    Modal,
    {
      zIndex: 1e4,
      isOpen: openForEditInfoByThree,
      closeByClickOut: false,
      onClose: (e) => {
        e && e.stopPropagation();
        setOpenForEditInfoByThree(false);
      },
      children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$h.editInfoByThree, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$h.titleBox, children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$h.titleContent, children: trans("Change account and password") }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: comCss.tip1, children: trans("Congratulations on successful registration!") }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: comCss.tip2, children: [
          trans(
            "The system has automatically generated the following account number and password for you. You can copy and save the text or pictures."
          ),
          /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: comCss.iptTip, children: trans(
            "And do not disclose it to others to avoid causing losses!"
          ) })
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          "div",
          {
            onClick: (e) => {
              e.stopPropagation();
            },
            children: /* @__PURE__ */ jsxRuntimeExports.jsxs(
              Form,
              {
                form,
                initialValues: {
                  username: "",
                  password: ""
                },
                onFinish: (values) => submit(values),
                onFinishFailed: ({ values, errorFields, outOfDate }) => {
                  console.log("Failed:", values, errorFields, outOfDate);
                },
                onValuesChange: (changedValues, allValues) => {
                  if (changedValues.username) {
                    form.setFieldsValue({
                      username: changedValues.username.toLowerCase().trim()
                    });
                  }
                  setCurPsdLevel(checkPasswordStrength(allValues.password));
                },
                children: [
                  /* @__PURE__ */ jsxRuntimeExports.jsx(
                    Form.Field,
                    {
                      name: "username",
                      className: css$h.noPaddingBottomBox,
                      defaultShowError: true,
                      rules: [
                        { required: true, message: trans("请输入用户名") },
                        {
                          validator: (rule, value) => {
                            const error = checkInputUserName(value);
                            if (!error) {
                              return Promise.resolve();
                            }
                            return Promise.reject(error);
                          }
                        }
                      ],
                      children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                        Input,
                        {
                          placeholder: trans("用户名"),
                          clearable: true,
                          focusBorder: true,
                          required: true,
                          className: css$h.loginInput,
                          prefix: /* @__PURE__ */ jsxRuntimeExports.jsx(UserFullIcon, { className: css$h.img1 })
                        }
                      )
                    }
                  ),
                  /* @__PURE__ */ jsxRuntimeExports.jsx(
                    Form.Field,
                    {
                      name: "password",
                      className: css$h.noPaddingBottomBox,
                      defaultShowError: true,
                      rules: [
                        { required: true, message: trans("请输入密码") },
                        {
                          validator: (rule, value) => {
                            const error = checkInputUserPwd(value);
                            if (!error) {
                              return Promise.resolve();
                            }
                            return Promise.reject(error);
                          }
                        }
                      ],
                      children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                        Input,
                        {
                          type: "password",
                          placeholder: trans("密码"),
                          clearable: true,
                          focusBorder: true,
                          required: true,
                          className: css$h.loginInput,
                          prefix: /* @__PURE__ */ jsxRuntimeExports.jsx(PasswordFullIcon, { className: css$h.img1 })
                        }
                      )
                    }
                  ),
                  /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$h.strength, children: [
                    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$h.thLabel, children: trans("密码强度") }),
                    /* @__PURE__ */ jsxRuntimeExports.jsx(
                      "div",
                      {
                        className: joinClass(
                          css$h.strengthItem,
                          curPsdLevel >= 1 ? curLevelClass : ""
                        )
                      }
                    ),
                    /* @__PURE__ */ jsxRuntimeExports.jsx(
                      "div",
                      {
                        className: joinClass(
                          css$h.strengthItem,
                          curPsdLevel >= 2 ? curLevelClass : ""
                        )
                      }
                    ),
                    /* @__PURE__ */ jsxRuntimeExports.jsx(
                      "div",
                      {
                        className: joinClass(
                          css$h.strengthItem,
                          curPsdLevel >= 3 ? curLevelClass : ""
                        )
                      }
                    ),
                    /* @__PURE__ */ jsxRuntimeExports.jsx(
                      "div",
                      {
                        className: joinClass(
                          css$h.strengthItem,
                          curPsdLevel >= 4 ? curLevelClass : ""
                        )
                      }
                    )
                  ] })
                ]
              }
            )
          }
        ),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: comCss.btnsBox, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(comCss.btnItem1), onClick: () => {
            setOpenForEditInfoByThree(false);
            setOpenForInfoPreviewByThree(true);
          }, children: /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: trans("Cancelar modificação") }) }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: comCss.btnItem2, onClick: () => {
            form.submit();
          }, children: /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: comCss.conSpan, children: trans("Confirm modification") }) })
        ] })
      ] })
    }
  ) });
};
const svg_theme_fill_color$g = "_svg_theme_fill_color_1xdus_3";
const drakFont$1 = "_drakFont_1xdus_55";
const sliderBox = "_sliderBox_1xdus_55";
const obBox = "_obBox_1xdus_55";
const obTitle = "_obTitle_1xdus_55";
const containerBox = "_containerBox_1xdus_55";
const toolsBox = "_toolsBox_1xdus_55";
const toolItemBox = "_toolItemBox_1xdus_55";
const gameTabsBoxs = "_gameTabsBoxs_1xdus_55";
const gameTabsTitle = "_gameTabsTitle_1xdus_55";
const lightBg$1 = "_lightBg_1xdus_59";
const boxShow = "_boxShow_1xdus_74";
const moveDown = "_moveDown_1xdus_78";
const gameTabsBox = "_gameTabsBox_1xdus_55";
const tabIcon = "_tabIcon_1xdus_126";
const music_records = "_music_records_1xdus_133";
const music_box$1 = "_music_box_1xdus_136";
const operation$1 = "_operation_1xdus_141";
const music_img = "_music_img_1xdus_161";
const pre_next = "_pre_next_1xdus_178";
const music_name$1 = "_music_name_1xdus_182";
const music_menu = "_music_menu_1xdus_192";
const star_num = "_star_num_1xdus_195";
const records = "_records_1xdus_212";
const w80 = "_w80_1xdus_239";
const select = "_select_1xdus_245";
const iconImgs = "_iconImgs_1xdus_277";
const gameTabItem = "_gameTabItem_1xdus_281";
const bgImg = "_bgImg_1xdus_348";
const iconImg = "_iconImg_1xdus_277";
const iconImgSelect = "_iconImgSelect_1xdus_386";
const iconImgSelectThot = "_iconImgSelectThot_1xdus_392";
const gameBg = "_gameBg_1xdus_436";
const infoBtnItem = "_infoBtnItem_1xdus_448";
const tips = "_tips_1xdus_464";
const agent = "_agent_1xdus_489";
const title$3 = "_title_1xdus_510";
const line = "_line_1xdus_523";
const tool_line_t = "_tool_line_t_1xdus_574";
const tool_left_i = "_tool_left_i_1xdus_591";
const active$3 = "_active_1xdus_607";
const empty = "_empty_1xdus_610";
const obLine = "_obLine_1xdus_622";
const obImg = "_obImg_1xdus_632";
const obText = "_obText_1xdus_636";
const css$g = {
  svg_theme_fill_color: svg_theme_fill_color$g,
  drakFont: drakFont$1,
  sliderBox,
  obBox,
  obTitle,
  containerBox,
  toolsBox,
  toolItemBox,
  gameTabsBoxs,
  gameTabsTitle,
  lightBg: lightBg$1,
  boxShow,
  moveDown,
  gameTabsBox,
  tabIcon,
  music_records,
  music_box: music_box$1,
  operation: operation$1,
  music_img,
  pre_next,
  music_name: music_name$1,
  music_menu,
  star_num,
  records,
  w80,
  select,
  iconImgs,
  gameTabItem,
  bgImg,
  iconImg,
  iconImgSelect,
  iconImgSelectThot,
  gameBg,
  infoBtnItem,
  tips,
  agent,
  title: title$3,
  line,
  tool_line_t,
  tool_left_i,
  active: active$3,
  empty,
  obLine,
  obImg,
  obText
};
const svg_theme_fill_color$f = "_svg_theme_fill_color_2bnqx_3";
const lineBox = "_lineBox_2bnqx_55";
const lineItem = "_lineItem_2bnqx_68";
const active$2 = "_active_2bnqx_78";
const icon$2 = "_icon_2bnqx_87";
const lineChIcon = "_lineChIcon_2bnqx_92";
const css$f = {
  svg_theme_fill_color: svg_theme_fill_color$f,
  lineBox,
  lineItem,
  active: active$2,
  icon: icon$2,
  lineChIcon
};
const LineDetection = ({ isOpen, offsetTop }) => {
  const { t, i18n } = useTranslation();
  const { theme, isShowSliderBox } = useUserInfoStore();
  const [lineArr, setLineArr] = reactExports.useState([]);
  const [curIndex, setCurIndex] = reactExports.useState(0);
  reactExports.useEffect(() => {
    initLine();
  }, []);
  useClickAway(() => {
    useUserInfoStore.setState({ openForLine: false });
  }, []);
  const initLine = () => {
    var _a;
    const curArr = [
      { url: "https://www.google.com/", time: getRandomInt(30, 200) }
      // {url: 'https://www.baidu.com/', time: getRandomInt(30, 200)},
      // {url: 'https://www.baidu.com/', time: getRandomInt(30, 200)},
      // {url: 'https://www.baidu.com/', time: getRandomInt(30, 200)},
    ];
    curArr.sort((a, b) => a.time - b.time);
    setLineArr(curArr);
    const curTime = (_a = curArr[curIndex]) == null ? void 0 : _a.time;
    useUserInfoStore.setState({ curLineTime: curTime });
  };
  const clickLine = (item2, index) => {
    setCurIndex(index);
    useUserInfoStore.setState({ openForLine: false });
  };
  if (!isOpen || !isShowSliderBox) return null;
  return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$f.lineBox, style: { bottom: "".concat(offsetTop, "px") }, children: lineArr.map((item2, index) => {
    return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css$f.lineItem, css$f[getLineClass(item2.time)], index === curIndex ? css$f.active : ""), onClick: () => {
      clickLine(item2, index);
    }, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$f.lineItemR, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "".concat(css$f.icon, " ").concat(lineCss[getLineClass(item2.time)]), children: /* @__PURE__ */ jsxRuntimeExports.jsx(LinhaIcon, { className: lineCss.line }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { children: [
          " ",
          t("线路"),
          " ",
          index + 1,
          " "
        ] })
      ] }),
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$f.lineItemL, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { children: [
          " ",
          item2.time,
          "MS "
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$f.lineChIcon, children: index === curIndex ? /* @__PURE__ */ jsxRuntimeExports.jsx(LinhaSelectIcon, {}) : "" })
      ] })
    ] }, index);
  }) });
};
const svg_theme_fill_color$e = "_svg_theme_fill_color_12383_3";
const drakFont = "_drakFont_12383_55";
const threeLoginBox = "_threeLoginBox_12383_55";
const title$2 = "_title_12383_55";
const lightBg = "_lightBg_12383_59";
const loginBtn$3 = "_loginBtn_12383_59";
const logo = "_logo_12383_102";
const css$e = {
  svg_theme_fill_color: svg_theme_fill_color$e,
  drakFont,
  threeLoginBox,
  title: title$2,
  lightBg,
  loginBtn: loginBtn$3,
  logo
};
const ThreeLoginBySliderBox = () => {
  const {
    google: [onClickGoogleLogin, isColseGoogleLogin],
    facebook: [onClickFaceBookLogin, isCloseFacebookLogin]
  } = ThreeLogin({ isHook: true });
  const { info } = useUserInfoStore();
  if (isColseGoogleLogin && isCloseFacebookLogin || info) return null;
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$e.threeLoginBox, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$e.title, children: trans("快速登录") }),
    !isColseGoogleLogin && /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$e.loginBtn, onClick: async () => {
      await onClickGoogleLogin();
    }, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { className: css$e.logo, src: "/home/loginreg/google_icon.webp" }),
      trans("谷歌登录")
    ] }),
    !isCloseFacebookLogin && /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$e.loginBtn, onClick: async () => {
      await onClickFaceBookLogin();
    }, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { className: css$e.logo, src: "/home/loginreg/facebook_icon.webp" }),
      trans("Facebook Login")
    ] })
  ] });
};
const SliderBox = () => {
  const location2 = useLocation();
  const state = location2.state || {};
  const { selectType, games, gameOpenMap, gameSortMap } = useGameStore();
  const navigate = useNavigate();
  const {
    isShowSliderBox,
    updateSliderBoxStatus,
    token,
    appUrl,
    theme,
    openForLine,
    curLineTime,
    missaoList,
    getMissaoList,
    websetConfig,
    availableList,
    getAvailableList,
    getMissaoRule,
    fetchRebateConfig,
    missaoSwitch,
    entrance,
    rebateStatus,
    getLanguage
  } = useUserInfoStore();
  const [lineTop, setLineTop] = reactExports.useState(0);
  const toolBoxRef = reactExports.useRef();
  const {
    next,
    pre,
    paused,
    pause,
    play,
    current,
    model: model2,
    setModel,
    collectList,
    player_switch
  } = useMusicStore();
  const { websetConfig: websetConfigByHook } = useWebsetConfig();
  const { standalone } = useGuideStore();
  const [threeLoginList, setThreeLoginList] = reactExports.useState([]);
  const [threeLoginShow, setThreeLoginShow] = reactExports.useState(false);
  const [isShowLanguage, setIsShowLanguage] = reactExports.useState(false);
  const { list: activityList, handleClick } = useNavigateToActivity();
  const missaoNum = missaoList.filter((el) => el.state === 502).length;
  const availableNum = availableList.d.length;
  GameTabs.sort((a, b) => {
    return (a.sort || gameSortMap[a.type] || 0) - (b.sort || gameSortMap[b.type] || 0);
  });
  reactExports.useEffect(() => {
    if (websetConfig) {
      GameTabs.map((item2) => {
        if (["player"].includes(item2 == null ? void 0 : item2.switchType)) {
          item2.switch = websetConfig["".concat(item2 == null ? void 0 : item2.switchType, "_switch")] === "1";
        } else {
          item2.switch = true;
        }
      });
      ToolTabs.map((item2) => {
        if (["netsignal", "lang"].includes(item2 == null ? void 0 : item2.switchType)) {
          item2.switch = websetConfig["".concat(item2 == null ? void 0 : item2.switchType, "_switch")] === "1";
        } else {
          item2.switch = true;
        }
      });
      const arr = websetConfig["official_channels"] && JSON.parse(websetConfig["official_channels"]) || [];
      setThreeLoginShow(!!arr.length);
      setThreeLoginList(arr.sort((a, b) => a.sort - b.sort));
    }
  }, [websetConfig]);
  reactExports.useEffect(() => {
    fetchRebateConfig();
    if (websetConfig && ["2", "1,2"].includes(entrance + "")) {
      getMissaoList();
    }
  }, [websetConfig, token, missaoSwitch, entrance]);
  reactExports.useEffect(() => {
    if (token) {
      getAvailableList({ state: "502" });
    } else {
      useUserInfoStore.setState({
        availableList: {
          d: [],
          agg: "0"
        }
      });
    }
  }, [token]);
  reactExports.useEffect(() => {
    getMissaoRule();
  }, []);
  return /* @__PURE__ */ jsxRuntimeExports.jsxs(
    "div",
    {
      className: joinClass(
        css$g.sliderBox,
        !websetConfigByHook.isHiddenDownloadApp && !standalone ? css$g.moveDown : "",
        isShowSliderBox ? css$g.boxShow : ""
      ),
      onClick: (e) => {
        e.stopPropagation();
        updateSliderBoxStatus(false);
      },
      children: [
        /* @__PURE__ */ jsxRuntimeExports.jsxs(
          "div",
          {
            onScroll: () => {
              useUserInfoStore.setState({ openForLine: false });
              setIsShowLanguage(false);
            },
            className: joinClass(
              css$g.containerBox,
              isShowSliderBox ? css$g.boxShow : ""
            ),
            onClick: (e) => {
              e.stopPropagation();
            },
            children: [
              /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$g.gameTabsBox, children: sortGameTabs(GameTabs, games).map((tab, idx) => {
                if (!tab.id) {
                  if (!gameOpenMap[tab.type] && gameTypeNames.indexOf(tab.type) > -1)
                    return null;
                  if (!games.find((item2) => item2.name === tab.type) && tab.adminConfigShow === true)
                    return null;
                }
                if (tab.type === "试玩" && token) {
                  return;
                }
                let isSelect = false;
                if (state.type) {
                  isSelect = tab.type === state.type;
                } else {
                  isSelect = selectType === tab.type;
                }
                const IconsCom = tab.IconsCom || "";
                const ActiveIcons = tab.ActiveIcon || "";
                if (!tab.switch && !tab.id) {
                  return;
                }
                if (tab.type === "music") {
                  return player_switch ? /* @__PURE__ */ jsxRuntimeExports.jsx(
                    "div",
                    {
                      className: "".concat(css$g.records, " ").concat(css$g.music_records),
                      children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$g.music_box, children: [
                        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$g.operation, children: [
                          /* @__PURE__ */ jsxRuntimeExports.jsx(
                            "div",
                            {
                              className: "".concat(css$g.music_img, " ").concat(css$g.pre_next),
                              onClick: pre,
                              children: /* @__PURE__ */ jsxRuntimeExports.jsx(AudioPreIcon, {})
                            }
                          ),
                          /* @__PURE__ */ jsxRuntimeExports.jsx(
                            "div",
                            {
                              className: css$g.music_img,
                              onClick: () => paused ? play() : pause(),
                              children: paused ? /* @__PURE__ */ jsxRuntimeExports.jsx(AudioPlayerIcon, {}) : /* @__PURE__ */ jsxRuntimeExports.jsx(AudioPausedIcon, {})
                            }
                          ),
                          /* @__PURE__ */ jsxRuntimeExports.jsx(
                            "div",
                            {
                              className: "".concat(css$g.music_img, " ").concat(css$g.pre_next),
                              onClick: next,
                              children: /* @__PURE__ */ jsxRuntimeExports.jsx(AudioNextIcon, {})
                            }
                          ),
                          /* @__PURE__ */ jsxRuntimeExports.jsx(
                            "div",
                            {
                              className: css$g.music_img,
                              onClick: () => {
                                setModel();
                              },
                              children: model2 === 1 ? /* @__PURE__ */ jsxRuntimeExports.jsx(AudioLoopIcon, {}) : model2 === 2 ? /* @__PURE__ */ jsxRuntimeExports.jsx(AudioRandomIcon, {}) : /* @__PURE__ */ jsxRuntimeExports.jsx(AudioOneceIcon, {})
                            }
                          ),
                          /* @__PURE__ */ jsxRuntimeExports.jsxs(
                            "div",
                            {
                              className: "".concat(css$g.music_img, " ").concat(css$g.music_menu),
                              onClick: () => useMusicStore.setState({ isOpen: true }),
                              children: [
                                /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$g.star_num, children: collectList.length }),
                                /* @__PURE__ */ jsxRuntimeExports.jsx(AudioMenuIcon, {})
                              ]
                            }
                          )
                        ] }),
                        /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$g.music_name, children: (current == null ? void 0 : current.music_name) || instance.t("没有音乐") })
                      ] })
                    },
                    "music_records"
                  ) : null;
                }
                return /* @__PURE__ */ jsxRuntimeExports.jsxs(
                  "div",
                  {
                    className: joinClass(
                      ["records", "agent"].includes(tab.action) ? css$g.records : css$g.gameTabItem,
                      isSelect ? css$g.select : ""
                    ),
                    style: {
                      paddingTop: tab.type === "真人" ? "0" : ""
                    },
                    onClick: () => {
                      if (tab.id) {
                        if (tab.open_by === 1) {
                          window.open(tab.url, "_blank");
                          return;
                        }
                        if (tab.open_by === 2) {
                          const curObj = activityList.find((a) => a.id === tab.url);
                          if (curObj) {
                            handleClick(curObj);
                          }
                        }
                        return;
                      }
                      if (tab.action === "home") {
                        navigate("/");
                        useGameStore.setState({ selectType: tab.type });
                        setTimeout(() => {
                          scrollToPlatromItem(GameTabs, tab.type);
                          window.clickTabing = true;
                          setTimeout(() => {
                            window.clickTabing = false;
                          }, 100);
                        }, 30);
                      }
                      if (tab.url) {
                        navigate(tab.url);
                      }
                      if (tab.type == "试玩") {
                        let gameId = games.find((item2) => item2.name === "试玩").id;
                        navigate("/demo-game?id=".concat(gameId, "&pid=").concat("0"));
                      }
                      updateSliderBoxStatus(false);
                    },
                    children: [
                      isSelect && !!ActiveIcons ? /* @__PURE__ */ jsxRuntimeExports.jsx(ActiveIcons, { className: joinClass(css$g.iconImg) }) : !!IconsCom ? /* @__PURE__ */ jsxRuntimeExports.jsx(
                        IconsCom,
                        {
                          className: joinClass(css$g.iconImgs, css$g.bgImg),
                          isSelect: true
                        }
                      ) : /* @__PURE__ */ jsxRuntimeExports.jsx(
                        "div",
                        {
                          className: css$g.tabIcon,
                          style: { backgroundImage: "url(".concat("" + tab.icon, ")") }
                        }
                      ),
                      /* @__PURE__ */ jsxRuntimeExports.jsx(
                        "span",
                        {
                          className: joinClass(
                            ["真人", "投注记录", "代理"].includes(tab.type) ? css$g.w80 : ""
                          ),
                          children: instance.t(tab.text) || tab.name
                        }
                      )
                    ]
                  },
                  idx
                );
              }) }),
              /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$g.gameTabsBoxs, children: [
                /* @__PURE__ */ jsxRuntimeExports.jsx("p", { className: css$g.gameTabsTitle, children: trans("优惠") }),
                /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$g.gameBg, children: InfoTabs.map((btnItem, idx) => {
                  if (btnItem.type === "missao" && !missaoList.length) {
                    return null;
                  }
                  if (btnItem.type === "rebate" && !rebateStatus) {
                    return null;
                  }
                  return /* @__PURE__ */ jsxRuntimeExports.jsxs(
                    "div",
                    {
                      className: joinClass(css$g.infoBtnItem, css$g[btnItem.cssName]),
                      onClick: () => {
                        if ((btnItem == null ? void 0 : btnItem.type) === "lxb") {
                          Message.info(instance.t("功能维护中"));
                        } else {
                          if (!token) {
                            const meta = routesMaps[btnItem.url] || {};
                            if (meta.auth) {
                              useUserInfoStore.setState({ openForLogin: true });
                            } else {
                              navigate(btnItem.url);
                            }
                          } else {
                            navigate(btnItem.url);
                          }
                        }
                        updateSliderBoxStatus(false);
                      },
                      children: [
                        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$g.title, css$g[btnItem.cssName]), children: instance.t(btnItem.text) }),
                        /* @__PURE__ */ jsxRuntimeExports.jsx(
                          Image,
                          {
                            className: css$g.iconImg,
                            src: "/home/icons/new_".concat(btnItem.icon)
                          }
                        ),
                        btnItem.type === "missao" && missaoNum > 0 && /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$g.tips, children: missaoNum }),
                        btnItem.type === "bonus" && availableNum > 0 && /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$g.tips, children: availableNum })
                      ]
                    },
                    idx
                  );
                }) })
              ] }),
              /* @__PURE__ */ jsxRuntimeExports.jsx("div", { ref: toolBoxRef, className: css$g.toolsBox, children: ToolTabs.map((tool, idx) => {
                var _a;
                if (tool.isHiden) return;
                if (!tool.switch) {
                  return;
                }
                const IconsCom = tool.IconsCom || "";
                return /* @__PURE__ */ jsxRuntimeExports.jsxs(
                  "div",
                  {
                    id: tool.id,
                    className: joinClass(
                      css$g.toolItemBox,
                      css$g[getLineClass(curLineTime)]
                    ),
                    onClick: (e) => {
                      if (!["line", "language"].includes(tool.path)) {
                        updateSliderBoxStatus(false);
                      }
                      if (tool.text === "Suporte") {
                        navigate("/message?service=1");
                      }
                      if (tool.path == "service") {
                        navigate("/message?service=1");
                      }
                      if (tool.path === "download") {
                        window.open(appUrl, "_blank");
                      }
                      if (tool.path === "line") {
                        const curtop = window.innerHeight - e.target.getBoundingClientRect().bottom - 2;
                        setLineTop(curtop);
                        useUserInfoStore.setState({ openForLine: !openForLine });
                        setIsShowLanguage(false);
                      }
                      if (tool.path === "language") {
                        setIsShowLanguage(!isShowLanguage);
                        useUserInfoStore.setState({ openForLine: false });
                      }
                    },
                    children: [
                      tool.path === "line" ? /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: lineCss[getLineClass(curLineTime)], children: [
                        /* @__PURE__ */ jsxRuntimeExports.jsx(
                          IconsCom,
                          {
                            className: joinClass(css$g.iconImg, lineCss.line),
                            isSelect: true
                          }
                        ),
                        /* @__PURE__ */ jsxRuntimeExports.jsxs(
                          "div",
                          {
                            className: "".concat(css$g.tool_line_t, " ").concat(lineCss.tool_line_t),
                            children: [
                              curLineTime,
                              " MS"
                            ]
                          }
                        )
                      ] }) : IconsCom ? /* @__PURE__ */ jsxRuntimeExports.jsx(
                        IconsCom,
                        {
                          className: joinClass(css$g.iconImg),
                          isSelect: true
                        }
                      ) : /* @__PURE__ */ jsxRuntimeExports.jsx(
                        Image,
                        {
                          className: css$g.iconImg,
                          src: "/home/icons/".concat(tool[theme] || tool.icon)
                        }
                      ),
                      /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: instance.t(tool.text) }),
                      tool.path === "line" ? /* @__PURE__ */ jsxRuntimeExports.jsx(
                        "span",
                        {
                          className: joinClass(
                            css$g.tool_left_i,
                            openForLine ? css$g.active : ""
                          ),
                          children: /* @__PURE__ */ jsxRuntimeExports.jsx(tool.rightIcon, {})
                        }
                      ) : "",
                      tool.path === "language" && /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
                        /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: (_a = getLanguage()) == null ? void 0 : _a.label }),
                        /* @__PURE__ */ jsxRuntimeExports.jsx(
                          "span",
                          {
                            className: joinClass(
                              css$g.tool_left_i,
                              isShowLanguage ? css$g.active : ""
                            ),
                            children: /* @__PURE__ */ jsxRuntimeExports.jsx(tool.rightIcon, {})
                          }
                        )
                      ] })
                    ]
                  },
                  idx
                );
              }) }),
              /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$g.obBox, children: [
                threeLoginShow && /* @__PURE__ */ jsxRuntimeExports.jsx("p", { className: css$g.obTitle, children: trans("官方频道") }),
                threeLoginList.map((item2, idx) => {
                  return /* @__PURE__ */ jsxRuntimeExports.jsxs(
                    "div",
                    {
                      className: css$g.obLine,
                      onClick: () => {
                        desktopOpen(item2.link);
                      },
                      children: [
                        /* @__PURE__ */ jsxRuntimeExports.jsx(
                          Image,
                          {
                            className: css$g.obImg,
                            src: item2.icon,
                            remote: true,
                            isGame: true
                          }
                        ),
                        /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$g.obText, children: item2.name })
                      ]
                    },
                    idx + item2.id
                  );
                })
              ] }),
              /* @__PURE__ */ jsxRuntimeExports.jsx(ThreeLoginBySliderBox, {}),
              /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$g.empty })
            ]
          }
        ),
        /* @__PURE__ */ jsxRuntimeExports.jsx(LineDetection, { isOpen: openForLine, offsetTop: lineTop }),
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          Language,
          {
            isOpen: isShowLanguage && isShowSliderBox,
            hideModel: () => {
              setIsShowLanguage(false);
            },
            elementId: "languageRef",
            position: "right"
          }
        )
      ]
    }
  );
};
const svg_theme_fill_color$d = "_svg_theme_fill_color_1crrh_3";
const loginBox$3 = "_loginBox_1crrh_55";
const clsoeBtn$4 = "_clsoeBtn_1crrh_175";
const titleBox = "_titleBox_1crrh_195";
const contentBox$2 = "_contentBox_1crrh_275";
const scrollBox = "_scrollBox_1crrh_391";
const loginBtn$2 = "_loginBtn_1crrh_544";
const css$d = {
  svg_theme_fill_color: svg_theme_fill_color$d,
  loginBox: loginBox$3,
  clsoeBtn: clsoeBtn$4,
  titleBox,
  contentBox: contentBox$2,
  scrollBox,
  loginBtn: loginBtn$2
};
const Login = () => {
  const { openForXieYi, theme } = useUserInfoStore();
  if (!openForXieYi) return null;
  return /* @__PURE__ */ jsxRuntimeExports.jsx(
    Modal,
    {
      isOpen: openForXieYi,
      closeByClickOut: false,
      onClose: (e) => {
        e && e.stopPropagation();
        useUserInfoStore.setState({ openForXieYi: false });
      },
      children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$d.loginBox, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$d.clsoeBtn, onClick: () => {
          useUserInfoStore.setState({ openForXieYi: false });
        }, children: /* @__PURE__ */ jsxRuntimeExports.jsx(CloseIconInLogin, {}) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$d.titleBox, children: trans("用户协议") }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$d.contentBox, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$d.scrollBox, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsxs("p", { children: [
            "1. ",
            trans("条款1")
          ] }),
          /* @__PURE__ */ jsxRuntimeExports.jsxs("p", { children: [
            "2. ",
            trans("条款2")
          ] }),
          /* @__PURE__ */ jsxRuntimeExports.jsxs("p", { children: [
            "3. ",
            trans("条款3")
          ] }),
          /* @__PURE__ */ jsxRuntimeExports.jsxs("p", { children: [
            "4. ",
            trans("条款4")
          ] }),
          /* @__PURE__ */ jsxRuntimeExports.jsxs("p", { children: [
            "5. ",
            trans("条款5")
          ] })
        ] }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$d.loginBtn, onClick: () => {
          useUserInfoStore.setState({ openForXieYi: false });
        }, children: trans("阅读并理解") })
      ] })
    }
  );
};
const svg_theme_fill_color$c = "_svg_theme_fill_color_13ti1_3";
const loginBox$2 = "_loginBox_13ti1_55";
const clsoeBtn$3 = "_clsoeBtn_13ti1_187";
const contentBox$1 = "_contentBox_13ti1_207";
const fav$2 = "_fav_13ti1_263";
const content$2 = "_content_13ti1_207";
const loginBtn$1 = "_loginBtn_13ti1_280";
const icon$1 = "_icon_13ti1_298";
const css$c = {
  svg_theme_fill_color: svg_theme_fill_color$c,
  loginBox: loginBox$2,
  clsoeBtn: clsoeBtn$3,
  contentBox: contentBox$1,
  fav: fav$2,
  content: content$2,
  loginBtn: loginBtn$1,
  icon: icon$1
};
const Download = () => {
  const domain = window.location.hostname;
  getWebType();
  const { openDownload, websetConfig, version, appUrl } = useUserInfoStore();
  if (!openDownload) return null;
  return /* @__PURE__ */ jsxRuntimeExports.jsx(
    Modal,
    {
      isOpen: openDownload,
      closeByClickOut: false,
      onClose: (e) => {
        e && e.stopPropagation();
        useUserInfoStore.setState({ openDownload: false });
      },
      children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$c.loginBox, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$c.clsoeBtn, onClick: () => {
          useUserInfoStore.setState({ openDownload: false });
        }, children: /* @__PURE__ */ jsxRuntimeExports.jsx(CloseIconInLogin, {}) }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$c.contentBox, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
            "  ",
            /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { className: css$c.fav, src: "/uploads/favicon.png?v=".concat("2024_12_14_18_4") })
          ] }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$c.content, children: websetConfig.banner_text })
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$c.loginBtn, onClick: () => {
          window.open(appUrl, "_blank");
          useUserInfoStore.setState({ openDownload: false });
        }, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
            getBrowser().isIos ? /* @__PURE__ */ jsxRuntimeExports.jsx(IosIcon, { className: css$c.icon }) : /* @__PURE__ */ jsxRuntimeExports.jsx(AndroidIcon, { className: css$c.icon }),
            "  "
          ] }),
          /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
            /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { children: [
              " V",
              version
            ] }),
            /* @__PURE__ */ jsxRuntimeExports.jsx("span", { style: { marginLeft: "5rem" }, children: domain })
          ] })
        ] })
      ] })
    }
  );
};
const svg_theme_fill_color$b = "_svg_theme_fill_color_2ybrw_3";
const loginBox$1 = "_loginBox_2ybrw_55";
const clsoeBtn$2 = "_clsoeBtn_2ybrw_64";
const midImg = "_midImg_2ybrw_79";
const css$b = {
  svg_theme_fill_color: svg_theme_fill_color$b,
  loginBox: loginBox$1,
  clsoeBtn: clsoeBtn$2,
  midImg
};
const AlertForOneSixSix = () => {
  const { alertFor166 } = useAlertStore();
  const fun = () => {
    if (window.jsBridge && window.jsBridge.postMessage) {
      try {
        window.jsBridge.postMessage("openWindow", JSON.stringify({ "url": hostname }));
      } catch (e) {
        console.log(e);
      }
    }
  };
  return /* @__PURE__ */ jsxRuntimeExports.jsx(
    Modal,
    {
      isOpen: alertFor166,
      closeByClickOut: true,
      onClose: (e) => {
        e && e.stopPropagation();
        useAlertStore.setState({ alertFor166: false });
        fun();
      },
      children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$b.loginBox, onClick: () => {
        fun();
      }, children: /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { className: css$b.midImg, src: "/home/166/act.webp" }) })
    }
  );
};
const svg_theme_fill_color$a = "_svg_theme_fill_color_s8r5o_3";
const floatInHome = "_floatInHome_s8r5o_55";
const css$a = {
  svg_theme_fill_color: svg_theme_fill_color$a,
  floatInHome
};
const svg_theme_fill_color$9 = "_svg_theme_fill_color_3ammk_3";
const floatBox = "_floatBox_3ammk_55";
const leftArrowBox = "_leftArrowBox_3ammk_60";
const rightArrowBox = "_rightArrowBox_3ammk_60";
const inBox = "_inBox_3ammk_93";
const itemBox = "_itemBox_3ammk_99";
const img$3 = "_img_3ammk_105";
const close$2 = "_close_3ammk_112";
const css$9 = {
  svg_theme_fill_color: svg_theme_fill_color$9,
  floatBox,
  leftArrowBox,
  rightArrowBox,
  inBox,
  itemBox,
  img: img$3,
  close: close$2
};
const cacheState$2 = {
  timer: null
};
const FloatBox = ({ onClickToLink, style }) => {
  const closeIcon = {
    black: "/home/icons/reg_close.webp",
    blue: "/home/icons/reg_close.webp",
    whiteGreen: "/home/icons/whiteGreen_reg_close.webp",
    whiteRed: "/home/icons/whiteRed_reg_close.webp",
    whiteYellow: "/home/icons/whiteGreen_reg_close.webp",
    whitePink: "/home/icons/whiteGreen_reg_close.webp",
    whiteBlue: "/home/icons/whiteBlue_reg_close.webp",
    whitePurple: "/home/icons/whiteGreen_reg_close.webp",
    whiteBrown: "/home/icons/whiteGreen_reg_close.webp",
    whiteDarkGreen: "/home/icons/whiteRed_reg_close.webp"
  };
  const { theme, token } = useUserInfoStore();
  const location2 = useLocation();
  const { activityStatus } = useGameStore();
  const state = useReactive({
    list: [],
    index: 0,
    isShow: true
  });
  const { float } = useFloatPopShareStore();
  reactExports.useEffect(() => {
    if (float.length > 0) {
      const list2 = [];
      float.forEach((item2) => {
        if (item2.state === 0) return;
        item2.portal = item2.portal || [];
        if (item2.portal.indexOf("h5") === -1) return;
        if (item2.link === "拼多多" && token && (activityStatus == null ? void 0 : activityStatus.turntable) !== 2) return;
        list2.push(item2);
      });
      list2.sort((a, b) => a.sort - b.sort);
      state.list = list2;
    }
  }, [float, activityStatus]);
  const clearTimer = reactExports.useCallback(() => {
    clearInterval(cacheState$2.timer);
  }, []);
  const startTimer = reactExports.useCallback(() => {
    if (state.list.length > 0) {
      cacheState$2.timer = setInterval(() => {
        if (state.index === state.list.length - 1) {
          state.index = 0;
        } else {
          state.index = state.index + 1;
        }
      }, 3.5 * 1e3);
    }
  }, []);
  reactExports.useEffect(() => {
    clearTimer();
    startTimer();
    return () => {
      clearTimer();
    };
  }, [state.list]);
  const { list } = state;
  const px = minPxChip() * (style || 100.1) * state.index * -1;
  if (state.list.length === 0) return;
  if (!state.isShow) return;
  if (location2.pathname !== "/") return;
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$9.floatBox, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$9.leftArrowBox, onClick: () => {
      clearTimer();
      if (state.index === 0) {
        state.index = state.list.length - 1;
        startTimer();
        return;
      }
      state.index = state.index - 1;
      startTimer();
    }, children: /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { src: "/home/icons/floatArrowLeft.webp" }) }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { style: { transform: "translateX(".concat(px, "px)") }, className: css$9.inBox, children: list.map((item2, idx) => {
      return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$9.itemBox, style: { width: style + "rem", height: style + "rem" }, onClick: () => {
        if (!item2.link) return;
        if (item2.link.indexOf("http") > -1) {
          desktopOpen(item2.link);
          return;
        }
        onClickToLink(item2.link);
      }, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          "div",
          {
            className: css$9.close,
            onClick: (e) => {
              e.stopPropagation();
              const list2 = JSON.parse(JSON.stringify(state.list));
              list2.splice(idx, 1);
              state.index = 0;
              state.list = list2;
            },
            children: /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { src: closeIcon[theme] || "/home/icons/reg_close.webp" })
          }
        ),
        /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { remote: true, isGame: true, className: css$9.img, src: item2.img })
      ] }, idx);
    }) }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$9.rightArrowBox, onClick: () => {
      clearTimer();
      if (state.index === state.list.length - 1) {
        state.index = 0;
        startTimer();
        return;
      }
      state.index = state.index + 1;
      startTimer();
    }, children: /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { src: "/home/icons/floatArrowLeft.webp" }) })
  ] });
};
const svg_theme_fill_color$8 = "_svg_theme_fill_color_1k100_3";
const HandoutBox = "_HandoutBox_1k100_55";
const context$1 = "_context_1k100_62";
const head = "_head_1k100_70";
const table = "_table_1k100_75";
const tableHead = "_tableHead_1k100_80";
const tr = "_tr_1k100_89";
const foot = "_foot_1k100_100";
const submitButton = "_submitButton_1k100_106";
const left$2 = "_left_1k100_118";
const checkBox = "_checkBox_1k100_118";
const text1 = "_text1_1k100_122";
const icon2 = "_icon2_1k100_129";
const padd = "_padd_1k100_138";
const close$1 = "_close_1k100_141";
const img$2 = "_img_1k100_154";
const css$8 = {
  svg_theme_fill_color: svg_theme_fill_color$8,
  HandoutBox,
  context: context$1,
  head,
  table,
  tableHead,
  tr,
  foot,
  submitButton,
  left: left$2,
  checkBox,
  text1,
  icon2,
  padd,
  close: close$1,
  img: img$2
};
const HandoutDialog = ({ onClose = () => {
} }) => {
  const { token, theme } = useUserInfoStore();
  const { activityList } = useGameStore();
  const navigate = useNavigate();
  const [state, setState] = reactExports.useState(false);
  const [checked, setChecked] = reactExports.useState(false);
  const [checked2, setChecked2] = reactExports.useState(false);
  const { t, i18n } = useTranslation();
  const [dataList, setDataList] = reactExports.useState([]);
  const [dataBonusType, setDataBonusType] = reactExports.useState("");
  const [handoutData, setHandoutData] = reactExports.useState({});
  const [title2, setTitle] = reactExports.useState("");
  reactExports.useEffect(() => {
    const today = localStorage.getItem(
      "firstChargeAllUserCheckTodayReminderMapper"
    );
    const permanent = localStorage.getItem(
      "firstChargeAllUserCheckNeverReminderMapper"
    );
    const dayType = localStorage.getItem(
      "allUserOpeningFirstChargeConstantRecord"
    );
    let dataDay = {};
    if (!token) {
      if (!today) {
        let data = {
          today: false,
          permanent: false
        };
        localStorage.setItem(
          "firstChargeAllUserCheckTodayReminderMapper",
          JSON.stringify(data)
        );
      }
      if (dayType) {
        let day = JSON.parse(dayType);
        if (day.unLogin != dayjs.tz().endOf("day").format("DD")) {
          dataDay = {
            unLogin: dayjs.tz().endOf("day").format("DD")
          };
          let data = today ? JSON.parse(today) : {};
          data.today = false;
          localStorage.setItem(
            "firstChargeAllUserCheckTodayReminderMapper",
            JSON.stringify(data)
          );
          localStorage.setItem(
            "allUserOpeningFirstChargeConstantRecord",
            JSON.stringify(dataDay)
          );
        }
      } else {
        dataDay = {
          unLogin: dayjs.tz().endOf("day").format("DD")
        };
        localStorage.setItem(
          "allUserOpeningFirstChargeConstantRecord",
          JSON.stringify(dataDay)
        );
      }
      dataDay = {
        unLogin: dayjs.tz().endOf("day").format("DD")
      };
      if (today) {
        const todays = localStorage.getItem(
          "firstChargeAllUserCheckTodayReminderMapper"
        );
        const data = JSON.parse(todays);
        if (data.today || data.permanent) {
          onClose();
          return;
        }
      }
    } else {
      if (permanent) {
        let data = {
          today: false,
          permanent: false
        };
        localStorage.setItem(
          "firstChargeAllUserCheckTodayReminderMapper",
          JSON.stringify(data)
        );
      }
      localStorage.setItem(
        "allUserOpeningFirstChargeConstantRecord",
        JSON.stringify(dataDay)
      );
      if (dayType) {
        let day = JSON.parse(dayType);
        if (day.login != dayjs.tz().endOf("day").format("DD")) {
          let dataDay2 = {
            unLogin: day.unLogin,
            login: dayjs.tz().endOf("day").format("DD")
          };
          let data = permanent ? JSON.parse(permanent) : {};
          data.permanent = false;
          localStorage.setItem(
            "firstChargeAllUserCheckNeverReminderMapper",
            JSON.stringify(data)
          );
          localStorage.setItem(
            "allUserOpeningFirstChargeConstantRecord",
            JSON.stringify(dataDay2)
          );
        }
      } else {
        let dataDay2 = {
          unLogin: dayjs.tz().endOf("day").format("DD"),
          login: dayjs.tz().endOf("day").format("DD")
        };
        localStorage.setItem(
          "allUserOpeningFirstChargeConstantRecord",
          JSON.stringify(dataDay2)
        );
      }
      if (permanent) {
        const permanents = localStorage.getItem(
          "firstChargeAllUserCheckNeverReminderMapper"
        );
        const data = JSON.parse(permanents);
        if (data.today || data.permanent) {
          onClose();
          return;
        }
      }
    }
    const handoutItem = activityList.find(
      (item2) => item2.flag === "single_topup"
    );
    setHandoutData(handoutItem);
    if (handoutItem) {
      getPromoDetail({ id: handoutItem.id, flag: handoutItem.flag }).then(
        ([res]) => {
          var _a, _b, _c;
          if (res) {
            setDataList(((_a = res.rules) == null ? void 0 : _a.bonus_list) || []);
            setTitle(((_b = res.config) == null ? void 0 : _b.title) || "");
            setDataBonusType(((_c = res.config) == null ? void 0 : _c.bonus_type) || "");
          }
        }
      );
      setState(true);
    } else {
      onClose();
    }
  }, []);
  const bntCheck = () => {
    if (token) {
      let data = {
        today: !checked,
        permanent: false
      };
      localStorage.setItem(
        "firstChargeAllUserCheckNeverReminderMapper",
        JSON.stringify(data)
      );
    } else {
      let data = {
        today: !checked,
        permanent: false
      };
      localStorage.setItem(
        "firstChargeAllUserCheckTodayReminderMapper",
        JSON.stringify(data)
      );
    }
    setChecked(!checked);
  };
  const bntCheck2 = () => {
    if (token) {
      let lacalData = localStorage.getItem(
        "firstChargeAllUserCheckNeverReminderMapper"
      );
      let data = {
        today: false,
        permanent: !checked2
      };
      if (lacalData) {
        data = JSON.parse(lacalData);
        data.permanent = !checked2;
      }
      localStorage.setItem(
        "firstChargeAllUserCheckNeverReminderMapper",
        JSON.stringify(data)
      );
    } else {
      let lacalData = localStorage.getItem(
        "firstChargeAllUserCheckTodayReminderMapper"
      );
      console.log(lacalData);
      let data = {
        today: false,
        permanent: !checked2
      };
      if (lacalData) {
        data = JSON.parse(lacalData);
        data.permanent = !checked2;
      }
      localStorage.setItem(
        "firstChargeAllUserCheckTodayReminderMapper",
        JSON.stringify(data)
      );
    }
    setChecked2(!checked2);
  };
  return /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, { children: dataList && dataList.length > 0 && /* @__PURE__ */ jsxRuntimeExports.jsx(Modal, { isOpen: state, closeByClickOut: false, zIndex: 99, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$8.HandoutBox, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$8.context, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$8.head, children: title2 }),
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$8.table, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$8.tableHead, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: t("充值金额") }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: t("奖励金额") })
        ] }),
        dataList.map((item2, index) => {
          return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$8.tr, children: [
            /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
              "≥ ",
              item2.deposit_amount
            ] }),
            /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: dataBonusType == 1 ? item2.bonus_amount : item2.bonus_rate })
          ] }, index);
        })
      ] }),
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$8.foot, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$8.left, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css$8.checkBox, css$8.padd), children: [
            /* @__PURE__ */ jsxRuntimeExports.jsx(CheckBox, { checked, onChange: bntCheck }),
            /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$8.text1, children: t("今天我不再出现") })
          ] }),
          /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$8.checkBox, children: [
            /* @__PURE__ */ jsxRuntimeExports.jsx(CheckBox, { checked: checked2, onChange: bntCheck2 }),
            /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$8.text1, children: t("永久隐藏") })
          ] })
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: /* @__PURE__ */ jsxRuntimeExports.jsx(
          "button",
          {
            onClick: () => {
              navigate(
                "/activity/top-up-winnings/".concat(handoutData.id, "/").concat(handoutData.flag)
              );
            },
            className: joinClass(css$8.submitButton, "button"),
            children: t("立即前往")
          }
        ) })
      ] })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$8.close, onClick: () => {
      setState(false);
      onClose();
    }, children: /* @__PURE__ */ jsxRuntimeExports.jsxs(
      "svg",
      {
        xmlns: "http://www.w3.org/2000/svg",
        width: "60",
        height: "60",
        viewBox: "0 0 60 60",
        fill: "none",
        children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("g", { clipPath: "url(#clip0_1498_10067)", children: /* @__PURE__ */ jsxRuntimeExports.jsx(
            "path",
            {
              fillRule: "evenodd",
              clipRule: "evenodd",
              d: "M57 30C57 44.9117 44.9117 57 30 57C15.0883 57 3 44.9117 3 30C3 15.0883 15.0883 3 30 3C44.9117 3 57 15.0883 57 30ZM60 30C60 46.5685 46.5685 60 30 60C13.4315 60 0 46.5685 0 30C0 13.4315 13.4315 0 30 0C46.5685 0 60 13.4315 60 30ZM19.768 19.232C20.7443 18.2557 22.3272 18.2557 23.3035 19.232L30.0005 25.9289L36.9101 19.0193C37.8865 18.043 39.4694 18.043 40.4457 19.0193C41.422 19.9956 41.422 21.5785 40.4457 22.5548L33.536 29.4645L40.9812 36.9097C41.9575 37.886 41.9575 39.4689 40.9812 40.4452C40.0049 41.4215 38.422 41.4215 37.4457 40.4452L30.0005 33L22.768 40.2325C21.7917 41.2088 20.2088 41.2088 19.2325 40.2325C18.2562 39.2562 18.2562 37.6733 19.2325 36.6969L26.465 29.4645L19.768 22.7675C18.7917 21.7912 18.7917 20.2083 19.768 19.232Z",
              fill: "white"
            }
          ) }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("defs", { children: /* @__PURE__ */ jsxRuntimeExports.jsx("clipPath", { id: "clip0_1498_10067", children: /* @__PURE__ */ jsxRuntimeExports.jsx("rect", { width: "60", height: "60", rx: "8", fill: "white" }) }) })
        ]
      }
    ) })
  ] }) }) });
};
const svg_theme_fill_color$7 = "_svg_theme_fill_color_1rns5_3";
const item$1 = "_item_1rns5_55";
const active$1 = "_active_1rns5_71";
const title$1 = "_title_1rns5_79";
const content$1 = "_content_1rns5_87";
const alert_box = "_alert_box_1rns5_97";
const clsoe_box = "_clsoe_box_1rns5_100";
const clsoeBtn$1 = "_clsoeBtn_1rns5_105";
const horizontal = "_horizontal_1rns5_119";
const items = "_items_1rns5_127";
const left$1 = "_left_1rns5_163";
const right$1 = "_right_1rns5_167";
const context = "_context_1rns5_171";
const content_box = "_content_box_1rns5_177";
const vertical = "_vertical_1rns5_231";
const switch_left = "_switch_left_1rns5_334";
const switch_right = "_switch_right_1rns5_338";
const top = "_top_1rns5_342";
const bottom = "_bottom_1rns5_357";
const no_show_select = "_no_show_select_1rns5_369";
const select_box = "_select_box_1rns5_376";
const selece_icon = "_selece_icon_1rns5_384";
const tipsSpan = "_tipsSpan_1rns5_388";
const no_read = "_no_read_1rns5_401";
const css$7 = {
  svg_theme_fill_color: svg_theme_fill_color$7,
  item: item$1,
  active: active$1,
  title: title$1,
  content: content$1,
  alert_box,
  clsoe_box,
  clsoeBtn: clsoeBtn$1,
  horizontal,
  items,
  left: left$1,
  right: right$1,
  context,
  content_box,
  vertical,
  "switch": "_switch_1rns5_326",
  switch_left,
  switch_right,
  top,
  bottom,
  no_show_select,
  select_box,
  selece_icon,
  tipsSpan,
  no_read
};
const Msg = ({ className } = {}) => {
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("svg", { className, xmlns: "http://www.w3.org/2000/svg", width: "22", height: "22", viewBox: "0 0 22 22", fill: "none", children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx("path", { d: "M2 2C0.895431 2 0 2.89543 0 4V4.29894L11.1264 10.7472L22 4.29894V4C22 2.89543 21.1046 2 20 2H2Z", fill: "#A7B7D7" }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("path", { d: "M22 4.68974L13.9867 10.5515L22 16.7012L12.9315 11.3234L11.1264 12.6438L9.27943 11.3234L0 16.7012L8.19967 10.5515L0 4.68974V16.7012V18C0 19.1046 0.89543 20 2 20H20C21.1046 20 22 19.1046 22 18V16.7012V4.68974Z", fill: "#A7B7D7" })
  ] });
};
const Notice = ({ className } = {}) => {
  return /* @__PURE__ */ jsxRuntimeExports.jsx("svg", { className, xmlns: "http://www.w3.org/2000/svg", width: "26", height: "22", viewBox: "0 0 26 22", fill: "none", children: /* @__PURE__ */ jsxRuntimeExports.jsx("path", { fillRule: "evenodd", clipRule: "evenodd", d: "M0 6.99938V14.9994C0 15.5517 0.447715 15.9994 1 15.9994H6.5L12.3753 20.6996C13.0301 21.2234 14 20.7573 14 19.9188V2.08C14 1.2415 13.0301 0.775323 12.3753 1.29913L6.5 5.99938H1C0.447715 5.99938 0 6.44709 0 6.99938ZM20.5409 3.39798C20.8337 3.10509 21.3086 3.10509 21.6015 3.39798C25.7996 7.59612 25.7996 14.4026 21.6015 18.6008C21.3086 18.8937 20.8337 18.8937 20.5409 18.6008C20.248 18.3079 20.248 17.833 20.5409 17.5401C24.1532 13.9278 24.1532 8.07099 20.5409 4.45864C20.248 4.16575 20.248 3.69087 20.5409 3.39798ZM17.7124 6.22641C18.0053 5.93351 18.4802 5.93351 18.7731 6.22641C21.4091 8.86245 21.4091 13.1363 18.7731 15.7723C18.4802 16.0652 18.0053 16.0652 17.7124 15.7723C17.4195 15.4795 17.4195 15.0046 17.7124 14.7117C19.7627 12.6614 19.7627 9.33732 17.7124 7.28707C17.4195 6.99417 17.4195 6.5193 17.7124 6.22641Z", fill: "#A7B7D7" }) });
};
const Items$1 = ({ popList, state, setState, itemMode }) => {
  const containRef = reactExports.useRef();
  reactExports.useEffect(() => {
    if (containRef.current && containRef.current.childNodes[state.index]) {
      const clientHeight = containRef.current.clientHeight;
      const itemHeight = containRef.current.childNodes[state.index].clientHeight;
      const centerHeight = clientHeight / 2 - itemHeight / 2;
      if (itemHeight * state.index > centerHeight) {
        containRef.current.scrollTop = itemHeight * state.index - centerHeight;
      } else {
        containRef.current.scrollTop = 0;
      }
    }
  }, [state.index]);
  const [readedPop, setReadedPop] = reactExports.useState(Array.from(new Set(Cache$1.get("readed_pop") || [])));
  reactExports.useEffect(() => {
    if (popList.length) {
      const rp = [...readedPop, popList[state.index].id];
      Cache$1.set("readed_pop", rp);
      setReadedPop(rp);
    }
  }, [state.index, popList]);
  const checkIsRead = (id) => {
    return readedPop.includes(id);
  };
  return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { ref: containRef, className: "".concat(css$7.items, " ").concat(css$7[itemMode]), children: popList.map((pop, i) => /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "".concat(css$7.item, " ").concat(state.index == i && css$7.active, " ").concat(!checkIsRead(pop.id) && css$7.no_read), onClick: () => setState({ index: i }), children: [
    pop.pop_type == "pop" && /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx(Notice, {}),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: pop.name })
    ] }),
    pop.pop_type == "notice" && /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx(Msg, {}),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: pop.title })
    ] })
  ] }, i)) });
};
const Horizontal = ({ itemMode = "right", state, setState, popList, onClickToLink }) => {
  var _a, _b, _c, _d, _e;
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$7.horizontal, children: [
    itemMode == "left" && /* @__PURE__ */ jsxRuntimeExports.jsx(Items$1, { popList, itemMode, state, setState }),
    ((_a = popList[state.index]) == null ? void 0 : _a["pop_type"]) == "pop" && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { onClick: () => {
      var _a2;
      return onClickToLink((_a2 = popList[state.index]) == null ? void 0 : _a2.link);
    }, className: css$7.context, style: {
      backgroundImage: "url(".concat("").concat((_b = popList[state.index]) == null ? void 0 : _b.img, ")")
    } }),
    ((_c = popList[state.index]) == null ? void 0 : _c["pop_type"]) == "notice" && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$7.context, onClick: () => {
      var _a2, _b2;
      if (((_a2 = popList[state.index]) == null ? void 0 : _a2.redirect) == 1) {
        onClickToLink((_b2 = popList[state.index]) == null ? void 0 : _b2.redirect_url);
      }
    }, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$7.content_box, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$7.title, children: (_d = popList[state.index]) == null ? void 0 : _d.title }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$7.content, children: (_e = popList[state.index]) == null ? void 0 : _e.content })
    ] }) }),
    itemMode == "right" && /* @__PURE__ */ jsxRuntimeExports.jsx(Items$1, { popList, itemMode, state, setState })
  ] });
};
const Switch = ({ className } = {}) => {
  return /* @__PURE__ */ jsxRuntimeExports.jsxs(
    "svg",
    {
      className,
      xmlns: "http://www.w3.org/2000/svg",
      width: "44",
      height: "44",
      viewBox: "0 0 44 44",
      fill: "none",
      children: [
        /* @__PURE__ */ jsxRuntimeExports.jsxs("g", { clipPath: "url(#clip0_1587_200984)", children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("rect", { width: "44", height: "44", rx: "22", fill: "black", fillOpacity: "0.24" }),
          /* @__PURE__ */ jsxRuntimeExports.jsx(
            "path",
            {
              d: "M17.2949 12.375L26.9199 22L17.2949 31.625",
              stroke: "white",
              strokeWidth: "2.40625",
              strokeLinecap: "round"
            }
          )
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("defs", { children: /* @__PURE__ */ jsxRuntimeExports.jsx("clipPath", { id: "clip0_1587_200984", children: /* @__PURE__ */ jsxRuntimeExports.jsx("rect", { width: "44", height: "44", rx: "11", fill: "white" }) }) })
      ]
    }
  );
};
const Items = ({ popList, state, setState }) => {
  const containRef = reactExports.useRef();
  reactExports.useEffect(() => {
    if (containRef.current && containRef.current.childNodes[state.index]) {
      const clientWidth = containRef.current.clientWidth;
      const itemWidth = containRef.current.childNodes[state.index].clientWidth;
      const centerWidth = clientWidth / 2 - itemWidth / 2;
      if (itemWidth * state.index > centerWidth) {
        containRef.current.scrollLeft = itemWidth * state.index - centerWidth;
      } else {
        containRef.current.scrollLeft = 0;
      }
    }
  }, [state.index]);
  const [readedPop, setReadedPop] = reactExports.useState(Array.from(new Set(Cache$1.get("readed_pop") || [])));
  reactExports.useEffect(() => {
    if (popList.length) {
      const rp = [...readedPop, popList[state.index].id];
      Cache$1.set("readed_pop", rp);
      setReadedPop(rp);
    }
  }, [state.index, popList]);
  const checkIsRead = (id) => {
    return readedPop.includes(id);
  };
  return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { ref: containRef, className: "".concat(css$7.items), children: popList.map((pop, i) => /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "".concat(css$7.item, " ").concat(state.index == i && css$7.active, " ").concat(!checkIsRead(pop.id) && css$7.no_read), onClick: () => setState({ index: i }), children: [
    pop.pop_type == "pop" && /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx(Notice, {}),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: pop.name })
    ] }),
    pop.pop_type == "notice" && /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx(Msg, {}),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: pop.title })
    ] })
  ] }, i)) });
};
const Vertical = ({ itemMode = "top", state, setState, popList, onClickToLink }) => {
  var _a, _b, _c, _d, _e;
  const onPre = () => {
    if (state.index == 0) {
      setState({
        index: popList.length - 1
      });
    } else {
      setState({
        index: state.index - 1
      });
    }
  };
  const onNext = () => {
    if (state.index == popList.length - 1) {
      setState({
        index: 0
      });
    } else {
      setState({
        index: state.index + 1
      });
    }
  };
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "".concat(css$7.vertical, " ").concat(css$7[itemMode]), children: [
    itemMode == "top" && /* @__PURE__ */ jsxRuntimeExports.jsx(Items, { popList, itemMode, state, setState }),
    ((_a = popList[state.index]) == null ? void 0 : _a["pop_type"]) == "pop" && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { onClick: () => {
      var _a2;
      return onClickToLink((_a2 = popList[state.index]) == null ? void 0 : _a2.link);
    }, className: css$7.context, style: {
      backgroundImage: "url(".concat("").concat((_b = popList[state.index]) == null ? void 0 : _b.img, ")")
    } }),
    ((_c = popList[state.index]) == null ? void 0 : _c["pop_type"]) == "notice" && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$7.context, onClick: () => {
      var _a2, _b2;
      if (((_a2 = popList[state.index]) == null ? void 0 : _a2.redirect) == 1) {
        onClickToLink((_b2 = popList[state.index]) == null ? void 0 : _b2.redirect_url);
      }
    }, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$7.content_box, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$7.title, children: (_d = popList[state.index]) == null ? void 0 : _d.title }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$7.content, children: (_e = popList[state.index]) == null ? void 0 : _e.content })
    ] }) }),
    itemMode == "bottom" && /* @__PURE__ */ jsxRuntimeExports.jsx(Items, { popList, itemMode, state, setState }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { onClick: onPre, className: "".concat(css$7.switch, " ").concat(css$7.switch_left), children: /* @__PURE__ */ jsxRuntimeExports.jsx(Switch, {}) }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { onClick: onNext, className: "".concat(css$7.switch, " ").concat(css$7.switch_right), children: /* @__PURE__ */ jsxRuntimeExports.jsx(Switch, {}) })
  ] });
};
const onceADayPop = Cache$1.get("onceADayPop") || {
  // 临时储存
  loginBF: {},
  loginAF: {},
  other: {}
};
const onceADay = (id, loginType3 = "other") => {
  const onceADayPopRight = Cache$1.get("onceADayPop") || {
    // 临时储存
    loginBF: {},
    loginAF: {},
    other: {}
  };
  if (onceADayPopRight[loginType3] && onceADayPopRight[loginType3][id]) {
    if (dayjs(onceADayPopRight[loginType3][id]).date() === dayjs().date()) {
      return false;
    }
  }
  onceADayPop[loginType3][id] = Date.now();
  return true;
};
const isShowPop = (item2) => {
  const token = Cache$1.get("token");
  let type = item2.login_bf;
  if (token) {
    type = item2.login_af;
  }
  if (type == 0 || !type) {
    return false;
  } else if (type == 1) {
    return true;
  } else if (type == 2) {
    return onceADay(item2.id, token ? "loginAF" : "loginBF");
  } else if (type == 3) {
    return window.isNewLogin ? true : false;
  }
};
const isShowToday = (item2) => {
  const data = Cache$1.get("no_need_show_today") || {};
  if (!data[item2.id]) {
    return true;
  }
  return dayjs(data[item2.id]).date() !== dayjs().date();
};
const no_need_show_today = Cache$1.get("no_need_show_today") || {};
const noNeedShowTodayClick = (item2, checked) => {
  if (checked) {
    no_need_show_today[item2.id] = Date.now();
  } else {
    no_need_show_today[item2.id] = void 0;
  }
};
const isPopNeverShow = (item2) => {
  const data = Cache$1.get("never_show_pop") || [];
  if (!data.includes(item2.id)) {
    return true;
  }
  return false;
};
const never_show_pop = Cache$1.get("never_show_pop") || [];
const popNeverShowClick = (item2, checked) => {
  if (checked) {
    never_show_pop.push(item2.id);
  } else {
    const index = never_show_pop.findIndex((i) => i == item2.id);
    if (index != -1) {
      never_show_pop.splice(index, 1);
    }
  }
};
const rightPopData = () => {
  Cache$1.set("onceADayPop", onceADayPop);
  Cache$1.set("no_need_show_today", no_need_show_today);
  Cache$1.set("never_show_pop", never_show_pop);
};
const AlertBox = ({ showNextPop, onClickToLink, setLoading }) => {
  var _a;
  const { websetConfig, theme, token } = useUserInfoStore();
  const [state, setState] = useSetState({
    index: 0,
    popList: [],
    noNeedShowMap: {}
  });
  useAsyncEffect(async () => {
    setLoading(true);
    const [webRes] = await memberWebsetList({ item: "pop" });
    const pop = (webRes == null ? void 0 : webRes.pop) || [];
    const [noticesRed] = await getNoticeList();
    const notices = (noticesRed == null ? void 0 : noticesRed.d) || [];
    const popList = [...notices.filter((item2) => item2.is_pop == 1).map((item2) => {
      return {
        pop_type: "notice",
        ...item2
      };
    }), ...pop.map((item2) => {
      return {
        pop_type: "pop",
        ...item2
      };
    })].filter((item2) => isShowPop(item2)).filter((item2) => {
      if (item2.close_today == 1) {
        return isShowToday(item2);
      }
      return true;
    });
    setState({ popList });
    setLoading(false);
    if (popList.length == 0) {
      showNextPop();
    }
    popList.forEach((item2) => {
      if (item2.img) {
        preloadImage("https://dl-br-cf.sadslj88.com/image-prod" + item2.img);
      }
    });
  }, [token]);
  if (state.loading) {
    return /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, {});
  }
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$7.alert_box, style: {
    height: ["2", "3"].includes((websetConfig == null ? void 0 : websetConfig.homePopupStyle) || "1") ? "700rem" : "500rem"
  }, children: [
    ["2", "3"].includes((websetConfig == null ? void 0 : websetConfig.homePopupStyle) || "1") && /* @__PURE__ */ jsxRuntimeExports.jsx(Vertical, { onClickToLink, itemMode: ((websetConfig == null ? void 0 : websetConfig.homePopupStyle) || "1") == "2" ? "top" : "bottom", popList: state.popList, state, setState }),
    ["1", "4"].includes((websetConfig == null ? void 0 : websetConfig.homePopupStyle) || "1") && /* @__PURE__ */ jsxRuntimeExports.jsx(Horizontal, { onClickToLink, itemMode: ((websetConfig == null ? void 0 : websetConfig.homePopupStyle) || "1") == "1" ? "left" : "right", popList: state.popList, state, setState }),
    ((_a = state.popList[state.index]) == null ? void 0 : _a.close_today) == 1 && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$7.no_show_select, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$7.select_box, onClick: () => {
      setState({
        noNeedShowMap: {
          ...state.noNeedShowMap,
          [state.popList[state.index].id]: !state.noNeedShowMap[state.popList[state.index].id]
        }
      });
      noNeedShowTodayClick(state.popList[state.index], !state.noNeedShowMap[state.popList[state.index].id]);
    }, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx(CheckBox, { checked: Boolean(state.noNeedShowMap[state.popList[state.index].id]) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$7.tipsSpan, children: trans("Don't show again today") })
    ] }) }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$7.clsoe_box, children: /* @__PURE__ */ jsxRuntimeExports.jsx(
      "div",
      {
        className: css$7.clsoeBtn,
        onClick: () => {
          console.log("22222222222222");
          showNextPop();
        },
        children: /* @__PURE__ */ jsxRuntimeExports.jsx(CloseIconInLogin, {})
      }
    ) })
  ] });
};
const svg_theme_fill_color$6 = "_svg_theme_fill_color_5ubz4_3";
const loginBox = "_loginBox_5ubz4_55";
const clsoeBtn = "_clsoeBtn_5ubz4_62";
const contentBox = "_contentBox_5ubz4_82";
const fav$1 = "_fav_5ubz4_88";
const content = "_content_5ubz4_82";
const loginBtn = "_loginBtn_5ubz4_105";
const icon = "_icon_5ubz4_123";
const css$6 = {
  svg_theme_fill_color: svg_theme_fill_color$6,
  loginBox,
  clsoeBtn,
  contentBox,
  fav: fav$1,
  content,
  loginBtn,
  icon
};
const DownloadBox = ({ showNextPop }) => {
  const { standalone } = useGuideStore();
  const domain = window.location.hostname;
  getWebType();
  const { websetConfig, version, appUrl, openDownload } = useUserInfoStore();
  const typeApp = useWebsetConfig().websetConfig.isHiddenDownloadApp;
  const bannerSwitch = websetConfig.banner_switch;
  reactExports.useEffect(() => {
    if (typeApp || bannerSwitch != "1" || standalone) {
      showNextPop();
    }
  }, []);
  return /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, { children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$6.loginBox, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$6.clsoeBtn, onClick: () => {
      showNextPop();
    }, children: /* @__PURE__ */ jsxRuntimeExports.jsx(CloseIconInLogin, {}) }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$6.contentBox, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
        "  ",
        /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { className: css$6.fav, src: "/uploads/favicon.png?v=".concat("2024_12_14_18_4") })
      ] }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$6.content, children: websetConfig.banner_text })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$6.loginBtn, onClick: () => {
      window.open(appUrl, "_blank");
      showNextPop();
    }, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
        getBrowser().isIos ? /* @__PURE__ */ jsxRuntimeExports.jsx(IosIcon, { className: css$6.icon }) : /* @__PURE__ */ jsxRuntimeExports.jsx(AndroidIcon, { className: css$6.icon }),
        "  "
      ] }),
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
        /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { children: [
          " V",
          version
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("span", { style: { marginLeft: "5rem" }, children: domain })
      ] })
    ] })
  ] }) });
};
const PopBox$1 = ({ onClickToLink }) => {
  const location2 = useLocation();
  const { activityList } = useGameStore();
  const { token } = useUserInfoStore();
  const [state, setState] = useSetState({
    index: 0,
    loading: false,
    popList: []
  });
  reactExports.useEffect(() => {
    const aList = activityList.filter((a) => {
      return isShowPop(a);
    }).filter((a) => {
      return isShowToday(a);
    }).filter((a) => {
      return isPopNeverShow(a);
    });
    setState({
      index: 0,
      popList: [
        // 默认显示后台网站设置弹窗
        {
          name: "defaultPop"
        },
        {
          name: "gong_toup"
        },
        ...aList.map((item2) => {
          return { name: item2.flag, id: item2.id };
        })
      ]
    });
  }, [activityList, token]);
  const showNextPop = () => {
    const index = state.index + 1;
    setState({
      index
    });
    if (!state.popList[index]) {
      rightPopData();
    }
  };
  const noShowToday = (item2, checked) => {
    noNeedShowTodayClick(item2, checked);
  };
  const neverShow = (item2, checked) => {
    popNeverShowClick(item2, checked);
  };
  if (location2.pathname !== "/") {
    return /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, {});
  }
  if (!state.popList[state.index]) {
    return /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, {});
  }
  if (!["gong_toup", "defaultPop", "single_topup", "turntable", "lucky"].includes(state.popList[state.index].name)) {
    showNextPop();
  }
  return /* @__PURE__ */ jsxRuntimeExports.jsxs(Modal, { closeByClickOut: false, isOpen: true, isShow: !state.loading, zIndex: 99, children: [
    state.popList[state.index].name == "defaultPop" && /* @__PURE__ */ jsxRuntimeExports.jsx(DownloadBox, { showNextPop }),
    state.popList[state.index].name == "gong_toup" && /* @__PURE__ */ jsxRuntimeExports.jsx(AlertBox, { onClickToLink, showNextPop, setLoading: (loading2) => setState({ loading: loading2 }) }),
    state.popList[state.index].name == "single_topup" && /* @__PURE__ */ jsxRuntimeExports.jsx(HandoutDialog, { onClose: showNextPop }),
    state.popList[state.index].name == "turntable" && /* @__PURE__ */ jsxRuntimeExports.jsx(LoginWheelDialog, { neverShow: (checked) => neverShow(state.popList[state.index], checked), noShowToday: (checked) => noShowToday(state.popList[state.index], checked), onClose: showNextPop }),
    state.popList[state.index].name == "lucky" && /* @__PURE__ */ jsxRuntimeExports.jsx(DiaDmWheel, { neverShow: (checked) => neverShow(state.popList[state.index], checked), noShowToday: (checked) => noShowToday(state.popList[state.index], checked), onClose: showNextPop })
  ] });
};
const svg_theme_fill_color$5 = "_svg_theme_fill_color_4w97a_3";
const xuanfu = "_xuanfu_4w97a_55";
const img$1 = "_img_4w97a_60";
const click_close = "_click_close_4w97a_64";
const close = "_close_4w97a_71";
const xuanfuGap = "_xuanfuGap_4w97a_78";
const kfContainer = "_kfContainer_4w97a_81";
const css$5 = {
  svg_theme_fill_color: svg_theme_fill_color$5,
  xuanfu,
  img: img$1,
  click_close,
  close,
  xuanfuGap,
  kfContainer
};
const XuanFuTG = (props) => {
  const closeIcon = {
    black: "/home/icons/reg_close.webp",
    blue: "/home/icons/reg_close.webp",
    whiteGreen: "/home/icons/whiteGreen_reg_close.webp",
    whiteRed: "/home/icons/whiteRed_reg_close.webp",
    whiteYellow: "/home/icons/whiteGreen_reg_close.webp",
    whitePink: "/home/icons/whiteGreen_reg_close.webp",
    whiteBlue: "/home/icons/whiteBlue_reg_close.webp",
    whitePurple: "/home/icons/whiteGreen_reg_close.webp",
    whiteBrown: "/home/icons/whiteGreen_reg_close.webp",
    whiteDarkGreen: "/home/icons/whiteRed_reg_close.webp"
  };
  const { theme } = useUserInfoStore();
  const location2 = useLocation();
  const [state, setState] = reactExports.useState({});
  const [xuanfuList, setXuanfuList] = reactExports.useState([]);
  const getData = async () => {
    const [res = [], err] = await getMemberCustomerList({
      flag: "2"
    });
    if (err) return;
    if (!res || res.length === 0) return;
    let stateMap = {};
    for (let i = 0; i < res.length; i++) {
      stateMap[res[i].id] = true;
    }
    setState(stateMap);
    setXuanfuList(res != null ? res : []);
  };
  const renderList = reactExports.useMemo(() => {
    return xuanfuList.filter((item2) => {
      return state[item2.id];
    });
  }, [xuanfuList, state]);
  reactExports.useEffect(() => {
  }, [state]);
  reactExports.useEffect(() => {
    getData();
  }, []);
  if (location2.pathname !== "/") return null;
  return /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, { children: !!renderList.length && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$5.xuanfu, style: props.style, children: renderList.map((item2, index) => {
    return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$5.kfContainer, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx(
        Image,
        {
          className: css$5.img,
          onClick: () => {
            if (item2.link === "") return;
            if (!item2.link.startsWith("http")) return;
            if (!!navigator.standalone) {
              window.location.href = item2.link;
            } else {
              desktopOpen(item2.link);
            }
          },
          src: item2.im,
          remote: true,
          isGame: true
        }
      ),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$5.xuanfuGap }),
      /* @__PURE__ */ jsxRuntimeExports.jsx(
        "div",
        {
          className: css$5.click_close,
          onClick: () => {
            let stateMap = { ...state };
            stateMap[item2.id] = false;
            setState(stateMap);
          },
          children: /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { className: css$5.close, src: closeIcon[theme] || "/home/icons/reg_close.webp" })
        }
      )
    ] }, item2.id);
  }) }) });
};
const svg_theme_fill_color$4 = "_svg_theme_fill_color_gbvvf_3";
const download_app = "_download_app_gbvvf_55";
const fav = "_fav_gbvvf_177";
const download_container = "_download_container_gbvvf_188";
const download_context = "_download_context_gbvvf_193";
const download_btns = "_download_btns_gbvvf_255";
const cancel = "_cancel_gbvvf_278";
const sure = "_sure_gbvvf_282";
const css$4 = {
  svg_theme_fill_color: svg_theme_fill_color$4,
  download_app,
  fav,
  download_container,
  download_context,
  download_btns,
  cancel,
  sure
};
const DownloadPop = ({ onClose = () => {
}, downloadText }) => {
  reactExports.useState(false);
  const [show, setShow] = reactExports.useState(true);
  const { websetConfig, appUrl } = useUserInfoStore();
  const { t } = useTranslation();
  return show ? reactDomExports.createPortal(
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$4.download_app, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$4.fav, children: /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { className: css$4.fav, src: "/uploads/favicon.png?v=".concat("2024_12_14_18_4") }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$4.download_container, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$4.download_context, children: (websetConfig == null ? void 0 : websetConfig.banner_bottom_text) ? /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: websetConfig.banner_bottom_text }) : /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: t("下载并使用App即可参与更多活动，享受更多优惠！") }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$4.download_btns, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx(
            "button",
            {
              className: joinClass(css$4.cancel, "button"),
              onClick: () => {
                setShow(false);
                onClose("bottom_download_app");
              },
              children: t("取消")
            }
          ),
          /* @__PURE__ */ jsxRuntimeExports.jsx(
            "button",
            {
              className: joinClass(css$4.sure, "button"),
              onClick: () => {
                window.open(appUrl, "_blank");
              },
              children: t("继续")
            }
          )
        ] })
      ] })
    ] }),
    document.body
  ) : /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, {});
};
const PopBox = ({ onClickToLink }) => {
  const location2 = useLocation();
  const today = dayjs().format("YYYY-MM-DD");
  const { token, websetConfig } = useUserInfoStore();
  const [showType, setShowType] = reactExports.useState(null);
  const onClose = (type) => {
    setShowType(null);
    Cache$1.set(type, today);
  };
  const getPopSet = async () => {
    if (!+websetConfig.banner_bottom_switch) {
      setShowType(null);
      return;
    }
    switch (websetConfig.banner_bottom_switch) {
      case "1":
        setShowType("download");
        break;
      case "2":
        setShowType("guide");
    }
  };
  reactExports.useEffect(() => {
    getPopSet();
  }, [token, websetConfig.banner_bottom_switch]);
  if (location2.pathname !== "/") return;
  if (!showType) return;
  if (showType === "guide") {
    return /* @__PURE__ */ jsxRuntimeExports.jsx(
      Guide,
      {
        onClose
      }
    );
  }
  return /* @__PURE__ */ jsxRuntimeExports.jsx(
    DownloadPop,
    {
      onClose
    }
  );
};
const svg_theme_fill_color$3 = "_svg_theme_fill_color_1dedw_3";
const ToTop$1 = "_ToTop_1dedw_55";
const ToTop_svg = "_ToTop_svg_1dedw_241";
const ToTop_svg_bg = "_ToTop_svg_bg_1dedw_245";
const ToTop_svg_icon = "_ToTop_svg_icon_1dedw_356";
const ToTop_hidden = "_ToTop_hidden_1dedw_420";
const lightColor = "_lightColor_1dedw_428";
const css$3 = {
  svg_theme_fill_color: svg_theme_fill_color$3,
  ToTop: ToTop$1,
  ToTop_svg,
  ToTop_svg_bg,
  ToTop_svg_icon,
  ToTop_hidden,
  lightColor
};
const ToTop = () => {
  const [show, setShow] = useGetState(false);
  const location2 = useLocation();
  const handleScroll = (e) => {
    if (e.target.scrollTop >= e.target.clientHeight * 0.5) {
      setShow(true);
    } else {
      setShow(false);
    }
  };
  useEventListener("scroll", handleScroll, { target: document.getElementById("homeBoxScroll") });
  return /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, {
    // 只在首页显示  
    children: location2.pathname === "/" && /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "".concat(css$3.ToTop, " ").concat(!show && css$3.ToTop_hidden), onClick: () => {
      const scrollEl = document.getElementById("homeBoxScroll");
      scrollEl.scrollTo({
        top: 0,
        behavior: "smooth"
      });
    }, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsxs("svg", { xmlns: "http://www.w3.org/2000/svg", width: "41", height: "40", viewBox: "0 0 41 40", fill: "none", className: css$3.ToTop_svg, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx("circle", { cx: "20.5", cy: "20", r: "20", fill: "#2FA33F", className: css$3.ToTop_svg_bg }),
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          "path",
          {
            fillRule: "evenodd",
            clipRule: "evenodd",
            d: "M14.5 10H26.5V11H14.5V10ZM17.5 26C17.5 26 14.5 25 14.5 23C14.5 22 15.5 20.5 16.5 20.5C16.5 17 17.5 15 20.5 13C23.5 15 24.5 17 24.5 20.5C25.5 20.5 26.5 22 26.5 23C26.5 25 23.5 26 23.5 26C23.5 25 22.9 25 22.5 25V26H18.5V25C18 25 17.5 25.2 17.5 26ZM18.5 27H19.5V29C19.1667 28.8333 18.5 28.2 18.5 27ZM20.5 27H22.5C22.5 28 22.1 30 20.5 30V27ZM20.5 20C21.6046 20 22.5 19.1046 22.5 18C22.5 16.8954 21.6046 16 20.5 16C19.3954 16 18.5 16.8954 18.5 18C18.5 19.1046 19.3954 20 20.5 20Z",
            fill: "white",
            className: css$3.ToTop_svg_icon
          }
        )
      ] }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$3.lightColor, children: trans("顶部") })
    ] })
  });
};
const floatStyle = {
  "FloatingStyle1": "70",
  //样式 1
  "FloatingStyle2": "100",
  //样式 2
  "FloatingStyle3": "120",
  //样式 3
  "FloatingStyle4": "146"
  //样式 4
};
const FloatInHome = () => {
  const location2 = useLocation();
  const navigate = useNavigate();
  const { standalone } = useGuideStore();
  const { isSetWithdrawPassword, websetConfig } = useUserInfoStore();
  const [showWheel, setShowWheel] = reactExports.useState(false);
  const onClickToLink = (link) => {
    if (!link) return;
    if (link.indexOf("http") > -1) {
      desktopOpen(link);
      return;
    }
    if (link === "登录") {
      useUserInfoStore.setState({ openForLogin: true });
      return;
    }
    if (link === "充值") {
      useUserInfoStore.setState({ openForRegister: true });
      return;
    }
    if (link === "推广") {
      navigate("/agent");
      return;
    }
    if (link === "充值") {
      navigate("/deposit");
      return;
    }
    if (link === "提现") {
      if (isSetWithdrawPassword) {
        navigate("/withdraw");
      } else {
        navigate("/set-withdraw-psw?to=withdraw");
      }
      return;
    }
    if (link === "客服") {
      navigate("/message?service=1");
      return;
    }
    if (link === "VIP") {
      navigate("/vip");
      return;
    }
    if (link === "个人中心") {
      navigate("/mine");
      return;
    }
    if (link === "拼多多") {
      setShowWheel(true);
      return;
    }
    navigate(link);
  };
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$a.floatInHome, style: { width: floatStyle["FloatingStyle" + (websetConfig == null ? void 0 : websetConfig["FloatingStyle"])] + "rem" }, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx(XuanFuTG, { style: { width: floatStyle["FloatingStyle" + (websetConfig == null ? void 0 : websetConfig["FloatingStyle"])] + "rem" } }),
    /* @__PURE__ */ jsxRuntimeExports.jsx(FloatBox, { onClickToLink, style: floatStyle["FloatingStyle" + (websetConfig == null ? void 0 : websetConfig["FloatingStyle"])] }),
    /* @__PURE__ */ jsxRuntimeExports.jsx(PopBox$1, { onClickToLink }),
    /* @__PURE__ */ jsxRuntimeExports.jsx(ToTop, {}),
    browser.versions.mobile && !standalone && /* @__PURE__ */ jsxRuntimeExports.jsx(PopBox, {}),
    showWheel && location2.pathname === "/" && /* @__PURE__ */ jsxRuntimeExports.jsx(LoginWheelDialog, { isShowFooter: false, onClose: () => {
      setShowWheel(false);
    } })
  ] });
};
const svg_theme_fill_color$2 = "_svg_theme_fill_color_17qwn_3";
const ask_to_reload$1 = "_ask_to_reload_17qwn_55";
const txt$1 = "_txt_17qwn_61";
const btnBoxs$1 = "_btnBoxs_17qwn_69";
const cancelBtn$1 = "_cancelBtn_17qwn_75";
const sureBtn$1 = "_sureBtn_17qwn_97";
const css$2 = {
  svg_theme_fill_color: svg_theme_fill_color$2,
  ask_to_reload: ask_to_reload$1,
  txt: txt$1,
  btnBoxs: btnBoxs$1,
  cancelBtn: cancelBtn$1,
  sureBtn: sureBtn$1
};
const cacheState$1 = {
  timer: null,
  count: 0,
  x: "",
  y: "",
  maxCount: 60 * 20,
  saveTime: 0
};
const AskToReload$1 = () => {
  const [isShow, setIsShow] = reactExports.useState(false);
  const startTimer = () => {
    cacheState$1.count = 0;
    cacheState$1.x = "";
    cacheState$1.y = "";
    clearInterval(cacheState$1.timer);
    function countTime() {
      cacheState$1.count++;
      if (cacheState$1.count >= cacheState$1.maxCount) {
        setIsShow(true);
        clearInterval(cacheState$1.timer);
        cacheState$1.count = 0;
        cacheState$1.x = "";
        cacheState$1.y = "";
      }
    }
    cacheState$1.timer = setInterval(countTime, 1 * 1e3);
  };
  reactExports.useEffect(() => {
    startTimer();
    const ontouchstart = (event) => {
      const touch = event.touches[0] || {};
      const x1 = touch.clientX || "";
      const y1 = touch.clientY || "";
      if (cacheState$1.x != x1 || cacheState$1.y != y1) {
        cacheState$1.count = 0;
      }
      cacheState$1.x = x1;
      cacheState$1.y = y1;
    };
    const onvisibilitychange = () => {
      if (document.visibilityState === "visible") {
        const cha = (/* @__PURE__ */ new Date()).valueOf() / 1e3 - cacheState$1.saveTime;
        if (cha > cacheState$1.maxCount) {
          cacheState$1.count = cacheState$1.maxCount + 1;
        }
      }
      if (document.visibilityState === "hidden") {
        cacheState$1.saveTime = (/* @__PURE__ */ new Date()).valueOf() / 1e3;
      }
    };
    window.addEventListener("ontouchstart", ontouchstart);
    window.addEventListener("visibilitychange", onvisibilitychange);
    return () => {
      window.removeEventListener("ontouchstart", ontouchstart);
      window.removeEventListener("visibilitychange", onvisibilitychange);
    };
  }, []);
  if (!isShow) return /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, {});
  return /* @__PURE__ */ jsxRuntimeExports.jsx(Modal, { isOpen: true, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$2.ask_to_reload, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx("p", { className: css$2.txt, children: trans("您已经很久没有操作，请刷新页面！") }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$2.btnBoxs, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$2.cancelBtn, onClick: () => {
        setIsShow(false);
        startTimer();
      }, children: trans("取消") }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$2.sureBtn, onClick: () => {
        location.reload();
        startTimer();
      }, children: trans("确认") })
    ] })
  ] }) });
};
const svg_theme_fill_color$1 = "_svg_theme_fill_color_11o8n_3";
const ask_to_reload = "_ask_to_reload_11o8n_55";
const txt = "_txt_11o8n_67";
const btnBoxs = "_btnBoxs_11o8n_71";
const cancelBtn = "_cancelBtn_11o8n_76";
const sureBtn = "_sureBtn_11o8n_94";
const css$1 = {
  svg_theme_fill_color: svg_theme_fill_color$1,
  ask_to_reload,
  txt,
  btnBoxs,
  cancelBtn,
  sureBtn
};
const cacheState = {
  timer: null,
  count: 0,
  x: "",
  y: "",
  maxCount: 60 * 20,
  saveTime: 0
};
const AskToReload = () => {
  const [isShow, setIsShow] = reactExports.useState(false);
  const startTimer = () => {
    cacheState.count = 0;
    cacheState.x = "";
    cacheState.y = "";
    clearInterval(cacheState.timer);
    function countTime() {
      cacheState.count++;
      if (cacheState.count >= cacheState.maxCount) {
        setIsShow(true);
        clearInterval(cacheState.timer);
        cacheState.count = 0;
        cacheState.x = "";
        cacheState.y = "";
      }
    }
    cacheState.timer = setInterval(countTime, 1 * 1e3);
  };
  reactExports.useEffect(() => {
    startTimer();
    const onmousemove = (event) => {
      const x1 = event.clientX;
      const y1 = event.clientY;
      if (cacheState.x != x1 || cacheState.y != y1) {
        cacheState.count = 0;
      }
      cacheState.x = x1;
      cacheState.y = y1;
    };
    const onkeydown = () => {
      cacheState.count = 0;
    };
    const onvisibilitychange = () => {
      if (document.visibilityState === "visible") {
        const cha = (/* @__PURE__ */ new Date()).valueOf() / 1e3 - cacheState.saveTime;
        if (cha > cacheState.maxCount) {
          cacheState.count = cacheState.maxCount + 1;
        }
      }
      if (document.visibilityState === "hidden") {
        cacheState.saveTime = (/* @__PURE__ */ new Date()).valueOf() / 1e3;
      }
    };
    window.addEventListener("mousemove", onmousemove);
    window.addEventListener("keydown", onkeydown);
    window.addEventListener("visibilitychange", onvisibilitychange);
    return () => {
      window.removeEventListener("mousemove", onmousemove);
      window.removeEventListener("keydown", onkeydown);
      window.removeEventListener("visibilitychange", onvisibilitychange);
    };
  }, []);
  if (!isShow) return /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, {});
  return /* @__PURE__ */ jsxRuntimeExports.jsx(Modal, { isOpen: true, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.ask_to_reload, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx("p", { className: css$1.txt, children: trans("您已经很久没有操作，请刷新页面！") }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.btnBoxs, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$1.cancelBtn, onClick: () => {
        setIsShow(false);
        startTimer();
      }, children: trans("取消") }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$1.sureBtn, onClick: () => {
        location.reload();
        startTimer();
      }, children: trans("确认") })
    ] })
  ] }) });
};
const svg_theme_fill_color = "_svg_theme_fill_color_yisjk_3";
const music_box = "_music_box_yisjk_55";
const container = "_container_yisjk_63";
const close_img = "_close_img_yisjk_69";
const title = "_title_yisjk_79";
const box = "_box_yisjk_85";
const volume = "_volume_yisjk_91";
const volIcon = "_volIcon_yisjk_99";
const audio_box = "_audio_box_yisjk_107";
const audio_title = "_audio_title_yisjk_111";
const music_list = "_music_list_yisjk_120";
const header = "_header_yisjk_123";
const tag = "_tag_yisjk_132";
const active = "_active_yisjk_139";
const body = "_body_yisjk_146";
const loadMore = "_loadMore_yisjk_162";
const item = "_item_yisjk_170";
const left = "_left_yisjk_178";
const order = "_order_yisjk_194";
const music_name = "_music_name_yisjk_202";
const size = "_size_yisjk_213";
const right = "_right_yisjk_217";
const img = "_img_yisjk_224";
const starActive = "_starActive_yisjk_229";
const deleteIcon = "_deleteIcon_yisjk_240";
const progress = "_progress_yisjk_244";
const point = "_point_yisjk_275";
const progress_small = "_progress_small_yisjk_364";
const audio = "_audio_yisjk_107";
const my_audio = "_my_audio_yisjk_397";
const time = "_time_yisjk_403";
const operation = "_operation_yisjk_411";
const column = "_column_yisjk_423";
const model = "_model_yisjk_451";
const btn_box = "_btn_box_yisjk_454";
const css = {
  svg_theme_fill_color,
  music_box,
  container,
  close_img,
  title,
  box,
  volume,
  volIcon,
  audio_box,
  audio_title,
  music_list,
  header,
  tag,
  active,
  body,
  loadMore,
  item,
  left,
  order,
  music_name,
  size,
  right,
  img,
  starActive,
  deleteIcon,
  progress,
  point,
  progress_small,
  audio,
  my_audio,
  time,
  operation,
  column,
  model,
  btn_box
};
const isPC$1 = document.documentElement.getAttribute("nested") === "1";
const Progress = ({ defaultProgress = 0, onRef, onProgressChange, onOperationChange, size: size2 = "", ...props }) => {
  const { language } = useUserInfoStore();
  const [progress2, setProgress] = reactExports.useState(defaultProgress);
  const progressEl = reactExports.useRef();
  const onPointDrag = (e) => {
    var _a, _b;
    const clientX = e.clientX || ((_b = (_a = e.touches) == null ? void 0 : _a[0]) == null ? void 0 : _b.clientX);
    if (clientX) {
      let dragWidth = (clientX - progressEl.current.getBoundingClientRect().left) / progressEl.current.clientWidth * 100;
      if (dragWidth < 0) {
        dragWidth = 0;
      }
      if (dragWidth > 100) {
        dragWidth = 100;
      }
      dragWidth = language == "ur" ? 100 - dragWidth : dragWidth;
      setProgress(dragWidth);
      onOperationChange == null ? void 0 : onOperationChange(dragWidth);
    }
  };
  const onPointDragEnd = () => {
    document.ontouchmove = null;
    document.ontouchend = null;
    document.onmousemove = null;
    document.onmouseup = null;
  };
  const onPointDragStart = () => {
    if (isPC$1) {
      document.onmousemove = onPointDrag;
      document.onmouseup = onPointDragEnd;
    } else {
      document.ontouchmove = onPointDrag;
      document.ontouchend = onPointDragEnd;
    }
  };
  const onProgressClick = (e) => {
    let dragWidth = (e.clientX - progressEl.current.getBoundingClientRect().left) / progressEl.current.clientWidth * 100;
    dragWidth = language == "ur" ? 100 - dragWidth : dragWidth;
    setProgress(dragWidth);
    onOperationChange == null ? void 0 : onOperationChange(dragWidth);
  };
  reactExports.useEffect(() => {
    onProgressChange == null ? void 0 : onProgressChange(progress2);
  }, [progress2]);
  reactExports.useImperativeHandle(onRef, () => ({
    setProgress
  }));
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { ref: progressEl, className: "".concat(css.progress, " ").concat(size2 ? css["progress_" + size2] : ""), ...props, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx(
      "div",
      {
        className: css.left,
        style: { width: "".concat(progress2, "%") },
        onClick: onProgressClick
      }
    ),
    /* @__PURE__ */ jsxRuntimeExports.jsx(
      "div",
      {
        className: css.right,
        style: { width: "calc(100% - ".concat(progress2, "%)") },
        onClick: onProgressClick
      }
    ),
    /* @__PURE__ */ jsxRuntimeExports.jsx(
      "div",
      {
        className: css.point,
        onMouseDown: onPointDragStart,
        onTouchStart: onPointDragStart,
        style: { left: "".concat(language == "ur" ? 100 - progress2 : progress2, "%") }
      }
    )
  ] });
};
const Audio = () => {
  const { t, i18n } = useTranslation();
  const {
    next,
    pre,
    currentTime,
    totalTime,
    setCurrentTime,
    paused,
    pause,
    play,
    isLoading,
    collectList,
    model: model2,
    setModel
  } = useMusicStore();
  const volumeProgress = reactExports.createRef();
  reactExports.useEffect(() => {
    if ((currentTime == null ? void 0 : currentTime.value) === 0) {
      volumeProgress.current.setProgress(0);
    } else {
      volumeProgress.current.setProgress(
        ((currentTime == null ? void 0 : currentTime.value) || 0) / ((totalTime == null ? void 0 : totalTime.value) || 0) * 100
      );
    }
  }, [currentTime, totalTime]);
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.audio, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.my_audio, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.time, children: isLoading ? "loading" : currentTime.label }),
      /* @__PURE__ */ jsxRuntimeExports.jsx(
        Progress,
        {
          onRef: volumeProgress,
          onOperationChange: (c) => setCurrentTime(c),
          size: "small"
        }
      ),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.time, children: totalTime.label })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.operation, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "".concat(css.column, " ").concat(css.model), onClick: () => {
        setModel();
      }, children: [
        model2 === 1 ? /* @__PURE__ */ jsxRuntimeExports.jsx(AudioLoopIcon, {}) : model2 === 2 ? /* @__PURE__ */ jsxRuntimeExports.jsx(AudioRandomIcon, {}) : /* @__PURE__ */ jsxRuntimeExports.jsx(AudioOneceIcon, {}),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { children: [
          model2 === 1 ? t("循环") : model2 === 2 ? t("随机") : t("重复"),
          "  "
        ] })
      ] }),
      /* @__PURE__ */ jsxRuntimeExports.jsx(
        "div",
        {
          className: css.btn_box,
          style: { transform: "scale(0.9)" },
          onClick: pre,
          children: /* @__PURE__ */ jsxRuntimeExports.jsx(AudioPreIcon, {})
        }
      ),
      /* @__PURE__ */ jsxRuntimeExports.jsx(
        "div",
        {
          className: css.btn_box,
          onClick: () => paused ? play() : pause(),
          children: paused ? /* @__PURE__ */ jsxRuntimeExports.jsx(AudioPlayerIcon, {}) : /* @__PURE__ */ jsxRuntimeExports.jsx(AudioPausedIcon, {})
        }
      ),
      /* @__PURE__ */ jsxRuntimeExports.jsx(
        "div",
        {
          className: css.btn_box,
          style: { transform: "scale(0.9)" },
          onClick: next,
          children: /* @__PURE__ */ jsxRuntimeExports.jsx(AudioNextIcon, {})
        }
      ),
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.column, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx("p", { children: collectList.length }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: t("转入") })
      ] })
    ] })
  ] });
};
const isPC = document.documentElement.getAttribute("nested") === "1";
const Music = () => {
  const { t, i18n } = useTranslation();
  const {
    initMusicStore,
    volume: volume2,
    setVolume,
    setNoVolume,
    musicList,
    current,
    changePlay,
    collectList,
    addCollect,
    removeCollect,
    showTag,
    setShowTag,
    loadMore: loadMore2,
    loadFilter,
    isOpen
  } = useMusicStore();
  const dataRef = reactExports.useRef();
  const loadMoreRef = reactExports.useRef();
  reactExports.useEffect(() => {
    initMusicStore();
  }, []);
  const onScroll = () => {
    if (dataRef.current && loadMoreRef.current) {
      const dr = dataRef.current.getBoundingClientRect();
      const lr = loadMoreRef.current.getBoundingClientRect();
      if (lr.bottom - dr.bottom <= 50) {
        loadMore2();
      }
    }
  };
  const VolProgress = reactExports.useRef();
  const handleVol = () => {
    const music_loc = JSON.parse(localStorage.getItem("music_o"));
    let volNum = music_loc.preVolume || 100;
    volume2 > 0 ? setNoVolume() : setVolume(volNum);
    VolProgress.current.setProgress(volume2 === 0 ? volNum : 0);
  };
  return /* @__PURE__ */ jsxRuntimeExports.jsx(Modal, { isOpen, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.music_box, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx(
      "div",
      {
        className: css.close_img,
        onClick: () => {
          useMusicStore.setState({ isOpen: false });
        },
        children: /* @__PURE__ */ jsxRuntimeExports.jsx(CloseIconInMineIcon, {})
      }
    ),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.container, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.title, children: t("音乐") }),
      isPC && /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "".concat(css.box, " ").concat(css.volume), children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: t("音乐") }),
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          Progress,
          {
            defaultProgress: volume2,
            onProgressChange: (v) => setVolume(v),
            onRef: VolProgress
          }
        ),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { onClick: () => {
          handleVol();
        }, className: css.volIcon, children: volume2 > 0 ? /* @__PURE__ */ jsxRuntimeExports.jsx(AudioVolumIcon, {}) : /* @__PURE__ */ jsxRuntimeExports.jsx(AudioNoVolumIcon, {}) })
      ] }),
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "".concat(css.box, " ").concat(css.audio_box), children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.audio_title, children: (current == null ? void 0 : current.music_name) || t("没有音乐") }),
        /* @__PURE__ */ jsxRuntimeExports.jsx(Audio, {})
      ] }),
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "".concat(css.box, " ").concat(css.music_list), children: [
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.header, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx(
            "div",
            {
              className: "".concat(css.tag, " ").concat(showTag === "hole" && css.active),
              onClick: () => setShowTag("hole"),
              children: t("音乐系统")
            }
          ),
          /* @__PURE__ */ jsxRuntimeExports.jsx(
            "div",
            {
              className: "".concat(css.tag, " ").concat(showTag === "collect" && css.active),
              onClick: () => setShowTag("collect"),
              children: t("我的歌曲")
            }
          )
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { ref: dataRef, className: css.body, onScroll, children: [
          (showTag === "hole" ? musicList : collectList).map((item2, i) => {
            return /* @__PURE__ */ jsxRuntimeExports.jsxs(
              "div",
              {
                className: "".concat(css.item, " ").concat(current.music_name === item2.music_name && css.active),
                children: [
                  /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.left, onClick: () => changePlay(item2), children: [
                    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.order, children: current.music_name === item2.music_name ? /* @__PURE__ */ jsxRuntimeExports.jsx(AudioMusicIcon, {}) : i + 1 }),
                    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.music_name, children: item2.music_name }),
                    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.size, children: item2.parseSize })
                  ] }),
                  showTag === "hole" ? /* @__PURE__ */ jsxRuntimeExports.jsx(
                    "div",
                    {
                      className: css.right,
                      onClick: () => {
                        collectList.find((c) => c.id === item2.id) ? removeCollect(item2) : addCollect(item2);
                      },
                      children: /* @__PURE__ */ jsxRuntimeExports.jsx(AudioStarIcon, { className: "".concat(css.img, " ").concat(collectList.find((c) => c.id === item2.id) ? css.starActive : css.star) })
                    }
                  ) : /* @__PURE__ */ jsxRuntimeExports.jsx(
                    "div",
                    {
                      className: "".concat(css.right, " ").concat(css.deleteIcon),
                      onClick: () => removeCollect(item2),
                      children: /* @__PURE__ */ jsxRuntimeExports.jsx(AudioDeleteIcon, {})
                    }
                  )
                ]
              },
              item2.id
            );
          }),
          /* @__PURE__ */ jsxRuntimeExports.jsx(
            "div",
            {
              ref: loadMoreRef,
              className: css.loadMore,
              style: {
                display: loadFilter.isLoading && !loadFilter.loadOver ? "block" : "none"
              },
              children: "loading"
            }
          )
        ] })
      ] })
    ] })
  ] }) });
};
function App() {
  const ref = reactExports.useRef(null);
  const {
    getUserBalanceByStore,
    websetConfig
  } = useUserInfoStore();
  const [showHeader, setShowHeader] = reactExports.useState(true);
  const navigate = useNavigate();
  const isH5 = getH5Type();
  const currentUrl = window.location.href;
  reactExports.useEffect(() => {
    if (Cache$1.get("token")) {
      postRecallbalance().then(() => {
        getUserBalanceByStore();
      });
    }
    if (currentUrl.split("/").pop().split("?")[0] === "demo-game") {
      useUserInfoStore.setState({
        openForRegister: false,
        openForLogin: false
      });
    }
  }, []);
  reactExports.useEffect(() => {
    var _a;
    if ((_a = websetConfig == null ? void 0 : websetConfig.site_uphold) == null ? void 0 : _a.switch) {
      navigate("/maintain");
    }
    if (location.pathname === "/maintain") {
      setShowHeader(false);
    } else {
      setShowHeader(true);
    }
  }, [location.pathname, websetConfig]);
  useClickAway(() => {
    const dom = ref.current || {};
    dom.innerHTML = "";
  }, ref);
  if (!showHeader) {
    return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$t.h5PageContentBox, children: /* @__PURE__ */ jsxRuntimeExports.jsx(RouterApp, {}) });
  }
  return /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$t.h5PageContentBox, id: "body", children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx(RouterApp, {}),
      /* @__PURE__ */ jsxRuntimeExports.jsx(SliderBox, {}),
      /* @__PURE__ */ jsxRuntimeExports.jsx(Container, { routesMaps }),
      /* @__PURE__ */ jsxRuntimeExports.jsx(Download, {}),
      /* @__PURE__ */ jsxRuntimeExports.jsx(LoginBox, {}),
      /* @__PURE__ */ jsxRuntimeExports.jsx(Login, {}),
      /* @__PURE__ */ jsxRuntimeExports.jsx(AlertForOneSixSix, {}),
      /* @__PURE__ */ jsxRuntimeExports.jsx(FloatInHome, {}),
      isH5 ? /* @__PURE__ */ jsxRuntimeExports.jsx(AskToReload$1, {}) : /* @__PURE__ */ jsxRuntimeExports.jsx(AskToReload, {}),
      /* @__PURE__ */ jsxRuntimeExports.jsx(RegisterByThree, {}),
      /* @__PURE__ */ jsxRuntimeExports.jsx(InfoPreviewByThree, {}),
      /* @__PURE__ */ jsxRuntimeExports.jsx(EditInfoByThree, {}),
      /* @__PURE__ */ jsxRuntimeExports.jsx(Music, {}),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { ref, className: css$t.googleTipBnt, id: "g_id_signIn" })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsx(ChangeTheme, {}),
    location.pathname !== "/maintain" ? /* @__PURE__ */ jsxRuntimeExports.jsx(ShowAllSVG, {}) : null
  ] });
}
export {
  App as default
};
