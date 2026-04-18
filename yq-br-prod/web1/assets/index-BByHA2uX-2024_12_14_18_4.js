import { V as useTranslation, q as useUserInfoStore, r as reactExports, y as useNavigate, o as useGameStore, aR as getGamesNav, j as jsxRuntimeExports, as as customToFixed, Q as getMoneyUnit, aS as numMulti, aT as numDiv, cA as getRebateConfig } from "./comps-B8ShbmG--2024_12_14_18_4.js";
import { r as rebateTypes } from "./config-B4NCyDU0-2024_12_14_18_4.js";
import { $ as MoreInMineIcon } from "./icons-Cdaou_E3-2024_12_14_18_4.js";
import { b as LeftNav } from "./index-DgUEEG5U-2024_12_14_18_4.js";
import "./index-CXpt7Cav-2024_12_14_18_4.js";
import "./index-DyG_R4zg-2024_12_14_18_4.js";
import "./index-4Dep-BeX-2024_12_14_18_4.js";
import "./index-CGmeRIXa-2024_12_14_18_4.js";
import "./index-BcpFmBv5-2024_12_14_18_4.js";
import "./index-CsbwU_08-2024_12_14_18_4.js";
import "./index-Bq-a07OY-2024_12_14_18_4.js";
const svg_theme_fill_color = "_svg_theme_fill_color_1mx77_3";
const container = "_container_1mx77_55";
const header = "_header_1mx77_59";
const moreDetail = "_moreDetail_1mx77_88";
const img = "_img_1mx77_100";
const listContainer = "_listContainer_1mx77_117";
const left_nav = "_left_nav_1mx77_122";
const rightContainer = "_rightContainer_1mx77_136";
const rightItem = "_rightItem_1mx77_141";
const first = "_first_1mx77_169";
const index = "_index_1mx77_180";
const second = "_second_1mx77_184";
const css = {
  svg_theme_fill_color,
  container,
  header,
  moreDetail,
  img,
  listContainer,
  left_nav,
  rightContainer,
  rightItem,
  first,
  index,
  second
};
const BackRate = () => {
  const { t, i18n } = useTranslation();
  const { info, theme, fetchRebateConfig } = useUserInfoStore();
  reactExports.useState([]);
  const [plat_type, setPlat_type] = reactExports.useState(3);
  const [data, setData] = reactExports.useState([]);
  const navigate = useNavigate();
  const { games, gameSortMap, gameOpenMap } = useGameStore();
  const [leftList, setLeftList] = reactExports.useState([]);
  const [activityMap, setActivityMap] = reactExports.useState([]);
  reactExports.useEffect(() => {
    getGamesNav().then(([res]) => {
      setActivityMap(res);
    });
  }, []);
  const getConfig = async (game_type) => {
    const [res, err] = await getRebateConfig({
      game_type
    });
    if (err) return;
    setData(res || []);
  };
  const handleClick = (id) => {
    setPlat_type(id);
  };
  reactExports.useEffect(() => {
    getConfig(plat_type);
  }, [plat_type]);
  reactExports.useEffect(() => {
    if (!activityMap.length) return;
    const newArr = [
      ...rebateTypes.sort((a, b) => {
        return (a.sort || gameSortMap[a.type] || 0) - (b.sort || gameSortMap[b.type] || 0);
      }).filter((item) => {
        var _a;
        const id = (_a = games.find((i) => i.name === item.type)) == null ? void 0 : _a.id;
        return activityMap.some((item2) => item2.id === id);
      }).map((item) => {
        var _a;
        return {
          label: item.text,
          icon: item.icon,
          value: (_a = games.find((game) => game.name === item.type)) == null ? void 0 : _a.id,
          IconsCom: item.IconsCom
        };
      })
    ];
    setLeftList(newArr);
  }, [games, activityMap]);
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.container, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.header, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
        t("获得优惠"),
        /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: (info == null ? void 0 : info.rebate_amount) && customToFixed(info.rebate_amount) || "0,00" })
      ] }),
      /* @__PURE__ */ jsxRuntimeExports.jsxs(
        "div",
        {
          className: css.moreDetail,
          onClick: () => {
            navigate("/record-betting?to=back-rate?tag=4");
          },
          children: [
            t("详情"),
            /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.img, children: /* @__PURE__ */ jsxRuntimeExports.jsx(MoreInMineIcon, {}) })
          ]
        }
      )
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.listContainer, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx(LeftNav, { className: css.left_nav, selectId: plat_type, dataList: leftList, onChange: handleClick }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.rightContainer, children: data.map((item, index2) => /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.rightItem, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.first, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: t("等级") }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.index, children: index2 + 1 })
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.second, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
            /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
              t("有效投注"),
              " (",
              getMoneyUnit(true),
              ")"
            ] }),
            /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { children: [
              numMulti(item.bet_amount, 1e4),
              " +"
            ] })
          ] }),
          /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
            /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: t("现金返还率") }),
            /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { children: [
              parseFloat(numDiv(item.rebate_amount, 100).toFixed(4)),
              "%"
            ] })
          ] })
        ] })
      ] }, index2)) })
    ] })
  ] });
};
export {
  BackRate as default
};
