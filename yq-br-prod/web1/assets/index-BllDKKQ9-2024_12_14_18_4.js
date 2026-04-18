import { o as useGameStore, r as reactExports, q as useUserInfoStore, j as jsxRuntimeExports, H as Message, a4 as instance, Q as getMoneyUnit, a as joinClass, I as Image, bM as Maintain, bN as getGameName, bO as favoritesremove, bP as favorites, y as useNavigate, ac as useSetState, bR as getHotGames, bS as getPlatLaunch, C as Cache, bU as historySave } from "./comps-B8ShbmG--2024_12_14_18_4.js";
import { cf as ActivePopular } from "./icons-Cdaou_E3-2024_12_14_18_4.js";
import { L as LoadingImg } from "./index-Bq-a07OY-2024_12_14_18_4.js";
import { L as LoadMore } from "./index-4Dep-BeX-2024_12_14_18_4.js";
import { P as Pagination } from "./index-_vAsCIZG-2024_12_14_18_4.js";
import { s as specialPlatformType } from "./config-B4NCyDU0-2024_12_14_18_4.js";
const svg_theme_fill_color$1 = "_svg_theme_fill_color_uzugf_3";
const gameBox$1 = "_gameBox_uzugf_55";
const logoBox$1 = "_logoBox_uzugf_69";
const laodingBox$1 = "_laodingBox_uzugf_79";
const game_list_item_hots$1 = "_game_list_item_hots_uzugf_205";
const game_list_item_hots_active$1 = "_game_list_item_hots_active_uzugf_225";
const identifier$1 = "_identifier_uzugf_1";
const gameBox_s = "_gameBox_s_uzugf_253";
const platformText = "_platformText_uzugf_268";
const game_recommend$1 = "_game_recommend_uzugf_283";
const css$1 = {
  svg_theme_fill_color: svg_theme_fill_color$1,
  gameBox: gameBox$1,
  logoBox: logoBox$1,
  laodingBox: laodingBox$1,
  game_list_item_hots: game_list_item_hots$1,
  game_list_item_hots_active: game_list_item_hots_active$1,
  identifier: identifier$1,
  gameBox_s,
  platformText,
  game_recommend: game_recommend$1
};
const RectGameItem = ({ gameNamesMap, maintainedMap, game, getPlatLaunchFun, idx }) => {
  const { renderType } = useGameStore();
  let gameImg = game.img;
  if (renderType === "rect") {
    gameImg = game.img.replace("images-br", "images-br-rect");
  }
  const [isShow, setIsShow] = reactExports.useState((game == null ? void 0 : game.is_favorites) || false);
  const [isEnlarged, setIsEnlarged] = reactExports.useState(false);
  const { token, websetConfig, info } = useUserInfoStore();
  websetConfig.game_recommend === "1";
  const btnCollect = async (game2) => {
    if (!token) {
      useUserInfoStore.setState({ openForLogin: true });
      return;
    }
    if (isShow) {
      const [res, err] = await favoritesremove({ pid: game2.platform_id, code: game2.game_id });
      if (res) setIsShow(false);
    } else {
      const [res, err] = await favorites({ pid: game2.platform_id, code: game2.game_id });
      if (res) setIsShow(true);
      setIsEnlarged(true);
    }
  };
  return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$1.gameBox, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.logoBox, onClick: () => {
    if (maintainedMap[game.platform_id] === 2 || game.maintained === 2) return;
    if (info && +info.balance < (+game.min_admission || 0)) {
      Message.error(instance.t("您的余额低于「最低准入金额」，请存款后才能玩", { amount: game.min_admission, coin: getMoneyUnit(true) }));
      return;
    }
    getPlatLaunchFun(game.platform_id, game.game_id, game);
  }, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { onClick: (e) => {
      e.stopPropagation();
      btnCollect(game);
    }, className: joinClass(css$1.game_list_item_hots, isEnlarged ? css$1.game_list_item_hots_active : ""), children: isShow && /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { src: "/home/icons/collect_active2.webp" }) }),
    game.is_recommend === 1 && /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { className: css$1.game_recommend, src: "/home/icons/recommend.webp" }),
    /* @__PURE__ */ jsxRuntimeExports.jsx(
      Image,
      {
        loading: /* @__PURE__ */ jsxRuntimeExports.jsx(LoadingImg, { className: css$1.laodingBox }),
        isGame: true,
        src: gameImg,
        remote: true
      }
    ),
    maintainedMap[game.platform_id] === 2 || game.maintained === 2 ? /* @__PURE__ */ jsxRuntimeExports.jsx(Maintain, {}) : null,
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$1.gameBox_s, children: getGameName(game) })
  ] }) });
};
const svg_theme_fill_color = "_svg_theme_fill_color_1p4p2_3";
const itemBox = "_itemBox_1p4p2_55";
const topBarBox = "_topBarBox_1p4p2_63";
const leftBox = "_leftBox_1p4p2_72";
const icon = "_icon_1p4p2_78";
const platformLogo = "_platformLogo_1p4p2_86";
const rightBox = "_rightBox_1p4p2_150";
const itemsContainer = "_itemsContainer_1p4p2_230";
const gameBox = "_gameBox_1p4p2_237";
const logoBox = "_logoBox_1p4p2_248";
const laodingBox = "_laodingBox_1p4p2_259";
const whiteLogo = "_whiteLogo_1p4p2_385";
const nameBox = "_nameBox_1p4p2_405";
const game_list_item_hots = "_game_list_item_hots_1p4p2_459";
const game_list_item_hots_active = "_game_list_item_hots_active_1p4p2_479";
const identifier = "_identifier_1p4p2_1";
const game_recommend = "_game_recommend_1p4p2_486";
const btmLoadMoreBox = "_btmLoadMoreBox_1p4p2_514";
const tips = "_tips_1p4p2_520";
const loadMoreBtn = "_loadMoreBtn_1p4p2_627";
const css = {
  svg_theme_fill_color,
  itemBox,
  topBarBox,
  leftBox,
  icon,
  platformLogo,
  rightBox,
  itemsContainer,
  gameBox,
  logoBox,
  laodingBox,
  whiteLogo,
  nameBox,
  game_list_item_hots,
  game_list_item_hots_active,
  identifier,
  game_recommend,
  btmLoadMoreBox,
  tips,
  loadMoreBtn
};
const HotGameItem = ({ item, remote }) => {
  const navigate = useNavigate();
  const { games, gameNamesMap, maintainedMap, renderType, gameSortMap } = useGameStore();
  const { token, info, websetConfig } = useUserInfoStore();
  const [showAll, setShowAll] = reactExports.useState(false);
  const rowItemLength = renderType === "rect" ? 3 : 4;
  const [state, setState] = useSetState({
    page: 1,
    list: [],
    page_size: 20,
    total: 0
  });
  const [isLoading, setIsLoading] = reactExports.useState(false);
  const hotShowBy = games ? games.find((item2) => item2.title === "热门") : {};
  const showBy = games ? JSON.parse((hotShowBy == null ? void 0 : hotShowBy.show_by) || "{}") : {};
  const len = renderType === "rect" ? showBy.sh || 10 : showBy.fh || 10;
  const getHotGameByQuery = async (page2 = 1) => {
    if (isLoading) return;
    setIsLoading(true);
    const [res] = await getHotGames(
      {
        page: page2,
        page_size: state.page_size
      },
      { useLoading: true }
    );
    setIsLoading(false);
    let d = res.d || [];
    const t = res.t || 0;
    if ((websetConfig == null ? void 0 : websetConfig.pages_turning_style) != "1") {
      if (page2 != 1) {
        d = state.list.concat(d);
      }
    }
    setState({
      list: d,
      page: page2,
      total: t || 0
    });
    if (page2 === 1) {
      useGameStore.setState({ hotGames: d });
    }
  };
  reactExports.useEffect(() => {
    getHotGameByQuery();
  }, []);
  const toPlatformGame = async (game) => {
    const g = games.find((item2) => item2.l.some((l) => l.id === game.id));
    if (specialPlatformType.includes(g.name)) {
      const [res, error] = await getPlatLaunch({ id: game.id, code: game.platform_id }, { useLoading: true });
      if (res) {
        Cache.set("gameStart", res);
        navigate("/gameStart");
        await historySave({ pid: game.id, code: game.platform_id });
      }
    } else {
      navigate("/venuegame?id=".concat(g.id, "&pid=").concat(game.id));
    }
  };
  const getPlatLaunchFun = async (id, code = "", game) => {
    if (!token) {
      useUserInfoStore.setState({ openForLogin: true });
      return;
    }
    if (game.game_id == game.platform_id) {
      toPlatformGame(game);
      return;
    }
    const [res, error] = await getPlatLaunch(
      { id, code },
      { useLoading: true }
    );
    if (res) {
      Cache.set("gameStart", res);
      navigate("/gameStart");
      await historySave({ pid: id, code });
    }
  };
  const { list, total, page } = state;
  const [isEnlarged, setIsEnlarged] = reactExports.useState(false);
  const btnCollect = async (game) => {
    if (!token) {
      useUserInfoStore.setState({ openForLogin: true });
      return;
    }
    if (game.is_favorites) {
      const [res, err] = await favoritesremove({
        pid: game.platform_id,
        code: game.game_id
      });
      if (res) {
        list.map((item2, idx) => {
          if (item2.id === game.id) {
            list[idx].is_favorites = false;
          }
        });
        setState({
          list,
          page,
          total
        });
      }
    } else {
      const [res, err] = await favorites({
        pid: game.platform_id,
        code: game.game_id
      });
      if (res) {
        list.map((item2, idx) => {
          if (item2.id === game.id) {
            list[idx].is_favorites = true;
          }
        });
        setState({
          list,
          page,
          total
        });
      }
      setIsEnlarged(true);
    }
  };
  const limitClicks = () => {
    setShowAll(true);
    if (isLoading) return;
    getHotGameByQuery(page + 1);
  };
  const getRenderList = () => {
    if (showAll) {
      return list;
    }
    return list.slice(0, len * rowItemLength);
  };
  const firstPlatform = reactExports.useMemo(() => {
    const hasLGames = games.sort((p, n) => gameSortMap[p.name] - gameSortMap[n.name]).filter((g) => {
      var _a;
      return (_a = g.l) == null ? void 0 : _a.length;
    }) || [];
    if ((hasLGames == null ? void 0 : hasLGames.length) > 0) {
      return hasLGames[0];
    }
    return null;
  }, [games, gameSortMap]);
  const toFirstPlatformGame = () => {
    var _a;
    if (firstPlatform) {
      if (specialPlatformType.includes(firstPlatform.name)) {
        navigate("/venuelist?id=".concat(firstPlatform.id));
      } else {
        navigate("/venuegame?id=".concat((_a = firstPlatform.l[0]) == null ? void 0 : _a.id));
      }
    }
  };
  return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.itemBox, id: "Popular", children: !remote && /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.topBarBox, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.leftBox, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.icon, children: /* @__PURE__ */ jsxRuntimeExports.jsx(ActivePopular, { isSelect: true }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: instance.t(item.type) })
      ] }),
      (websetConfig == null ? void 0 : websetConfig.pages_turning_style) == "1" && /* @__PURE__ */ jsxRuntimeExports.jsx(Pagination, { page: state.page, total: state.total, pageSize: state.page_size, onChange: (page2) => {
        getHotGameByQuery(page2);
      }, onClick: toFirstPlatformGame })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "".concat(css.itemsContainer, " hotGamesItemsContainer"), style: { paddingBottom: list.length > 0 ? "20rem" : "0" }, children: getRenderList().map((game, idx) => {
      let isShow = (game == null ? void 0 : game.is_favorites) || false;
      let gameImg = game.img;
      if (renderType === "rect") {
        gameImg = game.img.replace("images-br", "images-br-rect");
      }
      if (renderType === "rect") {
        return /* @__PURE__ */ jsxRuntimeExports.jsx(
          RectGameItem,
          {
            gameNamesMap,
            maintainedMap,
            game,
            idx,
            getPlatLaunchFun
          },
          idx
        );
      }
      return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.gameBox, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsxs(
          "div",
          {
            className: css.logoBox,
            onClick: () => {
              if (maintainedMap[game.platform_id] === 2 || game.maintained === 2) return;
              if (info && +info.balance < (+game.min_admission || 0)) {
                Message.error(instance.t("您的余额低于「最低准入金额」，请存款后才能玩", { amount: game.min_admission, coin: getMoneyUnit(true) }));
                return;
              }
              getPlatLaunchFun(game.platform_id, game.game_id, game);
            },
            children: [
              /* @__PURE__ */ jsxRuntimeExports.jsx(
                "div",
                {
                  onClick: (e) => {
                    e.stopPropagation();
                    btnCollect(game);
                  },
                  className: joinClass(
                    css.game_list_item_hots,
                    isEnlarged ? css.game_list_item_hots_active : ""
                  ),
                  children: isShow && /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { src: "/home/icons/collect_active2.webp" })
                }
              ),
              game.is_recommend === 1 && /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { className: css.game_recommend, src: "/home/icons/recommend.webp" }),
              /* @__PURE__ */ jsxRuntimeExports.jsx(
                Image,
                {
                  loading: /* @__PURE__ */ jsxRuntimeExports.jsx(LoadingImg, { className: css.laodingBox }),
                  isGame: true,
                  src: gameImg,
                  remote: true
                }
              ),
              maintainedMap[game.platform_id] === 2 || game.maintained === 2 ? /* @__PURE__ */ jsxRuntimeExports.jsx(Maintain, {}) : null
            ]
          }
        ),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.nameBox, children: getGameName(game) })
      ] }, game.id);
    }) }),
    (websetConfig == null ? void 0 : websetConfig.pages_turning_style) != "1" && /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.btmLoadMoreBox, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css.tips, children: instance.t("显示{{total}}款{{type}}游戏中的{{num}}款游戏", {
        total: total || 0,
        num: getRenderList().length || 0,
        type: instance.t(item.type)
      }) }),
      total > getRenderList().length ? /* @__PURE__ */ jsxRuntimeExports.jsx(
        LoadMore,
        {
          loading: isLoading,
          className: css.loadMoreBtn,
          onClick: () => {
            limitClicks();
          }
        }
      ) : null
    ] })
  ] }) });
};
export {
  HotGameItem as H,
  RectGameItem as R
};
