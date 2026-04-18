import { y as useNavigate, ar as useMessageStore, aA as useNoticStore, V as useTranslation, q as useUserInfoStore, j as jsxRuntimeExports, a as joinClass, C as Cache, ct as readUserMessage, I as Image, a9 as useReactive, u as useAsyncEffect, ad as getNoticeList, r as reactExports, H as Message$1, a6 as desktopOpen, af as getMemberCustomerList, aq as events, cu as withdrawFeedback, cv as listFeedback, cw as addFeedback, cx as getMessageList, cy as deleteUserMessage } from "./comps-B8ShbmG--2024_12_14_18_4.js";
import { dm as MessageSetIcon, dn as SelectIcon, dp as DeleteIcon, bV as InputClearIcon, A as ArrowLeftInMineIcon, dq as MessageVolumIcon, R as RectCopyIcon } from "./icons-Cdaou_E3-2024_12_14_18_4.js";
import { E as Empty, a as customFormatTimer, c as clipboardExports } from "./index-BcpFmBv5-2024_12_14_18_4.js";
import { B as Button } from "./index-CGmeRIXa-2024_12_14_18_4.js";
import { L as LoadMore } from "./index-4Dep-BeX-2024_12_14_18_4.js";
import { U as Upload } from "./index-CwSAIdOg-2024_12_14_18_4.js";
import "./s3--cfsTSzQ-2024_12_14_18_4.js";
const svg_theme_fill_color$5 = "_svg_theme_fill_color_t65c5_3";
const messageBox$1 = "_messageBox_t65c5_55";
const contentBox$4 = "_contentBox_t65c5_63";
const inSetBox = "_inSetBox_t65c5_68";
const showNotice = "_showNotice_t65c5_75";
const showMessage = "_showMessage_t65c5_78";
const showFeedback = "_showFeedback_t65c5_81";
const css$5 = {
  svg_theme_fill_color: svg_theme_fill_color$5,
  messageBox: messageBox$1,
  contentBox: contentBox$4,
  inSetBox,
  showNotice,
  showMessage,
  showFeedback
};
const svg_theme_fill_color$4 = "_svg_theme_fill_color_130n9_3";
const headerBox = "_headerBox_130n9_55";
const leftBox$2 = "_leftBox_130n9_61";
const backArrowBox = "_backArrowBox_130n9_67";
const ico = "_ico_130n9_78";
const tabsBox = "_tabsBox_130n9_89";
const active$1 = "_active_130n9_118";
const numBox = "_numBox_130n9_123";
const rightBox$2 = "_rightBox_130n9_149";
const icon = "_icon_130n9_168";
const editBox = "_editBox_130n9_189";
const right_svg = "_right_svg_130n9_192";
const selectAll = "_selectAll_130n9_203";
const cancel = "_cancel_130n9_211";
const noCheck$1 = "_noCheck_130n9_223";
const css$4 = {
  svg_theme_fill_color: svg_theme_fill_color$4,
  headerBox,
  leftBox: leftBox$2,
  backArrowBox,
  ico,
  tabsBox,
  active: active$1,
  numBox,
  rightBox: rightBox$2,
  icon,
  editBox,
  right_svg,
  selectAll,
  "delete": "_delete_130n9_210",
  cancel,
  noCheck: noCheck$1
};
const Header = ({
  type,
  isEdit,
  selectList,
  messageList,
  onSelectAll,
  onDeleteMessage,
  onSelectType = () => {
  },
  updateIsEdit
}) => {
  useNavigate();
  const { num: num2 } = useMessageStore();
  const { num: noticNum } = useNoticStore();
  const { t } = useTranslation();
  const { token } = useUserInfoStore();
  let numStr = noticNum ? noticNum : "";
  if (numStr > 99) numStr = "99+";
  return /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$4.headerBox, children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$4.leftBox, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$4.tabsBox, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx(
        "div",
        {
          className: type === "service" ? css$4.active : "",
          onClick: () => {
            onSelectType("service");
          },
          children: t("Contact us")
        }
      ),
      /* @__PURE__ */ jsxRuntimeExports.jsxs(
        "div",
        {
          className: type === "message" ? css$4.active : "",
          onClick: () => {
            onSelectType("message");
          },
          children: [
            t("消息"),
            num2 ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$4.numBox, children: num2 }) : null
          ]
        }
      ),
      /* @__PURE__ */ jsxRuntimeExports.jsxs(
        "div",
        {
          className: type === "notice" ? css$4.active : "",
          onClick: () => {
            onSelectType("notice");
          },
          children: [
            t("通知"),
            token && numStr ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$4.numBox, children: numStr }) : null
          ]
        }
      ),
      /* @__PURE__ */ jsxRuntimeExports.jsx(
        "div",
        {
          className: type === "feedback" ? css$4.active : "",
          onClick: () => {
            onSelectType("feedback");
          },
          children: t("Suggestion Bonus")
        }
      )
    ] }) }) }),
    type === "message" ? /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$4.rightBox, onClick: () => updateIsEdit(true), children: [
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$4.selectAll, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx(MessageSetIcon, { className: css$4.icon }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: t("管理") })
      ] }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$4.editBox, children: isEdit ? /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$4.selectAll, onClick: (e) => {
          onSelectAll();
        }, children: [
          selectList.length === messageList.length ? /* @__PURE__ */ jsxRuntimeExports.jsx(SelectIcon, {}) : /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$4.noCheck }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: t("全选") })
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$4.delete, onClick: () => onDeleteMessage(), children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx(DeleteIcon, { className: css$4.icon }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: t("删除") })
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$4.cancel, onClick: (e) => {
          e.stopPropagation();
          updateIsEdit(false);
        }, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx(InputClearIcon, {}),
          /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: t("取消") })
        ] })
      ] }) : /* @__PURE__ */ jsxRuntimeExports.jsx(ArrowLeftInMineIcon, { className: css$4.right_svg }) })
    ] }) : null
  ] });
};
const svg_theme_fill_color$3 = "_svg_theme_fill_color_t38fu_3";
const messageBox = "_messageBox_t38fu_55";
const contentBox$3 = "_contentBox_t38fu_59";
const emptyBox$1 = "_emptyBox_t38fu_65";
const itemBox$1 = "_itemBox_t38fu_68";
const alreadySelect = "_alreadySelect_t38fu_81";
const leftBox$1 = "_leftBox_t38fu_87";
const iconBox$1 = "_iconBox_t38fu_92";
const readSpan = "_readSpan_t38fu_99";
const noMargin = "_noMargin_t38fu_113";
const noCheck = "_noCheck_t38fu_116";
const selectIcon = "_selectIcon_t38fu_123";
const infoBox$1 = "_infoBox_t38fu_133";
const title$3 = "_title_t38fu_138";
const time$1 = "_time_t38fu_144";
const readInfoBox = "_readInfoBox_t38fu_151";
const rightBox$1 = "_rightBox_t38fu_157";
const text$2 = "_text_t38fu_162";
const arrowIcon$2 = "_arrowIcon_t38fu_167";
const is_read = "_is_read_t38fu_181";
const css$3 = {
  svg_theme_fill_color: svg_theme_fill_color$3,
  messageBox,
  contentBox: contentBox$3,
  emptyBox: emptyBox$1,
  itemBox: itemBox$1,
  alreadySelect,
  leftBox: leftBox$1,
  iconBox: iconBox$1,
  readSpan,
  noMargin,
  noCheck,
  selectIcon,
  infoBox: infoBox$1,
  title: title$3,
  time: time$1,
  readInfoBox,
  rightBox: rightBox$1,
  text: text$2,
  arrowIcon: arrowIcon$2,
  is_read
};
const listMaps = {
  black: "/message/no_read_message_icon_black.webp",
  blue: "/message/no_read_message_icon_blue.webp",
  whiteGreen: "/message/no_read_message_icon_white_green.webp",
  purple: "/message/no_read_message_icon_purple.webp",
  oilyGreen: "/message/no_read_message_icon_oilyGreen.webp",
  whiteRed: "/message/no_read_message_icon_whiteRed.webp",
  versaceYellow: "/message/no_read_message_icon_versaceYellow.webp",
  lancomePeach: "/message/no_read_message_icon_lancomePeach.webp",
  hermesOrange: "/message/no_read_message_icon_hermes_orange.webp",
  whiteBlue: "/message/no_read_message_icon_whiteBlue.webp",
  whiteYellow: "/message/no_read_message_icon_whiteYellow.webp",
  lightBrown: "/message/no_read_message_icon_lightBrown.webp",
  whiteOrange: "/message/no_read_message_icon_whiteOrange.webp",
  furlaBlue: "/message/no_read_message_icon_furlaBlue.webp",
  whitePink: "/message/no_read_message_icon_whitePink.webp",
  bvGreen: "/message/no_read_message_icon_bvGreen.webp",
  whiteBrown: "/message/no_read_message_icon_whiteBrown.webp",
  AnnaSuiPurple: "/message/no_read_message_icon_AnnaSuiPurple.webp",
  whitePurple: "/message/no_read_message_icon_whitePurple.webp",
  whiteDarkGreen: "/message/no_read_message_icon_whiteDarkGreen.webp",
  burgundyRed: "/message/no_read_message_icon_burgundyRed.webp",
  sk2: "/message/no_read_message_icon_sk2.webp",
  greenGold: "/message/no_read_message_icon_greenGold.webp",
  whiteRedGucci: "/message/no_read_message_icon_whiteRedGucci.webp",
  whiteBrownLauren: "/message/no_read_message_icon_whiteBrownLauren.webp",
  embraerBlue: "/message/no_read_message_icon_embraerBlue.webp",
  bvlgariBrown: "/message/no_read_message_icon_bvlgariBrown.webp",
  elsaPink: "/message/no_read_message_icon_elsaPink.webp",
  whiteBlack: "/message/no_read_message_icon_whiteBlack.webp",
  whiteBlueFendi: "/message/no_read_message_icon_whiteBlueFendi.webp",
  venetaGrey: "/message/no_read_message_icon_venetaGrey.webp",
  martinPurple: "/message/no_read_message_icon_martinPurple.webp",
  whiteGreenCindy: "/message/no_read_message_icon_whiteGreenCindy.webp",
  microsoftRed: "/message/no_read_message_icon_microsoftRed.webp",
  celineBrownWhite: "/message/no_read_message_icon_brownWhite.webp",
  ferrariBlack: "/message/no_read_message_icon_ferrariBlack.webp",
  burberryBlueWhite: "/message/no_read_message_icon_burberryBlueWhite.webp",
  diorPurpleWhite: "/home/icons/message_diorpurplewhite2.webp",
  almondYellow: "/home/icons/message_almondyellow.webp",
  montblancBluewhite: "/home/icons/message_montblancBluewhite.webp",
  windbellGreen: "/message/no_read_message_icon_windbellGreen.webp",
  bondiBlue: "/message/no_read_message_icon_bondiBlue.webp",
  wasabiYellowWhite: "/message/no_read_message_icon_wasabiYellowWhite1.webp",
  gemRed: "/message/no_read_message_icon_gemRed.webp",
  coralPurpleWhite: "/message/no_read_message_icon_coralPurpleWhite.webp",
  elegantPinkWhite: "/message/no_read_message_icon_elegantPinkWhite.webp",
  lemonGreenWhite: "/message/no_read_message_icon_lemonGreenWhite.webp",
  caramelYelloWhite: "/message/no_read_message_icon_caramelYelloWhite.webp",
  camelliaRedWhite: "/message/no_read_message_icon_camelliaRedWhite.webp",
  coralPurple: "/message/no_read_message_icon_coralPurple.webp",
}; 
const listReadMaps = {
  black: "/message/read_message_icon_black.webp",
  blue: "/message/read_message_icon_blue.webp",
  whiteGreen: "/message/read_message_icon_whiteGreen.webp",
  purple: "/message/read_message_icon_purple.webp",
  oilyGreen: "/message/read_message_icon_oilyGreen.webp",
  whiteRed: "/message/read_message_icon_whiteRed.webp",
  versaceYellow: "/message/read_message_icon_versaceYellow.webp",
  lancomePeach: "/message/read_message_icon_lancomePeach.webp",
  hermesOrange: "/message/read_message_icon_hermes_orange.webp",
  whiteBlue: "/message/read_message_icon_whiteBlue.webp",
  whiteYellow: "/message/read_message_icon_whiteYellow.webp",
  lightBrown: "/message/read_message_icon_lightBrown.webp",
  whiteOrange: "/message/read_message_icon_whiteOrange.webp",
  furlaBlue: "/message/read_message_icon_furlaBlue.webp",
  whitePink: "/message/read_message_icon_whitePink.webp",
  bvGreen: "/message/read_message_icon_bvGreen.webp",
  whiteBrown: "/message/read_message_icon_whiteBrown.webp",
  AnnaSuiPurple: "/message/read_message_icon_AnnaSuiPurple.webp",
  whitePurple: "/message/read_message_icon_whitePurple.webp",
  whiteDarkGreen: "/message/read_message_icon_whiteDarkGreen.webp",
  burgundyRed: "/message/read_message_icon_burgundyRed.webp",
  sk2: "/message/read_message_icon_sk2.webp",
  greenGold: "/message/read_message_icon_greenGold.webp",
  whiteRedGucci: "/message/read_message_icon_whiteRedGucci.webp",
  whiteBrownLauren: "/message/read_message_icon_whiteBrownLauren.webp",
  embraerBlue: "/message/read_message_icon_embraerBlue.webp",
  bvlgariBrown: "/message/read_message_icon_bvlgariBrown.webp",
  elsaPink: "/message/read_message_icon_elsaPink.webp",
  whiteBlack: "/message/read_message_icon_whiteBlack.webp",
  whiteBlueFendi: "/message/read_message_icon_whiteBlueFendi.webp",
  venetaGrey: "/message/read_message_icon_venetaGrey.webp",
  martinPurple: "/message/read_message_icon_martinPurple.webp",
  whiteGreenCindy: "/message/read_message_icon_whiteGreenCindy.webp",
  microsoftRed: "/message/read_message_icon_microsoftRed.webp",
  ferrariBlack: "/message/read_message_icon_ferrariBlack.webp",
  burberryBlueWhite: "/message/read_message_icon_burberryBlueWhite.webp",
  diorPurpleWhite: "/message/read_message_icon_diorpurplewhite.webp",
  almondYellow: "/message/read_message_icon_almondyellow.webp",
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
  coralPurple: "/message/read_message_icon_coralPurple.webp",
}; 
const MessageBox = ({
  messageList,
  isEdit,
  children,
  selectList = [],
  onSelect
}) => {
  const { getMessageNumBySotre } = useMessageStore((state) => state);
  const { theme } = useUserInfoStore();
  const navigate = useNavigate();
  return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$3.messageBox, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$3.contentBox, children: [
    messageList.length === 0 ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$3.emptyBox, children: /* @__PURE__ */ jsxRuntimeExports.jsx(Empty, {}) }) : messageList.map((message, idx) => {
      const isSelect = selectList.indexOf(message.id) > -1;
      return /* @__PURE__ */ jsxRuntimeExports.jsxs(
        "div",
        {
          className: joinClass(
            css$3.itemBox,
            isSelect && isEdit ? css$3.alreadySelect : "",
            message.is_read === 0 ? css$3.is_read : ""
          ),
          onClick: async () => {
            if (isEdit) {
              onSelect(message.id);
              return;
            }
            Cache.set("messageDetail", {
              ...message,
              page_type: "message"
            });
            navigate("/messageDetail");
            if (message.is_read === 0) {
              await readUserMessage({ id: message.id });
              getMessageNumBySotre();
            }
          },
          children: [
            /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$3.leftBox, children: [
              isEdit ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$3.iconBox, css$3.noMargin), children: isSelect ? /* @__PURE__ */ jsxRuntimeExports.jsx(SelectIcon, { className: css$3.selectIcon }) : /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$3.noCheck }) }) : null,
              /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$3.iconBox, children: message.is_read === 0 ? /* @__PURE__ */ jsxRuntimeExports.jsx(
                Image,
                {
                  src: listMaps[theme] || "/message/no_read_message_icon.webp"
                }
              ) : /* @__PURE__ */ jsxRuntimeExports.jsx(
                Image,
                {
                  src: listReadMaps[theme] || listMaps[theme] || "/message/no_read_message_icon.webp"
                }
              ) }),
              /* @__PURE__ */ jsxRuntimeExports.jsxs(
                "div",
                {
                  className: "".concat(css$3.infoBox, " ").concat(message.is_read === 0 ? "" : css$3.readInfoBox),
                  children: [
                    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$3.title, children: message.title }),
                    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$3.time, children: customFormatTimer(message.send_at) })
                  ]
                }
              )
            ] }),
            /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$3.rightBox, children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$3.arrowIcon, children: /* @__PURE__ */ jsxRuntimeExports.jsx(ArrowLeftInMineIcon, {}) }) })
          ]
        },
        idx
      );
    }),
    children
  ] }) });
};
const svg_theme_fill_color$2 = "_svg_theme_fill_color_14b6c_3";
const noticeBox = "_noticeBox_14b6c_55";
const contentBox$2 = "_contentBox_14b6c_59";
const emptyBox = "_emptyBox_14b6c_64";
const itemBox = "_itemBox_14b6c_67";
const leftBox = "_leftBox_14b6c_83";
const iconBox = "_iconBox_14b6c_88";
const timeColor = "_timeColor_14b6c_106";
const titleColor = "_titleColor_14b6c_109";
const infoBox = "_infoBox_14b6c_112";
const title$2 = "_title_14b6c_109";
const time = "_time_14b6c_106";
const rightBox = "_rightBox_14b6c_135";
const text$1 = "_text_14b6c_140";
const arrowIcon$1 = "_arrowIcon_14b6c_145";
const css$2 = {
  svg_theme_fill_color: svg_theme_fill_color$2,
  noticeBox,
  contentBox: contentBox$2,
  emptyBox,
  itemBox,
  leftBox,
  iconBox,
  timeColor,
  titleColor,
  infoBox,
  title: title$2,
  time,
  rightBox,
  text: text$1,
  arrowIcon: arrowIcon$1
};
const NoticeBox = ({ type }) => {
  const navigate = useNavigate();
  const state = useReactive({
    list: []
  });
  useAsyncEffect(async () => {
    if (type !== "notice") return;
    const [res] = await getNoticeList();
    if (res && res.d && Array.isArray(res.d) && res.d.length > 0) {
      state.list = res.d;
    }
  }, [type]);
  const { list: list2 } = state;
  const { token } = useUserInfoStore();
  return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$2.noticeBox, children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$2.contentBox, children: list2.length === 0 ? /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$2.emptyBox, children: [
    " ",
    /* @__PURE__ */ jsxRuntimeExports.jsx(Empty, {})
  ] }) : list2.map((message, idx) => {
    return /* @__PURE__ */ jsxRuntimeExports.jsxs(
      "div",
      {
        className: css$2.itemBox,
        onClick: async () => {
          Cache.set("messageDetail", {
            ...message,
            page_type: "notice"
          });
          navigate("/messageDetail");
        },
        children: [
          /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$2.leftBox, children: [
            /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$2.iconBox, children: /* @__PURE__ */ jsxRuntimeExports.jsx(MessageVolumIcon, {}) }),
            /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$2.infoBox, children: [
              /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css$2.title, message.is_read || !token ? css$2.timeColor : css$2.titleColor), children: message.title }),
              /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$2.time, children: customFormatTimer(message.created_at) })
            ] })
          ] }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$2.rightBox, children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$2.arrowIcon, children: /* @__PURE__ */ jsxRuntimeExports.jsx(ArrowLeftInMineIcon, {}) }) })
        ]
      },
      idx
    );
  }) }) });
};
const svg_theme_fill_color$1 = "_svg_theme_fill_color_1oahv_3";
const ServiceBox$1 = "_ServiceBox_1oahv_55";
const contentBox$1 = "_contentBox_1oahv_60";
const online = "_online_1oahv_63";
const left$1 = "_left_1oahv_70";
const right$1 = "_right_1oahv_80";
const title$1 = "_title_1oahv_83";
const desc = "_desc_1oahv_88";
const btn$1 = "_btn_1oahv_101";
const telegram = "_telegram_1oahv_108";
const content = "_content_1oahv_60";
const img = "_img_1oahv_124";
const telegramContent = "_telegramContent_1oahv_139";
const middle = "_middle_1oahv_157";
const css$1 = {
  svg_theme_fill_color: svg_theme_fill_color$1,
  ServiceBox: ServiceBox$1,
  contentBox: contentBox$1,
  online,
  left: left$1,
  right: right$1,
  title: title$1,
  desc,
  btn: btn$1,
  telegram,
  content,
  img,
  telegramContent,
  middle
};
const ServiceBox = () => {
  const [customerList, setCustomerList] = reactExports.useState([]);
  const [onlineService, setOnlineService] = reactExports.useState({});
  const { t, i18n } = useTranslation();
  const getOnlineService = async () => {
    const [res, error] = await getMemberCustomerList({
      flag: "1"
    });
    if (error) return;
    if (res && res.length > 0) {
      setOnlineService(res[0]);
    }
  };
  const getCustomerList = async () => {
    const [res, error] = await getMemberCustomerList({
      flag: "3"
    });
    if (error) return;
    setCustomerList(res || []);
  };
  const onlineObj = reactExports.useMemo(() => {
    if (onlineService.items && onlineService.items.length > 0) {
      return {
        im: onlineService.items[0].im,
        link: onlineService.items[0].link,
        name: onlineService.items[0].name,
        remark: onlineService.items[0].remark,
        status: onlineService.items[0].status
      };
    }
    return {};
  }, [onlineService]);
  reactExports.useEffect(() => {
    getCustomerList();
    getOnlineService();
  }, []);
  return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$1.ServiceBox, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.contentBox, children: [
    onlineObj.im && onlineObj.status == 2 && /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.online, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { className: css$1.left, src: onlineObj.im, remote: true, isGame: true }),
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.right, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$1.title, children: onlineObj.name }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$1.desc, children: onlineObj.remark }),
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          Button,
          {
            className: css$1.btn,
            onClick: () => {
              if (!onlineObj.link) {
                Message$1.error(t("暂无客服"));
                return;
              }
              if (window.navigator.standalone || window.matchMedia("(display-mode: standalone)").matches) {
                window.location.href = onlineObj.link;
              } else {
                desktopOpen(onlineObj.link);
              }
            },
            children: t("现在联系")
          }
        )
      ] })
    ] }),
    customerList && customerList.map((item, index) => {
      var _a, _b, _c;
      item.items = (_a = item.items) == null ? void 0 : _a.filter((i) => i.status == 2);
      return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
        ((_b = item.items) == null ? void 0 : _b.length) ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$1.telegram, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.content, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { className: css$1.img, src: item.im, remote: true, isGame: true }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: item.title })
        ] }) }) : null,
        ((_c = item.items) == null ? void 0 : _c.length) ? item.items.map((text2, i) => {
          return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.telegramContent, children: [
            /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { className: css$1.left, src: text2.im, remote: true, isGame: true }),
            /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.middle, children: [
              /* @__PURE__ */ jsxRuntimeExports.jsx("p", { children: text2.name }),
              /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: text2.remark })
            ] }),
            /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$1.right, children: /* @__PURE__ */ jsxRuntimeExports.jsx(
              Button,
              {
                className: css$1.btn,
                onClick: () => {
                  desktopOpen(text2.link);
                },
                children: t("现在联系")
              }
            ) })
          ] }, i);
        }) : null
      ] }, index);
    })
  ] }) });
};
const svg_theme_fill_color = "_svg_theme_fill_color_13m6p_3";
const feedback = "_feedback_13m6p_55";
const header = "_header_13m6p_60";
const left = "_left_13m6p_66";
const btn = "_btn_13m6p_69";
const active = "_active_13m6p_85";
const right = "_right_13m6p_90";
const monye = "_monye_13m6p_94";
const pendente = "_pendente_13m6p_105";
const num = "_num_13m6p_108";
const btns = "_btns_13m6p_111";
const btns2 = "_btns2_13m6p_128";
const contentBox = "_contentBox_13m6p_132";
const add = "_add_13m6p_137";
const title = "_title_13m6p_145";
const contentTxt = "_contentTxt_13m6p_152";
const numTxt = "_numTxt_13m6p_175";
const imgHead = "_imgHead_13m6p_181";
const footHead = "_footHead_13m6p_190";
const tips = "_tips_13m6p_195";
const btnBottn = "_btnBottn_13m6p_202";
const listBox = "_listBox_13m6p_219";
const list = "_list_13m6p_219";
const listHead = "_listHead_13m6p_378";
const id = "_id_13m6p_387";
const copyIcon = "_copyIcon_13m6p_390";
const rightAdopted = "_rightAdopted_13m6p_407";
const arrowIcon = "_arrowIcon_13m6p_412";
const listContent = "_listContent_13m6p_430";
const updateTime = "_updateTime_13m6p_433";
const listText = "_listText_13m6p_438";
const listTextReply = "_listTextReply_13m6p_444";
const detailImg = "_detailImg_13m6p_502";
const detailTime = "_detailTime_13m6p_513";
const detailContent = "_detailContent_13m6p_524";
const text = "_text_13m6p_530";
const line = "_line_13m6p_684";
const updatedAt = "_updatedAt_13m6p_777";
const replyContent = "_replyContent_13m6p_1008";
const css = {
  svg_theme_fill_color,
  feedback,
  header,
  left,
  btn,
  active,
  right,
  monye,
  pendente,
  num,
  btns,
  btns2,
  contentBox,
  add,
  title,
  contentTxt,
  numTxt,
  imgHead,
  footHead,
  tips,
  btnBottn,
  listBox,
  list,
  listHead,
  id,
  copyIcon,
  rightAdopted,
  arrowIcon,
  listContent,
  updateTime,
  listText,
  listTextReply,
  detailImg,
  detailTime,
  detailContent,
  text,
  line,
  updatedAt,
  replyContent
};
const Feedback = ({ type }) => {
  useNavigate();
  const { t, i18n } = useTranslation();
  const { theme, info, language } = useUserInfoStore();
  const state = useReactive({
    list: [],
    btnTpye: 1,
    text: "",
    attachment_url: "",
    attachment_type: "",
    detailType: false,
    //详情类型
    itemDetail: {},
    //详情数据
    amount: 0,
    page: 1,
    totle: 0
  });
  const withdraw = async (id2) => {
    const [res] = await withdrawFeedback({ uid: info.uid });
    if (res == 1e3) {
      Message$1.success(t("领取成功"));
      state.amount = 0;
    }
  };
  const handleScroll = () => {
    if (state.list.length < state.totle) {
      state.page++;
    }
  };
  const feedbackList = async () => {
    if (!info) return;
    let params = {
      uid: info.uid,
      //		用户id(必选)
      page: state.page,
      page_size: 10
    };
    const [res] = await listFeedback(params);
    if (res) {
      state.list = [...state.list, ...res.d || []];
      if (state.page == 1) {
        state.amount = res.a || 0;
        state.totle = res.t || 0;
      }
    }
  };
  reactExports.useEffect(() => {
    console.log("=============更新看看");
    if (state.btnTpye == 2) {
      feedbackList();
    }
  }, [state.page]);
  const { list: list2, btnTpye, text: text2, detailType, itemDetail } = state;
  const maxLength = 500;
  const handleChange = (event) => {
    state.text = event.target.value;
  };
  const btnSbumit = async () => {
    let params = {
      uid: info.uid,
      //		用户id(必选)
      username: info.username,
      //		用户名称(必选)
      content: state.text,
      //		string		反馈内容(必选)
      attachment_url: state.attachment_url,
      //	string		上传附件(可选)
      attachment_type: state.attachment_type
      //	string		附件类型(可选) 1:图片 2:视频
      // currency: "", //	string		货币类型(可选)
    };
    if (params.content.trim() == "") {
      return Message$1.error(t("Feedback cannot be empty"));
    }
    const [res] = await addFeedback(params);
    if (res == 1e3) {
      Message$1.success(t("Thank you for your valuable feedback"));
      state.text = "";
      state.attachment_url = "";
      state.attachment_type = "";
      events.emit("closeUpload");
    }
  };
  reactExports.useEffect(() => {
    events.on("uploadSuccess", handleEvent);
    return () => {
      events.off("uploadSuccess", handleEvent);
    };
  }, []);
  const handleEvent = (data) => {
    if (data.type.includes("video")) {
      state.attachment_type = "2";
    } else {
      state.attachment_type = "1";
    }
    state.attachment_url = data.fullPath;
  };
  const btnDetail = (item) => {
    state.detailType = true;
    state.itemDetail = item;
  };
  const videoRef = reactExports.useRef(null);
  const handleVideoClick = () => {
    if (videoRef.current) {
      if (videoRef.current.paused) {
        videoRef.current.play();
      } else {
        videoRef.current.pause();
      }
    }
  };
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.feedback, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.header, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.left, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          "div",
          {
            className: joinClass(css.btn, btnTpye == 1 ? css.active : ""),
            onClick: () => {
              state.btnTpye = 1;
            },
            children: t("Create Feedback")
          }
        ),
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          "div",
          {
            className: joinClass(css.btn, btnTpye == 2 ? css.active : ""),
            onClick: () => {
              state.page = 1;
              state.list = [];
              state.btnTpye = 2;
              state.detailType = false;
              feedbackList();
            },
            children: t("My Feedback")
          }
        )
      ] }),
      btnTpye == 2 ? /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.right, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.monye, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.pendente, children: t("待领取") }),
          /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.num, children: [
            state.amount,
            " "
          ] })
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          "div",
          {
            onClick: () => {
              if (state.amount != 0) withdraw();
            },
            className: joinClass(
              state.amount != 0 ? css.btns2 : "",
              css.btns
            ),
            children: t("Claimi All")
          }
        )
      ] }) : /* @__PURE__ */ jsxRuntimeExports.jsx("div", {})
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.contentBox, children: btnTpye == 1 ? /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.add, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.title, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: t("Feed Content") }),
          /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { children: [
            " (",
            t("Suggestions for revision"),
            ")"
          ] })
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.contentTxt, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx(
            "textarea",
            {
              value: text2,
              onChange: handleChange,
              placeholder: t(
                "Your opinions are valuable to us. All valuable opinions will be accepted, and once accepted, will be rewarded with cash prizes according to their usefulness. We welcome your opinions!"
              ),
              maxLength
            }
          ),
          /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.numTxt, children: [
            /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: language == "ur" ? maxLength : text2.length }),
            "/",
            language == "ur" ? text2.length : maxLength
          ] })
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.imgHead, children: [
          t("Pictures don't lie"),
          /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { children: [
            "(",
            t("Easier to be adopted"),
            ")"
          ] })
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: /* @__PURE__ */ jsxRuntimeExports.jsx(Upload, {}) }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.footHead, children: t("Reward Rules") }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.tips, children: t(
            "We've set up huge bonuses specifically to collect feedback so that we can optimize the system and features to give you a better experience! Once accepted, rewards will be given based on the usefulness (except those not accepted)"
          ) })
        ] })
      ] }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.btnBottn, children: /* @__PURE__ */ jsxRuntimeExports.jsx(
        "div",
        {
          className: joinClass(css.btns, "button"),
          onClick: () => {
            btnSbumit();
          },
          children: t("Submit Feedback")
        }
      ) })
    ] }) : list2.length === 0 ? /* @__PURE__ */ jsxRuntimeExports.jsx(Empty, {}) : /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, { children: /* @__PURE__ */ jsxRuntimeExports.jsx(
      "div",
      {
        className: css.listBox,
        onScroll: (event) => {
          const { scrollHeight, scrollTop, clientHeight } = event.currentTarget;
          if (scrollHeight - scrollTop === clientHeight) {
            handleScroll();
            console.log("已经滚动到底部");
          }
        },
        children: !detailType ? list2.map((item, index) => {
          return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.list, children: [
            /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.listHead, children: [
              /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.left, children: [
                t("Feedback ID"),
                ":",
                /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css.id, children: item.id }),
                /* @__PURE__ */ jsxRuntimeExports.jsx(
                  "div",
                  {
                    className: css.copyIcon,
                    onClick: () => {
                      clipboardExports.copy(item.id);
                      Message$1.success(t("复制成功"));
                    },
                    children: /* @__PURE__ */ jsxRuntimeExports.jsx(RectCopyIcon, {})
                  }
                )
              ] }),
              /* @__PURE__ */ jsxRuntimeExports.jsxs(
                "div",
                {
                  className: item.state == 3 ? css.rightAdopted : css.right,
                  onClick: () => {
                    btnDetail(item);
                  },
                  children: [
                    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: t(
                      item.state == 2 ? t("Not adopted") : item.state == 1 || item.state == 3 ? t("Adopted") : t("Pendente")
                    ) }),
                    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.arrowIcon, children: /* @__PURE__ */ jsxRuntimeExports.jsx(ArrowLeftInMineIcon, {}) })
                  ]
                }
              )
            ] }),
            /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.listContent, children: [
              /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.updateTime, children: [
                /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: t("Feed Content") }),
                /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: customFormatTimer(item.submit_time) })
              ] }),
              /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.listText, children: item.content })
            ] }),
            item.state != 0 && /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
              /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.line }),
              /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.listContent, children: [
                /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.updateTime, children: [
                  /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
                    t("Official answer"),
                    ":"
                  ] }),
                  /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: customFormatTimer(item.updated_at) })
                ] }),
                /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.listTextReply, children: item.reply_content })
              ] })
            ] })
          ] }, index);
        }) : /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.detailImg, children: itemDetail && itemDetail.attachment_url && /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, { children: itemDetail.attachment_type == "2" ? /* @__PURE__ */ jsxRuntimeExports.jsx("video", { onClick: handleVideoClick, ref: videoRef, children: /* @__PURE__ */ jsxRuntimeExports.jsx(
            "source",
            {
              src: "".concat("https://dl-br-cf.sadslj88.com").concat(itemDetail.attachment_url),
              type: "video/mp4"
            }
          ) }) : /* @__PURE__ */ jsxRuntimeExports.jsx(
            "img",
            {
              src: "".concat("https://dl-br-cf.sadslj88.com").concat(itemDetail.attachment_url)
            }
          ) }) }),
          /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.detailTime, children: [
            /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: t("Feed Content") }),
            /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: customFormatTimer(itemDetail.submit_time) })
          ] }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.detailContent, children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.text, children: itemDetail.content }) }),
          itemDetail.state != 0 && /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
            /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.line }),
            /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.updatedAt, children: [
              /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: t("Official answer") }),
              /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: customFormatTimer(itemDetail.updated_at) })
            ] }),
            /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.replyContent, children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.text, children: itemDetail.reply_content }) })
          ] })
        ] })
      }
    ) }) })
  ] });
};
const Message = () => {
  const { getMessageNumBySotre } = useMessageStore();
  const search = window.location.search;
  const params = new URLSearchParams(search);
  const to = params.get("service");
  const toNotice = params.get("notice");
  const toFeedback = params.get("feedback");
  const state = useReactive({
    type: "",
    // message, notice,
    page: 0,
    total: 0,
    page_size: 10,
    messageList: [],
    selectList: [],
    isEdit: false
  });
  const onSelectType = (t) => {
    state.type = t;
  };
  const getMessageListByAction = async (page2 = 1) => {
    if (page2 === 1) state.total = 0;
    const [res] = await getMessageList({ page: page2, page_size: state.page_size });
    if (res) {
      if (page2 === 1) {
        state.messageList = res.d || [];
      } else {
        state.messageList = [...state.messageList, ...res.d];
      }
      state.page = page2;
      if (page2 === 1) state.total = res.t;
    }
  };
  useAsyncEffect(async () => {
    if (state.type === "message") await getMessageListByAction(1);
  }, [state.type]);
  reactExports.useEffect(() => {
    if (to) {
      state.type = "service";
    } else if (toNotice) {
      state.type = "notice";
    } else if (toFeedback) {
      state.type = "feedback";
    } else {
      state.type = "message";
    }
    const onReceiveMessage = () => {
      getMessageListByAction(1);
    };
    events.on("ReceiveMessage", onReceiveMessage);
    return () => {
      events.off("ReceiveMessage", onReceiveMessage);
    };
  }, []);
  const updateIsEdit = (status) => {
    state.isEdit = status;
  };
  const onSelect = (id2) => {
    const selectList2 = state.selectList;
    const index = selectList2.indexOf(id2);
    if (index === -1) {
      selectList2.push(id2);
      state.selectList = [...selectList2];
      return;
    }
    selectList2.splice(index, 1);
    state.selectList = [...selectList2];
  };
  const onSelectAll = () => {
    const { messageList: messageList2, selectList: selectList2 } = state;
    if (messageList2.length === selectList2.length) {
      state.selectList = [];
    } else {
      state.selectList = [...messageList2.map((item) => item.id)];
    }
  };
  const deleteMessage = async () => {
    const { selectList: selectList2 } = state;
    if (selectList2.length === 0) return;
    await deleteUserMessage(
      { flag: 1, ids: selectList2.join(",") },
      { useLoading: true }
    );
    getMessageListByAction(1);
    getMessageNumBySotre();
  };
  const { type, messageList, isEdit, selectList, total, page } = state;
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$5.messageBox, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx(
      Header,
      {
        type: state.type,
        onSelectType,
        selectList,
        messageList,
        onSelectAll,
        onDeleteMessage: deleteMessage,
        isEdit,
        updateIsEdit
      }
    ),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$5.contentBox, children: /* @__PURE__ */ jsxRuntimeExports.jsxs(
      "div",
      {
        className: joinClass(
          css$5.inSetBox,
          type === "message" ? css$5.showMessage : type === "feedback" ? css$5.showFeedback : type === "service" ? "" : css$5.showNotice
        ),
        children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx(ServiceBox, {}),
          /* @__PURE__ */ jsxRuntimeExports.jsx(
            MessageBox,
            {
              type,
              messageList,
              isEdit,
              selectList,
              onSelect: (id2) => onSelect(id2),
              children: total > messageList.length ? /* @__PURE__ */ jsxRuntimeExports.jsx(
                LoadMore,
                {
                  className: css$5.loadMoreBtn,
                  onClick: () => {
                    getMessageListByAction(page + 1);
                  }
                }
              ) : null
            }
          ),
          /* @__PURE__ */ jsxRuntimeExports.jsx(NoticeBox, { type }),
          /* @__PURE__ */ jsxRuntimeExports.jsx(Feedback, { type })
        ]
      }
    ) })
  ] });
};
export {
  Message as default
};
