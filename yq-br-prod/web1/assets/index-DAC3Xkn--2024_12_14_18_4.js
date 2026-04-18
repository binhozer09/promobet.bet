import { V as useTranslation, r as reactExports, W as useClickAway, j as jsxRuntimeExports, a as joinClass } from "./comps-B8ShbmG--2024_12_14_18_4.js";
import { D as DownIcon } from "./icons-Cdaou_E3-2024_12_14_18_4.js";
const svg_theme_fill_color = "_svg_theme_fill_color_13fbq_3";
const selectContainer = "_selectContainer_13fbq_55";
const noBg = "_noBg_13fbq_72";
const small = "_small_13fbq_78";
const noBorder = "_noBorder_13fbq_81";
const select = "_select_13fbq_55";
const content = "_content_13fbq_94";
const prefix = "_prefix_13fbq_103";
const suffix = "_suffix_13fbq_112";
const up = "_up_13fbq_231";
const down = "_down_13fbq_234";
const options = "_options_13fbq_237";
const option = "_option_13fbq_237";
const active = "_active_13fbq_378";
const css = {
  svg_theme_fill_color,
  selectContainer,
  noBg,
  "default": "_default_13fbq_75",
  small,
  noBorder,
  select,
  content,
  prefix,
  suffix,
  up,
  down,
  options,
  option,
  active
};
const NewSelect = (props) => {
  const { t, i18n } = useTranslation();
  const { value } = props;
  const [showOptions, setShowOptions] = reactExports.useState(false);
  const optionsRef = reactExports.useRef();
  const selectorRef = reactExports.useRef();
  const renderValue = reactExports.useMemo(() => {
    if (props.items === void 0) return "";
    const item = props.items.find((_) => _.value === value);
    return item ? t(item.label) : "";
  }, [value, props.items]);
  const clickHandler = (_value) => {
    setShowOptions(false);
    props.onChange && props.onChange(_value);
  };
  useClickAway(() => {
    setShowOptions(false);
  }, [optionsRef, selectorRef]);
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { ref: selectorRef, className: joinClass("global_selectContainer", css.selectContainer, props.noBg ? css.noBg : ""), onClick: () => setShowOptions(!showOptions), children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass("global_select", css.select), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass("global_content", css.content), children: renderValue }) }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass("global_suffix", css.suffix), children: /* @__PURE__ */ jsxRuntimeExports.jsx(DownIcon, { className: joinClass(showOptions ? css.up : css.down), src: "/home/icons/down.webp" }) }),
    showOptions && props.items && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { ref: optionsRef, className: joinClass("global_options", css.options), children: props.items.map((_, index) => {
      return /* @__PURE__ */ jsxRuntimeExports.jsx(
        "div",
        {
          className: joinClass("global_option", css.option, value === _.value ? "".concat(css.active, " global_active") : ""),
          onClick: (e) => {
            e.stopPropagation();
            clickHandler(_.value);
          },
          children: t(_.label)
        },
        index
      );
    }) })
  ] });
};
export {
  NewSelect as N
};
