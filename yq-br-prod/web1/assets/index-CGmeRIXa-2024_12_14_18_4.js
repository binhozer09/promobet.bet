import { r as reactExports, j as jsxRuntimeExports, a as joinClass } from "./comps-B8ShbmG--2024_12_14_18_4.js";
const svg_theme_fill_color = "_svg_theme_fill_color_1aml9_3";
const buttonComponent = "_buttonComponent_1aml9_55";
const round = "_round_1aml9_69";
const block = "_block_1aml9_72";
const largeRound = "_largeRound_1aml9_76";
const disabled = "_disabled_1aml9_283";
const hollow = "_hollow_1aml9_293";
const loading = "_loading_1aml9_427";
const css = {
  svg_theme_fill_color,
  buttonComponent,
  round,
  block,
  largeRound,
  "type-primary": "_type-primary_1aml9_79",
  "type-warning": "_type-warning_1aml9_242",
  "type-borderBtn": "_type-borderBtn_1aml9_246",
  disabled,
  hollow,
  loading
};
const Button = reactExports.memo((props) => {
  const type = reactExports.useMemo(() => {
    var _a;
    return "type-".concat((_a = props.type) != null ? _a : "primary");
  }, [props.type]);
  const block2 = reactExports.useMemo(() => {
    var _a;
    return (_a = props.block) != null ? _a : false;
  }, [props.block]);
  const round2 = reactExports.useMemo(() => {
    var _a;
    return (_a = props.round) != null ? _a : true;
  }, [props.round]);
  const largeRound2 = reactExports.useMemo(() => {
    var _a;
    return (_a = props.largeRound) != null ? _a : false;
  }, [props.largeRound]);
  const disabled2 = reactExports.useMemo(() => {
    var _a;
    return (_a = props.disabled) != null ? _a : false;
  }, [props.disabled]);
  const loading2 = reactExports.useMemo(() => {
    var _a;
    return (_a = props.loading) != null ? _a : false;
  }, [props.loading]);
  return /* @__PURE__ */ jsxRuntimeExports.jsxs(
    "button",
    {
      onClick: props.onClick,
      type: props.htmlType || "button",
      style: props.style,
      className: joinClass(
        css.buttonComponent,
        css[type],
        block2 ? css.block : "",
        round2 ? css.round : "",
        largeRound2 ? css.largeRound : "",
        disabled2 || loading2 ? css.disabled : "",
        "button",
        props.className,
        props.hollow ? css.hollow : ""
      ),
      children: [
        props.children,
        loading2 && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.loading })
      ]
    }
  );
});
export {
  Button as B
};
