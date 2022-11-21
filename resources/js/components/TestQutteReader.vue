<template>
  <div>
    <p class="error">{{ error }}</p>

    <p class="decode-result"><b>{{ result }}</b></p>

    <div v-if="this.check">
      <qrcode-stream @decode="onDecode" @init="onInit" />
    </div>

  </div>
</template>

<script>
import { QrcodeStream } from 'vue-qrcode-reader'
export default {
  components: { QrcodeStream },
  props: {
    check: {
      type: Boolean,
      default: false,
    },
    from: {
      type: String,
      default: '',
    },
    to: {
      type: String,
      default: '',
    },
  },
  data () {
    return {
      result: '',
      error: '',
      test: this.check,
    }
  },

  methods: {
    onDecode (result) {
      this.result = 'テストをクリアしました。このウィンドウを閉じて手続きを進めてください。'
      this.check = false
    },

    async onInit (promise) {
      try {
        await promise
        await this.$emit("judge", 'success');
      } catch (error) {
        if (error.name === 'NotAllowedError') {
          this.error = "カメラの使用を許可してください。 ERROR: you need to grant camera access permission"
        } else if (error.name === 'NotFoundError') {
          this.error = "この端末ではBUNTSUに対応していません。 ERROR: no camera on this device"
        } else if (error.name === 'NotSupportedError') {
          this.error = "この端末ではBUNTSUに対応していません。 ERROR: secure context required (HTTPS, localhost)"
        } else if (error.name === 'NotReadableError') {
          this.error = "この端末ではBUNTSUに対応していません。 ERROR: is the camera already in use?"
        } else if (error.name === 'OverconstrainedError') {
          this.error = "この端末ではBUNTSUに対応していません。 ERROR: installed cameras are not suitable"
        } else if (error.name === 'この端末ではBUNTSUに対応していません。') {
          this.error = "この端末ではQ手を読み取れません。 ERROR: Stream API is not supported in this browser"
        } else if (error.name === 'InsecureContextError') {
          this.error = "この端末ではBUNTSUに対応していません。 ERROR: Camera access is only permitted in secure context. Use HTTPS or localhost rather than HTTP.";
        } else {
          this.error = `この端末ではBUNTSUに対応していません。 ERROR: Camera error (${error.name})`;
        }
        this.$emit("judge", 'error');
      }
    }
  }
}
</script>

<style scoped>
.error {
  font-weight: bold;
  color: red;
}

</style>