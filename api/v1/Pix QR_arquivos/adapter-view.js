const WIDTH = 750; //如果是尺寸的设计稿在这里修改
const maxWidth = 450;
const setView = () => {
  //设置html标签的fontSize
  document.documentElement.style.fontSize = (100 * Math.min(screen.width, maxWidth)) / WIDTH + 'px';
};

window.onresize = setView;
setView();
