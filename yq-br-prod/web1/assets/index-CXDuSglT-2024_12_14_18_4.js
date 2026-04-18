import { q as useUserInfoStore, r as reactExports, y as useNavigate, j as jsxRuntimeExports, Q as getMoneyUnit, as as customToFixed, z as trans, M as Modal, a as joinClass, cJ as getPromoBetApply, H as Message, cK as getPromoWelfareApply, bF as getMemberBankcardList } from "./comps-B8ShbmG--2024_12_14_18_4.js";
import { E as Empty, a as customFormatTimer } from "./index-BcpFmBv5-2024_12_14_18_4.js";
import "./icons-Cdaou_E3-2024_12_14_18_4.js";
const svg_theme_fill_color$1 = "_svg_theme_fill_color_1uoss_3";
const bonus = "_bonus_1uoss_55";
const header = "_header_1uoss_55";
const moneyLabel = "_moneyLabel_1uoss_61";
const totalMoney = "_totalMoney_1uoss_71";
const normalBtn = "_normalBtn_1uoss_77";
const bonusTableBox = "_bonusTableBox_1uoss_114";
const css$1 = {
  svg_theme_fill_color: svg_theme_fill_color$1,
  bonus,
  header,
  moneyLabel,
  totalMoney,
  normalBtn,
  bonusTableBox
};
const svg_theme_fill_color = "_svg_theme_fill_color_1l125_3";
const alreadyGetbonus_list = "_alreadyGetbonus_list_1l125_55";
const emptyBox = "_emptyBox_1l125_55";
const noBankTip = "_noBankTip_1l125_166";
const tip_context = "_tip_context_1l125_174";
const noBankTip_btns = "_noBankTip_btns_1l125_178";
const cancel = "_cancel_1l125_199";
const sure = "_sure_1l125_203";
const css = {
  svg_theme_fill_color,
  alreadyGetbonus_list,
  emptyBox,
  noBankTip,
  tip_context,
  noBankTip_btns,
  cancel,
  sure
};
const List = () => {
  const { token, availableList, getAvailableList, isSetWithdrawPassword, getMissaoList } = useUserInfoStore();
  const [hasBank, setHasBank] = reactExports.useState(false);
  const [showBankPop, setShowBankPop] = reactExports.useState(false);
  const navigate = useNavigate();
  const params = {
    state: "502"
  };
  const data = availableList.d || [];
  const handleGetBonus = (item) => {
    if (!token) {
      useUserInfoStore.setState({ openForRegister: true });
      return;
    }
    if (item && item.promo_id == "189610577209407602" && item["temp_add_record"] + "" === "1") {
      getPromoBetApply({ id: item.promo_id, rid: item.id }).then(([res, , err]) => {
        if (res) {
          Message.success(trans("成功收到"));
          getAvailableList(params);
          getMissaoList();
          return;
        }
      });
    } else {
      getPromoWelfareApply({ id: item.id }).then(([res, , err]) => {
        if (res == "1346") {
          setShowBankPop(true);
          return;
        }
        if (res) {
          Message.success(trans("成功收到"));
          getAvailableList(params);
          getMissaoList();
        }
      });
    }
  };
  const getBankcardList = async () => {
    var _a;
    const bankResult = await getMemberBankcardList();
    bankResult[0] && ((_a = bankResult[0]) == null ? void 0 : _a.length) > 0 && setHasBank(true);
  };
  reactExports.useEffect(() => {
    if (token) {
      getBankcardList();
      getAvailableList(params);
    }
  }, []);
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.alreadyGetbonus_list, children: [
    data.length === 0 ? /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.emptyBox, children: [
      "	",
      /* @__PURE__ */ jsxRuntimeExports.jsx(Empty, {})
    ] }) : data.map((item, index) => {
      return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "alreadyGetbonus_list_row", children: [
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "context_box", children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "title", children: item.title }),
          /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "alreadyGetbonus_list__container", children: [
            /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "alreadyGetbonus_list_bonus", children: [
              /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: "alreadyGetbonus_list_RS", children: getMoneyUnit(true) }),
              /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: "bonus", children: customToFixed(item.amount) })
            ] }),
            /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "alreadyGetbonus_list_date", children: customFormatTimer(item.created_at * 1e3, "DD/MM/YYYY") }),
            /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "alreadyGetbonus_list_status", children: [
              /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { children: [
                trans("来源"),
                ": "
              ] }),
              /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: trans("任务奖励") })
            ] })
          ] })
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          "div",
          {
            className: "alreadyGetbonus_list_btn",
            onClick: () => {
              handleGetBonus(item);
            },
            children: trans("领取")
          }
        )
      ] }, index);
    }),
    /* @__PURE__ */ jsxRuntimeExports.jsx(Modal, { isOpen: showBankPop, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.noBankTip, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.tip_context, children: trans("您需要先链接付款方式才能收到") }),
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.noBankTip_btns, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          "button",
          {
            className: joinClass(css.cancel, "button"),
            onClick: () => {
              setShowBankPop(false);
            },
            children: trans("取消")
          }
        ),
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          "button",
          {
            className: joinClass(css.sure, "button"),
            onClick: () => {
              if (isSetWithdrawPassword) {
                navigate("/withdraw");
              } else {
                navigate("/withdraw-set?to=withdraw");
              }
            },
            children: trans("继续")
          }
        )
      ] })
    ] }) })
  ] });
};
const Bonus = () => {
  const { availableList } = useUserInfoStore();
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.bonus, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$1.header, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css$1.totalMoney, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$1.moneyLabel, children: trans("奖金") }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css$1.totalMoney, children: customToFixed(availableList.agg) })
    ] }) }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css$1.bonusTableBox, children: /* @__PURE__ */ jsxRuntimeExports.jsx(List, {}) })
  ] });
};
export {
  Bonus as default
};
