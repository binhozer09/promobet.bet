import { r as reactExports, q as useUserInfoStore, j as jsxRuntimeExports, a as joinClass, H as Message, a4 as instance, Q as getMoneyUnit, I as Image, bM as Maintain, bN as getGameName, bO as favoritesremove, bP as favorites, c9 as useInViewport, y as useNavigate, o as useGameStore, cr as getGameTypeByName, cZ as useInfiniteScroll, c_ as useDebounceFn, bV as getSlotSearch, bS as getPlatLaunch, C as Cache, bU as historySave, i as getQueryVariable, z as trans } from "./comps-B8ShbmG--2024_12_14_18_4.js";
import { I as InnerPageWithBackContext } from "./index-DgUEEG5U-2024_12_14_18_4.js";
import { S as SearchInput } from "./index-DlqGj1la-2024_12_14_18_4.js";
import { L as LoadingImg } from "./index-Bq-a07OY-2024_12_14_18_4.js";
import { a as specialPlatform, G as GameTabs } from "./config-B4NCyDU0-2024_12_14_18_4.js";
import { dv as TudoIcon, cc as BlockchainIcon, dw as ActionBlockchaim, c5 as CartasIcon, dx as ActionPoker, bZ as SlotsIcon, cg as ActiveSlots, c3 as PescariaIcon, dy as ActionFishing } from "./icons-Cdaou_E3-2024_12_14_18_4.js";
import { L as LoadMore } from "./index-4Dep-BeX-2024_12_14_18_4.js";
import "./index-CXpt7Cav-2024_12_14_18_4.js";
import "./index-DyG_R4zg-2024_12_14_18_4.js";
import "./index-CGmeRIXa-2024_12_14_18_4.js";
import "./index-BcpFmBv5-2024_12_14_18_4.js";
import "./index-CsbwU_08-2024_12_14_18_4.js";
const svg_theme_fill_color$1 = "_svg_theme_fill_color_c9282_3";
const subclass = "_subclass_c9282_55";
const siderbar = "_siderbar_c9282_89";
const subMenu = "_subMenu_c9282_249";
const game_list_scroll = "_game_list_scroll_c9282_303";
const game_recommend$1 = "_game_recommend_c9282_453";
const identifier$1 = "_identifier_c9282_1";
const css$1 = {
  svg_theme_fill_color: svg_theme_fill_color$1,
  subclass,
  siderbar,
  subMenu,
  game_list_scroll,
  game_recommend: game_recommend$1,
  identifier: identifier$1
};
const svg_theme_fill_color = "_svg_theme_fill_color_1oxk6_3";
const game_list_item = "_game_list_item_1oxk6_55";
const game_recommend = "_game_recommend_1oxk6_201";
const identifier = "_identifier_1oxk6_1";
const css = {
  svg_theme_fill_color,
  game_list_item,
  game_recommend,
  identifier
};
const RectGameItem = ({ item, gameNamesMap, maintainedMap, getPlatLaunchFun, delFavorites = (id) => {
}, FavoritosType = true }) => {
  const gameImg = item.img.replace("images-br/", "images-br-rect/");
  const [isShow, setIsShow] = reactExports.useState(false);
  const [isEnlarged, setIsEnlarged] = reactExports.useState(false);
  reactExports.useEffect(() => {
    if (item.is_favorites) {
      setIsShow(true);
    } else {
      setIsShow(false);
    }
  }, [item]);
  const { token, info } = useUserInfoStore();
  const btnCollect = async (game) => {
    if (!token) {
      useUserInfoStore.setState({ openForLogin: true });
      return;
    }
    if (isShow) {
      const [res, err] = await favoritesremove({ pid: game.platform_id, code: game.game_id });
      if (res) {
        if (FavoritosType) {
          setIsShow(false);
        }
        setIsEnlarged(false);
        delFavorites(game.game_id);
      }
    } else {
      const [res, err] = await favorites({ pid: game.platform_id, code: game.game_id });
      if (res) setIsShow(true);
      setIsEnlarged(true);
    }
  };
  return /* @__PURE__ */ jsxRuntimeExports.jsx(
    "div",
    {
      className: joinClass(css.game_list_item, "button"),
      onClick: () => {
        if (maintainedMap[item.platform_id] === 2 || item.maintained === 2) return;
        if (info && +info.balance < (+item.min_admission || 0)) {
          Message.error(instance.t("您的余额低于「最低准入金额」，请存款后才能玩", { amount: item.min_admission, coin: getMoneyUnit(true) }));
          return;
        }
        getPlatLaunchFun(item.platform_id, item.game_id, item);
      },
      children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "game_img_box", children: [
        item.is_recommend === 1 && /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { className: css.game_recommend, src: "/home/icons/recommend.webp" }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { onClick: (e) => {
          e.stopPropagation();
          btnCollect(item);
        }, className: joinClass("game_list_item_hots", isEnlarged ? "game_list_item_hots_active" : ""), children: isShow && /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { src: "/home/icons/collect_active2.webp" }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { loading: /* @__PURE__ */ jsxRuntimeExports.jsx(LoadingImg, { className: "laodingBox" }), src: gameImg, remote: true, isGame: true }),
        maintainedMap[item.platform_id] === 2 || item.maintained === 2 ? /* @__PURE__ */ jsxRuntimeExports.jsx(Maintain, { className: "maintain" }) : null,
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "gameBox_s", children: specialPlatform.includes(item.id) ? item.name : getGameName(item) })
      ] })
    }
  );
};
const getTypeName = (type) => {
  switch (type) {
    case 2:
      return {
        name: "Pescaria",
        IconsCom: PescariaIcon,
        logo: "fishing_active.webp",
        ActiveIcon: ActionFishing
      };
    case 3:
      return {
        name: "Slots",
        IconsCom: SlotsIcon,
        logo: "slots_active.webp",
        ActiveIcon: ActiveSlots
      };
    case 5:
      return {
        name: "Cartas",
        IconsCom: CartasIcon,
        logo: "cartas_active.webp",
        ActiveIcon: ActionPoker
      };
    case 9:
      return {
        name: "Blockchain",
        IconsCom: BlockchainIcon,
        logo: "blockchain_active.webp",
        ActiveIcon: ActionBlockchaim
      };
    default:
      return {};
  }
};
const SiderBar = ({ onChange, value, list }) => {
  const { gameNamesMap } = useGameStore();
  const changeSiderBar = (bar) => {
    onChange(bar);
  };
  const siderList = reactExports.useMemo(() => {
    var _a;
    const type = (_a = list[0]) == null ? void 0 : _a.game_type;
    const logoItem = {
      id: "0",
      //pid 等于 0 查询所有该类型场馆下的游戏
      game_type: type,
      ...getTypeName(type)
    };
    return [logoItem, ...list];
  }, [list]);
  return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$1.siderbar, children: siderList.map((item, index) => {
    const IconsCom = item.IconsCom || /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, {});
    const ActiveIcon = item.ActiveIcon || /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, {});
    const isSelect = value === item.id;
    return /* @__PURE__ */ jsxRuntimeExports.jsxs(
      "div",
      {
        className: "siderbar-item button ".concat(value === item.id && "siderbar-item-active"),
        onClick: (e) => {
          changeSiderBar(item);
        },
        // 如果是首个siderbar 则查询所有场馆游戏且使用场馆类型icon
        children: [
          !index ? isSelect ? /* @__PURE__ */ jsxRuntimeExports.jsx(ActiveIcon, { className: "logo" }) : /* @__PURE__ */ jsxRuntimeExports.jsx(IconsCom, { isSelect, className: "logo" }) : /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { src: "/game/logo/".concat(gameNamesMap[item.id], ".webp") }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$1.gameTabStyle, children: instance.t(item.name) })
        ]
      },
      index
    );
  }) });
};
const SubMenu = ({ options, value, onClick }) => {
  useUserInfoStore();
  return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$1.subMenu, children: options.map((item) => {
    item.IconsCom || "";
    item.value === value;
    return /* @__PURE__ */ jsxRuntimeExports.jsx(
      "div",
      {
        className: "button ".concat(item.value === value ? "active" : ""),
        onClick: () => {
          onClick(item);
        },
        children: /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$1.gameTabStyle, children: instance.t(item.label) })
      },
      item.value
    );
  }) });
};
const PAGE_SIZE = 51;
const subOptions = [
  {
    icon: "/home/icons/all_active.webp",
    blue: "/home/icons/all_active_blue.webp",
    IconsCom: TudoIcon,
    value: 0,
    label: "Tudo"
  },
  // {
  //   icon: `/home/icons/hot_active.webp`,
  //   blue: `/home/icons/hot_blue_active.webp`,
  //   IconsCom: HotIcon,
  //   value: 2,
  //   label: 'Popular'
  // },
  {
    value: 3,
    label: "Recente"
  },
  {
    value: 4,
    label: "Favoritos"
  }
];
const SearchGame = ({ platType, paramPid, tab }) => {
  const { token } = useUserInfoStore();
  const moreRef = reactExports.useRef(null);
  const [inViewport] = useInViewport(moreRef);
  const navigate = useNavigate();
  const [siderList, setSiderList] = reactExports.useState([]);
  const initState = {
    pid: paramPid || "0",
    flag: 0,
    keyword: ""
  };
  const {
    games,
    gameNamesMap,
    maintainedMap,
    renderType,
    selectGame: listState
  } = useGameStore();
  const updateListState = (game) => {
    useGameStore.setState({
      selectGame: game
    });
  };
  const currentPlatform = reactExports.useMemo(() => {
    const slide = siderList;
    const s = slide.find((item) => item.id === listState.pid);
    return s || null;
  }, [listState, siderList]);
  reactExports.useEffect(() => {
    const g = siderList.find((item) => item.id == listState.pid);
    if (!g) {
      updateListState({ ...initState });
    }
  }, [siderList]);
  reactExports.useEffect(() => {
    return () => {
      updateListState({
        ...initState,
        pid: "-1"
      });
    };
  }, []);
  reactExports.useEffect(() => {
    var _a;
    if (games.length === 0) return;
    const list2 = ((_a = games.find((item) => item.id === +platType)) == null ? void 0 : _a.l) || [];
    const l = list2.map((res, idx) => {
      let name = getGameTypeByName(res.name);
      return {
        ...res,
        name
      };
    });
    setSiderList(l);
  }, [games, platType]);
  const {
    reload: searchGame,
    data,
    loading,
    loadMore,
    loadingMore
  } = useInfiniteScroll(
    (d) => {
      const page = d ? Math.ceil(d.list.length / PAGE_SIZE) + 1 : 1;
      if (listState.pid) {
        return getGameList({
          ...listState,
          page_size: PAGE_SIZE,
          page,
          game_type: platType
        });
      }
    },
    { reloadDeps: [listState.pid, listState.flag, platType], manual: true }
  );
  const [list, setList] = reactExports.useState([]);
  reactExports.useEffect(() => {
    setList((data == null ? void 0 : data.list) || []);
  }, [data]);
  const hasMore = data && data.list.length < data.total;
  const { run: getGameList } = useDebounceFn(
    async (params) => {
      if (currentPlatform && specialPlatform.includes(currentPlatform.id)) {
        return {
          list: [currentPlatform],
          total: 1
        };
      }
      const [result] = await getSlotSearch(params, { useLoading: true });
      return {
        list: result.d,
        total: result.t
      };
    },
    { wait: 300, leading: true }
  );
  const changeMenu = (cur) => {
    if (cur.value !== listState.flag) {
      updateListState({
        ...initState,
        flag: cur.value
      });
    }
  };
  const handleInput = (val) => {
    updateListState({
      ...listState,
      keyword: val
    });
  };
  const handleSearch = () => {
    searchGame();
  };
  const handleKeydown = (e) => {
    if (e.keyCode === 13 || e.key === "Enter") {
      searchGame();
    }
  };
  const handleChangeBar = async (plat) => {
    updateListState({
      ...initState,
      pid: plat.id
    });
  };
  const getPlatLaunchFun = async (id, code = "", item) => {
    if (!token) {
      useUserInfoStore.setState({ openForLogin: true });
      return;
    }
    let params = { id: item.platform_id, code: item.game_id };
    if (specialPlatform.includes(item.id)) {
      params = { id: item.id };
    }
    const [res, error] = await getPlatLaunch(params, { useLoading: true });
    if (res) {
      Cache.set("gameStart", res);
      navigate("/gameStart");
      await historySave({ pid: id, code });
    }
  };
  reactExports.useEffect(() => {
    if (inViewport && hasMore) {
      loadMore();
    }
  }, [inViewport, hasMore]);
  const { info } = useUserInfoStore();
  const handleDelteFav = (id) => {
    if (listState.flag == 4) {
      const newlist = list.filter((item) => item.game_id !== id);
      setList(newlist);
    }
  };
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
        if (listState.flag == 4) {
          const newlist = list.filter((item) => item.id !== game.id);
          console.log(newlist);
          setList(newlist);
        } else {
          list.map((item, idx) => {
            if (item.id === game.id) {
              list[idx].is_favorites = false;
            }
          });
          updateListState({
            ...listState
          });
        }
      }
    } else {
      const [res, err] = await favorites({
        pid: game.platform_id,
        code: game.game_id
      });
      if (res) {
        list.map((item, idx) => {
          if (item.id === game.id) {
            list[idx].is_favorites = true;
          }
        });
        updateListState({
          ...listState
        });
      }
      setIsEnlarged(true);
    }
  };
  if (!games || games.length === 0 || siderList.length === 0) {
    return null;
  }
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.subclass, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx(
      SearchInput,
      {
        value: listState.keyword,
        placeholder: instance.t("搜索游戏"),
        onClear: searchGame,
        onInput: handleInput,
        onSearch: handleSearch,
        onKeyDown: handleKeydown
      }
    ),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "flex_box", style: { position: "relative" }, children: [
      siderList.length !== 0 && /* @__PURE__ */ jsxRuntimeExports.jsx(
        SiderBar,
        {
          onChange: handleChangeBar,
          list: siderList,
          value: listState.pid
        }
      ),
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "container", children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "form_line", children: /* @__PURE__ */ jsxRuntimeExports.jsx(
          SubMenu,
          {
            options: subOptions,
            value: listState.flag,
            onClick: changeMenu
          }
        ) }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.game_list_scroll, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "game_list", children: list.map((item, idx) => {
            let isShow = (item == null ? void 0 : item.is_favorites) || false;
            if (renderType === "rect") {
              return /* @__PURE__ */ jsxRuntimeExports.jsx(
                RectGameItem,
                {
                  item,
                  gameNamesMap,
                  delFavorites: handleDelteFav,
                  FavoritosType: listState.flag == 4 ? false : true,
                  maintainedMap,
                  getPlatLaunchFun
                },
                idx
              );
            }
            return /* @__PURE__ */ jsxRuntimeExports.jsxs(
              "div",
              {
                className: "game_list_item button",
                onClick: () => {
                  if (maintainedMap[item.platform_id] === 2 || item.maintained === 2) return;
                  if (info && +info.balance < (+item.min_admission || 0)) {
                    Message.error(instance.t("您的余额低于「最低准入金额」，请存款后才能玩", { amount: item.min_admission, coin: getMoneyUnit(true) }));
                    return;
                  }
                  getPlatLaunchFun(item.platform_id, item.game_id, item);
                },
                children: [
                  /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "game_img_box", children: [
                    item.is_recommend === 1 && /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { className: css$1.game_recommend, src: "/home/icons/recommend.webp" }),
                    /* @__PURE__ */ jsxRuntimeExports.jsx(
                      "div",
                      {
                        onClick: (e) => {
                          e.stopPropagation();
                          btnCollect(item);
                        },
                        className: joinClass(
                          "game_list_item_hots",
                          isEnlarged ? "game_list_item_hots_active" : ""
                        ),
                        children: isShow && /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { src: "/home/icons/collect_active2.webp" })
                      }
                    ),
                    /* @__PURE__ */ jsxRuntimeExports.jsx(
                      Image,
                      {
                        loading: /* @__PURE__ */ jsxRuntimeExports.jsx(LoadingImg, { className: "laodingBox" }),
                        src: item.img,
                        remote: true,
                        isGame: true
                      }
                    ),
                    maintainedMap[item.platform_id] === 2 || item.maintained === 2 ? /* @__PURE__ */ jsxRuntimeExports.jsx(Maintain, {}) : null
                  ] }),
                  /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "game_list_item_name", children: specialPlatform.includes(item.id) ? item.name : getGameName(item) })
                ]
              },
              idx
            );
          }) }),
          /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "load_more", children: [
            /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: instance.t("显示{{total}}款{{type}}游戏中的{{num}}款游戏", {
              total: (data == null ? void 0 : data.total) || 0,
              num: (data == null ? void 0 : data.list.length) || 0,
              type: instance.t(tab.text)
            }) }),
            hasMore && /* @__PURE__ */ jsxRuntimeExports.jsx(
              LoadMore,
              {
                ref: moreRef,
                onClick: loadMore,
                disabled: loadingMore,
                className: "load_btn"
              }
            )
          ] })
        ] })
      ] })
    ] })
  ] });
};
const PlatformList = (props) => {
  const { games } = useGameStore();
  const { setTitle } = reactExports.useContext(InnerPageWithBackContext);
  const id = getQueryVariable("id");
  const pid = getQueryVariable("pid");
  const [item, setItem] = reactExports.useState(null);
  const [tab, setTab] = reactExports.useState({});
  reactExports.useEffect(() => {
    if (games && item) {
      setTimeout(() => {
        const g = GameTabs.find((_item) => _item.type === item.name);
        setTab(g || {});
        setTitle(trans(g == null ? void 0 : g.type));
      }, 300);
    }
  }, [games, item]);
  const gameItem = games.find((item2) => "".concat(item2.id) === "".concat(id));
  if (!gameItem) {
    return null;
  } else {
    if (!item) {
      setItem(gameItem);
    }
  }
  if (!games || games.length === 0) {
    return null;
  }
  return /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, { children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { style: {
    display: "flex",
    flexDirection: "column",
    fontSize: "14rem"
  }, children: item ? /* @__PURE__ */ jsxRuntimeExports.jsx(SearchGame, { paramPid: pid, platType: id, tab }, pid) : null }) });
};
export {
  PlatformList as default
};
