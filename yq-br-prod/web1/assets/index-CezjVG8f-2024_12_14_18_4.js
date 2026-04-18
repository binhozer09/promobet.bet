import { V as useTranslation, r as reactExports, j as jsxRuntimeExports, M as Modal, I as Image, z as trans, a as joinClass, q as useUserInfoStore, as as customToFixed, cB as postPromoLuckyApply, y as useNavigate, Z as useLocation, o as useGameStore, ac as useSetState, aB as useFlutterApp, aC as sendMessage, cC as getPromoLuckyDetail } from "./comps-B8ShbmG--2024_12_14_18_4.js";
import { a as CloseIconInLogin, ds as WarningIcon, a7 as ArrowLeftInMineIconTwo } from "./icons-Cdaou_E3-2024_12_14_18_4.js";
import { f as LuckyWheel } from "./index-DgUEEG5U-2024_12_14_18_4.js";
import { f as emptyImg, a as customFormatTimer } from "./index-BcpFmBv5-2024_12_14_18_4.js";
import { A as ActivityBottom } from "./index-oTqPuerr-2024_12_14_18_4.js";
import "./index-CXpt7Cav-2024_12_14_18_4.js";
import "./index-DyG_R4zg-2024_12_14_18_4.js";
import "./index-4Dep-BeX-2024_12_14_18_4.js";
import "./index-CGmeRIXa-2024_12_14_18_4.js";
import "./index-CsbwU_08-2024_12_14_18_4.js";
import "./index-Bq-a07OY-2024_12_14_18_4.js";
import "./config-B4NCyDU0-2024_12_14_18_4.js";
const svg_theme_fill_color$8 = "_svg_theme_fill_color_ayc12_3";
const DM_wheel = "_DM_wheel_ayc12_55";
const wheelHeader = "_wheelHeader_ayc12_183";
const wheelLeftArrow = "_wheelLeftArrow_ayc12_433";
const wheelTitle = "_wheelTitle_ayc12_707";
const wheelRecord = "_wheelRecord_ayc12_720";
const wheel_container = "_wheel_container_ayc12_844";
const DM_wheel_page = "_DM_wheel_page_ayc12_847";
const luckyInfo_box = "_luckyInfo_box_ayc12_852";
const wheel_pop_header = "_wheel_pop_header_ayc12_976";
const header_line = "_header_line_ayc12_1101";
const header_line_text = "_header_line_text_ayc12_1107";
const total$1 = "_total_ayc12_1232";
const light = "_light_ayc12_1358";
const xq_button = "_xq_button_ayc12_1439";
const header_progress = "_header_progress_ayc12_1557";
const tabsContainer = "_tabsContainer_ayc12_1561";
const tab_view = "_tab_view_ayc12_1687";
const rules_box = "_rules_box_ayc12_1690";
const dia_select = "_dia_select_ayc12_1814";
const dia_select_opt = "_dia_select_opt_ayc12_1817";
const css$8 = {
  svg_theme_fill_color: svg_theme_fill_color$8,
  DM_wheel,
  wheelHeader,
  wheelLeftArrow,
  wheelTitle,
  wheelRecord,
  wheel_container,
  DM_wheel_page,
  luckyInfo_box,
  wheel_pop_header,
  header_line,
  header_line_text,
  total: total$1,
  light,
  xq_button,
  header_progress,
  tabsContainer,
  tab_view,
  rules_box,
  dia_select,
  dia_select_opt
};
const svg_theme_fill_color$7 = "_svg_theme_fill_color_mvxmi_3";
const p_container = "_p_container_mvxmi_55";
const dm_bgBox = "_dm_bgBox_mvxmi_62";
const lqcg = "_lqcg_mvxmi_65";
const lucky_icon = "_lucky_icon_mvxmi_80";
const PrizeText = "_PrizeText_mvxmi_85";
const dm_total = "_dm_total_mvxmi_96";
const close_icon$2 = "_close_icon_mvxmi_452";
const css$7 = {
  svg_theme_fill_color: svg_theme_fill_color$7,
  p_container,
  dm_bgBox,
  lqcg,
  lucky_icon,
  PrizeText,
  dm_total,
  close_icon: close_icon$2
};
const PrizeDialog = ({ event, children, ...props }) => {
  useTranslation();
  const [state, SetState] = reactExports.useState(false);
  const [type, SetType] = reactExports.useState(1);
  const [amount2, SetAmount] = reactExports.useState(0);
  reactExports.useImperativeHandle(event, () => ({
    open: (prize) => {
      console.log("prize :", prize);
      SetType(prize.type);
      SetAmount(prize.bonus_amount);
      SetState(true);
    }
  }));
  return /* @__PURE__ */ jsxRuntimeExports.jsx(
    Modal,
    {
      isOpen: state,
      background: "rgba(0,0,0,0.7)",
      closeByClickOut: state,
      children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$7.p_container, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$7.dm_bgBox, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { src: "/dm-wheel/dm_prize_".concat(type, ".webp?123"), className: css$7.lucky_icon, onClick: (e) => SetState(false) }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$7.lqcg, children: trans("恭喜获得奖励") })
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$7.PrizeText, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$7.dm_total, children: amount2 }),
          " ",
          /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: trans("已存入余额") })
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          CloseIconInLogin,
          {
            className: joinClass(css$7.close_icon, "button"),
            onClick: (e) => {
              SetState(false);
            }
          }
        )
      ] })
    }
  );
};
const svg_theme_fill_color$6 = "_svg_theme_fill_color_8acqk_3";
const close_icon$1 = "_close_icon_8acqk_55";
const WarningBox = "_WarningBox_8acqk_66";
const warningIcon = "_warningIcon_8acqk_77";
const NoPrizeDialog$1 = "_NoPrizeDialog_8acqk_86";
const container = "_container_8acqk_90";
const css$6 = {
  svg_theme_fill_color: svg_theme_fill_color$6,
  close_icon: close_icon$1,
  WarningBox,
  warningIcon,
  NoPrizeDialog: NoPrizeDialog$1,
  container
};
const NoPrizeDialog = ({ event, children, ...props }) => {
  const [state, SetState] = reactExports.useState(false);
  reactExports.useImperativeHandle(event, () => ({
    open: () => {
      SetState(true);
    }
  }));
  return /* @__PURE__ */ jsxRuntimeExports.jsx(
    Modal,
    {
      isOpen: state,
      background: "rgba(0,0,0,0.7)",
      closeByClickOut: false,
      children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$6.NoPrizeDialog, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$6.container, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$6.WarningBox, children: [
            /* @__PURE__ */ jsxRuntimeExports.jsx(WarningIcon, { className: css$6.warningIcon }),
            /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: trans("重要提醒") })
          ] }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: trans("您的幸运值不足") })
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          CloseIconInLogin,
          {
            className: css$6.close_icon,
            onClick: (e) => {
              SetState(false);
            }
          }
        )
      ] })
    }
  );
};
const svg_theme_fill_color$5 = "_svg_theme_fill_color_bg47l_3";
const comp_wheel = "_comp_wheel_bg47l_55";
const css$5 = {
  svg_theme_fill_color: svg_theme_fill_color$5,
  comp_wheel
};
function Wheel({ luckyInfo, getLuckyLuckyInfo, typeChange }) {
  var _a;
  const luckyWheelRef = reactExports.useRef(null);
  const PrizeDiaRef = reactExports.useRef(null);
  const NoPrizeDiaRef = reactExports.useRef(null);
  const [prizeIndex, setPrizeIndex] = reactExports.useState(null);
  const [prizeList, setPrizeList] = reactExports.useState([]);
  const prizeResult = reactExports.useRef(null);
  const { token, getWheelInfo, wheelInfo, getUserBalanceByStore } = useUserInfoStore((state) => state);
  const loading = reactExports.useRef(false);
  const hadSelect = reactExports.useRef(false);
  const [styleType, setStyleType] = reactExports.useState(1);
  const currentTurntable = reactExports.useMemo(() => {
    const turntable_list = luckyInfo.turntable_list.find((item) => item.type == styleType);
    return turntable_list;
  }, [styleType, luckyInfo]);
  const canIUse = (_a = luckyInfo.lucky_detail) == null ? void 0 : _a.can_use_amount;
  const curUseVal = currentTurntable == null ? void 0 : currentTurntable.lucky_amount;
  reactExports.useEffect(() => {
    var _a2;
    if ((luckyInfo == null ? void 0 : luckyInfo.turntable_list) && luckyInfo.turntable_list.length && !hadSelect.current) {
      const type = (_a2 = luckyInfo.turntable_list[0]) == null ? void 0 : _a2.type;
      setStyleType(type);
      typeChange == null ? void 0 : typeChange(type);
    }
  }, [luckyInfo]);
  reactExports.useEffect(() => {
    var _a2;
    if (currentTurntable) {
      if ((_a2 = luckyInfo == null ? void 0 : luckyInfo.turntable_list) == null ? void 0 : _a2.length) {
        const prize_list = currentTurntable.bonus_list;
        setPrizeList([...prize_list]);
      }
      luckyWheelRef.current = new LuckyWheel({
        selector: ".pie",
        prizeDeg: 0,
        len: 10,
        onFinished: (index) => {
          setPrizeIndex(index);
          getLuckyLuckyInfo();
          setTimeout(() => {
            loading.current = false;
            PrizeDiaRef.current.open({
              ...prizeResult.current
            });
          }, 1e3);
        }
      });
    }
  }, [styleType]);
  const wheelTypeNames = {
    1: trans("白银转盘"),
    2: trans("黄金转盘"),
    3: trans("钻石转盘")
  };
  const handleChangeStyle = (item) => {
    if (loading.current) return;
    hadSelect.current = true;
    setStyleType(item.type);
    typeChange == null ? void 0 : typeChange(item.type);
  };
  const handleStart = async () => {
    if (!token) {
      useUserInfoStore.setState({ openForLogin: true });
      return;
    }
    if (loading.current) {
      return;
    }
    if (+canIUse < +curUseVal) {
      NoPrizeDiaRef.current.open();
      return;
    }
    loading.current = true;
    prizeResult.current = null;
    const [res, err] = await postPromoLuckyApply({ type: styleType });
    if (err) {
      loading.current = false;
      getLuckyLuckyInfo();
      return;
    } else {
      setPrizeIndex(null);
      luckyWheelRef.current.play();
      prizeResult.current = {
        ...res,
        type: styleType
      };
      setTimeout(() => {
        handleEnd();
      }, 3e3);
    }
  };
  const handleEnd = () => {
    console.log(" prizeResult.current :", prizeResult.current);
    const stopIndex = prizeList.findIndex((item, index) => {
      return item.id === prizeResult.current.id;
    });
    luckyWheelRef.current.stop(stopIndex);
  };
  const l = prizeList == null ? void 0 : prizeList.length;
  const average = 360 / l;
  const half = average / 2;
  const skewMain = -(l - 4) * 90 / l;
  const rightBig = l == 2 ? "50" : "0";
  const heightBig = l <= 3 ? "100" : "50";
  const topBig = l == 3 ? "-50" : "0";
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$5.comp_wheel, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "wheel-container ".concat("wheel_type_" + styleType), children: [
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "out-wheel", children: [
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "lucky_div_wrap", children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "pie", children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "luckBg", children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "luckWhellBg", children: [
            /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "luckWhellBgMain rotateStyle", children: prizeList.map((item, i) => {
              const bigAge = l > 2 ? i * 360 / l : "0";
              return /* @__PURE__ */ jsxRuntimeExports.jsx(
                "div",
                {
                  className: "luckWhellSector ".concat(prizeIndex === i ? "slice_active" : ""),
                  style: {
                    transform: "rotate(".concat(bigAge, "deg) skewY(").concat(skewMain, "deg)"),
                    right: "".concat(rightBig * i, "%"),
                    height: "".concat(heightBig, "%"),
                    top: "".concat(topBig, "%"),
                    width: "".concat(l == 1 ? 100 : 50, "%")
                  }
                },
                i
              );
            }) }),
            /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "wheel_main", children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "prize_list rotateStyle", children: prizeList.map((item, i) => {
              const angle = -(i * average + half);
              return /* @__PURE__ */ jsxRuntimeExports.jsxs(
                "div",
                {
                  className: "prize_item",
                  style: {
                    WebkitTransform: "rotate(".concat(-angle, "deg)"),
                    transform: "rotate(".concat(-angle, "deg)"),
                    width: "".concat(100 / l * 2, "%"),
                    marginLeft: "-".concat(100 / l, "%"),
                    fontSize: "".concat(58 - l * 2, "rem")
                  },
                  children: [
                    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { style: { fontSize: "30rem" }, children: /\./.test(item.bonus_amount.toString()) ? customToFixed(item.bonus_amount) : item.bonus_amount }),
                    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "prize_item_img", children: /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { src: "/dm-wheel/dm_coin_".concat(item.icon || 1, ".webp"), className: "prize_img" }) }),
                    prizeIndex === i && /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { src: "/dm-wheel/dm_wheel_active.webp", className: "slice_active_bg" })
                  ]
                },
                i
              );
            }) }) })
          ] }) }) }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "arrow", onClick: handleStart, children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "arrow_text", children: trans("开始") }) })
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "kedu" })
      ] }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "wheel_type_select", children: luckyInfo.turntable_list.map((item, i) => {
        return /* @__PURE__ */ jsxRuntimeExports.jsxs(
          "div",
          {
            className: "wheel_type_item ".concat(styleType === item.type ? "wheel_type_item_active" : "", " button"),
            onClick: (e) => {
              handleChangeStyle(item);
            },
            children: [
              /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { className: "type_name", children: [
                wheelTypeNames[item.type],
                " "
              ] }),
              /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { className: "type_amout", children: [
                trans("幸运值"),
                item.lucky_amount
              ] })
            ]
          },
          i
        );
      }) })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsx(PrizeDialog, { event: PrizeDiaRef }),
    /* @__PURE__ */ jsxRuntimeExports.jsx(NoPrizeDialog, { event: NoPrizeDiaRef })
  ] });
}
const svg_theme_fill_color$4 = "_svg_theme_fill_color_djxcy_3";
const wheel_tabs = "_wheel_tabs_djxcy_55";
const wheel_tabItem = "_wheel_tabItem_djxcy_63";
const wheel_tabs_active = "_wheel_tabs_active_djxcy_70";
const css$4 = {
  svg_theme_fill_color: svg_theme_fill_color$4,
  wheel_tabs,
  wheel_tabItem,
  wheel_tabs_active
};
const WheelTabs = (props) => {
  var _a;
  return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$4.wheel_tabs, children: (_a = props.options) == null ? void 0 : _a.map((tab, index) => {
    return /* @__PURE__ */ jsxRuntimeExports.jsx(
      "div",
      {
        className: joinClass(css$4.wheel_tabItem, props.value === tab.value ? css$4.wheel_tabs_active : ""),
        onClick: () => {
          props.onChange(tab.value);
        },
        children: tab.label
      },
      index
    );
  }) });
};
const svg_theme_fill_color$3 = "_svg_theme_fill_color_vsvkf_3";
const wheel_rules = "_wheel_rules_vsvkf_55";
const rules_title = "_rules_title_vsvkf_59";
const rule_context = "_rule_context_vsvkf_66";
const css$3 = {
  svg_theme_fill_color: svg_theme_fill_color$3,
  wheel_rules,
  rules_title,
  rule_context
};
const WheelRule = (props) => {
  var _a;
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$3.wheel_rules, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$3.rules_title, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("strong", { children: [
      trans("活动说明"),
      ":"
    ] }) }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$3.rule_context, children: (_a = props.rules) == null ? void 0 : _a.map((item, idx) => {
      return /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { children: [
        item.content,
        /* @__PURE__ */ jsxRuntimeExports.jsx("br", {})
      ] }, idx);
    }) })
  ] });
};
const svg_theme_fill_color$2 = "_svg_theme_fill_color_1v2wt_3";
const HelperList = "_HelperList_1v2wt_55";
const helper_item = "_helper_item_1v2wt_74";
const avatar$1 = "_avatar_1v2wt_203";
const type_name = "_type_name_1v2wt_207";
const left$1 = "_left_1v2wt_324";
const time$1 = "_time_1v2wt_328";
const helper_amount = "_helper_amount_1v2wt_449";
const num = "_num_1v2wt_453";
const empty_container = "_empty_container_1v2wt_573";
const empty = "_empty_1v2wt_59";
const emptyText = "_emptyText_1v2wt_592";
const css$2 = {
  svg_theme_fill_color: svg_theme_fill_color$2,
  HelperList,
  helper_item,
  avatar: avatar$1,
  type_name,
  left: left$1,
  time: time$1,
  helper_amount,
  num,
  empty_container,
  empty,
  emptyText
};
const DetailList = ({ data }) => {
  const { wheelInfo, theme } = useUserInfoStore();
  reactExports.useMemo(() => (wheelInfo == null ? void 0 : wheelInfo.child_list) || [], [wheelInfo]);
  const wheelTypeNames = {
    1: trans("白银转盘"),
    2: trans("黄金转盘"),
    3: trans("钻石转盘")
  };
  const renderHelpers = () => {
    return data.map((item, index) => {
      var _a;
      return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$2.helper_item, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$2.left, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { className: css$2.avatar, src: "/dm-wheel/dm_log_".concat(item.type, ".webp") }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$2.time, children: customFormatTimer(item.time, "MM/DD/YYYY HH:mm:ss") }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$2.type_name, children: wheelTypeNames[item.type] })
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$2.helper_amount, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { className: css$2.num, children: [
          "+  ",
          customToFixed(item.amount, /\./.test((_a = item.amount) == null ? void 0 : _a.toString()) ? 2 : 0)
        ] }) })
      ] }, index);
    });
  };
  return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$2.HelperList, children: data.length ? renderHelpers() : /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$2.empty, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { src: emptyImg[theme] || "/home/icons/empty_icon.webp" }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("p", { className: css$2.emptyText, children: trans("没有记录") })
  ] }) });
};
const svg_theme_fill_color$1 = "_svg_theme_fill_color_1rc22_3";
const bgColor = "_bgColor_1rc22_55";
const lightBg = "_lightBg_1rc22_176";
const lightBg2 = "_lightBg2_1rc22_291";
const withdrawal = "_withdrawal_1rc22_291";
const withdrawal_item = "_withdrawal_item_1rc22_291";
const borderColor1 = "_borderColor1_1rc22_409";
const tabItemActive = "_tabItemActive_1rc22_521";
const yellow = "_yellow_1rc22_677";
const amount = "_amount_1rc22_677";
const total = "_total_1rc22_677";
const title = "_title_1rc22_795";
const dark = "_dark_1rc22_901";
const time = "_time_1rc22_901";
const name = "_name_1rc22_901";
const lightColor2 = "_lightColor2_1rc22_1016";
const shareButton = "_shareButton_1rc22_1095";
const lightColor3 = "_lightColor3_1rc22_1310";
const lightColor4 = "_lightColor4_1rc22_1422";
const wht_scroll_box = "_wht_scroll_box_1rc22_1542";
const trans_box = "_trans_box_1rc22_1547";
const shakeList = "_shakeList_1rc22_1";
const left = "_left_1rc22_1559";
const avatar = "_avatar_1rc22_1563";
const css$1 = {
  svg_theme_fill_color: svg_theme_fill_color$1,
  bgColor,
  lightBg,
  lightBg2,
  withdrawal,
  withdrawal_item,
  borderColor1,
  tabItemActive,
  yellow,
  amount,
  total,
  title,
  dark,
  time,
  name,
  lightColor2,
  shareButton,
  lightColor3,
  lightColor4,
  wht_scroll_box,
  trans_box,
  shakeList,
  left,
  avatar
};
const GeneralReport = ({ show, list, wheelType }) => {
  const glist = reactExports.useMemo(() => list.filter((o) => o.type === wheelType), [list, wheelType]);
  return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { style: { display: show ? "block" : "none" }, children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$1.withdrawal, children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$1.wht_scroll_box, children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$1.trans_box, children: glist == null ? void 0 : glist.map((item, index) => {
    return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.withdrawal_item, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.left, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { className: css$1.avatar, src: "/dm-wheel/dm_log_".concat(item.type, ".webp") }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$1.name, children: item.username }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$1.time, children: customFormatTimer(item.time, "MM/DD/YYYY HH:mm:ss") })
      ] }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$1.amount, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { className: css$1.total, children: [
        "+  ",
        customToFixed(item.bonus_amount)
      ] }) })
    ] }, index);
  }) }) }) }) });
};
const GeneralReport$1 = reactExports.memo(GeneralReport);
const svg_theme_fill_color = "_svg_theme_fill_color_132im_3";
const DetailDialog$1 = "_DetailDialog_132im_55";
const Detail_title = "_Detail_title_132im_299";
const text_line = "_text_line_132im_421";
const close_icon = "_close_icon_132im_512";
const css = {
  svg_theme_fill_color,
  DetailDialog: DetailDialog$1,
  Detail_title,
  text_line,
  close_icon
};
const DetailDialog = ({ event, data = {} }) => {
  useTranslation();
  useUserInfoStore();
  const [state, SetState] = reactExports.useState(false);
  useNavigate();
  const [width, setWidth] = reactExports.useState(0);
  reactExports.useImperativeHandle(event, () => ({
    open: () => {
      setWidth(0);
      SetState(true);
    }
  }));
  return /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, { children: /* @__PURE__ */ jsxRuntimeExports.jsx(Modal, { isOpen: state, background: "rgba(0,0,0,0.7)", closeByClickOut: false, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.DetailDialog, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.Detail_title, children: trans("详情") }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.text_line, children: [
      trans("活动期间内总幸运值"),
      ": ",
      customToFixed(data.lucky_amount, 0)
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.text_line, children: [
      trans("已使用幸运值"),
      ": ",
      customToFixed(data.used_amount, 0)
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.text_line, children: [
      trans("已过期幸运值"),
      ": ",
      customToFixed(data.expiry_amount, 0),
      " "
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.text_line, children: [
      trans("可用幸运值"),
      ": ",
      customToFixed(data.can_use_amount, 0),
      " "
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsx(
      CloseIconInLogin,
      {
        className: css.close_icon,
        onClick: (e) => {
          SetState(false);
        }
      }
    )
  ] }) }) });
};
const DmWheel = ({ isDia = false, isShowFooter = true, onClose = () => {
}, noShowToday = () => {
}, neverShow = () => {
} }) => {
  var _a, _b, _c;
  const { search } = useLocation();
  const params = new URLSearchParams(search);
  const d = params.get("isDia");
  if (d === "1") {
    isDia = true;
  }
  const { token } = useUserInfoStore((state2) => state2);
  useGameStore();
  const [state, setState] = useSetState({
    curTab: "1",
    noNext: false,
    noToday: false,
    wheelType: 0,
    luckyInfo: {},
    tabOptions: [
      {
        label: trans("中奖播报"),
        value: "1"
      },
      {
        label: trans("我的记录"),
        value: "2"
      }
    ]
  });
  const { isApp } = useFlutterApp();
  const { luckyInfo } = state;
  const getLuckyLuckyInfo = async () => {
    var _a2, _b2;
    const [res, err] = await getPromoLuckyDetail();
    console.log("res lucky :", res);
    setState({
      luckyInfo: {
        lucky_detail: res.lucky_detail,
        //幸运值详情
        turntable_list: ((_a2 = res == null ? void 0 : res.rules) == null ? void 0 : _a2.turntable_list) || [],
        //奖品列表
        prize_list: res.prize_list,
        //我的记录
        other_list: res.other_list,
        // 中奖播报
        rules: ((_b2 = res.rules) == null ? void 0 : _b2.promo_rule_json) || [],
        //规则
        title: res.title
      }
    });
  };
  const detailDiaRef = reactExports.useRef(null);
  const { curTab } = state;
  const navigate = useNavigate();
  const changeTab = (value) => {
    setState({ curTab: value });
  };
  const wheelTypeChange = (type) => {
    setState({ wheelType: type });
  };
  reactExports.useEffect(() => {
    if (isDia && state.tabOptions.length < 3) {
      setState({ curTab: "0", tabOptions: [{ label: trans("规则"), value: "0" }, ...state.tabOptions] });
    }
    getLuckyLuckyInfo();
  }, [token]);
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$8.DM_wheel, children: [
    !isDia && /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$8.wheelHeader, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$8.wheelLeftArrow, children: /* @__PURE__ */ jsxRuntimeExports.jsx(
        "div",
        {
          onClick: () => {
            if (isApp) {
              sendMessage("onChatSystemMessage", "back");
              return;
            }
            navigate(-1);
          },
          children: /* @__PURE__ */ jsxRuntimeExports.jsx(ArrowLeftInMineIconTwo, {})
        }
      ) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$8.wheelTitle, children: luckyInfo.title }),
      /* @__PURE__ */ jsxRuntimeExports.jsx(
        "span",
        {
          className: joinClass(css$8.wheelRecord, "button"),
          onClick: () => {
            if (isApp) {
              sendMessage("onChatSystemMessage", "alreadyGetbonus");
              return;
            }
            navigate("/alreadyGetbonus");
          },
          children: trans("领取记录")
        }
      )
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: isDia ? "" : css$8.DM_wheel_page, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$8.luckyInfo_box, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$8.wheel_pop_header, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$8.header_line, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$8.header_line_text, children: [
            trans("当前幸运值"),
            " ",
            /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$8.total, children: customToFixed((_a = luckyInfo.lucky_detail) == null ? void 0 : _a.can_use_amount, 0) })
          ] }) }),
          /* @__PURE__ */ jsxRuntimeExports.jsx(
            "div",
            {
              className: "".concat(css$8.xq_button, "  button"),
              onClick: () => {
                detailDiaRef.current.open();
              },
              children: trans("详情")
            }
          )
        ] }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$8.wheel_container, children: ((_b = luckyInfo.turntable_list) == null ? void 0 : _b.length) > 0 && /* @__PURE__ */ jsxRuntimeExports.jsx(Wheel, { luckyInfo, getLuckyLuckyInfo, typeChange: wheelTypeChange }) })
      ] }),
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$8.tabsContainer, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx(WheelTabs, { options: state.tabOptions, onChange: changeTab, value: curTab }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$8.tab_view, children: [
          curTab === "0" && /* @__PURE__ */ jsxRuntimeExports.jsx(WheelRule, { rules: (luckyInfo == null ? void 0 : luckyInfo.rules) || [] }),
          /* @__PURE__ */ jsxRuntimeExports.jsx(GeneralReport$1, { show: curTab === "1", list: (_c = luckyInfo.other_list) != null ? _c : [], wheelType: state.wheelType }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { style: { display: curTab === "2" ? "block" : "none" }, children: /* @__PURE__ */ jsxRuntimeExports.jsx(DetailList, { data: luckyInfo.prize_list || [] }) })
        ] })
      ] }),
      !isDia && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$8.rules_box, children: /* @__PURE__ */ jsxRuntimeExports.jsx(WheelRule, { rules: (luckyInfo == null ? void 0 : luckyInfo.rules) || [] }) }),
      !isDia && /* @__PURE__ */ jsxRuntimeExports.jsx(ActivityBottom, {}),
      /* @__PURE__ */ jsxRuntimeExports.jsx(DetailDialog, { event: detailDiaRef, data: luckyInfo.lucky_detail })
    ] })
  ] });
};
export {
  DmWheel as default
};
