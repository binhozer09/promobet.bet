import { a2 as useNavigateToActivity, aB as useFlutterApp, q as useUserInfoStore, r as reactExports, j as jsxRuntimeExports, a as joinClass, I as Image, aC as sendMessage } from "./comps-B8ShbmG--2024_12_14_18_4.js";
import { L as LoginWheelDialog } from "./index-DgUEEG5U-2024_12_14_18_4.js";
const svg_theme_fill_color = "_svg_theme_fill_color_3egci_3";
const activityBottomContainer = "_activityBottomContainer_3egci_55";
const arrowContent = "_arrowContent_3egci_62";
const center = "_center_3egci_86";
const arrow = "_arrow_3egci_62";
const transform = "_transform_3egci_99";
const arrowColor = "_arrowColor_3egci_115";
const itemContainer = "_itemContainer_3egci_119";
const item = "_item_3egci_119";
const itemMap = "_itemMap_3egci_136";
const active = "_active_3egci_142";
const itemContent = "_itemContent_3egci_251";
const itemImg = "_itemImg_3egci_256";
const checked_img = "_checked_img_3egci_262";
const listTitle = "_listTitle_3egci_270";
const textActive = "_textActive_3egci_367";
const css = {
  svg_theme_fill_color,
  activityBottomContainer,
  arrowContent,
  center,
  arrow,
  transform,
  arrowColor,
  itemContainer,
  item,
  itemMap,
  active,
  itemContent,
  itemImg,
  checked_img,
  listTitle,
  textActive
};
 const checkMaps = {
  black: "/home/icons/checkd.webp",
  blue: "/home/icons/checkd_blue.webp",
  whiteGreen: "/home/icons/checkd_white_green.webp",
  purple: "/home/icons/checkd_purple.webp",
  whiteRed: "/home/icons/checkd_white_red.webp",
  oilyGreen: "/home/icons/checkd_oily_green.webp",
  versaceYellow: "/home/icons/checkd_versaceYellow.webp",
  hermesOrange: "/home/icons/checkd_hermesOrange.webp",
  whiteBlue: "/home/icons/checkd_whiteBlue.webp",
  sk2: "/home/icons/checkd_sk2.webp",
  whiteYellow: "/home/icons/checkd_whiteYellow.webp",
  lightBrown: "/home/icons/checkd_lightBrown.webp",
  whiteOrange: "/home/icons/checkd_whiteOrange.webp",
  furlaBlue: "/home/icons/checkd_furlaBlue.webp",
  whitePink: "/home/icons/check_whitePink.webp",
  bvGreen: "/home/icons/check_bvGreen.webp",
  whiteBrown: "/home/icons/check_whiteBrown.webp",
  AnnaSuiPurple: "/home/icons/check_AnnaSuiPurple.webp",
  whitePurple: "/home/icons/check_whitePurple.webp",
  burgundyRed: "/home/icons/check_burgundyRed.webp",
  whiteDarkGreen: "/home/icons/check_whiteDarkGreen.webp",
  whiteBrownLauren: "/home/icons/check_whiteBrownLauren.webp",
  greenGold: "/home/icons/check_greenGold.webp",
  whiteRedGucci: "/home/icons/check_whiteRedGucci.webp",
  embraerBlue: "/home/icons/check_embraerBlue.webp",
  elsaPink: "/home/icons/check_elsaPink.webp",
  bvlgariBrown: "/home/icons/check_bvlgariBrown.webp",
  whiteBlack: "/home/icons/check_whiteBlack.webp",
  whiteBlueFendi: "/home/icons/check_whiteBlueFendi.webp",
  venetaGrey: "/home/icons/check_venetaGrey.webp",
  martinPurple: "/home/icons/check_martinPurple.webp",
  lancomePeach: "/home/icons/checkd_lancomePeach.webp",
  microsoftRed: "/home/icons/checkd_microsoftRed.webp",
  whiteGreenCindy: "/home/icons/checkd_whiteGreenCindy.webp",
  celineBrownWhite: "/home/icons/checkd_brownWhite.webp",
  ferrariBlack: "/home/icons/checkd_ferrariBlack.webp",
  diorPurpleWhite: "/home/icons/checkd_diorpurplewhite.webp",
  almondYellow: "/home/icons/checkd_almondyellow.webp",
  montblancBluewhite: "/home/icons/checkd_montblancBluewhite.webp",
  bondiBlue: "/home/icons/checkd_bondiBlue.webp",
  wasabiYellowWhite: "/home/icons/checkd_wasabiYellowWhite.webp",
  gemRed: "/home/icons/checkd_gemRed.webp",
  coralPurpleWhite: "/home/icons/checkd_coralPurpleWhite.webp",
  elegantPinkWhite: "/home/icons/checkd_elegantPinkWhite.webp",
  lemonGreenWhite: "/home/icons/checkd_lemonGreenWhite.webp",
  caramelYelloWhite: "/home/icons/checkd_caramelYelloWhite.webp",
  camelliaRedWhite: "/home/icons/checkd_camelliaRedWhite.webp",
  coralPurple: "/home/icons/checkd_coralPurple.webp",
};
const setActiveCenter = () => {
  const container = document.querySelector(".".concat(css.itemContainer));
  const item2 = document.querySelector(".".concat(css.itemMap, ".").concat(css.active));
  if (!container || !item2) {
    return;
  }
  const itemWidth = item2.clientWidth;
  const containerWidth = container.clientWidth;
  const left = item2.offsetLeft - (containerWidth - itemWidth) / 2;
  container.scrollLeft = left;
};
const ActivityBottom = () => {
  const { list, curId, handleClick, next, prev } = useNavigateToActivity();
  const { isApp } = useFlutterApp();
  const { theme } = useUserInfoStore();
  const [showWheel, setShowWheel] = reactExports.useState(false);
  reactExports.useEffect(() => {
    setActiveCenter();
  }, [curId, list]);
  const goToPage = (item2) => {
    if (isApp) {
      sendMessage("getTitleName", "");
    }
    if (item2.flag === "turntable") {
      setShowWheel(true);
      return;
    }
    handleClick(item2);
  };
  return /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, { children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.activityBottomContainer, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.center, children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.itemContainer, children: list.map((item2, index) => {
      return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css.item), children: [
        /* @__PURE__ */ jsxRuntimeExports.jsxs(
          "div",
          {
            className: joinClass(
              css.itemMap,
              curId === item2.id ? css.active : ""
            ),
            children: [
              /* @__PURE__ */ jsxRuntimeExports.jsx(
                "div",
                {
                  className: css.itemContent,
                  onClick: (e) => {
                    e.stopPropagation();
                    goToPage(item2);
                  },
                  children: /* @__PURE__ */ jsxRuntimeExports.jsx(
                    Image,
                    {
                      className: css.itemImg,
                      src: item2.static.list_h5 || item2.static.list_web,
                      remote: true,
                      isGame: true
                    }
                  )
                }
              ),
              curId === item2.id && /* @__PURE__ */ jsxRuntimeExports.jsx(
                Image,
                {
                  className: css.checked_img,
                  src: checkMaps[theme] || "/home/icons/checkd.webp"
                }
              )
            ]
          }
        ),
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          "div",
          {
            className: joinClass(
              css.listTitle,
              curId === item2.id ? css.textActive : ""
            ),
            children: item2.title
          }
        )
      ] }, index);
    }) }) }),
    showWheel && /* @__PURE__ */ jsxRuntimeExports.jsx(LoginWheelDialog, { isShowFooter: false, onClose: () => {
      setShowWheel(false);
    } })
  ] }) });
};
export {
  ActivityBottom as A
};
