import { V as useTranslation, q as useUserInfoStore, r as reactExports, j as jsxRuntimeExports, z as trans, cM as getPromoInviteRecordDetail, aB as useFlutterApp, p as useFloatPopShareStore, cN as _, i as getQueryVariable, aa as minPxChip, H as Message, I as Image, a as joinClass, M as Modal, cO as getPromoInviteList, aC as sendMessage, cP as postPromoInviteOpen, aV as getLinkList, Q as getMoneyUnit, a6 as desktopOpen, cQ as postPromoInviteOpenonce } from "./comps-B8ShbmG--2024_12_14_18_4.js";
import { P as Picker, c as Table, I as InnerPageWithBackContext } from "./index-DgUEEG5U-2024_12_14_18_4.js";
import { I as Input, a as customFormatTimer, d as browser, c as clipboardExports } from "./index-BcpFmBv5-2024_12_14_18_4.js";
import { f as CloseIconInMineIcon, X as SearchToolIcon, Y as ShareInMineIcon, R as RectCopyIcon, du as ArrowDoubleIcon } from "./icons-Cdaou_E3-2024_12_14_18_4.js";
import { L as LoadMore } from "./index-4Dep-BeX-2024_12_14_18_4.js";
import { B as Button } from "./index-CGmeRIXa-2024_12_14_18_4.js";
import { A as ActivityBottom } from "./index-oTqPuerr-2024_12_14_18_4.js";
import "./index-CXpt7Cav-2024_12_14_18_4.js";
import "./index-DyG_R4zg-2024_12_14_18_4.js";
import "./index-CsbwU_08-2024_12_14_18_4.js";
import "./index-Bq-a07OY-2024_12_14_18_4.js";
import "./config-B4NCyDU0-2024_12_14_18_4.js";
const svg_theme_fill_color = "_svg_theme_fill_color_1rbw4_3";
const recommendBox = "_recommendBox_1rbw4_55";
const peopleNum = "_peopleNum_1rbw4_65";
const num = "_num_1rbw4_71";
const flexSend = "_flexSend_1rbw4_75";
const detail = "_detail_1rbw4_81";
const condition = "_condition_1rbw4_89";
const title = "_title_1rbw4_96";
const list = "_list_1rbw4_102";
const table = "_table_1rbw4_105";
const amountColor = "_amountColor_1rbw4_115";
const introduce = "_introduce_1rbw4_147";
const detailPop = "_detailPop_1rbw4_164";
const close = "_close_1rbw4_172";
const selectContent = "_selectContent_1rbw4_191";
const searchIcon = "_searchIcon_1rbw4_196";
const searchInput = "_searchInput_1rbw4_199";
const searchBox = "_searchBox_1rbw4_204";
const svgSearch = "_svgSearch_1rbw4_218";
const search_input = "_search_input_1rbw4_227";
const tipPop = "_tipPop_1rbw4_542";
const tip = "_tip_1rbw4_542";
const btn = "_btn_1rbw4_577";
const coinContent = "_coinContent_1rbw4_582";
const scrollTable = "_scrollTable_1rbw4_592";
const item = "_item_1rbw4_607";
const shareContent = "_shareContent_1rbw4_611";
const bx_1 = "_bx_1_1rbw4_615";
const bx_2 = "_bx_2_1rbw4_621";
const bx_3 = "_bx_3_1rbw4_627";
const copy_icon = "_copy_icon_1rbw4_786";
const amount = "_amount_1rbw4_115";
const is_active = "_is_active_1rbw4_803";
const not_active = "_not_active_1rbw4_807";
const hongbao = "_hongbao_1rbw4_811";
const card = "_card_1rbw4_820";
const arrowIcon = "_arrowIcon_1rbw4_830";
const light = "_light_1rbw4_838";
const arrowRight = "_arrowRight_1rbw4_842";
const arrowDown = "_arrowDown_1rbw4_849";
const hongbaoCard = "_hongbaoCard_1rbw4_856";
const cur = "_cur_1rbw4_866";
const receiveAmount = "_receiveAmount_1rbw4_869";
const pepleNum = "_pepleNum_1rbw4_878";
const pepleNum2 = "_pepleNum2_1rbw4_888";
const pepleNum3 = "_pepleNum3_1rbw4_902";
const baoxiangCard = "_baoxiangCard_1rbw4_906";
const notBaixiang = "_notBaixiang_1rbw4_917";
const baoxiangKaiqi = "_baoxiangKaiqi_1rbw4_924";
const baoxiangLingqu = "_baoxiangLingqu_1rbw4_931";
const baoxiangDown = "_baoxiangDown_1rbw4_939";
const oneClick = "_oneClick_1rbw4_943";
const curContainer = "_curContainer_1rbw4_952";
const currency = "_currency_1rbw4_958";
const css = {
  svg_theme_fill_color,
  recommendBox,
  peopleNum,
  num,
  flexSend,
  detail,
  condition,
  title,
  list,
  table,
  amountColor,
  introduce,
  detailPop,
  close,
  selectContent,
  searchIcon,
  searchInput,
  searchBox,
  svgSearch,
  search_input,
  tipPop,
  tip,
  btn,
  coinContent,
  scrollTable,
  item,
  shareContent,
  bx_1,
  bx_2,
  bx_3,
  copy_icon,
  amount,
  is_active,
  not_active,
  hongbao,
  card,
  arrowIcon,
  light,
  arrowRight,
  arrowDown,
  hongbaoCard,
  cur,
  receiveAmount,
  pepleNum,
  pepleNum2,
  pepleNum3,
  baoxiangCard,
  notBaixiang,
  baoxiangKaiqi,
  baoxiangLingqu,
  baoxiangDown,
  oneClick,
  curContainer,
  currency
};
const Detail = (props) => {
  const { i18n } = useTranslation();
  useUserInfoStore();
  const onClose = () => {
    props.onClose && props.onClose();
  };
  const [flag, setFlag] = reactExports.useState("0");
  const [dataSource, setDataSource] = reactExports.useState([]);
  const [page, setPage] = reactExports.useState(1);
  const [t, setT] = reactExports.useState(0);
  const [username, setUsername] = reactExports.useState("");
  const flagItems = [
    { label: i18n.t("全部"), value: "0" },
    { label: i18n.t("有效"), value: "1" },
    { label: i18n.t("无效"), value: "2" }
  ];
  const params = reactExports.useMemo(() => {
    return {
      flag,
      page,
      page_size: 10
    };
  }, [page, flag]);
  const [isLoading, setIsLoading] = reactExports.useState(false);
  const getList = async (username2) => {
    if (isLoading) return;
    const _params = Object.assign({}, params);
    if (username2) {
      _params.username = username2;
    }
    setIsLoading(true);
    const [res, error] = await getPromoInviteRecordDetail(_params, {
      useLoading: true
    });
    if (error) return;
    if (_params.page === 1) {
      setT(res.t);
    }
    setIsLoading(false);
    const _data = res.d || [];
    if (_params.page > 1) {
      setDataSource(dataSource.concat(_data));
    } else {
      setDataSource(_data);
    }
  };
  reactExports.useEffect(() => {
    getList(username);
  }, [params]);
  const limitClicks = () => {
    if (isLoading) return;
    setPage(page + 1);
  };
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.detailPop, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.close, onClick: onClose, children: /* @__PURE__ */ jsxRuntimeExports.jsx(CloseIconInMineIcon, {}) }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs(
      "div",
      {
        onClick: (e) => {
          e.stopPropagation();
        },
        children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("p", { className: css.title, children: i18n.t("我的推荐") }),
          /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.selectContent, children: [
            /* @__PURE__ */ jsxRuntimeExports.jsx(
              Picker,
              {
                items: flagItems,
                onChange: (_value) => {
                  setFlag(_value);
                }
              }
            ),
            /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.searchInput, children: /* @__PURE__ */ jsxRuntimeExports.jsx(
              Input,
              {
                placeholder: i18n.t("账户"),
                radius: true,
                suffix: /* @__PURE__ */ jsxRuntimeExports.jsx(
                  "div",
                  {
                    className: css.searchBox,
                    onClick: () => {
                      if (page === 1) {
                        getList(username);
                      } else {
                        setPage(1);
                      }
                    },
                    children: /* @__PURE__ */ jsxRuntimeExports.jsx(SearchToolIcon, { className: css.svgSearch })
                  }
                ),
                onChange: (val) => {
                  setUsername(val);
                }
              }
            ) })
          ] }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.scrollTable, children: /* @__PURE__ */ jsxRuntimeExports.jsx(
            Table,
            {
              columns: [
                {
                  title: i18n.t("下级账户"),
                  dataIndex: "username",
                  render: (text, item2) => {
                    return /* @__PURE__ */ jsxRuntimeExports.jsx("span", { style: { fontWeight: "bold" }, children: item2.username });
                  }
                },
                {
                  title: i18n.t("参与时间"),
                  dataIndex: "created_at",
                  render: (text, item2) => {
                    return /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: customFormatTimer(text) });
                  }
                },
                {
                  title: i18n.t("有效与否"),
                  dataIndex: "is_active",
                  render: (text, item2) => {
                    return /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: item2.is_active ? css.is_active : css.not_active, style: { fontWeight: "bold" }, children: item2.is_active ? trans("是") : trans("否") });
                  }
                },
                {
                  title: i18n.t("有效条件"),
                  dataIndex: "username",
                  render: (text, item2) => {
                    return /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { style: { fontWeight: "bold" }, children: [
                      i18n.t("累计充值"),
                      "：",
                      /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css.amount, children: item2.deposit_amount }),
                      /* @__PURE__ */ jsxRuntimeExports.jsx("br", {}),
                      i18n.t("累计投注"),
                      "：",
                      /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css.amount, children: item2.valid_bet_amount })
                    ] });
                  }
                }
              ],
              dataSource
            }
          ) }),
          t > dataSource.length && /* @__PURE__ */ jsxRuntimeExports.jsx(
            LoadMore,
            {
              onClick: () => {
                limitClicks();
              }
            }
          )
        ]
      }
    )
  ] });
};
const RecommendFriends = () => {
  const { t } = useTranslation();
  const { setTitle } = reactExports.useContext(InnerPageWithBackContext);
  const { token, theme, agentUrl, getUserBalanceByStore } = useUserInfoStore(
    (state) => state
  );
  const { isApp } = useFlutterApp();
  const [detailStatus, setDetailStatus] = reactExports.useState(false);
  const [tipStatus, setTipStatus] = reactExports.useState(false);
  const { share } = useFloatPopShareStore();
  const [recharge, setRecharge] = reactExports.useState(0);
  const [bet, setBet] = reactExports.useState(0);
  const [num2, setNum] = reactExports.useState(0);
  const [dataList, setDataList] = reactExports.useState([]);
  const [openAmount, setOpenAmount] = reactExports.useState(0);
  const [shareUrl, setShareUrl] = reactExports.useState("");
  const [maxMount, setMaxMount] = reactExports.useState(0);
  const [is_manual, setIsManual] = reactExports.useState(1);
  const [rules, setRules] = reactExports.useState([]);
  const [isHongbao, setIsHongbao] = reactExports.useState(false);
  let loading = false;
  const renderDataList = reactExports.useMemo(() => {
    if (dataList.length === 0) return [];
    const sortDataList = dataList.sort((a, b) => a.sort - b.sort);
    const chunkList = _.chunk(sortDataList, 4);
    const maxMemCount = _.maxBy(sortDataList, "mem_count").mem_count;
    const modifiedArray = _.map(chunkList, (item2, index) => {
      if (item2.length < 4 && index % 2 !== 0) {
        const len = 4 - item2.length;
        for (let i = 0; i < len; i++) {
          item2.push({ state: 4 });
        }
      }
      if (index % 2 === 0) {
        return item2;
      }
      return _.reverse(item2);
    });
    const fat = _.flatten(modifiedArray);
    for (let i = fat.length - 1; i >= 0; i--) {
      if (fat[i].state == 1) {
        fat[i] = {
          ...fat[i],
          color: theme === "whiteGreen" ? "#D9D9D9" : "#FFFFFF"
        };
      } else {
        fat[i] = { ...fat[i], color: "#D9D9D9" };
      }
    }
    if (fat.length) {
      const maxItemIndex = fat.findIndex(
        (item2) => +item2.mem_count === +maxMemCount
      );
      fat[maxItemIndex] = { ...fat[maxItemIndex], hidden: true };
    }
    return fat;
  }, [dataList]);
  const hongbaoList = reactExports.useMemo(() => {
    let list2 = [];
    if (renderDataList && renderDataList.length) {
      const chunkList = _.chunk(renderDataList, 4);
      for (let i = 0; i < chunkList.length; i++) {
        chunkList[i] = _.flatMap(chunkList[i], (num22) => [num22, "left"]).slice(0, -1);
      }
      list2 = _.flatMap(chunkList, (o) => [o, Array(7).fill().map((_2, i) => i)]).slice(0, -1);
    }
    let nullStart = 3;
    let rightStart = 2;
    list2 = list2.map((item2, index) => {
      if (index === nullStart) {
        item2 = item2.reverse();
        nullStart += 4;
      }
      if (index === rightStart) {
        for (let i = 0; i < item2.length; i++) {
          if (typeof item2[i] === "string") {
            item2[i] = "right";
          }
        }
        rightStart += 4;
      }
      return item2;
    });
    console.log("list", list2);
    return list2;
  }, [renderDataList]);
  const totalAmount = reactExports.useMemo(() => {
    return renderDataList.reduce((acc, item2) => {
      if (item2.state === 2) {
        return acc + +item2.bonus_amount;
      }
      return acc;
    }, 0);
  }, [renderDataList]);
  const getList = async () => {
    const [res, err] = await getPromoInviteList();
    if (err) return;
    setRecharge(res.deposit_limit);
    setBet(res.valid_bet_amount);
    setNum(res.total_mem_count);
    setDataList(res.list || []);
    setRules(res.promo_rule_json || []);
    setTitle(res.title);
    sendMessage("getTitleName", res.title);
    setIsManual(res.is_manual);
    setIsHongbao(res.is_manual === 3);
    setMaxMount(res.list[res.list.length - 1].bonus_amount);
  };
  const openInvite = async (item2) => {
    if (loading) return;
    loading = true;
    const [res, err] = await postPromoInviteOpen({ mem_count: item2.mem_count });
    loading = false;
    if (err) return;
    setTimeout(() => {
      getUserBalanceByStore();
      setTipStatus(true);
      setOpenAmount(item2.bonus_amount);
      getList();
    }, 100);
  };
  const getLinkUrl = async () => {
    const [res, err] = await getLinkList();
    if (err) return;
    if (res && Array.isArray(res) && res.length > 0) {
      const url = "".concat(window.location.origin, "/?id=").concat(res[0].code, "&currency=").concat(getMoneyUnit(), "&type=2");
      setShareUrl(url);
    }
  };
  const nativeShare = async () => {
    if (navigator.share) {
      try {
        await navigator.share({
          title: "",
          text: "",
          url: shareUrl
        });
      } catch (error) {
        console.error("error", error);
      }
    } else {
      console.log("Web Share API 不受支持");
    }
  };
  const shareApp = (item2) => {
    sendMessage("onChatSystemMessage", "activity-".concat(item2.link));
  };
  const openWindow = (item2) => {
    desktopOpen(item2.link.replace("xxxxx", encodeURIComponent(shareUrl)));
  };
  const getArrowClass = (list2, index, direction) => {
    if (direction === "left") {
      return list2[index - 1].state !== 1 && list2[index + 1].state !== 1 ? css.light : "";
    } else {
      return list2[index + 1].state !== 1 && list2[index - 1].state !== 1 ? css.light : "";
    }
  };
  const showBounsAmount = (item2) => {
    if (isHongbao) {
      return item2.bonus_amount;
    }
    if (item2.state === 1) {
      return "";
    }
  };
  const showPessoas = (item2) => {
    if (item2.state === 2 && !isHongbao) {
      return t("领取");
    }
    return /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
      item2.mem_count,
      " ",
      t("人")
    ] });
  };
  const oneClickReceive = async () => {
    const [res, err] = await postPromoInviteOpenonce();
    if (err) return;
    if (res === "1000") {
      getUserBalanceByStore();
      setTipStatus(true);
      setOpenAmount(totalAmount);
      getList();
    }
  };
  reactExports.useEffect(() => {
    getList();
    if (token || getQueryVariable("t")) {
      getLinkUrl();
    } else {
      setShareUrl(window.location.origin);
    }
  }, [token]);
  reactExports.useEffect(() => {
    if (shareUrl) {
      browser.toCanvas(document.getElementById("qrcode"), shareUrl, {
        width: minPxChip() * 123.5,
        height: minPxChip() * 123.5,
        margin: 0
      });
    }
  }, [shareUrl]);
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.recommendBox, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "share-box", children: [
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "share-row1", children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "recommend_title", children: t("推荐信息") }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "qr-container", children: [
          /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "qr-box", children: [
            /* @__PURE__ */ jsxRuntimeExports.jsx("canvas", { className: "qr-code", id: "qrcode" }),
            /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "qr-text", children: t("点击保存") })
          ] }),
          /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "share-input-box", children: [
            /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "title", children: [
              t("推广链接"),
              /* @__PURE__ */ jsxRuntimeExports.jsx(
                "div",
                {
                  className: "share-icon",
                  onClick: () => {
                    nativeShare();
                  },
                  children: /* @__PURE__ */ jsxRuntimeExports.jsx(ShareInMineIcon, {})
                }
              )
            ] }),
            /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "share-url", children: [
              /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: shareUrl }),
              /* @__PURE__ */ jsxRuntimeExports.jsx(
                "div",
                {
                  className: "".concat(css.copy_icon, " button"),
                  onClick: () => {
                    clipboardExports.copy(shareUrl);
                    Message.success(t("复制成功"));
                  },
                  children: /* @__PURE__ */ jsxRuntimeExports.jsx(RectCopyIcon, {})
                }
              )
            ] })
          ] })
        ] })
      ] }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "chat-list", children: share.map((item2, index) => {
        const link = item2.link.replace("xxxxx", encodeURIComponent(shareUrl));
        return /* @__PURE__ */ jsxRuntimeExports.jsxs(
          "div",
          {
            className: "chat-app",
            onClick: (e) => {
              clipboardExports.copy(agentUrl);
              e.stopPropagation();
              isApp ? shareApp(item2) : openWindow(item2);
            },
            children: [
              /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { src: item2.img, isGame: true, remote: true }),
              /* @__PURE__ */ jsxRuntimeExports.jsx(
                "a",
                {
                  className: "app-title",
                  href: link,
                  id: "share-".concat(item2.name),
                  target: "_blank",
                  children: item2.name
                }
              )
            ]
          },
          index
        );
      }) }),
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.peopleNum, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
          /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { className: css.num, children: [
            t("有效下级人员"),
            " ",
            /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: num2 })
          ] }),
          is_manual === 4 && /* @__PURE__ */ jsxRuntimeExports.jsx("br", {}),
          (token || getQueryVariable("t")) && /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { className: joinClass(css.num, is_manual !== 4 ? css.flexSend : ""), children: [
            t("人"),
            /* @__PURE__ */ jsxRuntimeExports.jsx(
              "span",
              {
                className: css.detail,
                onClick: (e) => {
                  e.stopPropagation();
                  setDetailStatus(true);
                },
                children: t("详情")
              }
            )
          ] })
        ] }),
        is_manual === 4 && (token || getQueryVariable("t")) && /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css.oneClick, css.num), children: [
          /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { className: css.curContainer, children: [
            /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css.currency, children: /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: "R$" }) }),
            /* @__PURE__ */ jsxRuntimeExports.jsx("span", { children: totalAmount })
          ] }),
          /* @__PURE__ */ jsxRuntimeExports.jsx(
            Button,
            {
              onClick: () => {
                if (totalAmount <= 0) return;
                oneClickReceive();
              },
              disabled: totalAmount <= 0,
              children: t("领取")
            }
          )
        ] })
      ] })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.condition, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("p", { className: css.title, children: t("领取彩金所需条件如下") }),
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.list, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.table, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: t("下级累计充值") }),
          /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
            /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { className: css.amountColor, children: [
              "≥",
              " ",
              recharge
            ] }),
            " "
          ] })
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.table, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: t("下级累计投注") }),
          /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
            /* @__PURE__ */ jsxRuntimeExports.jsxs("span", { className: css.amountColor, children: [
              "≥",
              " ",
              bet
            ] }),
            " "
          ] })
        ] })
      ] })
    ] }),
    (is_manual === 1 || is_manual === 3) && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.condition, css.hongbao), children: hongbaoList.map((_list, index) => {
      if (Object.keys(_list[0]).length !== 0) {
        return _list.map((cItem, cIndex) => {
          if (typeof cItem === "string") {
            return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.card, children: _list[cIndex - 1].bonus_amount && (cItem === "left" ? /* @__PURE__ */ jsxRuntimeExports.jsx(ArrowDoubleIcon, { className: joinClass(css.arrowRight, css.arrowIcon, getArrowClass(_list, cIndex, "left")) }) : /* @__PURE__ */ jsxRuntimeExports.jsx(ArrowDoubleIcon, { className: joinClass(css.arrowLeft, css.arrowIcon, getArrowClass(_list, cIndex, "right")) })) }, cIndex);
          } else {
            return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css.card), style: {
              opacity: cItem.bonus_amount ? 1 : 0
            }, children: [
              /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { src: "/activity/".concat(isHongbao ? "hongbao_" : "", "state").concat(cItem.state, ".webp") }),
              /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css.hongbaoCard, isHongbao ? "" : css.baoxiangCard, cItem.state === 2 ? css.cur : ""), onClick: () => {
                if (cItem.state === 2) {
                  openInvite(cItem);
                }
              }, children: [
                /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(
                  css.receiveAmount,
                  cItem.state === 1 ? css.not : ""
                  // 未达标
                ), children: showBounsAmount(cItem) }),
                isHongbao ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.pepleNum, cItem.mem_count >= 1e3 ? css.pepleNum2 : ""), children: showPessoas(cItem) }) : /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.pepleNum2, cItem.mem_count >= 100 ? css.pepleNum3 : ""), children: showPessoas(cItem) }),
                !isHongbao && cItem.state === 1 ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.notBaixiang), children: cItem.bonus_amount }) : null,
                !isHongbao && cItem.state === 2 ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.baoxiangKaiqi), children: cItem.bonus_amount }) : null,
                !isHongbao && cItem.state === 3 ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.baoxiangLingqu), children: cItem.bonus_amount }) : null
              ] })
            ] }, cIndex);
          }
        });
      } else {
        return _list.map((cItem, cIndex) => {
          return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.card, isHongbao ? "" : css.baoxiangDown), children: cItem === 6 ? /* @__PURE__ */ jsxRuntimeExports.jsx(ArrowDoubleIcon, { className: joinClass(css.arrowDown, css.arrowIcon, hongbaoList[index - 1][6].state !== 1 ? css.light : "") }) : "" }, cIndex);
        });
      }
    }) }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.introduce, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
        t("活动说明"),
        "："
      ] }),
      rules.map((item2, index) => {
        return /* @__PURE__ */ jsxRuntimeExports.jsxs("p", { children: [
          index + 1,
          ",",
          item2.content
        ] }, index);
      })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsx(ActivityBottom, {}),
    /* @__PURE__ */ jsxRuntimeExports.jsx(Modal, { isOpen: detailStatus, onClose: (e) => e && e.stopPropagation(), children: /* @__PURE__ */ jsxRuntimeExports.jsx(
      Detail,
      {
        onClose: () => {
          setDetailStatus(false);
        }
      }
    ) }),
    /* @__PURE__ */ jsxRuntimeExports.jsx(Modal, { isOpen: tipStatus, onClose: (e) => e && e.stopPropagation(), children: /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.tipPop, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("p", { className: css.title, children: t("提示信息") }),
      /* @__PURE__ */ jsxRuntimeExports.jsx(
        "div",
        {
          className: css.close,
          onClick: (e) => {
            e.stopPropagation();
            setTipStatus(false);
          },
          children: /* @__PURE__ */ jsxRuntimeExports.jsx(CloseIconInMineIcon, {})
        }
      ),
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.coinContent, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { src: "/home/icons/activity_coin.webp" }),
        /* @__PURE__ */ jsxRuntimeExports.jsx(
          "p",
          {
            className: css.tip,
            dangerouslySetInnerHTML: {
              __html: "".concat(t("恭喜您成功收到"), "  <span>").concat(openAmount, "</span>")
            }
          }
        )
      ] }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.btn, children: /* @__PURE__ */ jsxRuntimeExports.jsx(
        Button,
        {
          block: true,
          className: css.btnColor,
          onClick: () => {
            setTipStatus(false);
          },
          children: t("知道了")
        }
      ) })
    ] }) }) })
  ] });
};
export {
  RecommendFriends as default
};
