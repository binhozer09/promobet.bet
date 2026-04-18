import { r as reactExports, y as useNavigate, j as jsxRuntimeExports, I as Image, a6 as desktopOpen, q as useUserInfoStore, a9 as useReactive, u as useAsyncEffect, az as getBanner, aa as minPxChip } from "./comps-B8ShbmG--2024_12_14_18_4.js";
const svg_theme_fill_color$1 = "_svg_theme_fill_color_ygd8w_3";
const carouselBox = "_carouselBox_ygd8w_55";
const contentBox = "_contentBox_ygd8w_63";
const moveToRight = "_moveToRight_ygd8w_70";
const moveToLeft = "_moveToLeft_ygd8w_77";
const moveToCenter = "_moveToCenter_ygd8w_84";
const points = "_points_ygd8w_91";
const point_box = "_point_box_ygd8w_99";
const point = "_point_ygd8w_91";
const active = "_active_ygd8w_120";
const css$1 = {
  svg_theme_fill_color: svg_theme_fill_color$1,
  carouselBox,
  contentBox,
  moveToRight,
  moveToLeft,
  moveToCenter,
  points,
  point_box,
  point,
  active
};
const svg_theme_fill_color = "_svg_theme_fill_color_11bva_3";
const bannerItemBox = "_bannerItemBox_11bva_55";
const lastImg = "_lastImg_11bva_63";
const middleImg = "_middleImg_11bva_71";
const nextImg = "_nextImg_11bva_78";
const css = {
  svg_theme_fill_color,
  bannerItemBox,
  lastImg,
  middleImg,
  nextImg
};
const cacheState$1 = {
  startClientX: 0,
  endClientX: 0
};
const Item = (props = {}) => {
  const { item, imgs, index, updateSelectIndex } = props;
  const lastImg2 = imgs[index - 1] || null;
  const nextImg2 = imgs[index + 1] || null;
  const dom = reactExports.useRef({});
  const navigate = useNavigate();
  reactExports.useEffect(() => {
    if (dom.current) {
      const onTouchStart = (e) => {
        const touch = e.targetTouches[0] || {};
        cacheState$1.startClientX = touch.clientX || 0;
      };
      dom.current.addEventListener("touchstart", onTouchStart, {
        passive: true
      });
      const onTouchend = (e) => {
        const touch = e.changedTouches[0] || {};
        cacheState$1.endClientX = touch.clientX || 0;
        const cha = cacheState$1.endClientX - cacheState$1.startClientX;
        if (cha > 40) {
          if (lastImg2) updateSelectIndex(index - 1);
        }
        if (cha < -40) {
          if (nextImg2) updateSelectIndex(index + 1);
        }
      };
      dom.current.addEventListener("touchend", onTouchend, {
        passive: true
      });
      return () => {
        if (!dom.current) return;
        dom.current.removeEventListener("touchstart", onTouchStart, {
          passive: true
        });
        dom.current.removeEventListener("touchend", onTouchStart, {
          passive: true
        });
      };
    }
  }, [dom.current]);
  return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { ref: dom, className: css.bannerItemBox, children: /* @__PURE__ */ jsxRuntimeExports.jsx(
    Image,
    {
      remote: true,
      isGame: true,
      src: item.images,
      className: css.middleImg,
      onClick: () => {
        if (item.flags === "1") {
          navigate(item.url);
        }
        if (item.flags === "2") {
          desktopOpen(item.url);
        }
      }
    }
  ) });
};
const cacheState = {
  timer: null,
  timeoutTimer: null
};
const Carousel = () => {
  const { language } = useUserInfoStore();
  const boxDom = reactExports.useRef();
  const state = useReactive({
    selectIdx: 0,
    imgs: [],
    // 无限滚动的最小的索引
    min: 0,
    // 无限滚动的最大的索引
    max: 0
  });
  const { imgs } = state;
  const checkoutPlayBack = () => {
    clearTimeout(cacheState.timeoutTimer);
    cacheState.timeoutTimer = setTimeout(() => {
      if (state.selectIdx > state.max) {
        if (boxDom.current) boxDom.current.style.transition = "all 0s ease-in-out";
        state.selectIdx = state.min;
        setTimeout(() => {
          if (boxDom.current) boxDom.current.style.transition = "all 0.2s ease-in-out";
        }, 0.2 * 1e3);
      }
      if (state.selectIdx < state.min) {
        if (boxDom.current) boxDom.current.style.transition = "all 0s ease-in-out";
        state.selectIdx = state.max;
        setTimeout(() => {
          if (boxDom.current) boxDom.current.style.transition = "all 0.2s ease-in-out";
        }, 0.2 * 1e3);
      }
    }, 0.3 * 1e3);
  };
  const updateSelectIndex = (index) => {
    if (index === state.selectIdx) return;
    if (imgs.length < 2) return;
    clearTimer();
    state.selectIdx = index;
    checkoutPlayBack();
    startTimer();
  };
  useAsyncEffect(async () => {
    if (imgs.length === 0) {
      clearTimer();
      const [res] = await getBanner({
        flags: 2
      });
      if (res && res.length > 0) {
        state.imgs = res;
        if (res.length > 0) {
          state.selectIdx = res.length;
          state.min = res.length;
          state.max = res.length * 2 - 1;
        }
        startTimer();
      }
    }
  }, [imgs]);
  reactExports.useEffect(() => {
    return () => {
      clearTimer();
    };
  }, []);
  const clearTimer = () => {
    clearInterval(cacheState.timer);
  };
  const startTimer = () => {
    cacheState.timer = setInterval(() => {
      state.selectIdx = state.selectIdx + 1;
      checkoutPlayBack();
    }, 3 * 1500);
  };
  const { selectIdx } = state;
  const windowWidth = minPxChip() * 750;
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.carouselBox, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { ref: boxDom, style: { transform: "translateX(".concat(language == "ur" ? 1 * selectIdx * windowWidth : -1 * selectIdx * windowWidth, "px)") }, className: css$1.contentBox, children: [...imgs, ...imgs, ...imgs].map((item, idx) => {
      return /* @__PURE__ */ jsxRuntimeExports.jsx(
        Item,
        {
          updateSelectIndex: (idx2) => updateSelectIndex(idx2),
          index: idx,
          item,
          imgs: [...imgs, ...imgs, ...imgs]
        },
        idx
      );
    }) }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$1.points, children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$1.point_box, children: imgs.map((_, index) => {
      return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "".concat(css$1.point, " ").concat(state.selectIdx % (imgs.length || 0) == index && css$1.active) }, index);
    }) }) })
  ] });
};
export {
  Carousel as C
};
