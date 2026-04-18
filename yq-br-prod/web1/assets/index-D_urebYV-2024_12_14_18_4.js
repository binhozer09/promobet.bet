import { q as useUserInfoStore, r as reactExports, W as useClickAway, ag as reactDomExports, j as jsxRuntimeExports, au as languageOptions, I as Image, av as flagFormat, aD as toPXBack } from "./comps-B8ShbmG--2024_12_14_18_4.js";
const svg_theme_fill_color = "_svg_theme_fill_color_1al4v_3";
const language_selector = "_language_selector_1al4v_55";
const item = "_item_1al4v_65";
const active = "_active_1al4v_84";
const language_selector_right = "_language_selector_right_1al4v_89";
const css = {
  svg_theme_fill_color,
  language_selector,
  item,
  active,
  language_selector_right
};
const isNested = document.documentElement.getAttribute("nested") === "1";
const Language = ({
  isOpen = false,
  position = "bottom",
  hideModel,
  element,
  elementId,
  pFormat
}) => {
  const { language, setLanguage } = useUserInfoStore();
  const selectorRef = reactExports.useRef();
  const [isShow, setIsShow] = reactExports.useState(isOpen);
  const [selectorPosition, setSelectorPosition] = reactExports.useState({});
  const languageRef = reactExports.useRef();
  const setPosition = () => {
    if (elementId) {
      languageRef.current = document.getElementById(elementId);
    }
    if (element == null ? void 0 : element.current) {
      languageRef.current = element.current;
    }
    if (!languageRef.current || !isOpen) return;
    const rect = languageRef.current.getBoundingClientRect();
    if (position === "bottom") {
      let leftMore = 0;
      if (selectorRef.current) {
        leftMore = (rect.width + selectorRef.current.clientWidth) / 2 - selectorRef.current.clientWidth;
      }
      let p = {
        top: rect.bottom + 5,
        left: rect.left + leftMore
      };
      if (language == "ur") {
        p.left = rect.left + parseFloat(toPXBack(248)) - rect.width - parseFloat(toPXBack(20));
        p = (pFormat == null ? void 0 : pFormat(p, rect, { width: parseFloat(toPXBack(248)) })) || p;
      } else {
        p = (pFormat == null ? void 0 : pFormat(p, rect, { width: parseFloat(toPXBack(248)) })) || p;
      }
      setSelectorPosition({
        top: "".concat(p.top, "px"),
        left: "".concat(p.left, "px")
      });
    }
    if (position === "right") {
      let topMore = -8;
      if (selectorRef.current) {
        topMore = (languageRef.current.getBoundingClientRect().height + selectorRef.current.clientHeight) / 2 - selectorRef.current.clientHeight;
      }
      let p = {
        top: rect.top + topMore
      };
      if (language == "ur") {
        if (isNested) {
          p.left = rect.left - parseFloat(toPXBack(248)) - parseFloat(toPXBack(16));
        }
        p = (pFormat == null ? void 0 : pFormat(p, rect, { width: parseFloat(toPXBack(248)) })) || p;
      } else {
        if (isNested) {
          p.left = rect.right + parseFloat(toPXBack(16));
        }
        p = (pFormat == null ? void 0 : pFormat(p, rect, { width: parseFloat(toPXBack(248)) })) || p;
      }
      setSelectorPosition({
        top: p.top + "px",
        left: p.left + "px"
      });
    }
    setIsShow(true);
  };
  useClickAway(() => {
    hideModel();
  }, [languageRef]);
  reactExports.useEffect(() => {
    if (isShow) {
      const rect = selectorRef.current.getBoundingClientRect();
      if (rect.bottom > (window.innerHeight || document.documentElement.clientHeight)) {
        setSelectorPosition({
          left: selectorPosition.left,
          bottom: "10rem"
        });
      }
    }
  }, [isShow]);
  reactExports.useEffect(() => {
    let timeout = null;
    let controller = null;
    if (!isOpen) {
      setIsShow(false);
    } else {
      setPosition();
      if (controller) {
        controller.abort();
      }
      controller = new AbortController();
      window.addEventListener(
        "resize",
        () => {
          setIsShow(false);
          clearTimeout(timeout);
          timeout = setTimeout(() => {
            setPosition();
          }, 300);
        },
        { signal: controller.signal }
      );
    }
    return () => {
      if (controller) {
        controller.abort();
        controller = null;
      }
      if (timeout) {
        clearTimeout(timeout);
        timeout = null;
      }
    };
  }, [isOpen]);
  return reactDomExports.createPortal(
    isShow && /* @__PURE__ */ jsxRuntimeExports.jsx(
      "div",
      {
        ref: selectorRef,
        className: "".concat(css.language_selector, " ").concat(css["language_selector_" + position]),
        style: {
          ...selectorPosition
        },
        children: languageOptions.map((item2) => /* @__PURE__ */ jsxRuntimeExports.jsxs(
          "div",
          {
            onClick: () => {
              if (item2.value == "ur") {
                document.documentElement.dir = "rtl";
              } else {
                document.documentElement.dir = "ltr";
              }
              setLanguage(item2.value);
              hideModel();
            },
            className: "".concat(css.item, " ").concat(language === item2.value && css.active),
            children: [
              /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { src: "/home/icons/currency_".concat(flagFormat[item2.value], ".webp") }),
              item2.label
            ]
          },
          item2.value
        ))
      }
    ),
    document.body
  );
};
export {
  Language as L
};
