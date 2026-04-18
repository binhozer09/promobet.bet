import { q as useUserInfoStore } from "./comps-B8ShbmG--2024_12_14_18_4.js";
import { h as LinhaIcon, T as DropDownIcon, aV as Language, bW as BaixarAppIcon, bX as SuporteIcon, bY as FAQIcon, bZ as SlotsIcon, b$ as CassinoIcon, c1 as EsporteIcon, c3 as PescariaIcon, c5 as CartasIcon, c7 as LoteriaIcon, c9 as EsportsIcon, cb as Cockfighting, cc as BlockchainIcon, ce as HotIcon, cf as ActivePopular, cg as ActiveSlots, ch as ActiveCassinoAoVivo, ci as ActiveEsporte, cj as ActivePescaria, ck as ActiveCartas, cl as ActiveLoteria, cm as ActiveEsports, cn as ActiveBlockchaim, co as ActiveCockfighting, cr as Recente, cs as ActiveRecente, ct as Favoritos, cu as ActiveFavoritos, cp as Experimente, cq as ActiveExperimente, cv as BetRecordsIcon, cw as ActionRecordes, cx as ConvidarIcon, cy as EventosIcon, cz as VIPIcon, cA as RebateIcon, cB as BonusIcon, cC as AlreadyGetBonusIcon } from "./icons-Cdaou_E3-2024_12_14_18_4.js";
const specialPlatformType = ["真人", "体育", "彩票", "电竞", "斗鸡", "热门"];
const specialPlatform = ["26595015200506"];
const state = useUserInfoStore.getState();
const GameTabsArr = [
  {
    text: "Popular",
    type: "热门",
    IconsCom: HotIcon,
    icon: "hot_active.webp",
    blue: "hot_blue_active.webp",
    grey: "hot.webp",
    action: "home",
    sort: -100,
    adminConfigShow: true,
    ActiveIcon: ActivePopular
  },
  {
    text: "Slots",
    type: "电子",
    IconsCom: ActiveSlots,
    icon: "slots_active.webp",
    grey: "slots.webp",
    action: "home",
    adminConfigShow: true,
    ActiveIcon: ActiveSlots
  },
  {
    text: "Cassino ao vivo",
    type: "真人",
    IconsCom: CassinoIcon,
    icon: "casino_active.webp",
    grey: "casino.webp",
    action: "home",
    adminConfigShow: false,
    ActiveIcon: ActiveCassinoAoVivo
  },
  {
    text: "Esporte",
    type: "体育",
    IconsCom: EsporteIcon,
    icon: "sports_active.webp",
    grey: "sports.webp",
    action: "home",
    adminConfigShow: true,
    ActiveIcon: ActiveEsporte
  },
  {
    text: "Pescaria",
    type: "捕鱼",
    IconsCom: PescariaIcon,
    icon: "fishing_active.webp",
    grey: "fishing.webp",
    action: "home",
    adminConfigShow: true,
    ActiveIcon: ActivePescaria
  },
  {
    text: "Cartas",
    type: "棋牌",
    IconsCom: CartasIcon,
    icon: "cartas_active.webp",
    grey: "cartas.webp",
    action: "home",
    adminConfigShow: true,
    ActiveIcon: ActiveCartas
  },
  {
    text: "Loteria",
    type: "彩票",
    IconsCom: LoteriaIcon,
    icon: "lottery_active.webp",
    grey: "lottery.webp",
    action: "home",
    adminConfigShow: true,
    ActiveIcon: ActiveLoteria
  },
  {
    text: "Esports",
    type: "电竞",
    IconsCom: EsportsIcon,
    icon: "esports_active.webp",
    grey: "esports.webp",
    action: "home",
    adminConfigShow: true,
    ActiveIcon: ActiveEsports
  },
  {
    text: "Blockchain",
    type: "小游戏",
    IconsCom: ActiveBlockchaim,
    icon: "blockchain_active.webp",
    grey: "blockchain.webp",
    action: "home",
    adminConfigShow: true,
    ActiveIcon: ActiveBlockchaim
  },
  {
    text: "斗鸡",
    type: "斗鸡",
    IconsCom: Cockfighting,
    icon: "blockchain_active.webp",
    grey: "blockchain.webp",
    action: "home",
    adminConfigShow: true,
    ActiveIcon: ActiveCockfighting
  },
  {
    text: "Recente",
    type: "最近",
    IconsCom: ActiveRecente,
    icon: "blockchain_active.webp",
    grey: "blockchain.webp",
    action: "home",
    ActiveIcon: ActiveRecente,
    sort: 97
  },
  {
    text: "Favoritos",
    type: "收藏",
    IconsCom: ActiveFavoritos,
    icon: "blockchain_active.webp",
    grey: "blockchain.webp",
    action: "home",
    ActiveIcon: ActiveFavoritos,
    sort: 98
  },
  {
    text: "试玩",
    type: "试玩",
    IconsCom: Experimente,
    ActiveIcon: ActiveExperimente,
    adminConfigShow: true,
    icon: "blockchain_active.webp",
    grey: "blockchain.webp",
    sort: 99
  },
  // {
  //   text: 'Jogar Grátis',
  //   IconsCom: JogarIcon,
  //   icon: 'jogar_active.webp',
  //   grey: 'jogar.webp',
  // },
  {
    type: "music",
    action: "records",
    sort: 100,
    switchType: "player"
  },
  {
    text: "Recordes de Apostas",
    type: "投注记录",
    IconsCom: BetRecordsIcon,
    icon: "betrecords_active.webp",
    action: "records",
    url: "/record-betting?tag=2",
    sort: 101,
    ActiveIcon: ActionRecordes
  },
  {
    text: "推广",
    IconsCom: ConvidarIcon,
    icon: "agent_active.webp",
    type: "代理",
    action: "agent",
    url: "/agent",
    sort: 102,
    ActiveIcon: ConvidarIcon
  }
];
const GameTabs = GameTabsArr;
const InfoTabs = [
  {
    text: "Eventos",
    IconsCom: EventosIcon,
    isShowInHome: false,
    icon: "event_active.webp",
    cssName: "event",
    url: "/activity"
  },
    {
    text: "Tarefa",
    IconsCom: EventosIcon,
    isShowInHome: false,
    icon: "missao_active.webp",
    cssName: "vip",
    url: "/missao"
  },
//  {
//    text: "Tarefa",
//    IconsCom: EventosIcon,
//    isShowInHome: false,
//    icon: "missao_active.webp",
//    cssName: "missao",
//    url: "/missao",
//    type: "missao"
//  },
  {
    text: "VIP",
    IconsCom: VIPIcon,
    isShowInHome: false,
    icon: "vip_active.webp",
    cssName: "vip",
    url: "/vip"
  },
  {
    text: "Rebate",
    IconsCom: RebateIcon,
    isShowInHome: false,
    icon: "mission_active.webp",
    url: "/back-rate",
    cssName: "mission",
    type: "rebate"
  },
  // {
  //   text: 'Rebate',
  //   icon: 'rebate_active.webp',
  //   cssName: 'rebate'
  // },
  // {
  //   text: 'Reward',
  //   icon: 'reward_active.webp',
  //   cssName: 'reward'
  // },
  // {
  //   text: 'Interest',
  //   icon: 'interest_active.webp',
  //   cssName: 'interest'
  // },
  {
    text: "Pendente",
    IconsCom: BonusIcon,
    isShowInHome: false,
    icon: "bonus_active.webp",
    cssName: "bonus",
    url: "/bonus",
    type: "bonus"
  },
  {
    // 已领取记录
    text: "Histórico",
    IconsCom: AlreadyGetBonusIcon,
    isShowInHome: true,
    icon: "alreadyGetBouns.webp",
    cssName: "bonus",
    url: "/alreadyGetbonus"
  }
  // {
  //   text: 'Juros',
  //   IconsCom: InterestIcon,
  //   isShowInHome: false,
  //   icon: 'int_active.webp',
  //   cssName: 'juros',
  //   url: '',
  //   type: 'lxb',
  // },
];
const gameTypeNames = [
  "真人",
  "体育",
  "彩票",
  "电竞",
  "电子",
  "捕鱼",
  "棋牌",
  "小游戏"
];
const ToolTabs = [
  {
    text: "Linha 1",
    IconsCom: LinhaIcon,
    isShowInHome: false,
    // 是否显示在首页导航栏
    path: "line",
    rightIcon: DropDownIcon,
    switchType: "netsignal"
  },
  {
    path: "language",
    icon: "language.webp",
    IconsCom: Language,
    isShowInHome: false,
    // 是否显示在首页导航栏
    rightIcon: DropDownIcon,
    id: "languageRef",
    switchType: "lang"
  },
  {
    text: "Baixar Agora",
    IconsCom: BaixarAppIcon,
    isShowInHome: false,
    isHiden: state.isHiddenDownloadApp,
    path: "download",
    icon: "download_icon.webp",
    blue: "download_icon_blue.webp",
    icon2: "download_inhome_icons.webp"
  },
  {
    text: "Suporte",
    isShowInHome: false,
    IconsCom: SuporteIcon,
    icon: "call_icon.webp",
    blue: "call_icon_blue.webp"
  },
  {
    text: "FAQ",
    IconsCom: FAQIcon,
    isShowInHome: false,
    path: "service"
  }
];
const rebateTypes = [
  {
    text: "老虎机",
    type: "电子",
    icon: "slots",
    id: 3,
    IconsCom: SlotsIcon
  },
  {
    text: "真人",
    type: "真人",
    icon: "casino",
    id: 1,
    IconsCom: CassinoIcon
  },
  {
    text: "体育",
    type: "体育",
    icon: "sports",
    id: 4,
    IconsCom: EsporteIcon
  },
  {
    text: "捕鱼",
    type: "捕鱼",
    icon: "fishing",
    id: 2,
    IconsCom: PescariaIcon
  },
  {
    text: "棋牌",
    type: "棋牌",
    icon: "cartas",
    id: 5,
    IconsCom: CartasIcon
  },
  {
    text: "彩票",
    type: "彩票",
    icon: "lottery",
    id: 7,
    IconsCom: LoteriaIcon
  },
  {
    text: "电竞",
    type: "电竞",
    icon: "esports",
    id: 6,
    IconsCom: EsportsIcon
  },
  {
    text: "斗鸡",
    type: "斗鸡",
    icon: "blockchain",
    id: 8,
    IconsCom: Cockfighting
  },
  {
    text: "区块链",
    type: "小游戏",
    icon: "blockchain",
    id: 9,
    IconsCom: BlockchainIcon
  }
];
const platTypes = [
  {
    label: "全部",
    type: "全部",
    value: 0
  },
  {
    label: "真人",
    type: "真人",
    value: 1
  },
  {
    label: "捕鱼",
    type: "捕鱼",
    value: 2
  },
  {
    label: "电子",
    type: "电子",
    value: 3
  },
  {
    label: "体育",
    type: "体育",
    value: 4
  },
  {
    label: "棋牌",
    type: "棋牌",
    value: 5
  },
  {
    label: "电竞",
    type: "电竞",
    value: 6
  },
  {
    label: "彩票",
    type: "彩票",
    value: 7
  },
  {
    label: "斗鸡",
    type: "斗鸡",
    value: 9
  },
  {
    label: "区块链",
    type: "小游戏",
    value: 9
  }
];
export {
  GameTabs as G,
  InfoTabs as I,
  ToolTabs as T,
  specialPlatform as a,
  gameTypeNames as g,
  platTypes as p,
  rebateTypes as r,
  specialPlatformType as s
};
