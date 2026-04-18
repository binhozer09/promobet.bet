import { q as useUserInfoStore, r as reactExports, j as jsxRuntimeExports, a as joinClass, I as Image } from "./comps-B8ShbmG--2024_12_14_18_4.js";
import { R as RefForm, F as FormContext, W as WrapperField, e as FormItemContext } from "./index-BcpFmBv5-2024_12_14_18_4.js";
const svg_theme_fill_color = "_svg_theme_fill_color_1f8yy_3";
const error = "_error_1f8yy_55";
const errIcon = "_errIcon_1f8yy_61";
const small = "_small_1f8yy_71";
const defaultError = "_defaultError_1f8yy_156";
const errText = "_errText_1f8yy_162";
const formItem = "_formItem_1f8yy_166";
const css = {
  svg_theme_fill_color,
  error,
  errIcon,
  "default": "_default_1f8yy_68",
  small,
  defaultError,
  errText,
  formItem
};
const tipsImg = {
  sk2: "/home/icons/warning_icon_sk2.webp",
  furlaBlue: "/home/icons/warning_icon_furlaBlue.webp",
  elsaPink: "/home/icons/warning_icon_elsaPink.webp",
  microsoftRed: "/home/icons/warning_icon_sk2.webp"
};
const Item = ({ children, className, defaultShowError, ...rest }) => {
  const { theme } = useUserInfoStore();
  const formContext = reactExports.useContext(FormContext);
  const [errorShow, setErrorShow] = reactExports.useState(false);
  const error2 = reactExports.useMemo(() => {
    var _a;
    if (!formContext.errorFields) return "";
    if (!formContext.errorFields.length) return "";
    if (!rest.name) return "";
    return ((_a = formContext.errorFields.find((item) => item.name[0] === rest.name)) == null ? void 0 : _a.errors[0]) || "";
  }, [formContext.errorFields]);
  const size = reactExports.useMemo(() => {
    var _a;
    return (_a = formContext.size) != null ? _a : "default";
  }, [formContext.size]);
  const showError = reactExports.useMemo(() => {
    if (defaultShowError) {
      return true;
    }
    return errorShow;
  }, [defaultShowError, errorShow]);
  reactExports.useEffect(() => {
    setErrorShow(!!error2);
  }, [formContext.errorFields]);
  return /* @__PURE__ */ jsxRuntimeExports.jsx(
    WrapperField,
    {
      ...rest,
      onReset: () => {
        setErrorShow(false);
      },
      children: /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css.formItem, css[size], !error2 ? className : ""), children: [
        /* @__PURE__ */ jsxRuntimeExports.jsx(FormItemContext.Provider, { value: {
          errorShow
        }, children: /* @__PURE__ */ jsxRuntimeExports.jsx(WrapperField, { ...rest, children }) }),
        showError && /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(
          css.error,
          defaultShowError ? css.defaultError : "",
          css[size]
        ), children: [
          error2 && /* @__PURE__ */ jsxRuntimeExports.jsx(Image, { className: css.errIcon, src: tipsImg[theme] || "/home/icons/warning_icon.webp" }),
          error2 && /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css.errText, children: error2 })
        ] })
      ] })
    }
  );
};
const Form = ({ children, className, onFinishFailed, onFieldsChange, ...rest }) => {
  const [errorFields, setErrorFields] = reactExports.useState([]);
  return /* @__PURE__ */ jsxRuntimeExports.jsx(
    RefForm,
    {
      ...rest,
      onFinish: (values) => {
        setErrorFields([]);
        rest.onFinish && rest.onFinish(values);
      },
      onFinishFailed: ({ values, errorFields: errorFields2, outOfDate }) => {
        setErrorFields(errorFields2);
        onFinishFailed && onFinishFailed({ values, errorFields: errorFields2, outOfDate });
      },
      onFieldsChange: (changedFields, allFields) => {
        setErrorFields(
          allFields.filter((item) => item.errors.length > 0)
        );
        onFieldsChange && onFieldsChange(changedFields, allFields);
      },
      children: /* @__PURE__ */ jsxRuntimeExports.jsx(FormContext.Provider, { value: {
        errorFields,
        size: rest.size
      }, children })
    }
  );
};
Form.useForm = RefForm.useForm;
Object.assign(Form, {
  Field: Item
});
export {
  Form as F
};
