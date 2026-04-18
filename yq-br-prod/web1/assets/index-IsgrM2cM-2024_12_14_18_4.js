import { q as useUserInfoStore, o as useGameStore, y as useNavigate, r as reactExports, aq as events, j as jsxRuntimeExports, a4 as instance, z as trans, I as Image, bM as Maintain, H as Message, Q as getMoneyUnit, bS as getPlatLaunch, C as Cache } from "./comps-B8ShbmG--2024_12_14_18_4.js";
import { G as GameTabs, s as specialPlatformType } from "./config-B4NCyDU0-2024_12_14_18_4.js";
import { L as LoadingImg } from "./index-Bq-a07OY-2024_12_14_18_4.js";
import { L as LoadMore } from "./index-4Dep-BeX-2024_12_14_18_4.js";
import { P as Pagination } from "./index-_vAsCIZG-2024_12_14_18_4.js";
const svg_theme_fill_color = "_svg_theme_fill_color_trpke_3";
const typeGameBox = "_typeGameBox_trpke_55";
const title = "_title_trpke_60";
const logoBox = "_logoBox_trpke_65";
const icon = "_icon_trpke_70";
const gameBox = "_gameBox_trpke_146";
const gameItemBox = "_gameItemBox_trpke_153";
const laodingBox = "_laodingBox_trpke_170";
const gameItemBox2 = "_gameItemBox2_trpke_196";
const laodingBox2 = "_laodingBox2_trpke_210";
const gameName = "_gameName_trpke_219";
const all = "_all_trpke_239";
const gameBox_s = "_gameBox_s_trpke_249";
const box2Content = "_box2Content_trpke_266";
const btmLoadMoreBox = "_btmLoadMoreBox_trpke_272";
const tips = "_tips_trpke_278";
const loadMoreBtn = "_loadMoreBtn_trpke_283";
const css = {
  svg_theme_fill_color,
  typeGameBox,
  title,
  logoBox,
  icon,
  gameBox,
  gameItemBox,
  laodingBox,
  gameItemBox2,
  laodingBox2,
  gameName,
  all,
  gameBox_s,
  box2Content,
  btmLoadMoreBox,
  tips,
  loadMoreBtn
};
const TypeGame = (props = {}) => {
  var _a, _b;
  const { token, info, websetConfig } = useUserInfoStore();
  const { renderType } = useGameStore();
  const navigate = useNavigate();
  const [page, setPage] = reactExports.useState(1);
  const item = props.item || null;
  if (!item) return;
  const tabItem = GameTabs.find((tabItem2) => {
    return tabItem2.type && tabItem2.type === item.name;
  }) || null;
  if (!tabItem) return;
  const showBy = JSON.parse((item == null ? void 0 : item.show_by) || "{}");
  const len = renderType === "rect" ? showBy.sh || 10 : showBy.fh || 10;
  const staticRow = renderType === "rect" ? 3 : 4;
  const list = reactExports.useMemo(() => {
    if ((websetConfig == null ? void 0 : websetConfig.pages_turning_style) != "1") {
      return (item.l || []).slice(0, len * staticRow * page);
    } else {
      return (item.l || []).slice(len * staticRow * (page - 1), len * staticRow * (page - 1) + len * staticRow);
    }
  }, [websetConfig, props.item, page]);
  const getPlatLaunchFun = async (id, min_admission) => {
    if (!token) {
      useUserInfoStore.setState({ openForLogin: true });
      return;
    }
    if (info && +info.balance < (+min_admission || 0)) {
      Message.error(instance.t("您的余额低于「最低准入金额」，请存款后才能玩", { amount: min_admission, coin: getMoneyUnit(true) }));
      return;
    }
    const [res, error] = await getPlatLaunch({ id }, { useLoading: true });
    if (res) {
      Cache.set("gameStart", res);
      navigate("/gameStart");
    }
  };
  const IconsCom = tabItem.ActiveIcon || null;
  const toPlatformGame = (pid) => {
    const _item = GameTabs.find((el) => el.type === item.name);
    const type = _item.type;
    if (!specialPlatformType.includes(type)) {
      navigate("/venuegame?id=".concat(item.id, "&pid=").concat(pid || "0"));
    } else {
      navigate("/venuelist?id=".concat(item.id, "&pid=").concat(pid || "0"));
    }
  };
  reactExports.useEffect(() => {
    events.on("search", (searchTab) => {
      if (searchTab.name === item.name) {
        navigate("/venuegame?id=".concat(item.id));
      }
    });
    return () => {
      events.off("search");
    };
  }, []);
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", {
    className: css.typeGameBox,
    id: tabItem.text,
    // 如果是venuelist渲染的此组件，不显示顶部内容
    children: [
      props.isVenueListRender ? null : /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.title, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.logoBox, children: [
          IconsCom ? /* @__PURE__ */ jsxRuntimeExports.jsx(IconsCom, { isSelect: true, className: css.icon }) : null,
          /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css.gameTabStyle, children: instance.t(tabItem.text) })
        ] }),
        (websetConfig == null ? void 0 : websetConfig.pages_turning_style) == "1" ? /* @__PURE__ */ jsxRuntimeExports.jsx(Pagination, { pageSize: len * staticRow, page, total: ((_a = item.l) == null ? void 0 : _a.length) || 0, onChange: (page2) => {
          setPage(page2);
        }, onClick: toPlatformGame }) : /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.all, onClick: () => toPlatformGame(), children: [
          trans("Tudo"),
          " ",
          (_b = item.l) == null ? void 0 : _b.length
        ] })
      ] }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.gameBox, children: list.map((game, idx) => {
        let gameImg = game.promo_image || game.popular_image || game.img;
        if (renderType === "rect") {
          gameImg = game.img.replace("images-br", "images-br-rect");
        }
        return (
          // 根据renderType判断显示正方形还是长方形
          renderType === "rect" ? /* @__PURE__ */ jsxRuntimeExports.jsxs(
            "div",
            {
              img: gameImg,
              className: css.gameItemBox,
              onClick: () => {
                if (game.maintained === 2) return;
                if (!specialPlatformType.includes(item.name)) {
                  toPlatformGame(game.id);
                  return;
                }
                getPlatLaunchFun(game.id, game.min_admission);
              },
              children: [
                /* @__PURE__ */ jsxRuntimeExports.jsx(
                  Image,
                  {
                    loading: /* @__PURE__ */ jsxRuntimeExports.jsx(LoadingImg, { className: css.laodingBox }),
                    src: gameImg,
                    remote: true,
                    isGame: true
                  }
                ),
                game.maintained === 2 ? /* @__PURE__ */ jsxRuntimeExports.jsx(Maintain, {}) : null,
                /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.gameBox_s, children: [
                  " ",
                  game.name,
                  " "
                ] })
              ]
            },
            idx
          ) : /* @__PURE__ */ jsxRuntimeExports.jsxs(
            "div",
            {
              img: gameImg,
              className: css.gameItemBox2,
              onClick: () => {
                if (game.maintained === 2) return;
                if (!specialPlatformType.includes(item.name)) {
                  toPlatformGame(game.id);
                  return;
                }
                getPlatLaunchFun(game.id, game.min_admission);
              },
              children: [
                /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.box2Content, children: [
                  /* @__PURE__ */ jsxRuntimeExports.jsx(
                    Image,
                    {
                      loading: /* @__PURE__ */ jsxRuntimeExports.jsx(LoadingImg, { className: css.laodingBox2 }),
                      remote: true,
                      isGame: true,
                      src: gameImg
                    }
                  ),
                  game.maintained === 2 ? /* @__PURE__ */ jsxRuntimeExports.jsx(Maintain, {}) : null
                ] }),
                /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.gameName, children: game.name })
              ]
            },
            idx
          )
        );
      }) }),
      (websetConfig == null ? void 0 : websetConfig.pages_turning_style) != "1" && /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.btmLoadMoreBox, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css.tips, children: instance.t("显示{{total}}款{{type}}游戏中的{{num}}款游戏", {
          total: (item.l || []).length || 0,
          num: list.length || 0,
          type: instance.t(tabItem.text)
        }) }),
        (item.l || []).length > list.length ? /* @__PURE__ */ jsxRuntimeExports.jsx(
          LoadMore,
          {
            className: css.loadMoreBtn,
            onClick: () => {
              setPage(page + 1);
            }
          }
        ) : null
      ] })
    ]
  });
};
export {
  TypeGame as T
};
