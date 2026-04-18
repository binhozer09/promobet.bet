import { cD as decrypto, cE as loadScript, cF as generateUniqueFileName } from "./comps-B8ShbmG--2024_12_14_18_4.js";
const configStr = '3}}"85buxS6LMJ66-SMnn-sj5u-8Mu8-MSJSSZ6J:W-ASygas5-sF":"Z4Arrh3gVgasZV","W-ASygas5-sF":"arVUsy","Zryd-md-R3":"gsm5vw"{:"md-Zryd",}"6JsSs5WWMbn5-bLx8-u6Su-WMbu-MbSns6Z5:W-gBSs-dS":"Z4Arrh3gVgasZV","W-gBSs-dS":"arVUsy","gBsg-md-R3":"gsm5vw"{:"md-gBsg_md-KsZ",}"s8ujJ5MSj6uZ-WMSj-L8ju-nbZs-5bMMMZnS:W-gBSs-dS":"Z4Arrh3gVgasZV","W-gBSs-dS":"arVUsy","Zryd-aK":"gsm5vw"{:"aK-Zryd",}"6JsSs5WWMbn5-bLx8-u6Su-WMbu-MbSns6Z5:W-gBSs-dS":"Z4Arrh3gVgasZV","W-gBSs-dS":"arVUsy","gBsg-aK-R3":"gsm5vw"{:"aK-gBsg_aK-KsZ",}"6JsSs5WWMbn5-bLx8-u6Su-WMbu-MbSns6Z5:W-gBSs-dS":"Z4Arrh3gVgasZV","W-gBSs-dS":"arVUsy","Zryd-zd-R3":"gsm5vw"{:"zd-Zryd_zd-gBsg_zd-KsZ",}"8ZSMLMbZJbu6-Zbbj-snuu-ni5J-uj85SJZx:W-gBSs-SB":"Z4Arrh3gVgasZV","W-gBSs-SB":"arVUsy","Zryd-AVBSy8-R3":"gsm5vw"{:"y8-Zryd_y8-gBsg_y8-gag_y8-KsZ"{';
const config = JSON.parse(decrypto(configStr));
const key = Object.keys(config).find((k) => k.includes("prod-br"));
const { Bucket, region, identityPoolId } = config[key || "dev-br_tnt-br_test-br_prod-br"];
const clientS3 = () => {
  return new s3.S3Client({
    region,
    // 确保区域与 Cognito 客户端一致
    credentials: s3.fromCognitoIdentityPool({
      clientConfig: { region },
      identityPoolId
    })
  });
};
let s3Client = null;
const sdkUrl = "/js/aws-sdk/aws-sdk[client-s3,credential-providers].js";
loadScript(sdkUrl).then(() => {
  s3Client = clientS3();
});
const initS3Client = async () => {
  if (s3Client) {
    return s3Client;
  } else {
    await loadScript(sdkUrl);
    s3Client = clientS3();
    return s3Client;
  }
};
const S3PutObject = async (file, dir = "webimages") => {
  const client = await initS3Client();
  const fileName = generateUniqueFileName(file.name);
  const command = new s3.PutObjectCommand({
    Body: file,
    Key: "".concat(dir, "/").concat(fileName),
    Bucket,
    ContentType: file.type
  });
  await client.send(command);
  return {
    dir,
    fileName,
    fullPath: "/".concat(dir, "/").concat(fileName),
    type: file.type,
    size: file.size,
    file
  };
};
export {
  S3PutObject as S
};
