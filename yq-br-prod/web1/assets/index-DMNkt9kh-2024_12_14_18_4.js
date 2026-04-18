import { V as useTranslation, j as jsxRuntimeExports, Q as getMoneyUnit, as as customToFixed, r as reactExports, q as useUserInfoStore, ac as useSetState, f as dayjs, cL as numAdd, b1 as getMemberRecordTrade } from "./comps-B8ShbmG--2024_12_14_18_4.js";
import { N as NewSelect } from "./index-DAC3Xkn--2024_12_14_18_4.js";
import { L as LoadMore } from "./index-4Dep-BeX-2024_12_14_18_4.js";
import { E as Empty, a as customFormatTimer } from "./index-BcpFmBv5-2024_12_14_18_4.js";
import "./icons-Cdaou_E3-2024_12_14_18_4.js";
const svg_theme_fill_color$1 = "_svg_theme_fill_color_xxrsn_3";
const bonus = "_bonus_xxrsn_55";
const header = "_header_xxrsn_55";
const moneyLabel = "_moneyLabel_xxrsn_67";
const totalMoney = "_totalMoney_xxrsn_77";
const normalBtn = "_normalBtn_xxrsn_87";
const bonusTableBox = "_bonusTableBox_xxrsn_104";
const css$1 = {
  svg_theme_fill_color: svg_theme_fill_color$1,
  bonus,
  header,
  moneyLabel,
  totalMoney,
  normalBtn,
  bonusTableBox
};
const svg_theme_fill_color = "_svg_theme_fill_color_3v264_3";
const alreadyGetbonus_list = "_alreadyGetbonus_list_3v264_55";
const emptyBox = "_emptyBox_3v264_55";
const css = {
  svg_theme_fill_color,
  alreadyGetbonus_list,
  emptyBox
};
const List = ({ data = [] }) => {
  const { t, i18n } = useTranslation();
  const formatFlag = (flag) => {
    const resEl = flagItems.find((item) => +item.value === flag);
    return resEl.value === "279" ? t("任务奖励") : t(resEl.label);
  };
  const getLabel = {
    211: t("platformBonusSite"),
    212: t("upgradeBonus"),
    213: t("birthdayBonus"),
    214: t("monthlyBonus"),
    215: t("redPacketBonus"),
    216: t("maintenanceCompensation"),
    217: t("depositDiscount"),
    218: t("eventBonus"),
    219: t("referralBonus"),
    220: t("bonusAdjustment"),
    221: t("venueBalanceNegativeClearance"),
    222: t("agentBonus"),
    223: t("taskBonus")
  };
  return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.alreadyGetbonus_list, children: data.length === 0 ? /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.emptyBox, children: [
    "	",
    /* @__PURE__ */ jsxRuntimeExports.jsx(Empty, {})
  ] }) : data.map((item, index) => {
    return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "alreadyGetbonus_list_row", children: [
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "context_box", children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "title", children: item.ptitle ? item.ptitle : getLabel[item.ty] || "" }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "alreadyGetbonus_list__container", children: [
          /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "alreadyGetbonus_list_bonus", children: [
            /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: "alreadyGetbonus_list_RS", children: getMoneyUnit(true) }),
            /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: "bonus", children: customToFixed(item.amount) })
          ] }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "alreadyGetbonus_list_date", children: customFormatTimer(item.created_at * 1e3, "MM/DD/YYYY") }),
          /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "alreadyGetbonus_list_status", children: [
            /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { children: [
              t("来源"),
              ": "
            ] }),
            /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: formatFlag(item.flag) })
          ] })
        ] })
      ] }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "alreadyGetbonus_list_btn", children: t("已完成") })
    ] }, index);
  }) });
};
const selectOPtions = [
  { value: 0, label: "今日" },
  { value: 1, label: "昨日" },
  { value: 7, label: "近7日" },
  { value: 15, label: "近15日" },
  { value: 30, label: "近30日" },
  { value: 60, label: "近60日" }
];
const flagItems = [
  // { label: t('存款'), value: '271' },
  // { label: t('取款'), value: '272' },
  // { label: t('转账'), value: '273' },
  { label: "奖金", value: "274" },
  // { label: t('佣金返水'), value: '275' },
  // { label: t('调整'), value: '278' },
  { label: "任务", value: "279" }
];
const AlreadyGetbonus = () => {
  const { t, i18n } = useTranslation();
  const windowSearch = window.location.search;
  const getParams = new URLSearchParams(windowSearch);
  const defaultFlag = getParams.get("flag") || "274";
  const [value, setValue] = reactExports.useState(0);
  const [page, setPage] = reactExports.useState(1);
  useUserInfoStore();
  const [total, setTotal] = reactExports.useState(0);
  const [thisState, setThisState] = useSetState({
    start_time: "",
    end_time: "",
    totalMoney: 0
  });
  const { start_time, end_time, totalMoney: totalMoney2 } = thisState;
  const [params, setParams] = reactExports.useState({
    ty: "0",
    flag: defaultFlag,
    page_size: 15
  });
  const [data, setData] = reactExports.useState([]);
  const getList = async () => {
    if (page === 1) {
      setTotal(0);
    }
    if (!start_time) return;
    const [res, err] = await getMemberRecordTrade({
      ...params,
      page,
      start_time,
      end_time,
      state: 1
    });
    if (page === 1) {
      setTotal(res.t);
      setData(res.d || []);
      return;
    }
    setData([...data, ...res.d]);
  };
  const search = () => {
    if (page !== 1) {
      setData([]);
      setPage(1);
    } else {
      getList();
    }
  };
  reactExports.useEffect(() => {
    setThisState({
      start_time: dayjs().subtract(value, "day").format("YYYY-MM-DD") + " 00:00:00",
      end_time: dayjs().format("YYYY-MM-DD") + " 23:59:59"
    });
  }, [value]);
  reactExports.useEffect(() => {
    search();
  }, [start_time, params.flag]);
  reactExports.useEffect(() => {
    if (start_time) {
      getList();
    }
  }, [page]);
  reactExports.useEffect(() => {
    let count = 0;
    data.forEach((item) => {
      count = numAdd(count, item.amount);
    });
    setThisState({ totalMoney: count });
  }, [data]);
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.bonus, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.header, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx(
        NewSelect,
        {
          items: selectOPtions,
          value,
          onChange: (_v) => {
            setValue(_v);
          }
        }
      ),
      /* @__PURE__ */ jsxRuntimeExports.jsx(
        NewSelect,
        {
          items: flagItems,
          value: params.flag,
          onChange: (_value) => {
            setParams({
              ...params,
              flag: _value
            });
          }
        }
      ),
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.totalMoney, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$1.moneyLabel, children: t("奖金") }),
        /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$1.totalMoney, children: customToFixed(totalMoney2) })
      ] })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$1.bonusTableBox, children: /* @__PURE__ */ jsxRuntimeExports.jsx(List, { data }) }),
    total > data.length && /* @__PURE__ */ jsxRuntimeExports.jsx(
      LoadMore,
      {
        onClick: () => {
          setPage(page + 1);
        }
      }
    )
  ] });
};
export {
  AlreadyGetbonus as default,
  flagItems
};
