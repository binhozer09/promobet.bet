import { Z as useLocation, r as reactExports, q as useUserInfoStore, j as jsxRuntimeExports, a as joinClass, z as trans, as as customToFixed, Q as getMoneyUnit, I as Image, cI as getMemberRecordTradeDetail } from "./comps-B8ShbmG--2024_12_14_18_4.js";
import { C as CopyText, m as mineCss } from "./index-DgUEEG5U-2024_12_14_18_4.js";
import { a as customFormatTimer } from "./index-BcpFmBv5-2024_12_14_18_4.js";
import "./icons-Cdaou_E3-2024_12_14_18_4.js";
import "./index-CXpt7Cav-2024_12_14_18_4.js";
import "./index-DyG_R4zg-2024_12_14_18_4.js";
import "./index-4Dep-BeX-2024_12_14_18_4.js";
import "./index-CGmeRIXa-2024_12_14_18_4.js";
import "./index-CsbwU_08-2024_12_14_18_4.js";
import "./index-Bq-a07OY-2024_12_14_18_4.js";
import "./config-B4NCyDU0-2024_12_14_18_4.js";
const svg_theme_fill_color = "_svg_theme_fill_color_1h7a3_3";
const container = "_container_1h7a3_55";
const item = "_item_1h7a3_58";
const lable = "_lable_1h7a3_64";
const value = "_value_1h7a3_183";
const special = "_special_1h7a3_311";
const red = "_red_1h7a3_425";
const right = "_right_1h7a3_503";
const w60 = "_w60_1h7a3_509";
const withItem = "_withItem_1h7a3_513";
const bankIco = "_bankIco_1h7a3_517";
const title = "_title_1h7a3_526";
const phone = "_phone_1h7a3_540";
const infoBank = "_infoBank_1h7a3_634";
const green = "_green_1h7a3_642";
const white = "_white_1h7a3_646";
const css = {
  svg_theme_fill_color,
  container,
  item,
  lable,
  value,
  special,
  red,
  right,
  w60,
  withItem,
  bankIco,
  title,
  phone,
  infoBank,
  green,
  white
};
const formatStr = (str) => {
  if (!str) return "";
  const len = str.length;
  if (len < 4) return str;
  const end = str.substr(len - 4, 4);
  return "****".concat(end);
};
const WithdrawDetail = () => {
  var _a, _b, _c, _d;
  const location = useLocation();
  const query = new URLSearchParams(location.search);
  const id = query.get("id");
  const [detail, setDetail] = reactExports.useState({});
  const { info } = useUserInfoStore();
  const getDetail = async () => {
    const [res, err] = await getMemberRecordTradeDetail({
      id,
      flag: 272
    });
    if (err) return;
    if (res.d === null) return;
    setDetail(res.d[0]);
  };
  const userInfo = reactExports.useMemo(() => {
    if (!info) {
      return {
        username: "",
        uid: ""
      };
    }
    return {
      username: info.username,
      uid: info.uid
    };
  }, [info]);
  const stateToText = (state) => {
    switch (state) {
      case 371:
        return trans("正在审查中");
      case 372:
        return trans("审核拒绝");
      case 373:
        return trans("拉出来");
      case 374:
        return trans("提款成功");
      case 375:
        return trans("提现失败");
      case 376:
        return trans("订单异常");
      case 377:
        return trans("支付失败");
      case 381:
        return trans("取消");
      case 382:
        return trans("取消");
      default:
        return trans("未知");
    }
  };
  reactExports.useEffect(() => {
    getDetail();
  }, []);
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.container, children: [
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css.item, mineCss.flexItemCenter), children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.lable, children: trans("创建时间") }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.value, children: detail.created_at == "0" ? "" : customFormatTimer(detail.created_at) })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css.item, mineCss.flexItemCenter), children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.lable, children: trans("提款金额") }),
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css.value, css.special), children: [
        customToFixed((_a = detail.amount) != null ? _a : 0),
        getMoneyUnit(true),
        +detail.bank_ty === 18 && "(".concat(customToFixed(detail.amount / detail.rate) + detail.currency, ")")
      ] })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css.item, mineCss.flexItemCenter), children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.lable, children: trans("手续费") }),
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css.value, css.special), children: [
        customToFixed((_b = detail.discount) != null ? _b : 0),
        getMoneyUnit(true)
      ] })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css.item, mineCss.flexItemCenter), children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.lable, children: trans("到账金额") }),
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css.value, css.special), children: [
        customToFixed((_c = detail.ramount) != null ? _c : 0),
        getMoneyUnit(true),
        +detail.bank_ty === 18 && "(".concat(detail.crypto_amount + detail.currency, ")")
      ] })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css.item, mineCss.flexItemCenter), children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.lable, children: trans("提款方式") }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.value, children: detail.channel_name })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css.item, mineCss.flexItemCenter), children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.lable, children: trans("账户信息") }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.infoBank, children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.withItem, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { className: css.bankIco, src: [5, 10, 25].includes(detail.bank_ty) ? "/bank/".concat("BR", "/").concat(detail.bank_id, ".webp") : detail.bank_ty === 18 ? "/home/icons/".concat(detail.currency, ".webp") : "/home/icons/deposit_".concat((_d = detail.channel_name) == null ? void 0 : _d.toLocaleLowerCase(), ".webp") }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.right, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.title, children: [5, 10, 25, 18].includes(detail.bank_ty) ? detail.bank_name : userInfo.username }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.phone, children: formatStr(detail.account) })
        ] })
      ] }) })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css.item, mineCss.flexItemCenter), children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.lable, children: trans("审核状态") }),
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.w60, children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.value, detail.state === 374 ? css.green : detail.state === 371 || detail.state === 373 ? css.special : css.red), children: stateToText(detail.state) }),
        detail.state !== 374 ? /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: joinClass(css.lable, css.right, detail.state === 374 ? css.green : css.red), dangerouslySetInnerHTML: {
          __html: detail.remark
        } }) : ""
      ] })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css.item, mineCss.flexItemCenter), children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.lable, children: trans("审核时间") }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.value, children: detail.updated_at + "" === "0" ? "" : customFormatTimer(detail.updated_at) })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css.item, mineCss.flexItemCenter), children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.lable, children: trans("订单号") }),
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.value, children: /* @__PURE__ */ jsxRuntimeExports.jsx(CopyText, { value: detail.bill_no, className: css.white }) })
    ] }),
    +detail.bank_ty === 18 && /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css.item, mineCss.flexItemCenter), children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.lable, children: trans("汇率") }),
      /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.value, children: [
        getMoneyUnit(true),
        "1:U",
        customToFixed(1 / detail.rate, 10)
      ] })
    ] })
  ] });
};
export {
  WithdrawDetail as default
};
