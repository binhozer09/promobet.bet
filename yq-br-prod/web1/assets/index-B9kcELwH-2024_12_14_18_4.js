const __vite__mapDeps=(i,m=__vite__mapDeps,d=(m.f||(m.f=["assets/index-DgUEEG5U-2024_12_14_18_4.js","assets/comps-B8ShbmG--2024_12_14_18_4.js","assets/icons-Cdaou_E3-2024_12_14_18_4.js","assets/icons-CxwRDrrn-2024_12_14_18_4.css","assets/comps-DLgBMKMA-2024_12_14_18_4.css","assets/index-CXpt7Cav-2024_12_14_18_4.js","assets/index-D1UCb5so-2024_12_14_18_4.css","assets/index-DyG_R4zg-2024_12_14_18_4.js","assets/index-Bok_l04h-2024_12_14_18_4.css","assets/index-4Dep-BeX-2024_12_14_18_4.js","assets/index-Bt-OyMEQ-2024_12_14_18_4.css","assets/index-CGmeRIXa-2024_12_14_18_4.js","assets/index-DF6p0DMW-2024_12_14_18_4.css","assets/index-BcpFmBv5-2024_12_14_18_4.js","assets/index-C7CpiKuL-2024_12_14_18_4.css","assets/index-CsbwU_08-2024_12_14_18_4.js","assets/index-DsKRbARX-2024_12_14_18_4.css","assets/index-Bq-a07OY-2024_12_14_18_4.js","assets/index-D8LKgtTC-2024_12_14_18_4.css","assets/config-B4NCyDU0-2024_12_14_18_4.js","assets/index-Dzw5inTt-2024_12_14_18_4.css"])))=>i.map(i=>d[i]);
import { r as reactExports, _ as __vitePreload, a9 as useReactive, aA as useNoticStore, q as useUserInfoStore, C as Cache, cz as getNoticeRead, j as jsxRuntimeExports, b2 as Loading } from "./comps-B8ShbmG--2024_12_14_18_4.js";
import { a as customFormatTimer } from "./index-BcpFmBv5-2024_12_14_18_4.js";
import "./icons-Cdaou_E3-2024_12_14_18_4.js";
const svg_theme_fill_color = "_svg_theme_fill_color_1z08q_3";
const contentBox = "_contentBox_1z08q_55";
const title = "_title_1z08q_181";
const message_title = "_message_title_1z08q_292";
const content = "_content_1z08q_55";
const time = "_time_1z08q_447";
const css = {
  svg_theme_fill_color,
  contentBox,
  title,
  message_title,
  content,
  time
};
const InnerPageWithBack = reactExports.lazy(() => __vitePreload(() => import("./index-DgUEEG5U-2024_12_14_18_4.js").then((n) => n.j), true ? __vite__mapDeps([0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]) : void 0));
const MessageDetail = () => {
  const state = useReactive({
    info: {}
  });
  const { getNoticNumBySotre } = useNoticStore((state2) => state2);
  const { token, info: userInfo } = useUserInfoStore();
  reactExports.useLayoutEffect(() => {
    state.info = Cache.get("messageDetail") || {};
    if (Object.keys(state.info).length !== 0) {
      if (token && state.info.is_read == 0) {
        noticeRead(state.info);
      }
    }
  }, []);
  const noticeRead = async () => {
    const [res] = await getNoticeRead({ id: state.info.id, uid: userInfo.uid });
    if (res == 1e3) {
      getNoticNumBySotre();
    }
  };
  const { info } = state;
  return /* @__PURE__ */ jsxRuntimeExports.jsx(reactExports.Suspense, { fallback: /* @__PURE__ */ jsxRuntimeExports.jsx(Loading, {}), children: /* @__PURE__ */ jsxRuntimeExports.jsx(InnerPageWithBack, { title: info.title, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.contentBox, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx(
      "div",
      {
        className: "".concat(css.title, " ").concat(state.info.page_type === "message" && css.message_title),
        children: info.title
      }
    ),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.time, children: info.created_at && customFormatTimer(info.created_at) || info.send_at && customFormatTimer(info.send_at) }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.content, children: info.content })
  ] }) }) });
};
export {
  MessageDetail as default
};
