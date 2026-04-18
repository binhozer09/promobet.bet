import { q as useUserInfoStore, r as reactExports, j as jsxRuntimeExports } from "./comps-B8ShbmG--2024_12_14_18_4.js";
import { bV as InputClearIcon, X as SearchToolIcon } from "./icons-Cdaou_E3-2024_12_14_18_4.js";
const svg_theme_fill_color = "_svg_theme_fill_color_ig1bd_3";
const search_input = "_search_input_ig1bd_55";
const inBox = "_inBox_ig1bd_67";
const searchBtn = "_searchBtn_ig1bd_344";
const svg = "_svg_ig1bd_3";
const css = {
  svg_theme_fill_color,
  search_input,
  inBox,
  searchBtn,
  svg
};
const SearchInput = ({
  onInput = () => {
  },
  value,
  onBlur = () => {
  },
  onSearch = () => {
  },
  onKeyDown = () => {
  },
  placeholder,
  width,
  className
}) => {
  useUserInfoStore();
  const inputRef = reactExports.useRef();
  const handleEventInput = (e) => {
    onInput(e.target.value);
  };
  const handleClearn = (e) => {
    onInput("");
    inputRef.current.focus();
  };
  return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "".concat(css.search_input), children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.inBox, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx("input", { onChange: (e) => handleEventInput(e), ref: inputRef, onBlur: (e) => {
      onBlur && onBlur(e.target.value);
    }, placeholder, value, onKeyDown }),
    value ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.clsoeBtn, onClick: handleClearn, children: /* @__PURE__ */ jsxRuntimeExports.jsx(InputClearIcon, { className: css.iconsvg }) }) : "",
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.searchBtn, onClick: () => onSearch(), children: /* @__PURE__ */ jsxRuntimeExports.jsx(SearchToolIcon, { className: css.svg }) })
  ] }) });
};
export {
  SearchInput as S
};
