import { r as reactExports, aq as events, j as jsxRuntimeExports, b6 as Spin, a as joinClass, z as trans, H as Message } from "./comps-B8ShbmG--2024_12_14_18_4.js";
import { S as S3PutObject } from "./s3--cfsTSzQ-2024_12_14_18_4.js";
const svg_theme_fill_color = "_svg_theme_fill_color_kno9d_3";
const uploadContainer = "_uploadContainer_kno9d_55";
const fileInput = "_fileInput_kno9d_61";
const uploadButton = "_uploadButton_kno9d_65";
const item = "_item_kno9d_186";
const top = "_top_kno9d_190";
const botton = "_botton_kno9d_424";
const uploadButton2 = "_uploadButton2_kno9d_659";
const progressBar = "_progressBar_kno9d_812";
const statusMessage = "_statusMessage_kno9d_818";
const successMessage = "_successMessage_kno9d_823";
const errorMessage = "_errorMessage_kno9d_827";
const uploadImg = "_uploadImg_kno9d_831";
const tips = "_tips_kno9d_844";
const css = {
  svg_theme_fill_color,
  uploadContainer,
  fileInput,
  uploadButton,
  item,
  top,
  botton,
  uploadButton2,
  progressBar,
  statusMessage,
  successMessage,
  errorMessage,
  uploadImg,
  tips
};
const Upload = () => {
  reactExports.useState(null);
  reactExports.useState(0);
  const [uploadStatus, setUploadStatus] = reactExports.useState("");
  const [uploadResult, setUploadResult] = reactExports.useState(null);
  const handleFileChange = (event) => {
    setUploadStatus("ready");
    uploadFileFn(event.target.files[0]);
  };
  reactExports.useEffect(() => {
    events.on("closeUpload", handleEvent);
    return () => {
      events.off("closeUpload", handleEvent);
    };
  }, []);
  const handleEvent = () => {
    setUploadResult(null);
    setUploadStatus("");
  };
  async function uploadFileFn(file) {
    console.log("file :>> ", file);
    if (file.size / 1024 / 1024 > 50) {
      Message.error(
        trans("The file size cannot exceed {{size}}", { size: "50MB" })
      );
      return;
    }
    if (!file || uploadStatus == "loading") return;
    setUploadStatus("loading");
    try {
      const response = await S3PutObject(file);
      setUploadResult(response);
      events.emit("uploadSuccess", response);
      setUploadStatus("success");
    } catch (e) {
      console.error(e);
      setUploadStatus("error");
    }
  }
  const videoRef = reactExports.useRef(null);
  const handleVideoClick = () => {
    if (videoRef.current) {
      if (videoRef.current.paused) {
        videoRef.current.play();
      } else {
        videoRef.current.pause();
      }
    }
  };
  return /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { children: [
    uploadResult && /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.uploadImg, children: uploadStatus == "loading" ? /* @__PURE__ */ jsxRuntimeExports.jsx(Spin, {}) : /* @__PURE__ */ jsxRuntimeExports.jsx(jsxRuntimeExports.Fragment, { children: uploadResult.type.includes("video") ? /* @__PURE__ */ jsxRuntimeExports.jsx(
      "video",
      {
        onClick: handleVideoClick,
        ref: videoRef,
        style: { width: "100rem", height: "100rem" },
        children: /* @__PURE__ */ jsxRuntimeExports.jsx(
          "source",
          {
            src: "".concat("https://dl-br-cf.sadslj88.com").concat(uploadResult.fullPath),
            type: "video/mp4"
          }
        )
      }
    ) : /* @__PURE__ */ jsxRuntimeExports.jsx("img", { src: "".concat("https://dl-br-cf.sadslj88.com").concat(uploadResult.fullPath) }) }) }),
    /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: joinClass(css.uploadContainer, "button"), children: [
      /* @__PURE__ */ jsxRuntimeExports.jsx(
        "input",
        {
          type: "file",
          onChange: handleFileChange,
          accept: "image/*, video/*",
          id: "fileInput",
          className: css.fileInput
        }
      ),
      !uploadResult ? /* @__PURE__ */ jsxRuntimeExports.jsx("label", { htmlFor: "fileInput", className: css.uploadButton, children: uploadStatus == "loading" ? /* @__PURE__ */ jsxRuntimeExports.jsx(Spin, {}) : /* @__PURE__ */ jsxRuntimeExports.jsxs(jsxRuntimeExports.Fragment, { children: [
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.top, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.item }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.item })
        ] }),
        /* @__PURE__ */ jsxRuntimeExports.jsxs("div", { className: css.botton, children: [
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.item }),
          /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.item })
        ] })
      ] }) }) : /* @__PURE__ */ jsxRuntimeExports.jsx("label", { htmlFor: "fileInput", style: { display: "flex" }, children: /* @__PURE__ */ jsxRuntimeExports.jsx("span", { className: css.uploadButton2, children: trans("Resend") }) })
    ] }),
    /* @__PURE__ */ jsxRuntimeExports.jsx("div", { className: css.tips, children: trans("The file size cannot exceed {{size}}", { size: "50MB" }) })
  ] });
};
export {
  Upload as U
};
