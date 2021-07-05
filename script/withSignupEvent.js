'use strict';

/* 注文時の会員登録用フォームのdisabled */
const withSignup = document.querySelector('.with-signup');
const items = document.querySelectorAll('.disabled-toggle');
const guest = document.querySelector('input[value="guest"]');
const signup = document.querySelector('input[value="signup"]');

/* with-signupなら登録用の項目を表示 */
const disabledToggle = () => {
  if (signup.checked) {
    items.forEach((elm) => { elm.disabled = false; });
  } else {
    items.forEach((elm) => { elm.disabled = true; });
  }
}
/* with-signupの状態監視 */
withSignup.addEventListener('change', disabledToggle);

// チェック状態の反映を初回実行
// ブラウザバック時のチェック状態の取得のブラウザ仕様について
// DOMContentLoadedやloadのイベントを利用しても意図通りの取得ができないため、setTimeoutで処理を並びなおさせることで回避
// window.addEventListener('load', () => { setTimeout(disabledToggle, 0); });
setTimeout(disabledToggle, 0);