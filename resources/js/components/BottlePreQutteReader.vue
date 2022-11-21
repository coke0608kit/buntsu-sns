<template>
  <div @click="modal">
    <a class=" text-danger" data-toggle="modal" data-target="#modal-qutte">
      <i class="fa-solid fa-qrcode fa-3x mr-1"></i>
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
                <bottle-qutte-reader
                  :check = this.modalCheck
                  :from = this.from
                >
                </bottle-qutte-reader>
              </div>
          </div>
        </div>
      </div>
      <!-- modal -->
  </div>
</template>

<script>
import BottleQutteReader from './BottleQutteReader'
export default {
    components: {
        BottleQutteReader
    },
  props: {
    from: {
      type: String,
      default: '',
    }
  },
  data () {
    return {
      modalCheck: false,
    }
  },

  methods: {
    modal () {
      this.modalCheck = true
    },

    async onInit (promise) {
      try {
        await promise
      } catch (error) {
        if (error.name === 'NotAllowedError') {
          this.error = "ERROR: you need to grant camera access permission"
        } else if (error.name === 'NotFoundError') {
          this.error = "ERROR: no camera on this device"
        } else if (error.name === 'NotSupportedError') {
          this.error = "ERROR: secure context required (HTTPS, localhost)"
        } else if (error.name === 'NotReadableError') {
          this.error = "ERROR: is the camera already in use?"
        } else if (error.name === 'OverconstrainedError') {
          this.error = "ERROR: installed cameras are not suitable"
        } else if (error.name === 'StreamApiNotSupportedError') {
          this.error = "ERROR: Stream API is not supported in this browser"
        } else if (error.name === 'InsecureContextError') {
          this.error = 'ERROR: Camera access is only permitted in secure context. Use HTTPS or localhost rather than HTTP.';
        } else {
          this.error = `ERROR: Camera error (${error.name})`;
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
</style>