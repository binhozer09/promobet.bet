import { r as reactExports, j as jsxRuntimeExports, z as trans } from "./comps-B8ShbmG--2024_12_14_18_4.js";
import { A as ArrowLeftInMineIcon, dt as SvgVector } from "./icons-Cdaou_E3-2024_12_14_18_4.js";
const svg_theme_fill_color = "_svg_theme_fill_color_1u0w0_3";
const pagination = "_pagination_1u0w0_55";
const txt = "_txt_1u0w0_62";
const pre = "_pre_1u0w0_76";
const pagination_temp12 = "_pagination_temp12_1u0w0_76";
const next = "_next_1u0w0_76";
const active = "_active_1u0w0_111";
const css = {
  svg_theme_fill_color,
  pagination,
  txt,
  pre,
  pagination_temp12,
  next,
  active
};
const isTemp12 = document.documentElement.getAttribute("temph5") === "12";
const Pagination = ({ className, total = 0, pageSize = 12, page = 1, onChange, onClick }) => {
  const totalPage = reactExports.useMemo(() => {
    return total % pageSize == 0 ? parseInt(total / pageSize) : parseInt(total / pageSize) + 1;
  }, [total, pageSize]);
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "".concat(isTemp12 ? css.pagination_temp12 : css.pagination, " ").concat(className), children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "".concat(css.pre, " ").concat(page > 1 && css.active), onClick: () => {
      if (page > 1) {
        onChange == null ? void 0 : onChange(page - 1);
      }
    }, children: isTemp12 ? /* @__PURE__ */ jsxRuntimeExports.jsx(ArrowLeftInMineIcon, {}) : /* @__PURE__ */ jsxRuntimeExports.jsx(SvgVector, {}) }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.txt, onClick: () => {
      onClick == null ? void 0 : onClick();
    }, children: trans("全部") }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "".concat(css.next, " ").concat(page < totalPage && css.active), onClick: () => {
      if (page < totalPage) {
        onChange == null ? void 0 : onChange(page + 1);
      }
    }, children: isTemp12 ? /* @__PURE__ */ jsxRuntimeExports.jsx(ArrowLeftInMineIcon, {}) : /* @__PURE__ */ jsxRuntimeExports.jsx(SvgVector, {}) })
  ] });
};
export {
  Pagination as P
};
