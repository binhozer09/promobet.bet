import { j as jsxRuntimeExports, I as Image, q as useUserInfoStore, o as useGameStore, y as useNavigate, a1 as useGuideStore, r as reactExports, a0 as useWebsetConfig, a as joinClass, N as getCountryFlag, as as customToFixed, at as Fresh, a4 as instance, z as trans, aq as events, M as Modal, ah as useGetState, a7 as getBrowser, aA as useNoticStore, Q as getMoneyUnit, c9 as useInViewport, a2 as useNavigateToActivity, a3 as sortGameTabs, a5 as scrollToPlatromItem, H as Message, bM as Maintain, bN as getGameName, ca as favoritesList, bS as getPlatLaunch, C as Cache, bU as historySave, bO as favoritesremove, cb as historyList } from "./comps-B8ShbmG--2024_12_14_18_4.js";
import { Q as SiderMenuIcon, cI as TriangleIcon, X as SearchToolIcon, s as AudioVolumIcon, cJ as SvgArrowIcon } from "./icons-Cdaou_E3-2024_12_14_18_4.js";
import { d as DanceNum, D as DepositDialog } from "./index-DgUEEG5U-2024_12_14_18_4.js";
import { G as GameTabs$1, g as gameTypeNames, I as InfoTabs, T as ToolTabs } from "./config-B4NCyDU0-2024_12_14_18_4.js";
import { C as Carousel } from "./index-Cm3Fe5Rr-2024_12_14_18_4.js";
import { D as Download, J as Jackpot } from "./index-J_XVEfOf-2024_12_14_18_4.js";
import "./index-4Dep-BeX-2024_12_14_18_4.js";
import { H as HotGameItem, R as RectGameItem } from "./index-BllDKKQ9-2024_12_14_18_4.js";
import { T as TypeGame } from "./index-IsgrM2cM-2024_12_14_18_4.js";
import { B as BottomTips } from "./index-BYMVQi1V-2024_12_14_18_4.js";
import { L as LoadingImg } from "./index-Bq-a07OY-2024_12_14_18_4.js";
import { S as SearchInput } from "./index-DlqGj1la-2024_12_14_18_4.js";
import { E as Empty } from "./index-BcpFmBv5-2024_12_14_18_4.js";
import "./s3--cfsTSzQ-2024_12_14_18_4.js";
import "./index-CXpt7Cav-2024_12_14_18_4.js";
import "./index-DyG_R4zg-2024_12_14_18_4.js";
import "./index-CGmeRIXa-2024_12_14_18_4.js";
import "./index-CsbwU_08-2024_12_14_18_4.js";
import "./util-DT9EqNCx-2024_12_14_18_4.js";
import "./index-DLcyu0vi-2024_12_14_18_4.js";
import "./index-_vAsCIZG-2024_12_14_18_4.js";
const svg_theme_fill_color$7 = "_svg_theme_fill_color_1wa3q_3";
const headerBox = "_headerBox_1wa3q_55";
const leftBox$2 = "_leftBox_1wa3q_135";
const menuIcon = "_menuIcon_1wa3q_141";
const left = "_left_1wa3q_135";
const right = "_right_1wa3q_149";
const logo = "_logo_1wa3q_168";
const rightBox$2 = "_rightBox_1wa3q_173";
const moneyBox = "_moneyBox_1wa3q_184";
const loginBtn = "_loginBtn_1wa3q_198";
const messageBox$1 = "_messageBox_1wa3q_284";
const messageIcon$1 = "_messageIcon_1wa3q_293";
const num$1 = "_num_1wa3q_301";
const despositOutBtnBox = "_despositOutBtnBox_1wa3q_316";
const line = "_line_1wa3q_405";
const triangLeIconBox = "_triangLeIconBox_1wa3q_464";
const show = "_show_1wa3q_473";
const menuBox = "_menuBox_1wa3q_537";
const dep = "_dep_1wa3q_551";
const saq = "_saq_1wa3q_552";
const despositBtn = "_despositBtn_1wa3q_567";
const registerBtn = "_registerBtn_1wa3q_577";
const moneyIcon = "_moneyIcon_1wa3q_674";
const moneySpan = "_moneySpan_1wa3q_682";
const loading = "_loading_1wa3q_689";
const customDN = "_customDN_1wa3q_701";
const freshIcon = "_freshIcon_1wa3q_713";
const headerBox_search = "_headerBox_search_1wa3q_728";
const css$7 = {
  svg_theme_fill_color: svg_theme_fill_color$7,
  headerBox,
  leftBox: leftBox$2,
  menuIcon,
  left,
  right,
  logo,
  rightBox: rightBox$2,
  moneyBox,
  loginBtn,
  messageBox: messageBox$1,
  messageIcon: messageIcon$1,
  num: num$1,
  despositOutBtnBox,
  line,
  triangLeIconBox,
  show,
  menuBox,
  dep,
  saq,
  despositBtn,
  registerBtn,
  moneyIcon,
  moneySpan,
  loading,
  customDN,
  freshIcon,
  headerBox_search
};
const svg_theme_fill_color$6 = "_svg_theme_fill_color_1kkts_3";
const festival$1 = "_festival_1kkts_55";
const css$6 = {
  svg_theme_fill_color: svg_theme_fill_color$6,
  festival: festival$1
};
const Christmas = () => {
  return /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$6.festival, style: {
      top: 0,
      left: 0
    }, children: /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { style: {
      width: "36rem",
      height: "36rem"
    }, src: "/festival/christmas/tree.webp" }) }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$6.festival, style: {
      top: 0,
      right: 0
    }, children: /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { style: {
      width: "58rem",
      height: "36rem"
    }, src: "/festival/christmas/bell.webp" }) })
  ] });
};
const Header = () => {
  const {
    isShowSliderBox,
    updateSliderBoxStatus,
    theme,
    info,
    getUserBalanceByStore,
    appUrl,
    isSetWithdrawPassword,
    closeAppDownloadTopTips,
    token,
    openDownload
  } = useUserInfoStore();
  const { gameSortMap, games } = useGameStore();
  const navigate = useNavigate();
  useGuideStore();
  const [isShowMenu, setIsShowMenu] = reactExports.useState(false);
  const dom = reactExports.useRef(null);
  const { websetConfig } = useWebsetConfig();
  const [depositStatus, setDepositStatus] = reactExports.useState(false);
  const [loadingTime, setLoadingTime] = reactExports.useState(0);
  const reloadUserBalance = async () => {
    setLoadingTime(Date.now());
    await getUserBalanceByStore();
  };
  GameTabs$1.sort((a, b) => {
    return (a.sort || gameSortMap[a.type] || 0) - (b.sort || gameSortMap[b.type] || 0);
  });
  reactExports.useEffect(() => {
    setTimeout(() => {
      setLoadingTime(0);
    }, Math.max(500 - (Date.now() - loadingTime), 0));
  }, [info]);
  reactExports.useEffect(() => {
    if (isShowMenu) {
      const onMouseUp = (e) => {
        if (dom.current) {
          const parentElement = dom.current.parentElement;
          if (parentElement === e.target || parentElement.contains(e.target)) {
            return;
          }
          if (dom.current !== e.target && !dom.current.contains(e.target)) {
            setIsShowMenu(false);
          }
        }
      };
      document.addEventListener("touchend", onMouseUp);
      return () => {
        document.removeEventListener("touchend", onMouseUp);
      };
    }
  }, [isShowMenu]);
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
    /* @__PURE__ */ jsxRuntimeExports.jsxs(
      "div",
      {
        id: "headerBoxDom",
        className: joinClass(
          css$7.headerBox
        ),
        children: [
          websetConfig.festival_christmas && /* @__PURE__ */ jsxRuntimeExports.jsx(Christmas, {}),
          /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$7.leftBox, children: [
            /* @__PURE__ */ jsxRuntimeExports.jsx(
              "div",
              {
                onClick: () => {
                  updateSliderBoxStatus(!isShowSliderBox);
                },
                className: joinClass(css$7.menuIcon, isShowSliderBox ? css$7.left : css$7.right),
                children: /* @__PURE__ */ jsxRuntimeExports.jsx(SiderMenuIcon, {})
              }
            ),
            websetConfig.logo_img ? /* @__PURE__ */ jsxRuntimeExports.jsx(
              Image,
              {
                src: websetConfig.logo_img,
                onClick: () => navigate("/"),
                className: css$7.logo,
                style: {
                  maxWidth: token ? "210rem" : "230rem"
                },
                remote: true,
                isGame: true
              }
            ) : null
          ] }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$7.rightBox, children: info && info.uid ? /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
            /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$7.moneyBox, children: [
              /* @__PURE__ */ jsxRuntimeExports.jsx(
                Image,
                {
                  className: css$7.moneyIcon,
                  src: getCountryFlag(true)
                }
              ),
              /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$7.moneySpan, children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$7.number, children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                DanceNum,
                {
                  fontWeight: "400",
                  fontSize: "26",
                  color: "#EAB700",
                  ellipsis: 9,
                  num: customToFixed(info.balance || "0.00"),
                  className: css$7.customDN
                }
              ) }) }),
              /* @__PURE__ */ jsxRuntimeExports.jsx(Fresh, { className: css$7.freshIcon, onClick: reloadUserBalance })
            ] }),
            /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$7.despositOutBtnBox, children: [
              /* @__PURE__ */ jsxRuntimeExports.jsx(
                "div",
                {
                  className: css$7.despositBtn,
                  onClick: () => {
                    setDepositStatus(true);
                  },
                  children: instance.t("充值")
                }
              ),
              /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$7.line }),
              /* @__PURE__ */ jsxRuntimeExports.jsx(
                "div",
                {
                  className: joinClass(
                    css$7.triangLeIconBox,
                    isShowMenu ? css$7.show : ""
                  ),
                  onClick: () => {
                    setIsShowMenu(!isShowMenu);
                  },
                  children: /* @__PURE__ */ jsxRuntimeExports.jsx(TriangleIcon, {})
                }
              ),
              isShowMenu ? /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$7.menuBox, ref: dom, children: [
                /* @__PURE__ */ jsxRuntimeExports.jsx(
                  "div",
                  {
                    className: css$7.dep,
                    onClick: () => {
                      navigate("/deposit");
                    },
                    children: instance.t("充值")
                  }
                ),
                /* @__PURE__ */ jsxRuntimeExports.jsx(
                  "div",
                  {
                    className: css$7.saq,
                    onClick: () => {
                      if (isSetWithdrawPassword) {
                        navigate("/withdraw");
                      } else {
                        navigate("/withdraw-set?to=withdraw");
                      }
                    },
                    children: instance.t("提现")
                  }
                )
              ] }) : null
            ] })
          ] }) : /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
            /* @__PURE__ */ jsxRuntimeExports.jsx(
              "div",
              {
                className: css$7.loginBtn,
                onClick: () => {
                  useUserInfoStore.setState({
                    openForLogin: true,
                    isShowSliderBox: false
                  });
                },
                children: trans("登录")
              }
            ),
            /* @__PURE__ */ jsxRuntimeExports.jsx(
              "div",
              {
                className: css$7.registerBtn,
                onClick: () => {
                  useUserInfoStore.setState({
                    openForRegister: true,
                    isShowSliderBox: false
                  });
                },
                children: trans("注册")
              }
            )
          ] }) }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$7.headerBox_search, onClick: () => {
            const firstSearchTabs = games.find((game) => ["电子", "捕鱼", "棋牌", "小游戏"].includes(game.title));
            events.emit("search", firstSearchTabs);
          }, children: /* @__PURE__ */ jsxRuntimeExports.jsx(SearchToolIcon, {}) })
        ]
      }
    ),
    /* @__PURE__ */ jsxRuntimeExports.jsx(Modal, { showType: "bottom", isOpen: depositStatus, onClose: (e) => e && e.stopPropagation(), children: /* @__PURE__ */ jsxRuntimeExports.jsx(DepositDialog, { setDepositStatus }) })
  ] });
};
const svg_theme_fill_color$5 = "_svg_theme_fill_color_1djuc_3";
const noticeBox = "_noticeBox_1djuc_55";
const voiceIconBox = "_voiceIconBox_1djuc_67";
const textBox = "_textBox_1djuc_81";
const marquee = "_marquee_1djuc_89";
const space = "_space_1djuc_98";
const name = "_name_1djuc_107";
const gameName = "_gameName_1djuc_112";
const winText = "_winText_1djuc_116";
const money = "_money_1djuc_120";
const searchBox = "_searchBox_1djuc_125";
const messageBox = "_messageBox_1djuc_138";
const messageIcon = "_messageIcon_1djuc_144";
const iconColor = "_iconColor_1djuc_170";
const stopColorTop = "_stopColorTop_1djuc_173";
const stopColorBotton = "_stopColorBotton_1djuc_176";
const num = "_num_1djuc_179";
const span = "_span_1djuc_192";
const css$5 = {
  svg_theme_fill_color: svg_theme_fill_color$5,
  noticeBox,
  voiceIconBox,
  textBox,
  marquee,
  space,
  name,
  gameName,
  winText,
  money,
  searchBox,
  messageBox,
  messageIcon,
  iconColor,
  stopColorTop,
  stopColorBotton,
  num,
  span
};
const Step0 = ({ className }) => {
  return /* @__PURE__ */ jsxRuntimeExports.jsx("svg", { xmlns: "http://www.w3.org/2000/svg", width: "34", height: "28", viewBox: "0 0 34 28", fill: "none", className, children: /* @__PURE__ */ jsxRuntimeExports.jsx(
    "path",
    {
      d: "M0 19.0909V8.90909C0 8.20618 0.56982 7.63636 1.27273 7.63636H8.27273L15.7504 1.65423C16.5837 0.987567 17.8182 1.58088 17.8182 2.64807V25.3519C17.8182 26.4191 16.5837 27.0124 15.7504 26.3458L8.27273 20.3636H1.27273C0.569819 20.3636 0 19.7938 0 19.0909Z",
      fill: "#2FA33F"
    }
  ) });
};
const Step1 = ({ className }) => {
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("svg", { xmlns: "http://www.w3.org/2000/svg", width: "34", height: "28", viewBox: "0 0 34 28", fill: "none", className, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsxs("g", { clipPath: "url(#clip0_506_7394)", children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx(
        "path",
        {
          fillRule: "evenodd",
          clipRule: "evenodd",
          d: "M22.543 7.92531C22.9157 7.55254 23.5201 7.55254 23.8929 7.92531C27.2478 11.2803 27.2478 16.7197 23.8929 20.0747C23.5201 20.4475 22.9157 20.4475 22.543 20.0747C22.1702 19.7019 22.1702 19.0975 22.543 18.7248C25.1524 16.1153 25.1524 11.8847 22.543 9.27524C22.1702 8.90247 22.1702 8.29808 22.543 7.92531Z",
          fill: "#2FA33F"
        }
      ),
      /* @__PURE__ */ jsxRuntimeExports.jsx(
        "path",
        {
          d: "M0 19.0909V8.90909C0 8.20618 0.56982 7.63636 1.27273 7.63636H8.27273L15.7504 1.65423C16.5837 0.987567 17.8182 1.58088 17.8182 2.64807V25.3519C17.8182 26.4191 16.5837 27.0124 15.7504 26.3458L8.27273 20.3636H1.27273C0.569819 20.3636 0 19.7938 0 19.0909Z",
          fill: "#2FA33F"
        }
      )
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("defs", { children: /* @__PURE__ */ jsxRuntimeExports.jsx("clipPath", { id: "clip0_506_7394", children: /* @__PURE__ */ jsxRuntimeExports.jsx("rect", { width: "33.0909", height: "28", fill: "white" }) }) })
  ] });
};
const VoiceIcon = ({ className }) => {
  const [setp, setStep, getSetp] = useGetState(0);
  reactExports.useEffect(() => {
    const timer = setInterval(() => {
      if (getSetp() === 2) {
        setStep(0);
      } else {
        setStep(getSetp() + 1);
      }
    }, 200);
    return () => {
      clearInterval(timer);
    };
  }, []);
  return /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, { children: setp === 0 ? /* @__PURE__ */ jsxRuntimeExports.jsx(Step0, { className }) : setp === 1 ? /* @__PURE__ */ jsxRuntimeExports.jsx(Step1, { className }) : /* @__PURE__ */ jsxRuntimeExports.jsx(AudioVolumIcon, { className }) });
};
const isMobileIos = getBrowser().isIos && getBrowser().isMobile;
const Notice = () => {
  const navigate = useNavigate();
  const { num: num2 } = useNoticStore();
  const { marqueelist, getMarqueeByStore, theme, websetConfig, token } = useUserInfoStore();
  const messageImg = { 
    black: "/home/icons/message_black.webp",
    blue: "/message/no_read_message_icon_blue2.webp",
    whiteGreen: "/message/no_read_message_icon_white_green2.webp",
    purple: "/message/no_read_message_icon_purple2.webp",
    oilyGreen: "/message/no_read_message_icon_oilyGreen.webp",
    whiteRed: "/message/no_read_message_icon_whiteRed2.webp",
    versaceYellow: "/message/no_read_message_icon_versaceYellow.webp",
    lancomePeach: "/message/no_read_message_icon_lancomePeach2.webp",
    hermesOrange: "/message/no_read_message_icon_hermes_orange.webp",
    whiteBlue: "/message/no_read_message_icon_whiteBlue.webp",
    whiteYellow: "/message/no_read_message_icon_whiteYellow.webp",
    lightBrown: "/message/no_read_message_icon_lightBrown2.webp",
    whiteOrange: "/message/no_read_message_icon_whiteOrange3.webp",
    furlaBlue: "/message/no_read_message_icon_furlaBlue.webp",
    whitePink: "/message/no_read_message_icon_whitePink.webp",
    bvGreen: "/message/no_read_message_icon_bvGreen2.webp",
    whiteBrown: "/message/no_read_message_icon_whiteBrown.webp",
    AnnaSuiPurple: "/message/no_read_message_icon_AnnaSuiPurple.webp",
    whitePurple: "/message/no_read_message_icon_whitePurple.webp",
    whiteDarkGreen: "/message/no_read_message_icon_whiteDarkGreen2.webp",
    burgundyRed: "/message/no_read_message_icon_burgundyRed.webp",
    sk2: "/message/no_read_message_icon_sk2.webp",
    greenGold: "/message/no_read_message_icon_greenGold.webp",
    whiteRedGucci: "/message/no_read_message_icon_whiteRedGucci.webp",
    whiteBrownLauren: "/message/no_read_message_icon_whiteBrownLauren2.webp",
    embraerBlue: "/message/no_read_message_icon_embraerBlue2.webp",
    bvlgariBrown: "/message/no_read_message_icon_bvlgariBrown.webp",
    elsaPink: "/message/no_read_message_icon_elsaPink.webp",
    whiteBlack: "/message/no_read_message_icon_whiteBlack2.webp",
    whiteBlueFendi: "/message/no_read_message_icon_whiteBlueFendi.webp",
    venetaGrey: "/message/no_read_message_icon_venetaGrey2.webp",
    martinPurple: "/message/no_read_message_icon_martinPurple2.webp",
    usdtGreen: "/message/no_read_message_icon_usdtGreen.webp",
    whiteGreenCindy: "/message/no_read_message_icon_whiteGreenCindy.webp",
    microsoftRed: "/home/icons/message_microsoftRed.webp",
    ferrariBlack: "/home/icons/message_ferrariBlack.webp",
    celineBrownWhite: "/home/icons/message_brownWhite.webp",
    burberryBlueWhite: "/home/icons/message_burberryBlueWhite.webp",
    diorPurpleWhite: "/home/icons/message_diorpurplewhite2.webp",
    almondYellow: "/home/icons/message_almondyellow.webp",
    // Novos temas adicionados
    montblancBluewhite: "/message/no_read_message_icon_montblancBluewhite.webp",
    windbellGreen: "/message/no_read_message_icon_windbellGreen.webp",
    bondiBlue: "/message/no_read_message_icon_bondiBlue.webp",
    wasabiYellowWhite: "/message/no_read_message_icon_wasabiYellowWhite.webp",
    gemRed: "/message/no_read_message_icon_gemRed.webp",
    coralPurpleWhite: "/message/no_read_message_icon_coralPurpleWhite.webp",
    elegantPinkWhite: "/message/no_read_message_icon_elegantPinkWhite.webp",
    lemonGreenWhite: "/message/no_read_message_icon_lemonGreenWhite.webp",
    caramelYelloWhite: "/message/no_read_message_icon_caramelYelloWhite.webp",
    camelliaRedWhite: "/message/no_read_message_icon_camelliaRedWhite.webp",
    coralPurple: "/message/no_read_message_icon_coralPurple.webp"
};

const messageReadImg = {
    black: "/message/read_message_icon_black.webp",
    blue: "/message/read_message_icon_blue2.webp",
    whiteGreen: "/message/read_message_icon_whiteGreen2.webp",
    purple: "/message/read_message_icon_whiteGreen2.webp",
    oilyGreen: "/message/read_message_icon_oilyGreen.webp",
    whiteRed: "/message/read_message_icon_whiteRed2.webp",
    versaceYellow: "/message/read_message_icon_versaceYellow.webp",
    lancomePeach: "/message/read_message_icon_lancomePeach2.webp",
    hermesOrange: "/message/read_message_icon_hermes_orange.webp",
    whiteBlue: "/message/read_message_icon_whiteBlue.webp",
    whiteYellow: "/message/read_message_icon_whiteYellow.webp",
    lightBrown: "/message/read_message_icon_lightBrown2.webp",
    whiteOrange: "/message/read_message_icon_whiteOrange2.webp",
    furlaBlue: "/message/read_message_icon_furlaBlue.webp",
    whitePink: "/message/read_message_icon_whitePink.webp",
    bvGreen: "/message/read_message_icon_bvGreen2.webp",
    whiteBrown: "/message/read_message_icon_whiteBrown.webp",
    AnnaSuiPurple: "/message/read_message_icon_AnnaSuiPurple.webp",
    whitePurple: "/message/read_message_icon_whitePurple.webp",
    whiteDarkGreen: "/message/read_message_icon_whiteDarkGreen2.webp",
    burgundyRed: "/message/read_message_icon_burgundyRed.webp",
    greenGold: "/message/read_message_icon_greenGold.webp",
    whiteRedGucci: "/message/read_message_icon_whiteRedGucci.webp",
    whiteBrownLauren: "/message/read_message_icon_whiteBrownLauren2.webp",
    embraerBlue: "/message/read_message_icon_embraerBlue2.webp",
    bvlgariBrown: "/message/read_message_icon_bvlgariBrown.webp",
    elsaPink: "/message/read_message_icon_elsaPink.webp",
    whiteBlack: "/message/read_message_icon_whiteBlack2.webp",
    whiteBlueFendi: "/message/read_message_icon_whiteBlueFendi.webp",
    venetaGrey: "/message/read_message_icon_venetaGrey2.webp",
    martinPurple: "/message/read_message_icon_martinPurple2.webp",
    usdtGreen: "/message/read_message_icon_usdtGreen.webp",
    whiteGreenCindy: "/message/read_message_icon_whiteGreenCindy2.webp",
    microsoftRed: "/message/read_message_icon_microsoftRed2.webp",
    celineBrownWhite: "/message/read_message_icon_brownWhite2.webp",
    ferrariBlack: "/message/read_message_icon_ferrariBlack2.webp",
    burberryBlueWhite: "/message/read_message_icon_burberryBlueWhite2.webp",
    diorPurpleWhite: "/message/read_message_icon_diorpurplewhite.webp",
    almondYellow: "/message/read_message_icon_almondyellow.webp",
    // Novos temas adicionados
    montblancBluewhite: "/message/read_message_icon_montblancBluewhite.webp",
    windbellGreen: "/message/read_message_icon_windbellGreen.webp",
    bondiBlue: "/message/read_message_icon_bondiBlue.webp",
    wasabiYellowWhite: "/message/read_message_icon_wasabiYellowWhite.webp",
    gemRed: "/message/read_message_icon_gemRed.webp",
    coralPurpleWhite: "/message/read_message_icon_coralPurpleWhite.webp",
    elegantPinkWhite: "/message/read_message_icon_elegantPinkWhite.webp",
    lemonGreenWhite: "/message/read_message_icon_lemonGreenWhite.webp",
    caramelYelloWhite: "/message/read_message_icon_caramelYelloWhite.webp",
    camelliaRedWhite: "/message/read_message_icon_camelliaRedWhite.webp",
    coralPurple: "/message/read_message_icon_coralPurple.webp"
};

  reactExports.useEffect(() => {
    getMarqueeByStore();
  }, []);
  const text = marqueelist.map((maq, i) => {
    const arr = maq.split("|");
    const str = '<span class="'.concat(css$5.space, '">\n      <span class="').concat(css$5.name, '">').concat(arr[2], '</span>\n      <span class="').concat(css$5.gameName, '">').concat(arr[1], '</span>\n      <span class="').concat(css$5.winText, '">').concat(trans("它赢了"), '</span>\n      <span class="').concat(css$5.money, '">').concat(getMoneyUnit(true), " ").concat(customToFixed(arr[3]), "</span>&nbsp;\n    </span>");
    return str;
  });
  let numStr = num2 ? num2 : "";
  if (numStr > 99) numStr = "99+";
  const marqueeTxt = websetConfig.marqueeTxt || "";
  const txt2 = marqueeTxt.split("***").map((t, i) => {
    return '<span class="'.concat(css$5.winText, " ").concat(css$5.space, '">\n      ').concat(t.replace(/\s/g, " "), "\n    </span>");
  });
  const marqueeType = websetConfig.marqueeType || "";
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$5.noticeBox, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$5.voiceIconBox, children: /* @__PURE__ */ jsxRuntimeExports.jsx(VoiceIcon, { className: css$5.voiceIcon }) }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$5.textBox, children: /* @__PURE__ */ jsxRuntimeExports.jsx("marquee", { scrolldelay: isMobileIos ? "1" : "20", truespeed: "true", scrollamount: "1", dangerouslySetInnerHTML: {
      __html: marqueeType === "2" ? txt2 : text
    } }) }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs(
      "div",
      {
        className: css$5.messageBox,
        onClick: () => {
          navigate("/message?notice=1");
        },
        children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$5.messageIcon, children: numStr && token ? /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { src: messageImg[theme] || "/home/icons/message_black.webp" }) : /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { src: messageReadImg[theme] || "/message/read_message_icon_black.webp" }) }),
          numStr && token ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$5.num, children: /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$5.span, children: numStr || "99+" }) }) : null
        ]
      }
    )
  ] });
};
const svg_theme_fill_color$4 = "_svg_theme_fill_color_1i1na_3";
const gameTabsBox = "_gameTabsBox_1i1na_55";
const leftArrowBox = "_leftArrowBox_1i1na_186";
const rightArrowBox = "_rightArrowBox_1i1na_187";
const hiden = "_hiden_1i1na_220";
const gameTabs = "_gameTabs_1i1na_55";
const container = "_container_1i1na_270";
const leftLine = "_leftLine_1i1na_276";
const tabIcon = "_tabIcon_1i1na_280";
const tabItemBox = "_tabItemBox_1i1na_287";
const select = "_select_1i1na_444";
const icon$2 = "_icon_1i1na_463";
const iconSelect = "_iconSelect_1i1na_467";
const icons = "_icons_1i1na_479";
const small = "_small_1i1na_547";
const festival = "_festival_1i1na_554";
const css$4 = {
  svg_theme_fill_color: svg_theme_fill_color$4,
  gameTabsBox,
  leftArrowBox,
  rightArrowBox,
  hiden,
  gameTabs,
  container,
  leftLine,
  tabIcon,
  tabItemBox,
  select,
  icon: icon$2,
  iconSelect,
  icons,
  small,
  festival
};
const GameTabs = () => {
  const { games, selectType, gameOpenMap, gameSortMap } = useGameStore();
  const { appUrl, theme, websetConfig, token, language } = useUserInfoStore();
  const leftLineRef = reactExports.useRef();
  const rightLineRef = reactExports.useRef();
  const [leftLineRefInView] = useInViewport(leftLineRef);
  const [rightLineRefInView] = useInViewport(rightLineRef);
  const scrollDom = reactExports.useRef();
  const navigate = useNavigate();
  const { list: activityList, handleClick } = useNavigateToActivity();
  GameTabs$1.sort((a, b) => {
    return (a.sort || gameSortMap[a.type] || 0) - (b.sort || gameSortMap[b.type] || 0);
  });
  reactExports.useEffect(() => {
    const dom = document.querySelector(".".concat(css$4.gameTabs));
    const select2 = dom.querySelector(".".concat(css$4.select));
    if (select2) {
      dom.scrollTo({
        left: select2.offsetLeft - dom.clientWidth / 2 + select2.clientWidth / 2,
        behavior: "smooth"
      });
    }
  }, [selectType]);
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$4.gameTabsBox, id: "gameTabsBox", children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx(
      "div",
      {
        className: joinClass(
          css$4.leftArrowBox,
          leftLineRefInView ? css$4.hiden : ""
        ),
        onClick: () => {
          scrollDom.current.scrollTo({
            left: language == "ur" ? scrollDom.current.scrollLeft + scrollDom.current.clientWidth : scrollDom.current.scrollLeft - scrollDom.current.clientWidth,
            behavior: "smooth"
          });
        },
        children: /* @__PURE__ */ jsxRuntimeExports.jsx(SvgArrowIcon, {})
      }
    ),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { ref: scrollDom, className: css$4.gameTabs, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$4.container, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { ref: leftLineRef, className: css$4.leftLine }),
      sortGameTabs(GameTabs$1, games).map((tab, idx) => {
        if (!tab.id) {
          if (!gameOpenMap[tab.type] && gameTypeNames.indexOf(tab.type) > -1)
            return null;
          if (!tab.grey) return null;
          if (!games.find((item) => item.name === tab.type) && tab.adminConfigShow === true) return null;
        }
        if (tab.type === "试玩" && token) {
          return;
        }
        const isSelect = selectType === tab.type;
        const IconsCom = tab.IconsCom || "";
        const ActiveIcons = tab.ActiveIcon || "";
        return /* @__PURE__ */ jsxRuntimeExports.jsxs(
          "div",
          {
            className: joinClass(
              css$4.tabItemBox,
              isSelect ? css$4.select : ""
            ),
            id: "label_".concat(tab.text),
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
              if (tab.type == "试玩") {
                let gameId = games.find((item) => item.name === "试玩").id;
                navigate("/demo-game?id=".concat(gameId, "&pid=").concat("0"));
              }
              if (tab.action === "router") {
                const gameItem = games.find(
                  (item) => item.name === tab.type
                );
                if (!gameItem) return;
              }
              if (tab.action === "home") {
                navigate("/");
                useGameStore.setState({ selectType: tab.type });
                scrollToPlatromItem(GameTabs$1, tab.type);
                window.clickTabing = true;
                setTimeout(() => {
                  window.clickTabing = false;
                }, 100);
              }
            },
            children: [
              websetConfig.festival_christmas && isSelect && /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { className: css$4.festival, src: "/festival/christmas/santa_deer.webp" }),
              isSelect && !!ActiveIcons ? /* @__PURE__ */ jsxRuntimeExports.jsx(ActiveIcons, { className: joinClass(css$4.icon) }) : !!IconsCom ? /* @__PURE__ */ jsxRuntimeExports.jsx(
                IconsCom,
                {
                  className: joinClass(css$4.icons),
                  isSelect
                }
              ) : /* @__PURE__ */ jsxRuntimeExports.jsx(
                "div",
                {
                  className: css$4.tabIcon,
                  style: { backgroundImage: "url(".concat("https://dl-br-cf.sadslj88.com" + tab.icon, ")") }
                }
              ),
              /* @__PURE__ */ jsxRuntimeExports.jsx("span", { text: tab.type, className: css$4.gameTabStyle, children: instance.t(tab.text) || tab.name })
            ]
          },
          idx
        );
      }),
      InfoTabs.map((tab, idx) => {
        if (!tab.isShowInHome) return;
        const IconsCom = tab.IconsCom || "";
        return /* @__PURE__ */ jsxRuntimeExports.jsxs(
          "div",
          {
            className: css$4.tabItemBox,
            onClick: () => {
              navigate(tab.url);
            },
            children: [
              !!IconsCom ? /* @__PURE__ */ jsxRuntimeExports.jsx(IconsCom, { className: joinClass(css$4.icon, css$4.icons) }) : /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { className: css$4.icon, src: "/home/icons/".concat(tab.icon) }),
              /* @__PURE__ */ jsxRuntimeExports.jsx("span", { text: tab.type, children: instance.t(tab.text) })
            ]
          },
          idx
        );
      }),
      ToolTabs.map((tab, idx) => {
        if (!tab.isShowInHome) return;
        const IconsCom = tab.IconsCom || "";
        return /* @__PURE__ */ jsxRuntimeExports.jsxs(
          "div",
          {
            className: css$4.tabItemBox,
            onClick: () => {
              if (tab.path === "download") {
                window.open(appUrl, "_blank");
              }
            },
            children: [
              !!IconsCom ? /* @__PURE__ */ jsxRuntimeExports.jsx(IconsCom, { className: joinClass(css$4.icon, css$4.icons) }) : /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { className: css$4.icon, src: "/home/icons/".concat(tab.icon) }),
              /* @__PURE__ */ jsxRuntimeExports.jsx("span", { text: tab.type, children: instance.t(tab.text) })
            ]
          },
          idx
        );
      }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { ref: rightLineRef, className: css$4.leftLine })
    ] }) }),
    /* @__PURE__ */ jsxRuntimeExports.jsx(
      "div",
      {
        className: joinClass(
          css$4.rightArrowBox,
          rightLineRefInView ? css$4.hiden : ""
        ),
        onClick: () => {
          scrollDom.current.scrollTo({
            left: language == "ur" ? scrollDom.current.scrollLeft - scrollDom.current.clientWidth : scrollDom.current.scrollLeft + scrollDom.current.clientWidth,
            behavior: "smooth"
          });
        },
        children: /* @__PURE__ */ jsxRuntimeExports.jsx(SvgArrowIcon, {})
      }
    )
  ] });
};
const svg_theme_fill_color$3 = "_svg_theme_fill_color_fasfu_3";
const gamesBox = "_gamesBox_fasfu_55";
const css$3 = {
  svg_theme_fill_color: svg_theme_fill_color$3,
  gamesBox
};
const HomeGameTypes = [
  {
    type: "Popular",
    icon: "hot_active.webp"
  }
];
const Games = () => {
  const { games } = useGameStore();
  const platformList = [];
  games.forEach((gameTypeobj) => {
    if (gameTypeobj.l && Array.isArray(gameTypeobj.l) && gameTypeobj.l.length > 0) {
      gameTypeobj.l.forEach((game) => {
        if (game.platform_is_hot === 1) {
          const obj = Object.assign(
            {},
            game,
            { prefix: gameTypeobj.prefix, level: gameTypeobj.level, typeName: gameTypeobj.name }
          );
          platformList.push(obj);
        }
      });
    }
  });
  platformList.sort((a, b) => {
    return b.seq - a.seq;
  });
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$3.gamesBox, children: [
    games.find((item) => item.title === "热门") ? HomeGameTypes.map((item, idx) => {
      return /* @__PURE__ */ jsxRuntimeExports.jsx(HotGameItem, { item }, idx);
    }) : "",
    games.filter((item) => item.title !== "热门").map((item, idx) => {
      if (item.l && Array.isArray(item.l) && item.l.length > 0) {
        return /* @__PURE__ */ jsxRuntimeExports.jsx(TypeGame, { item }, idx);
      }
    })
  ] });
};
const svg_theme_fill_color$2 = "_svg_theme_fill_color_18i9o_3";
const homeContainer = "_homeContainer_18i9o_55";
const contentBox = "_contentBox_18i9o_257";
const download = "_download_18i9o_265";
const css$2 = {
  svg_theme_fill_color: svg_theme_fill_color$2,
  homeContainer,
  contentBox,
  download
};
const svg_theme_fill_color$1 = "_svg_theme_fill_color_eua6c_3";
const typeGameBox$1 = "_typeGameBox_eua6c_55";
const headBox$1 = "_headBox_eua6c_55";
const leftBox$1 = "_leftBox_eua6c_65";
const icon$1 = "_icon_eua6c_71";
const platformLogo$1 = "_platformLogo_eua6c_83";
const rightBox$1 = "_rightBox_eua6c_141";
const gameBoxs$1 = "_gameBoxs_eua6c_230";
const gameBox$1 = "_gameBox_eua6c_230";
const logoBox$1 = "_logoBox_eua6c_242";
const laodingBox$1 = "_laodingBox_eua6c_252";
const game_list_item_hots$1 = "_game_list_item_hots_eua6c_378";
const gameBox_s$1 = "_gameBox_s_eua6c_389";
const platformText$1 = "_platformText_eua6c_404";
const empty$1 = "_empty_eua6c_416";
const css$1 = {
  svg_theme_fill_color: svg_theme_fill_color$1,
  typeGameBox: typeGameBox$1,
  headBox: headBox$1,
  leftBox: leftBox$1,
  icon: icon$1,
  platformLogo: platformLogo$1,
  rightBox: rightBox$1,
  gameBoxs: gameBoxs$1,
  gameBox: gameBox$1,
  logoBox: logoBox$1,
  laodingBox: laodingBox$1,
  game_list_item_hots: game_list_item_hots$1,
  gameBox_s: gameBox_s$1,
  platformText: platformText$1,
  empty: empty$1
};
const Collect = (props = {}) => {
  const initState = {
    pid: "0",
    flag: 0,
    keyword: ""
  };
  const [listState, updateListState] = reactExports.useState(initState);
  const navigate = useNavigate();
  const { token, info } = useUserInfoStore();
  const item = props.item || null;
  if (!item) return;
  const [list, setList] = reactExports.useState([]);
  const [searchList, setSearchList] = reactExports.useState([]);
  async function getGame() {
    if (!token) {
      useUserInfoStore.setState({ openForLogin: true });
      return;
    }
    const [result] = await favoritesList();
    setList((result == null ? void 0 : result.d) || []);
    setSearchList((result == null ? void 0 : result.d) || []);
  }
  reactExports.useEffect(() => {
    getGame();
  }, [item]);
  const tabItem = GameTabs$1.find((tabItem2) => {
    return tabItem2.type && tabItem2.type === item;
  }) || null;
  if (!tabItem) return;
  const getPlatLaunchFun = async (id, codeId = "") => {
    if (!token) {
      useUserInfoStore.setState({ openForLogin: true });
      return;
    }
    const [res, error] = await getPlatLaunch(
      { id, code: codeId },
      { useLoading: true }
    );
    if (res) {
      Cache.set("gameStart", res);
      navigate("/gameStart");
      await historySave({ pid: id, code: codeId });
    }
  };
  const IconsCom = tabItem.ActiveIcon || null;
  const { gameNamesMap, maintainedMap } = useGameStore();
  const btnCollect = async (game) => {
    const filterItem = list.filter((item2) => item2.id != game.id);
    const [res, err] = await favoritesremove({
      pid: game.platform_id,
      code: game.game_id
    });
    if (res) setList(filterItem);
  };
  const handleInput = (val) => {
    updateListState({
      ...listState,
      keyword: val
    });
  };
  const handleSearch = () => {
    let regex = new RegExp(listState.keyword);
    let searchResult = searchList.filter((item2) => regex.test(getGameName(item2)));
    setList(searchResult || []);
  };
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.typeGameBox, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx(
      SearchInput,
      {
        value: listState.keyword,
        placeholder: instance.t("搜索游戏"),
        onInput: handleInput,
        onSearch: handleSearch
      }
    ),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.headBox, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.leftBox, children: [
        IconsCom ? /* @__PURE__ */ jsxRuntimeExports.jsx(IconsCom, { isSelect: true, className: css$1.icon }) : null,
        /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: instance.t(tabItem.text) })
      ] }),
      list.length > 0 && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$1.rightBox, children: instance.t("全部") })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.gameBoxs, children: [
      list.map((game, idx) => {
        const gameImg = game.img.replace("images-br/", "images-br-rect/");
        return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$1.gameBox, children: /* @__PURE__ */ jsxRuntimeExports.jsxs(
          "div",
          {
            className: css$1.logoBox,
            onClick: () => {
              if (maintainedMap[game.platform_id] === 2 || game.maintained === 2) return;
              if (info && +info.balance < (+game.min_admission || 0)) {
                Message.error(instance.t("您的余额低于「最低准入金额」，请存款后才能玩", { amount: game.min_admission, coin: getMoneyUnit(true) }));
                return;
              }
              getPlatLaunchFun(game.platform_id, game.game_id);
            },
            children: [
              /* @__PURE__ */ jsxRuntimeExports.jsx(
                "div",
                {
                  onClick: (e) => {
                    e.stopPropagation();
                    btnCollect(game);
                  },
                  className: css$1.game_list_item_hots,
                  children: /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { src: "/home/icons/ic_Favoritos_y.webp" })
                }
              ),
              /* @__PURE__ */ jsxRuntimeExports.jsx(
                Image,
                {
                  loading: /* @__PURE__ */ jsxRuntimeExports.jsx(LoadingImg, { className: css$1.laodingBox }),
                  isGame: true,
                  src: gameImg,
                  remote: true
                }
              ),
              maintainedMap[game.platform_id] === 2 || game.maintained === 2 ? /* @__PURE__ */ jsxRuntimeExports.jsx(Maintain, {}) : null,
              /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$1.gameBox_s, children: getGameName(game) }),
              /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$1.platformText, children: gameNamesMap[game.platform_id] || "" })
            ]
          }
        ) }, idx);
      }),
      list.length === 0 && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$1.empty, children: /* @__PURE__ */ jsxRuntimeExports.jsx(Empty, {}) })
    ] })
  ] });
};
const svg_theme_fill_color = "_svg_theme_fill_color_t0mmv_3";
const typeGameBox = "_typeGameBox_t0mmv_55";
const headBox = "_headBox_t0mmv_55";
const leftBox = "_leftBox_t0mmv_65";
const icon = "_icon_t0mmv_71";
const platformLogo = "_platformLogo_t0mmv_83";
const lengtText = "_lengtText_t0mmv_144";
const rightBox = "_rightBox_t0mmv_147";
const gameBoxs = "_gameBoxs_t0mmv_234";
const gameBox = "_gameBox_t0mmv_234";
const logoBox = "_logoBox_t0mmv_246";
const laodingBox = "_laodingBox_t0mmv_256";
const game_list_item_hots = "_game_list_item_hots_t0mmv_271";
const gameBox_s = "_gameBox_s_t0mmv_282";
const platformText = "_platformText_t0mmv_297";
const empty = "_empty_t0mmv_312";
const css = {
  svg_theme_fill_color,
  typeGameBox,
  headBox,
  leftBox,
  icon,
  platformLogo,
  lengtText,
  rightBox,
  gameBoxs,
  gameBox,
  logoBox,
  laodingBox,
  game_list_item_hots,
  gameBox_s,
  platformText,
  empty
};
const Rcent = (props = {}) => {
  const initState = {
    pid: "0",
    flag: 0,
    keyword: ""
  };
  const [listState, updateListState] = reactExports.useState(initState);
  const navigate = useNavigate();
  const { token } = useUserInfoStore();
  const item = props.item || null;
  if (!item) return;
  const [list, setList] = reactExports.useState([]);
  const [searchList, setSearchList] = reactExports.useState([]);
  async function getGame() {
    if (!token) {
      useUserInfoStore.setState({ openForLogin: true });
      return;
    }
    const [result] = await historyList();
    setList((result == null ? void 0 : result.d) || []);
    setSearchList((result == null ? void 0 : result.d) || []);
  }
  reactExports.useEffect(() => {
    getGame();
  }, [item]);
  const tabItem = GameTabs$1.find((tabItem2) => {
    return tabItem2.type && tabItem2.type === item;
  }) || null;
  if (!tabItem) return;
  const getPlatLaunchFun = async (id, codeId = "") => {
    if (!token) {
      useUserInfoStore.setState({ openForLogin: true });
      return;
    }
    const [res, error] = await getPlatLaunch(
      { id, code: codeId },
      { useLoading: true }
    );
    if (res) {
      Cache.set("gameStart", res);
      navigate("/gameStart");
      await historySave({ pid: id, code: codeId });
    }
  };
  const IconsCom = tabItem.ActiveIcon || null;
  const { gameNamesMap, maintainedMap } = useGameStore();
  const handleInput = (val) => {
    updateListState({
      ...listState,
      keyword: val
    });
  };
  const handleSearch = () => {
    let regex = new RegExp(listState.keyword);
    let searchResult = searchList.filter((item2) => regex.test(getGameName(item2)));
    setList(searchResult || []);
  };
  useUserInfoStore();
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.typeGameBox, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx(
      SearchInput,
      {
        value: listState.keyword,
        placeholder: instance.t("搜索游戏"),
        onInput: handleInput,
        onSearch: handleSearch
      }
    ),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.headBox, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.leftBox, children: [
        IconsCom ? /* @__PURE__ */ jsxRuntimeExports.jsx(IconsCom, { isSelect: true, className: css.icon }) : null,
        /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: instance.t(tabItem.text) })
      ] }),
      list.length > 0 && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.rightBox, children: instance.t("Tudo") })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.gameBoxs, children: [
      list.map((game, idx) => {
        return /* @__PURE__ */ jsxRuntimeExports.jsx(
          RectGameItem,
          {
            game,
            maintainedMap,
            getPlatLaunchFun,
            gameNamesMap
          },
          idx
        );
      }),
      list.length === 0 && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.empty, children: /* @__PURE__ */ jsxRuntimeExports.jsx(Empty, {}) })
    ] })
  ] });
};
const platDomTopList = [];
const domScrollHandle = (e) => {
  if (window.clickTabing) {
    return;
  }
  if (window.disabledScrollHandle) {
    return;
  }
  const gameTabsBox2 = document.getElementById("gameTabsBox");
  const downloadDom = document.getElementById("downloadDom");
  const headerBoxDom = document.getElementById("headerBoxDom");
  const gameTabsBoxHeight = gameTabsBox2 ? gameTabsBox2.offsetHeight : 0;
  const downloadDomHeight = downloadDom ? downloadDom.offsetHeight : 0;
  const headerBoxDomHeight = headerBoxDom ? headerBoxDom.offsetHeight : 0;
  if (platDomTopList.length === 0) {
    for (let i = 0; i < GameTabs$1.length; i++) {
      const dom = document.getElementById(GameTabs$1[i].text);
      if (!dom) {
        continue;
      }
      platDomTopList.push({
        text: GameTabs$1[i].text,
        type: GameTabs$1[i].type,
        top: dom.offsetTop
      });
    }
    return;
  }
  const _scrollTop = e.target.scrollTop + gameTabsBoxHeight + downloadDomHeight + headerBoxDomHeight;
  let _index = 0;
  for (let i = 0; i < platDomTopList.length; i++) {
    if (_scrollTop >= platDomTopList[i].top) {
      _index = i;
    }
  }
  const _type = platDomTopList[_index].type;
  useGameStore.setState({ selectType: _type });
};
const Home = () => {
  const { selectType, games } = useGameStore();
  const { websetConfig } = useWebsetConfig();
  const renderTopComps = () => {
    let type = websetConfig.sort_jackpot || "4";
    if (type === "1") {
      return /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx(Jackpot, {}),
        /* @__PURE__ */ jsxRuntimeExports.jsx(Carousel, {}),
        /* @__PURE__ */ jsxRuntimeExports.jsx(Notice, {}),
        /* @__PURE__ */ jsxRuntimeExports.jsx(GameTabs, {})
      ] });
    }
    if (type === "2") {
      return /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx(Carousel, {}),
        /* @__PURE__ */ jsxRuntimeExports.jsx(Jackpot, {}),
        /* @__PURE__ */ jsxRuntimeExports.jsx(Notice, {}),
        /* @__PURE__ */ jsxRuntimeExports.jsx(GameTabs, {})
      ] });
    }
    if (type === "3") {
      return /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx(Carousel, {}),
        /* @__PURE__ */ jsxRuntimeExports.jsx(Notice, {}),
        /* @__PURE__ */ jsxRuntimeExports.jsx(Jackpot, {}),
        /* @__PURE__ */ jsxRuntimeExports.jsx(GameTabs, {})
      ] });
    }
    if (type === "4") {
      return /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx(Carousel, {}),
        /* @__PURE__ */ jsxRuntimeExports.jsx(Notice, {}),
        /* @__PURE__ */ jsxRuntimeExports.jsx(GameTabs, {}),
        ["收藏", "最近"].indexOf(selectType) === -1 ? /* @__PURE__ */ jsxRuntimeExports.jsx(Jackpot, {}) : null
      ] });
    }
  };
  reactExports.useEffect(() => {
    if (["收藏", "最近"].includes(selectType)) {
      window.disabledScrollHandle = true;
    } else {
      window.disabledScrollHandle = false;
    }
  }, [selectType]);
  reactExports.useEffect(() => {
    const scrollDom = document.getElementById("homeBoxScroll");
    scrollDom.addEventListener("scroll", domScrollHandle);
    return () => {
      scrollDom.removeEventListener("scroll", domScrollHandle);
    };
  }, []);
  return /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, { children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$2.homeContainer, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx(Download, {}),
    /* @__PURE__ */ jsxRuntimeExports.jsx(Header, {}),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$2.contentBox, id: "homeBoxScroll", children: [
      renderTopComps(),
      ["收藏"].indexOf(selectType) > -1 ? /* @__PURE__ */ jsxRuntimeExports.jsx(Collect, { item: selectType }) : null,
      ["最近"].indexOf(selectType) > -1 ? /* @__PURE__ */ jsxRuntimeExports.jsx(Rcent, { item: selectType }) : null,
      ["收藏", "最近"].indexOf(selectType) === -1 ? /* @__PURE__ */ jsxRuntimeExports.jsx(Games, {}) : null,
      /* @__PURE__ */ jsxRuntimeExports.jsx(BottomTips, {})
    ] })
  ] }) });
};
export {
  Home as default
};
