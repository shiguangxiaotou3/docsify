"use strict";
(() => {
  // package.json
  var version = "0.4.2";

  // src/styles/index.scss
  var css = `@media screen and (max-width: 768px){.controls{display:none !important}}.chat-panel{position:relative;border-radius:.5rem;margin:1rem auto;background-color:#f6f8fa;overflow:hidden}.chat-panel button{border:0;background:none;margin:0;padding:0;display:flex;align-items:center;justify-content:center}.chat-panel .title-bar{text-align:center}.chat-panel .title-bar.mac{display:flex;justify-content:center;padding:.9rem 1rem;width:100%}.chat-panel .title-bar.mac .title{font-weight:500;font-size:.9rem;line-height:.9rem;letter-spacing:.5px}.chat-panel .title-bar.mac .controls{position:absolute;top:1rem;left:1rem;display:grid;gap:.6rem;grid-template-columns:repeat(3, 0.8rem)}.chat-panel .title-bar.mac .controls svg{opacity:0}.chat-panel .title-bar.mac .controls:hover button{transform:scale(1.2)}.chat-panel .title-bar.mac .controls:hover svg{opacity:1}.chat-panel .title-bar.mac .controls .close{--bg-color: #ff5f56;--border-color: #e0443e}.chat-panel .title-bar.mac .controls .stretch{--bg-color: #27c93f;--border-color: #1aab29}.chat-panel .title-bar.mac .controls .stretch svg{transform:rotate(90deg)}.chat-panel .title-bar.mac .controls .minimize{--bg-color: #ffbd2e;--border-color: #dea123}.chat-panel .title-bar.mac .controls .circle{width:.8rem;height:.8rem;border-radius:50%;background-color:var(--bg-color);box-shadow:0 0 0 .5px var(--border-color);transition:transform .1s ease-in}.chat-panel .title-bar.windows{display:flex;flex-shrink:0;width:100%;height:28px;align-items:center;justify-content:center;position:relative;border-radius:6px 6px 0 0}.chat-panel .title-bar.windows .title{font-size:.8rem}.chat-panel .title-bar.windows .controls{height:100%;position:absolute;right:0;display:flex;align-items:center}.chat-panel .title-bar.windows .controls svg{width:12px;height:100%}.chat-panel .title-bar.windows .controls button{height:100%;padding:0 18px;transition:all ease-in-out 60ms}.chat-panel .title-bar.windows .controls button:hover{background:rgba(136,136,136,.2)}.chat-panel .title-bar.windows .controls button[class=close]:hover{background:rgba(255,0,0,.8)}.chat-panel .title-bar.windows .controls button[class=close]:hover svg{filter:invert(1)}.chat-panel .main-area{width:100%;min-height:auto}.chat-panel .main-area .chat-message{display:flex;position:relative;padding:1rem;opacity:0;transform:translate(-10%);transition:transform .4s ease-out,opacity .4s ease-in}.chat-panel .main-area .chat-message.myself{transform:translate(10%);justify-content:flex-end}.chat-panel .main-area .chat-message.myself .message-box{margin-left:0;margin-right:.5rem}.chat-panel .main-area .chat-message.myself .nickname{text-align:right}.chat-panel .main-area .chat-message.show{opacity:1;transform:translate(0)}.chat-panel .main-area .chat-message .avatar{width:2.5rem;height:2.5rem;overflow:hidden;flex-shrink:0;border-radius:50%;line-height:2.5rem;color:#fff;text-align:center}.chat-panel .main-area .chat-message .avatar img{display:inline-flex;line-height:0;justify-content:center;align-items:center;color:#fff}.chat-panel .main-area .chat-message .message-box{display:inline-block;margin-left:.5rem;max-width:90%;vertical-align:top}.chat-panel .main-area .chat-message .message-box .nickname{font-size:.8rem;color:gray}.chat-panel .main-area .chat-message .message-box .message{position:relative;font-size:.9rem;border-radius:.5rem;background-color:#fff;word-break:break-all;padding:.6rem .7rem;margin-top:.2rem;box-shadow:rgba(0,0,0,0) 0px 0px 0px 0px,rgba(0,0,0,0) 0px 0px 0px 0px,rgba(0,0,0,.05) 0px 1px 2px 0px}.chat-panel .main-area .chat-message .message-box .message .chat-text{min-height:1rem}.chat-panel .main-area .chat-message .message-box .message .chat-image{display:block;min-width:5rem;border-radius:.3rem;margin-bottom:.3rem}`;
  var styleElement = document.createElement("style");
  styleElement.textContent = css;
  document.head.appendChild(styleElement);

  // src/icons/mac/close.svg
  var close_default = '<svg width="7" height="7" fill="none" xmlns="http://www.w3.org/2000/svg"><path stroke="#000" stroke-width="1.2" stroke-linecap="round"d="M1.182 5.99L5.99 1.182m0 4.95L1.182 1.323"></path></svg>';

  // src/icons/mac/minimize.svg
  var minimize_default = '<svg width="6" height="1" fill="none" xmlns="http://www.w3.org/2000/svg"><path stroke="#000" stroke-width="2" stroke-linecap="round" d="M.61.703h5.8" /></svg>';

  // src/icons/mac/stretch.svg
  var stretch_default = '<svgviewBox="0 0 13 13"xmlns="http://www.w3.org/2000/svg"fill-rule="evenodd"clip-rule="evenodd"stroke-linejoin="round"stroke-miterlimit="2"><path d="M4.871 3.553L9.37 8.098V3.553H4.871zm3.134 5.769L3.506 4.777v4.545h4.499z" /><circle cx="6.438" cy="6.438" r="6.438" fill="none" /></svg>';

  // src/icons/windows/close.svg
  var close_default2 = '<svgversion="1.0"xmlns="http://www.w3.org/2000/svg"width="512.000000pt"height="512.000000pt"viewBox="0 0 512.000000 512.000000"preserveAspectRatio="xMidYMid meet"><g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)" fill="#000000"stroke="none"><pathd="M900 4272 c-46 -23 -75 -79 -65 -130 6 -33 98 -129 778 -809 l772 -773 -772 -772 c-849 -851 -807 -802 -767 -885 25 -51 77 -78 129 -69 36 7 110 78 813 779 l772 772 773 -772 c702 -701 776 -772 812 -779 52 -9 104 18 129 69 40 83 82 34 -767 885 l-772 772 772 773 c680 680 772 776 778 809 15 82 -61 158 -143 143 -33 -6 -129 -98 -810 -778 l-772 -772 -768 767 c-428 428 -779 772 -795 777 -39 15 -56 14 -97 -7z" /></g></svg>';

  // src/icons/windows/minimize.svg
  var minimize_default2 = '<svgversion="1.0"xmlns="http://www.w3.org/2000/svg"width="512.000000pt"height="512.000000pt"viewBox="0 0 512.000000 512.000000"preserveAspectRatio="xMidYMid meet"><g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)" fill="#000000"stroke="none"><pathd="M724 2751 c-105 -64 -109 -209 -8 -272 l39 -24 1805 0 1805 0 35 22 c101 63 104 194 6 267 l-27 21 -1812 3 c-1761 2 -1813 1 -1843 -17z" /></g></svg>';

  // src/icons/windows/stretch.svg
  var stretch_default2 = '<svgversion="1.0"xmlns="http://www.w3.org/2000/svg"width="512.000000pt"height="512.000000pt"viewBox="0 0 512.000000 512.000000"preserveAspectRatio="xMidYMid meet"><g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)" fill="#000000"stroke="none"><pathd="M1100 4464 c-218 -47 -399 -229 -445 -449 -22 -105 -22 -2805 0 -2910 47 -222 228 -403 450 -450 105 -22 2805 -22 2910 0 222 47 403 228 450 450 22 105 22 2805 0 2910 -47 222 -228 403 -450 450 -102 21 -2815 21 -2915 -1z m2870 -315 c58 -18 130 -78 159 -134 l26 -50 3 -1385 c2 -1001 0 -1396 -9 -1426 -16 -60 -76 -133 -134 -163 l-50 -26 -1405 0 -1405 0 -50 26 c-58 30 -118 103 -134 163 -9 30 -11 425 -9 1426 l3 1385 26 50 c28 53 100 116 153 133 46 15 2776 16 2826 1z" /></g></svg>';

  // src/index.ts
  var IS_MAC = /(Mac|iPhone|iPod|iPad)/i.test(navigator.platform);
  var CHAT_PANEL_MARKUP = /( *)(<!-+\s+chat:\s*?start\s+-+>)(?:(?!(<!-+\s+chat:\s*?(?:start|end)\s+-+>))[\s\S])*(<!-+\s+chat:\s*?end\s+-+>)/;
  var CHAT_TITLE_MARKUP = /[\r\n]*(\s*)<!-+\s+title:\s*(.*)\s+-+>/;
  var CHAT_MESSAGE_MARKUP = /[\r\n]*(\s*)#{1,6}\s*[*_]{2}\s*(.*[^\s])\s*[*_]{2}[\r\n]+([\s\S]*?)(?=#{1,6}\s*[*_]{2}|<!-+\s+chat:\s*?end\s+-+>)/m;
  var setting = {
    animation: 50,
    myself: null,
    os: IS_MAC ? "mac" : "windows",
    title: "\u804A\u5929\u8BB0\u5F55",
    users: [],
    version
  };
  var titleBarIcon = {
    mac: {
      close: close_default,
      minimize: minimize_default,
      stretch: stretch_default
    },
    windows: {
      close: close_default2,
      minimize: minimize_default2,
      stretch: stretch_default2
    }
  };
  function stringToColor(string) {
    let hash = 0;
    let color = "#";
    for (let i = 0; i < string.length; i++) {
      hash = string.charCodeAt(i) + ((hash << 5) - hash);
    }
    for (let i = 0; i < 3; i++) {
      const value = hash >> i * 8 & 255;
      color += ("00" + value.toString(16)).substr(-2);
    }
    return color;
  }
  function generateTitleBar(title) {
    let os = setting.os;
    let controls = "";
    switch (os) {
      case "mac":
        controls = `
        <button class="circle close">${titleBarIcon[os].close}</button>
        <button class="circle minimize">${titleBarIcon[os].minimize}</button>
        <button class="circle stretch"> ${titleBarIcon[os].stretch}</button>
      `;
        break;
      case "windows":
        controls = `
        <button class="minimize">${titleBarIcon[os].minimize}</button>
        <button class="stretch"> ${titleBarIcon[os].stretch}</button>
        <button class="close">${titleBarIcon[os].close}</button>
      `;
        break;
      default:
        console.error(`os "${os}" is invalid argument`);
        break;
    }
    return `
    <header class="title-bar ${os}">
      <div class="controls">${controls}</div>
      <span class="title">${title}</span>
    </header>
  `;
  }
  function generateAvatar(user) {
    const { avatar, nickname } = user;
    if (avatar) {
      return `<div class="avatar"><img src="${avatar}"></div>`;
    } else {
      const color = stringToColor(nickname);
      const first_char = nickname.substring(0, 1);
      return `<div class="avatar" style="background-color: ${color};">${first_char}</div>`;
    }
  }
  function generateMessage(content) {
    const regex = /!\[(.*?)\]\((.*?)\)/;
    const segments = content.trim().split("\n");
    const message = segments.map((segment) => {
      const is_image = regex.test(segment);
      if (is_image) {
        const image = '<img class="chat-image" src="$2" alt="$1" />';
        return segment.replace(regex, image);
      } else {
        const text = `<div class="chat-text">${segment}</div>`;
        return segment.replace(segment, text);
      }
    });
    return message.join("\n");
  }
  function renderChat(content, vm) {
    let chatExecs;
    let messageExecs;
    while (chatExecs = CHAT_PANEL_MARKUP.exec(content)) {
      let raw_chat = chatExecs[0];
      let title = setting.title;
      let chat_start_replacement = "";
      let chat_end_replacement = "";
      const has_title = CHAT_TITLE_MARKUP.test(raw_chat);
      const has_message = CHAT_MESSAGE_MARKUP.test(raw_chat);
      if (has_title) {
        const titleExecs = CHAT_TITLE_MARKUP.exec(raw_chat);
        title = titleExecs[2];
        raw_chat = raw_chat.replace(CHAT_TITLE_MARKUP, "");
      }
      const chat_title_bar = generateTitleBar(title);
      if (has_message) {
        chat_start_replacement = `<section class="${"chat-panel" /* ChatPanel */}">${chat_title_bar}<main class="main-area">`;
        chat_end_replacement = `</main></section>`;
        while (messageExecs = CHAT_MESSAGE_MARKUP.exec(raw_chat)) {
          const nickname = messageExecs[2];
          const message = generateMessage(messageExecs[3]);
          const user = setting.users.find((item) => item.nickname === nickname) ?? {
            nickname
          };
          const is_me = setting.myself === nickname;
          const avatar = generateAvatar(user);
          const chatContentTemplate = `
          <div class="chat-message ${!is_me ? "" : "myself"}">
            $1
            <div class="message-box">
              <div class="nickname">${nickname}</div>
              <div class="message">${message}</div>
            </div>
            $2
          </div>
        `;
          const avatarPosition = !is_me ? ["$1", "$2"] : ["$2", "$1"];
          const chatContent = chatContentTemplate.replace(avatarPosition[0], avatar).replace(avatarPosition[1], "");
          raw_chat = raw_chat.replace(messageExecs[0], chatContent);
        }
      }
      const chat_start = chatExecs[2];
      const chat_end = chatExecs[4];
      raw_chat = raw_chat.replace(chat_start, chat_start_replacement);
      raw_chat = raw_chat.replace(chat_end, chat_end_replacement);
      raw_chat = raw_chat.replace(/(\s{2,}|\n)/g, "");
      content = content.replace(chatExecs[0], raw_chat);
    }
    return content;
  }
  function createResizeObserver() {
    return new ResizeObserver((entries) => {
      entries.forEach((entry) => {
        const { target } = entry;
        const { offsetWidth } = target;
        const chatImageElements = target.getElementsByClassName("chat-image" /* ChatImage */);
        for (let i = 0; i < chatImageElements.length; i++) {
          const element = chatImageElements[i];
          element.style.maxWidth = `calc((${offsetWidth}px - 5rem) / 2)`;
        }
      });
    });
  }
  function createIntersectionObserver() {
    return new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        const { target, isIntersecting } = entry;
        const chatMessageElements = target.getElementsByClassName("chat-message" /* ChatMessage */);
        for (let i = 0; i < chatMessageElements.length; i++) {
          const element = chatMessageElements[i];
          if (isIntersecting) {
            setTimeout(() => element.classList.add("show"), i * setting.animation);
          } else {
            element.classList.remove("show");
          }
        }
      });
    });
  }
  function docsifyChat(hook, vm) {
    let has_chat;
    const resizeObserver = createResizeObserver();
    const intersectionObserver = createIntersectionObserver();
    hook.beforeEach((content) => {
      has_chat = CHAT_PANEL_MARKUP.test(content);
      if (has_chat) {
        content = renderChat(content, vm);
      }
      return content;
    });
    hook.doneEach(() => {
      resizeObserver.disconnect();
      intersectionObserver.disconnect();
      if (!has_chat) {
        return;
      }
      const chatPanelElements = document.getElementsByClassName("chat-panel" /* ChatPanel */);
      for (let i = 0; i < chatPanelElements.length; i++) {
        const element = chatPanelElements[i];
        resizeObserver.observe(element);
        intersectionObserver.observe(element);
      }
    });
  }
  if (window) {
    window.$docsify ??= {};
    window.$docsify.chat ??= {};
    for (const key in window.$docsify.chat) {
      if (Object.prototype.hasOwnProperty.call(setting, key)) {
        setting[key] = window.$docsify.chat[key];
      }
    }
    window.$docsify.plugins ??= [];
    window.$docsify.plugins = [docsifyChat, ...window.$docsify.plugins];
  }
})();
