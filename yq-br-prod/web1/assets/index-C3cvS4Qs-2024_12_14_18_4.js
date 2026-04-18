import { V as useTranslation, q as useUserInfoStore, y as useNavigate, a1 as useGuideStore, r as reactExports, j as jsxRuntimeExports, a as joinClass, I as Image, as as customToFixed, cY as currentUnitConfig, M as Modal, H as Message, cK as getPromoWelfareApply, bF as getMemberBankcardList } from "./comps-B8ShbmG--2024_12_14_18_4.js";
import "./icons-Cdaou_E3-2024_12_14_18_4.js";
const svg_theme_fill_color = "_svg_theme_fill_color_1w32m_3";
const missao_container = "_missao_container_1w32m_55";
const left_nav = "_left_nav_1w32m_59";
const left_nav_item = "_left_nav_item_1w32m_62";
const left_nav_button = "_left_nav_button_1w32m_83";
const rightContainer = "_rightContainer_1w32m_96";
const rightItem = "_rightItem_1w32m_102";
const missao_title_box = "_missao_title_box_1w32m_114";
const missao_bonus = "_missao_bonus_1w32m_123";
const missao_bonus_label = "_missao_bonus_label_1w32m_126";
const missao_bonus_val = "_missao_bonus_val_1w32m_129";
const missao_disabled = "_missao_disabled_1w32m_137";
const btn = "_btn_1w32m_141";
const oper_btn = "_oper_btn_1w32m_141";
const bonus_btn = "_bonus_btn_1w32m_141";
const rule_details = "_rule_details_1w32m_201";
const rule_title = "_rule_title_1w32m_208";
const rule_context = "_rule_context_1w32m_211";
const noBankTip = "_noBankTip_1w32m_215";
const tip_context = "_tip_context_1w32m_223";
const noBankTip_btns = "_noBankTip_btns_1w32m_227";
const cancel = "_cancel_1w32m_248";
const sure = "_sure_1w32m_252";
const css = {
  svg_theme_fill_color,
  missao_container,
  left_nav,
  left_nav_item,
  left_nav_button,
  rightContainer,
  rightItem,
  missao_title_box,
  missao_bonus,
  missao_bonus_label,
  missao_bonus_val,
  missao_disabled,
  btn,
  oper_btn,
  bonus_btn,
  rule_details,
  rule_title,
  rule_context,
  noBankTip,
  tip_context,
  noBankTip_btns,
  cancel,
  sure
};
const Missao = () => {
  const { t } = useTranslation();
  const {
    // info,
    // theme,
    token,
    missaoList,
    getMissaoList,
    getAvailableList,
    appUrl,
    isSetWithdrawPassword
  } = useUserInfoStore();
  const navigate = useNavigate();
  const { promptEvent } = useGuideStore();
  const [hasBank, setHasBank] = reactExports.useState(false);
  const [showBankPop, setShowBankPop] = reactExports.useState(false);
  const handleGetBonus = (item) => {
    if (!!item["check_deposit"] && item["first_deposit_done"] !== 1) return Message.error(t("请完成首次充值，即可领取彩金"));
    if (!token) return useUserInfoStore.setState({ openForRegister: true });
    const tag = item["welfare_id"] + "" === "9" && { "3187-errorTag": "3187-errorTag" };
    getPromoWelfareApply({ id: item.id }, tag).then(([res, err]) => {
      if (err + "" === "3187" && tag) {
        return Message.error(t("3187-errorTag"));
      }
      if (res == "1346") {
        return setShowBankPop(true);
      }
      if (res) {
        Message.success(t("成功收到") + "~");
        getMissaoList();
        getAvailableList({ state: "502" });
      }
    });
  };
  const handleOper = (item) => {
    if (!token) {
      return useUserInfoStore.setState({ openForRegister: true });
    }
    if (item.welfare_id === "1") {
      window.open(appUrl, "_blank");
    } else if (item.welfare_id === "2") {
      if (promptEvent) {
        promptEvent.prompt();
      } else {
        console.error("浏览器不支持!");
      }
    } else if (item["welfare_id"] + "" === "9") {
      handleGetBonus(item);
    } else {
      const linkList = {
        "3": !!isSetWithdrawPassword ? "/withdraw" : "/withdraw-set",
        "4": "/personal-information",
        "5": !!isSetWithdrawPassword ? "/withdraw" : "/withdraw-set",
        "6": !!isSetWithdrawPassword ? "/withdraw" : "/withdraw-set",
        "7": "/personal-information",
        "8": "/personal-information",
        "10": "/personal-information"
      };
      navigate(linkList[item == null ? void 0 : item.welfare_id]);
    }
  };
  const getBankcardList = async () => {
    var _a;
    const bankResult = await getMemberBankcardList();
    bankResult[0] && ((_a = bankResult[0]) == null ? void 0 : _a.length) > 0 && setHasBank(true);
  };
  reactExports.useEffect(() => {
    getMissaoList();
    if (token) {
      getBankcardList();
    }
  }, []);
  return /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.missao_container, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.left_nav, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.left_nav_item), children: /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { children: [
          " ",
          t("新玩家的福利")
        ] }) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          "div",
          {
            className: joinClass(css.left_nav_button, "button"),
            onClick: () => {
              if (!token) {
                return useUserInfoStore.setState({ openForRegister: true });
              }
              navigate("/alreadyGetbonus?flag=279");
            },
            children: t("历史记录")
          }
        )
      ] }),
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.rightContainer, children: [
        missaoList.map((item, index) => /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.rightItem, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.rightItem_info, children: [
            /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.missao_title_box, children: [
              /* @__PURE__ */ jsxRuntimeExports.jsx(
                Image,
                {
                  className: css.iconImg,
                  src: "/home/icons/".concat(item.icon)
                }
              ),
              /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: item.title })
            ] }),
            /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.missao_bonus, children: [
              /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css.missao_bonus_label, children: t("奖金") }),
              /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: joinClass(css.missao_bonus_val, item.state === 503 ? css.missao_disabled : ""), children: customToFixed(item.amount) })
            ] })
          ] }),
          item.state === 501 ? /* @__PURE__ */ jsxRuntimeExports.jsx(
            "button",
            {
              className: joinClass(css.oper_btn, "button"),
              onClick: () => {
                handleOper(item);
              },
              children: t("待领取")
            }
          ) : /* @__PURE__ */ jsxRuntimeExports.jsx(
            "button",
            {
              className: joinClass(css.bonus_btn, "button"),
              disabled: item.state !== 502,
              onClick: () => {
                handleGetBonus(item);
              },
              children: item.state === 502 ? t("领取") : t("已结算")
            }
          )
        ] }, index)),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.rule_details, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsxs("p", { className: css.rule_title, children: [
            " ",
            t("任务规则_1", { utc: currentUnitConfig["utc"] }),
            ":"
          ] }),
          /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { className: css.rule_context, children: [
            " ",
            t("任务规则_2")
          ] }),
          /* @__PURE__ */ jsxRuntimeExports.jsxs("p", { className: css.rule_title, children: [
            t("任务规则_3"),
            ":"
          ] }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css.rule_context, children: t("任务规则_4") }),
          /* @__PURE__ */ jsxRuntimeExports.jsxs("p", { className: css.rule_title, children: [
            t("任务规则_5"),
            ":"
          ] }),
          /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { className: css.rule_context, children: [
            t("任务规则_6"),
            /* @__PURE__ */ jsxRuntimeExports.jsx("br", {}),
            t("任务规则_7"),
            /* @__PURE__ */ jsxRuntimeExports.jsx("br", {}),
            t("任务规则_8"),
            /* @__PURE__ */ jsxRuntimeExports.jsx("br", {}),
            t("任务规则_9"),
            /* @__PURE__ */ jsxRuntimeExports.jsx("br", {}),
            t("任务规则_10"),
            /* @__PURE__ */ jsxRuntimeExports.jsx("br", {}),
            t("任务规则_11")
          ] })
        ] })
      ] })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsx(Modal, { isOpen: showBankPop, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.noBankTip, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.tip_context, children: t("您需要先链接付款方式才能收到") }),
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.noBankTip_btns, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          "button",
          {
            className: joinClass(css.cancel, "button"),
            onClick: () => {
              setShowBankPop(false);
            },
            children: t("取消")
          }
        ),
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          "button",
          {
            className: joinClass(css.sure, "button"),
            onClick: () => {
              navigate(isSetWithdrawPassword ? "/withdraw" : "/withdraw-set?to=withdraw");
            },
            children: t("继续")
          }
        )
      ] })
    ] }) })
  ] });
};
export {
  Missao as default
};
