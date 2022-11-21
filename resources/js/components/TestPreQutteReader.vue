<template>
  <div @click="modal">
    <a class="d-flex text-danger" data-toggle="modal" data-target="#modal-qutte" 
      v-if="this.judge != 'success'">
      <i class="fa-solid fa-qrcode fa-3x mr-1"></i>
      <p>
        BUNTSUを利用するにはスマホのカメラが必要です。ここをクリックしてテストしてください。
      </p>
    </a>

      <!-- modal -->
      <div id="modal-qutte" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
              <div class="modal-body">
                <test-qutte-reader
                  :check = this.modalCheck
                  @judge="reflect"
                >
                </test-qutte-reader>
              </div>
          </div>
        </div>
      </div>
      <!-- modal -->
  </div>
</template>

<script>
import TestQutteReader from './TestQutteReader'
export default {
    components: {
        TestQutteReader
    },
  props: {
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
      modalCheck: false,
      judge: '',
    }
  },

  methods: {
    modal () {
      this.modalCheck = true
      this.$emit("status", 'true');
    },
    reflect (value) {
      this.judge = value
      this.$emit("judge", value);
    },
  }
}
</script>

<style scoped>
.error {
  font-weight: bold;
  color: red;
}
</style>