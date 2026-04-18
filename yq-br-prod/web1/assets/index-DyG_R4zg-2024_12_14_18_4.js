import { r as reactExports, j as jsxRuntimeExports, a as joinClass } from "./comps-B8ShbmG--2024_12_14_18_4.js";
const svg_theme_fill_color = "_svg_theme_fill_color_vswbp_3";
const tabsComponent2 = "_tabsComponent2_vswbp_55";
const tabItemContainer2 = "_tabItemContainer2_vswbp_59";
const scrollX = "_scrollX_vswbp_73";
const wrap = "_wrap_vswbp_83";
const tabItem = "_tabItem_vswbp_59";
const text = "_text_vswbp_106";
const active = "_active_vswbp_132";
const tabsComponent = "_tabsComponent_vswbp_55";
const tabItemContainer = "_tabItemContainer_vswbp_59";
const css = {
  svg_theme_fill_color,
  tabsComponent2,
  tabItemContainer2,
  scrollX,
  wrap,
  tabItem,
  text,
  active,
  tabsComponent,
  tabItemContainer
};
const _Tab = reactExports.memo(({
  children,
  active: active2,
  items = [],
  type = "line",
  onChange = () => {
  }
}) => {
  const itemRefs = Array.from({ length: items.length }).map(() => reactExports.useRef(null));
  const handleTabClick = (value) => {
    onChange(value);
    const index = items.findIndex((item) => item.value === value);
    const ref = itemRefs[index];
    if (ref && ref.current) {
      ref.current.scrollIntoView({
        behavior: "smooth",
        inline: "center",
        block: "center"
      });
    }
  };
  const renderNav = () => {
    return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(type === "line" ? css.tabItemContainer : css.tabItemContainer2), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.scrollX), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.wrap), children: items.map((item, index) => {
      const { value, label } = item;
      return /* @__PURE__ */ jsxRuntimeExports.jsx(
        "div",
        {
          ref: itemRefs[index],
          className: joinClass(
            css.tabItem,
            active2 === value ? css.active : ""
          ),
          onClick: () => handleTabClick(value),
          children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.text, children: [
            active2 === value ? item.activeIcon : item.icon,
            label
          ] })
        },
        value
      );
    }) }) }) });
  };
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("section", { className: joinClass(type === "line" ? css.tabsComponent : css.tabsComponent2), children: [
    renderNav(),
    children && (Array.isArray(children) ? children.filter((child) => child.props.value === active2) : children.props.value === active2 && children)
  ] });
});
const TabPane = reactExports.memo(({
  children,
  value
}) => {
  return /* @__PURE__ */ jsxRuntimeExports.jsx("section", { className: css.tabsPaneComponent, children });
});
const Tab = Object.assign(_Tab, {
  Pane: TabPane
});
export {
  Tab as T
};
