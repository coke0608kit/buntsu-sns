<template>
  <div>
    <p class="error">{{ error }}</p>

    <p class="decode-result"><b>{{ result }}</b></p>
    
    <div v-if="oldFromUserUrl !== ''">
      <p><b>旧宛名情報</b></p>
    </div>
    <div class="row" v-if="oldFromUserUrl !== ''">
      <a :href="oldFromUserUrl" class="text-dark col text-center">
          <img v-if="oldFromUserIcon !== null" style="width:45px;" :alt="oldFromUser" :src="oldFromUserIcon">
          <i v-else class="fas fa-user-circle fa-3x"></i>
        <p style="overflow: hidden;text-overflow: ellipsis;white-space: nowrap;max-width: 100px;">{{ oldFromUser }}</p>
      </a>
      <div class="col text-center">
        <i class="fa-solid fa-arrow-right fa-3x"></i>
      </div>
      <a :href="oldToUserUrl" class="text-dark col text-center">
          <img v-if="oldToUserIcon !== null" style="width:45px;" :alt="oldToUser" :src="oldToUserIcon">
          <i v-else class="fas fa-user-circle fa-3x"></i>
        <p style="overflow: hidden;text-overflow: ellipsis;white-space: nowrap;max-width: 100px;">{{ oldToUser }}</p>
      </a>
    </div>
    
    <div v-if="fromUserUrl !== ''">
      <p><b>新宛名情報</b></p>
    </div>
    <div class="row" v-if="fromUserUrl !== ''">
      <a :href="fromUserUrl" class="text-dark col text-center">
          <img v-if="fromUserIcon !== null" style="width:45px;" :alt="fromUser" :src="fromUserIcon">
          <i v-else class="fas fa-user-circle fa-3x"></i>
        <p style="overflow: hidden;text-overflow: ellipsis;white-space: nowrap;max-width: 100px;">{{ fromUser }}</p>
      </a>
      <div class="col text-center">
        <i class="fa-solid fa-arrow-right fa-3x"></i>
      </div>
      <a :href="toUserUrl" class="text-dark col text-center">
          <img v-if="toUserIcon !== null" style="width:45px;" :alt="toUser" :src="toUserIcon">
          <i v-else class="fas fa-user-circle fa-3x"></i>
        <p style="overflow: hidden;text-overflow: ellipsis;white-space: nowrap;max-width: 100px;">{{ toUser }}</p>
      </a>
    </div>

    <div v-if="this.check">
      <qrcode-stream @decode="onDecode" @init="onInit" />
    </div>

    <div v-if="this.load" class="loader">
    </div>
    <div v-if="this.load">
      読込中
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
      load: false,
      fromUser: '',
      toUser: '',
      fromUserUrl: '',
      toUserUrl: '',
      fromUserIcon: '',
      toUserIcon: '',
      oldFromUser: '',
      oldToUser: '',
      oldFromUserUrl: '',
      oldToUserUrl: '',
      oldFromUserIcon: '',
      oldToUserIcon: ''
    }
  },

  methods: {
    onDecode (result) {
      this.result = ''
      this.check = false
      this.load = true

      axios.get('/bottle', {
          params: {
              qutteId: result,
              from: this.from,
          }
      })
      .then(response => {
        this.load = false
        if (response.data.qutteInfo.status == 'new') {
          this.result = 'Q手に宛名情報を登録しました。'
          this.fromUser = response.data.qutteInfo.fromUser
          this.toUser = response.data.qutteInfo.toUser
          this.fromUserUrl = response.data.qutteInfo.fromUserUrl
          this.toUserUrl = response.data.qutteInfo.toUserUrl
          this.fromUserIcon = response.data.qutteInfo.fromUserIcon
          this.toUserIcon = response.data.qutteInfo.toUserIcon
        } else if (response.data.qutteInfo.status == 'override') {
          this.result = 'Q手の宛名情報をを更新しました。'
          this.oldFromUser = response.data.qutteInfo.oldFromUser
          this.oldToUser = response.data.qutteInfo.oldToUser
          this.oldFromUserUrl = response.data.qutteInfo.oldFromUserUrl
          this.oldToUserUrl = response.data.qutteInfo.oldToUserUrl
          this.oldFromUserIcon = response.data.qutteInfo.oldFromUserIcon
          this.oldToUserIcon = response.data.qutteInfo.oldToUserIcon
          this.fromUser = response.data.qutteInfo.fromUser
          this.toUser = response.data.qutteInfo.toUser
          this.fromUserUrl = response.data.qutteInfo.fromUserUrl
          this.toUserUrl = response.data.qutteInfo.toUserUrl
          this.fromUserIcon = response.data.qutteInfo.fromUserIcon
          this.toUserIcon = response.data.qutteInfo.toUserIcon
        } else if (response.data.qutteInfo.status == 'done') {
          this.result = '使用済みのQ手です。正しいQ手でもう一度お試しください。'
          this.check = true
        } else if (response.data.qutteInfo.status == 'unknown') {
          this.result = '読み取られた二次元バーコードはQ手ではありません。正しいQ手でもう一度お試しください。'
          this.check = true
        } else if (response.data.qutteInfo.status == 'none') {
          this.result = '対象となるユーザが見つけられませんでした。'
        }
      })
      .catch(error => {
          console.log(error);
      })
    },

    async onInit (promise) {
      try {
        await promise
      } catch (error) {
        if (error.name === 'NotAllowedError') {
          this.error = "この端末ではQ手を読み取れません。 ERROR: you need to grant camera access permission"
        } else if (error.name === 'NotFoundError') {
          this.error = "この端末ではQ手を読み取れません。 ERROR: no camera on this device"
        } else if (error.name === 'NotSupportedError') {
          this.error = "この端末ではQ手を読み取れません。 ERROR: secure context required (HTTPS, localhost)"
        } else if (error.name === 'NotReadableError') {
          this.error = "この端末ではQ手を読み取れません。 ERROR: is the camera already in use?"
        } else if (error.name === 'OverconstrainedError') {
          this.error = "この端末ではQ手を読み取れません。 ERROR: installed cameras are not suitable"
        } else if (error.name === 'StreamApiNotSupportedError') {
          this.error = "この端末ではQ手を読み取れません。 ERROR: Stream API is not supported in this browser"
        } else if (error.name === 'InsecureContextError') {
          this.error = "この端末ではQ手を読み取れません。 ERROR: Camera access is only permitted in secure context. Use HTTPS or localhost rather than HTTP.";
        } else {
          this.error = `この端末ではQ手を読み取れません。 ERROR: Camera error (${error.name})`;
        }
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

.loader {
  color: #6c6b70;
  font-size: 90px;
  text-indent: -9999em;
  overflow: hidden;
  width: 1em;
  height: 1em;
  border-radius: 50%;
  margin: 72px auto;
  position: relative;
  -webkit-transform: translateZ(0);
  -ms-transform: translateZ(0);
  transform: translateZ(0);
  -webkit-animation: load6 1.7s infinite ease, round 1.7s infinite ease;
  animation: load6 1.7s infinite ease, round 1.7s infinite ease;
}
@-webkit-keyframes load6 {
  0% {
    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
  }
  5%,
  95% {
    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
  }
  10%,
  59% {
    box-shadow: 0 -0.83em 0 -0.4em, -0.087em -0.825em 0 -0.42em, -0.173em -0.812em 0 -0.44em, -0.256em -0.789em 0 -0.46em, -0.297em -0.775em 0 -0.477em;
  }
  20% {
    box-shadow: 0 -0.83em 0 -0.4em, -0.338em -0.758em 0 -0.42em, -0.555em -0.617em 0 -0.44em, -0.671em -0.488em 0 -0.46em, -0.749em -0.34em 0 -0.477em;
  }
  38% {
    box-shadow: 0 -0.83em 0 -0.4em, -0.377em -0.74em 0 -0.42em, -0.645em -0.522em 0 -0.44em, -0.775em -0.297em 0 -0.46em, -0.82em -0.09em 0 -0.477em;
  }
  100% {
    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
  }
}
@keyframes load6 {
  0% {
    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
  }
  5%,
  95% {
    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
  }
  10%,
  59% {
    box-shadow: 0 -0.83em 0 -0.4em, -0.087em -0.825em 0 -0.42em, -0.173em -0.812em 0 -0.44em, -0.256em -0.789em 0 -0.46em, -0.297em -0.775em 0 -0.477em;
  }
  20% {
    box-shadow: 0 -0.83em 0 -0.4em, -0.338em -0.758em 0 -0.42em, -0.555em -0.617em 0 -0.44em, -0.671em -0.488em 0 -0.46em, -0.749em -0.34em 0 -0.477em;
  }
  38% {
    box-shadow: 0 -0.83em 0 -0.4em, -0.377em -0.74em 0 -0.42em, -0.645em -0.522em 0 -0.44em, -0.775em -0.297em 0 -0.46em, -0.82em -0.09em 0 -0.477em;
  }
  100% {
    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
  }
}
@-webkit-keyframes round {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes round {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}

</style>