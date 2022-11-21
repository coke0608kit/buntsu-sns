<template>
  <div>
    <div id="parent" class="bg-warning btn my-3 d-flex flex-column mx-0 p-0">
    </div>
    <div style="text-align: center;font-size: 12px;">
      <p>カード情報は<a href="https://pay.jp/security" target="_blank" style="color: black;text-decoration: underline;">PAY.JP <i class="fas fa-external-link-alt" aria-hidden="true"></i></a>が責任を持って管理します。</p>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      publickey: {
        type: String,
        default: '',
      },
    },
    mounted() {
      let recaptchaScript = document.createElement('script')
      recaptchaScript.setAttribute('src', 'https://checkout.pay.jp/')
      recaptchaScript.setAttribute('class', 'payjp-button')
      recaptchaScript.setAttribute('data-key', this.publickey)
      recaptchaScript.setAttribute('data-text', "カード情報を入力")
      recaptchaScript.setAttribute('data-submit-text', "カードを登録する")
      let parent = document.getElementById('parent');
      parent.appendChild(recaptchaScript);
    },
  };
</script>

<style>
  #payjp_checkout_box {
    width: 100%;
    height: 100px;
  }
  #payjp_checkout_box input[type=button] {
    border: none!important;
    background: none;
    background-image: none!important;
    background-color: unset!important;
    background-image: none;
    color: black!important;
    
    width: 100%;
    height: 100px;
  }
</style>