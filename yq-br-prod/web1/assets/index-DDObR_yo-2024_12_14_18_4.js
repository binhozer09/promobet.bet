import { j as jsxRuntimeExports, I as Image } from "./comps-B8ShbmG--2024_12_14_18_4.js";
const svg_theme_fill_color = "_svg_theme_fill_color_pvvbv_3";
const vip_icon = "_vip_icon_pvvbv_55";
const css = {
  svg_theme_fill_color,
  vip_icon
};
const VipIcon = ({ level, className, type = "" }) => {
  return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "".concat(css.vip_icon, " ").concat(!!className && className), children: /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { src: "/home/vip/vipLevel".concat(level).concat(type ? "_".concat(type) : "", ".webp") }) });
};
export {
  VipIcon as V
};
