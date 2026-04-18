import { r as reactExports, q as useUserInfoStore, j as jsxRuntimeExports, a as joinClass, I as Image, b6 as Spin, z as trans } from "./comps-B8ShbmG--2024_12_14_18_4.js";
import { $ as MoreInMineIcon } from "./icons-Cdaou_E3-2024_12_14_18_4.js";
const svg_theme_fill_color$1 = "_svg_theme_fill_color_jdwcc_3";
const radioGroup = "_radioGroup_jdwcc_55";
const radioItem = "_radioItem_jdwcc_63";
const active = "_active_jdwcc_236";
const activeTip = "_activeTip_jdwcc_466";
const css$1 = {
  svg_theme_fill_color: svg_theme_fill_color$1,
  radioGroup,
  radioItem,
  active,
  activeTip
};
const RadioContext = reactExports.createContext({});
const checkMaps = {
  black: "/home/icons/checkd.webp",
  blue: "/home/icons/checkd_blue.webp",
  whiteGreen: "/home/icons/checkd_white_green.webp",
  whiteGreenCindy: "/home/icons/whiteGreenCindy.webp",
  purple: "/home/icons/checkd_purple.webp",
  whiteRed: "/home/icons/checkd_white_red.webp",
  oilyGreen: "/home/icons/checkd_oily_green.webp",
  versaceYellow: "/home/icons/checkd_versaceYellow.webp",
  lancomePeach: "/home/icons/checkd_lancomePeach.webp",
  hermesOrange: "/home/icons/checkd_hermesOrange.webp",
  whiteBlue: "/home/icons/checkd_whiteBlue.webp",
  sk2: "/home/icons/checkd_sk2.webp",
  microsoftRed: "/home/icons/checkd_microsoftRed.webp",
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
  greenGold: "/home/icons/checkd_greenGold.webp",
  whiteRedGucci: "/home/icons/checked_whiteRedGucci.webp",
  embraerBlue: "/home/icons/checked_embraerBlue.webp",
  elsaPink: "/home/icons/checked_elsaPink.webp",
  bvlgariBrown: "/home/icons/checked_bvlgariBrown.webp",
  whiteBlack: "/home/icons/check_whiteBlack.webp",
  whiteBlueFendi: "/home/icons/check_whiteBlueFendi.webp",
  venetaGrey: "/home/icons/check_venetaGrey.webp",
  martinPurple: "/home/icons/check_martinPurple.webp",
  usdtGreen: "/home/icons/check_usdtGreen.webp",
  celineBrownWhite: "/home/icons/check_brownWhite.webp",
  ferrariBlack: "/home/icons/checkd_ferrariBlack.webp",
  burberryBlueWhite: "/home/icons/checkd_burberryBlueWhite.webp",
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
const _Radio = reactExports.memo((props) => {
  const { theme } = useUserInfoStore();
  const { parent, isChecked, ...context } = reactExports.useContext(RadioContext);
  const checked = reactExports.useMemo(() => {
    if (isChecked) {
      return isChecked(props.value);
    }
    return parent ? props.value === context.checked : props.checked;
  }, [context.checked]);
  const toggle = () => {
    if (props.onClick) {
      props.onClick();
      return;
    }
    const emitter = parent ? context.toggle : () => {
    };
    emitter(props.value);
  };
  const gapLength = reactExports.useMemo(() => {
    return context.rowLength - 1;
  }, [context.rowLength]);
  const widthPercentage = reactExports.useMemo(() => {
    return "calc((100% - calc(100vw / 750 * ".concat(18 * gapLength, ")) / ").concat(context.rowLength, ")");
  }, [context.rowLength]);
  return /* @__PURE__ */ jsxRuntimeExports.jsxs(
    "div",
    {
      onClick: toggle,
      className: joinClass(css$1.radioItem, checked && css$1.active, "button"),
      style: {
        "--radio-item-width": widthPercentage
      },
      children: [
        props.children,
        checked && context.checkMark && /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$1.activeTip, children: /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { src: checkMaps[theme] || "/home/icons/checkd.webp" }) })
      ]
    }
  );
});
const RadioGroup = reactExports.memo((props) => {
  var _a;
  const [checked, setChecked] = reactExports.useState(props.defaultValue);
  const toggle = (value) => {
    var _a2;
    setChecked(value);
    (_a2 = props.onChange) == null ? void 0 : _a2.call(props, value);
  };
  reactExports.useEffect(() => {
    setChecked(props.defaultValue);
  }, [props.defaultValue]);
  return /* @__PURE__ */ jsxRuntimeExports.jsx(
    RadioContext.Provider,
    {
      value: {
        parent: { props },
        toggle,
        checked,
        /** 一行显示多少条 */
        rowLength: props.rowLength || 2,
        /** 是否显示勾选图标 */
        checkMark: (_a = props.checkMark) != null ? _a : true,
        isChecked: props.isChecked
      },
      children: /* @__PURE__ */ jsxRuntimeExports.jsx(
        "section",
        {
          className: joinClass(css$1.radioGroup),
          style: {
            "--radio-row-length": props.rowLength
          },
          children: props.children
        }
      )
    }
  );
});
const Radio = Object.assign(_Radio, { Group: RadioGroup });
const svg_theme_fill_color = "_svg_theme_fill_color_yhb1i_3";
const more = "_more_yhb1i_55";
const css = {
  svg_theme_fill_color,
  more
};
const LoadMore = reactExports.forwardRef(({ loadingSize, loading = false, disabled = false, className, onClick, ...args }, ref) => {
  return loading ? /* @__PURE__ */ jsxRuntimeExports.jsx(Spin, { size: loadingSize }) : /* @__PURE__ */ jsxRuntimeExports.jsxs(
    "div",
    {
      ref,
      ...args,
      onClick: () => {
        if (!disabled) {
          onClick();
        }
      },
      className: "".concat(css.more, " ").concat(className || ""),
      style: {
        opacity: disabled ? 0.5 : 1
      },
      children: [
        trans("加载更多"),
        /* @__PURE__ */ jsxRuntimeExports.jsx(MoreInMineIcon, {})
      ]
    }
  );
});
export {
  LoadMore as L,
  Radio as R
};
