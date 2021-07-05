'use strict';

/* ハンバーガーメニュー表示切替(toggle) */
const hamBtn = document.querySelector('.hambuger-btn');
const hamMenu = document.querySelector('.hambuger-menu');

const hamToggle = () => {
  hamMenu.style.display = hamMenu.style.display == 'block' ? 'none' : 'block';
};

hamBtn.addEventListener('click', hamToggle);
