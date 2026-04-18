import { ac as useSetState, j as jsxRuntimeExports, M as Modal, E as CheckBox, z as trans } from "./comps-B8ShbmG--2024_12_14_18_4.js";
import { a as CloseIconInLogin } from "./icons-Cdaou_E3-2024_12_14_18_4.js";
import DmWheel from "./index-CezjVG8f-2024_12_14_18_4.js";
const svg_theme_fill_color = "_svg_theme_fill_color_st3hg_3";
const bgColor = "_bgColor_st3hg_55";
const dia_dm_wheel = "_dia_dm_wheel_st3hg_55";
const lightBg = "_lightBg_st3hg_176";
const lightBg2 = "_lightBg2_st3hg_291";
const borderColor1 = "_borderColor1_st3hg_409";
const tabItemActive = "_tabItemActive_st3hg_521";
const yellow = "_yellow_st3hg_677";
const title = "_title_st3hg_795";
const dark = "_dark_st3hg_901";
const lightColor2 = "_lightColor2_st3hg_1016";
const dia_select = "_dia_select_st3hg_1016";
const dia_select_opt = "_dia_select_opt_st3hg_1016";
const shareButton = "_shareButton_st3hg_1095";
const lightColor3 = "_lightColor3_st3hg_1310";
const lightColor4 = "_lightColor4_st3hg_1422";
const dia_scroll = "_dia_scroll_st3hg_1541";
const close_icon = "_close_icon_st3hg_1568";
const css = {
  svg_theme_fill_color,
  bgColor,
  dia_dm_wheel,
  lightBg,
  lightBg2,
  borderColor1,
  tabItemActive,
  yellow,
  title,
  dark,
  lightColor2,
  dia_select,
  dia_select_opt,
  shareButton,
  lightColor3,
  lightColor4,
  dia_scroll,
  close_icon
};
const DiaDmWheel = ({ onClose = () => {
}, noShowToday = () => {
}, neverShow = () => {
} }) => {
  const [state, setState] = useSetState({
    curTab: "1",
    isOpen: true,
    noNext: false,
    noToday: false
  });
  return /* @__PURE__ */ jsxRuntimeExports.jsx(Modal, { isOpen: state.isOpen, background: "rgba(0,0,0,0.4)", closeByClickOut: false, zIndex: 99, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.dia_dm_wheel, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.dia_scroll, children: /* @__PURE__ */ jsxRuntimeExports.jsx(DmWheel, { isDia: true }) }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.dia_select, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.dia_select_opt, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          CheckBox,
          {
            checked: state.noToday,
            onChange: (val) => {
              noShowToday(val);
              setState({ noToday: val });
            }
          }
        ),
        /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: trans("Don't show again today") })
      ] }),
      /* @__PURE__ */ jsxRuntimeExports.jsxs(
        "div",
        {
          className: css.dia_select_opt,
          onClick: () => {
            neverShow(!state.noNext);
            setState({ noNext: !state.noNext });
          },
          children: [
            /* @__PURE__ */ jsxRuntimeExports.jsx(
              CheckBox,
              {
                checked: state.noNext,
                onChange: (val) => {
                  neverShow(val);
                  setState({ noNext: val });
                }
              }
            ),
            /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { children: [
              " ",
              trans("永久隐藏")
            ] })
          ]
        }
      )
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsx(
      CloseIconInLogin,
      {
        className: css.close_icon,
        onClick: (e) => {
          console.log("e :", e);
          setState({ isOpen: false });
          onClose();
        }
      }
    )
  ] }) });
};
export {
  DiaDmWheel as D
};
