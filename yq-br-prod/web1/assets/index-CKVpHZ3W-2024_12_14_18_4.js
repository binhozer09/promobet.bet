import { V as useTranslation, q as useUserInfoStore, y as useNavigate, j as jsxRuntimeExports, as as customToFixed, r as reactExports, a as joinClass, z as trans, cl as getVipConfig } from "./comps-B8ShbmG--2024_12_14_18_4.js";
import { V as VipIcon } from "./index-DDObR_yo-2024_12_14_18_4.js";
import "./icons-Cdaou_E3-2024_12_14_18_4.js";
const svg_theme_fill_color = "_svg_theme_fill_color_14td9_3";
const vip_page = "_vip_page_14td9_55";
const flex = "_flex_14td9_84";
const flex1 = "_flex1_14td9_88";
const flexCenter = "_flexCenter_14td9_92";
const vipProgressContainer = "_vipProgressContainer_14td9_98";
const progressTitle = "_progressTitle_14td9_104";
const vipProgress = "_vipProgress_14td9_98";
const vipImg = "_vipImg_14td9_124";
const ico = "_ico_14td9_127";
const progressWidth = "_progressWidth_14td9_131";
const progressContent = "_progressContent_14td9_134";
const progress = "_progress_14td9_104";
const progressCon = "_progressCon_14td9_134";
const title1 = "_title1_14td9_157";
const special = "_special_14td9_157";
const title2 = "_title2_14td9_161";
const vipleftspan = "_vipleftspan_14td9_180";
const vipTip = "_vipTip_14td9_185";
const nextMoney = "_nextMoney_14td9_190";
const progressComponent = "_progressComponent_14td9_201";
const content = "_content_14td9_211";
const c_list = "_c_list_14td9_243";
const fullLevel = "_fullLevel_14td9_322";
const tips = "_tips_14td9_331";
const amount = "_amount_14td9_335";
const bestImg = "_bestImg_14td9_341";
const bestContent = "_bestContent_14td9_345";
const css = {
  svg_theme_fill_color,
  vip_page,
  flex,
  flex1,
  flexCenter,
  vipProgressContainer,
  progressTitle,
  vipProgress,
  vipImg,
  ico,
  progressWidth,
  progressContent,
  progress,
  progressCon,
  title1,
  special,
  title2,
  vipleftspan,
  vipTip,
  nextMoney,
  progressComponent,
  content,
  c_list,
  fullLevel,
  tips,
  amount,
  bestImg,
  bestContent
};
const VipProgress = () => {
  const { t, i18n } = useTranslation();
  const { info } = useUserInfoStore();
  const propgressBet = reactExports.useMemo(() => {
    return (info == null ? void 0 : info.current_bet) / (info == null ? void 0 : info.next_bet_requirement) * 100;
  }, [info]);
  const propgressDeposit = reactExports.useMemo(() => {
    return (info == null ? void 0 : info.current_deposit) / (info == null ? void 0 : info.next_deposit_requirement) * 100;
  }, [info]);
  return /* @__PURE__ */ jsxRuntimeExports.jsxs(
    "section",
    {
      className: joinClass(
        css.vipProgressContainer,
        (info == null ? void 0 : info.level) == 15 ? css.bestContent : ""
      ),
      children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.progressTitle, children: t("当前水平") }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs(
          "div",
          {
            className: joinClass(
              css.vipProgress,
              (info == null ? void 0 : info.level) != 15 ? css.flexCenter : ""
            ),
            children: [
              /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.vipImg, children: /* @__PURE__ */ jsxRuntimeExports.jsx(VipIcon, { level: info == null ? void 0 : info.level }) }),
              (info == null ? void 0 : info.level) != 15 ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.progressWidth, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css.progressContent, css.flex1), children: [
                /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css.title1, css.flexCenter), children: [
                  /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css.vipleftspan, children: t("下一个等级") }),
                  /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { className: joinClass(css.special, css.vipTip), children: [
                    "VIP",
                    info == null ? void 0 : info["next_level"]
                  ] })
                ] }),
                /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.progress, children: [
                  !!(info == null ? void 0 : info["next_bet_requirement"]) && (info == null ? void 0 : info["next_bet_requirement"]) * 1 !== 0 && /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.progressCon, children: [
                    /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { children: [
                      trans("下注"),
                      " "
                    ] }),
                    /* @__PURE__ */ jsxRuntimeExports.jsx(
                      Progress,
                      {
                        progress: propgressBet,
                        text: info == null ? void 0 : info["current_bet"],
                        next: info == null ? void 0 : info["next_bet_requirement"]
                      }
                    )
                  ] }),
                  !!(info == null ? void 0 : info["next_deposit_requirement"]) && (info == null ? void 0 : info["next_deposit_requirement"]) * 1 !== 0 && /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.progressCon, children: [
                    /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { children: [
                      trans("存款"),
                      " "
                    ] }),
                    /* @__PURE__ */ jsxRuntimeExports.jsx(
                      Progress,
                      {
                        progress: propgressDeposit,
                        text: info == null ? void 0 : info["current_deposit"],
                        next: info == null ? void 0 : info["next_deposit_requirement"]
                      }
                    )
                  ] })
                ] }),
                /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.title2, css.flexCenter), children: !!(info == null ? void 0 : info["next_bet_requirement"]) && (info == null ? void 0 : info["next_bet_requirement"]) * 1 !== 0 && !!(info == null ? void 0 : info["next_deposit_requirement"]) && (info == null ? void 0 : info["next_deposit_requirement"]) * 1 !== 0 ? /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
                  /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: joinClass(css.vipleftspan), children: t("仍需投注") }),
                  /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: joinClass(css.special, css.nextMoney), children: (info == null ? void 0 : info["next_bet_requirement"]) - (info == null ? void 0 : info["current_bet"]) >= 0 && customToFixed(
                    (info == null ? void 0 : info["next_bet_requirement"]) - (info == null ? void 0 : info["current_bet"])
                  ) || "0.00" }),
                  /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: joinClass(css.vipleftspan), children: t("需流水") }),
                  /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: joinClass(css.special, css.nextMoney), children: (info == null ? void 0 : info["next_deposit_requirement"]) - (info == null ? void 0 : info["current_deposit"]) >= 0 && customToFixed(
                    (info == null ? void 0 : info["next_deposit_requirement"]) - (info == null ? void 0 : info["current_deposit"])
                  ) || "0.00" })
                ] }) : !!(info == null ? void 0 : info["next_bet_requirement"]) && (info == null ? void 0 : info["next_bet_requirement"]) * 1 !== 0 ? /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
                  /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: joinClass(css.vipleftspan), children: t("仍需有效投注") }),
                  /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: joinClass(css.special, css.nextMoney), children: (info == null ? void 0 : info["next_bet_requirement"]) - (info == null ? void 0 : info["current_bet"]) >= 0 && customToFixed(
                    (info == null ? void 0 : info["next_bet_requirement"]) - (info == null ? void 0 : info["current_bet"])
                  ) || "0.00" })
                ] }) : !!(info == null ? void 0 : info["next_deposit_requirement"]) && (info == null ? void 0 : info["next_deposit_requirement"]) * 1 !== 0 && /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
                  /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: joinClass(css.vipleftspan), children: t("仍需有效流水") }),
                  /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: joinClass(css.special, css.nextMoney), children: (info == null ? void 0 : info["next_deposit_requirement"]) - (info == null ? void 0 : info["current_deposit"]) >= 0 && customToFixed(
                    (info == null ? void 0 : info["next_deposit_requirement"]) - (info == null ? void 0 : info["current_deposit"])
                  ) || "0.00" })
                ] }) })
              ] }) }) : /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.fullLevel, children: [
                !!info["next_bet_requirement"] && info["next_bet_requirement"] * 1 !== 0 && /* @__PURE__ */ jsxRuntimeExports.jsxs("p", { children: [
                  trans("有效累积投注"),
                  /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css.amount, children: customToFixed(info == null ? void 0 : info["current_bet"]) })
                ] }),
                !!info["next_deposit_requirement"] && info["next_deposit_requirement"] * 1 !== 0 && /* @__PURE__ */ jsxRuntimeExports.jsxs("p", { children: [
                  trans("有效累积充值"),
                  /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css.amount, children: customToFixed(info == null ? void 0 : info["current_deposit"]) })
                ] }),
                /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css.tips, children: t("恭喜您，您已达到最高等级！") })
              ] }),
              (info == null ? void 0 : info.level) != 15 ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.vipImg, children: /* @__PURE__ */ jsxRuntimeExports.jsx(VipIcon, { level: info == null ? void 0 : info.next_level }) }) : /* @__PURE__ */ jsxRuntimeExports.jsx("div", {})
            ]
          }
        )
      ]
    }
  );
};
const Progress = ({ progress: progress2 = 0, text = "0", next = "0" }) => {
  return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.progressComponent, children: /* @__PURE__ */ jsxRuntimeExports.jsx(
    "div",
    {
      className: css.content,
      style: { width: "".concat(Math.min(progress2, 100), "%") },
      children: /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { children: [
        +text && customToFixed(text) || "0.00",
        " / ",
        +next && customToFixed(next)
      ] })
    }
  ) });
};
const VipList = ({ colums = [], data = [] }) => {
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.c_list, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "c_list_header c_list_row", children: colums.map((col) => {
      return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "c_list_col", children: col.title }, col.field);
    }) }),
    data.map((row, index) => {
      return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "c_list_row", children: colums.map((col, i) => {
        return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "c_list_col", children: col.render ? col.render(row) : col.format ? col.format(row) : row[col["field"]] }, i);
      }) }, index);
    })
  ] });
};
const Vip = () => {
  const { t, i18n } = useTranslation();
  const { info } = useUserInfoStore();
  const navigate = useNavigate();
  const arr = [
    {
      title: t("等级"),
      field: "level",
      render: (row) => {
        return /* @__PURE__ */ jsxRuntimeExports.jsx(VipIcon, { level: row.level });
      }
    },
    {
      title: t("存款"),
      field: "upgrade_deposit",
      format: (row) => {
        return customToFixed(row.upgrade_deposit);
      }
    },
    {
      title: t("下注"),
      field: "upgrade_record",
      format: (row) => {
        return customToFixed(row.upgrade_record);
      }
    },
    {
      title: t("奖金"),
      field: "upgrade_gift",
      format: (row) => customToFixed(row.upgrade_gift)
    }
  ];
  const [colums, setColums] = reactExports.useState([]);
  const [data, setData] = reactExports.useState([]);
  const getConfig = async () => {
    const [res] = await getVipConfig();
    const newArr = arr.map((item) => {
      if (item.field === "upgrade_record" && (!(info == null ? void 0 : info["next_bet_requirement"]) || (info == null ? void 0 : info["next_bet_requirement"]) * 1 === 0))
        return "null";
      if (item.field === "upgrade_deposit" && (!(info == null ? void 0 : info["next_deposit_requirement"]) || (info == null ? void 0 : info["next_deposit_requirement"]) * 1 === 0))
        return "null";
      return item;
    }).filter((item) => item !== "null");
    setColums(newArr);
    setData(res);
  };
  reactExports.useEffect(() => {
    getConfig();
  }, [info]);
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.vip_page, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx(VipProgress, {}),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "btns", children: /* @__PURE__ */ jsxRuntimeExports.jsx(
      "div",
      {
        className: joinClass("button", css.flexCenter),
        onClick: () => navigate("/deposit"),
        children: t("立即充值")
      }
    ) }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("h5", { children: t("VIP等级列表") }),
    /* @__PURE__ */ jsxRuntimeExports.jsx(VipList, { colums, data })
  ] });
};
export {
  Vip as default
};
