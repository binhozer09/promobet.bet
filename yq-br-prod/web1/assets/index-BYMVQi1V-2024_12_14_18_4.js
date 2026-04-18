import { q as useUserInfoStore, j as jsxRuntimeExports, a0 as useWebsetConfig, a as joinClass, y as useNavigate, I as Image, a9 as useReactive, r as reactExports, a4 as instance, p as useFloatPopShareStore, z as trans, a6 as desktopOpen, C as Cache, H as Message, o as useGameStore } from "./comps-B8ShbmG--2024_12_14_18_4.js";
import "./config-B4NCyDU0-2024_12_14_18_4.js";
import { c as clipboardExports } from "./index-BcpFmBv5-2024_12_14_18_4.js";
const svg_theme_fill_color = "_svg_theme_fill_color_1m925_3";
const borderTop = "_borderTop_1m925_55";
const styl5Partner = "_styl5Partner_1m925_55";
const styl4Company = "_styl4Company_1m925_55";
const styl4Partner = "_styl4Partner_1m925_55";
const styl3Partner = "_styl3Partner_1m925_55";
const style2Company = "_style2Company_1m925_55";
const styl1Partner = "_styl1Partner_1m925_55";
const tipsBox = "_tipsBox_1m925_59";
const websitImg = "_websitImg_1m925_234";
const detailTip = "_detailTip_1m925_240";
const styl1Tips = "_styl1Tips_1m925_256";
const styl1Quick = "_styl1Quick_1m925_260";
const styl1Official = "_styl1Official_1m925_264";
const styl1Company = "_styl1Company_1m925_268";
const style2Tips = "_style2Tips_1m925_277";
const styl2Partner = "_styl2Partner_1m925_290";
const tips3List = "_tips3List_1m925_294";
const styl3Tips = "_styl3Tips_1m925_298";
const styl3Official = "_styl3Official_1m925_302";
const styl3Company = "_styl3Company_1m925_306";
const quick2Item = "_quick2Item_1m925_315";
const quick2Title = "_quick2Title_1m925_322";
const quick2List = "_quick2List_1m925_326";
const quick2ListItem = "_quick2ListItem_1m925_331";
const style4Tips = "_style4Tips_1m925_350";
const styl4Quick = "_styl4Quick_1m925_358";
const styl5Tips = "_styl5Tips_1m925_374";
const styl5Quick = "_styl5Quick_1m925_378";
const styl5Official = "_styl5Official_1m925_383";
const style5Tips = "_style5Tips_1m925_393";
const logoContainer = "_logoContainer_1m925_402";
const logo = "_logo_1m925_133";
const css = {
  svg_theme_fill_color,
  borderTop,
  styl5Partner,
  styl4Company,
  styl4Partner,
  styl3Partner,
  style2Company,
  styl1Partner,
  tipsBox,
  websitImg,
  detailTip,
  styl1Tips,
  styl1Quick,
  styl1Official,
  styl1Company,
  style2Tips,
  styl2Partner,
  tips3List,
  styl3Tips,
  styl3Official,
  styl3Company,
  quick2Item,
  quick2Title,
  quick2List,
  quick2ListItem,
  style4Tips,
  styl4Quick,
  styl5Tips,
  styl5Quick,
  styl5Official,
  style5Tips,
  logoContainer,
  logo
};
function useShowState() {
  const { websetConfig } = useUserInfoStore();
  const state = useReactive({
    /** 快速跳转开关 */
    quickNavigateToggle: false,
    /** 分享设置开关 */
    shareSettingsToggle: false,
    /** 官方频道或社区开关 */
    officialChannelToggle: false,
    /** 合作方信息开关 */
    partnerInfoToggle: false,
    /** 牌照资质开关 */
    licenseToggle: false
  });
  reactExports.useEffect(() => {
    if (websetConfig) {
      const footerJson = JSON.parse(websetConfig.footerJson || "{}");
      state.quickNavigateToggle = footerJson.quickNavigateToggle === "1";
      state.shareSettingsToggle = footerJson.shareSettingsToggle === "1";
      state.officialChannelToggle = footerJson.officialChannelToggle === "1";
      state.partnerInfoToggle = footerJson.partnerInfoToggle === "1";
      state.licenseToggle = footerJson.licenseToggle === "1";
    }
  }, [websetConfig]);
  return state;
}
const QuickEntry = ({ style = 1 }) => {
  const { websetConfig } = useUserInfoStore();
  const navigate = useNavigate();
  const state = useReactive({
    mapList: [
      {
        title: "Cassino",
        child: [
          {
            title: "Activity",
            value: "活动",
            path: "/activity"
          },
          {
            title: "任务",
            value: "任务中心",
            path: "/missao"
          },
          {
            title: "返水",
            value: "返水",
            path: "/back-rate"
          },
          {
            title: "Pending",
            value: "待领取",
            path: "/bonus"
          },
          {
            title: "VIP",
            value: "VIP",
            path: "/vip"
          },
          {
            title: "Agent",
            value: "邀请/代理",
            path: "/agent"
          },
          {
            title: "Historic",
            value: "历史",
            path: "/alreadyGetbonus"
          }
        ]
      },
      {
        title: "游戏",
        child: [
          {
            title: "LIVE",
            value: "真人",
            id: "label_Cartas",
            gametype: "真人"
          },
          {
            title: "捕鱼",
            value: "捕鱼",
            id: "label_Pescaria",
            gametype: "捕鱼"
          },
          {
            title: "电子",
            value: "电子",
            id: "label_Slots",
            gametype: "电子"
          },
          {
            title: "体育",
            // 11
            value: "体育",
            id: "label_Esporte",
            gametype: "体育"
          },
          {
            title: "棋牌",
            value: "棋牌",
            id: "label_Cartas",
            gametype: "棋牌"
          },
          {
            title: "电竞",
            value: "电竞",
            id: "label_Esports",
            gametype: "电竞"
          },
          {
            title: "彩票",
            value: "彩票",
            id: "label_Loteria",
            gametype: "彩票"
          },
          {
            title: "斗鸡",
            value: "斗鸡",
            id: "label_douji",
            gametype: "斗鸡"
          },
          {
            title: "小游戏",
            value: "小游戏",
            id: "label_Blockchain",
            gametype: "小游戏"
          }
        ]
      },
      {
        title: "帮助",
        child: [
          {
            title: "在线客服",
            value: "在线客服",
            path: "/message?service=1"
          },
          {
            title: "帮助中心",
            value: "帮助中心",
            path: ""
          },
          {
            title: "有奖反馈",
            value: "有奖反馈",
            path: ""
          },
          {
            title: "about",
            value: "关于我们",
            path: ""
          }
        ]
      }
    ]
  });
  function handleClcik(item) {
    if (item.title === "Suporte online") {
      const url = Cache.get("services_url") || "";
      if (!url) {
        Message.error(trans("暂无客服"));
        return;
      }
      desktopOpen(url);
      return;
    }
    if (item.path) {
      navigate(item.path);
      if (item.gametype) {
        useGameStore.setState({
          selectType: item.gametype
        });
      }
    } else {
      const dom = document.getElementById(item.id);
      if (!dom) return;
      dom.click();
    }
  }
  reactExports.useEffect(() => {
    if (websetConfig) {
      const footerJson = JSON.parse(websetConfig.footerJson || "{}");
      const list1Str = footerJson.cassinoAry || "";
      const list2Str = footerJson.gameAry || "";
      const list3Str = footerJson.suporteAry || "";
      state.mapList[0].child = state.mapList[0].child.filter(
        (item) => list1Str.includes(item.value)
      );
      state.mapList[1].child = state.mapList[1].child.filter(
        (item) => list2Str.includes(item.value)
      );
      state.mapList[2].child = state.mapList[2].child.filter(
        (item) => list3Str.includes(item.value)
      );
    }
  }, [websetConfig]);
  return /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, { children: style === 1 ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "maps_list", children: state.mapList.map((col) => {
    return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "maps_list_col", children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "map_list_col_title", children: instance.t(col.title) }),
      col.child.map((item) => {
        return /* @__PURE__ */ jsxRuntimeExports.jsx(
          "div",
          {
            className: "button itemTitle",
            onClick: () => {
              handleClcik(item);
            },
            children: instance.t(item.title)
          },
          item.title
        );
      })
    ] }, col.title);
  }) }) : /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.quick2Container, children: state.mapList.map((pItem, i) => {
    return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.quick2Item, children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.quick2Title, children: instance.t(pItem.title) }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.quick2List, children: pItem.child.map((item, index) => {
        return /* @__PURE__ */ jsxRuntimeExports.jsx(
          "div",
          {
            className: css.quick2ListItem,
            onClick: () => {
              handleClcik(item);
            },
            children: instance.t(item.title)
          },
          index
        );
      }) })
    ] }, pItem.title);
  }) }) });
};
const Contact = () => {
  const { agentUrl } = useUserInfoStore();
  const { share } = useFloatPopShareStore();
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "img_list", children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx("p", { className: "img_list_title", children: trans("Contact us") }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "img_list_flex", children: [
      share == null ? void 0 : share.map((item, index) => {
        const link = item.link.replace("xxxxx", encodeURIComponent(agentUrl));
        return /* @__PURE__ */ jsxRuntimeExports.jsx(
          Image,
          {
            className: "button",
            remote: true,
            isGame: true,
            src: item.img,
            onClick: () => {
              console.log("share link :>> ", link);
              clipboardExports.copy(agentUrl);
              desktopOpen(item.link);
            }
          },
          index
        );
      }),
      /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { className: "button", src: "/home/icons/18_.webp" })
    ] })
  ] });
};
const OfficialChannel = () => {
  const { websetConfig } = useUserInfoStore();
  const [threeLoginList, setThreeLoginList] = reactExports.useState([]);
  const [threeLoginShow, setThreeLoginShow] = reactExports.useState(false);
  reactExports.useEffect(() => {
    if (websetConfig) {
      const arr = JSON.parse((websetConfig == null ? void 0 : websetConfig.official_channels) || "[]");
      setThreeLoginShow(!!arr.length);
      setThreeLoginList(arr.sort((a, b) => a.sort - b.sort));
    }
  }, [websetConfig]);
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "img_list", children: [
    threeLoginShow && /* @__PURE__ */ jsxRuntimeExports.jsx("p", { className: "img_list_title", children: trans("官方频道") }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "img_list_flex img_canal_oficial", children: threeLoginList == null ? void 0 : threeLoginList.map((item, index) => {
      return /* @__PURE__ */ jsxRuntimeExports.jsx(
        Image,
        {
          className: "button",
          src: item.icon,
          remote: true,
          isGame: true,
          onClick: () => {
            if (window.navigator.standalone || window.matchMedia("(display-mode: standalone)").matches) {
              window.location.href = item.link;
            } else {
              desktopOpen(item.link);
            }
          }
        },
        index
      );
    }) })
  ] });
};
const CompanyInfo = () => {
  var _a, _b;
  const navigate = useNavigate();
  const { websetConfig } = useUserInfoStore();
  const footerJson = JSON.parse((websetConfig == null ? void 0 : websetConfig.footerJson) || "{}");
  return /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: (footerJson == null ? void 0 : footerJson["licenseImgToggle"]) === "1" && ((_b = (_a = footerJson == null ? void 0 : footerJson["licenseImgData"]) == null ? void 0 : _a[0]) == null ? void 0 : _b["image"]) && /* @__PURE__ */ jsxRuntimeExports.jsx(
      Image,
      {
        onClick: () => {
          navigate("/license");
        },
        className: css.websitImg,
        src: footerJson == null ? void 0 : footerJson["licenseImgData"][0]["image"],
        remote: true,
        isGame: true
      }
    ) }),
    /* @__PURE__ */ jsxRuntimeExports.jsx(
      "div",
      {
        className: css.detailTip,
        dangerouslySetInnerHTML: { __html: footerJson.companyInfoHtml }
      }
    )
  ] });
};
const PartnerInfo = () => {
  const { websetConfig } = useUserInfoStore();
  const { partnerInfoToggle, licenseToggle } = useShowState();
  const state = useReactive({
    partnerInfoData: [],
    licenseInfo: []
  });
  reactExports.useEffect(() => {
    if (websetConfig) {
      try {
        const footerJson = JSON.parse(websetConfig.footerJson || "{}");
        state.partnerInfoData = footerJson.partnerInfoData || [];
        state.licenseInfo = footerJson.licenseInfo || [];
      } catch (error) {
        console.error("合作方信息解析失败", error);
      }
    }
  }, [websetConfig]);
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "tip_bottom", children: [
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "logos_1", children: partnerInfoToggle && state.partnerInfoData.map((item, index) => {
      return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "logos_2_item", children: /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { src: item.image, remote: true, isGame: true }) }, index);
    }) }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "logos_1 logos_2", children: licenseToggle && state.licenseInfo.map((item, index) => {
      return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: "logos_2_item logos_3_item", children: /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { src: item.image, remote: true, isGame: true }) }, index);
    }) })
  ] });
};
const Reserved = () => {
  const { websetConfig } = useUserInfoStore();
  const state = useReactive({
    list: []
  });
  reactExports.useEffect(() => {
    if (websetConfig) {
      const footerJson = JSON.parse(websetConfig.footerJson || "{}");
      const _copyrightInfo = footerJson.copyrightInfo || "[]";
      const copyrightInfo = _copyrightInfo;
      if (copyrightInfo && Array.isArray(copyrightInfo)) {
        const arr = [];
        for (let i = 0; i < Math.ceil(copyrightInfo.length / 2); i++) {
          arr.push([copyrightInfo[i * 2], copyrightInfo[i * 2 + 1]]);
        }
        state.list = arr;
      }
    }
  }, [websetConfig]);
  return /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, { children: state.list.map((item, index) => {
    return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "reserved", children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx(
        "p",
        {
          className: "group_name",
          dangerouslySetInnerHTML: {
            __html: item[0].replace(/\n/g, "<br>")
          }
        }
      ),
      item[1] && /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx("p", { className: "group_line" }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("p", { className: "group_all", children: [
          " ",
          item[1],
          " "
        ] })
      ] })
    ] }, index);
  }) });
};
const Style1 = () => {
  const { websetConfig: websetConfigByHook } = useWebsetConfig();
  const {
    quickNavigateToggle,
    shareSettingsToggle,
    officialChannelToggle,
    partnerInfoToggle,
    licenseToggle
  } = useShowState();
  return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.tipsBox, children: /* @__PURE__ */ jsxRuntimeExports.jsxs(
    "div",
    {
      className: joinClass(
        "containerBox",
        !websetConfigByHook.isHiddenDownloadApp ? "bottomMoveDown" : ""
      ),
      children: [
        quickNavigateToggle && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.styl1Quick, children: /* @__PURE__ */ jsxRuntimeExports.jsx(QuickEntry, {}) }),
        shareSettingsToggle || officialChannelToggle ? /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "tips_list", children: [
          shareSettingsToggle && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.styl1Tips, children: /* @__PURE__ */ jsxRuntimeExports.jsx(Contact, {}) }),
          officialChannelToggle && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.styl1Official, children: /* @__PURE__ */ jsxRuntimeExports.jsx(OfficialChannel, {}) })
        ] }) : null,
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.styl1Company, children: /* @__PURE__ */ jsxRuntimeExports.jsx(CompanyInfo, {}) }),
        partnerInfoToggle || licenseToggle ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.styl1Partner, children: /* @__PURE__ */ jsxRuntimeExports.jsx(PartnerInfo, {}) }) : null,
        /* @__PURE__ */ jsxRuntimeExports.jsx(Reserved, {})
      ]
    }
  ) });
};
const Style2 = () => {
  const { websetConfig } = useUserInfoStore();
  const { websetConfig: websetConfigByHook } = useWebsetConfig();
  const officialChannels = JSON.parse((websetConfig == null ? void 0 : websetConfig.official_channels) || "[]");
  const {
    shareSettingsToggle,
    officialChannelToggle,
    partnerInfoToggle,
    licenseToggle
  } = useShowState();
  return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.tipsBox, children: /* @__PURE__ */ jsxRuntimeExports.jsxs(
    "div",
    {
      className: joinClass(
        "containerBox",
        !websetConfigByHook.isHiddenDownloadApp ? "bottomMoveDown" : ""
      ),
      children: [
        shareSettingsToggle || officialChannelToggle ? /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "".concat(css.style2Tips, " tips_list"), children: [
          shareSettingsToggle && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: /* @__PURE__ */ jsxRuntimeExports.jsx(Contact, {}) }),
          officialChannelToggle && officialChannels.length !== 0 && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: /* @__PURE__ */ jsxRuntimeExports.jsx(OfficialChannel, {}) })
        ] }) : null,
        partnerInfoToggle || licenseToggle ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.styl2Partner, children: /* @__PURE__ */ jsxRuntimeExports.jsx(PartnerInfo, {}) }) : null,
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.style2Company, children: /* @__PURE__ */ jsxRuntimeExports.jsx(CompanyInfo, {}) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx(Reserved, {})
      ]
    }
  ) });
};
const Style3 = () => {
  const { websetConfig: websetConfigByHook } = useWebsetConfig();
  const {
    quickNavigateToggle,
    shareSettingsToggle,
    officialChannelToggle,
    partnerInfoToggle,
    licenseToggle
  } = useShowState();
  return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.tipsBox, children: /* @__PURE__ */ jsxRuntimeExports.jsxs(
    "div",
    {
      className: joinClass(
        "containerBox",
        !websetConfigByHook.isHiddenDownloadApp ? "bottomMoveDown" : ""
      ),
      children: [
        shareSettingsToggle || officialChannelToggle ? /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "".concat(css.tips3List, " tips_list"), children: [
          shareSettingsToggle && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.styl3Tips, children: /* @__PURE__ */ jsxRuntimeExports.jsx(Contact, {}) }),
          officialChannelToggle && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.styl3Official, children: /* @__PURE__ */ jsxRuntimeExports.jsx(OfficialChannel, {}) })
        ] }) : null,
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.styl3Company, children: /* @__PURE__ */ jsxRuntimeExports.jsx(CompanyInfo, {}) }),
        quickNavigateToggle && /* @__PURE__ */ jsxRuntimeExports.jsx(QuickEntry, { style: 2 }),
        partnerInfoToggle || licenseToggle ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.styl3Partner, children: /* @__PURE__ */ jsxRuntimeExports.jsx(PartnerInfo, {}) }) : null,
        /* @__PURE__ */ jsxRuntimeExports.jsx(Reserved, {})
      ]
    }
  ) });
};
const Style4 = () => {
  const { websetConfig } = useUserInfoStore();
  const { websetConfig: websetConfigByHook } = useWebsetConfig();
  const officialChannels = JSON.parse((websetConfig == null ? void 0 : websetConfig.official_channels) || "[]");
  const {
    quickNavigateToggle,
    shareSettingsToggle,
    officialChannelToggle,
    partnerInfoToggle,
    licenseToggle
  } = useShowState();
  return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.tipsBox, children: /* @__PURE__ */ jsxRuntimeExports.jsxs(
    "div",
    {
      className: joinClass(
        "containerBox",
        !websetConfigByHook.isHiddenDownloadApp ? "bottomMoveDown" : ""
      ),
      children: [
        quickNavigateToggle && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.styl4Quick, children: /* @__PURE__ */ jsxRuntimeExports.jsx(QuickEntry, {}) }),
        shareSettingsToggle || officialChannelToggle ? /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "".concat(css.style4Tips, " tips_list"), children: [
          shareSettingsToggle && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: /* @__PURE__ */ jsxRuntimeExports.jsx(Contact, {}) }),
          officialChannelToggle && officialChannels.length !== 0 && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: /* @__PURE__ */ jsxRuntimeExports.jsx(OfficialChannel, {}) })
        ] }) : null,
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.styl4Company, children: /* @__PURE__ */ jsxRuntimeExports.jsx(CompanyInfo, {}) }),
        partnerInfoToggle || licenseToggle ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.styl4Partner, children: /* @__PURE__ */ jsxRuntimeExports.jsx(PartnerInfo, {}) }) : null,
        /* @__PURE__ */ jsxRuntimeExports.jsx(Reserved, {})
      ]
    }
  ) });
};
const Style5 = () => {
  const navigate = useNavigate();
  const { websetConfig } = useUserInfoStore();
  const { websetConfig: websetConfigByHook } = useWebsetConfig();
  const officialChannels = JSON.parse((websetConfig == null ? void 0 : websetConfig.official_channels) || "[]");
  const {
    quickNavigateToggle,
    shareSettingsToggle,
    officialChannelToggle,
    partnerInfoToggle,
    licenseToggle
  } = useShowState();
  return /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.tipsBox, children: /* @__PURE__ */ jsxRuntimeExports.jsxs(
    "div",
    {
      className: joinClass(
        "containerBox",
        !websetConfigByHook.isHiddenDownloadApp ? "bottomMoveDown" : ""
      ),
      children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.logoContainer, children: /* @__PURE__ */ jsxRuntimeExports.jsx(
          Image,
          {
            src: websetConfigByHook.logo_img,
            onClick: () => navigate("/"),
            className: css.logo,
            remote: true,
            isGame: true
          }
        ) }),
        /* @__PURE__ */ jsxRuntimeExports.jsx(CompanyInfo, {}),
        quickNavigateToggle && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.styl5Quick, children: /* @__PURE__ */ jsxRuntimeExports.jsx(QuickEntry, {}) }),
        shareSettingsToggle || officialChannelToggle ? /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: "".concat(css.style5Tips, " tips_list"), children: [
          officialChannelToggle && officialChannels.length !== 0 && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: /* @__PURE__ */ jsxRuntimeExports.jsx(OfficialChannel, {}) }),
          shareSettingsToggle && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { children: /* @__PURE__ */ jsxRuntimeExports.jsx(Contact, {}) })
        ] }) : null,
        partnerInfoToggle || licenseToggle ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.styl5Partner, children: /* @__PURE__ */ jsxRuntimeExports.jsx(PartnerInfo, {}) }) : null,
        /* @__PURE__ */ jsxRuntimeExports.jsx(Reserved, {})
      ]
    }
  ) });
};
const BottomTips = () => {
  const { websetConfig } = useUserInfoStore();
  const footerJson = JSON.parse((websetConfig == null ? void 0 : websetConfig.footerJson) || "{}");
  const styleNum = footerJson.styleDisplay;
  return /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
    styleNum === "1" && /* @__PURE__ */ jsxRuntimeExports.jsx(Style1, {}),
    styleNum === "2" && /* @__PURE__ */ jsxRuntimeExports.jsx(Style2, {}),
    styleNum === "3" && /* @__PURE__ */ jsxRuntimeExports.jsx(Style3, {}),
    styleNum === "4" && /* @__PURE__ */ jsxRuntimeExports.jsx(Style4, {}),
    styleNum === "5" && /* @__PURE__ */ jsxRuntimeExports.jsx(Style5, {})
  ] });
};
export {
  BottomTips as B
};
