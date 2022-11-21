<template>
  <div>
    <label style="width: 100%;">
      <div 
        class="test2"
        data-toggle="modal" data-target="#test"
        v-show="defaultflg"
      >
      </div>
      <input
        type="file"
        name="image"
        accept="image/*"
        class="test"
        @change="setImage"
      >
      <input type="hidden" name="imageBase64" :value="cropImg">
    <img
      :src="cropImg"
      v-show="checkflg"
      style="width: 100%; height: auto; border: 1px solid gray"
      data-toggle="modal" data-target="#test"
      alt="Cropped Image"
    >
    </label>
      <!-- modal -->
      <div id="test" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
              <div class="modal-body" style="margin: 0 auto;">
                <div style="width: 80%; height:auto; border: 1px solid gray; display: inline-block;">
                  <vue-cropper
                    ref="cropper"
                    :guides="true"
                    :view-mode="2"
                    drag-mode="crop"
                    :auto-crop-area="0.5"
                    :min-container-width="250"
                    :min-container-height="180"
                    :background="true"
                    :rotatable="true"
                    :src="imgSrc"
                    alt="Source Image"
                    :img-style="{ 'width': '80%', 'height': 'auto' }"
                    :aspect-ratio="1"
                  ></vue-cropper>
                </div>
              </div>
              <div class="modal-footer justify-content-between">
                <a class="btn btn-outline-grey" data-dismiss="modal">キャンセル</a>
                <div @click="rotate" v-if="imgSrc != ''">右に回転</div>
                <div @click="cropImage" v-if="imgSrc != ''" style="margin-right: 40px;" class="btn btn-danger" data-dismiss="modal">調整</div>
              </div>
          </div>
        </div>
      </div>
      <!-- modal -->
  </div>
</template>

<script>
import VueCropper from "vue-cropperjs";
import "cropperjs/dist/cropper.css";
export default {
  components: {
    VueCropper
  },
    props: {
      seticon: {
        type: String,
        default: '',
      },
    },
  data() {
    return {
      imgSrc: "",
      cropImg: this.seticon,
      checkflg: false,
      defaultflg: true
    };
  },
  mounted() {
    if (this.cropImg != '') {
      this.checkflg = true;
      this.defaultflg = false;
    }
  },
  methods: {
    setImage(e) {
      const file = e.target.files[0];
      if (!file.type.includes("image/")) {
        alert("Please select an image file");
        return;
      }
      if (typeof FileReader === "function") {
        const reader = new FileReader();
        reader.onload = event => {
          this.imgSrc = event.target.result;
          // rebuild cropperjs with the updated source
          this.$refs.cropper.replace(event.target.result);
        };
        reader.readAsDataURL(file);
      } else {
        alert("Sorry, FileReader API not supported");
      }
    },
    cropImage() {
      // get image data for post processing, e.g. upload or setting image src
      this.cropImg = this.$refs.cropper.getCroppedCanvas().toDataURL();
      this.checkflg = true;
      this.defaultflg = false;
    },
    rotate() {
      // guess what this does :)
      this.$refs.cropper.rotate(90);
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.test {
    display: none;
}
.test2 {
    background-image: url('../../img/background.svg');
    width: 100%;
    height: 70vw;
    max-height: 300px;
    background-position: center;
    background-repeat: no-repeat;
    border: 1px solid gray;
}
</style>
